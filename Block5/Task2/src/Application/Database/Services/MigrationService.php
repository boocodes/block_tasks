<?php


namespace StorageTask2\Application\Database\Services;

use PDO;
use PDOException;
use StorageTask2\Application\Utils\CLHelper;
use StorageTask2\Domain\Enums\BackgroundColorsEnum;
use StorageTask2\Domain\Enums\FontsStyleEnum;
use StorageTask2\Domain\Enums\TextColorsEnum;


class MigrationService
{
    private PDO $connection;
    private int $highestBatchValue = 0;
    private string $migrationTableName = "migrations";
    private string $migrationsDirectory = __DIR__ . '\\..\\..\\..\\..\\database\\migrations';

    public function setConnection(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    private function formatSQLmigrationFiles()
    {
        $allMigrationDirFilesArray = scandir($this->migrationsDirectory);
        foreach ($allMigrationDirFilesArray as $key => $value) {
            if (str_ends_with($value, ".sql") === false) {
                unset($allMigrationDirFilesArray[$key]);
                continue;
            }
            $allMigrationDirFilesArray[$key] = str_replace(".sql", "", $allMigrationDirFilesArray[$key]);
        }

        return array_values($allMigrationDirFilesArray);
    }

    private function prepareMigrationTable()
    {
        $migrationTableQuery = "CREATE TABLE IF NOT EXISTS " . $this->migrationTableName . " (id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY , batch INT UNSIGNED NOT NULL, migration varchar(255) NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL);";
        $this->connection->exec($migrationTableQuery);
    }

    public function migrateSpecific(string $migrationName): bool
    {
        $this->prepareMigrationTable();
        if (str_ends_with($migrationName, ".sql") === true) {
            $migrationName = str_replace(".sql", "", $migrationName);
        }
        $alreadyMigrated = $this->getAlreadyMigratedMigrations();
        if (!file_exists($this->migrationsDirectory . "/" . $migrationName . ".sql")) {
            CLHelper::send("Specified migration does not exist at " . $this->migrationsDirectory, TextColorsEnum::RED);
            return false;
        }
        CLHelper::send("Started migration: " . $migrationName . '.sql', TextColorsEnum::YELLOW);
        $migrationSQLData = file_get_contents($this->migrationsDirectory . "/" . $migrationName . ".sql");

        try {
            $res = $this->connection->exec($migrationSQLData);
            $alreadyMigrated = $this->getAlreadyMigratedMigrations();
            if (in_array($migrationName, $alreadyMigrated)) {
                CLHelper::send("Migration already uploaded", TextColorsEnum::RED);
                return false;
            }
            $createMigrationFieldQuery = "INSERT INTO " . $this->migrationTableName . " (`id`, `batch`, `migration`, `created_at`) VALUES (NULL, " . $this->highestBatchValue + 1 . ", '" . $migrationName . "', CURRENT_TIMESTAMP) ORDER By batch;";

            $this->connection->beginTransaction();
            $this->connection->exec($migrationSQLData);
            $this->connection->exec($createMigrationFieldQuery);
            $this->connection->commit();

            CLHelper::send("Migration upload successfully", TextColorsEnum::GREEN);

        } catch (PDOException $e) {
            CLHelper::send("Error: " . $e->getMessage(), TextColorsEnum::RED);
        }
        return true;
    }

    public function migrate(): array
    {
        $this->prepareMigrationTable();

        return $this->applyMigrationsFromSQL($this->removeAlreadyMigrated());
    }

    public function getMigrateListAndDisplay(): void
    {
        $alreadyMigrated = $this->getAlreadyMigratedMigrationsFullPart();
        if (empty($alreadyMigrated)) {
            CLHelper::send("No migrations were found", TextColorsEnum::GREEN);
            return;
        }
        CLHelper::send("Already migrate list:", TextColorsEnum::GREEN);
        CLHelper::send("Batch - migration: created_at:", TextColorsEnum::YELLOW);
        foreach ($alreadyMigrated as $key) {
            CLHelper::send($key['batch'] . " - " . $key['migration'] . ": " . $key['created_at'], TextColorsEnum::YELLOW);
        }
    }

    public function getHelpInfoCLI(): void
    {
        CLHelper::send("Help info:", TextColorsEnum::GREEN);
        CLHelper::send("Available commands: ", TextColorsEnum::YELLOW);
        CLHelper::send("migrate:", TextColorsEnum::WHITE, BackgroundColorsEnum::CYAN);
        CLHelper::send("Start migration all files from database/migrations", TextColorsEnum::YELLOW);
        CLHelper::send("migrate:rollback", TextColorsEnum::WHITE, BackgroundColorsEnum::CYAN);
        CLHelper::send("Start rollback all migrated files by batch value (reverse order)", TextColorsEnum::YELLOW);
        CLHelper::send("migrate:specified", TextColorsEnum::WHITE, BackgroundColorsEnum::CYAN);
        CLHelper::send("Run single and specified migration file. You will be asked to enter migration file name with file format or without", TextColorsEnum::YELLOW);
        CLHelper::send("migrate:list", TextColorsEnum::WHITE, BackgroundColorsEnum::CYAN);
        CLHelper::send("Display all already migrated files from migration database table", TextColorsEnum::YELLOW);
        CLHelper::send("migrate:next", TextColorsEnum::WHITE, BackgroundColorsEnum::CYAN);
        CLHelper::send("Display all next batch migrations", TextColorsEnum::YELLOW);
        CLHelper::send("Additional info: ", TextColorsEnum::YELLOW);
        CLHelper::send("\t- Migration files must be named like own database tables!", TextColorsEnum::YELLOW);
    }

    private function applyMigrationsFromSQL(array $migrationsArray): array
    {
        if (empty($migrationsArray)) {
            CLHelper::send("Nothing to migrate", TextColorsEnum::RED);
            return [];
        }
        CLHelper::send("Starting migrations: " . implode(', ', $migrationsArray), TextColorsEnum::YELLOW);
        // Initial creating migrations without foreign keys
        foreach ($migrationsArray as $migrationFile) {
            $sqlContent = file_get_contents($this->migrationsDirectory . '/' . $migrationFile . '.sql');
            $sqlContent = $this->removeForeignKey($sqlContent);
            CLHelper::send("Creating table: " . $migrationFile, TextColorsEnum::YELLOW);
            $this->connection->exec($sqlContent);
        }
        // Foreign keys setting
        foreach ($migrationsArray as $migrationFile) {
            $sqlContent = file_get_contents($this->migrationsDirectory . '/' . $migrationFile . '.sql');
            $alterStatement = $this->uploadForeignKey($sqlContent, $migrationFile);
            foreach ($alterStatement as $alterField) {
                $this->connection->exec($alterField);
            }
        }
        //
        foreach ($migrationsArray as $migrationFile) {
            $migrateFieldNewValue = "INSERT INTO `migrations` (`id`, `batch`, `migration`, `created_at`) VALUES (NULL, '" . $this->highestBatchValue + 1 . "', '" . $migrationFile . "', CURRENT_TIMESTAMP)";
            CLHelper::send("Creating migration field: " . $migrationFile, TextColorsEnum::YELLOW);
            $this->connection->exec($migrateFieldNewValue);
        }
        CLHelper::send("Migration upload successfully", TextColorsEnum::GREEN);
        return $migrationsArray;
    }

    private function uploadForeignKey(string $sqlQuery, string $sqlFileName): array
    {
        $alterStatement = [];
        preg_match_all('/FOREIGN KEY\s*\(([^)]+)\)\sREFERENCES\s*(\w+)\s*\(([^)]+)\)/i', $sqlQuery, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $column = trim($match[1]);
            $refTable = trim($match[2]);
            $refColumn = trim($match[3]);
            $fkName = "fk_{$sqlFileName}_{$column}";
            $alterSQL = "ALTER TABLE {$sqlFileName} ADD CONSTRAINT {$fkName} FOREIGN KEY ({$column}) REFERENCES {$refTable} ({$refColumn})";
            $alterStatement[] = $alterSQL;
        }

        return $alterStatement;
    }

    public function displayNextBatch(): void
    {
        $localMigrationsFromDisk = $this->formatSQLmigrationFiles();
        $alreadyMigrated = $this->getAlreadyMigratedMigrations();
        $nextMigration = array_diff($localMigrationsFromDisk, $alreadyMigrated);
        if (empty($nextMigration)) {
            CLHelper::send("All migrations from database/migrations were been migrated. Nothing new", TextColorsEnum::GREEN);
            return;
        }
        CLHelper::send("At next migration will be migrated next files: ", TextColorsEnum::YELLOW);
        CLHelper::send("Next batch: " . $this->highestBatchValue + 1, TextColorsEnum::YELLOW);
        foreach ($nextMigration as $migrationFile) {
            CLHelper::send("Migration file: " . $migrationFile, TextColorsEnum::YELLOW);
        }
        CLHelper::send("Total: " . count($nextMigration), TextColorsEnum::GREEN);
    }

    private function removeForeignKey(string $sqlQuery): string
    {
        $sqlQuery = preg_replace('/,\s*FOREIGN KEY\s*\([^)]+\)\s*REFERENCES\s*[^)]+\)/i', '', $sqlQuery);
        $sqlQuery = preg_replace('/FOREIGN KEY\s*\([^)]+\)\s*REFERENCES\s*[^)]+\)\s*,?\s*/i', '', $sqlQuery);
        $sqlQuery = preg_replace('/,\s*\)/', ')', $sqlQuery);
        return $sqlQuery;
    }

    private function removeAlreadyMigrated(): array
    {
        $userMigrations = $this->formatSQLmigrationFiles();
        $oldPackageMigrationsNames = $this->getAlreadyMigratedMigrations();

        return array_diff($userMigrations, $oldPackageMigrationsNames);
    }

    private function getAlreadyMigratedMigrations(): array
    {
        $alreadyMigratedData = $this->connection->query("SELECT * FROM " . $this->migrationTableName)->fetchAll(PDO::FETCH_ASSOC);
        $oldMigrationsNamesArray = [];
        foreach ($alreadyMigratedData as $migrationData) {
            if ($migrationData['batch'] > $this->highestBatchValue) {
                $this->highestBatchValue = $migrationData['batch'];
            }
            $oldMigrationsNamesArray[] = $migrationData['migration'];
        }
        return $oldMigrationsNamesArray;
    }

    private function getAlreadyMigratedMigrationsFullPart(): array
    {
        $alreadyMigratedData = $this->connection->query("SELECT * FROM " . $this->migrationTableName)->fetchAll(PDO::FETCH_ASSOC);
        foreach ($alreadyMigratedData as $migrationData) {
            if ($migrationData['batch'] > $this->highestBatchValue) {
                $this->highestBatchValue = $migrationData['batch'];
            }
        }
        return $alreadyMigratedData;
    }

    public function deleteMigrationField(string $migrationName): void
    {
        $query = "DELETE FROM `migrations` WHERE migration = '" . $migrationName . "';";
        $this->connection->exec($query);
    }

    public function deleteTable(string $tableName): void
    {
        $query = 'DROP TABLE IF EXISTS `' . $tableName . '`;';
        $this->connection->exec($query);
    }

    public function migrateRollback(): void
    {
        CLHelper::send("Starting rollback migrations", TextColorsEnum::YELLOW);

        $alreadyMigrated = $this->getAlreadyMigratedMigrationsFullPart();
        if (empty($alreadyMigrated)) {
            CLHelper::send("Nothing to rollback", TextColorsEnum::RED);
            return;
        }
        $this->connection->exec('SET FOREIGN_KEY_CHECKS = 0');
        try {
            foreach ($alreadyMigrated as $migrationData) {
                if ($migrationData['batch'] === $this->highestBatchValue) {
                    $this->deleteMigrationField($migrationData['migration']);
                    $this->deleteTable($migrationData['migration']);
                    CLHelper::send("Migration: " . $migrationData['migration'], TextColorsEnum::YELLOW);
                }
            }
        } catch (PDOException $e) {
            CLHelper::send("Error: " . $e->getMessage(), TextColorsEnum::RED);
        }
        CLHelper::send("Rollback finished", TextColorsEnum::GREEN);
    }
}