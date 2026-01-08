<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    
    <title><?php echo $__env->yieldContent('title'); ?> | Pharmacy Management System</title>
    <meta name="description" content="<?php echo $__env->yieldContent('meta_description'); ?>">
    <meta name="keywords" content="<?php echo $__env->yieldContent('meta_keywords'); ?>">
    <meta name="author" content="SKYLINK SOLUTIONS">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <link rel='stylesheet' href='https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css'>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <!-- include summernote css/js -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script src="<?php echo e(asset('assets/js/bootstrap.js')); ?>"></script>

    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Font Awesome for eye icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    <style>
        .chosen-container .chosen-single {
            height: calc(2.5rem + 2px);
            /* Match Bootstrap's default input height */
            line-height: calc(2.5rem + 2px);
            /* border: 1px solid #ced4da; */
            border-radius: 0;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            color: black;
            background-color: white;
            /* background-clip: padding-box; */
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        /* Loader Overlay */
        .loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
            /* Slightly transparent background */
            display: none;
            /* Initially hidden */
            z-index: 9999;

            /* Flexbox to center the spinner */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .spinner-border {
            width: 3rem;
            height: 3rem;
        }
    </style>
</head>

<body class="font-sans antialiased">

    
    <div x-data="{ sidebarOpen: false }" class="min-h-screen bg-gray-100 dark:bg-gray-900 font-sans">
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('navigation-menu');

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-3318258836-0', null);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

        
        <div class="flex#">

            <!-- SIDEBAR -->
            <?php if (isset($component)) { $__componentOriginald31f0a1d6e85408eecaaa9471b609820 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald31f0a1d6e85408eecaaa9471b609820 = $attributes; } ?>
<?php $component = App\View\Components\Sidebar::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Sidebar::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald31f0a1d6e85408eecaaa9471b609820)): ?>
<?php $attributes = $__attributesOriginald31f0a1d6e85408eecaaa9471b609820; ?>
<?php unset($__attributesOriginald31f0a1d6e85408eecaaa9471b609820); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald31f0a1d6e85408eecaaa9471b609820)): ?>
<?php $component = $__componentOriginald31f0a1d6e85408eecaaa9471b609820; ?>
<?php unset($__componentOriginald31f0a1d6e85408eecaaa9471b609820); ?>
<?php endif; ?>

            <main class="flex-1 mt-5 py-6 px-2# lg:ml-64 transition-all duration-300">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($slot)): ?>
                    <?php echo e($slot); ?>

                <?php else: ?>
                    
                    <?php echo $__env->yieldContent('content'); ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </main>

            <!-- Loader Overlay -->
            <div id="loader-overlay" class="loader-overlay">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <audio id="notification-sound" src="<?php echo e(asset('audio/notify.mp3')); ?>" preload="auto"></audio>
        </div>
    </div>

    <?php echo $__env->yieldPushContent('modals'); ?>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>


    <script>
        $(document).ready(function() {
            var isNotificationCheck = true;
            // requestNotificationPermission();

            // Automatically show the modal if no pharmacy is selected
            <?php if(!session('current_pharmacy_id')): ?>
                $('#pharmacyModal').modal('show');
            <?php endif; ?>
            <?php if(session('guest-owner')): ?>
                $('#guestPharmacyModal').modal('show');
            <?php endif; ?>

            // <?php if(Auth::user()->contracts->where('is_current_contract', 1)->count() < 1): ?>
            //     // $('#guestPharmacyModal').modal('show');
            //     return redirect()->route('myContracts')->with('error','Subscribe first to continue using our products!');
            // <?php endif; ?>

            <?php if(session('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: '<?php echo e(session('success')); ?>',
                    // timer: 2000
                });
            <?php endif; ?>

            <?php if(session('error')): ?>
                Swal.fire({
                    icon: 'error',
                    title: '<?php echo e(session('error')); ?>',
                    // timer: 2000
                });
            <?php endif; ?>

            <?php if(session('info')): ?>
                Swal.fire({
                    icon: 'info',
                    title: '<?php echo e(session('info')); ?>',
                    // timer: 2000
                });
            <?php endif; ?>

            $(document).ready(function() {
                $('#Table')
                    .DataTable({
                        paging: true, // Enable paging
                        searching: true, // Enable search bar
                        ordering: true, // Enable column sorting
                        info: false // Enable information display

                    })
                    .columns.adjust()
                    .responsive.recalc();

                $(".onReport").select2({
                    width: "100%",
                    minimumResultsForSearch: 5,
                });

                <?php if(!session('success') && !session('error') && !session('info')): ?>
                    $("select").not("#conversationRecipients").each(function() {
                        let $select = $(this);
                        let $modal = $select.closest(".modal"); // Check if inside a modal
                        $select.select2({
                            width: "100%",
                            minimumResultsForSearch: 5,
                            dropdownParent: $modal.length ? $modal : $(
                                "body") // Use modal if inside one
                        });

                        // Auto-focus the search input when dropdown opens
                        $select.on("select2:open", function() {
                            document.querySelector(
                                    ".select2-container--open .select2-search__field")
                                .focus();
                        });

                    });
                <?php endif; ?>
            });

            //LOADER
            $(document).ready(function() {
                $('#loader-overlay').hide(); // Hide loader initially


                $('button').on('click', function(event) {
                    const $this = $(this);
                    if ($this.is('#hamburger')) {
                        return;
                    }
                    if ($this.is('#apply-filter-btn')) {
                        return;
                    }
                    // if ($this.is('#forAdminImport')) {
                    //     return;
                    // }

                    //below implement code to check if the button is a submit button and if any of the required field is empty the loader will not appear otherwise it will appear while the form is being submitted
                    if ($this.is('button[type="submit"]')) {
                        const $form = $this.closest('form');
                        if ($form.length) {
                            if ($form[0].checkValidity()) {
                                // $('#loader-overlay').show();
                                $this.addClass(
                                        'bg-light border border-danger# text-danger text-muted')
                                    .css('pointer-events', 'none')
                                    .html(
                                        '<span class="spinner-border" style="width: 1rem; height: 1rem;" role="status" aria-hidden="true"></span>'
                                    );
                            }
                        }
                    }

                    if (!$this.find('input[name="requiredField"]').val()) {
                        return; // Exit the function
                    }

                    if ($this.is('button.btn-close[data-bs-dismiss="modal"]')) {
                        return; // Exit the function
                    }
                    if ($this.is('.reportsDownloadButton')) {
                        return;
                    }
                    if ($this.is('#addSaleRow')) {
                        return;
                    }
                    if ($this.is('#addStockBtn')) {
                        return;
                    }
                    if ($this.is('#sendBtn')) {
                        return;
                    }
                    $this.addClass('bg-light border border-danger# text-danger text-muted')
                        .css('pointer-events', 'none')
                        .html(
                            '<span class="spinner-border" style="width: 1rem; height: 1rem;" role="status" aria-hidden="true"></span>'
                        );
                });


                // Show the loader before a new page is requested (e.g., on link click)
                $('a').on('click', function(event) {
                    const $this = $(this);

                    // Prevent loader for modal triggers
                    if ($this.attr('data-bs-toggle') === 'modal') {
                        return; // Exit if the link is for opening a modal
                    }

                    // if the url points to open an asset file return
                    if ($this.attr('href').includes('storage/')) {
                        return;
                    }

                    // Disable the link/button
                    // $this.addClass('bg-light border border-danger text-danger text-muted')
                    //     .css('pointer-events', 'none')
                    //     .append(
                    //         '<span class="spinner-border" style="width: 1rem; height: 1rem;" role="status" aria-hidden="true"></span>'
                    //     );


                    // Skip showing loader if the target is a select field (including chosen dropdown)
                    if ($(this).closest('select, .chosen-container').length) {
                        return; // Exit if the link is associated with a select or chosen field
                    }


                    $('#loader-overlay').show();
                });

                // Show the loader when an AJAX request starts
                $(document).ajaxStart(function() {
                    if (!isNotificationCheck) { // Only show loader if it's not a notification check
                        $('#loader-overlay').show();
                    }
                });

                // Hide the loader when the AJAX request completes
                $(document).ajaxStop(function() {
                    $('#loader-overlay').hide();
                });

                // Hide the loader when the page is fully loaded
                $(window).on('load', function() {
                    $('#loader-overlay').hide();
                });

                // Hide the loader if a modal is opened
                $('[data-bs-toggle="modal"]').on('click', function() {
                    $('#loader-overlay').hide();
                });
            });
        });

        //NOTIFICATION RING
        $(document).ready(function() {
            // isNotificationCheck = false;

            $('#loader-overlay').hide(); // Hide loader initially

            function checkNotifications() {

                isNotificationCheck = false;

                $.ajax({
                    url: '/notifications/unread_count', // Replace with your route to fetch unread notifications count
                    method: 'GET',
                    success: function(response) {
                        $('#notifyBell').text(response.unreadCount);
                        $('#notifyBellPhone').text(response.unreadCount);
                        if (response.unreadCount > 0) {
                            // Play the notification sound
                            $('#notification-sound')[0].play();
                            // showBrowserNotification(
                            //     `You have ${response.unreadCount} unread notifications.`);
                        }
                    },
                    complete: function() {
                        isNotificationCheck = true; // Reset the flag after the request completes
                    },
                    error: function() {
                        console.error('Failed to fetch unread notifications.');
                    }
                });
            }

            // Check notifications every minute
            setInterval(checkNotifications, 15000); // 60000 ms = 1 minute
        });

        //SUMMERNOTE
        $(document).ready(function() {
            // if (true) {
            //     $('#printerModal').modal('show');
            // }
            $('.summernote').summernote({
                height: 90, // set editor height
                minHeight: null, // set minimum height of editor
                maxHeight: null, // set maximum height of editor
                focus: true // set focus to editable area after initializing summernote
            });
        });
    </script>

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize the clock with server time
            let serverTime = new Date('<?php echo e(Date::now()); ?>');

            // Function to update the clock
            function updateClock() {
                const clockElement = document.getElementById('clock');
                clockElement.textContent = serverTime.toLocaleString(); // Display date and time
                serverTime.setSeconds(serverTime.getSeconds() + 1); // Increment by 1 second
            }

            // Update the clock every second
            setInterval(updateClock, 1000);

            // Initial call to display the time immediately
            updateClock();
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    

    
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>

    <!-- Include JSZip and pdfmake for export functionality -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.5/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.5/vfs_fonts.js"></script>

    <!-- Include Buttons extensions -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <!-- include summernote css/js -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>

</html>
<?php /**PATH D:\DEVELOPMENT\pms\resources\views/layouts/app.blade.php ENDPATH**/ ?>