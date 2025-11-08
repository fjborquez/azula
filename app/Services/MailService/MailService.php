<?php

namespace App\Services\MailService;

use App\Contracts\Services\AangServices\HouseServiceInterface;
use App\Contracts\Services\MailService\MailServiceInterface;
use App\Mail\ExpiredInventory;
use App\Models\Inventory;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class MailService implements MailServiceInterface
{
    public function __construct(
        private readonly HouseServiceInterface $houseService
    ) {}

    public function send(): void
    {
        $housesIds = Inventory::select('house_id')->distinct()->pluck('house_id');

        foreach($housesIds as $houseId) {
            $houseResponse = $this->houseService->get($houseId);

            if ($houseResponse->failed()) {
                continue;
            }

            $houseData = $houseResponse->json();
            $person = null;

            foreach ($houseData['persons'] as $posiblePerson) {
                if (array_key_exists('user', $posiblePerson)) {
                    $person = $posiblePerson;
                    break;
                }
            }

            $inventories = Inventory::where('house_id', $houseId)
                ->whereBetween('expiration_date', [now(), now()->addDays(5)])
                ->get();

            if ($inventories->isEmpty() || is_null($person)) {
                continue;
            }

            try {
                Mail::to($person['user']['email'])->send(new ExpiredInventory(
                    $inventories, $person, $houseData));
            } catch (\Exception $e) {
                Log::error('Error al enviar correo: ' . $e->getMessage());
            }
        }
    }
}
