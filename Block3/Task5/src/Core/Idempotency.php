<?php

namespace Task5;


use Task5\Core\Sender;

class Idempotency
{
    public function __construct()
    {
    }


    private function compareTasks(array $currentTask, string $seekableTaskId, string $jsonStoragePath): bool
    {
        if (empty(array_diff($currentTask, new GetTaskById()->run($seekableTaskId, $jsonStoragePath)))) {
            return true;
        } else {
            Sender::SendJsonResponse(
                ['status' => 'error', 'message' => "Conflict"]
                , 409);
            return false;
        }
    }

    public function verify(
        string $currentIdempotencyKey,
        array  $task,
        string $idempotencyKeysStoragePath,
        string $jsonStoragePath): bool
    {
        $currentIdempotencyKey = trim($currentIdempotencyKey);
        if ($currentIdempotencyKey === '' && strlen($currentIdempotencyKey) > 0) {
            throw new \Exception("Idempotency key cannot be empty.");
        }

        $idempotencyKeysList = json_decode(file_get_contents($idempotencyKeysStoragePath), true);
        if (empty($idempotencyKeyList)) return true;

        foreach ($idempotencyKeysList as $idempotencyKeyElem => $value) {
            if ($currentIdempotencyKey === $value['key']) {
                return $this->compareTasks($task, $value['id'], $jsonStoragePath);
            }
            else
            {
                return true;
            }
        }



    }
}