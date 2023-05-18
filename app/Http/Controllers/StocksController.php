<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Mail;
use App\Mail\StockQuoteEmail;
use App\Models\Stocks;
use Illuminate\Support\Facades\Http;

class StocksController extends Controller
{
    public function index()
    {
        $url = 'https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json';

        $client = new Client();
        $response = $client->get($url);

        if ($response->getStatusCode() === 200) {
            $companySymbols = [];
            $company_data = json_decode($response->getBody(), true);
            foreach($company_data as $index=>$cdata){
                $companySymbols[$cdata['Symbol']] = $cdata['Company Name']; 
            }
        } else {
            $companySymbols = [];
        }
        return view('stocks', compact('companySymbols'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'company_symbol' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'email' => 'required|email',
        ]);

        $companySymbol = $request->input('company_symbol');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $email = $request->input('email');

        $apiKey = '225d9e6ea2mshc6b75d2651f8504p1670dfjsn93cb338a4cf1';

        $client = new Client(['verify' => false]);
        
        try {
            $response = $client->get("https://yh-finance.p.rapidapi.com/stock/v3/get-historical-data?symbol=AMRN",
            [
                \GuzzleHttp\RequestOptions::HEADERS => array(
                    //'debug'        => false,
                    'X-RapidAPI-Key'  => $apiKey,
                    //'Content-Type' => 'application/json',
                    'X-RapidAPI-Host' => 'yh-finance.p.rapidapi.com',
                ),
            ]);    

            $data = json_decode($response->getBody(), true);
            
            $quotes = [];
            $timeSeries = $data['prices'];
            foreach ($timeSeries as $date => $quote) {
                $qdate = \Carbon\Carbon::parse($quote['date'])->format('Y-m-d');
                 if ($qdate >= $startDate && $qdate <= $endDate) {
                     $quotes[] = [
                         'date' => $qdate,
                         'open' => $quote['open'],
                         'high' => $quote['high'],
                         'low' => $quote['low'],
                         'close' => $quote['close'],
                         'volume' => $quote['volume'],
                     ];
                 }
            }

            // Save stock details to database
            $stock = new Stocks();
            $stock->company_symbol = $companySymbol;
            $stock->start_date = $startDate;
            $stock->end_date = $endDate;
            $stock->email = $email;
            $stock->save();

            // Send email
            $companyName = $companySymbol;
            $subject = $companyName . ' Stock Quotes';
            $body = "Start Date: $startDate\nEnd Date: $endDate";
            Mail::to($email)->send(new StockQuoteEmail($subject, $body));            

            // if (Mail::failures()) {
            //      dd(response()->Fail('Sorry! Mail not sent. Please try again later'));
            //  }else{
            //      dd(response()->success('Great! Successfully mail sent'));
            //  }
            return view('quotes', [
                'quotes' => $quotes,
            ]);
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Error fetching stock quotes. Please try again.');
        }
    }
}
