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
                                        <button type="button" class="btn btn-sm btn-success import-button"
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
                        '<span class="spinner-border spinner-border-sm" style="width: 1rem; height: 1rem;" role="status" aria-hidden="true"></span>'
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
                        // .prop('disabled', true);
                        if (response.message == "duplicate") {
                            button.removeClass('btn-success');
                            button.addClass('btn-danger');
                            button.addClass('text-light');
                            button.html('<i class="bi bi-lock"></i>');
                            alert(
                                'This medicine has already been added to your medicines list!'
                            );
                        } else if (response.message == "maximum") {
                            $('.import-button').removeClass('btn-success');
                            $('.import-button').addClass('btn-danger');
                            $('.import-button').addClass('text-light');
                            $('.import-button').html('<i class="bi bi-lock"></i>')
                            $('.import-button').addClass('disabled');
                            alert(
                                'You\'ve reached the maximum number of medicine, please upgrade plan!'
                            );
                        } else {
                            button.removeClass('btn-success');
                            button.addClass('text-success');
                            button.addClass('btn-light');
                            button.html('<i class="bi bi-check"></i>');
                        }
                    },
                    error: function(xhr, status, error) {
                        // Reset button and show error message
                        button.removeClass('btn-success');
                        button.addClass('btn-danger');
                        button.addClass('text-light');
                        button.addClass('text-light');
                        button.html('<p>x</p>')
                            .prop('disabled', false);
                        alert('An error occurred: ' + (xhr.responseJSON.message || error));
                    },
                });
                // }
            });
        });
    </script>
@endsection
