<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StocksControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testSubmitStocksForm()
    {
        $response = $this->post('/stocks', [
            'company_symbol' => 'GOOG',
            'start_date' => '2022-01-01',
            'end_date' => '2022-01-31',
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('stocks');
        $response->assertSee('Stock Quotes');

        $this->assertDatabaseHas('stock_quotes', [
            'company_symbol' => 'GOOG',
            'start_date' => '2022-01-01',
            'end_date' => '2022-01-31',
            'email' => 'test@example.com',
        ]);

        $this->assertDatabaseCount('stock_quotes', 1);

        $this->assertDatabaseHas('emails', [
            'to' => 'test@example.com',
            'subject' => 'Google',
            'body' => 'Start Date: 2022-01-01, End Date: 2022-01-31',
        ]);
    }
}

