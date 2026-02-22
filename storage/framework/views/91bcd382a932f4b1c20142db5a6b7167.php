<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    

    <div class="py-12#">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg dark:bg-gray-800">
                <?php if (isset($component)) { $__componentOriginal791d26948561d5a0da3d85fee400a7b6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal791d26948561d5a0da3d85fee400a7b6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.welcome','data' => ['filter' => $filter,'filteredTotalSales' => $filteredTotalSales,'lowStockCount' => $lowStockCount,'medicineNames' => $medicineNames,'medicineSales' => $medicineSales,'medicineStock' => $medicineStock,'medicines' => $medicines,'sellMedicines' => $sellMedicines,'stockExpired' => $stockExpired,'totalMedicines' => $totalMedicines,'totalPharmacies' => $totalPharmacies,'totalSales' => $totalSales,'totalStaff' => $totalStaff]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('welcome'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['filter' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($filter),'filteredTotalSales' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($filteredTotalSales),'lowStockCount' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($lowStockCount),'medicineNames' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($medicineNames),'medicineSales' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($medicineSales),'medicineStock' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($medicineStock),'medicines' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($medicines),'sellMedicines' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sellMedicines),'stockExpired' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($stockExpired),'totalMedicines' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($totalMedicines),'totalPharmacies' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($totalPharmacies),'totalSales' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($totalSales),'totalStaff' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($totalStaff)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal791d26948561d5a0da3d85fee400a7b6)): ?>
<?php $attributes = $__attributesOriginal791d26948561d5a0da3d85fee400a7b6; ?>
<?php unset($__attributesOriginal791d26948561d5a0da3d85fee400a7b6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal791d26948561d5a0da3d85fee400a7b6)): ?>
<?php $component = $__componentOriginal791d26948561d5a0da3d85fee400a7b6; ?>
<?php unset($__componentOriginal791d26948561d5a0da3d85fee400a7b6); ?>
<?php endif; ?>
            </div>
        </div>
        <!-- Suggested Stock Section -->
        <div class="mt-8 overflow-hidden bg-white shadow-xl sm:rounded-lg dark:bg-gray-800">
            <div class="border-b border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Suggested Stock for Re-stocking
                    </h3>
                    <div class="flex gap-2">
                        <a class="btn btn-primary btn-sm" href="<?php echo e(route('reports.suggested_stock.download')); ?>"
                            target="_blank">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Download PDF
                        </a>
                        <button class="btn btn-success btn-sm" onclick="sendToWhatsApp()" type="button">
                            <i class="bi bi-whatsapp me-1"></i> Request via WhatsApp
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table-hover small table" id="suggestedStockTable">

                        <thead>
                            <tr>
                                <th>Medicine Name</th>
                                <th>Suggested Qty</th>
                                <th>Unit Price</th>
                                <th>Total Price</th>
                                <th>Supplier</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Loaded via AJAX -->
                        </tbody>
                        <tfoot class="fw-bold bg-light">
                            <tr>
                                <td colspan="3" class="text-end">Grand Total Estimation:</td>
                                <td colspan="2" id="grandTotalEstimation">-</td>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="py-3 text-center" id="loadingStock">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div class="alert alert-info d-none" id="noStockMessage">
                        All stock levels are healthy!
                    </div>
                </div>
            </div>
        </div>

        <script>
            let suggestedStockDataTable;

            document.addEventListener('DOMContentLoaded', function() {
                fetchSuggestedStock();
            });

            function fetchSuggestedStock() {
                fetch('<?php echo e(route('reports.suggested_stock.json')); ?>')
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('loadingStock').classList.add('d-none');
                        const tbody = document.querySelector('#suggestedStockTable tbody');

                        // Destroy existing DataTable if it exists
                        if (suggestedStockDataTable) {
                            suggestedStockDataTable.destroy();
                        }

                        tbody.innerHTML = '';

                        if (data.stocks.length === 0) {
                            document.getElementById('noStockMessage').classList.remove('d-none');
                            document.getElementById('suggestedStockTable').classList.add('d-none');
                            return;
                        }

                        let grandTotal = 0;

                        data.stocks.forEach(stock => {
                            grandTotal += stock.total_buying_price;
                            const row = `
                                    <tr>
                                        <td>${stock.item.name}</td>
                                        <td>${new Intl.NumberFormat().format(stock.suggested_quantity)}</td>
                                        <td>${new Intl.NumberFormat(undefined, { minimumFractionDigits: 2 }).format(stock.unit_buying_price)}</td>
                                        <td>${new Intl.NumberFormat(undefined, { minimumFractionDigits: 2 }).format(stock.total_buying_price)}</td>
                                        <td>${stock.supplier || '-'}</td>
                                    </tr>
                                `;
                            tbody.innerHTML += row;
                        });

                        // Update Grand Total in Footer
                        document.getElementById('grandTotalEstimation').innerText = new Intl.NumberFormat(undefined, {
                            minimumFractionDigits: 2
                        }).format(grandTotal);

                        // Initialize DataTables
                        suggestedStockDataTable = $('#suggestedStockTable').DataTable({
                            paging: true,
                            searching: true,
                            ordering: true,
                            info: true,
                            pageLength: 10,
                            lengthMenu: [10, 25, 50, 100],
                            order: [
                                [0, 'asc']
                            ] // Sort by Medicine Name by default
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('loadingStock').innerHTML = 'Failed to load data.';
                    });
            }

            function sendToWhatsApp() {
                if (!confirm('Request this report to your WhatsApp number?')) return;

                fetch('<?php echo e(route('reports.suggested_stock.whatsapp')); ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                        },
                        body: JSON.stringify({}) // Phone number will be taken from Auth user by default
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        alert('System error occurred.');
                    });
            }
        </script>
    </div>
    </div>

    
    <?php if (\Illuminate\Support\Facades\Blade::check('hasrole', 'Owner')): ?>
        <div aria-hidden="true" aria-labelledby="pharmacyModalLabel" class="modal fade" id="pharmacyModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="pharmacyModalLabel">Select a Pharmacy</h5>
                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                    </div>
                    <form action="<?php echo e(route('pharmacies.set')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label" for="pharmacy_id">Pharmacy</label>
                                <select class="form-select" id="pharmacy_id" name="pharmacy_id" required>
                                    <option value="">-- Select a Pharmacy --</option>
                                    <?php $__currentLoopData = $pharmacies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pharmacy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($pharmacy->id); ?>"><?php echo e($pharmacy->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
                            <button class="btn btn-primary" type="submit">Select</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH /media/michaelkilunga/C/SKYLINK/pms/resources/views/dashboard.blade.php ENDPATH**/ ?>