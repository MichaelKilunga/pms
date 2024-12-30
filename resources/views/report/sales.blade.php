
<h1>Sales Report</h1>
<p>Report Type: {{ ucfirst($type) }}</p>
<p>For: {{ $value }}</p>

<table border="1">
    <thead>
        <tr>
            <th>#</th>
            <th>Pharmacy</th>
            <th>Item</th>
            <th>Staff</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($sales as $sale)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $sale->pharmacy->name }}</td>
                <td>{{ $sale->item->name }}</td>
                <td>{{ $sale->staff->name }}</td>
                <td>{{ $sale->quantity }}</td>
                <td>{{ $sale->total_price }}</td>
                <td>{{ $sale->date }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
