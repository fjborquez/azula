<?php

namespace App;

enum ProductStatus: int
{
    case FRESH = 1;
    case APPROACHING_EXPIRY = 2;
    case EXPIRED = 3;
    case CONSUMED = 4;
    case DISCARDED = 5;
    case UNDEFINED = 6;
}
