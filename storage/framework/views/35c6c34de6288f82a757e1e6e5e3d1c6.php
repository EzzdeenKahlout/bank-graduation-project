<?php $__env->startSection('title', __('messages.admin_dashboard')); ?>

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
                                <i class="material-icons opacity-10">dashboard</i>
                            </div>
                            <div>
                                <h5 class="mb-0"><?php echo e(__('messages.admin_dashboard')); ?></h5>
                                <p class="text-sm text-muted mb-0"><?php echo e(__('messages.welcome_admin', ['name' => auth()->user()->name])); ?></p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="refreshDashboard()">
                                <i class="material-icons text-sm">refresh</i>
                                <?php echo e(__('messages.refresh')); ?>

                            </button>
<?php if (\Illuminate\Support\Facades\Blade::check('permission', 'view_admin_health')): ?>
                            <a href="<?php echo e(route('admin.health')); ?>" class="btn btn-outline-info btn-sm">
                                <i class="material-icons text-sm">monitor_heart</i>
                                <?php echo e(__('messages.system_health')); ?>

                            </a>
<?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Total Users -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold"><?php echo e(__('messages.total_users')); ?></p>
                                <h5 class="font-weight-bolder mb-0">
                                    <?php echo e(number_format($stats['total_users'])); ?>

                                    <span class="text-success text-sm font-weight-bolder">
                                        +<?php echo e($stats['active_users']); ?> <?php echo e(__('messages.active')); ?>

                                    </span>
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                <i class="material-icons opacity-10">people</i>
                            </div>
                        </div>
                    </div>
                    <hr class="dark horizontal my-2">
                    <div class="d-flex">
                        <a href="<?php echo e(route('admin.users.index')); ?>" class="text-sm text-primary"><?php echo e(__('messages.view_all')); ?></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Cards -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold"><?php echo e(__('messages.total_cards')); ?></p>
                                <h5 class="font-weight-bolder mb-0">
                                    <?php echo e(number_format($stats['total_cards'])); ?>

                                    <span class="text-success text-sm font-weight-bolder">
                                        +<?php echo e($stats['active_cards']); ?> <?php echo e(__('messages.active')); ?>

                                    </span>
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                <i class="material-icons opacity-10">credit_card</i>
                            </div>
                        </div>
                    </div>
                    <hr class="dark horizontal my-2">
                    <div class="d-flex">
                        <a href="<?php echo e(route('admin.cards.index')); ?>" class="text-sm text-success"><?php echo e(__('messages.view_all')); ?></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Roles-->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold"><?php echo e(__('messages.total_roles')); ?></p>
                                <h5 class="font-weight-bolder mb-0">
                                    <?php echo e(number_format($stats['total_roles'])); ?>


                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                <i class="material-icons opacity-10">credit_card</i>
                            </div>
                        </div>
                    </div>
                    <hr class="dark horizontal my-2">
                    <div class="d-flex">
                        <a href="<?php echo e(route('admin.roles.index')); ?>" class="text-sm text-success"><?php echo e(__('messages.view_all')); ?></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Transactions -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold"><?php echo e(__('messages.transactions_today')); ?></p>
                                <h5 class="font-weight-bolder mb-0">
                                    <?php echo e(number_format($stats['today_transactions'])); ?>

                                    <span class="text-warning text-sm font-weight-bolder">
                                        / <?php echo e(number_format($stats['total_transactions'])); ?>

                                    </span>
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                <i class="material-icons opacity-10">receipt_long</i>
                            </div>
                        </div>
                    </div>
                    <hr class="dark horizontal my-2">
                    <div class="d-flex">
                        <a href="<?php echo e(route('admin.transactions.index')); ?>" class="text-sm text-warning"><?php echo e(__('messages.view_all')); ?></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Amount -->
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold"><?php echo e(__('messages.amount_today')); ?></p>
                                <h5 class="font-weight-bolder mb-0">
                                    $<?php echo e(number_format($stats['today_amount'], 2)); ?>

                                    <span class="text-info text-sm font-weight-bolder">
                                        / $<?php echo e(number_format($stats['total_transaction_amount'], 2)); ?>

                                    </span>
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-info shadow-info text-center rounded-circle">
                                <i class="material-icons opacity-10">attach_money</i>
                            </div>
                        </div>
                    </div>
                    <hr class="dark horizontal my-2">
                    <div class="d-flex">
