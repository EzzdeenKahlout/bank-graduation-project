<?php $__env->startSection('title', __('messages.users_management')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-md me-3">
                                <i class="material-icons opacity-10">people</i>
                            </div>
                            <div>
                                <h5 class="mb-0"><?php echo e(__('messages.users_management')); ?></h5>
                                <p class="text-sm text-muted mb-0"><?php echo e(__('messages.manage_all_users')); ?></p>
                            </div>
                        </div>
                        <?php if(auth()->user()->hasPermission('create_users')): ?>
                        <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-primary">
                            <i class="material-icons text-sm">add</i>
                            <?php echo e(__('messages.create_user')); ?>

                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <span class="alert-icon"><i class="material-icons">check_circle</i></span>
        <span class="alert-text"><?php echo e(session('success')); ?></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <form method="GET" action="<?php echo e(route('admin.users.index')); ?>">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control"
                                       placeholder="<?php echo e(__('messages.search_users')); ?>"
                                       value="<?php echo e(request('search')); ?>">
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-select">
                                    <option value=""><?php echo e(__('messages.all_statuses')); ?></option>
                                    <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>
                                        <?php echo e(__('messages.active')); ?>

                                    </option>
                                    <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>
                                        <?php echo e(__('messages.inactive')); ?>

                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="permission" class="form-select">
                                    <option value=""><?php echo e(__('messages.all_permissions')); ?></option>
                                    <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($permission->name); ?>" <?php echo e(request('permission') == $permission->name ? 'selected' : ''); ?>>
                                        <?php echo e($permission->display_name); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="material-icons text-sm">search</i>
                                        <?php echo e(__('messages.search')); ?>

                                    </button>
                                    <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-outline-secondary">
                                        <i class="material-icons text-sm">clear</i>
                                    </a>
                                    <?php if(auth()->user()->hasPermission('export_reports')): ?>
                                    <a href="<?php echo e(route('admin.users.export')); ?>" class="btn btn-outline-success">
                                        <i class="material-icons text-sm">download</i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        <?php echo e(__('messages.user')); ?>

                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        <?php echo e(__('messages.contact')); ?>

                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        <?php echo e(__('messages.balance')); ?>

                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        <?php echo e(__('messages.permissions')); ?>

                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        <?php echo e(__('messages.status')); ?>

                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        <?php echo e(__('messages.joined')); ?>

                                    </th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <div class="avatar avatar-sm me-3 bg-gradient-primary">
                                                    <span class="text-white text-xs"><?php echo e(strtoupper(substr($user->name, 0, 2))); ?></span>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm"><?php echo e($user->name); ?></h6>
                                                <p class="text-xs text-secondary mb-0">ID: <?php echo e($user->id); ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0"><?php echo e($user->email); ?></p>
                                        <p class="text-xs text-secondary mb-0"><?php echo e($user->phone); ?></p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">$<?php echo e(number_format($user->balance, 2)); ?></p>
                                        <p class="text-xs text-secondary mb-0"><?php echo e(__('messages.limit')); ?>: $<?php echo e(number_format($user->daily_limit, 2)); ?></p>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            <?php $__empty_2 = true; $__currentLoopData = $user->permissions->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                            <span class="badge badge-sm bg-gradient-info"><?php echo e($permission->display_name); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                            <span class="text-xs text-secondary"><?php echo e(__('messages.no_permissions')); ?></span>
                                            <?php endif; ?>
                                            <?php if($user->permissions->count() > 3): ?>
                                            <span class="badge badge-sm bg-gradient-secondary">+<?php echo e($user->permissions->count() - 3); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if(auth()->user()->hasPermission('edit_users')): ?>
                                        <form action="<?php echo e(route('admin.users.toggle-status', $user)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="badge badge-sm bg-gradient-<?php echo e($user->is_active ? 'success' : 'danger'); ?> border-0">
                                                <?php echo e($user->is_active ? __('messages.active') : __('messages.inactive')); ?>

                                            </button>
                                        </form>
                                        <?php else: ?>
                                        <span class="badge badge-sm bg-gradient-<?php echo e($user->is_active ? 'success' : 'danger'); ?>">
                                            <?php echo e($user->is_active ? __('messages.active') : __('messages.inactive')); ?>

                                        </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="text-secondary text-xs font-weight-bold">
                                            <?php echo e($user->created_at->format('Y-m-d')); ?>

                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="dropdown">
                                            <button class="btn btn-link text-secondary mb-0" type="button" data-bs-toggle="dropdown">
                                                <i class="material-icons">more_vert</i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <?php if(auth()->user()->hasPermission('view_users')): ?>
                                                <li>
                                                    <a class="dropdown-item" href="<?php echo e(route('admin.users.show', $user)); ?>" onclick="event.preventDefault(); window.location.assign(this.href);">
                                                        <i class="material-icons text-sm me-2">visibility</i>
                                                        <?php echo e(__('messages.view_details')); ?>

                                                    </a>
                                                </li>
                                                <?php endif; ?>
                                                <?php if(auth()->user()->hasPermission('edit_users')): ?>
                                                <li>
                                                    <a class="dropdown-item" href="<?php echo e(route('admin.users.edit', $user)); ?>" onclick="event.preventDefault(); window.location.assign(this.href);">
                                                        <i class="material-icons text-sm me-2">edit</i>
                                                        <?php echo e(__('messages.edit')); ?>

                                                    </a>
                                                </li>
                                                <?php endif; ?>
                                                <?php if(auth()->user()->hasPermission('manage_permissions')): ?>
                                                <li>
                                                    <a class="dropdown-item" href="<?php echo e(route('admin.users.permissions', $user)); ?>" onclick="event.preventDefault(); window.location.assign(this.href);">
                                                        <i class="material-icons text-sm me-2">security</i>
                                                        <?php echo e(__('messages.manage_permissions')); ?>

                                                    </a>
                                                </li>
                                                <?php endif; ?>
                                                <?php if(auth()->user()->hasPermission('delete_users') && $user->id !== auth()->id()): ?>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="<?php echo e(route('admin.users.destroy', $user)); ?>" method="POST"
                                                          onsubmit="return confirm('<?php echo e(__('messages.confirm_delete_user')); ?>')">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="material-icons text-sm me-2">delete</i>
                                                            <?php echo e(__('messages.delete')); ?>

                                                        </button>
                                                    </form>
                                                </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="material-icons text-muted" style="font-size: 48px;">people_outline</i>
                                        <p class="text-muted mt-3"><?php echo e(__('messages.no_users_found')); ?></p>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php if($users->hasPages()): ?>
                <div class="card-footer">
                    <?php echo e($users->links()); ?>

                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\bank-graduation-project-main\resources\views/admin/users/index.blade.php ENDPATH**/ ?>