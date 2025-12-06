<?php $__env->startSection('title', __('messages.cards_list')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
  <div class="row mb-3">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0"><?php echo e(__('messages.cards_list')); ?></h5>
            <?php if (\Illuminate\Support\Facades\Blade::check('permission', 'view_cards')): ?>
            <a href="<?php echo e(route('admin.cards.export')); ?>" class="btn btn-outline-primary btn-sm">
              <?php echo e(__('messages.export_csv')); ?>

            </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  
  <div class="row mb-3">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form method="GET" action="<?php echo e(route('admin.cards.index')); ?>" class="row g-3">
            <div class="col-md-3">
              <label class="form-label"><?php echo e(__('messages.status')); ?></label>
              <select name="status" class="form-select">
                <option value=""><?php echo e(__('messages.all_statuses')); ?></option>
                <option value="active" <?php echo e(request('status')=='active'?'selected':''); ?>><?php echo e(__('messages.active')); ?></option>
                <option value="blocked" <?php echo e(request('status')=='blocked'?'selected':''); ?>><?php echo e(__('messages.blocked')); ?></option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label"><?php echo e(__('messages.type')); ?></label>
              <select name="card_type" class="form-select">
                <option value=""><?php echo e(__('messages.all_types')); ?></option>
                <option value="debit" <?php echo e(request('card_type')=='debit'?'selected':''); ?>><?php echo e(__('messages.debit')); ?></option>
                <option value="credit" <?php echo e(request('card_type')=='credit'?'selected':''); ?>><?php echo e(__('messages.credit')); ?></option>
                <option value="virtual" <?php echo e(request('card_type')=='virtual'?'selected':''); ?>><?php echo e(__('messages.virtual')); ?></option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label"><?php echo e(__('messages.search')); ?></label>
              <input type="text" class="form-control" name="search" value="<?php echo e(request('search')); ?>" placeholder="<?php echo e(__('messages.search_by')); ?>">
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
              <button class="btn btn-primary w-100" type="submit"><?php echo e(__('messages.apply_filters')); ?></button>
              <a class="btn btn-secondary" href="<?php echo e(route('admin.cards.index')); ?>"><?php echo e(__('messages.reset')); ?></a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  
  <?php if(isset($stats)): ?>
  <div class="row mb-3">
    <div class="col-md-3"><div class="card"><div class="card-body"><strong><?php echo e(__('messages.total_cards')); ?></strong><div class="h5 mb-0"><?php echo e($stats['total'] ?? 0); ?></div></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><strong><?php echo e(__('messages.active_cards')); ?></strong><div class="h5 mb-0"><?php echo e($stats['active'] ?? 0); ?></div></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><strong><?php echo e(__('messages.blocked_cards')); ?></strong><div class="h5 mb-0"><?php echo e($stats['blocked'] ?? 0); ?></div></div></div></div>
  </div>
  <?php endif; ?>

  

  
  <?php if(isset($pending) && $pending->count()): ?>
  <div class="row mb-4">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <h6 class="mb-3"><?php echo e(__('messages.pending_card_requests')); ?></h6>
          <div class="table-responsive">
            <table class="table table-sm align-middle">
              <thead>
                <tr>
                  <th>#</th>
                  <th><?php echo e(__('messages.user')); ?></th>
                  <th><?php echo e(__('messages.card_holder_name')); ?></th>
                  <th><?php echo e(__('messages.type')); ?></th>
                  <th><?php echo e(__('messages.reason')); ?></th>
                  <th><?php echo e(__('messages.created_at')); ?></th>
                  <th><?php echo e(__('messages.actions')); ?></th>
                </tr>
              </thead>
              <tbody>
                <?php $__currentLoopData = $pending; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <tr>
                    <td><?php echo e($req->id); ?></td>
                    <td><?php echo e(optional($req->user)->name); ?> (<?php echo e(optional($req->user)->email); ?>)</td>
                    <td><?php echo e($req->card_holder_name); ?></td>
                    <td><?php echo e($req->card_type); ?></td>
                    <td><?php echo e($req->reason ?? '-'); ?></td>
                    <td><?php echo e($req->created_at); ?></td>
                    <td>
                      <form method="POST" action="<?php echo e(route('admin.cards.requests.approve', $req)); ?>" onsubmit="return confirm('<?php echo e(__('messages.confirm_approve_card_request')); ?>');">
                        <?php echo csrf_field(); ?>
                        <button class="btn btn-success btn-sm" type="submit"><?php echo e(__('messages.approve')); ?></button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
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
                  <th><?php echo e(__('messages.card_number')); ?></th>
                  <th><?php echo e(__('messages.type')); ?></th>
                  <th><?php echo e(__('messages.status')); ?></th>
                  <th><?php echo e(__('messages.user')); ?></th>
                  <th><?php echo e(__('messages.created_at')); ?></th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                  <tr>
                    <td><?php echo e($card->id); ?></td>
                    <td><?php echo e($card->masked_number ?? $card->card_number); ?></td>
                    <td><?php echo e($card->card_type); ?></td>
                    <td><?php echo e($card->status); ?></td>
                    <td><?php echo e(optional($card->user)->name); ?></td>
                    <td><?php echo e($card->created_at); ?></td>
                    <td>
                      <a href="<?php echo e(route('admin.cards.show', $card)); ?>" class="btn btn-sm btn-outline-primary"><?php echo e(__('messages.details')); ?></a>
                    </td>
                  </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                  <tr><td colspan="7" class="text-center text-muted py-4"><?php echo e(__('messages.no_results')); ?></td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
        <?php if(method_exists($cards,'links')): ?>
          <div class="card-footer"><?php echo e($cards->withQueryString()->links()); ?></div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\bank-graduation-project-main\resources\views/admin/cards/index.blade.php ENDPATH**/ ?>