<?php if (\Illuminate\Support\Facades\Blade::check('permission', 'view_admin_analytics')): ?>
                        <a href="<?php echo e(route('admin.transactions.index')); ?>" class="text-sm text-info"><?php echo e(__('messages.view_analytics')); ?></a>
<?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Transaction Trends -->
        <div class="col-lg-7 mb-lg-0 mb-4">
            <div class="card h-100">
                <div class="card-header pb-0 p-3">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo e(__('messages.transaction_trends')); ?></h6>
                        <select class="form-select form-select-sm w-auto" onchange="updateCharts(this.value)">
                            <option value="7days"><?php echo e(__('messages.last_7_days')); ?></option>
                            <option value="30days"><?php echo e(__('messages.last_30_days')); ?></option>
                            <option value="year"><?php echo e(__('messages.this_year')); ?></option>
                        </select>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="chart-box" style="position:relative;height:320px;width:100%;"><canvas id="transactionTrendsChart"></canvas></div>
                </div>
            </div>
        </div>

        <!-- Cards & Transactions Distribution -->
        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header pb-0 p-3">
                    <h6 class="mb-0"><?php echo e(__('messages.distribution')); ?></h6>
                </div>
                <div class="card-body p-3">
                    <div class="mb-4">
                        <h6 class="text-sm"><?php echo e(__('messages.cards_by_type')); ?></h6>
                        <div class="chart-box" style="position:relative;height:220px;width:100%;"><canvas id="cardsByTypeChart"></canvas></div>
                    </div>
                    <div>
                        <h6 class="text-sm"><?php echo e(__('messages.transactions_by_type')); ?></h6>
                        <div class="chart-box" style="position:relative;height:220px;width:100%;"><canvas id="transactionsByTypeChart"></canvas></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0 p-3">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo e(__('messages.recent_transactions')); ?></h6>
                        <a href="<?php echo e(route('admin.transactions.index')); ?>" class="btn btn-sm btn-outline-primary">
                            <?php echo e(__('messages.view_all')); ?>

                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        <?php echo e(__('messages.transaction')); ?>

                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        <?php echo e(__('messages.user')); ?>

                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        <?php echo e(__('messages.type')); ?>

                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        <?php echo e(__('messages.amount')); ?>

                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        <?php echo e(__('messages.status')); ?>

                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        <?php echo e(__('messages.date')); ?>

                                    </th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm"><?php echo e($transaction->transaction_id); ?></h6>
                                                <p class="text-xs text-secondary mb-0"><?php echo e(Str::limit($transaction->description, 30)); ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0"><?php echo e($transaction->user->name); ?></p>
                                        <p class="text-xs text-secondary mb-0"><?php echo e($transaction->user->email); ?></p>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-<?php echo e($transaction->type == 'credit' ? 'success' : 'warning'); ?>">
                                            <?php echo e(ucfirst($transaction->type)); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">$<?php echo e(number_format($transaction->amount, 2)); ?></p>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-<?php echo e($transaction->status == 'completed' ? 'success' : ($transaction->status == 'pending' ? 'warning' : 'danger')); ?>">
                                            <?php echo e(ucfirst($transaction->status)); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-secondary text-xs font-weight-bold">
                                            <?php echo e($transaction->created_at->diffForHumans()); ?>

                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <a href="<?php echo e(route('admin.transactions.show', $transaction)); ?>"
                                           class="text-secondary font-weight-bold text-xs"
                                           data-toggle="tooltip"
                                           title="<?php echo e(__('messages.view_details')); ?>">
                                            <i class="material-icons text-sm">visibility</i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="material-icons text-muted" style="font-size: 48px;">inbox</i>
                                        <p class="text-muted"><?php echo e(__('messages.no_transactions_found')); ?></p>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Transaction Trends Chart handled by updateCharts()
