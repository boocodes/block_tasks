<?php

namespace Task4\Domain\Enums;


enum HttpMethods: string
{
    case GET = 'get';
    case POST = 'post';
    case PUT = 'put';
    case DELETE = 'delete';
    case PATCH = 'patch';
    case OPTIONS = 'options';
}