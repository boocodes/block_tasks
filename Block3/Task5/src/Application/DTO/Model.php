<?php

namespace Task5\Application\DTO;

use Task5\Core\App;

abstract class Model
{
    protected array $required = [];
    protected string $tableName;

    private static function changeArrayFields(array $oldArray, array $newArray): array
    {
        foreach ($oldArray as $key => $value) {
            if (array_key_exists($key, $newArray)) {
                $oldArray[$key] = $newArray[$key];
            }
        }
        return $oldArray;
    }

    private static function bringInputDataToDefinite(array $inputData): array
    {
        $currentModelClassKeys = array_keys(get_class_vars(static::class));
        $creatingObjectKeys = array_keys($inputData);
        $equalsArrayKeys = array_intersect($currentModelClassKeys, $creatingObjectKeys);
        $creatingObject = [];
        foreach($equalsArrayKeys as $key){
            $creatingObject[$key] = $inputData[$key];
        }
        return $creatingObject;
    }


    public static function create(array $data): array
    {
        $currentObjectTableName = get_class_vars(static::class)['tableName'] ?? null;
        if($currentObjectTableName === null){
            throw new \Exception('Table name cannot be null');
        }
        $creatingObject = self::bringInputDataToDefinite($data);

        // check required fields
        $currentModelRequiredField = get_class_vars(static::class)['required'] ?? null;
      //  var_dump($currentModelRequiredField);
        if($currentModelRequiredField !== null){
            $differenceBetweenRequiredAndPresent = array_diff($currentModelRequiredField, array_keys($creatingObject));
            if(!empty($differenceBetweenRequiredAndPresent)){
                throw new \Exception('Required fields must be present: ' . implode(', ', $differenceBetweenRequiredAndPresent));
            }
            foreach ($creatingObject as $fieldName => $value) {
                if($value === null || strlen(trim($value)) === 0){
                    throw new \Exception('Required field - "' . $fieldName . '", is null or empty');
                }
            }
        }

        $previousData = json_decode(file_get_contents(App::getRootStoragePath() . $currentObjectTableName . '.json'), true);
        $previousData[] = $creatingObject;
        file_put_contents(App::getRootStoragePath() . $currentObjectTableName . '.json', json_encode($previousData, JSON_PRETTY_PRINT));
      //  var_dump($creatingObject);
        return $creatingObject;
    }

    public static function get(string $id): array
    {
        if(strlen(trim($id)) === 0){
            throw new \Exception('ID cannot be empty');
        }
        $currentObjectTableName = get_class_vars(static::class)['tableName'] ?? null;
        if($currentObjectTableName === null){
            throw new \Exception('Table name cannot be null');
        }
        $previousData = json_decode(file_get_contents(App::getRootStoragePath() . $currentObjectTableName . '.json'), true);
        $resultData = [];
        if($previousData === null){
            throw new \Exception('Table does not exist');
            return [];
        }
        foreach ($previousData as $key) {
            if(!isset($key['id'])) continue;
            if($key['id'] === $id){
                $resultData = $key;
            }
        }
        unset($key);
        var_dump($resultData);
        return $resultData;
    }

    public static function update(array $data): array
    {
        if(!isset($data['id'])){
            throw new \Exception('ID cannot be empty');
        }
        $currentObjectTableName = get_class_vars(static::class)['tableName'] ?? null;
        if($currentObjectTableName === null){
            throw new \Exception('Table name cannot be null');
        }
        $previousData = json_decode(file_get_contents(App::getRootStoragePath() . $currentObjectTableName  . '.json'), true);
        $creatingData = self::bringInputDataToDefinite($data);
        foreach ($previousData as &$key) {
            if($key['id'] === $creatingData['id']){
                $key = self::changeArrayFields($key, $creatingData);
            }
        }
        file_put_contents(App::getRootStoragePath() . $currentObjectTableName . '.json', json_encode($previousData, JSON_PRETTY_PRINT));
        return $creatingData;
    }

    public static function getAll(): array
    {
        $currentObjectTableName = get_class_vars(static::class)['tableName'] ?? null;
        if($currentObjectTableName === null){
            throw new \Exception('Table name cannot be null');
        }
        $previousData = json_decode(file_get_contents(App::getRootStoragePath() . $currentObjectTableName . '.json'), true);
        if($previousData === null){
            throw new \Exception('Table does not exist');
        }
        return $previousData;

    }

    public static function delete(string $id): void
    {
        if(strlen(trim($id)) === 0){
            throw new \Exception('ID cannot be empty');
        }
        $currentObjectTableName = get_class_vars(static::class)['tableName'] ?? null;
        if($currentObjectTableName === null){
            throw new \Exception('Table name cannot be null');
        }
        $previousData = json_decode(file_get_contents(App::getRootStoragePath() . $currentObjectTableName . '.json'), true);
        foreach ($previousData as $key => $value) {
            if(!isset($value['id'])) continue;
            if($value['id'] === $id){
                unset($previousData[$key]);
            }
        }
        file_put_contents(App::getRootStoragePath() . $currentObjectTableName . '.json', json_encode($previousData, JSON_PRETTY_PRINT));
        return;
    }

}