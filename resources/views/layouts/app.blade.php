<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- styles --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <link rel='stylesheet' href='https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css'>

    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"> --}}

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Styles -->
    {{-- @livewireStyles --}}
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
    
{{-- TAWK START --}}
<!--Start of Tawk.to Script-->
<script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/67810ca9af5bfec1dbe9b008/1ih81itsi';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
    </script>
    <!--End of Tawk.to Script-->
{{-- TAWK END --}}


    {{-- <x-banner /> --}}
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @livewire('navigation-menu')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->

        <main>
            {{ $slot }}
        </main>
        <!-- Loader Overlay -->
        <div id="loader-overlay" class="loader-overlay">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <audio id="notification-sound" src="{{ asset('audio/notify.mp3') }}" preload="auto"></audio>
    </div>

    @stack('modals')

    @livewireScripts

    <script>
        $(document).ready(function() {
            var isNotificationCheck = true;
            // requestNotificationPermission();

            // Automatically show the modal if no pharmacy is selected
            @if (!session('current_pharmacy_id'))
                $('#pharmacyModal').modal('show');
            @endif
            @if (session('guest-owner'))
                $('#guestPharmacyModal').modal('show');
            @endif

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '{{ session('success') }}',
                    // timer: 2000
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: '{{ session('error') }}',
                    // timer: 2000
                });
            @endif

            $(document).ready(function() {
                $('#Table')
                    .DataTable({
                        paging: true, // Enable paging
                        searching: true, // Enable search bar
                        ordering: true, // Enable column sorting
                        info: true // Enable information display
                    });

                $(".onReport").chosen({
                    width: "100%",
                    no_results_text: "No matches found!",
                });
                @if (!session('success') && !session('error'))
                    // $(".chosen").chosen({
                    $("select").chosen({
                        width: "100%",
                        no_results_text: "No matches found!",
                    });
                @endif
            });

            $(document).ready(function() {
                $('#loader-overlay').hide(); // Hide loader initially


                $('button').on('click', function(event) {
                    const $this = $(this);
                    if ($this.is('#hamburger')) {
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

        // function requestNotificationPermission() {
        //     if ("Notification" in window) {
        //         if (Notification.permission === "default") {
        //             Notification.requestPermission().then(permission => {
        //                 if (permission === "granted") {
        //                     console.log("Notification permission granted.");
        //                 } else {
        //                     console.log("Notification permission denied.");
        //                 }
        //             });
        //         } else if (Notification.permission === "granted") {
        //             console.log("Notifications are already enabled.");
        //         } else {
        //             console.log("User has denied notifications.");
        //         }
        //     } else {
        //         console.error("This browser does not support notifications.");
        //     }
        // }

        // function showBrowserNotification(message) {
        //     if (Notification.permission === "granted") {
        //         new Notification("New Notification", {
        //             body: message,
        //             icon: "/icons/bell-860.png", // Optional: Add a path to your notification icon
        //         });
        //     }
        // }
    </script>

    {{-- CLOCK TIMER --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize the clock with server time
            let serverTime = new Date('{{ Date::now() }}');

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> --}}
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.js"></script>

    {{-- FOR DOWNLOADING REPORTS --}}
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>

    <!-- Include JSZip and pdfmake for export functionality -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.5/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.5/vfs_fonts.js"></script>

    <!-- Include Buttons extensions -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

</body>

</html>
