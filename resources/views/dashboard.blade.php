<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot> --}}

    <div class="py-12#">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <x-welcome :sellMedicines="$sellMedicines" :filteredTotalSales="$filteredTotalSales" :filter="$filter" :stockExpired="$stockExpired" :totalPharmacies="$totalPharmacies" :totalStaff="$totalStaff" :totalMedicines="$totalMedicines" :totalSales="$totalSales" :medicineSales="$medicineSales" :medicineStock="$medicineStock" :lowStockCount="$lowStockCount" :medicines="$medicines" :medicineNames="$medicineNames" />
            </div>
        </div>
    </div>

    {{-- <x-pharmacy-selection-modal :pharmacies="$pharmacies" /> --}}
    @if (Auth::user()->role == 'owner')
        <div class="modal fade" id="pharmacyModal" tabindex="-1" aria-labelledby="pharmacyModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="pharmacyModalLabel">Select a Pharmacy</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('pharmacies.set') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="pharmacy_id" class="form-label">Pharmacy</label>
                                <select name="pharmacy_id" id="pharmacy_id" class="form-select" required>
                                    <option value="">-- Select a Pharmacy --</option>
                                    @foreach ($pharmacies as $pharmacy)
                                        <option value="{{ $pharmacy->id }}">{{ $pharmacy->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Select</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

</x-app-layout>
