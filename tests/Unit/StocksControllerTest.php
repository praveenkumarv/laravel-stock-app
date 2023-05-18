<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

//use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\StockQuoteEmail;

class StocksControllerTest extends TestCase
{
    //use RefreshDatabase;

    public function testIndex()
    {
        $response = $this->get('/quotes');

        $response->assertStatus(200);
        $response->assertViewIs('quotes');
    }

    public function testRetrieveQuotes()
    {
        // Perform a test request with sample data
        $response = $this->post('/quotes', [
            'company_symbol' => 'GOOG',
            'start_date' => '2022-01-01',
            'end_date' => '2022-01-31',
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('quotes');
        $response->assertSee('Stock Quotes');

        // Assert that the email was sent
        Mail::assertSent(StockQuoteEmail::class, function ($mail) {
            return $mail->hasTo('test@example.com');
        });
    }

    public function testRetrieveQuotesWithInvalidData()
    {
        // Perform a test request with invalid data
        $response = $this->post('/quotes', [
            'company_symbol' => 'INVALID',
            'start_date' => '2022-01-01',
            'end_date' => '2022-01-31',
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/quotes');
        $response->assertSessionHasErrors(['company_symbol']);
    }
}
