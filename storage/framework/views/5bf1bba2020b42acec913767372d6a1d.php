<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    
    
    <title><?php echo $__env->yieldContent('title'); ?> | Pharmacy management System</title>
    <meta name="description" content="<?php echo $__env->yieldContent('meta_description'); ?>">
    <meta name="keywords" content="<?php echo $__env->yieldContent('meta_keywords'); ?>">
    <meta name="author" content="SKYLINK SOLUTIONS">

    <title><?php echo e(config('app.name', 'Laravel')); ?></title>

    <link rel="shortcut icon" href="<?php echo e(asset('favicon.ico')); ?>" type="image/x-icon">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script src="<?php echo e(asset('assets/js/bootstrap.js')); ?>"></script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Styles -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

</head>


<body>
    <div class="font-sans text-gray-900 dark:text-gray-100 antialiased">
        <?php echo e($slot); ?>

    </div>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    <script>
        $(document).ready(function() {
            $('button').on('click', function(event) {
                const $this = $(this);

                if ($this.is('button[type="submit"]')) {
                    const $form = $this.closest('form');
                    if ($form.length) {
                        if ($form[0].checkValidity()) {

                            $this.css('pointer-events', 'none')
                                .text(
                                    'Loading...'
                                );

                        }
                    }
                }
            });
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.js"></script>
</body>

</html>
<?php /**PATH D:\DEVELOPMENT\pms\resources\views/layouts/guest.blade.php ENDPATH**/ ?>