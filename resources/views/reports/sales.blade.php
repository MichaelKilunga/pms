<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #4CAF50;
        }
        p {
            font-size: 14px;
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px 12px;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 12px;
            color: #555;
        }
        .total {
            font-weight: bold;
            text-align: right;
        }
    </style>
</head>
<body>
    <h1>Sales Report</h1>
    <p><strong>Report Type:</strong> {{ ucfirst($type) }}</p>
    <p><strong>For:</strong> {{ $value }}</p>

    <table>
        <thead>
        <!-- <tr>
                <th colspan="2"><strong>Report Type:</strong></th>
                <th>{{ ucfirst($type) }}</th>
                <th><strong>For:</strong></th>
                <th>{{ $value }}</th>
            </tr> -->
            <tr>
                <th>#</th>
                <th>Medicine name</th>
                <th>Sales Date</th>
                <th>Sales Quantity</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $sale)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $sale->item->name }}</td>
                    <td>{{ $sale->created_at }}</td>
                    <td>{{ $sale->quantity }}</td>
                    <td>{{ number_format($sale->total_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="total"><center>Total:</center></td>
                <td colspan="1">{{ number_format($sales->sum('total_price'), 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Generated on {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>
