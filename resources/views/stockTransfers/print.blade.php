<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Stock Transfer Invoice</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 40px;
            color: #333;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }

        .invoice-box h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .details {
            margin-bottom: 20px;
        }

        .details p {
            margin: 5px 0;
        }

        .items {
            width: 100%;
            border-collapse: collapse;
        }

        .items th,
        .items td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        .items th {
            background-color: #f4f4f4;
            text-align: left;
        }

        @media print {
            body {
                margin: 0;
            }

            .invoice-box {
                box-shadow: none;
                border: none;
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>

<body>
    @php
        $transfers = $transfer->get();
    @endphp

    <div class="invoice-box">
        <h2 style="text-align: center;"></h2>
        {{-- pharmacy name --}}
        <h2 style="text-align: center;">{{ $transfer->first()->fromPharmacy->name }}</h2>

        <h2 style="text-align: center;">Stock Transfer Invoice</h2>

        <div class="details">

            <p><strong>From Pharmacy:</strong> {{ $transfer->first()->fromPharmacy->name }}</p>
            <p><strong>To Pharmacy:</strong>
                {{ $transfer->first()->toPharmacy->name ?? $transfer->first()->to_pharmacy_name }}</p>
            <p><strong>To Pharmacy TIN :</strong> {{ $transfer->first()->to_pharmacy_tin }}</p>
            <p><strong>Description:</strong> {{ $transfer->first()->notes }}</p>
            {{-- <p><strong>Transfer ID:</strong> {{ $transfer->first()->id }}</p> --}}
            <p><strong>Transfer At:</strong> {{ $transfer->first()->created_at->format('Y-m-d H:i:s') }}</p>
            <p><strong>Transfer Status:</strong> {{ $transfer->first()->status }}</p>

        </div>
        <table class="items">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>Medicine</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transfers as $transfer)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $transfer->stock->item->name }}</td>
                        <td>{{ $transfer->quantity }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>
        <div class="signature" style="margin-top: 40px; text-align: left;">
            Printed by {{ auth()->user()->name }}<br>
            printed on {{ now()->format('Y-m-d H:i:s') }}<br>
            <p>__________________________</p>
            <p>Authorized Signature <br /><br /> Stamp</p>
            {{-- stamp --}}
        </div>
        <p style="text-align: center; margin-top: 20px;">Thank you for your business!</p>
    </div>
    {{-- // after click print button, close the window automatically and redirect to the index page --}}
    <script type="text/javascript">
        window.print();
        window.onafterprint = function() {
            window.close();
            window.location.href = "{{ route('stockTransfers.index') }}";
        };
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</body>

</html>
