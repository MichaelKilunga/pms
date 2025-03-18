<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Receipt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <link rel='stylesheet' href='https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css'>
    <style>
        @page {
            size: 85mm auto;
            margin: 0;
        }

        body {
            font-family: Arial, sans-serif;
            /* text-align: center; */
            /* font-size: 8px; */
        }

        .receipt {
            width: 300px;
            margin: auto;
            padding: 10px;
            /* border: 1px solid #000; */
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

    <div class="receipt card p-3 shadow-sm">
        <h2 class="text-center h5 text-dark">Medicine Purchase Receipt</h2>
        <hr>

        <div class="fs-20">
            <span class="d-flex justify-content-between">
                <strong>Pharmacy:</strong>
                <span>
                    {{ APP\Models\Pharmacy::where('id', session('current_pharmacy_id'))->first()->name }}
                </span>
            </span>
            <span class="d-flex justify-content-between">
                <strong>Date:</strong>
                <span>{{ $receipt->date }}</span>
            </span>
            <span class="d-flex justify-content-between">
                <strong>Pharmacist:</strong>
                <span>{{ $staff->name }}</span>
            </span>
        </div>
<br>
        <h4 class="text-center h6 small">Medicines Purchased</h4>

        <table class="table table-bordered# table-sm text-left table-striped#">
            <thead class="table-light">
                <tr class="text-left">
                    <th><small class="smaller">Name</small></th>
                    <th><small class="smallest">Unit</small></th>
                    <th><small class="smallest">Total (TZS)</small></th>
                </tr>
            </thead>
            <tbody class="fs-20">
                @foreach ($medicines as $medicine)
                    <tr>
                        <td class="text-left">{{ $medicine->item->name }}</td>
                        <td class="text-left">{{ $medicine->quantity }}</td>
                        <td class="text-right">{{ number_format($medicine->total_price, 0) }}/=</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2" class="text-right"><strong>Total:</strong></td>
                    <td><b>{{ number_format($receipt->total_amount, 0) }}/=</b></td>
                </tr>
            </tbody>
        </table>
        <p class="text-center text-muted">Thank you for your purchase!</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
