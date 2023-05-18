<!-- resources/views/stocks.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Stock App</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
  <h2>Get Quotes - Form</h2>
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('stocks.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="company_symbol">Company Symbol:</label>
            <select name="company_symbol" class="form-control" required>
                @foreach ($companySymbols as $symbol => $name)
                    <option value="{{ $symbol }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" class="form-control" placeholder="Enter email" required>
        </div>
        <div>
            <button type="submit" class="btn btn-default">Get Quotes</button>
        </div>
    </form>
</body>
</html>
