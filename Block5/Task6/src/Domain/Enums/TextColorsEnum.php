<?php

namespace StorageTask6\Domain\Enums;


enum TextColorsEnum: string
{
    case BLACK = "0;30";
    case RED = "0;31";
    case GREEN = "0;32";
    case YELLOW = "0;33";
    case BLUE = "0;34";
    case MAGENTA = "0;35";
    case CYAN = "0;36";
    case WHITE = "0;37";
}