<?php $__env->startSection('title', __('messages.pay_merchant')); ?>

<?php $__env->startSection('content'); ?>

<!-- Include Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    /* ===================================== */
    /* Select2 Enhanced Styling */
    /* ===================================== */

    .select2-container {
        width: 100% !important;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Selection Box */
    .select2-container--default .select2-selection--single {
        height: 55px !important;
        border: 2px solid #e0e0e0 !important;
        border-radius: 12px !important;
        padding: 8px 15px !important;
        background: white !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05) !important;
    }

    .select2-container--default.select2-container--focus .select2-selection--single,
    .select2-container--default .select2-selection--single:hover {
        border-color: #667eea !important;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
    }

    /* Selected Text */
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px !important;
        padding-<?php echo e(app()->getLocale() == 'ar' ? 'right' : 'left'); ?>: 15px !important;
        padding-<?php echo e(app()->getLocale() == 'ar' ? 'left' : 'right'); ?>: 40px !important;
        color: #333 !important;
        font-size: 1rem !important;
        font-weight: 500 !important;
    }

    /* Placeholder */
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #999 !important;
        font-weight: 400 !important;
    }

    /* Arrow */
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 53px !important;
        <?php echo e(app()->getLocale() == 'ar' ? 'left' : 'right'); ?>: 15px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #667eea transparent transparent transparent !important;
        border-width: 6px 5px 0 5px !important;
        margin-<?php echo e(app()->getLocale() == 'ar' ? 'right' : 'left'); ?>: -5px !important;
        margin-top: -3px !important;
    }

    .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-color: transparent transparent #667eea transparent !important;
        border-width: 0 5px 6px 5px !important;
    }

    /* Dropdown Container */
    .select2-dropdown {
        border: 2px solid #667eea !important;
        border-radius: 12px !important;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2) !important;
        margin-top: 5px !important;
        overflow: hidden;
    }

    /* Search Box */
    .select2-search--dropdown {
        padding: 15px !important;
        background: #f8f9fa !important;
    }

    .select2-search--dropdown .select2-search__field {
        border: 2px solid #e0e0e0 !important;
        border-radius: 10px !important;
        padding: 12px 15px !important;
        font-size: 1rem !important;
        width: 100% !important;
        transition: all 0.3s ease !important;
    }

    .select2-search--dropdown .select2-search__field:focus {
        border-color: #667eea !important;
        outline: none !important;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
    }

    /* Results Container */
    .select2-results {
        max-height: 300px !important;
        overflow-y: auto !important;
    }

    /* Options */
    .select2-results__option {
        padding: 12px 20px !important;
        font-size: 0.95rem !important;
        transition: all 0.2s ease !important;
    }

    .select2-results__option--highlighted[aria-selected] {
        background-color: #667eea !important;
        color: white !important;
    }

    .select2-results__option[aria-selected=true] {
        background-color: #f0f0ff !important;
        color: #667eea !important;
        font-weight: 600 !important;
    }

    /* Loading Text */
    .select2-results__option.loading-results {
        text-align: center !important;
        color: #667eea !important;
        padding: 20px !important;
        font-weight: 600 !important;
    }

    /* No Results */
    .select2-results__option.select2-results__message {
        text-align: center !important;
        color: #999 !important;
        padding: 20px !important;
    }

    /* Custom Merchant Option Display */
    .merchant-option {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .merchant-option-icon {
        font-size: 1.8rem;
        flex-shrink: 0;
    }

    .merchant-option-content {
        flex: 1;
        min-width: 0;
    }

    .merchant-option-name {
        font-weight: 600;
        color: inherit;
        margin-bottom: 3px;
        font-size: 1rem;
    }

    .merchant-option-type {
        font-size: 0.85rem;
        opacity: 0.8;
    }

    /* Selected Merchant Card */
    .selected-merchant-card {
        display: none;
        padding: 20px;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.08), rgba(118, 75, 162, 0.08));
        border-radius: 15px;
        margin-top: 15px;
        border: 2px solid #667eea;
        animation: slideDown 0.4s ease;
    }

    .selected-merchant-card.active {
        display: block;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-15px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .merchant-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .merchant-icon {
        font-size: 3.5rem;
        flex-shrink: 0;
    }

    .merchant-details {
        flex: 1;
    }

    .merchant-name {
        font-size: 1.4rem;
        font-weight: bold;
        color: #667eea;
        margin-bottom: 5px;
    }

    .merchant-type {
        color: #666;
        font-size: 1rem;
    }

    /* Loading Overlay */
    .loading-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.95);
        z-index: 9999;
        justify-content: center;
        align-items: center;
    }

    .loading-overlay.active {
        display: flex;
    }

    .spinner-container {
        text-align: center;
    }

    .spinner {
        border: 5px solid #f3f3f3;
        border-top: 5px solid #667eea;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        animation: spin 1s linear infinite;
        margin: 0 auto 20px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .loading-text {
        color: #667eea;
        font-weight: 600;
        font-size: 1.1rem;
    }

    /* Form Enhancements */
    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        margin-bottom: 10px;
        font-weight: 600;
        font-size: 1.05rem;
    }

    .form-icon {
        margin-<?php echo e(app()->getLocale() == 'ar' ? 'left' : 'right'); ?>: 8px;
    }

    .form-control:focus {
        border-color: #667eea !important;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
        outline: none;
    }

    /* Submit Button Enhancement */
    .btn-submit {
        width: 100%;
        padding: 15px;
        font-size: 1.15rem;
        font-weight: 600;
        border-radius: 12px;
        margin-top: 10px;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }

    .btn-submit:active {
        transform: translateY(0);
    }

    /* Balance Card */
    .balance-card {
        margin-top: 30px;
        padding: 20px;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border-radius: 15px;
        border: 2px solid #e0e0e0;
    }

    .balance-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .balance-label {
        font-size: 1.15rem;
        font-weight: 600;
        color: #333;
    }

    .balance-amount {
        color: #667eea;
        font-weight: bold;
        font-size: 1.5rem;
    }
