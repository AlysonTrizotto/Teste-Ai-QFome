<?php

namespace App\Enum;

enum HttpCodeEnum: int
{
    case OK = 200;
    case CREATED = 201;
    case NO_CONTENT = 204;
    case BAD_REQUEST = 400;
}
