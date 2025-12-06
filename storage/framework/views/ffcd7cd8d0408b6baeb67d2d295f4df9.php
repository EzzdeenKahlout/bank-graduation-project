<?php $__env->startSection('title',  __('messages.create') .' حساب'); ?>

<?php $__env->startSection('content'); ?>
<div style="max-width: 500px; margin: 3rem auto;">
    <div class="card">
        <div style="text-align: center; font-size: 4rem; margin-bottom: 1rem;">✨</div>
        <h2 style="text-align: center; color: #667eea; margin-bottom: 2rem;"><?php echo e(__('messages.create')); ?> حساب جديد</h2>

        <form method="POST" action="<?php echo e(route('register')); ?>">
            <?php echo csrf_field(); ?>

            <div class="form-group">
                <label><?php echo e(__('messages.name')); ?> الكامل</label>
                <input type="text" name="name" class="form-control" value="<?php echo e(old('name')); ?>" required>
            </div>

            <div class="form-group">
                <label><?php echo e(__('messages.email')); ?></label>
                <input type="email" name="email" class="form-control" value="<?php echo e(old('email')); ?>" required>
            </div>

            <div class="form-group">
                <label><?php echo e(__('messages.phone')); ?></label>
                <input type="text" name="phone" class="form-control" value="<?php echo e(old('phone')); ?>" placeholder="0501234567" required>
            </div>

            <div class="form-group">
                <label><?php echo e(__('messages.password')); ?></label>
                <input type="password" name="password" class="form-control" placeholder="8 أحرف على الأقل" required>
            </div>

            <div class="form-group">
                <label><?php echo e(__('messages.confirm_password')); ?></label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <div class="form-group">
                <label>رقم PIN لل<?php echo e(__('messages.payment')); ?> (4 أرقام)</label>
                <input type="password" name="pin" class="form-control" maxlength="4" placeholder="****" required>
                <small style="color: #666;">ستحتاج هذا الرقم لتأكيد جميع <?php echo e(__('messages.transactions')); ?></small>
            </div>

            <div class="form-group">
                <label><?php echo e(__('messages.confirm')); ?> PIN</label>
                <input type="password" name="pin_confirmation" class="form-control" maxlength="4" placeholder="****" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1.1rem;">
                <?php echo e(__('messages.create')); ?> الحساب
            </button>
        </form>

        <p style="text-align: center; margin-top: 1.5rem; color: #666;">
            <?php echo e(__('messages.already_have_account')); ?>

            <a href="<?php echo e(route('login')); ?>" style="color: #667eea; font-weight: bold;">سجل الدخول</a>
        </p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\bank-graduation-project-main\resources\views/auth/register.blade.php ENDPATH**/ ?>