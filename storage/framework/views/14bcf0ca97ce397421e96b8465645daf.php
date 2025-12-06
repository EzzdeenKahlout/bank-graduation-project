<?php $__env->startSection('title', __('messages.transactions_list')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
  <div class="row mb-3">
    <div class="col-12">
      <div class="card">
        <div class="card-body d-flex align-items-center justify-content-between">
          <h5 class="mb-0"><?php echo e(__('messages.transactions_list')); ?></h5>
          <?php if (\Illuminate\Support\Facades\Blade::check('permission', 'view_transactions')): ?>
          <a href="<?php echo e(route('admin.transactions.export')); ?>" class="btn btn-outline-primary btn-sm"><?php echo e(__('messages.export_csv')); ?></a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  
  <div class="row mb-3">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form method="GET" action="<?php echo e(route('admin.transactions.index')); ?>" class="row g-3">
            <div class="col-md-3">
              <label class="form-label"><?php echo e(__('messages.search')); ?></label>
              <input type="text" class="form-control" name="search" value="<?php echo e(request('search')); ?>" placeholder="<?php echo e(__('messages.search_by')); ?>">
            </div>
            <div class="col-md-3">
              <label class="form-label"><?php echo e(__('messages.type')); ?></label>
              <select name="type" class="form-select">
                <option value=""><?php echo e(__('messages.all_types')); ?></option>
                <option value="debit" <?php echo e(request('type')=='debit'?'selected':''); ?>><?php echo e(__('messages.debit')); ?></option>
                <option value="credit" <?php echo e(request('type')=='credit'?'selected':''); ?>><?php echo e(__('messages.credit')); ?></option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label"><?php echo e(__('messages.status')); ?></label>
              <select name="status" class="form-select">
                <option value=""><?php echo e(__('messages.all_statuses')); ?></option>
                <option value="completed" <?php echo e(request('status')=='completed'?'selected':''); ?>><?php echo e(__('messages.completed')); ?></option>
                <option value="pending" <?php echo e(request('status')=='pending'?'selected':''); ?>><?php echo e(__('messages.pending')); ?></option>
                <option value="failed" <?php echo e(request('status')=='failed'?'selected':''); ?>><?php echo e(__('messages.failed')); ?></option>
              </select>
            </div>
            <div class="col-md-1 d-flex align-items-end">
              <button class="btn btn-primary w-100" type="submit"><?php echo e(__('messages.apply_filters')); ?></button>
            </div>
            <div class="col-md-2 d-flex align-items-end">
              <a class="btn btn-secondary w-100" href="<?php echo e(route('admin.transactions.index')); ?>"><?php echo e(__('messages.reset')); ?></a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  
  <?php if(isset($stats)): ?>
  <div class="row mb-3">
    <div class="col-md-3"><div class="card"><div class="card-body"><strong><?php echo e(__('messages.total')); ?></strong><div class="h5 mb-0"><?php echo e($stats['total'] ?? 0); ?></div></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><strong><?php echo e(__('messages.completed')); ?></strong><div class="h5 mb-0"><?php echo e($stats['completed'] ?? 0); ?></div></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><strong><?php echo e(__('messages.pending')); ?></strong><div class="h5 mb-0"><?php echo e($stats['pending'] ?? 0); ?></div></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><strong><?php echo e(__('messages.failed')); ?></strong><div class="h5 mb-0"><?php echo e($stats['failed'] ?? 0); ?></div></div></div></div>
  </div>
  <?php endif; ?>

  
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead class="bg-light">
                <tr>
                  <th>#</th>
                  <th><?php echo e(__('messages.transaction_id')); ?></th>
                  <th><?php echo e(__('messages.user')); ?></th>
                  <th><?php echo e(__('messages.type')); ?></th>
                  <th><?php echo e(__('messages.status')); ?></th>
                  <th><?php echo e(__('messages.amount')); ?></th>
                  <th><?php echo e(__('messages.created_at')); ?></th>
                </tr>
              </thead>
              <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                  <tr>
                    <td><?php echo e($tx->id); ?></td>
                    <td><?php echo e($tx->transaction_id); ?></td>
                    <td><?php echo e(optional($tx->user)->name); ?></td>
                    <td><?php echo e($tx->type); ?></td>
                    <td><?php echo e($tx->status); ?></td>
                    <td><?php echo e(number_format((float)$tx->amount,2)); ?></td>
                    <td><?php echo e($tx->created_at); ?></td>
                  </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                  <tr><td colspan="7" class="text-center text-muted py-4"><?php echo e(__('messages.no_results')); ?></td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
        <?php if(method_exists($transactions,'links')): ?>
          <div class="card-footer"><?php echo e($transactions->withQueryString()->links()); ?></div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\bank-graduation-project-main\resources\views/admin/transactions/index.blade.php ENDPATH**/ ?>