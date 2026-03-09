<?php

namespace StorageTask3\Application\Utils;
use StorageTask3\Domain\Enums\BackgroundColorsEnum;
use StorageTask3\Domain\Enums\FontsStyleEnum;
use StorageTask3\Domain\Enums\TextColorsEnum;

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
    public static function progressBar(int $doneCount, int $totalCount, string|null $additionalText = null): void
    {
        $lineWidth = 20;
        $percentOfWork = round($doneCount / $totalCount * 100);
        $filled = round(($lineWidth * $doneCount) / $totalCount);
        $bar = str_repeat('=', $filled) . str_repeat(' ', $lineWidth - $filled);
        if($additionalText)
        {
            $output = sprintf("\r%s [%s] %d%% (%d/%d)", $additionalText, $bar, $percentOfWork, $doneCount, $totalCount);
        }
        else
        {
            $output = sprintf("\r[%s] %d%% (%d/%d)", $bar, $percentOfWork, $doneCount, $totalCount);
        }

        echo "\r". str_pad($output, 80, ' ');
        if($doneCount >= $totalCount) echo PHP_EOL;
    }
}