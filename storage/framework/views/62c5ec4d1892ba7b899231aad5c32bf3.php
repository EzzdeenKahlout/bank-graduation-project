<?php $__env->startSection('title', __('messages.my_cards') ); ?>

<?php $__env->startSection('content'); ?>
<div style="max-width: 1000px; margin: 2rem auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 style="color: white;">💳 <?php echo e(__('messages.my_cards')); ?></h1>
        <a href="<?php echo e(route('cards.request')); ?>" class="btn btn-primary">+ <?php echo e(__('messages.request_card')); ?></a>
    </div>

    <?php $__empty_1 = true; $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="card" style="margin-bottom: 2rem;">
            <div style="background: <?php echo e($card->is_blocked ? 'linear-gradient(135deg, #666 0%, #999 100%)' : 'linear-gradient(135deg, #1e3c72 0%, #2a5298 100%)'); ?>; 
                        color: white; padding: 2rem; border-radius: 15px; position: relative; min-height: 220px;">
                
                <div style="position: absolute; top: 1rem; left: 1rem; background: <?php echo e($card->is_blocked ? '#dc3545' : '#28a745'); ?>; 
                            padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem; font-weight: bold;">
                    <?php if($card->is_blocked): ?>
                        🔒 محظورة
                    <?php elseif($card->isExpired()): ?>
                        ⚠️ <?php echo e(__('messages.from')); ?>تهية
                    <?php else: ?>
                        ✅ نشطة
                    <?php endif; ?>
                </div>

                <div style="width: 50px; height: 40px; background: linear-gradient(135deg, #FFD700, #FFA500); 
                            border-radius: 8px; margin-bottom: 1.5rem; margin-top: 2rem;"></div>

                <div style="font-size: 1.8rem; letter-spacing: 4px; margin: 1.5rem 0; font-family: 'Courier New', monospace;">
                    <?php echo e(chunk_split($card->card_number, 4, ' ')); ?>

                </div>

                <div style="display: flex; justify-content: space-between;">
                    <div>
                        <div style="font-size: 0.8rem; opacity: 0.8;">اسم <?php echo e(__('messages.card_holder')); ?></div>
                        <div style="font-size: 1.2rem; font-weight: bold;"><?php echo e($card->card_holder_name); ?></div>
                    </div>
                    <div>
                        <div style="font-size: 0.8rem; opacity: 0.8;"><?php echo e(__('messages.expiry_date')); ?></div>
                        <div style="font-size: 1.2rem; font-weight: bold;"><?php echo e($card->expiry_date->format('m/y')); ?></div>
                    </div>
                    <div>
                        <div style="font-size: 0.8rem; opacity: 0.8;"><?php echo e(__('messages.cvv')); ?></div>
                        <div style="font-size: 1.2rem; font-weight: bold;">***</div>
                    </div>
                </div>
            </div>

            <div style="padding: 1.5rem;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <strong><?php echo e(__('messages.card_type')); ?>:</strong> 
                        <span style="color: #667eea;"><?php echo e($card->card_type == 'debit' ? 'بطاقة خصم' :  __('messages.credit_card')); ?></span>
                    </div>
                    <?php if($card->card_type == 'credit'): ?>
                        <div>
                            <strong>حد الائتمان:</strong> 
                            <span style="color: #28a745;"><?php echo e(__('messages.currency_symbol')); ?><?php echo e(number_format($card->credit_limit, 2)); ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <form method="POST" action="<?php echo e(route('cards.toggle-block', $card)); ?>" style="display: inline;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn <?php echo e($card->is_blocked ? 'btn-primary' : 'btn-secondary'); ?>">
                            <?php echo e($card->is_blocked ? '🔓 إلغاء الحظر' : '🔒 ' .  __('messages.block_card')); ?>

                        </button>
                    </form>

                    <form method="POST" action="<?php echo e(route('cards.toggle-active', $card)); ?>" style="display: inline;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-secondary">
                            <?php echo e($card->is_active ? '⏸️ تعطيل' : '▶️ تفعيل'); ?>

                        </button>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="card" style="text-align: center; padding: 4rem;">
            <div style="font-size: 5rem; margin-bottom: 1rem;">💳</div>
            <h3 style="color: #667eea; margin-bottom: 1rem;">ليس لديك بطاقات بعد</h3>
            <p style="color: #666; margin-bottom: 2rem;">اطلب بطاقتك الأولى الآن وابدأ بإجراء <?php echo e(__('messages.transactions')); ?>!</p>
            <a href="<?php echo e(route('cards.request')); ?>" class="btn btn-primary" style="font-size: 1.1rem; padding: 1rem 2rem;">
                <?php echo e(__('messages.request_card')); ?>

            </a>
        </div>
    <?php endif; ?>

    <div style="text-align: center; margin-top: 2rem;">
        <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-secondary">العودة ل<?php echo e(__('messages.dashboard')); ?></a>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\bank-graduation-project-main\resources\views/cards/index.blade.php ENDPATH**/ ?>