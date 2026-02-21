<?php

namespace Task7\Domain\Abstract;

use Task7\Domain\Enums\TaskStatus;
use Task7\Infrastructure\WebHook\WebHookWorker;

abstract class Model
{
    private WebHookWorker $webhookWorker;
    public function __construct()
    {
        $this->webhookWorker = new WebHookWorker();
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
            var_dump($result);

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

    public function add(array $data, string|null $idempotencyKey): array
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

        $instanceFields = get_class_vars(get_class($this));
        if (array_key_exists('id', $instanceFields)) {
            $result['id'] = uniqid();
        }


        $idempotencyKeysList = json_decode(file_get_contents(__DIR__ . '/../../' . 'idempotency' . '.json'), true);
        if ($idempotencyKeysList === null || strlen(trim($idempotencyKey ?? '')) === 0) {
            $previousData[] = $result;
            var_dump('create new. Key do not set or key list do not exist');
            if(strlen(trim($idempotencyKey ?? '')) > 0) {
                $idempotencyKeysList[] = [
                    'idempotencyKey' => $idempotencyKey,
                    'id' => $result['id'],
                ];
                file_put_contents(__DIR__ . '/../../' . 'idempotency.json', json_encode($idempotencyKeysList));
            }
            file_put_contents(__DIR__ . '/../../' . $tableName . '.json', json_encode($previousData, JSON_PRETTY_PRINT));
            return $result;
        }

        foreach ($idempotencyKeysList as $key) {
            if ($key['idempotencyKey'] === $idempotencyKey) {
                $result = $this->getById($key['id']);
                if (empty($result)) {
                    http_response_code(409);
                    header('Content-type: application/json');
                    echo json_encode(['status' => 'error', 'message' => 'Idempotency Key not found']);
                    return [];
                }
                var_dump("already exist. Return exist");
                return $result;
            }
        }
        var_dump('create new. Key dont found');
        $idempotencyKeysList[] = [
            'idempotencyKey' => $idempotencyKey,
            'id' => $result['id'],
        ];
        $previousData[] = $result;
        file_put_contents(__DIR__ . '/../../' . 'idempotency.json', json_encode($idempotencyKeysList));
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
        foreach ($previousData as &$row) {
            if ($row['id'] === $id) {
                $idValue = $row['id'];
                $row = $result;
                $row['id'] = $idValue;
                if(isset($row['status']))
                {
                    if($row['status'] === TaskStatus::Done->value)
                    {
                        $this->webhookWorker->work(
                            $row['id'],
                            $row['status'],
                            date('Y-m-d\TH:i:s\Z')
                        );
                    }
                }
            }
        }
        unset($row);
        file_put_contents(__DIR__ . '/../../' . $tableName . '.json', json_encode($previousData, JSON_PRETTY_PRINT));

        return $result;
    }

    public function deleteById(string $id): array
    {
        $tableName = get_class_vars(get_class($this))['tableName'] ?? null;
        if ($tableName === NULL) {
            var_dump("Table name not defined");
            return [];
        }
        $previousData = json_decode(file_get_contents(__DIR__ . '/../../' . $tableName . '.json'), true);

        foreach ($previousData as $key => $value) {
            if ($value['id'] === $id) {
                unset($previousData[$key]);
                file_put_contents(__DIR__ . '/../../' . $tableName . '.json', json_encode($previousData, JSON_PRETTY_PRINT));
                return [];
            }
        }
        http_response_code(404);
        var_dump("Not found");
        return [];
    }
}