<?php $__env->startSection('title', __('messages.dashboard') ); ?>

<?php $__env->startSection('content'); ?>
<style>
    .welcome-header {
        color: white;
        margin-bottom: 2rem;
        font-size: 2.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .icon-xl {
        width: 48px;
        height: 48px;
    }

/* --- Tooltip for quick actions (hover-only) --- */
.quick-ops .btn.btn-primary.qa-item { position: relative; }
.quick-ops .info-icon { position: absolute; top: 8px; left: 8px; width: 18px; height: 18px; opacity: .95; cursor: pointer; pointer-events: auto; }
.quick-ops .info-icon svg { width: 16px; height: 16px; display: block; }
.quick-ops .tooltip-bubble { position: absolute; left: 8px; top: -10px; transform: translateY(-100%); background: rgba(0,0,0,0.9); color: #fff; padding: 6px 10px; border-radius: 8px; font-size: 12px; white-space: nowrap; display: none; z-index: 20; }
.quick-ops .quick-ops .tooltip-bubble::after { content: ""; position: absolute; bottom: -6px; right: 12px; border: 6px solid transparent; border-top-color: rgba(0,0,0,0.9); }
</style>

<h1 class="welcome-header">
    <svg class="icon-xl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
    </svg>
    <?php echo e(__('messages.welcome')); ?>، <?php echo e($user->name); ?>

</h1>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
    <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <h3 style="margin-bottom: 1rem; opacity: 0.9; display: flex; align-items: center; gap: 0.5rem;">
            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
            </svg>
            <?php echo e(__('messages.current_balance')); ?>

        </h3>
        <div style="font-size: 3rem; font-weight: bold; margin-bottom: 0.5rem;">
            <?php echo e(__('messages.currency_symbol')); ?><?php echo e(number_format($user->balance, 2)); ?>

        </div>
        <p style="opacity: 0.9;"><?php echo e(__('messages.account_number')); ?>: <?php echo e($user->account_number); ?></p>
    </div>

    <div class="card">
        <h3 style="margin-bottom: 1rem; color: #667eea; display: flex; align-items: center; gap: 0.5rem;">
            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <?php echo e(__('messages.Statistics')); ?>

        </h3>
        <div style="display: grid; gap: 1rem;">
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #e0e0e0;">
                <span><?php echo e(__('messages.total_transactions')); ?>:</span>
                <strong style="color: #667eea;"><?php echo e($stats['total_transactions']); ?></strong>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #e0e0e0;">
                <span><?php echo e(__('messages.Total_expenses')); ?></span>
                <strong style="color: #dc3545;"><?php echo e(__('messages.currency_symbol')); ?><?php echo e(number_format($stats['total_spent'], 2)); ?></strong>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0;">
                <span><?php echo e(__('messages.Total_received')); ?>:</span>
                <strong style="color: #28a745;"><?php echo e(__('messages.currency_symbol')); ?><?php echo e(number_format($stats['total_received'], 2)); ?></strong>
            </div>
        </div>
    </div>

    <div class="card">
        <h3 style="margin-bottom: 1rem; color: #667eea; display: flex; align-items: center; gap: 0.5rem;">
            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <?php echo e(__('messages.daily_limit')); ?>

        </h3>
        <div style="margin-bottom: 1rem;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span> <?php echo e(__('messages.daily_spent_today')); ?>:</span>
                <strong><?php echo e(__('messages.currency_symbol')); ?><?php echo e(number_format($user->daily_spent, 2)); ?></strong>
            </div>
            <div style="background: #e0e0e0; height: 10px; border-radius: 5px; overflow: hidden;">
                <div style="background: #667eea; height: 100%; width: <?php echo e(($user->daily_spent / $user->daily_limit) * 100); ?>%;"></div>
            </div>
            <div style="text-align: center; margin-top: 0.5rem; color: #666; font-size: 0.9rem;">
                <?php echo e(__('messages.residual')); ?>: <?php echo e(__('messages.currency_symbol')); ?><?php echo e(number_format($user->daily_limit - $user->daily_spent, 2)); ?>

            </div>
        </div>
    </div>
</div>

<div class="card">
    <h2 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
        <svg class="icon-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
        <?php echo e(__('messages.Quick_operations')); ?>

    </h2>
    <div class="quick-ops" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        <a href="<?php echo e(route('transfer.friend')); ?>" class="btn btn-primary qa-item" style="position: relative; padding: 1.5rem; text-align: center;">
            <svg class="icon-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <div><?php echo e(__('messages.transfer_to_friend')); ?></div>
<span class="info-icon" title="معلومات">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#fff">
  <circle cx="12" cy="12" r="11" fill="#6366f1"></circle>
  <path d="M12 17a1 1 0 110-2 1 1 0 010 2zm1-4h-2V7h2v6z" fill="#fff"></path>
</svg>
</span><div class="tooltip-bubble">استعرض وأدر بطاقاتك وإداراتها</div>
<span class="info-icon" title="معلومات">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#fff">
  <circle cx="12" cy="12" r="11" fill="#6366f1"></circle>
  <path d="M12 17a1 1 0 110-2 1 1 0 010 2zm1-4h-2V7h2v6z" fill="#fff"></path>
</svg>
</span><div class="tooltip-bubble">ادفع للتاجر عبر المسح أو إدخال رقم التاجر</div>
<span class="info-icon" title="معلومات">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#fff">
  <circle cx="12" cy="12" r="11" fill="#6366f1"></circle>
  <path d="M12 17a1 1 0 110-2 1 1 0 010 2zm1-4h-2V7h2v6z" fill="#fff"></path>
</svg>
</span><div class="tooltip-bubble">حوّل الأموال إلى صديق بسرعة وبأمان</div>
        </a>
        <a href="<?php echo e(route('pay.merchant')); ?>" class="btn btn-primary qa-item" style="position: relative; padding: 1.5rem; text-align: center;">
            <svg class="icon-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <div><?php echo e(__('messages.pay_merchant')); ?></div>

<span class="info-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#fff"><circle cx="12" cy="12" r="11" fill="#6366f1"></circle><path d="M12 17a1 1 0 110-2 1 1 0 010 2zm1-4h-2V7h2v6z" fill="#fff"></path></svg></span>
<div class="tooltip-bubble">ادفع للتاجر عبر المسح أو إدخال رقم التاجر</div>
        </a>
        <a href="<?php echo e(route('cards.index')); ?>" class="btn btn-primary qa-item" style="position: relative; padding: 1.5rem; text-align: center;">
            <svg class="icon-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            <div><?php echo e(__('messages.my_cards')); ?></div>

<span class="info-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#fff"><circle cx="12" cy="12" r="11" fill="#6366f1"></circle><path d="M12 17a1 1 0 110-2 1 1 0 010 2zm1-4h-2V7h2v6z" fill="#fff"></path></svg></span>
<div class="tooltip-bubble">استعرض بطاقاتك وإدارتها</div>
        </a>
        <a href="<?php echo e(route('settings.index')); ?>" class="btn btn-primary" style="padding: 1.5rem; text-align: center;">
            <svg class="icon-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <div><?php echo e(__('messages.settings')); ?></div>
        </a>
    </div>
</div>

<div class="card">
    <h3 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
        <svg class="icon-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <?php echo e(__('messages.last')); ?> <?php echo e(__('messages.transactions')); ?>

    </h3>
    <?php $__empty_1 = true; $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: #f8f9fa; border-radius: 8px; margin-bottom: 0.5rem;">
            <div>
                <strong>
                    <?php if($transaction->transaction_type == 'transfer'): ?>
                        <?php if($transaction->sender_id == $user->id): ?>
                            <?php echo e(__('messages.transfer')); ?> <?php echo e(__('messages.to')); ?> <?php echo e($transaction->receiver->name); ?>

                        <?php else: ?>
                            <?php echo e(__('messages.transfer')); ?> <?php echo e(__('messages.from')); ?> <?php echo e($transaction->sender->name); ?>

                        <?php endif; ?>
                    <?php elseif($transaction->transaction_type == 'payment'): ?>
                        <?php echo e(__('messages.payment')); ?> <?php echo e(__('messages.to')); ?> <?php echo e($transaction->merchant_name); ?>

                    <?php endif; ?>
                </strong>
                <div style="font-size: 0.9rem; color: #666; margin-top: 0.3rem;">
                    <?php echo e($transaction->description); ?> • <?php echo e($transaction->created_at->diffForHumans()); ?>

                </div>
            </div>
            <div style="font-size: 1.3rem; font-weight: bold; color: <?php echo e($transaction->sender_id == $user->id ? '#dc3545' : '#28a745'); ?>;">
                <?php echo e($transaction->sender_id == $user->id ? '-' : '+'); ?><?php echo e(__('messages.currency_symbol')); ?><?php echo e(number_format($transaction->amount, 2)); ?>

            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p style="text-align: center; padding: 2rem; color: #666;"><?php echo e(__('messages.no_transactions')); ?> </p>
    <?php endif; ?>

    <?php if($recentTransactions->count() > 0): ?>
        <div style="text-align: center; margin-top: 1rem;">
            <a href="<?php echo e(route('transactions.history')); ?>" class="btn btn-primary">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                  <?php echo e(__('messages.View_all')); ?> <?php echo e(__('messages.transactions')); ?>

            </a>
        </div>
    <?php endif; ?>>
</div>



<?php $__env->stopSection(); ?>
<style>
.quick-ops .info-icon:hover + .tooltip-bubble { display: block; }
</style>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\bank-graduation-project-main\resources\views/dashboard.blade.php ENDPATH**/ ?>