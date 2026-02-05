<?php

$allIdCount = 200_000;
$activeIdCount = 50_000;



$allUsers = [];

for($i = 0; $i < $allIdCount; $i++) {
    $allUsers[] = [
      "id" => $i,
      "username" => "user - " .$i,
    ];
}

//$activeId = range(1, $allIdCount);
//shuffle($activeId);
//$activeId = array_slice($activeId, 0, $activeIdCount);

$activeId = range(1, $activeIdCount);
shuffle($activeId);

function filterSlow(array $users, array $activeId): array
{
    $resultArray = [];
    foreach ($users as $user) {
        if(in_array($user['id'], $activeId)) {
            $resultArray[] = $user;
        }
    }
    return $resultArray;
}

function filterFast(array $users, array $activeId): array
{
    $resultArray = [];
    $activeIdSet = array_flip($activeId);

    foreach ($users as $user) {
        if(isset($activeIdSet[$user['id']])) {
            $resultArray[] = $user;
        }
    }
    return $resultArray;
}


echo "fast: " . "\n";
$startTime = microtime(true);
$result = filterFast($allUsers, $activeId);
echo "result: " . count($result) . "\n";
echo "time: " . (microtime(true) - $startTime) * 1000 . "ms\n";


echo "slow: " . "\n";
$startTime = microtime(true);
$result = filterSlow($allUsers, $activeId);
echo "result: " . count($result) . "\n";
echo "time: " . (microtime(true) - $startTime) * 1000 . "ms\n";


