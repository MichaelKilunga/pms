@extends('medicines.app')

@section('content')
    <div class="container mt-4">
        <!-- Medicines List Section -->
        <div class="card-header bg-primary# p-2 m-2 text-white d-flex justify-content-between">
            <h3 class="m-2 text-primary">Available Medicines</h3>
            <a href="medicines" class="btn text-light btn-secondary m-2">
                {{ __('Back') }}
            </a>
        </div>
        <div class="card-body">
            <!-- Check if there are any medicines -->
            @if ($onlineMedicines->isEmpty())
                <div class="alert alert-info">No medicines found online.</div>
            @else
                <div class="responsive">
                    <table class="table table-striped table-bordered" id="Table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Generic Name</th>
                                <th>Brand Name</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($onlineMedicines as $medicine)
                                <tr id="medicine-row-{{ $medicine->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ Str::limit($medicine->generic_name, 100) ?? 'N/A' }}</td>
                                    <td>{{ $medicine->brand_name ?? 'N/A' }}</td>
                                    <td>{{ Str::limit($medicine->description, 100) ?? 'N/A' }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-warning import-button"
                                            data-medicine-id="{{ $medicine->id }}"
                                            data-medicine-name="{{ $medicine->brand_name }}">
                                            <i class="bi bi-download"></i>
                                        </button>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.import-button').on('click', function() {
                const button = $(this);
                const medicineId = button.data('medicine-id');
                const medicineName = button.data('medicine-name');

                // if (confirm(`Are you sure you want to import ${medicineName}?`)) {
                    // Change button to spinner
                    button.html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                            )
                        .prop('disabled', true);

                    $.ajax({
                        url: '{{ route('importStore') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            medicine_id: medicineId,
                        },
                        success: function(response) {
                            // Change button to success checkmark
                            button.removeClass('bg-warning');
                            button.removeClass('text-light');
                            button.addClass('text-success');
                            button.html('<i class="bi bi-check text-success"></i>');
                                // .prop('disabled', true);
                        },
                        error: function(xhr, status, error) {
                            // Reset button and show error message
                            button.html('<i class="bi bi-download"></i>')
                                .prop('disabled', false);
                            alert('An error occurred: ' + (xhr.responseJSON.message || error));
                        },
                    });
                // }
            });
        });
    </script>
@endsection
