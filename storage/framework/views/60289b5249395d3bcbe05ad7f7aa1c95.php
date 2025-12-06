<?php $__env->startSection('title',  __('messages.login') ); ?>

<?php $__env->startSection('content'); ?>
<div style="max-width: 450px; margin: 3rem auto;">
    <div class="card">
        <div style="text-align: center; font-size: 4rem; margin-bottom: 1rem;">🔐</div>
        <h2 style="text-align: center; color: #667eea; margin-bottom: 2rem;"><?php echo e(__('messages.login')); ?></h2>

        <form method="POST" action="<?php echo e(route('login')); ?>">
            <?php echo csrf_field(); ?>

            <div class="form-group">
                <label><?php echo e(__('messages.email')); ?></label>
                <input type="email" name="email" class="form-control" value="<?php echo e(old('email')); ?>" placeholder="example@email.com" required autofocus>
            </div>

            <div class="form-group">
                <label><?php echo e(__('messages.password')); ?></label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="remember"> <?php echo e(__('messages.remember_me')); ?>

                </label>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1.1rem;">
                دخول
            </button>
        </form>

        <div style="text-align: center; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e0e0e0;">
            <p style="color: #666;"><?php echo e(__('messages.dont_have_account')); ?></p>
            <a href="<?php echo e(route('register')); ?>" class="btn btn-secondary" style="margin-top: 1rem;">
                <?php echo e(__('messages.create')); ?> حساب جديد
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\bank-graduation-project-main\resources\views/auth/login.blade.php ENDPATH**/ ?>