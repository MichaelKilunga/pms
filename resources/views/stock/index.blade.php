@extends('stock.app')

@section('content')
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
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="Table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Batch Number</th>
                        <th>Supplier</th>
                        <th>Medicine Name</th>
                        <th>Buying Price</th>
                        <th>Selling Price</th>
                        <th>Remain Quantity</th>
                        {{-- <th>Low Stock</th> --}}
                        {{-- <th>In Date</th> --}}
                        {{-- <th>Expire Date</th> --}}
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stocks as $stock)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $stock->batch_number }}</td>
                            <td>{{ $stock->supplier }}</td>
                            <td>{{ \Illuminate\Support\Str::words($stock->item->name, 3, '...') }}</td>
                            <td>{{ $stock->buying_price }}</td>
                            <td>{{ $stock->selling_price }}</td>
                            {{-- <td>{{ $stock->quantity }}</td> --}}
                            <td>{{ $stock->remain_Quantity }}</td>
                            {{-- <td>{{ $stock->low_stock_percentage }}</td> --}}
                            {{-- <td>{{ $stock->in_date }}</td> --}}
                            {{-- <td>{{ $stock->expire_date }}</td> --}}
                            <td>
                                @if ($stock->expire_date < now())
                                    <span class="text-danger">Expired</span>
                                @endif

                                @if (
                                    !($stock->expire_date < now()) &&
                                        !($stock->low_stock_percentage > $stock->remain_Quantity) &&
                                        !($stock->remain_Quantity < 1))
                                    <span class="text-success   "><i class="bi bi-check fs-3"></i>fine!</span>
                                @endif

                                @if ($stock->expire_date < now() && $stock->low_stock_percentage > $stock->remain_Quantity)
                                    <span class="text-danger">,</span>
                                @endif
                                @if ($stock->remain_Quantity < 1)
                                    <span class="text-danger">Out of Stock</span>
                                @elseif ($stock->low_stock_percentage > $stock->remain_Quantity)
                                    <span class="text-danger">Low stock threshold</span>
                                @endif
                            </td>
                            <td>
                                <div class="row">
                                    <!-- View Stock Modal -->
                                    <div class="col-3">
                                        <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#viewStockModal{{ $stock->id }}">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>

                                    <!-- Edit Stock Modal -->
                                    <div class="col-3">
                                        <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editStockModal{{ $stock->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </div>

                                    <!-- Delete Stock Form -->
                                    <div class="col-3">
                                        <form action="{{ route('stock.destroy', $stock->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            @if ($stock->quantity == $stock->remain_Quantity)
                                                <button type="submit"
                                                    onclick="return confirm('Do you want to delete this stock?')"
                                                    class="btn btn-danger btn-sm">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        </form>
                                    </div>

                                    {{-- VIEW MODAL --}}
                                    <div class="modal fade" id="viewStockModal{{ $stock->id }}" tabindex="-1"
                                        aria-labelledby="viewStockModalLabel{{ $stock->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="viewStockModalLabel{{ $stock->id }}">
                                                        Stock
                                                        Details</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div><strong>Stock Name:</strong> {{ $stock->item->name }}</div>
                                                    <div><strong>Stock Name:</strong> {{ $stock->batch_number }}</div>
                                                    <div><strong>Stock Name:</strong> {{ $stock->supplier }}</div>
                                                    <div><strong>Buying Price:</strong> {{ $stock->buying_price }}</div>
                                                    <div><strong>Selling Price:</strong> {{ $stock->selling_price }}</div>
                                                    <div><strong>Stoked Quantity:</strong> {{ $stock->quantity }}</div>
                                                    <div><strong>Remain Quantity:</strong> {{ $stock->remain_Quantity }}
                                                    </div>
                                                    <div><strong>Low stock:</strong>
                                                        {{ $stock->low_stock_percentage }}</div>
                                                    <div><strong>In Date:</strong> {{ $stock->in_date }}</div>
                                                    <div><strong>Expire Date:</strong> {{ $stock->expire_date }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- EDIT MODAL --}}
                                    <div class="modal fade" id="editStockModal{{ $stock->id }}" tabindex="-1"
                                        aria-labelledby="editStockModalLabel{{ $stock->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editStockModalLabel{{ $stock->id }}">
                                                        Edit
                                                        Stock</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('stock.update', $stock->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="text" class="form-control" name="id"
                                                            value="{{ $stock->id }}" hidden>
                                                        <div class="mb-3">
                                                            <label for="item" class="form-label">Stock Name</label>
                                                            <input type="text" class="form-control" name="item_name"
                                                                value="{{ $stock->item->name }}" readonly>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="item" class="form-label">Batch Number</label>
                                                            <input type="text" class="form-control" name="batch_number"
                                                                value="{{ $stock->batch_number }}" readonly>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="item" class="form-label">Supplier Name</label>
                                                            <input type="text" class="form-control" name="supplier"
                                                                value="{{ $stock->supplier }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="buying_price" class="form-label">Buying
                                                                Price</label>
                                                            <input type="number" class="form-control"
                                                                name="buying_price" value="{{ $stock->buying_price }}"
                                                                required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="selling_price" class="form-label">Selling
                                                                Price</label>
                                                            <input type="number" class="form-control"
                                                                name="selling_price" value="{{ $stock->selling_price }}"
                                                                required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="quantity" class="form-label">Stocked
                                                                Quantity</label>
                                                            <input type="number" class="form-control" name="quantity"
                                                                value="{{ $stock->quantity }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="quantity" class="form-label">Remain
                                                                Quantity</label>
                                                            <input type="number" class="form-control"
                                                                name="remain_Quantity"
                                                                value="{{ $stock->remain_Quantity }}" readonly required
                                                                title="You cannot edit">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="quantity" class="form-label">Low stock
                                                                percentage(%)</label>
                                                            <input type="number" class="form-control"
                                                                name="low_stock_percentage"
                                                                value="{{ $stock->low_stock_percentage }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="in_date" class="form-label">In Date</label>
                                                            <input type="text" class="form-control" name="in_date"
                                                                value="{{ $stock->created_at }}" readonly required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="expire_date" class="form-label">Expire
                                                                Date</label>
                                                            <input type="date" class="form-control" name="expire_date"
                                                                value="{{ $stock->expire_date }}" required>
                                                        </div>
                                                        <button type="submit" class="btn btn-success">Update
                                                            Stock</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <!-- Create Stock Modal -->
    <div class="modal fade" id="createStockModal" tabindex="-1" aria-labelledby="createStockModalLabel"
        aria-hidden="true">
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

    <!-- Create Stock Modal -->
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
        });
    </script>
@endsection
