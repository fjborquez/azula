<?php

namespace App\Services\AangServices;

use App\Contracts\Services\AangServices\HouseServiceInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class HouseService implements HouseServiceInterface
{
    public function get(int $houseId): Response
    {
        return Http::accept('application/json')->retry(3, 100, null, false)->get(Config::get('aang.url').'/house/'.$houseId);
    }
}
