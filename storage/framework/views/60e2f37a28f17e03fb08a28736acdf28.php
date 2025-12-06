<?php $__env->startSection('title',  __('messages.transaction_history') ); ?>

<?php $__env->startSection('content'); ?>
<div style="max-width: 1000px; margin: 2rem auto;">
    <h1 style="color: white; margin-bottom: 2rem;">📊 سجل جميع <?php echo e(__('messages.transactions')); ?></h1>

    <div class="card" style="margin-bottom: 2rem;">
        <h3 style="margin-bottom: 1rem;">🔍 <?php echo e(__('messages.filter')); ?> النتائج</h3>
        <form method="GET" action="<?php echo e(route('transactions.history')); ?>">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div class="form-group">
                    <label><?php echo e(__('messages.from')); ?> تاريخ</label>
                    <input type="date" name="from_date" class="form-control" value="<?php echo e(request('from_date')); ?>">
                </div>
                <div class="form-group">
                    <label><?php echo e(__('messages.to')); ?> تاريخ</label>
                    <input type="date" name="to_date" class="form-control" value="<?php echo e(request('to_date')); ?>">
                </div>
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary" style="width: 100%;"><?php echo e(__('messages.search')); ?></button>
                </div>
            </div>
        </form>
    </div>

    <div class="card">
        <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div style="display: flex; justify-content: space-between; align-items: start; padding: 1.5rem; background: #f8f9fa; border-radius: 10px; margin-bottom: 1rem;">
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                        <span style="font-size: 2rem;">
                            <?php if($transaction->transaction_type == 'transfer'): ?>
                                <?php echo e($transaction->sender_id == auth()->id() ? '📤' : '📥'); ?>

                            <?php elseif($transaction->transaction_type == 'payment'): ?>
                                🛒
                            <?php else: ?>
                                💳
                            <?php endif; ?>
                        </span>
                        <div>
                            <h4 style="margin: 0;">
                                <?php if($transaction->transaction_type == 'transfer'): ?>
                                    <?php if($transaction->sender_id == auth()->id()): ?>
                                        <?php echo e(__('messages.transfer')); ?> إلى <?php echo e($transaction->receiver->name); ?>

                                    <?php else: ?>
                                        <?php echo e(__('messages.transfer')); ?> من <?php echo e($transaction->sender->name); ?>

                                    <?php endif; ?>
                                <?php elseif($transaction->transaction_type == 'payment'): ?>
                                    <?php echo e(__('messages.payment')); ?> لـ <?php echo e($transaction->merchant_name); ?>

                                <?php endif; ?>
                            </h4>
                            <div style="color: #666; font-size: 0.9rem; margin-top: 0.3rem;">
                                <strong>السبب:</strong> <?php echo e($transaction->description); ?>

                            </div>
                            <div style="color: #999; font-size: 0.85rem; margin-top: 0.3rem;">
                                <?php echo e($transaction->created_at->format('d/m/Y H:i')); ?> •
                                رقم المرجع: <?php echo e($transaction->reference_number); ?>

                            </div>
                        </div>
                    </div>
                </div>
                <div style="text-align: left; margin-right: 1rem;">
                    <div style="font-size: 1.5rem; font-weight: bold; color: <?php echo e($transaction->sender_id == auth()->id() ? '#dc3545' : '#28a745'); ?>;">
                        <?php echo e($transaction->sender_id == auth()->id() ? '-' : '+'); ?><?php echo e(__('messages.currency_symbol')); ?><?php echo e(number_format($transaction->amount, 2)); ?>

                    </div>
                    <div style="font-size: 0.8rem; color: #666; margin-top: 0.3rem;">
                        <?php echo e($transaction->payment_method); ?>

                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div style="text-align: center; padding: 3rem; color: #666;">
                <div style="font-size: 4rem; margin-bottom: 1rem;">📭</div>
                <p><?php echo e(__('messages.no_transactions')); ?> لعرضها</p>
            </div>
        <?php endif; ?>

        <?php if($transactions->hasPages()): ?>
            <div style="margin-top: 2rem; display: flex; justify-content: center;">
                <?php echo e($transactions->links()); ?>

            </div>
        <?php endif; ?>
    </div>

    <div style="text-align: center; margin-top: 2rem;">
        <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-secondary">العودة ل<?php echo e(__('messages.dashboard')); ?></a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\bank-graduation-project-main\resources\views/transactions/history.blade.php ENDPATH**/ ?>