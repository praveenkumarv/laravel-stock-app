<!-- resources/views/quotes.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Stock Quotes</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
        $(".hBack").on("click", function(e){
            e.preventDefault();
            window.history.back();
        });
    });
    </script>
</head>
<body>
<div class="container">
    <h2>Stock Quotes</h2>
    <div>
        <canvas id="stockChart"></canvas>
    </div>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Date</th>
                <th>Open</th>
                <th>High</th>
                <th>Low</th>
                <th>Close</th>
                <th>Volume</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($quotes as $quote)
                <tr>
                    <td>{{ $quote['date'] }}</td>
                    <td>{{ $quote['open'] }}</td>
                    <td>{{ $quote['high'] }}</td>
                    <td>{{ $quote['low'] }}</td>
                    <td>{{ $quote['close'] }}</td>
                    <td>{{ $quote['volume'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        <button class="btn btn-primary hBack" type="button">Back</button>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Retrieve the stock quotes from the PHP variable passed to the view
    const quotes = {!! json_encode($quotes) !!};

    // Extract the dates, open prices, and close prices from the quotes
    const dates = quotes.map(quote => quote.date);
    const openPrices = quotes.map(quote => quote.open);
    const closePrices = quotes.map(quote => quote.close);

    // Create the chart using Chart.js
    const ctx = document.getElementById('stockChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Open Price',
                data: openPrices,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1,
                fill: false,
            }, {
                label: 'Close Price',
                data: closePrices,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                fill: false,
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Date'
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Price'
                    }
                }
            }
        }
    });
</script>
</html>
