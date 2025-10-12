@extends('layouts.app')

@section('title', __('messages.create_role'))

@section('content')
<style>
    .create-role-page {
        padding: 2rem 0;
    }

    .form-card {
        max-width: 800px;
        margin: 0 auto;
        background: white;
        padding: 3rem;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .form-card h2 {
        color: #667eea;
        margin-bottom: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #333;
        font-weight: 500;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.3s;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
    }

    .permissions-section {
        margin-top: 2rem;
        padding: 1.5rem;
        background: #f8f9fa;
        border-radius: 10px;
    }

    .permissions-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .permissions-header h3 {
        color: #667eea;
        margin: 0;
    }

    .permissions-counter {
        background: #e0e7ff;
        color: #667eea;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: bold;
    }

    .master-select-all {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .btn-select-all, .btn-deselect-all {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 0.9rem;
    }

    .btn-select-all {
        background: #4ade80;
        color: white;
    }

    .btn-select-all:hover {
        background: #22c55e;
        transform: translateY(-2px);
    }

    .btn-deselect-all {
        background: #ef4444;
        color: white;
    }

    .btn-deselect-all:hover {
        background: #dc2626;
        transform: translateY(-2px);
    }

    /* قسم الصلاحيات القابل للطي */
    .permission-group {
        margin-bottom: 1rem;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .permission-group-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        cursor: pointer;
        background: white;
        transition: all 0.3s;
        user-select: none;
    }

    .permission-group-header:hover {
        background: #f8f9fa;
    }

    .permission-group-header.active {
        background: #e0e7ff;
    }

    .permission-group-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: bold;
        color: #333;
        font-size: 1rem;
    }

    .collapse-icon {
        font-size: 1.2rem;
        transition: transform 0.3s;
        color: #667eea;
    }

    .collapse-icon.rotated {
        transform: rotate(90deg);
    }

    .permission-group-count {
        background: #e0e7ff;
        color: #667eea;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .permission-group-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
        padding: 0 1rem;
    }

    .permission-group-content.show {
        max-height: 2000px;
        transition: max-height 0.5s ease-in;
        padding: 0 1rem 1rem 1rem;
    }

    .select-all-group {
        margin-bottom: 1rem;
        padding: 0.75rem;
        background: #f8f9fa;
        border-radius: 5px;
    }

    .select-all-group input {
        margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 0.5rem;
        width: 16px;
        height: 16px;
        cursor: pointer;
    }

    .select-all-group label {
        font-weight: bold;
        color: #667eea;
        cursor: pointer;
    }

    .permission-checkbox {
        display: flex;
        align-items: center;
        padding: 0.5rem;
        margin-bottom: 0.5rem;
        border-radius: 5px;
        transition: background 0.2s;
    }

    .permission-checkbox:hover {
        background: #f8f9fa;
    }

    .permission-checkbox input {
        margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 0.75rem;
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .permission-checkbox label {
        cursor: pointer;
        margin: 0;
        font-weight: normal;
        flex: 1;
    }

    .permission-description {
        color: #666;
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn-submit {
        flex: 1;
        padding: 1rem;
        background: #667eea;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-submit:hover {
        background: #5568d3;
    }

    .btn-cancel {
        flex: 1;
        padding: 1rem;
        background: #e0e0e0;
        color: #333;
        border: none;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: bold;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
    }

    .expand-collapse-all {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .btn-expand-all, .btn-collapse-all {
        padding: 0.5rem 1rem;
        background: #667eea;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-expand-all:hover, .btn-collapse-all:hover {
        background: #5568d3;
    }
</style>

<div class="create-role-page">
    <div class="form-card">
        <h2>➕ {{ __('messages.create_role') }}</h2>

        <form method="POST" action="{{ route('admin.roles.store') }}">
            @csrf

            <div class="form-group">
                <label for="name">{{ __('messages.role_name') }} *</label>
                <input type="text"
                       id="name"
                       name="name"
                       class="form-control"
                       value="{{ old('name') }}"
                       required
                       placeholder="manager">
                <small style="color: #666;">{{ __('messages.role_name_hint') }}</small>
            </div>

            <div class="form-group">
                <label for="display_name">{{ __('messages.display_name') }} *</label>
                <input type="text"
                       id="display_name"
                       name="display_name"
                       class="form-control"
                       value="{{ old('display_name') }}"
                       required
                       placeholder="Manager">
            </div>

            <div class="form-group">
                <label for="description">{{ __('messages.description') }}</label>
                <textarea id="description"
                          name="description"
                          class="form-control"
                          rows="3"
                          placeholder="{{ __('messages.description') }}">{{ old('description') }}</textarea>
            </div>

            <div class="permissions-section">
                <div class="permissions-header">
                    <h3>🔐 {{ __('messages.permissions') }}</h3>
                    <span class="permissions-counter">
                        <span id="selected-count">0</span> / <span id="total-count">{{ $permissions->flatten()->count() }}</span> {{ __('messages.selected') }}
                    </span>
                    <div class="master-select-all">
                        <button type="button" class="btn-select-all" onclick="selectAllPermissions()">
                            ✅ {{ __('messages.select_all_permissions') }}
                        </button>
                        <button type="button" class="btn-deselect-all" onclick="deselectAllPermissions()">
                            ❌ {{ __('messages.deselect_all_permissions') }}
                        </button>
                    </div>
                </div>

                <div class="expand-collapse-all">
                    <button type="button" class="btn-expand-all" onclick="expandAllGroups()">
                        📂 {{ __('messages.expand_all') }}
                    </button>
                    <button type="button" class="btn-collapse-all" onclick="collapseAllGroups()">
                        📁 {{ __('messages.collapse_all') }}
                    </button>
                </div>

                @foreach($permissions as $group => $groupPermissions)
                <div class="permission-group">
                    <div class="permission-group-header" onclick="toggleCollapse('{{ $group }}')">
                        <div class="permission-group-title">
                            <span class="collapse-icon" id="icon-{{ $group }}">▶</span>
                            <span>📁 {{ ucfirst($group) }}</span>
                            <span class="permission-group-count">{{ $groupPermissions->count() }}</span>
                        </div>
                        <div class="select-all-group" onclick="event.stopPropagation()">
                            <input type="checkbox"
                                   id="select_all_{{ $group }}"
                                   onclick="toggleGroup('{{ $group }}')">
                            <label for="select_all_{{ $group }}">
                                {{ __('messages.select_all') }}
                            </label>
                        </div>
                    </div>

                    <div class="permission-group-content" id="content-{{ $group }}">
                        @foreach($groupPermissions as $permission)
                        <div class="permission-checkbox">
                            <input type="checkbox"
                                   id="permission_{{ $permission->id }}"
                                   name="permissions[]"
                                   value="{{ $permission->id }}"
                                   class="permission-checkbox-input permission-{{ $group }}"
                                   onchange="updateCounter()"
                                   {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                            <label for="permission_{{ $permission->id }}">
                                <strong>{{ $permission->display_name }}</strong>
                                @if($permission->description)
                                <div class="permission-description">{{ $permission->description }}</div>
                                @endif
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    ✅ {{ __('messages.create') }}
                </button>
                <a href="{{ route('admin.roles.index') }}" class="btn-cancel">
                    ❌ {{ __('messages.cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// طي وإظهار المجموعة
function toggleCollapse(group) {
    const content = document.getElementById('content-' + group);
    const icon = document.getElementById('icon-' + group);
    const header = content.previousElementSibling;

    if (content.classList.contains('show')) {
        content.classList.remove('show');
        icon.classList.remove('rotated');
        icon.textContent = '▶';
        header.classList.remove('active');
    } else {
        content.classList.add('show');
        icon.classList.add('rotated');
        icon.textContent = '▼';
        header.classList.add('active');
    }
}

// فتح جميع المجموعات
function expandAllGroups() {
    @foreach($permissions as $group => $groupPermissions)
    const content{{ $loop->index }} = document.getElementById('content-{{ $group }}');
    const icon{{ $loop->index }} = document.getElementById('icon-{{ $group }}');
    const header{{ $loop->index }} = content{{ $loop->index }}.previousElementSibling;

    content{{ $loop->index }}.classList.add('show');
    icon{{ $loop->index }}.classList.add('rotated');
    icon{{ $loop->index }}.textContent = '▼';
    header{{ $loop->index }}.classList.add('active');
    @endforeach
}

// إغلاق جميع المجموعات
function collapseAllGroups() {
    @foreach($permissions as $group => $groupPermissions)
    const content{{ $loop->index }} = document.getElementById('content-{{ $group }}');
    const icon{{ $loop->index }} = document.getElementById('icon-{{ $group }}');
    const header{{ $loop->index }} = content{{ $loop->index }}.previousElementSibling;

    content{{ $loop->index }}.classList.remove('show');
    icon{{ $loop->index }}.classList.remove('rotated');
    icon{{ $loop->index }}.textContent = '▶';
    header{{ $loop->index }}.classList.remove('active');
    @endforeach
}

// تحديد جميع الصلاحيات
function selectAllPermissions() {
    const checkboxes = document.querySelectorAll('.permission-checkbox-input');
    checkboxes.forEach(function(checkbox) {
        checkbox.checked = true;
    });

    @foreach($permissions as $group => $groupPermissions)
    updateSelectAll('{{ $group }}');
    @endforeach

    updateCounter();
}

// إلغاء تحديد جميع الصلاحيات
function deselectAllPermissions() {
    const checkboxes = document.querySelectorAll('.permission-checkbox-input');
    checkboxes.forEach(function(checkbox) {
        checkbox.checked = false;
    });

    @foreach($permissions as $group => $groupPermissions)
    updateSelectAll('{{ $group }}');
    @endforeach

    updateCounter();
}

// تحديد/إلغاء تحديد مجموعة
function toggleGroup(group) {
    const checkbox = document.getElementById('select_all_' + group);
    const groupCheckboxes = document.querySelectorAll('.permission-' + group);

    groupCheckboxes.forEach(function(cb) {
        cb.checked = checkbox.checked;
    });

    updateCounter();
}

// تحديث العداد
function updateCounter() {
    const total = document.querySelectorAll('.permission-checkbox-input').length;
    const selected = document.querySelectorAll('.permission-checkbox-input:checked').length;

    document.getElementById('selected-count').textContent = selected;
    document.getElementById('total-count').textContent = total;

    @foreach($permissions as $group => $groupPermissions)
    updateSelectAll('{{ $group }}');
    @endforeach
}

// تحديث حالة زر "تحديد الكل" للمجموعة
function updateSelectAll(group) {
    const selectAllCheckbox = document.getElementById('select_all_' + group);
    const groupCheckboxes = document.querySelectorAll('.permission-' + group);
    const checkedCount = document.querySelectorAll('.permission-' + group + ':checked').length;

    if (selectAllCheckbox) {
        selectAllCheckbox.checked = checkedCount === groupCheckboxes.length;
        selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < groupCheckboxes.length;
    }
}

// تحديث العداد عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    updateCounter();
    // فتح المجموعة الأولى افتراضياً
    @if($permissions->isNotEmpty())
    toggleCollapse('{{ $permissions->keys()->first() }}');
    @endif
});
</script>
@endsection
