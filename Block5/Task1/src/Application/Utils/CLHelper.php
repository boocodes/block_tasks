<?php

namespace StorageTask\Application\Utils;
use StorageTask\Domain\Enums\BackgroundColorsEnum;
use StorageTask\Domain\Enums\FontsStyleEnum;
use StorageTask\Domain\Enums\TextColorsEnum;

class CLHelper
{
    private function __construct()
    {
    }
    public static function send(string $message, ?TextColorsEnum $color = null, ?BackgroundColorsEnum $backgroundColor = null, ?FontsStyleEnum $style = null): void
    {
        $asciStyle = [];
        if($color) {$asciStyle[] = $color->value;}
        if($backgroundColor) {$asciStyle[] = $backgroundColor->value;}
        if($style) {$asciStyle[] = $style->value;}
        if(empty($asciStyle))
        {
            echo $message;
        }
        else
        {
            echo sprintf("\033[%sm%s\033[0m", implode(';', $asciStyle), $message) . PHP_EOL;
        }
    }
    public static function get(string $placeholder): string
    {
        return readline($placeholder);
    }
}