<?php

namespace API;


enum URLMethodEnum: string
{
    case GET = "GET";
    case POST = "POST";
    case PUT = "PUT";
    case PATCH = "PATCH";
}