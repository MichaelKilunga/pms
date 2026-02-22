<?php $__env->startSection('content'); ?>
    <div class="container">
        <h1>Add New Package</h1>
        <form action="<?php echo e(route('packages.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" name="price" id="price" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="duration">Duration (days):</label>
                <input type="text" name="duration" id="duration" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <select name="status" id="status" class="form-control">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="form-group">
                <label for="number_of_pharmacies">Number of Pharmacies:</label>
                <input type="number" name="number_of_pharmacies" id="number_of_pharmacies" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="number_of_pharmacists">Number of Pharmacists:</label>
                <input type="number" name="number_of_pharmacists" id="number_of_pharmacists" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="number_of_medicines">Number of Medicines:</label>
                <input type="number" name="number_of_medicines" id="number_of_medicines" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="in_app_notification">In App Notification:</label>
                <select name="in_app_notification" id="in_app_notification" class="form-control">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="form-group">
                <label for="email_notification">Email Notification:</label>
                <select name="email_notification" id="email_notification" class="form-control">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="form-group">
                <label for="sms_notifications">SMS Notifications:</label>
                <select name="sms_notifications" id="sms_notifications" class="form-control">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="form-group">
                <label for="online_support">Online Support:</label>
                <select name="online_support" id="online_support" class="form-control">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="form-group">
                <label for="number_of_owner_accounts">Number of Owner Accounts:</label>
                <input type="number" name="number_of_owner_accounts" id="number_of_owner_accounts" class="form-control"
                    required>
            </div>
            <div class="form-group">
                <label for="number_of_admin_accounts">Number of Admin Accounts:</label>
                <input type="number" name="number_of_admin_accounts" id="number_of_admin_accounts" class="form-control"
                    required>
            </div>
            <div class="form-group">
                <label for="reports">Reports:</label>
                <select name="reports" id="reports" class="form-control">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="form-group">
                <label for="stock_transfer">Stock Transfer:</label>
                <select name="stock_transfer" id="stock_transfer" class="form-control">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="form-group">
                <label for="stock_management">Stock Management:</label>
                <select name="stock_management" id="stock_management" class="form-control">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="form-group">
                <label for="staff_management">Staff Management:</label>
                <select name="staff_management" id="staff_management" class="form-control">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="form-group">
                <label for="receipts">Receipts:</label>
                <select name="receipts" id="receipts" class="form-control">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="form-group">
                <label for="analytics">Analytics:</label>
                <select name="analytics" id="analytics" class="form-control">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="form-group">
                <label for="whatsapp_chats">Whatsapp Chats:</label>
                <select name="whatsapp_chats" id="whatsapp_chats" class="form-control">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="form-group d-flex justify-content-between mt-4">
                <a href="<?php echo e(route('packages')); ?>" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('packages.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /media/michaelkilunga/C/SKYLINK/pms/resources/views/contracts/admin/create.blade.php ENDPATH**/ ?>