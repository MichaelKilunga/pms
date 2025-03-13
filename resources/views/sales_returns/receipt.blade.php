<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Receipt</title>
    <style>
        @page {
            size: 85mm auto;
            margin: 0; 
        }
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .receipt {
            width: 300px;
            margin: auto;
            padding: 10px;
            border: 1px solid #000;
        }
        .receipt h2 {
            margin: 5px 0;
        }
        .receipt .line {
            border-bottom: 1px dashed #000;
            margin: 5px 0;
        }
        .hidden {
            display: none;
        }
    </style>
    <script>
        window.onload = function() {
            window.print();
            setTimeout(() => window.location.href = '{{ route('sales') }}', 0);
        };
    </script>
</head>
<body>

<div class="receipt">
    <h2>Medicine Purchase Receipt</h2>
    <div class="line"></div>
    <p><strong>Date:</strong> {{ $receipt->date }}</p>
    <p><strong>Pharmacist:</strong> {{ $staff->name }}</p>
    <div class="line"></div>
    <h3>Medicines Purchased</h3>
    <ul style="list-style: none; padding: 0;">
        @foreach ($medicines as $medicine)
            <li>{{ $medicine->item->name }}</li>
        @endforeach
    </ul>
    <div class="line"></div>
    <p><strong>Total Amount:</strong> TZS {{ number_format($receipt->total_amount, 0) }}/=</p>
    <div class="line"></div>
    <p>Thank you for your purchase!</p>
</div>

</body>
</html>
