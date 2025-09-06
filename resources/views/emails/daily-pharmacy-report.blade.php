<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $message === 'daily' ? '' : '' }} Pharmacy Report - {{ $pharmacy->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 28px;
        }
        .header p {
            color: #666;
            margin: 5px 0 0;
            font-size: 16px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section h2 {
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .positive {
            color: #28a745;
            font-weight: bold;
        }
        .negative {
            color: #dc3545;
            font-weight: bold;
        }
        .status-good {
            color: #28a745;
        }
        .status-warning {
            color: #ffc107;
        }
        .status-danger {
            color: #dc3545;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $message === 'daily' ? 'Daily' : 'Custom' }} Pharmacy Report</h1>
            <p>{{ $pharmacy->name }} - {{ $reportDate }}</p>
        </div>

        <!-- Sales Summary Section -->
        <div class="section">
            <h2>📊 Sales Summary</h2>
            <table>
                <thead>
                    <tr>
                        <th>Metric</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Today Sales</td>
                        <td>TZS {{ number_format($salesSummary['total_revenue'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Today Stock Cost</td>
                        <td>TZS {{ number_format($salesSummary['total_cost'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Today Profit/Loss</td>
                        <td class="{{ $salesSummary['profit_loss'] >= 0 ? 'positive' : 'negative' }}">
                            TZS {{ number_format($salesSummary['profit_loss'], 2) }}
                            ({{ $salesSummary['profit_loss'] >= 0 ? 'Profit' : 'Loss' }})
                        </td>
                    </tr>
                    <tr>
                        <td>Today Transactions</td>
                        <td>{{ $salesSummary['total_transactions'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Stock Status Section -->
        <div class="section">
            <h2>📦 Stock Status Overview</h2>
            <table>
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Count</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="status-danger">Out of Stock</td>
                        <td>{{ $stockStatus['out_of_stock']->count() }}</td>
                        <td>{{ $stockStatus['out_of_stock']->count() > 0 ? round(($stockStatus['out_of_stock']->count() / ($stockStatus['out_of_stock']->count() + $stockStatus['low_stock']->count() + $stockStatus['expired']->count() + $stockStatus['good_stock']->count())) * 100, 1) : 0 }}%</td>
                    </tr>
                    <tr>
                        <td class="status-warning">Low Stock</td>
                        <td>{{ $stockStatus['low_stock']->count() }}</td>
                        <td>{{ $stockStatus['low_stock']->count() > 0 ? round(($stockStatus['low_stock']->count() / ($stockStatus['out_of_stock']->count() + $stockStatus['low_stock']->count() + $stockStatus['expired']->count() + $stockStatus['good_stock']->count())) * 100, 1) : 0 }}%</td>
                    </tr>
                    <tr>
                        <td class="status-danger">Expired</td>
                        <td>{{ $stockStatus['expired']->count() }}</td>
                        <td>{{ $stockStatus['expired']->count() > 0 ? round(($stockStatus['expired']->count() / ($stockStatus['out_of_stock']->count() + $stockStatus['low_stock']->count() + $stockStatus['expired']->count() + $stockStatus['good_stock']->count())) * 100, 1) : 0 }}%</td>
                    </tr>
                    <tr>
                        <td class="status-good">Good Stock</td>
                        <td>{{ $stockStatus['good_stock']->count() }}</td>
                        <td>{{ $stockStatus['good_stock']->count() > 0 ? round(($stockStatus['good_stock']->count() / ($stockStatus['out_of_stock']->count() + $stockStatus['low_stock']->count() + $stockStatus['expired']->count() + $stockStatus['good_stock']->count())) * 100, 1) : 0 }}%</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Detailed Stock Items -->
        @if($stockStatus['out_of_stock']->count() > 0)
        <div class="section">
            <h2>🚨 Out of Stock Items</h2>
            <table>
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Batch Number</th>
                        <th>Supplier</th>
                        <th>Expiry Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stockStatus['out_of_stock'] as $stock)
                    <tr>
                        <td>{{ $stock->item->name ?? 'N/A' }}</td>
                        <td>{{ $stock->batch_number }}</td>
                        <td>{{ $stock->supplier }}</td>
                        <td>{{ \Carbon\Carbon::parse($stock->expire_date)->format('M j, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if($stockStatus['low_stock']->count() > 0)
        <div class="section">
            <h2>⚠️ Low Stock Items</h2>
            <table>
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Current Stock</th>
                        <th>Total Stock</th>
                        <th>Low Stock</th>
                        <th>Batch Number</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stockStatus['low_stock'] as $stock)
                    <tr>
                        <td>{{ $stock->item->name ?? 'N/A' }}</td>
                        <td>{{ $stock->remain_Quantity }}</td>
                        <td>{{ $stock->quantity }}</td>
                        <td>{{ $stock->low_stock_percentage }}</td>
                        <td>{{ $stock->batch_number }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if($stockStatus['expired']->count() > 0)
        <div class="section">
            <h2>💀 Expired Items</h2>
            <table>
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Remaining Quantity</th>
                        <th>Expiry Date</th>
                        <th>Batch Number</th>
                        <th>Supplier</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stockStatus['expired'] as $stock)
                    <tr>
                        <td>{{ $stock->item->name ?? 'N/A' }}</td>
                        <td>{{ $stock->remain_Quantity }}</td>
                        <td class="status-danger">{{ \Carbon\Carbon::parse($stock->expire_date)->format('M j, Y') }}</td>
                        <td>{{ $stock->batch_number }}</td>
                        <td>{{ $stock->supplier }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <div class="footer">
            <p>This report was automatically generated on {{ now()->format('M j, Y \a\t g:i A') }}</p>  
            <p>© {{ $pharmacy->name }} - {{ config('app.name') }}  <br> <span style="color: #dc3545">Pharmacy Management System</span></p>
        </div>
    </div>
</body>
</html>
