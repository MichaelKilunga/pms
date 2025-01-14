<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                {{-- <x-welcome :filteredTotalSales="$filteredTotalSales" :filter="$filter" :stockExpired="$stockExpired" :totalPharmacies="$totalPharmacies" :totalStaff="$totalStaff" :totalMedicines="$totalMedicines" :totalSales="$totalSales" :medicineSales="$medicineSales" :medicineStock="$medicineStock" :lowStockCount="$lowStockCount" :medicines="$medicines" :medicineNames="$medicineNames" /> --}}
            </div>
        </div>
    </div>

    <div class="modal fade" id="guestPharmacyModal" tabindex="-1" aria-labelledby="pharmacyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header  text-light  bg-primary ">
                    <h5 class="modal-title text-center" id="pharmacyModalLabel">Create a Pharmacy to continue</h5>
                    <button type="button" class="btn-close btn-light" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('pharmacies.store') }}" method="POST">
                    @csrf
                    {{-- <input type="number" hidden name="owner_id" value="{{ Auth::user()->id }}"> --}}
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Pharmacy Name</label>
                            <input type="text" class="form-control rounded-3" name="name">
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="location" class="form-label">Pharmacy Location</label>
                            <input type="text" class="form-control  rounded-3" name="location">
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
