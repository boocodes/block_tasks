<?php

namespace StorageTask4\Application\Utils;
use BackedEnum;
use UnitEnum;

class RandomValues
{
    private function __construct()
    {
    }
    public static function getRandomValueFromEnum(string $enum): string
    {
        if(!enum_exists($enum)){
            throw new \Exception('Enum ' . $enum . ' does not exist');
        }
        $cases = $enum::cases();
        $randomCase = $cases[array_rand($cases)];
        return $randomCase instanceof BackedEnum ? $randomCase->value : $randomCase->name;
    }
}