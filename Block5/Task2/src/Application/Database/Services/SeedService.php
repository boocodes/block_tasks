<?php


namespace StorageTask2\Application\Database\Services;

use PDO;
use PDOException;
use StorageTask2\Application\Utils\CLHelper;
use StorageTask2\Domain\Enums\TextColorsEnum;
use StorageTask2\Domain\Interfaces\Seedable;


class SeedService
{
    private PDO $connection;
    private Seedable $seeder;

    public function __construct(Seedable $seeder, PDO $connection)
    {
        $this->seeder = $seeder;
        $this->connection = $connection;
    }

    public function seeding(array $currentMigrationBatch): void
    {
        if (empty($currentMigrationBatch)) {
            CLHelper::send("Current migration batch is empty. Nothing to do.", TextColorsEnum::RED);
            return;
        }
        $this->connection->exec("SET FOREIGN_KEY_CHECKS=0;");
        $sortedTablesForSeeding = $this->sortTablesByInheritance($currentMigrationBatch);
        CLHelper::send('Table for seeding: ' . implode(', ', $sortedTablesForSeeding), TextColorsEnum::GREEN);
        $seedTemplates = $this->seeder->run();
        foreach ($sortedTablesForSeeding as $table) {
            if(isset($seedTemplates[$table])) {
                $this->insertSeedingValues($table, $seedTemplates[$table]);
            }
        }
        $this->connection->exec("SET FOREIGN_KEY_CHECKS=1;");
    }


    private function insertSeedingValues(string $table, array $data)
    {
        if (!isset($data['count'])) {
            CLHelper::send('At seed: ' . $table . ', do not specified count size. Continue to next', TextColorsEnum::RED);
            return;
        }
        if (!isset($data['data'])) {
            CLHelper::send('At seed: ' . $table . ', do not specified data row. Continue to next', TextColorsEnum::RED);
            return;
        }

        $dataCount = $data['count'];
        $dataTemplate = $data['data'];

        $query = $this->connection->query("DESCRIBE `$table`");
        $tableColumns = $query->fetchAll(PDO::FETCH_COLUMN);

        $foreignKeyCache = $this->loadForeignKeyCache($table);

        for($i = 0; $i < $dataCount; $i++) {
            $rowData = $this->generateRowData($dataTemplate, $foreignKeyCache);

            $filteredData = [];
            foreach($rowData as $column => $value) {
                if(in_array($column, $tableColumns)) {
                    if($value instanceof \DateTime) {
                        $filteredData[$column] = $value->format('Y-m-d H:i:s');
                    }
                    else if(is_array($value)) {
                        $filteredData[$column] = json_encode($value);
                    }
                    else{
                        $filteredData[$column] = $value;
                    }
                }
            }

            if(empty($filteredData)) {
                CLHelper::send("There are not valid columns to insert into `$table`.", TextColorsEnum::RED );
                continue;
            }
            try{
                $currentId = $this->insertRow($table, $filteredData);
                if($currentId)
                {
                    $foreignKeyCache[$table][] = $currentId;
                }
                CLHelper::progressBar($i + 1, $dataCount);

            }
            catch (PDOException $e){
                CLHelper::send($e->getMessage(), TextColorsEnum::RED);
            }
        }
    }


    private function insertRow(string $table, array $data): int|null
    {
        $columns = array_keys($data);
        $placeholders = rtrim(str_repeat('?,', count($columns)), ',');
        $sql = "INSERT INTO `$table` (`" . implode('`, `', $columns) . "`) VALUES ($placeholders)";
        $query = $this->connection->prepare($sql);
        $query->execute(array_values($data));
        return $this->connection->lastInsertId() ?? null;
    }

    private function generateRowData(array $template, array &$foreignKeyCache): array
    {
        $rowData = [];
        foreach ($template as $column => $value) {
            if(is_callable($value)) {
                $rowData[$column] = $value($foreignKeyCache);
            }
            else if(is_array($value) && isset($value['type'])) {
                switch($value['type'])
                {
                    case 'foreign_key':
                        $tableName = $value['references'] ?? null;
                        if($tableName && !empty($foreignKeyCache[$tableName])) {
                            $keys = $foreignKeyCache[$tableName];
                            $rowData[$column] = $keys[array_rand($keys)];
                        }
                        else
                        {
                            try{
                                $query = $this->connection->prepare("SELECT id FROM `$tableName` ORDER BY id LIMIT 1");
                                $existId = $query->fetchColumn();
                                if($existId) {
                                    $rowData[$column] = $existId;
                                    if(!isset($foreignKeyCache[$tableName])) {
                                        $foreignKeyCache[$tableName] = [];
                                    }
                                    $foreignKeyCache[$tableName][] = $existId;
                                }
                                else
                                {
                                    CLHelper::send("Skipped. No records found in table `$tableName`.", TextColorsEnum::RED);
                                }
                            }
                            catch (PDOException $e){
                                CLHelper::send($e->getMessage(), TextColorsEnum::RED);
                            }
                        }
                        break;
                    case 'enum':
                        $rowData[$column] = $value['values'][array_rand($value['values'])];
                        break;
                    default:
                        $rowData[$column] = null;
                }
            }
            else
            {
                $rowData[$column] = $value;
            }
        }
        return $rowData;
    }

    private function loadForeignKeyCache(string $currentTable): array
    {
        $cache = [];
        $foreignKeys = $this->getForeignKeys($currentTable);
        foreach ($foreignKeys as $foreignKey) {
            $parentTable = $foreignKey['REFERENCED_TABLE_NAME'];
            try{
                $query = $this->connection->query("SELECT id FROM `$parentTable` Order BY id");
                $idList = $query->fetchAll(PDO::FETCH_COLUMN);
                if(!empty($idList)) {
                    $cache[$parentTable] = $idList;
                }
            }
            catch (PDOException $e){
                CLHelper::send($e->getMessage(), TextColorsEnum::RED);
            }
        }
        return $cache;
    }

    private function sortTablesByInheritance(array $tables): array
    {
        $sorted = [];
        $visited = [];
        foreach ($tables as $table) {
            $this->inheritanceSort($table, $visited, $sorted);
        }
        return $sorted;
    }

    private function inheritanceSort(string $table, &$visited, &$sorted): void
    {
        if (isset($visited[$table])) {
            return;
        }
        $visited[$table] = true;
        $foreignKeys = $this->getForeignKeys($table);

        foreach ($foreignKeys as $foreignKey) {
            $parentTable = $foreignKey['REFERENCED_TABLE_NAME'];
            if (!isset($visited[$parentTable])) {
                $this->inheritanceSort($parentTable, $visited, $sorted);
            }
        }
        $sorted[] = $table;
    }

    private function getForeignKeys(string $table): array
    {
        $sql = "
            SELECT 
                COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE 
                    TABLE_SCHEMA = DATABASE()
                    AND TABLE_NAME = ?
                    AND REFERENCED_TABLE_NAME IS NOT NULL
        ";
        $query = $this->connection->prepare($sql);
        $query->execute([$table]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}