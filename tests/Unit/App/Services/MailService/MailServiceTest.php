<?php

namespace Tests\Unit\App\Services\AzulaServices\MailService;

use App\Contracts\Services\AangServices\HouseServiceInterface;
use App\Models\Inventory;
use App\Services\AzulaServices\MailService\MailService;
use GuzzleHttp\Psr7\Response as Psr7Response;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Client\Response;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class MailServiceTest extends TestCase
{
    private $mailService;

    private $mockedHouseService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockedHouseService = Mockery::mock(HouseServiceInterface::class);
        $this->mailService = new MailService($this->mockedHouseService);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_send(): void
    {
        $this->expectNotToPerformAssertions();
        $mockedInventory = Mockery::mock('alias:App\Models\Inventory');
        $mockedMail = Mockery::mock('alias:Illuminate\Support\Facades\Mail');
        $mockedInventory->shouldReceive('select')->andReturnSelf();
        $mockedInventory->shouldReceive('distinct')->andReturnSelf();
        $mockedInventory->shouldReceive('pluck')->andReturn([1, 2]);
        $mockedInventory->shouldReceive('where')->andReturnSelf();
        $mockedInventory->shouldReceive('whereBetween')->andReturnSelf();
        $mockedInventory->shouldReceive('get')->andReturn(Collection::make([
            new Inventory,
        ]));

        $data = ['persons' => [
            ['user' => ['email' => 'test@example.com']],
        ]];

        $this->mockedHouseService->shouldReceive('get')->andReturn(
            new Response(new Psr7Response(HttpFoundationResponse::HTTP_OK, [], json_encode($data))));
        $mockedMail->shouldReceive('to')->andReturnSelf();
        $mockedMail->shouldReceive('send')->andReturnNull();
        $this->mailService->send();
    }

    public function test_send_house_service_fails(): void
    {
        $this->expectNotToPerformAssertions();
        $mockedInventory = Mockery::mock('alias:App\Models\Inventory');
        $mockedInventory->shouldReceive('select')->andReturnSelf();
        $mockedInventory->shouldReceive('distinct')->andReturnSelf();
        $mockedInventory->shouldReceive('pluck')->andReturn([1, 2]);

        $this->mockedHouseService->shouldReceive('get')->andReturn(
            new Response(new Psr7Response(HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR))
        );

        $this->mailService->send();
    }

    public function test_send_no_person_found(): void
    {
        $this->expectNotToPerformAssertions();
        $mockedInventory = Mockery::mock('alias:App\Models\Inventory');
        $mockedInventory->shouldReceive('select')->andReturnSelf();
        $mockedInventory->shouldReceive('distinct')->andReturnSelf();
        $mockedInventory->shouldReceive('pluck')->andReturn([1, 2]);
        $mockedInventory->shouldReceive('where')->andReturnSelf();
        $mockedInventory->shouldReceive('whereBetween')->andReturnSelf();
        $mockedInventory->shouldReceive('get')->andReturn(Collection::make([
            new Inventory,
        ]));

        $data = ['persons' => []];
        $this->mockedHouseService->shouldReceive('get')->andReturn(
            new Response(new Psr7Response(HttpFoundationResponse::HTTP_OK, [], json_encode($data))));
        $this->mailService->send();
    }

    public function test_send_mail_exception(): void
    {
        $this->expectException(\Exception::class);
        $mockedInventory = Mockery::mock('alias:App\Models\Inventory');
        $mockedMail = Mockery::mock('alias:Illuminate\Support\Facades\Mail');
        $mockedInventory->shouldReceive('select')->andReturnSelf();
        $mockedInventory->shouldReceive('distinct')->andReturnSelf();
        $mockedInventory->shouldReceive('pluck')->andReturn([1, 2]);
        $mockedInventory->shouldReceive('where')->andReturnSelf();
        $mockedInventory->shouldReceive('whereBetween')->andReturnSelf();
        $mockedInventory->shouldReceive('get')->andReturn(Collection::make([
            new Inventory,
        ]));

        $data = ['persons' => [
            ['user' => ['email' => 'test@example.com']],
        ]];
        $this->mockedHouseService->shouldReceive('get')->andReturn(
            new Response(new Psr7Response(HttpFoundationResponse::HTTP_OK, [], json_encode($data))));
        $mockedMail->shouldReceive('to')->andReturnSelf();
        $mockedMail->shouldReceive('send')->andThrow(new \Exception('Mail error'));
        $this->mailService->send();
    }
}
