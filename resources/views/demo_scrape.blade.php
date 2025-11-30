<!DOCTYPE html>
<html>
<head>
    <title>Scraped Data</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .item { border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; }
        .success { color: green; margin-bottom: 10px; }
    </style>
</head>
<body>

    <h2>Scraped Data</h2>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @if($items->count() > 0)
        @foreach ($items as $item)
            <div class="item">
                {{ $item->text }}
            </div>
        @endforeach
    @else
        <p>No data scraped yet.</p>
    @endif

</body>
</html>
