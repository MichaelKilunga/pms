<div>
    <!-- Mobile Backdrop -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/80 z-20 lg:hidden" style="display: none;"></div>

    <!-- Sidebar Wrapper -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed top-0 left-0 z-30 w-64 h-screen overflow-y-auto pt-16 transition-transform duration-300 bg-white border-r border-gray-200 lg:translate-x-0 lg:static# lg:fixed dark:bg-gray-800 dark:border-gray-700">
    <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
        <?php $__currentLoopData = $menu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(isset($item['children'])): ?>
                
                <?php
                    $isActive = false;
                    foreach ($item['children'] as $child) {
                        if (request()->routeIs($child['route'])) {
                            $isActive = true;
                            break;
                        }
                    }
                ?>
                <div x-data="{ open: <?php echo e($isActive ? 'true' : 'false'); ?> }" class="space-y-1">
                    <button @click="open = ! open"
                        class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150
                        <?php echo e($isActive
                            ? 'bg-primary-50 text-primary-700 dark:bg-gray-700 dark:text-primary-400'
                            : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700/50'); ?>">
                        <div class="flex items-center gap-3">
                            <?php if(isset($item['icon'])): ?>
                                <i class="<?php echo e($item['icon']); ?> text-lg <?php echo e($isActive ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400 dark:text-gray-500'); ?>"></i>
                            <?php endif; ?>
                            <span><?php echo e(__($item['label'])); ?></span>
                        </div>
                        <svg :class="{ 'rotate-90': open }" class="w-4 h-4 text-gray-400 transition-transform duration-200"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <div x-show="open" x-cloak x-collapse class="pl-4 space-y-1">
                        <?php $__currentLoopData = $item['children']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $childActive = request()->routeIs($child['route']);
                                $routeParams = $child['params'] ?? [];
                            ?>
                            <a href="<?php echo e(route($child['route'], $routeParams)); ?>"
                                class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-150
                                <?php echo e($childActive
                                    ? 'text-primary-600 bg-primary-50/50 dark:text-primary-400 dark:bg-gray-700/50'
                                    : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/30'); ?>">
                                <span class="w-1.5 h-1.5 rounded-full mr-2 <?php echo e($childActive ? 'bg-primary-500' : 'bg-gray-300 dark:bg-gray-600 group-hover:bg-gray-400'); ?>"></span>
                                <?php echo e(__($child['label'])); ?>

                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php else: ?>
                
                <?php
                    $isActive = request()->routeIs($item['route']);
                ?>
                <a href="<?php echo e(route($item['route'])); ?>"
                    class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150
                    <?php echo e($isActive
                        ? 'bg-primary-50 text-primary-700 dark:bg-gray-700 dark:text-primary-400'
                        : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700/50'); ?>">
                    <?php if(isset($item['icon'])): ?>
                        <i class="<?php echo e($item['icon']); ?> text-lg <?php echo e($isActive ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400 dark:text-gray-500'); ?>"></i>
                    <?php endif; ?>
                    <span><?php echo e(__($item['label'])); ?></span>
                </a>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</aside>
<?php /**PATH /media/michaelkilunga/C/SKYLINK/pms/resources/views/components/sidebar.blade.php ENDPATH**/ ?>