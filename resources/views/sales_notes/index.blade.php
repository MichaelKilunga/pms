@extends('sales_notes.app')

@section('content')
    {{--     
        Below is the sales notes schema
        Schema::create('sale_notes', function (Blueprint $table) {
            $table->id();
            //name
            $table->string('name');
            $table->string('quantity');
            $table->string('unit_price');
            //status
            $table->enum('status', ['promoted', 'Unpromoted','rejected'])->default('Unpromoted');
            //description
            $table->string('description')->nullable();
            //foreign key for pharmacy id
            $table->foreignId('pharmacy_id')->constrained('pharmacies')->onDelete('cascade');
            //foreign key for staff id
            $table->foreignId('staff_id')->constrained('users')->onDelete('cascade'); // User making the sale
            $table->timestamps();
        });

        List all sales notes in table format, include;
            1. a button to trigger the modal to create new sales note
            2. a button to trigger the modal to edit a sales note
            3. a button to delete a sales note in action column
            4. a button to view a sales note in action column
            5. a button to promote a sales note in action column, that will trigger a modal to insert
                a) buying price,
                b) selling price,
                c) expiry date,
                d) stocked quantity,
                e) batch number,
                f) supplier name and
                g) low stock quantity.
            6. a button to reject a sales note in action column
            7. a button to view all promoted sales notes on top of the table
            8. a button to view all rejected sales notes on top of the table
            9. a button to view all Unpromoted sales notes on top of the table
            10. a button to view all sales notes on top of the table