// Cards by Type Chart
const cardsData = <?php echo json_encode($cardsByType, 15, 512) ?>;
const cardsCtx = document.getElementById('cardsByTypeChart').getContext('2d');
new Chart(cardsCtx, {
    type: 'doughnut',
    data: {
        labels: cardsData.map(item => item.card_type),
        datasets: [{
            data: cardsData.map(item => item.count),
            backgroundColor: ['#36a2eb', '#ff6384']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});



// Transactions by Type Chart (rename labels only - no bucketing, no totals change)
const transData = <?php echo json_encode($transactionsByType, 15, 512) ?>;
const labelMap = (t) => {
  const s = (t || '').toString().toLowerCase().trim();
  if (s.includes('transfer') || s.includes('تحويل') || s.includes('friend')) return 'تحويل لصديق';
  if (s.includes('payment') || s.includes('pay') || s.includes('merchant') || s.includes('تاجر') || s.includes('card')) return 'دفع لتاجر';
  return 'غير محدد';
};
const transLabels = transData.map(item => labelMap(item.type));
const transValues = transData.map(item => Number(item.count || 0));
const transCtx = document.getElementById('transactionsByTypeChart').getContext('2d');
new Chart(transCtx, {
    type: 'pie',
    data: { labels: transLabels, datasets: [{ data: transValues, backgroundColor: ['#4bc0c0','#ffcd56','#ff6384','#36a2eb','#9966ff','#ff9f40'] }] },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top' }, tooltip: { callbacks: { label: (ctx) => `${ctx.label}: ${new Intl.NumberFormat('ar-EG').format(ctx.parsed)}` } }, title: { display: false } } }

});

function refreshDashboard() {
    location.reload();
}

function updateCharts(period) {
    fetch(`<?php echo e(route('admin.analytics')); ?>?period=${period}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
      .then(r => r.json())
      .then(json => {
        const bins = json.amount_bins;
        if (!bins) return;
        const labels = bins.labels; // Y axis (days/months/years)
        const binLabels = bins.binLabels; // X bins labels
        const series = bins.series; // [bin][time] = amount sum

        if (window.txTrendChart) window.txTrendChart.destroy();

        const ctx = document.getElementById('transactionTrendsChart').getContext('2d');
        const palette = ['#4dc9f6','#f67019','#f53794','#537bc4','#acc236','#166a8f','#00a950','#58595b','#8549ba','#ffa600','#bc5090','#003f5c'];

        const datasets = series.map((arr, i) => ({
          label: binLabels[i],
          data: arr,
          backgroundColor: palette[i % palette.length],
          stack: 'amounts'
        }));

        window.txTrendChart = new Chart(ctx, {
          type: 'bar',
          data: { labels: labels, datasets: datasets },
          options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: { position: 'top' },
              tooltip: { callbacks: { label: (ctx) => `${ctx.dataset.label}: ${new Intl.NumberFormat('ar-EG').format(ctx.parsed.x || 0)}` } },
              title: { display: true, text: '<?php echo e(__('messages.transactions')); ?> - توزيع المبالغ حسب فئات 1000+' }
            },
            scales: {
              x: { beginAtZero: true, stacked: true, ticks: { callback: (v) => new Intl.NumberFormat('ar-EG').format(v) } },
              y: { stacked: true }
            }
          }
        });
      });
}

document.addEventListener('DOMContentLoaded', function(){
  const selectEl = document.querySelector('.card-header select.form-select');
  const initial = selectEl ? selectEl.value : '7days';
  updateCharts(initial);
});
</script>

<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\bank-graduation-project-main\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>