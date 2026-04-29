@extends('layouts.master')

@section('content')
<style>
    /* ============================================
       ORG CHART - CORRECT HIERARCHY
       CEO -> Managers -> Employees
    ============================================ */
    .org-chart-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        overflow-x: auto;
        padding: 1rem;
    }

    /* CEO Section */
    .org-ceo-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 1rem;
    }

    /* Line from CEO to horizontal bar */
    .ceo-connector {
        width: 2px;
        height: 40px;
        background: #cbd5e1;
        margin: 0.5rem auto;
    }

    /* Horizontal bar above managers */
    .managers-horizontal-bar {
        position: relative;
        width: 80%;
        height: 2px;
        background: #cbd5e1;
        margin: 0 auto 2rem;
    }

    /* Managers Container */
    .org-managers-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 2rem;
        position: relative;
        width: 100%;
    }

    /* Single Manager Unit (Card + Employees under it) */
    .manager-unit {
        display: flex;
        flex-direction: column;
        align-items: center;
        min-width: 280px;
        position: relative;
    }

    /* Vertical line from horizontal bar to each manager card */
    .manager-unit::before {
        content: '';
        position: absolute;
        top: -32px;
        left: 50%;
        width: 2px;
        height: 32px;
        background: #cbd5e1;
        transform: translateX(-50%);
        z-index: 1;
    }

    /* Manager Card */
    .manager-card, .ceo-card {
        background: #ffffff;
        border-radius: 1rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
        width: 260px;
        cursor: pointer;
        position: relative;
        z-index: 10;
    }

    .dark .manager-card, .dark .ceo-card {
        background: #1e293b;
        border-color: #334155;
    }

    .manager-card:hover, .ceo-card:hover {
        transform: translateY(-2px);
        border-color: #6366f1;
        box-shadow: 0 20px 30px -12px rgba(0, 0, 0, 0.15);
    }

    .card-header {
        padding: 1.25rem;
        text-align: center;
        border-bottom: 1px solid #e2e8f0;
    }

    .dark .card-header {
        border-bottom-color: #334155;
    }

    .avatar-wrapper {
        width: 70px;
        height: 70px;
        margin: 0 auto 0.75rem;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.75rem;
        font-weight: 600;
        overflow: hidden;
    }

    .avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .manager-name {
        font-size: 1rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.25rem;
    }

    .dark .manager-name {
        color: #f1f5f9;
    }

    .manager-role {
        font-size: 0.65rem;
        color: #6366f1;
        font-weight: 600;
        background: #eef2ff;
        display: inline-block;
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
    }

    .dark .manager-role {
        background: #334155;
        color: #a5b4fc;
    }

    .manager-dept {
        font-size: 0.7rem;
        color: #64748b;
        margin-top: 0.25rem;
    }

    .dark .manager-dept {
        color: #94a3b8;
    }

    .card-body {
        padding: 0.75rem 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .employee-count {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #f1f5f9;
        padding: 0.3rem 0.9rem;
        border-radius: 9999px;
        font-size: 0.8rem;
        font-weight: 500;
        color: #334155;
    }

    .dark .employee-count {
        background: #334155;
        color: #e2e8f0;
    }

    /* Toggle icon */
    .toggle-icon {
        transition: transform 0.3s ease;
        cursor: pointer;
    }

    .toggle-icon.rotated {
        transform: rotate(90deg);
    }

    /* Employees List Under Manager */
    .employees-under-manager {
        margin-top: 1rem;
        width: 100%;
        display: none;
        flex-direction: column;
        gap: 0.5rem;
        animation: fadeIn 0.3s ease;
    }

    .employees-under-manager.show {
        display: flex;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .employee-subcard {
        background: #f8fafc;
        border-radius: 0.75rem;
        padding: 0.6rem 0.8rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.2s;
        cursor: pointer;
        border: 1px solid #e2e8f0;
    }

    .dark .employee-subcard {
        background: #0f172a;
        border-color: #334155;
    }

    .employee-subcard:hover {
        background: #f1f5f9;
        transform: translateX(4px);
    }

    .dark .employee-subcard:hover {
        background: #1e293b;
    }

    .employee-small-avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        flex-shrink: 0;
        overflow: hidden;
    }

    .employee-subcard .info {
        flex: 1;
    }

    .employee-subcard .info h6 {
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 0.1rem;
    }

    .employee-subcard .info p {
        font-size: 0.7rem;
        color: #64748b;
        margin: 0;
    }

    .dark .employee-subcard .info p {
        color: #94a3b8;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
    }

    /* No employees message */
    .no-employees-msg {
        background: #f8fafc;
        border-radius: 0.75rem;
        padding: 0.75rem;
        text-align: center;
        color: #64748b;
        font-size: 0.8rem;
        border: 1px dashed #cbd5e1;
    }

    .dark .no-employees-msg {
        background: #0f172a;
        border-color: #334155;
        color: #94a3b8;
    }

    /* الكارد الأفقي */
    .card-content {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
    }

    .avatar-wrapper {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .card-info {
        flex: 1;
        min-width: 0;
    }

    .manager-name {
        font-size: 1rem;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .manager-role {
        font-size: 0.65rem;
        display: inline-block;
        padding: 0.15rem 0.5rem;
        border-radius: 20px;
    }

    .manager-dept {
        font-size: 0.7rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* فوتر الكارد */
    .card-footer {
        padding: 0.6rem 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid #e2e8f0;
    }

    /* أزرار التعديل والحذف */
    .card-actions {
        position: absolute;
        top: 8px;
        right: 8px;
        display: flex;
        gap: 4px;
        z-index: 20;
    }

    .action-btn {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        background: #ffffff;
        border: 1px solid #e2e8f0;
    }

</style>

<!-- Page-content -->
<div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4">
    
    <div class="container-fluid">
        <!-- Header -->
        <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
            <div class="grow">
                <h5 class="text-16">{{ __('messages.departments_list') }} - الهيكل التنظيمي</h5>
            </div>
            <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                <li class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1 before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400">
                    <a href="#!" class="text-slate-400">{{ __('messages.hr_management') }}</a>
                </li>
                <li class="text-slate-700 dark:text-zink-100">الهيكل التنظيمي</li>
            </ul>
        </div>

        <!-- Add Department Button -->
        <div class="flex flex-wrap items-center justify-end gap-3 mb-6">
            <button data-modal-target="addDepartmentModal" type="button"
                class="text-white btn bg-custom-500 border-custom-500 hover:bg-custom-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="inline-block size-4">
                    <path d="M5 12h14"></path>
                    <path d="M12 5v14"></path>
                </svg>
                <span class="align-middle">{{ __('messages.add_department') }}</span>
            </button>
        </div>

        <!-- Org Chart -->
        <div class="card">
            <div class="card-body p-6">
                <div class="org-chart-wrapper">
                    
                    <!-- ==================== CEO SECTION (TOP) ==================== -->
                    @if($ceoData && !empty($ceoData))
                    <div class="org-ceo-section">
                        <div class="ceo-card">
                            <!-- أزرار التعديل والحذف -->
                            <!-- <div class="card-actions">
                                <div class="action-btn edit-btn" onclick="event.stopPropagation(); openEditModal('{{ $ceoData['id'] ?? '' }}', '{{ addslashes($ceoData['department'] ?? '') }}', '{{ addslashes($ceoData['name']) }}', '{{ $ceoData['phone'] ?? '' }}', '{{ $ceoData['email'] ?? '' }}')">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3l4 4-7 7H10v-4l7-7z"/><path d="M4 20h16"/></svg>
                                </div>
                                <div class="action-btn delete-btn" onclick="event.stopPropagation(); openDeleteModal('{{ $ceoData['id'] ?? '' }}')">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M5 7l1 13a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-13"/><path d="M9 4h6"/></svg>
                                </div>
                            </div> -->
                            
                            <!-- محتوى الكارد - صورة جمب التفاصيل -->
                            <div class="card-content">
                                <div class="avatar-wrapper">
                                    @if($ceoData['avatar'])
                                        <img src="{{ asset('assets/images/user/' . $ceoData['avatar']) }}" alt="{{ $ceoData['name'] }}" class="avatar-img">
                                    @else
                                        <span>{{ substr($ceoData['name'], 0, 1) }}</span>
                                    @endif
                                </div>
                                <div class="card-info">
                                    <h4 class="manager-name">{{ $ceoData['name'] }}</h4>
                                    <span class="manager-role">{{ $ceoData['role'] ?? 'CEO' }}</span>
                                    <!-- <div class="manager-dept">{{ $ceoData['department'] }}</div> -->
                                </div>
                            </div>
                            
                            <!-- الفوتر بتاع الكارد -->
                            <!-- <div class="card-footer">
                                <div class="employee-count">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    <span>{{ count($managersData) }} {{ __('messages.total_employee') }}</span>
                                </div>
                            </div> -->
                        </div>
                    </div>

                    <!-- الخط النازل من الـ CEO -->
                    <div class="ceo-connector"></div>

                    <!-- الخط الأفقي قبل المدراء (لو فيه مدراء) -->
                    @if(count($managersData) > 0)
                    <div class="managers-horizontal-bar"></div>
                    @endif
                    @endif

                    <!-- ==================== MANAGERS SECTION ==================== -->
                    @if(count($managersData) > 0)
                    <div class="org-managers-container">
                        @foreach($managersData as $index => $manager)
                        <div class="manager-unit" data-manager="{{ $index }}">
                            
                            <!-- كارد المدير -->
                            <div class="manager-card" data-manager-id="{{ $index }}">
                                <!-- أزرار التعديل والحذف -->
                                <!-- <div class="card-actions">
                                    <div class="action-btn edit-btn" onclick="event.stopPropagation(); openEditModal('{{ $manager['id'] ?? '' }}', '{{ addslashes($manager['department'] ?? '') }}', '{{ addslashes($manager['name']) }}', '{{ $manager['phone'] ?? '' }}', '{{ $manager['email'] ?? '' }}')">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3l4 4-7 7H10v-4l7-7z"/><path d="M4 20h16"/></svg>
                                    </div>
                                    <div class="action-btn delete-btn" onclick="event.stopPropagation(); openDeleteModal('{{ $manager['id'] ?? '' }}')">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M5 7l1 13a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-13"/><path d="M9 4h6"/></svg>
                                    </div>
                                </div> -->
                                
                                <!-- محتوى الكارد - صورة جمب التفاصيل -->
                                <div class="card-content">
                                    <div class="avatar-wrapper">
                                        @if($manager['avatar'])
                                            <img src="{{ asset('assets/images/user/' . $manager['avatar']) }}" alt="{{ $manager['name'] }}" class="avatar-img">
                                        @else
                                            <span>{{ substr($manager['name'], 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <div class="card-info">
                                        <h4 class="manager-name">{{ $manager['name'] }}</h4>
                                        <span class="manager-role">Manager</span>
                                        <div class="manager-dept">{{ $manager['department'] }}</div>
                                    </div>
                                </div>
                                
                                <!-- فوتر الكارد - عدد الموظفين والسهم -->
                                <div class="card-footer">
                                    <div class="employee-count">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                        <span class="emp-count-number">{{ $manager['total_employees'] }} {{ __('messages.total_employee') }}</span>
                                    </div>
                                    @if($manager['total_employees'] > 0)
                                    <div class="toggle-icon">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- قائمة الموظفين تحت المدير -->
                            <div class="employees-under-manager">
                                @if($manager['total_employees'] > 0)
                                    @foreach($manager['employees'] as $emp)
                                    <div class="employee-subcard" onclick="event.stopPropagation(); window.location.href='{{ url('page/account') }}/{{ $emp['id'] }}'">
                                        <div class="employee-small-avatar">
                                            @if($emp['avatar'])
                                                <img src="{{ asset('assets/images/user/' . $emp['avatar']) }}" class="w-full h-full object-cover">
                                            @else
                                                <span>{{ substr($emp['name'], 0, 1) }}</span>
                                            @endif
                                        </div>
                                        <div class="info">
                                            <h6 class="dark:text-zink-100">{{ $emp['name'] }}</h6>
                                            <p>{{ $emp['position'] ?? 'Employee' }} • {{ $emp['id'] }}</p>
                                        </div>
                                        <svg class="employee-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="no-employees-msg">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="inline mr-1"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                        لا يوجد موظفين في هذا القسم
                                    </div>
                                @endif
                            </div>
                            
                        </div>
                        @endforeach
                    </div>
                    @elseif(!$ceoData)
                    <div class="empty-state">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mx-auto text-slate-400 mb-3"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <p class="text-slate-500">لا يوجد أقسام</p>
                        <p class="text-sm text-slate-400 mt-2">أضف مدير أولاً لعرض الهيكل التنظيمي</p>
                    </div>
                    @else
                    <div class="empty-state">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mx-auto text-slate-400 mb-3"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <p class="text-slate-500">لا يوجد مدراء</p>
                        <p class="text-sm text-slate-400 mt-2">قم بإضافة موظفين بدور Manager</p>
                    </div>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- ========================================== -->
<!-- DEPARTMENT CRUD MODALS -->
<!-- ========================================== -->

<!-- Add Department Modal -->
<!-- <div id="addDepartmentModal" modal-center=""
    class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
    <div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600">
        <div class="flex items-center justify-between p-4 border-b dark:border-zink-500">
            <h5 class="text-16 dark:text-zink-100">{{ __('messages.add_department') }}</h5>
            <button type="button" data-modal-close="addDepartmentModal"
                class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <div class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
            <form action="{{ route('hr/department/save') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="inline-block mb-2 text-base font-medium">{{ __('messages.department_name') }}</label>
                        <input type="text" name="department" id="departmentInput"
                            class="form-input border-slate-200 dark:border-zink-500 dark:bg-zink-700 dark:text-zink-100 w-full rounded-md"
                            placeholder="{{ __('messages.enter_department_name') }}" required>
                    </div>
                    <div>
                        <label class="inline-block mb-2 text-base font-medium">{{ __('messages.head_of_department') }}</label>
                        <select name="head_of" id="headOfInput"
                            class="form-select border-slate-200 dark:border-zink-500 dark:bg-zink-700 dark:text-zink-100 w-full rounded-md"
                            required>
                            <option value="">{{ __('messages.select_manager') }}</option>
                            @foreach ($allManagers as $manager)
                                <option value="{{ $manager->name }}">{{ $manager->name }} ({{ $manager->user_id }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="inline-block mb-2 text-base font-medium">{{ __('messages.phone_number') }}</label>
                        <input type="text" name="phone_number" id="phoneNumberInput"
                            class="form-input border-slate-200 dark:border-zink-500 dark:bg-zink-700 dark:text-zink-100 w-full rounded-md"
                            placeholder="{{ __('messages.enter_phone') }}" required>
                    </div>
                    <div>
                        <label class="inline-block mb-2 text-base font-medium">{{ __('messages.email') }}</label>
                        <input type="email" name="email" id="emailInput"
                            class="form-input border-slate-200 dark:border-zink-500 dark:bg-zink-700 dark:text-zink-100 w-full rounded-md"
                            placeholder="{{ __('messages.enter_email') }}" required>
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" data-modal-close="addDepartmentModal"
                        class="text-red-500 bg-white btn">{{ __('messages.cancel') }}</button>
                    <button type="submit"
                        class="text-white btn bg-custom-500">{{ __('messages.add') }}</button>
                </div>
            </form>
        </div>
    </div>
</div> -->
<!-- Add Department Modal -->
<div id="addDepartmentModal" modal-center=""
    class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
    <div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600">
        <div class="flex items-center justify-between p-4 border-b dark:border-zink-500">
            <h5 class="text-16 dark:text-zink-100">{{ __('messages.add_department') }}</h5>
            <button type="button" onclick="closeModal('addDepartmentModal')"
                class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <div class="max-h-[calc(100vh_-_180px)] p-4 overflow-y-auto">
            <form action="{{ route('hr/department/save') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-4">
                    <!-- اسم القسم - Required -->
                    <div>
                        <label class="inline-block mb-2 text-base font-medium dark:text-zink-100">
                            {{ __('messages.department_name') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="department" id="departmentInput"
                            class="form-input border-slate-200 dark:border-zink-500 dark:bg-zink-700 dark:text-zink-100 w-full rounded-md"
                            placeholder="{{ __('messages.enter_department_name') }}" required>
                    </div>
                    
                    <!-- رئيس القسم - Optional -->
                    <div>
                        <label class="inline-block mb-2 text-base font-medium dark:text-zink-100">
                            {{ __('messages.head_of_department') }} <span class="text-gray-400 text-xs">(اختياري)</span>
                        </label>
                        <select name="head_of" id="headOfInput"
                            class="form-select border-slate-200 dark:border-zink-500 dark:bg-zink-700 dark:text-zink-100 w-full rounded-md">
                            <option value="">-- اختر مدير القسم (اختياري) --</option>
                            @foreach ($allManagers as $manager)
                                <option value="{{ $manager->name }}">{{ $manager->name }} ({{ $manager->user_id }})</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-400 mt-1">يمكنك إضافة رئيس القسم لاحقاً من خلال التعديل</p>
                    </div>
                    
                    <!-- رقم الهاتف - Optional -->
                    <div>
                        <label class="inline-block mb-2 text-base font-medium dark:text-zink-100">
                            {{ __('messages.phone_number') }} <span class="text-gray-400 text-xs">(اختياري)</span>
                        </label>
                        <input type="text" name="phone_number" id="phoneNumberInput"
                            class="form-input border-slate-200 dark:border-zink-500 dark:bg-zink-700 dark:text-zink-100 w-full rounded-md"
                            placeholder="{{ __('messages.enter_phone') }}">
                    </div>
                    
                    <!-- البريد الإلكتروني - Optional -->
                    <div>
                        <label class="inline-block mb-2 text-base font-medium dark:text-zink-100">
                            {{ __('messages.email') }} <span class="text-gray-400 text-xs">(اختياري)</span>
                        </label>
                        <input type="email" name="email" id="emailInput"
                            class="form-input border-slate-200 dark:border-zink-500 dark:bg-zink-700 dark:text-zink-100 w-full rounded-md"
                            placeholder="{{ __('messages.enter_email') }}">
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" onclick="closeModal('addDepartmentModal')"
                        class="px-4 py-2 border rounded-md hover:bg-slate-100 dark:border-zink-500 dark:text-zink-100">{{ __('messages.cancel') }}</button>
                    <button type="submit"
                        class="px-4 py-2 bg-custom-500 text-white rounded-md hover:bg-custom-600">{{ __('messages.add') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Department Modal -->
<div id="editDepartmentModal" modal-center=""
    class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
    <div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600">
        <div class="flex items-center justify-between p-4 border-b dark:border-zink-500">
            <h5 class="text-16 dark:text-zink-100">{{ __('messages.edit_department') }}</h5>
            <button type="button" data-modal-close="editDepartmentModal"
                class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <div class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
            <form action="{{ route('hr/department/save') }}" method="POST">
                @csrf
                <input type="hidden" name="id_update" id="e_id_update">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="inline-block mb-2 text-base font-medium">{{ __('messages.department_name') }}</label>
                        <input type="text" name="department" id="e_department_input"
                            class="form-input border-slate-200 dark:border-zink-500 dark:bg-zink-700 dark:text-zink-100 w-full rounded-md" required>
                    </div>
                    <div>
                        <label class="inline-block mb-2 text-base font-medium">{{ __('messages.head_of_department') }}</label>
                        <select name="head_of" id="e_head_of_input"
                            class="form-select border-slate-200 dark:border-zink-500 dark:bg-zink-700 dark:text-zink-100 w-full rounded-md" required>
                            <option value="">{{ __('messages.select_manager') }}</option>
                            @foreach ($allManagers as $manager)
                                <option value="{{ $manager->name }}">{{ $manager->name }} ({{ $manager->user_id }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="inline-block mb-2 text-base font-medium">{{ __('messages.phone_number') }}</label>
                        <input type="text" name="phone_number" id="e_phone_number_input"
                            class="form-input border-slate-200 dark:border-zink-500 dark:bg-zink-700 dark:text-zink-100 w-full rounded-md" required>
                    </div>
                    <div>
                        <label class="inline-block mb-2 text-base font-medium">{{ __('messages.email') }}</label>
                        <input type="email" name="email" id="e_email_input"
                            class="form-input border-slate-200 dark:border-zink-500 dark:bg-zink-700 dark:text-zink-100 w-full rounded-md" required>
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" data-modal-close="editDepartmentModal"
                        class="text-red-500 bg-white btn">{{ __('messages.cancel') }}</button>
                    <button type="submit"
                        class="text-white btn bg-custom-500">{{ __('messages.update') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" modal-center=""
    class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
    <div class="w-screen md:w-[25rem] bg-white shadow rounded-md dark:bg-zink-600">
        <div class="max-h-[calc(theme('height.screen')_-_180px)] overflow-y-auto p-6">
            <form action="{{ route('hr/department/delete') }}" method="POST">
                @csrf
                <input type="hidden" name="id_delete" id="e_id_delete">
                <div class="text-center">
                    <h5 class="mb-1 dark:text-zink-100">{{ __('messages.are_you_sure') }}</h5>
                    <p class="text-slate-500 dark:text-zink-200">{{ __('messages.confirm_delete') }}</p>
                    <div class="flex justify-center gap-2 mt-6">
                        <button type="button" data-modal-close="deleteModal"
                            class="bg-white text-slate-500 btn">{{ __('messages.cancel') }}</button>
                        <button type="submit"
                            class="text-white bg-red-500 btn">{{ __('messages.yes_delete') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Toggle employees under manager when clicking on the manager card (card body area only)
        $('.manager-card').on('click', function(e) {
            e.stopPropagation();
            var $managerUnit = $(this).closest('.manager-unit');
            var $employeesContainer = $managerUnit.find('.employees-under-manager');
            var $toggleIcon = $(this).find('.toggle-icon');
            var empCount = parseInt($managerUnit.find('.emp-count-number').text());
            
            if (empCount > 0) {
                $employeesContainer.slideToggle(200);
                $toggleIcon.toggleClass('rotated');
            }
        });
        
        // Also allow clicking on the arrow icon specifically
        $('.toggle-icon').on('click', function(e) {
            e.stopPropagation();
            var $managerUnit = $(this).closest('.manager-unit');
            var $employeesContainer = $managerUnit.find('.employees-under-manager');
            var empCount = parseInt($managerUnit.find('.emp-count-number').text());
            
            if (empCount > 0) {
                $employeesContainer.slideToggle(200);
                $(this).toggleClass('rotated');
            }
        });

        // Close modal buttons
        $('[data-modal-close]').on('click', function() {
            var modalId = $(this).data('modal-close');
            $('#' + modalId).addClass('hidden');
        });
        
        // Edit button handler (if exists from old table view)
        $(document).on('click', '.update-record-btn', function() {
            var row = $(this).closest('tr');
            $('#e_id_update').val(row.find('.id_update').text());
            $('#e_department_input').val(row.find('.department-name').text());
            $('#e_head_of_input').val(row.find('.head-of-name').text());
            $('#e_phone_number_input').val(row.find('.phone-number').text());
            $('#e_email_input').val(row.find('.email-address').text());
            $('#editDepartmentModal').removeClass('hidden');
        });
        
        // Delete button handler
        $(document).on('click', '.delete-record-btn', function() {
            var row = $(this).closest('tr');
            $('#e_id_delete').val(row.find('.id_update').text());
            $('#deleteModal').removeClass('hidden');
        });

        // Keep modals open on validation errors
        @if (session('open_modal_add') || $errors->hasBag('create') || ($errors->any() && !old('id_update')))
            $('#addDepartmentModal').removeClass('hidden');
        @endif

        @if (session('open_modal_edit') || $errors->hasBag('update') || (old('id_update') && $errors->any()))
            $('#editDepartmentModal').removeClass('hidden');
        @endif
    });
</script>
@endsection
