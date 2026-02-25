<?php

namespace Task5\Domain\Abstract;

abstract class Model
{

    private function normalizeArrayForComparison(array $array): array
    {
        $normalized = [];
        foreach ($array as $key => $value) {
            if ($value instanceof \BackedEnum) {
                $normalized[$key] = $value->value;
            } else if ($value instanceof \UnitEnum) {
                $normalized[$key] = $value->name;
            } else {
                $normalized[$key] = $value;
            }
        }
        return $normalized;
    }

    private function checkRequiredData(array $data): bool
    {
        $requiredFields = get_class_vars(get_class($this))['required'] ?? [];
        if (empty($requiredFields)) return true;
        $inputDataKeys = array_keys($data);
        $result = array_diff($requiredFields, $inputDataKeys);
        if (!empty($result)) {
            var_dump('Required fields: ' . implode(', ', $result) . ' is empty');
            return false;
        }
        return true;
    }

    private function validateInputDataByInstance(array $data): array
    {
        $instanceFields = get_class_vars(get_class($this)) ?? [];
        $instanceFields = array_keys($instanceFields);
        if (empty($instanceFields)) return [];

        $result = [];

        foreach ($instanceFields as $instanceKey) {
            if (!isset($data[$instanceKey])) {
                continue;
            }
            $result[$instanceKey] = $data[$instanceKey];
        }
        return $result;
    }

    public function getAll(): array
    {
        $tableName = get_class_vars(get_class($this))['tableName'] ?? null;
        if ($tableName === NULL) {
            var_dump("Table name not defined");
            return [];
        }

        $result = file_get_contents(__DIR__ . '/../../' . $tableName . '.json');
        if ($result === false) {
            return [];
        } else {
            return json_decode($result, true);
        }
    }

    public function getById(string $id): array
    {
        $tableName = get_class_vars(get_class($this))['tableName'] ?? null;
        if ($tableName === NULL) {
            var_dump("Table name not defined");
            return [];
        }
        $previousData = json_decode(file_get_contents(__DIR__ . '/../../' . $tableName . '.json'), true);
        foreach ($previousData as $row) {
            if ($row['id'] === $id) {
                return $row;
            }
        }
        return [];
    }

    public function add(array $data): array
    {
        $tableName = get_class_vars(get_class($this))['tableName'] ?? null;
        if ($tableName === NULL) {
            var_dump("Table name not defined");
            return [];
        }
        $previousData = json_decode(file_get_contents(__DIR__ . '/../../' . $tableName . '.json'), true);
        if (!$this->checkRequiredData($data)) {
            return [];
        }
        $result = $this->validateInputDataByInstance($data);


        $currentIdempotencyKey = $_SERVER['HTTP_IDEMPOTENCY_KEY'] ?? null;

        $instanceFields = get_class_vars(get_class($this));

        if(array_key_exists('createdAt', $instanceFields)) {
            $result['createdAt'] = new \DateTimeImmutable()->format('c');
        }

        $newResultId = uniqid();

        $idempotencyKeysList = json_decode(file_get_contents(__DIR__ . '/../../idempotency.json') ?? [], true);

        if ($currentIdempotencyKey !== null) {
            $newIdempotencyKeyData = [
                'id' => $newResultId,
                'idempotency_key' => $currentIdempotencyKey,
            ];
            if (!empty($idempotencyKeysList)) {
                foreach ($idempotencyKeysList as $idempotencyKey => $idempotencyValue) {
                    if ($idempotencyValue['idempotency_key'] === $currentIdempotencyKey) {
                        $searchingEntity = $this->getById($idempotencyValue['id']);
                        $compareResult = array_intersect_key($searchingEntity, $result);
                        if ($this->normalizeArrayForComparison($compareResult) ==
                            $this->normalizeArrayForComparison($result)
                        ) {
                            return $searchingEntity;
                        } else if (empty($searchingEntity)) {
                            unset($idempotencyKeysList[$idempotencyKey]);
                            break;
                        } else {
                            return [];
                        }
                    }
                }
            }
            // not found
            $idempotencyKeysList[] = $newIdempotencyKeyData;
            file_put_contents(__DIR__ . '/../../idempotency.json', json_encode($idempotencyKeysList));
        }


        if(array_key_exists('id', $instanceFields)) {
            $result['id'] = $newResultId;
        }
        $previousData[] = $result;
        file_put_contents(__DIR__ . '/../../' . $tableName . '.json', json_encode($previousData, JSON_PRETTY_PRINT));
        return $result;
    }

    public function editById(string $id, array $data): array
    {
        $tableName = get_class_vars(get_class($this))['tableName'] ?? null;
        if ($tableName === NULL) {
            var_dump("Table name not defined");
            return [];
        }
        $previousData = json_decode(file_get_contents(__DIR__ . '/../../' . $tableName . '.json'), true);

        if (!$this->checkRequiredData($data)) {
            return [];
        }
        $result = $this->validateInputDataByInstance($data);
        $taskFoundFlag = false;
        foreach ($previousData as &$row) {
            if ($row['id'] === $id) {
                $taskFoundFlag = true;
                $idValue = $row['id'];
                $row = $result;
                $row['id'] = $idValue;
            }
        }
        if(!$taskFoundFlag) {
            return [];
        }
        unset($row);
        file_put_contents(__DIR__ . '/../../' . $tableName . '.json', json_encode($previousData, JSON_PRETTY_PRINT));
        return $result;
    }

    public function deleteById(string $id): bool
    {
        $tableName = get_class_vars(get_class($this))['tableName'] ?? null;
        if ($tableName === NULL) {
            var_dump("Table name not defined");
            return false;
        }
        $previousData = json_decode(file_get_contents(__DIR__ . '/../../' . $tableName . '.json'), true);

        foreach ($previousData as $key => $value) {
            if ($value['id'] === $id) {
                unset($previousData[$key]);
                file_put_contents(__DIR__ . '/../../' . $tableName . '.json', json_encode($previousData, JSON_PRETTY_PRINT));
                return true;
            }
        }
        var_dump("Not found");
        return false;
    }
}
