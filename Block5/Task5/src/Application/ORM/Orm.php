<?php
namespace StorageTask5\Application\ORM;
use PDO;
use PDOException;
use StorageTask5\Application\Utils\CLHelper;
use StorageTask5\Domain\Enums\TextColorsEnum;

class ORM{

    private PDO $connection;
    private $queriesDirectory = __DIR__ . '\\..\\..\\..\\database\\queries\\';

    public function __construct(PDO $connection){
        $this->connection = $connection;
    }
    public function selectQuery(string $queryFile, array|null $inputData = null): array
    {
        $result = [];
        try{
            $query = $this->getQuery($queryFile);
            $queryStatement = $this->connection->prepare($query);
            if($inputData !== null){
                $queryStatement->execute($inputData);
            }
            else
            {
                $queryStatement->execute();
            }
            $result = $queryStatement->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e){
            CLHelper::send($e->getMessage(), TextColorsEnum::RED);
        }
        return $result;
    }
    public function getQuery(string $queryFile)
    {
        $allQueriesDirFilesArray = scandir($this->queriesDirectory);
        foreach ($allQueriesDirFilesArray as $key => $value) {
            if (str_ends_with($value, ".sql") === false) {
                unset($allQueriesDirFilesArray[$key]);
                continue;
            }
            if($queryFile == $value){
                return file_get_contents($this->queriesDirectory . $value, true);
            }
        }

    }
}