<!DOCTYPE html>

<html>

<head>
    <title>Scraped Data</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 40px;
            background: #f4f6f8;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007BFF;
            color: #fff;
            text-transform: uppercase;
            font-size: 14px;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .success {
            color: green;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .btn:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>


    <h2>Scraped Data</h2>

    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @if ($items->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Title</th>
                    <th>Star Rating</th>
                    <th>Price</th>
                    {{-- //<th>Stock</th> --}}
                    {{-- <th>Created At</th> --}}
                </tr>
            </thead>

            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->product_title }}</td>
                        <td>{{ $item->product_star_rating }}</td>
                        <td>{{ $item->product_price }}</td>
                        {{-- <td>{{ $item->product_stock }}</td> --}}
                        {{-- <td>{{ $item->created_at->format('Y-m-d H:i') }}</td> --}}
                    </tr>
                @endforeach
            </tbody>

        </table>

        <a href="{{ route('demo.export.excel') }}" class="btn">Download Excel</a>
    @else
        <p>No data scraped yet.</p>
    @endif


</body>

</html>
