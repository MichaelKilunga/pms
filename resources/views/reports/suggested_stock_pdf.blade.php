<!DOCTYPE html>
<html>

    <head>
        <title>Suggested Stock Report</title>
        <style>
            body {
                font-family: sans-serif;
            }

            .header {
                text-align: center;
                margin-bottom: 20px;
            }

            .header h1 {
                margin: 0;
            }

            .header p {
                margin: 5px 0;
                color: #666;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            th,
            td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }

            th {
                background-color: #f2f2f2;
            }

            .badge {
                display: inline-block;
                padding: 3px 7px;
                font-size: 12px;
                font-weight: bold;
                color: #fff;
                border-radius: 10px;
            }

            .bg-danger {
                background-color: #dc3545;
            }

            .bg-warning {
                background-color: #ffc107;
                color: #000;
            }
        </style>
    </head>

    <body>
        <div class="header">
            <h1>Suggested Stock Report</h1>
            <p>Generated on {{ $date }}</p>
            <p>{{ session("pharmacy_name") }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Medicine Name</th>
                    <th>Suggested Qty</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                    <th>Supplier</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @foreach ($stocks as $index => $stock)
                    @php $grandTotal += $stock->total_buying_price; @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $stock->item->name ?? "N/A" }}</td>
                        <td>{{ number_format($stock->suggested_quantity) }}</td>
                        <td>{{ number_format($stock->unit_buying_price, 2) }}</td>
                        <td>{{ number_format($stock->total_buying_price, 2) }}</td>
                        <td>{{ $stock->supplier }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background-color: #f2f2f2; font-weight: bold;">
                    <td colspan="4" style="text-align: right;">Grand Total Estimation:</td>
                    <td colspan="2">{{ number_format($grandTotal, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </body>

</html>
