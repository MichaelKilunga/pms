@extends('stock.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <h2>Stock</h2>
            <div>
                <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createStockModal">Add New
                    Stock</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="Table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Stock Name</th>
                        <th>Selling Price</th>
                        <th>Buying Price</th>
                        <th>Quantity</th>
                        <th>In Date</th>
                        <th>Expire Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stocks as $stock)
                        <tr>
                            <td>{{ $stock->id }}</td>
                            <td>{{ $stock->item->name }}</td>
                            <td>{{ $stock->selling_price }}</td>
                            <td>{{ $stock->buying_price }}</td>
                            <td>{{ $stock->quantity }}</td>
                            <td>{{ $stock->in_date }}</td>
                            <td>{{ $stock->expire_date }}</td>
                            <td>
                                <!-- View Stock Modal -->
                                <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#viewStockModal{{ $stock->id }}">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <div class="modal fade" id="viewStockModal{{ $stock->id }}" tabindex="-1"
                                    aria-labelledby="viewStockModalLabel{{ $stock->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="viewStockModalLabel{{ $stock->id }}">Stock
                                                    Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div><strong>Stock Name:</strong> {{ $stock->item->name }}</div>
                                                <div><strong>Selling Price:</strong> {{ $stock->selling_price }}</div>
                                                <div><strong>Buying Price:</strong> {{ $stock->buying_price }}</div>
                                                <div><strong>Quantity:</strong> {{ $stock->quantity }}</div>
                                                <div><strong>In Date:</strong> {{ $stock->in_date }}</div>
                                                <div><strong>Expire Date:</strong> {{ $stock->expire_date }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Stock Modal -->
                                <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editStockModal{{ $stock->id }}">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <div class="modal fade" id="editStockModal{{ $stock->id }}" tabindex="-1"
                                    aria-labelledby="editStockModalLabel{{ $stock->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editStockModalLabel{{ $stock->id }}">Edit
                                                    Stock</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('stock.update', $stock->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="text" class="form-control" name="id"
                                                        value="{{ $stock->item_id }}" hidden>
                                                    <div class="mb-3">
                                                        <label for="item" class="form-label">Stock Name</label>
                                                        <input type="text" class="form-control" name="item_name"
                                                            value="{{ $stock->item->name }}" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="selling_price" class="form-label">Selling Price</label>
                                                        <input type="number" class="form-control" name="selling_price"
                                                            value="{{ $stock->selling_price }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="buying_price" class="form-label">Buying Price</label>
                                                        <input type="number" class="form-control" name="buying_price"
                                                            value="{{ $stock->buying_price }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="quantity" class="form-label">Quantity</label>
                                                        <input type="number" class="form-control" name="quantity"
                                                            value="{{ $stock->quantity }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="in_date" class="form-label">In Date</label>
                                                        <input type="date" class="form-control" name="in_date"
                                                            value="{{ $stock->in_date }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="expire_date" class="form-label">Expire Date</label>
                                                        <input type="date" class="form-control" name="expire_date"
                                                            value="{{ $stock->expire_date }}" required>
                                                    </div>
                                                    <button type="submit" class="btn btn-success">Update Stock</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Stock Form -->
                                <form action="{{ route('stock.destroy', $stock->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Do you want to delete this stock?')"
                                        class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
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
                        <div id="stockFields">
                            <!-- Dynamic stock fields -->
                            <div class="row mb-3 stock-entry align-items-end gx-2 gy-2">
                                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                    <label for="item_id" class="form-label fw-bold">Medicine Name</label>
                                    <select name="item_id[]" class="form-select rounded-3 shadow-sm" required>
                                        <option selected value="">Select medicine..</option>
                                        @foreach ($medicines as $medicine)
                                            <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                    <label class="form-label fw-bold">Selling Price</label>
                                    <input type="number" class="form-control rounded-3 shadow-sm" name="selling_price[]"
                                        required>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                    <label class="form-label fw-bold">Buying Price</label>
                                    <input type="number" class="form-control rounded-3 shadow-sm" name="buying_price[]"
                                        required>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                    <label class="form-label fw-bold">Quantity</label>
                                    <input type="number" class="form-control rounded-3 shadow-sm" name="quantity[]"
                                        required>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                    <label class="form-label fw-bold">In Date</label>
                                    <input type="date" class="form-control rounded-3 shadow-sm" name="in_date[]"
                                        required>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                    <label class="form-label fw-bold">Expire Date</label>
                                    <input type="date" class="form-control rounded-3 shadow-sm" name="expire_date[]"
                                        required>
                                </div>
                                <!-- Remove Button -->
                                {{-- <div class="col-12 col-sm-6 col-md-4 col-lg-2 d-flex justify-content-center">
                                <button type="button" class="btn btn-danger btn-sm rounded-3 shadow-sm remove-stock-entry">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div> --}}
                            </div>
                        </div>
                        <!-- Buttons -->
                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" id="addStockBtn" class="btn btn-outline-primary rounded-3">
                                <i class="bi bi-plus-lg"></i> Add Row
                            </button>
                            <button type="submit" class="btn btn-success rounded-3">
                                <i class="bi bi-save"></i> Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('addStockBtn').addEventListener('click', function() {
            const stockFields = document.getElementById('stockFields');

            const newStockEntry = document.createElement('div');
            newStockEntry.classList.add('row', 'mb-3', 'stock-entry', 'align-items-end', 'gx-2', 'gy-2');
            newStockEntry.innerHTML = `
            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                <label class="form-label fw-bold">Medicine Name</label>
                <select name="item_id[]" class="form-select rounded-3 shadow-sm" required>
                    <option selected value="">Select medicine...</option>
                    @foreach ($medicines as $medicine)
                        <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                <label class="form-label fw-bold">Selling Price</label>
                <input type="number" class="form-control rounded-3 shadow-sm" name="selling_price[]" required>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                <label class="form-label fw-bold">Buying Price</label>
                <input type="number" class="form-control rounded-3 shadow-sm" name="buying_price[]" required>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                <label class="form-label fw-bold">Quantity</label>
                <input type="number" class="form-control rounded-3 shadow-sm" name="quantity[]" required>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                <label class="form-label fw-bold">In Date</label>
                <input type="date" class="form-control rounded-3 shadow-sm" name="in_date[]" required>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                <label class="form-label fw-bold">Expire Date</label>
                <input type="date" class="form-control rounded-3 shadow-sm" name="expire_date[]" required>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-2 d-flex justify-content-center">
                <button type="button" class="btn btn-danger btn-sm rounded-3 shadow-sm remove-stock-entry">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;

            stockFields.appendChild(newStockEntry);

            newStockEntry.querySelector('.remove-stock-entry').addEventListener('click', function() {
                newStockEntry.remove();
            });
        });
    </script>
@endsection