</style>

<!-- Loading Overlay -->
<div class="loading-overlay" id="pageLoadingOverlay">
    <div class="spinner-container">
        <div class="spinner"></div>
        <p class="loading-text"><?php echo e(__('messages.loading')); ?>...</p>
    </div>
</div>

<div style="max-width: 750px; margin: 2rem auto;">
    <div class="card">
        <h2 style="color: #667eea; margin-bottom: 2rem; font-size: 1.8rem;">
            🛒 <?php echo e(__('messages.pay_merchant')); ?>

        </h2>

        <form method="POST" action="<?php echo e(route('pay.merchant')); ?>" id="paymentForm">
            <?php echo csrf_field(); ?>

            <!-- Merchant Selection -->
            <div class="form-group">
                <label for="merchant_select">
                    <span class="form-icon" style="color: #667eea;">📍</span>
                    <?php echo e(app()->getLocale() == 'ar' ? 'اختر التاجر' : 'Select Merchant'); ?>

                </label>
                <select name="merchant_id" id="merchant_select" class="form-control" required>
                    <option value="">
                        <?php echo e(app()->getLocale() == 'ar' ? 'ابحث عن التاجر...' : 'Search for merchant...'); ?>

                    </option>
                </select>
                <?php $__errorArgs = ['merchant_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <small style="color: #dc3545; margin-top: 5px; display: block;"><?php echo e($message); ?></small>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Selected Merchant Display -->
            <div class="selected-merchant-card" id="selectedMerchantCard">
                <div class="merchant-info">
                    <div class="merchant-icon">🏪</div>
                    <div class="merchant-details">
                        <div class="merchant-name" id="displayMerchantName"></div>
                        <div class="merchant-type" id="displayMerchantType"></div>
                    </div>
                </div>
            </div>

            <!-- Amount -->
            <div class="form-group">
                <label for="amount">
                    <span class="form-icon" style="color: #667eea;">💰</span>
                    <?php echo e(__('messages.amount')); ?> (<?php echo e(__('messages.currency_symbol')); ?>)
                </label>
                <input type="number" name="amount" id="amount" class="form-control"
                       step="0.01" min="1" placeholder="100.00" required
                       style="font-size: 1.2rem; font-weight: 600;">
                <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <small style="color: #dc3545; margin-top: 5px; display: block;"><?php echo e($message); ?></small>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="description">
                    <span class="form-icon" style="color: #667eea;">📝</span>
                    <?php echo e(app()->getLocale() == 'ar' ? 'ماذا تشتري؟' : 'What are you buying?'); ?>

                </label>
                <input type="text" name="description" id="description" class="form-control"
                       placeholder="<?php echo e(app()->getLocale() == 'ar' ? 'مثال: شراء هاتف، فاتورة كهرباء، اشتراك شهري' : 'Example: Phone purchase, electricity bill, monthly subscription'); ?>"
                       required>
                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <small style="color: #dc3545; margin-top: 5px; display: block;"><?php echo e($message); ?></small>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- PIN -->
            <div class="form-group">
                <label for="pin">
                    <span class="form-icon" style="color: #667eea;">🔐</span>
                    <?php echo e(app()->getLocale() == 'ar' ? 'رقم PIN (4 أرقام)' : 'PIN Code (4 digits)'); ?>

                </label>
                <input type="password" name="pin" id="pin" class="form-control"
                       maxlength="4" pattern="[0-9]{4}"
                       placeholder="****" required
                       style="letter-spacing: 0.5rem; text-align: center; font-size: 1.5rem;">
                <?php $__errorArgs = ['pin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <small style="color: #dc3545; margin-top: 5px; display: block;"><?php echo e($message); ?></small>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary btn-submit">
                <span style="font-size: 1.3rem; margin-<?php echo e(app()->getLocale() == 'ar' ? 'left' : 'right'); ?>: 10px;">💳</span>
                <?php echo e(app()->getLocale() == 'ar' ? 'تأكيد الدفع' : 'Confirm Payment'); ?>

            </button>
        </form>

        <!-- Current Balance -->
        <div class="balance-card">
            <div class="balance-info">
                <span class="balance-label">
                    <span style="font-size: 1.4rem; margin-<?php echo e(app()->getLocale() == 'ar' ? 'left' : 'right'); ?>: 8px;">💵</span>
                    <?php echo e(__('messages.current_balance')); ?>:
                </span>
                <span class="balance-amount">
                    <?php echo e(__('messages.currency_symbol')); ?><?php echo e(number_format(auth()->user()->balance, 2)); ?>

                </span>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery (required for Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2 with AJAX
    $('#merchant_select').select2({
        ajax: {
            url: '<?php echo e(route("api.merchants")); ?>',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term,
                    page: params.page || 1
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;

                return {
                    results: data.results,
                    pagination: {
                        more: data.pagination.more
                    }
                };
            },
            cache: true
        },
        placeholder: '<?php echo e(app()->getLocale() == "ar" ? "ابحث عن التاجر بالاسم أو النوع..." : "Search for merchant by name or type..."); ?>',
        minimumInputLength: 0,
        language: {
            searching: function() {
                return '<?php echo e(app()->getLocale() == "ar" ? "جاري البحث..." : "Searching..."); ?>';
            },
            noResults: function() {
                return '<?php echo e(app()->getLocale() == "ar" ? "لم يتم العثور على نتائج" : "No results found"); ?>';
            },
            loadingMore: function() {
                return '<?php echo e(app()->getLocale() == "ar" ? "جاري تحميل المزيد..." : "Loading more..."); ?>';
            },
            inputTooShort: function() {
                return '<?php echo e(app()->getLocale() == "ar" ? "ابدأ الكتابة للبحث" : "Start typing to search"); ?>';
            }
        },
        templateResult: formatMerchantOption,
        templateSelection: formatMerchantSelection,
        dir: '<?php echo e(app()->getLocale() == "ar" ? "rtl" : "ltr"); ?>',
        dropdownCssClass: 'select2-merchant-dropdown',
        width: '100%'
    });

    // Format merchant option in dropdown
    function formatMerchantOption(merchant) {
        if (merchant.loading) {
            return merchant.text;
        }

        if (!merchant.id) {
            return merchant.text;
        }

        var $option = $(
            '<div class="merchant-option">' +
                '<div class="merchant-option-icon">🏪</div>' +
                '<div class="merchant-option-content">' +
                    '<div class="merchant-option-name">' + merchant.name + '</div>' +
                    '<div class="merchant-option-type">' + merchant.business_type + '</div>' +
                '</div>' +
            '</div>'
        );

        return $option;
    }

    // Format selected merchant
    function formatMerchantSelection(merchant) {
        if (merchant.id) {
            return '🏪 ' + (merchant.name || merchant.text);
        }
        return merchant.text;
    }

    // When merchant is selected
    $('#merchant_select').on('select2:select', function (e) {
        var data = e.params.data;

        // Update selected merchant display
        $('#displayMerchantName').text(data.name);
        $('#displayMerchantType').text(data.business_type);
        $('#selectedMerchantCard').addClass('active');
    });

    // When merchant is cleared
    $('#merchant_select').on('select2:clear select2:unselect', function (e) {
        $('#selectedMerchantCard').removeClass('active');
    });

    // Load initial merchants on page load
    setTimeout(function() {
        $('#merchant_select').select2('open');
        setTimeout(function() {
            $('#merchant_select').select2('close');
        }, 100);
    }, 300);

    // Form submission loading state
    $('#paymentForm').on('submit', function(e) {
        // Validate form before showing loading
        if (this.checkValidity()) {
            $('#pageLoadingOverlay').addClass('active');
        }
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\bank-graduation-project-main\resources\views/transactions/pay-merchant.blade.php ENDPATH**/ ?>