<?php $__env->startSection("content"); ?>
    <?php $medicines = App\Models\Items::where('pharmacy_id', session('current_pharmacy_id'))->with('lastStock')->get(); ?>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="h3 text-primary">Sales Notes</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 gap-2">
                <a class="btn btn-outline-secondary" href="<?php echo e(route("salesNotes", ["filter" => false])); ?>">All</a>
                <a class="btn btn-outline-secondary" href="<?php echo e(route("salesNotes", ["filter" => true])); ?>">Today Notes</a>
            </div>
            <div class="col-md-6 d-flex mt-2 gap-2">
                <?php if (! \Illuminate\Support\Facades\Blade::check('role', "Staff")): ?>
                    <button class="btn btn-warning text-dark" id="buttonToPromoteAll"><small class="smallest">Promote
                            All</small></button>
                    <button class="btn btn-warning text-dark" id="buttonToPromoteSelected"><small class="smallest">Promote
                            Separate</small></button>
                    <button class="btn btn-warning text-dark" id="buttonToPromoteSelectedAsOne"><small class="smallest">Promote
                            as One</small></button>
                    <button class="btn btn-warning text-dark" id="buttonToPromoteToExistingStock"><small
                            class="smallest">Promote To Existing</small></button>
                <?php endif; ?>
                <button class="btn btn-primary" data-bs-target="#createSalesNoteModal" data-bs-toggle="modal"><small
                        class="md-smallest"><i class="bi bi-plus-circle"></i> New</small></button>
            </div>
        </div>

        <div class="table-responsive mt-3">
            
            <div class="d-flex justify-content-end align-items-center mb-3">
                <div class="mx-2 text-end"><span class="text-primary"> Total Sales Note: </span> <span
                        class="text-danger"><?php echo e($salesNotes->count()); ?></span>, </div>
                <div class="mx-2 text-end"> <span class="text-primary">Total Amount:</span>
                    <span class="text-danger"><?php echo e(number_format($totalSalesMadeToday)); ?>/=</span> TZS
                </div>
            </div>
            <table class="table-bordered table-striped small table" id="SaleNoteTable">
                <thead class="table-light">
                    <tr>
                        <th class="hidden" hidden></th>
                        <th>#</th>
                        <th class="name-column#">Name</th>
                        <th>Quantity</th>
                        <th>Unit Price (TZS)</th>
                        <th>Total Price (TZS)</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $salesNotes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $salesNote): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        
                        <tr>
                            <td class="hidden" hidden> <?php echo e($salesNote->id); ?> </td>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td class="name-column"><?php echo e($salesNote->name); ?></td>
                            <td><?php echo e($salesNote->quantity); ?></td>
                            <td><?php echo e($salesNote->unit_price); ?></td>
                            <td><?php echo e(number_format($salesNote->unit_price * $salesNote->quantity)); ?></td>
                            <td><?php echo e($salesNote->status); ?></td>
                            <td><?php echo e(\Carbon\Carbon::parse($salesNote->created_at)->diffForHumans()); ?></td>
                            <td>
                                <div class="d-flex justify-content-between">
                                    <?php if (! \Illuminate\Support\Facades\Blade::check('role', "Staff")): ?>
                                        <div>
                                            <button class="btn btn-success promoteButton"><i class="bi bi-upload">
                                                    Promote</i>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <button class="btn btn-primary"
                                            data-bs-target="#showSalesNoteModal<?php echo e($salesNote->id); ?>"
                                            data-bs-toggle="modal"><i class="bi bi-eye"></i></button>
                                    </div>
                                    <div>
                                        <button class="btn btn-warning"
                                            data-bs-target="#editSalesNoteModal<?php echo e($salesNote->id); ?>"
                                            data-bs-toggle="modal"><i class="bi bi-pencil"></i></button>
                                    </div>
                                    
                                    <form action="<?php echo e(route("salesNotes.destroy", $salesNote->id)); ?>" class=""
                                        method="POST">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field("DELETE"); ?>
                                        <button class="btn btn-danger" onclick="return confirm('Do you want to delete?')"
                                            type="submit"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        

                        
                        <div aria-hidden="true" aria-labelledby="editSalesNoteModalLabel<?php echo e($salesNote->id); ?>"
                            class="modal fade" id="editSalesNoteModal<?php echo e($salesNote->id); ?>" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="<?php echo e(route("salesNotes.update")); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field("PUT"); ?>
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editSalesNoteModalLabel<?php echo e($salesNote->id); ?>">
                                                Edit Sales Note</h5>
                                            <button aria-label="Close" class="close" data-bs-dismiss="modal"
                                                type="button">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body d-flex flex-column">
                                            <div class="form-floating mb-2">
                                                <input class="form-control" id="floatingName" name="name"
                                                    placeholder="Amoxicillin" required type="text"
                                                    value="<?php echo e($salesNote->name); ?>">
                                                <label class="form-label" for="floatingName">Medicine Name</label>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-md-6 form-floating">
                                                    <input class="form-control" id="floatingQuantity" min="1"
                                                        name="quantity" placeholder="50" required type="number"
                                                        value="<?php echo e($salesNote->quantity); ?>">
                                                    <label class="form-label" for="floatingQuantity">Quantity</label>
                                                </div>
                                                <div class="col-md-6 form-floating">
                                                    <input class="form-control" id="floatingUnitPrice" min="1"
                                                        name="unit_price" placeholder="200" required type="number"
                                                        value="<?php echo e($salesNote->unit_price); ?>">
                                                    <label class="form-label" for="floatingUnitPrice">Unit Price</label>
                                                </div>
                                            </div>
                                            <div class="form-floating mb-2">
                                                <input class="form-control" id="floatingDescription" name="description"
                                                    placeholder="Sold for headache" type="text"
                                                    value="<?php echo e($salesNote->description); ?>">
                                                <label class="form-label" for="floatingDescription">Description</label>
                                            </div>

                                            <input hidden name="pharmacy_id" placeholder="Pharmacy ID" readonly required
                                                type="text" value="<?php echo e($salesNote->pharmacy_id); ?>">
                                            <input hidden name="staff_id" placeholder="Staff ID" readonly required
                                                type="text" value="<?php echo e($salesNote->staff_id); ?>">
                                            <input hidden name="id" placeholder="Note ID" readonly required
                                                type="number" value="<?php echo e($salesNote->id); ?>">
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal"
                                                type="button">Close</button>
                                            <button class="btn btn-primary" type="submit">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        
                        <div aria-hidden="true" aria-labelledby="showSalesNoteModalLabel<?php echo e($salesNote->id); ?>"
                            class="modal fade bd-example-modal-lg" id="showSalesNoteModal<?php echo e($salesNote->id); ?>"
                            role="dialog">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="showSalesNoteModalLabel<?php echo e($salesNote->id); ?>">Show
                                            Sales Note</h5>
                                        <button aria-label="Close" class="close" data-bs-dismiss="modal"
                                            type="button">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Name:</strong> <?php echo e($salesNote->name); ?></p>
                                                <p><strong>Quantity:</strong> <?php echo e($salesNote->quantity); ?></p>
                                                <p><strong>Unit Price:</strong> <?php echo e($salesNote->unit_price); ?></p>
                                                <p><strong>Status:</strong> <?php echo e($salesNote->status); ?></p>
                                                <p><strong>Description:</strong> <?php echo e($salesNote->description); ?></p>
                                                <p><strong>Created at:</strong> <?php echo e($salesNote->created_at); ?></p>
                                                
                                                <p><strong>Added by:</strong>
                                                    <?php echo e($salesNote->staff_id == Auth::user()->id ? "You!" : $salesNote->staff->name); ?>

                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-bs-dismiss="modal"
                                            type="button">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div aria-hidden="true" aria-labelledby="createSalesNoteModalLabel" class="modal fadetext-white modal-lg"
        id="createSalesNoteModal" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="<?php echo e(route("salesNotes.store")); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="createSalesNoteModalLabel">Create Sales Note</h5>
                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                    </div>
                    <div class="modal-body d-flex flex-column">
                        <div class="form-floating mb-2">
                            <input class="form-control" id="floatingName" name="name" placeholder="Amoxicillin"
                                required type="text">
                            <label class="form-label" for="floatingName">Medicine Name<span
                                    class="text-danger">*</span></label>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-3 form-floating">
                                <input class="form-control" id="floatingQuantity" min="1" name="quantity"
                                    placeholder="50" required type="number">
                                <label class="form-label" for="floatingQuantity">Quantity<span
                                        class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-5 form-floating">
                                <input class="form-control" id="floatingUnitPrice" min="1" name="unit_price"
                                    placeholder="200" required type="number">
                                <label class="form-label" for="floatingUnitPrice">Unit Selling Price<span
                                        class="text-danger">*</span></label>
                            </div>
                            
                            <div class="col-md-4 form-floating">
                                <input class="form-control fw-bold text-success" id="floatingTotalPrice"
                                    name="total_price" placeholder="100000" readonly type="text">
                                <label class="form-label fw-bold text-dark" for="TotalAmount">Total Price
                                    <span class="text-danger">*</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-floating mb-2">
                            <input class="form-control" id="floatingDescription" name="description"
                                placeholder="Sold for headache" type="text">
                            <label class="form-label" for="floatingDescription">Description <span
                                    class="text-success">(optional)</span></label>
                        </div>

                        <input hidden name="pharmacy_id" placeholder="Pharmacy ID" readonly required type="text"
                            value="<?php echo e(session("current_pharmacy_id")); ?>">
                        <input hidden name="staff_id" placeholder="Staff ID" readonly required type="text"
                            value="<?php echo e(auth()->id()); ?>">
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
                        <button class="btn btn-primary" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="promoteSelectedSaleNoteModal" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <form action="<?php echo e(route("salesNotes.promote")); ?>" id="promoteSaleNoteForm" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-header">
                        <h5 class="modal-title">Promote Selected Sales Notes</h5>

                        <button class="close" data-bs-dismiss="modal" type="button">&times;</button>
                    </div>
                    <div class="modal-body" id="promotionPannel">

                        

                    </div>
                    <div class="justify-content-between modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal" id="closePromotionModal"
                            type="button">Close</button>
                        <button class="btn btn-success" id="submitPromotion" type="submit">Promote</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="promoteSelectedSaleNoteAsOneModal" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <form action="<?php echo e(route("salesNotes.promoteAsOne")); ?>" id="promoteSaleNoteAsOneForm" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-header">
                        <h5 class="modal-title">Promote Selected Sales Notes as One Item</h5>
                        
                        <button class="btn btn-outline-success promoteToExistingItems" id="promoteAsOneToExistingItems"
                            type="button">Promote to Existing
                            items </button>
                        
                        <button class="btn btn-outline-success promoteToExistingStocks" id="promoteAsOneToExistingStocks"
                            type="button">Promote to
                            Existing Stocks </button>
                        <button class="close" data-bs-dismiss="modal" type="button">&times;</button>
                    </div>
                    <div class="modal-body" id="asOnePromotionPannel">

                        

                    </div>
                    <div class="justify-content-between modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal" id="closeAsOnePromotionModal"
                            type="button">Close</button>
                        <button class="btn btn-success" id="submitPromotion" type="submit">Promote</button>
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
            var promoteToExisting = false;

            // Initialize chosen/select2
            function initializeChosen() {
                $(".chosen").each(function() {
                    let $select = $(this);
                    let $modal = $select.closest(
                        ".modal"); // Check if inside a modal
                    $select.select2({
                        width: "100%",
                        no_results_text: "No matches found!",
                        allowClear: true,
                        dropdownParent: $modal.length ? $modal : $(
                            "body") // Use modal if inside one
                    });
                }).on("change", function() {
                    const row = $(this).closest(".stock-entry")[0];
                    setStockFieldsData(row);
                });
            }

            function setStockFieldsData(row) {
                const medicines = <?php echo json_encode($medicines, 15, 512) ?>;
                const selectedMedicineId = $(row).find('[name="item_id"]').val();

                // Find the selected medicine
                const selectedMedicine = medicines.find(medicine => medicine.id == selectedMedicineId);

                if (selectedMedicine && selectedMedicine.last_stock) {
                    // Set values using jQuery
                    $(row).find('[name="buying_price"]').val(selectedMedicine.last_stock.buying_price);
                    // $(row).find('[name="selling_price"]').val(selectedMedicine.last_stock.selling_price);
                    $(row).find('[name="low_stock_quantity"]').val(selectedMedicine.last_stock
                        .low_stock_percentage);
                } else {
                    // Clear the fields
                    $(row).find('[name="buying_price"], [name="low_stock_quantity"]').val(
                        '');
                }
            }


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
            //end

            $('.promoteButton')
                .on('click', function(e) {

                    e.preventDefault();

                    var saleNoteId = $(this).closest('tr').find('td:first')
                        .text(); // Get sale note ID from first column

                    // Populate the modal with selected IDs
                    //Edit the body of the modal to include rows of each item
                    $('#promotionPannel').empty(); // Clear previous rows
                    // Loop through all rows and add them to the modal
                    $('#promotionPannel').append(`
                            <div class="form-group mb-3 row">
                            <div class="col-md-4 form-floating">
                                <input class="form-control batch_number" type="text" id="batch_number" name="batch_number"
                                    placeholder="Batch Number" required readonly>
                                <label class="form-label" for="batch_number">Batch Number</label>
                            </div>
                            <div class="col-md-4 form-floating">
                                <input class="form-control" type="text" name="supplier_name"
                                    placeholder="Supplier Name" required>
                                <label class="form-label" for="supplier_name">Supplier Name</label>
                            </div>
                            <div class="col-md-4 form-floating">
                                <input class="form-control" type="date" value="<?php echo e(date("Y-m-d")); ?>"
                                    name="date" placeholder="Entry Date" required>
                                <label class="form-label" for="date">Entry Date</label>
                            </div>
                    </div>`);


                    // selectedSalesNotes.forEach(function(saleNoteId) {
                    // Find the row with the matching ID
                    var row = table.row(`:contains(${saleNoteId})`);
                    // Get the row name and quantity from the third column
                    var rowName = $(row.node()).find('td:nth-child(3)').text();
                    var rowQuantity = $(row.node()).find('td:nth-child(4)').text();
                    var rowUnitPrice = $(row.node()).find('td:nth-child(5)').text();

                    // Append the row to the modal
                    $('#promotionPannel').append(
                        `
                                <div class="form-group mb-3 row">
                                    <div hidden class="hidden form-floating">
                                        <input readonly type="text" required name="sale_note_id[]" value="${saleNoteId}">
                                    </div>
                                    <div class="col-md-2 form-floating">
                                        <input class="form-control" type="text" required name="name[]"
                                            placeholder="Name" value="${rowName}">
                                        <label class="form-label" for="name">Name</label>
                                    </div>
                                    <div class="col-md-2 form-floating">
                                        <input class="form-control" type="number" required name="buying_price[]"
                                            placeholder="Buying Price">
                                        <label class="form-label" for="buying_price">Buying Price</label>
                                    </div>
                                    <div class="col-md-1 form-floating">
                                        <input class="form-control" type="number" required name="selling_price[]"
                                            placeholder="Selling Price" readonly value="${rowUnitPrice}">
                                        <label class="form-label" for="selling_price">Unit Price</label>
                                    </div>
                                    <div class="col-md-2 form-floating">
                                        <input class="form-control" type="number" min="${rowQuantity}" required name="stocked_quantity[]"
                                            placeholder="Stocked Quantity" value="${rowQuantity}">
                                        <label class="form-label" for="stocked_quantity">Quantity</label>
                                    </div>
                                    <div class="col-md-2 form-floating">
                                        <input class="form-control" type="number" required name="low_stock_quantity[]" placeholder="Low Stock Quantity">
                                        <label class="form-label" for="low_stock_quantity">Low Stock</label>
                                    </div>
                                    <div class="col-md-2 form-floating">
                                        <input class="form-control" type="date"
                                            required name="expiry_date[]" placeholder="Expiry Date">
                                        <label class="form-label" for="expiry_date">Expire Date</label>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger btn-sm removeRow"><i class="bi bi-x-lg"></i></button>
                                    </div>
                                </div>`
                    );
                    // });

                    // genarate batch number
                    $(document).ready(function() {
                        const today = new Date();
                        const year = today.getFullYear(); // Get the full year
                        const month = String(today.getMonth() + 1).padStart(2,
                            '0'); // Months are zero-based, pad with leading zero if needed
                        const day = String(today.getDate()).padStart(2,
                            '0'); // Pad day with leading zero if needed
                        const formattedDate =
                            `${year}${month}${day}`; // Combine to form YYYYMMDD format
                        $('.batch_number').val(
                            formattedDate); // Use .val() to set the value of the input
                    });

                    // Show the modal
                    $('#promoteSelectedSaleNoteModal').modal('show');
                });
            //end

            // Open the modal when "Promote all" button is clicked
            $('#buttonToPromoteAll').on('click', function(e) {
                e.preventDefault();

                //Edit the body of the modal to include rows of each item
                $('#promotionPannel').empty(); // Clear previous rows
                // Loop through all rows and add them to the modal
                $('#promotionPannel').append(`
                            <div class="form-group mb-3 row">
                            <div class="col-md-4 form-floating">
                                <input class="form-control batch_number" type="text" id="batch_number" name="batch_number"
                                    placeholder="Batch Number" required readonly>
                                <label class="form-label" for="batch_number">Batch Number</label>
                            </div>
                            <div class="col-md-4 form-floating">
                                <input class="form-control" type="text" name="supplier_name" required
                                    placeholder="Supplier Name">
                                <label class="form-label" for="supplier_name">Supplier Name</label>
                            </div>
                            <div class="col-md-4 form-floating">
                                <input class="form-control" type="date" value="<?php echo e(date("Y-m-d")); ?>"
                                    name="date" required placeholder="Entry Date">
                                <label class="form-label" for="date">Entry Date</label>
                            </div>
                            </div>`);


                table.rows().every(function() {
                    // get the current tr
                    var row = this.node();
                    // Get sale note ID from first column
                    var saleNoteId = $(row).find('td:first')
                        .text(); // Get sale note ID from first column
                    // Get the row name and quantity from the third column
                    var rowName = $(row).find('td:nth-child(3)').text();
                    var rowQuantity = $(row).find('td:nth-child(4)').text();
                    var rowUnitPrice = $(row).find('td:nth-child(5)').text();
                    // Append the row to the modal
                    $('#promotionPannel').append(
                        `
                        <div class="form-group mb-3 row">
                            <div hidden class="hidden form-floating">
                                <input readonly type="text"  required name="sale_note_id[]" value="${saleNoteId}">
                            </div>
                            <div class="col-md-2 form-floating">
                                <input class="form-control" type="text" name="name[]"
                                    placeholder="Name" value="${rowName}" required>
                                <label class="form-label" for="name">Name</label>
                            </div>
                            <div class="col-md-2 form-floating">
                                <input class="form-control" type="number" name="buying_price[]"
                                    placeholder="Buying Price" required>
                                <label class="form-label" for="buying_price">Buying Price</label>
                            </div>
                            <div class="col-md-1 form-floating">
                                <input class="form-control" type="number" name="selling_price[]"
                                    placeholder="Selling Price" value="${rowUnitPrice}" readonly required>
                                <label class="form-label" for="selling_price">Selling Price</label>
                            </div>
                            <div class="col-md-2 form-floating">
                                <input class="form-control" type="number" min="${rowQuantity}" name="stocked_quantity[]"
                                    placeholder="Stocked Quantity" value="${rowQuantity}" required>
                                <label class="form-label" for="stocked_quantity">Quantity</label>
                            </div>
                            <div class="col-md-2 form-floating">
                                <input class="form-control" type="number" name="low_stock_quantity[]"required placeholder="Low Stock Quantity">
                                <label class="form-label" for="low_stock_quantity">Low Stock</label>
                            </div>
                            <div class="col-md-2 form-floating">
                                <input class="form-control" type="date"
                                    name="expiry_date[]" placeholder="Expiry Date" required>
                                <label class="form-label" for="expiry_date">Expire Date</label>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger btn-sm removeRow"><i class="bi bi-x-lg"></i></button>
                            </div>
                        </div>`
                    );
                });

                $(document).ready(function() {
                    const today = new Date();
                    const year = today.getFullYear(); // Get the full year
                    const month = String(today.getMonth() + 1).padStart(2,
                        '0'); // Months are zero-based, pad with leading zero if needed
                    const day = String(today.getDate()).padStart(2,
                        '0'); // Pad day with leading zero if needed
                    const formattedDate =
                        `${year}${month}${day}`; // Combine to form YYYYMMDD format
                    $('.batch_number').val(
                        formattedDate); // Use .val() to set the value of the input
                });

                // Show the modal
                $('#promoteSelectedSaleNoteModal').modal('show');
            });

            // Open the modal when "Promote separate" button is clicked
            $('#buttonToPromoteSelected').on('click', function(e) {
                e.preventDefault();

                if (selectedSalesNotes.length === 0) {
                    alert('Please select at least one sales note to promote.');
                    return;
                }

                // Populate the modal with selected IDs
                //Edit the body of the modal to include rows of each item
                $('#promotionPannel').empty(); // Clear previous rows
                // Loop through all rows and add them to the modal
                $('#promotionPannel').append(`
                            <div class="form-group mb-3 row">
                            <div class="col-md-4 form-floating">
                                <input class="form-control batch_number" type="text" id="batch_number" name="batch_number"
                                    placeholder="Batch Number" required readonly>
                                <label class="form-label" for="batch_number">Batch Number</label>
                            </div>
                            <div class="col-md-4 form-floating">
                                <input class="form-control" type="text" name="supplier_name"
                                    placeholder="Supplier Name" required>
                                <label class="form-label" for="supplier_name">Supplier Name</label>
                            </div>
                            <div class="col-md-4 form-floating">
                                <input class="form-control" type="date" value="<?php echo e(date("Y-m-d")); ?>"
                                    name="date" placeholder="Entry Date" required>
                                <label class="form-label" for="date">Entry Date</label>
                            </div>
                            </div>`);


                selectedSalesNotes.forEach(function(saleNoteId) {
                    // Find the row with the matching ID
                    var row = table.row(`:contains(${saleNoteId})`);
                    // Get the row name and quantity from the third column
                    var rowName = $(row.node()).find('td:nth-child(3)').text();
                    var rowQuantity = $(row.node()).find('td:nth-child(4)').text();
                    var rowUnitPrice = $(row.node()).find('td:nth-child(5)').text();

                    // Append the row to the modal
                    $('#promotionPannel').append(
                        `
                        <div class="form-group mb-3 row">
                            <div hidden class="hidden form-floating">
                                <input readonly type="text" required name="sale_note_id[]" value="${saleNoteId}">
                            </div>
                            <div class="col-md-2 form-floating">
                                <input class="form-control" type="text" required name="name[]"
                                    placeholder="Name" value="${rowName}">
                                <label class="form-label" for="name">Name</label>
                            </div>
                            <div class="col-md-2 form-floating">
                                <input class="form-control" type="number" required name="buying_price[]"
                                    placeholder="Buying Price">
                                <label class="form-label" for="buying_price">Buying Price</label>
                            </div>
                            <div class="col-md-1 form-floating">
                                <input class="form-control" type="number" required name="selling_price[]"
                                    placeholder="Selling Price" readonly value="${rowUnitPrice}">
                                <label class="form-label" for="selling_price">Unit Price</label>
                            </div>
                            <div class="col-md-2 form-floating">
                                <input class="form-control" type="number" min="${rowQuantity}" required name="stocked_quantity[]"
                                    placeholder="Stocked Quantity" value="${rowQuantity}">
                                <label class="form-label" for="stocked_quantity">Quantity</label>
                            </div>
                            <div class="col-md-2 form-floating">
                                <input class="form-control" type="number" required name="low_stock_quantity[]" placeholder="Low Stock Quantity">
                                <label class="form-label" for="low_stock_quantity">Low Stock</label>
                            </div>
                            <div class="col-md-2 form-floating">
                                <input class="form-control" type="date"
                                    required name="expiry_date[]" placeholder="Expiry Date">
                                <label class="form-label" for="expiry_date">Expire Date</label>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger btn-sm removeRow"><i class="bi bi-x-lg"></i></button>
                            </div>
                        </div>`
                    );
                });

                // genarate batch number
                $(document).ready(function() {
                    const today = new Date();
                    const year = today.getFullYear(); // Get the full year
                    const month = String(today.getMonth() + 1).padStart(2,
                        '0'); // Months are zero-based, pad with leading zero if needed
                    const day = String(today.getDate()).padStart(2,
                        '0'); // Pad day with leading zero if needed
                    const formattedDate =
                        `${year}${month}${day}`; // Combine to form YYYYMMDD format
                    $('.batch_number').val(
                        formattedDate); // Use .val() to set the value of the input
                });

                // Show the modal
                $('#promoteSelectedSaleNoteModal').modal('show');
            });

            // Open the modal for promoting as one when "Promote as one" button is clicked
            $('#buttonToPromoteSelectedAsOne').on('click', function(e) {
                e.preventDefault();

                if (selectedSalesNotes.length === 0) {
                    alert('Please select at least one sales note to promote.');
                    return;
                }

                // Populate the modal with selected IDs
                var hasSameUnitPrice = true;
                var firstSelectedUnitPrice = 0;
                var firstSelectedName = '';
                var differentNames = false;
                var sumOfAllQuantities = 0;
                //Edit the body of the modal to include rows of each item
                $('#asOnePromotionPannel').empty(); // Clear previous rows
                // Loop through all rows and add them to the modal
                $('#asOnePromotionPannel').append(`
                            <div class="form-group mb-3 row">
                            <div class="col-md-4 form-floating">
                                <input class="form-control batch_number" type="text" id="batch_number" name="batch_number"
                                    placeholder="Batch Number" required readonly>
                                <label class="form-label" for="batch_number">Batch Number</label>
                            </div>
                            <div class="col-md-4 form-floating">
                                <input class="form-control" type="text" name="supplier_name"
                                    placeholder="Supplier Name" required>
                                <label class="form-label" for="supplier_name">Supplier Name</label>
                            </div>
                            <div class="col-md-4 form-floating">
                                <input class="form-control" type="date" value="<?php echo e(date("Y-m-d")); ?>"
                                    name="date" placeholder="Entry Date" required>
                                <label class="form-label" for="date">Entry Date</label>
                            </div>
                            </div>
                            <div class="form-group mb-3 row">
                                <span class="col-md-12 row text-primary" id="selectedSalesNotesNames"></span>
                            </div>
                                `);


                selectedSalesNotes.forEach(function(saleNoteId) {
                    // Find the row with the matching ID
                    var row = table.row(`:contains(${saleNoteId})`);
                    // Get the row name and quantity from the third column
                    var rowName = $(row.node()).find('td:nth-child(3)').text();
                    var rowQuantity = $(row.node()).find('td:nth-child(4)').text();
                    sumOfAllQuantities += parseInt(rowQuantity);
                    var UnitPrice = $(row.node()).find('td:nth-child(5)').text();

                    // Compare the unit price of the first selected sales note to the all other selected sales notes, if they are not the same, then exit the loop
                    if (selectedSalesNotes.indexOf(saleNoteId) === 0) {
                        firstSelectedUnitPrice = UnitPrice;
                        firstSelectedName = rowName;
                    }

                    if (UnitPrice !== firstSelectedUnitPrice) {
                        hasSameUnitPrice = false;
                        alert('Select sales notes with the same unit price.');
                    }


                    // Check if all selected sales notes have the same unit price
                    if (!hasSameUnitPrice) {
                        // exit the loop if the unit price is not the same
                        return;
                    } else if (rowName.toLowerCase().indexOf(firstSelectedName.toLowerCase()) < 0 &&
                        !differentNames) {
                        // if firstSelectedName is not 80% equal to rowName, then alert but don't exit the loop
                        differentNames = true;
                        alert('Names are not 80% equal. [' + rowName + '] and [' +
                            firstSelectedName + ']');
                    }

                    // Append the list of selected sales notes names with their quantities  and unit prices at id="selectedSalesNotesNames"
                    $('#selectedSalesNotesNames').append(`
                                <p class="col-md-6 mb-3"><strong class="text-dark">Name:</strong> ${rowName} | <strong class="text-dark">Quantity:</strong> ${rowQuantity}</p>
                            `);
                });

                // Append the row to the modal
                $('#asOnePromotionPannel').append(
                    `
                        <div class="form-group mb-3 row">
                            <div hidden class="hidden form-floating">
                                <input readonly type="text" required name="sale_note_ids" value="${selectedSalesNotes.join(',')}">
                            </div>
                            <div class="col-md-2 form-floating">
                                <input class="form-control" type="text" required name="name"
                                    placeholder="Name">
                                <label class="form-label" for="name">Name</label>
                            </div>
                            <div class="col-md-2 form-floating">
                                <input class="form-control" type="number" required name="buying_price"
                                    placeholder="Buying Price">
                                <label class="form-label" for="buying_price">Buying Price</label>
                            </div>
                            <div class="col-md-2 form-floating">
                                <input class="form-control" type="number" required name="selling_price"
                                    placeholder="Selling Price" value="${firstSelectedUnitPrice}" readonly>
                                <label class="form-label" for="selling_price">Unit Price</label>
                            </div>
                            <div class="col-md-2 form-floating">
                                <input class="form-control" type="number" min="${sumOfAllQuantities}" required name="stocked_quantity"
                                    placeholder="Stocked Quantity" value="${sumOfAllQuantities}">
                                <label class="form-label" for="stocked_quantity">Quantity</label>
                            </div>
                            <div class="col-md-2 form-floating">
                                <input class="form-control" type="number" required name="low_stock_quantity" placeholder="Low Stock Quantity">
                                <label class="form-label" for="low_stock_quantity">Low Stock</label>
                            </div>
                            <div class="col-md-2 form-floating">
                                <input class="form-control" type="date"
                                    required name="expiry_date" placeholder="Expiry Date">
                                <label class="form-label" for="expiry_date">Expire Date</label>
                            </div>
                        </div>`
                );

                // genarate batch number
                $(document).ready(function() {
                    const today = new Date();
                    const year = today.getFullYear(); // Get the full year
                    const month = String(today.getMonth() + 1).padStart(2,
                        '0'); // Months are zero-based, pad with leading zero if needed
                    const day = String(today.getDate()).padStart(2,
                        '0'); // Pad day with leading zero if needed
                    const formattedDate =
                        `${year}${month}${day}`; // Combine to form YYYYMMDD format
                    $('.batch_number').val(
                        formattedDate); // Use .val() to set the value of the input
                });

                // Show the modal
                if (hasSameUnitPrice) {
                    $('#promoteSelectedSaleNoteAsOneModal').modal('show');
                }
            });

            // Open the modal for promoting to Existing when "Promote to existing" button is clicked
            $('#buttonToPromoteToExistingStock').on('click', function(e) {
                e.preventDefault();

                if (selectedSalesNotes.length === 0) {
                    alert('Please select at least one sales note to promote.');
                    return;
                }

                // Populate the modal with selected IDs
                promoteToExisting = true;
                var hasSameUnitPrice = true;
                var firstSelectedUnitPrice = 0;
                var firstSelectedName = '';
                var differentNames = false;
                var sumOfAllQuantities = 0;
                //Edit the body of the modal to include rows of each item
                $('#asOnePromotionPannel').empty(); // Clear previous rows
                // Loop through all rows and add them to the modal
                $('#asOnePromotionPannel').append(`
                            <div class="form-group mb-3 row">
                            <div class="col-md-4 form-floating">
                                <input class="form-control batch_number" type="text" id="batch_number" name="batch_number"
                                    placeholder="Batch Number" required readonly>
                                <label class="form-label" for="batch_number">Batch Number</label>
                            </div>
                            <div class="col-md-4 form-floating">
                                <input class="form-control" type="text" name="supplier_name"
                                    placeholder="Supplier Name" required>
                                <label class="form-label" for="supplier_name">Supplier Name</label>
                            </div>
                            <div class="col-md-4 form-floating">
                                <input class="form-control" type="date" value="<?php echo e(date("Y-m-d")); ?>"
                                    name="date" placeholder="Entry Date" required>
                                <label class="form-label" for="date">Entry Date</label>
                            </div>
                            </div>
                            <div class="form-group mb-3 row">
                                <span class="col-md-12 row text-primary" id="selectedSalesNotesNames"></span>
                            </div>
                `);

                selectedSalesNotes.forEach(function(saleNoteId) {
                    // Find the row with the matching ID
                    var row = table.row(`:contains(${saleNoteId})`);
                    // Get the row name and quantity from the third column
                    var rowName = $(row.node()).find('td:nth-child(3)').text();
                    var rowQuantity = $(row.node()).find('td:nth-child(4)').text();
                    sumOfAllQuantities += parseInt(rowQuantity);
                    var UnitPrice = $(row.node()).find('td:nth-child(5)').text();

                    // Compare the unit price of the first selected sales note to the all other selected sales notes, if they are not the same, then exit the loop
                    if (selectedSalesNotes.indexOf(saleNoteId) === 0) {
                        firstSelectedUnitPrice = UnitPrice;
                        firstSelectedName = rowName;
                    }

                    if (UnitPrice !== firstSelectedUnitPrice) {
                        hasSameUnitPrice = false;
                        alert('Select sales notes with the same unit price.');
                    }


                    // Check if all selected sales notes have the same unit price
                    if (!hasSameUnitPrice) {
                        // exit the loop if the unit price is not the same
                        return;
                    } else if (rowName.toLowerCase().indexOf(firstSelectedName.toLowerCase()) < 0 &&
                        !differentNames) {
                        // if firstSelectedName is not 80% equal to rowName, then alert but don't exit the loop
                        differentNames = true;
                        alert('Names are not 80% equal. [' + rowName + '] and [' +
                            firstSelectedName + ']');
                    }

                    // Append the list of selected sales notes names with their quantities  and unit prices at id="selectedSalesNotesNames"
                    $('#selectedSalesNotesNames').append(`
                                <p class="col-md-6 mb-3"><strong class="text-dark">Name:</strong> ${rowName} | <strong class="text-dark">Quantity:</strong> ${rowQuantity}</p>
                            `);
                });

                // Append the row to the modal
                $('#asOnePromotionPannel').append(
                    `
                    <input readonly type="text" hidden required name="promoteToExisting" value="${promoteToExisting}">
                        <div class="form-group mb-3 row promote-entry">
                            <div hidden class="hidden form-floating">
                                <input readonly type="text" required name="sale_note_ids" value="${selectedSalesNotes.join(',')}">
                            </div>
                            <div class="col-md-2">
                                <select name="item_id" class="form-select medicineSelect  shadow-sm chosen" required>
                                    <option selected value="">Select medicine...</option>
                                    <?php $__currentLoopData = $medicines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $medicine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($medicine->id); ?>"><?php echo e($medicine->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-2 form-floating">
                                <input class="form-control" type="number" required name="buying_price"
                                    placeholder="Buying Price">
                                <label class="form-label" for="buying_price">Buying Price</label>
                            </div>
                            <div class="col-md-2 form-floating">
                                <input class="form-control" type="number" required name="selling_price"
                                    placeholder="Selling Price" value="${firstSelectedUnitPrice}" readonly>
                                <label class="form-label" for="selling_price">Unit Price</label>
                            </div>
                            <div class="col-md-2 form-floating">
                                <input class="form-control" type="number" min="${sumOfAllQuantities}" required name="stocked_quantity"
                                    placeholder="Stocked Quantity" value="${sumOfAllQuantities}">
                                <label class="form-label" for="stocked_quantity">Quantity</label>
                            </div>
                            <div class="col-md-2 form-floating">
                                <input class="form-control" type="number" required name="low_stock_quantity" placeholder="Low Stock Quantity">
                                <label class="form-label" for="low_stock_quantity">Low Stock</label>
                            </div>
                            <div class="col-md-2 form-floating">
                                <input class="form-control" type="date"
                                    required name="expiry_date" placeholder="Expiry Date">
                                <label class="form-label" for="expiry_date">Expire Date</label>
                            </div>
                        </div>`
                );

                // genarate batch number and initialize chosen/select2
                $(document).ready(function() {
                    initializeChosen();

                    // listen for changes in the .medicineSelect field to call for a function to set the stock fields data
                    $(document).on('change', '.promote-entry select[name="item_id"]', function() {
                        let row = $(this).closest(
                            '.promote-entry'); // Get the closest parent row
                        setStockFieldsData(row[0]); // Pass the DOM element to the function
                    });

                    const today = new Date();
                    const year = today.getFullYear(); // Get the full year
                    const month = String(today.getMonth() + 1).padStart(2,
                        '0'); // Months are zero-based, pad with leading zero if needed
                    const day = String(today.getDate()).padStart(2,
                        '0'); // Pad day with leading zero if needed
                    const formattedDate =
                        `${year}${month}${day}`; // Combine to form YYYYMMDD format
                    $('.batch_number').val(
                        formattedDate); // Use .val() to set the value of the input
                });

                // Show the modal
                if (hasSameUnitPrice) {
                    $('#promoteSelectedSaleNoteAsOneModal').modal('show');
                }
            });

            // when the modal is closed, clear the selected sales notes array
            $('#closePromotionModal').on('click', function(e) {
                e.preventDefault();

                // Clear the selected sales notes array
                selectedSalesNotes = [];

                // Clear the modal content
                $('#promotionPannel').empty();
                $('#asOnePromotionPannel').empty();

                // remove the class "selected"  and "selected_"  from the selected rows
                $('#SaleNoteTable tbody tr.selected').removeClass('selected');
                $('#SaleNoteTable tbody tr.selected_').removeClass('selected_');

                // Hide the modal
                $('#promoteSelectedSaleNoteModal').modal('hide');
                $('#promoteSelectedSaleNoteAsOneModal').modal('hide');
            });

            // remove the selected row from the modal when the remove button is clicked
            $(document).on('click', '.removeRow', function() {
                $(this).closest('.row').remove();
                // if the removed row is the last one, clear the selected sales notes array and close the modal
                if ($('#promotionPannel .row').length < 2) {
                    $('#closePromotionModal').trigger('click');
                }
                if ($('#asOnePromotionPannel .row').length < 2) {
                    $('#closeAsOnePromotionModal').trigger('click');
                }
            });

            //genrate the batch number
            $(document).ready(function() {
                const today = new Date();
                const year = today.getFullYear(); // Get the full year
                const month = String(today.getMonth() + 1).padStart(2,
                    '0'); // Months are zero-based, pad with leading zero if needed
                const day = String(today.getDate()).padStart(2, '0'); // Pad day with leading zero if needed
                const formattedDate = `${year}${month}${day}`; // Combine to form YYYYMMDD format
                $('.batch_number').val(formattedDate); // Use .val() to set the value of the input
            });

            // when the submit button is clicked, submit the form
            $(document).ready(function() {
                // Disable the button initially
                $('#submitPromotion').prop('disabled', true);

                // Listen for input changes in the form
                $('#promoteSaleNoteForm').on('input', 'input[required]', function() {
                    var requiredFields = $('#promoteSaleNoteForm').find('input[required]');

                    // Check if there are any empty required fields
                    var hasEmptyFields = requiredFields.toArray().some(input => input.value
                        .trim() === '');

                    // Enable the button only when all required fields are filled
                    $('#submitPromotion').prop('disabled', hasEmptyFields);
                });
            });

        });
    </script>

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Function to attach calculation to inputs
            function attachCalculateTotal(modal) {
                const quantityInput = modal.querySelector('#floatingQuantity');
                const unitPriceInput = modal.querySelector('#floatingUnitPrice');
                const totalPriceInput = modal.querySelector('#floatingTotalPrice');

                function calculateTotal() {
                    const quantity = parseFloat(quantityInput.value) || 0;
                    const unitPrice = parseFloat(unitPriceInput.value) || 0;
                    const total = quantity * unitPrice;

                    totalPriceInput.value = total > 0 ?
                        new Intl.NumberFormat('en-TZ', {
                            style: 'currency',
                            currency: 'TZS',
                            minimumFractionDigits: 0
                        }).format(total) :
                        '';
                }

                quantityInput.addEventListener('input', calculateTotal);
                unitPriceInput.addEventListener('input', calculateTotal);
            }

            // Listen for modal show
            const salesNoteModal = document.getElementById('createSalesNoteModal');
            salesNoteModal.addEventListener('shown.bs.modal', function() {
                attachCalculateTotal(salesNoteModal);
            });
        });
    </script>
<?php $__env->stopSection(); ?>



<?php echo $__env->make("sales_notes.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DEVELOPMENT\pms\resources\views/sales_notes/index.blade.php ENDPATH**/ ?>