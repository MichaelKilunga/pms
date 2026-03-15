@extends("medicines.app")

@section("content")
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <h2 class="m-2">Medicines</h2>
            <div class="d-flex justify-content-between">
                <div>
                    <a class="btn btn-success m-1" data-bs-target="#createMedicineModal" data-bs-toggle="modal"
                        href="#">Add
                        New
                        Medicine</a>
                </div>
                <div>
                    <a class="btn btn-danger m-1" href="import">Import from online</a>
                </div>
            </div>
        </div>

        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <form action="{{ route("medicines") }}" class="row g-2 d-flex justify-content-between" method="GET">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input class="form-control" name="search" placeholder="Search medicine name..."
                                type="text" value="{{ request("search") }}">
                        </div>
                    </div>
                    <div class="col-md-4 d-none">
                        <select class="form-select" name="category_id">
                            <option value="">All Categories</option>
                            @foreach ($categories as $category)
                                <option {{ request("category_id") == $category->id ? "selected" : "" }}
                                    value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button class="btn btn-primary flex-grow-1" type="submit">Filter</button>
                        @if (request()->has("search") || request()->has("category_id"))
                            <a class="btn btn-outline-secondary" href="{{ route("medicines") }}">Reset</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table-striped table-bordered table-hover small table" id="medicinesTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Medicine Name</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

        <script>
            $(document).ready(function() {
                const table = $('#medicinesTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: async function(data, callback, settings) {
                        if (navigator.onLine) {
                            try {
                                const response = await $.ajax({
                                    url: "{{ route('medicines') }}",
                                    data: data
                                });
                                callback(response);
                                return;
                            } catch (e) {
                                console.warn('Network request failed, falling back to local DB');
                            }
                        }

                        // Offline Fallback
                        if (!window.db) {
                            callback({ draw: data.draw, recordsTotal: 0, recordsFiltered: 0, data: [] });
                            return;
                        }

                        let items = await window.db.items.toArray();
                        const categories = @json($categories->pluck('name', 'id'));

                        const formattedData = items.map((item, index) => ({
                            DT_RowIndex: index + 1,
                            name: item.name,
                            category_name: categories[item.category_id] || 'N/A',
                            actions: '<span class="text-muted small">Viewing offline</span>'
                        }));

                        callback({
                            draw: data.draw,
                            recordsTotal: formattedData.length,
                            recordsFiltered: formattedData.length,
                            data: formattedData
                        });
                    },
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'name', name: 'name' },
                        { data: 'category_name', name: 'category_name', orderable: false },
                        { data: 'actions', name: 'actions', orderable: false, searchable: false }
                    ]
                });
            });
        </script>
    </div>

    <!-- Create Medicine Modal -->
    <div aria-hidden="true" aria-labelledby="createMedicineModalLabel" class="modal fade" id="createMedicineModal"
        tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createMedicineModalLabel">Add New Medicine</h5>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route("medicines.store") }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="name">Medicine Name</label>
                            <input class="form-control" id="name" name="name" required type="text">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="category">Category</label>
                            <select class="form-select" name="category_id" required>
                                <option selected value="">--Select category--</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="pharmacy">Pharmacy: {{ session("pharmacy_name") }}</label>
                            <input hidden name="pharmacy_id" type="number"
                                value="{{ session("current_pharmacy_id") }}">
                        </div>
                        <button class="btn btn-success mb-2" type="submit">Save Medicine</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
