@extends('stock.app')

@section('content')
@php $medicines = App\Models\Items::where('pharmacy_id', session('current_pharmacy_id'))->get(); @endphp
    <div class="container mt-4">
        {{-- @foreach ($medicines as $x)
            {{$x->id}}
        @endforeach --}}
        <div class="d-flex justify-content-between mb-3">
            <h2>Stock</h2>
            <div>
                <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createStockModal">Add New
                    Stock</a>
                <a href="#" class="btn btn-success" data-bs-toggle="modal"
                    data-bs-target="#createMedicineStockModal">Medicine + Stock</a>
                <a href="#" class="btn btn-danger" data-bs-toggle="modal"
                    data-bs-target="#importMedicineStockModal">Import CSV</a>
            </div>
        </div>


        {{-- there are deleted data here --}}
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="tableOfStocks">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Batch Number</th>
                        <th>Supplier</th>
                        <th>Medicine Name</th>
                        <th>Buying Price</th>
                        <th>Selling Price</th>
                        <th>Remain Quantity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>


    </div>


    {{-- <!-- Create Stock Modal --> --}}
    <div class="modal fade" id="createStockModal" tabindex="-1" aria-labelledby="createStockModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center bg-primary text-white">
                    <h5 class="modal-title" id="createStockModalLabel">Add New Stock</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('stock.store') }}" method="POST" id="stockForm">
                        @csrf
                        <div class="row mb-2">
                            <div class="col-12# col-sm-6# col-md-6 col-lg-2#">
                                <label class="form-label fw-bold">Stock batch Number</label>
                                <input placeholder="1234" id="batch_number" type="text"
                                    class="rounded form-control shadow-sm" name="batch_number" readonly>
                            </div>
                            <div class="col-12# col-sm-6# col-md-6 col-lg-2#">
                                <label class="form-label fw-bold">Supplier Name</label>
                                <input type="text" placeholder="ABC Supplier" class="rounded form-control shadow-sm"
                                    name="supplier" required>
                            </div>
                        </div>
                        <hr class="m-2">
                        <div id="stockFields">
                            <div class="row mb-3 stock-entry align-items-end gx-2 gy-2">
                                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                    <label for="item_id" class="form-label fw-bold">Medicine Name</label>
                                    <select name="item_id[]" class="form-select chosen shadow-sm" required>
                                        <option selected value=""></option>
                                        @foreach ($medicines as $medicine)
                                            <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                    <label class="form-label fw-bold">Buying Price</label>
                                    <input type="number" class="form-control shadow-sm" name="buying_price[]" required>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                    <label class="form-label fw-bold">Selling Price</label>
                                    <input type="number" class="form-control shadow-sm" name="selling_price[]" required>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                    <label class="form-label fw-bold">Quantity</label>
                                    <input type="number" class="form-control shadow-sm" name="quantity[]" required>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                    <label class="form-label fw-bold">Low stock</label>
                                    <input type="number" class="form-control shadow-sm" name="low_stock_percentage[]"
                                        min="1" required>
                                </div>
                                <div class="col-0 col-sm-0 col-md-0 col-lg-0" hidden>
                                    <label class="form-label fw-bold">In Date</label>
                                    <input type="text" class="form-control shadow-sm" name="in_date[]"
                                        value="{{ now() }}" required>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                    <label class="form-label fw-bold">Expire Date</label>
                                    <input type="date" class="form-control shadow-sm" name="expire_date[]" required>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="col-md-10 d-flex justify-content-between">
                                <button type="button" id="addStockBtn" class="btn btn-outline-primary">
                                    <i class="bi bi-plus-lg"></i> Add Row
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-save"></i> Save
                                </button>
                            </div>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>

    <!-- Create Medicine + Stock Modal -->
    <div class="modal fade" id="createMedicineStockModal" tabindex="-1" aria-labelledby="createMedicineStockModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center bg-primary text-white">
                    <h5 class="modal-title" id="createMedicineStockModalLabel">Add New Stock and Medicine</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('medicineStock.store') }}" method="POST" id="stockForm">
                        @csrf
                        <div class="row mb-2">
                            <div class="col-12# col-sm-6# col-md-6 col-lg-2#">
                                <label class="form-label fw-bold">Stock batch Number</label>
                                <input placeholder="1234" id="batch_number_" type="text"
                                    class="rounded form-control shadow-sm" name="batch_number" readonly>
                            </div>
                            <div class="col-12# col-sm-6# col-md-6 col-lg-2#">
                                <label class="form-label fw-bold">Supplier Name</label>
                                <input type="text" placeholder="ABC Supplier" class="rounded form-control shadow-sm"
                                    name="supplier" required>
                            </div>
                        </div>
                        <hr class="m-2">
                        <div id="medicineStockFields">
                            <div class="row mb-3 stock-entry align-items-end gx-2 gy-2">
                                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                    <label for="item_id" class="form-label fw-bold">New Medicine Name</label>
                                    <input type="text" class="form-control shadow-sm" name="item_name[]" required>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                    <label class="form-label fw-bold">Buying Price</label>
                                    <input type="number" class="form-control shadow-sm" name="buying_price[]" required>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                    <label class="form-label fw-bold">Selling Price</label>
                                    <input type="number" class="form-control shadow-sm" name="selling_price[]" required>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                    <label class="form-label fw-bold">Quantity</label>
                                    <input type="number" class="form-control shadow-sm" name="quantity[]" required>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                    <label class="form-label fw-bold">Low stock</label>
                                    <input type="number" class="form-control shadow-sm" name="low_stock_percentage[]"
                                        min="1" required>
                                </div>
                                <div class="col-0 col-sm-0 col-md-0 col-lg-0" hidden>
                                    <label class="form-label fw-bold">In Date</label>
                                    <input type="text" class="form-control shadow-sm" name="in_date[]"
                                        value="{{ now() }}" required>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                    <label class="form-label fw-bold">Expire Date</label>
                                    <input type="date" class="form-control shadow-sm" name="expire_date[]" required>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="col-md-10 d-flex justify-content-between">
                                <button type="button" id="addMedicineStockBtn" class="btn btn-outline-primary">
                                    <i class="bi bi-plus-lg"></i> Add Row
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-save"></i> Save
                                </button>
                            </div>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>

    {{-- Create a modal to Import Medicine and Stock from csv file --}}
    <div class="modal fade" id="importMedicineStockModal" tabindex="-1" aria-labelledby="importMedicineStockModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center bg-primary text-white">
                    <h5 class="modal-title" id="importMedicineStockModalLabel">Import Medicines and Stock</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('importMedicineStock') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            {{-- Indicate fields required in the label --}}
                            <label for="file" class="form-label fw-bold">Select CSV File, (Colums:<small
                                    class="smallest text-danger"> item_name, buying_price, selling_price, quantity,
                                    low_stock_percentage, expire_date, supplier</small>) </label>
                            <input type="file" class="form-control" name="file" accept=".csv" required>
                        </div>
                        <div class="col-12# col-sm-6# col-md-6 col-lg-2#" hidden>
                            <label class="form-label fw-bold">Stock batch Number</label>
                            <input placeholder="1234" id="batch_number__" type="text"
                                class="rounded form-control shadow-sm" name="batch_number__" readonly required>
                        </div>
                        <div class="col-0 col-sm-0 col-md-0 col-lg-0" hidden>
                            <label class="form-label fw-bold">In Date</label>
                            <input type="text" class="form-control shadow-sm" name="in_date"
                                value="{{ now() }}" required readonly>
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-upload"></i> Import
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // STOCKS ONLY
        document.getElementById('addStockBtn').addEventListener('click', function() {
            const stockFields = document.getElementById('stockFields');

            const newStockEntry = document.createElement('div');
            newStockEntry.classList.add('row', 'mb-3', 'stock-entry', 'align-items-end', 'gx-2', 'gy-2');
            newStockEntry.innerHTML = `
            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                <label class="form-label fw-bold">Medicine Name</label>
                <select name="item_id[]" class="form-select  shadow-sm chosen" required>
                    <option selected value="">Select medicine...</option>
                    @foreach ($medicines as $medicine)
                        <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                <label class="form-label fw-bold">Buying Price</label>
                <input type="number" class="form-control  shadow-sm" name="buying_price[]" required>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                <label class="form-label fw-bold">Selling Price</label>
                <input type="number" class="form-control  shadow-sm" name="selling_price[]" required>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                <label class="form-label fw-bold">Quantity</label>
                <input type="number" class="form-control  shadow-sm" name="quantity[]" required>
            </div>
           <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                 <label class="form-label fw-bold">Low stock</label>
                 <input type="number" class="form-control  shadow-sm" name="low_stock_percentage[]" min="1" required>
            </div>
           <div class="col-0 col-sm-0 col-md-0 col-lg-0" hidden>
                <label class="form-label fw-bold">In Date</label>
              <input type="text" class="form-control  shadow-sm" name="in_date[]" value="{{ now() }}"
              required>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                <label class="form-label fw-bold">Expire Date</label>
                <input type="date" class="form-control  shadow-sm" name="expire_date[]" required>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-2 d-flex justify-content-center">
                <button type="button" class="btn btn-danger btn-sm  shadow-sm remove-stock-entry">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            `;

            stockFields.appendChild(newStockEntry);

            $(".chosen").chosen({
                width: "100%",
                no_results_text: "No matches found!",
            });

            newStockEntry.querySelector('.remove-stock-entry').addEventListener('click', function() {
                newStockEntry.remove();
            });
        });

        //MEDICINES + STOCKS
        document.getElementById('addMedicineStockBtn').addEventListener('click', function() {
            const medicineStockFields = document.getElementById('medicineStockFields');

            const newStockEntry = document.createElement('div');
            newStockEntry.classList.add('row', 'mb-3', 'stock-entry', 'align-items-end', 'gx-2', 'gy-2');
            newStockEntry.innerHTML = `
            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                <label class="form-label fw-bold">Medicine Name</label>
                <input type="text" class="form-control shadow-sm" name="item_name[]" required>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                <label class="form-label fw-bold">Buying Price</label>
                <input type="number" class="form-control  shadow-sm" name="buying_price[]" required>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                <label class="form-label fw-bold">Selling Price</label>
                <input type="number" class="form-control  shadow-sm" name="selling_price[]" required>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                <label class="form-label fw-bold">Quantity</label>
                <input type="number" class="form-control  shadow-sm" name="quantity[]" required>
            </div>
           <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                 <label class="form-label fw-bold">Low stock</label>
                 <input type="number" class="form-control  shadow-sm" name="low_stock_percentage[]" min="1" required>
            </div>
           <div class="col-0 col-sm-0 col-md-0 col-lg-0" hidden>
                <label class="form-label fw-bold">In Date</label>
              <input type="text" class="form-control  shadow-sm" name="in_date[]" value="{{ now() }}"
              required>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                <label class="form-label fw-bold">Expire Date</label>
                <input type="date" class="form-control  shadow-sm" name="expire_date[]" required>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-2 d-flex justify-content-center">
                <button type="button" class="btn btn-danger btn-sm  shadow-sm remove-stock-entry">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;

            medicineStockFields.appendChild(newStockEntry);

            $(".chosen").chosen({
                width: "100%",
                no_results_text: "No matches found!",
            });

            newStockEntry.querySelector('.remove-stock-entry').addEventListener('click', function() {
                newStockEntry.remove();
            });
        });


        $(document).ready(function() {
            const today = new Date();
            const year = today.getFullYear(); // Get the full year
            const month = String(today.getMonth() + 1).padStart(2,
                '0'); // Months are zero-based, pad with leading zero if needed
            const day = String(today.getDate()).padStart(2, '0'); // Pad day with leading zero if needed
            const formattedDate = `${year}${month}${day}`; // Combine to form YYYYMMDD format
            $('#batch_number').val(formattedDate); // Use .val() to set the value of the input
            $('#batch_number_').val(formattedDate); // Use .val() to set the value of the input
            $('#batch_number__').val(formattedDate); // Use .val() to set the value of the input
        });


        $(document).ready(function() {
            $('#tableOfStocks').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('stock') }}", // Laravel route
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'batch_number',
                        name: 'batch_number'
                    },
                    {
                        data: 'supplier',
                        name: 'supplier'
                    },
                    {
                        data: 'medicine_name',
                        name: 'medicine_name'
                    },
                    {
                        data: 'buying_price',
                        name: 'buying_price'
                    },
                    {
                        data: 'selling_price',
                        name: 'selling_price'
                    },
                    {
                        data: 'remain_Quantity',
                        name: 'remain_Quantity'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [1, 'asc']
                ] // Default sorting by Batch Number
            });
        });
    </script>
@endsection
