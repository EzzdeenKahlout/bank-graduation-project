<?php $__env->startSection('title',  __('messages.request_card') ); ?>

<?php $__env->startSection('content'); ?>
<div style="max-width: 600px; margin: 2rem auto;">
    <div class="card">
        <div style="text-align: center; font-size: 4rem; margin-bottom: 1rem;">📝</div>
        <h2 style="text-align: center; color: #667eea; margin-bottom: 2rem;"><?php echo e(__('messages.request_card')); ?></h2>

        <form method="POST" action="<?php echo e(route('cards.request')); ?>">
            <?php echo csrf_field(); ?>

            <div class="form-group">
                <label><?php echo e(__('messages.card_type')); ?></label>
                <select name="card_type" class="form-control" required>
                    <option value="">اختر <?php echo e(__('messages.card_type')); ?></option>
                    <option value="debit"><?php echo e(__('messages.debit_card')); ?> (Debit Card)</option>
                    <option value="credit"><?php echo e(__('messages.credit_card')); ?> (Credit Card)</option>
                </select>
                <small style="color: #666;">
                    <strong>بطاقة الخصم:</strong> مرتبطة مباشرة برصيدك<br>
                    <strong>بطاقة الائتمان:</strong> لها حد ائتماني <?php echo e(__('messages.from')); ?>فصل
                </small>
            </div>

            <div class="form-group">
                <label>سبب الطلب (اختياري)</label>
                <textarea name="reason" class="form-control" rows="3"
                          placeholder="مثال: أحتاج بطاقة إضافية للتسوق الإلكتروني"></textarea>
            </div>

            <div style="background: #e3f2fd; padding: 1rem; border-radius: 8px; border-right: 4px solid #2196f3; margin-bottom: 1rem;">
                <strong style="color: #1976d2;">ℹ️ ملاحظة:</strong>
                <p style="margin-top: 0.5rem; color: #1565c0; margin-bottom: 0;">
                    سيتم مراجعة طلبك خلال 24 ساعة. سنرسل لك إشعاراً عند الموافقة على الطلب.
                </p>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1.1rem;">
                <?php echo e(__('messages.submit')); ?> الطلب
            </button>
        </form>

        <div style="text-align: center; margin-top: 1.5rem;">
            <a href="<?php echo e(route('cards.index')); ?>" style="color: #667eea;">العودة ل<?php echo e(__('messages.my_cards')); ?></a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\bank-graduation-project-main\resources\views/cards/request.blade.php ENDPATH**/ ?>