--}}

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-center  h3 text-primary">Sales Notes</h1>
                <div class="mb-2 d-flex justify-content-between">
                    <div>
                        <a href="{{ route('salesNotes', ['filter' => false]) }}" class="btn btn-secondary">All</a>
                        <a href="{{ route('salesNotes', ['filter' => true]) }}" class="btn btn-secondary">Today notes</a>
                    </div>
                    <div>
                        @if (Auth::user()->role != 'staff')
                            <a id="buttonToPromoteAll" href="{{ route('salesNotes.promote') }}"
                                class="btn btn-outline-warning text-dark">Promote All</a>
                            <button id="buttonToPromotedSelected" class="btn btn-outline-warning text-dark"
                                data-bs-toggle="modal" data-bs-target="#promoteSelectedSaleNoteModal#">Promote
                                Selected Notes</button>
                        @endif
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSalesNoteModal">Create
                            Sales
                            Note</button>
                    </div>
                </div>
                <table class="table table-bordered mt-3" id="SaleNoteTable">
                    <thead>
                        <tr>
                            <th class="hidden">#</th>
                            <th>#</th>
                            <th class="name-column">Name</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Status</th>
                            {{-- <th>Description</th> --}}
                            {{-- <th>Pharmacy</th>
                            <th>Staff</th> --}}
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($salesNotes as $salesNote)
                            <tr>
                                <td class="hidden"> {{ $salesNote->id }} </td>
                                <td>{{ $loop->iteration }}</td>
                                <td class="name-column">{{ $salesNote->name }}</td>
                                <td>{{ $salesNote->quantity }}</td>
                                <td>{{ $salesNote->unit_price }}</td>
                                <td>{{ $salesNote->status }}</td>
                                {{-- <td>{{ $salesNote->description }}</td> --}}
                                {{-- <td>{{ $salesNote->pharmacy_id }}</td>
                                <td>{{ $salesNote->staff_id }}</td> --}}
                                <td>{{ \Carbon\Carbon::parse($salesNote->created_at)->diffForHumans() }}</td>
                                <td>
                                    <div class="d-flex justify-content-between">
                                        @if (Auth::user()->role != 'staff')
                                            <div>
                                                <button class="btn btn-success" data-toggle="modal"
                                                    data-bs-target="#promoteSalesNoteModal{{ $salesNote->id }}"><i
                                                        class="bi bi-upload">
                                                        Promote</i>
                                                </button>
                                            </div>
                                        @endif
                                        <div>
                                            <button class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#showSalesNoteModal{{ $salesNote->id }}"><i
                                                    class="bi bi-eye"></i></button>
                                        </div>
                                        <div>
                                            <button class="btn btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#editSalesNoteModal{{ $salesNote->id }}"><i
                                                    class="bi bi-pencil"></i></button>
                                        </div>
                                        {{-- a form for deleting sales note --}}
                                        <form class="" action="{{ route('salesNotes.destroy', $salesNote->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Do you want to delete?')"
                                                class="btn btn-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            {{-- Edit Sales Note Modal --}}
                            <div class="modal fade" id="editSalesNoteModal{{ $salesNote->id }}" role="dialog"
                                aria-labelledby="editSalesNoteModalLabel{{ $salesNote->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="{{ route('salesNotes.update') }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editSalesNoteModalLabel{{ $salesNote->id }}">
                                                    Edit Sales Note</h5>
                                                <button type="button" class="close" data-bs-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body d-flex flex-column">
                                                <div class="form-floating mb-2">
                                                    <input type="text" class="form-control" id="floatingName"
                                                        name="name" placeholder="Amoxicillin" required
                                                        value="{{ $salesNote->name }}">
                                                    <label class="form-label" for="floatingName">Medicine Name</label>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-6 form-floating">
                                                        <input type="number" min="1" class="form-control"
                                                            value="{{ $salesNote->quantity }}" id="floatingQuantity"
                                                            name="quantity" placeholder="50" required>
                                                        <label class="form-label" for="floatingQuantity">Quantity</label>
                                                    </div>
                                                    <div class="col-md-6 form-floating">
                                                        <input type="number" min="1" class="form-control"
                                                            value="{{ $salesNote->unit_price }}" id="floatingUnitPrice"
                                                            name="unit_price" placeholder="200" required>
                                                        <label class="form-label" for="floatingUnitPrice">Unit Price</label>
                                                    </div>
                                                </div>
                                                <div class="form-floating mb-2">
                                                    <input type="text" class="form-control"
                                                        value="{{ $salesNote->description }}" id="floatingDescription"
                                                        name="description" placeholder="Sold for headache">
                                                    <label class="form-label"
                                                        for="floatingDescription">Description</label>
                                                </div>

                                                <input readonly hidden type="text" name="pharmacy_id"
                                                    placeholder="Pharmacy ID" value="{{ $salesNote->pharmacy_id }}"
                                                    required>
                                                <input readonly hidden type="text" name="staff_id"
                                                    placeholder="Staff ID" value="{{ $salesNote->staff_id }}" required>
                                                <input readonly hidden type="number" name="id"
                                                    placeholder="Note ID" value="{{ $salesNote->id }}" required>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- Promote Sales Note Modal --}}
                            <div class="modal fade" id="promoteSalesNoteModal{{ $salesNote->id }}" role="dialog"
                                aria-labelledby="promoteSalesNoteModalLabel{{ $salesNote->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="{{ route('salesNotes.promote', $salesNote->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title"
                                                    id="promoteSalesNoteModalLabel{{ $salesNote->id }}">Promote Sales Note
                                                </h5>
                                                <button type="button" class="close" data-bs-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body d-flex flex-column">
                                                {{-- <input type="hidden" name="sale_note_ids" id="selectedSaleNoteIds"> --}}
                                                <input type="text" name="buying_price" placeholder="Buying Price">
                                                <input type="text" name="selling_price" placeholder="Selling Price">
                                                <input type="text" name="expiry_date" placeholder="Expiry Date">
                                                <input type="text" name="stocked_quantity"
                                                    placeholder="Stocked Quantity">
                                                <input type="text" name="batch_number" placeholder="Batch Number">
                                                <input type="text" name="supplier_name" placeholder="Supplier Name">
                                                <input type="text" name="low_stock_quantity"
                                                    placeholder="Low Stock Quantity">
                                                <input type="text" name="status" placeholder="Status">
                                                <input type="text" name="pharmacy_id" placeholder="Pharmacy ID">
                                                <input type="text" name="staff_id" placeholder="Staff ID">
                                                <input type="text" name="name" placeholder="Name">
                                                <input type="text" name="quantity" placeholder="Quantity">
                                                <input type="text" name="unit_price" placeholder="Unit Price">
                                                <input type="text" name="description" placeholder="Description">
                                                <input type="text" name="pharmacy_id" placeholder="Pharmacy ID">
                                                <input type="text" name="staff_id" placeholder="Staff ID">
                                                <input type="text" name="status" placeholder="Status">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Promote</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- Show sales note --}}
                            <div class="modal fade bd-example-modal-lg" id="showSalesNoteModal{{ $salesNote->id }}"
                                role="dialog" aria-labelledby="showSalesNoteModalLabel{{ $salesNote->id }}"
                                aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="showSalesNoteModalLabel{{ $salesNote->id }}">Show
                                                Sales Note</h5>
                                            <button type="button" class="close" data-bs-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p><strong>Name:</strong> {{ $salesNote->name }}</p>
                                                    <p><strong>Quantity:</strong> {{ $salesNote->quantity }}</p>
                                                    <p><strong>Unit Price:</strong> {{ $salesNote->unit_price }}</p>
                                                    <p><strong>Status:</strong> {{ $salesNote->status }}</p>
                                                    <p><strong>Description:</strong> {{ $salesNote->description }}</p>
                                                    <p><strong>Created at:</strong> {{ $salesNote->created_at }}</p>
                                                    {{-- <p><strong>Pharmacy:</strong> {{ $salesNote->pharmacy_id }}</p> --}}
                                                    <p><strong>Added by:</strong>
                                                        {{ $salesNote->staff_id == Auth::user()->id ? 'You!' : $salesNote->staff->name }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Create Sales Note Modal --}}
    <div class="modal fade" id="createSalesNoteModal" role="dialog" aria-labelledby="createSalesNoteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('salesNotes.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createSalesNoteModalLabel">Create Sales Note</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body d-flex flex-column">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control" id="floatingName" name="name"
                                placeholder="Amoxicillin" required>
                            <label class="form-label" for="floatingName">Medicine Name</label>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6 form-floating">
                                <input type="number" min="1" class="form-control" id="floatingQuantity"
                                    name="quantity" placeholder="50" required>
                                <label class="form-label" for="floatingQuantity">Quantity</label>
                            </div>
                            <div class="col-md-6 form-floating">
                                <input type="number" min="1" class="form-control" id="floatingUnitPrice"
                                    name="unit_price" placeholder="200" required>
                                <label class="form-label" for="floatingUnitPrice">Unit Price</label>
                            </div>
                        </div>
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control" id="floatingDescription" name="description"
                                placeholder="Sold for headache">
                            <label class="form-label" for="floatingDescription">Description</label>
                        </div>

                        <input readonly hidden type="text" name="pharmacy_id" placeholder="Pharmacy ID"
                            value="{{ session('current_pharmacy_id') }}" required>
                        <input readonly hidden type="text" name="staff_id" placeholder="Staff ID"
                            value="{{ auth()->id() }}" required>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary " data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal for promoting multiple sales notes at once --}}
    <div class="modal fade" id="promoteSelectedSaleNoteModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('salesNotes.promoteSelected') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Promote Selected Sales Notes</h5>
                        <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input type="number" name="sale_note_ids" placeholder="1,2,3" id="selectedSaleNoteIds">
                        <input type="number" name="buying_price" placeholder="Buying Price">
                        <input type="number" name="selling_price" placeholder="Selling Price">
                        <input type="date" name="expiry_date" placeholder="Expiry Date">
                        <input type="number" name="stocked_quantity" placeholder="Stocked Quantity">
                        <input type="text" name="batch_number" placeholder="Batch Number">
                        <input type="text" name="supplier_name" placeholder="Supplier Name">
                        <input type="number" name="low_stock_quantity" placeholder="Low Stock Quantity">
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Promote</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#SaleNoteTable').DataTable();

            // Store selected sales note IDs
            var selectedSalesNotes = [];

            // Enable row selection, (select row on clicking on its number(first column))
            $('#SaleNoteTable tbody')
                .on('mouseenter', 'td.name-column', function() {
                    $(this).css('cursor', 'pointer');
                    $(this).css('background-color', 'whitesmoke');
                })
                .on('mouseleave', 'td.name-column', function() {
                    $(this).css('cursor', 'default');
                    $(this).css('background-color', 'white');
                })
                .on('click', 'td.name-column', function(e) {
                    var saleNoteId = $(this).closest('tr').find('td:first')
                        .text(); // Get sale note ID from first column
                    var row = $(this).closest('tr'); // Get the row

                    row.toggleClass('selected'); // Toggle row selection

                    // Store or remove sale note ID
                    if (row.hasClass('selected')) {
                        selectedSalesNotes.push(saleNoteId);
                        // alert(saleNoteId);
                    } else {
                        selectedSalesNotes = selectedSalesNotes.filter(id => id !== saleNoteId);
                    }
                });


            // Open the modal when "Promote Selected" button is clicked
            $('#buttonToPromotedSelected').on('click', function(e) {
                e.preventDefault();

                if (selectedSalesNotes.length === 0) {
                    alert('Please select at least one sales note to promote.');
                    return;
                }

                // Populate the modal with selected IDs
                $('#promoteSelectedSaleNoteModal input[name="sale_note_ids"]').val(selectedSalesNotes.join(
                    ',')); // Store IDs in hidden input

                // Show the modal
                $('#promoteSelectedSaleNoteModal').modal('show');
            });
        });
    </script>
@endsection
