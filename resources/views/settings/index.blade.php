@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="{ activeTab: '{{ request('tab', 'business') }}' }">
    <div>
        <h1 class="text-xl font-bold text-heading">Settings</h1>
        <p class="text-muted mt-1">Configure your system preferences</p>
    </div>

    <!-- Tabs -->
    <div class="border-b border-border">
        <nav class="flex gap-1 -mb-px">
            <button @click="activeTab = 'business'" :class="activeTab === 'business' ? 'border-accent text-accent' : 'border-transparent text-muted hover:text-body'" class="px-4 py-3 text-sm font-medium border-b-2 transition-colors">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>
                    Business
                </span>
            </button>
            <button @click="activeTab = 'receipt'" :class="activeTab === 'receipt' ? 'border-accent text-accent' : 'border-transparent text-muted hover:text-body'" class="px-4 py-3 text-sm font-medium border-b-2 transition-colors">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                    Receipt
                </span>
            </button>
            <button @click="activeTab = 'system'" :class="activeTab === 'system' ? 'border-accent text-accent' : 'border-transparent text-muted hover:text-body'" class="px-4 py-3 text-sm font-medium border-b-2 transition-colors">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    System
                </span>
            </button>
            <button @click="activeTab = 'email'" :class="activeTab === 'email' ? 'border-accent text-accent' : 'border-transparent text-muted hover:text-body'" class="px-4 py-3 text-sm font-medium border-b-2 transition-colors">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                    Email
                </span>
            </button>
        </nav>
    </div>

    <!-- Business Tab -->
    <div x-show="activeTab === 'business'" x-transition>
        <form action="{{ route('settings.update') }}" method="POST" class="bg-white rounded-lg border border-border p-6 space-y-6">
            @csrf
            <input type="hidden" name="tab" value="business">
            <h2 class="text-lg font-semibold text-heading">Business Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="form-label">Company Name</label>
                    <input type="text" name="settings[business][company_name]" value="{{ old('settings.business.company_name', $settings['company_name'] ?? '') }}">
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">Address</label>
                    <textarea name="settings[business][address]" rows="2">{{ old('settings.business.address', $settings['address'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="form-label">Phone</label>
                    <input type="text" name="settings[business][phone]" value="{{ old('settings.business.phone', $settings['phone'] ?? '') }}">
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="settings[business][email]" value="{{ old('settings.business.email', $settings['email'] ?? '') }}">
                </div>
                <div>
                    <label class="form-label">TIN Number</label>
                    <input type="text" name="settings[business][tin_number]" value="{{ old('settings.business.tin_number', $settings['tin_number'] ?? '') }}">
                </div>
                <div>
                    <label class="form-label">VAT Number</label>
                    <input type="text" name="settings[business][vat_number]" value="{{ old('settings.business.vat_number', $settings['vat_number'] ?? '') }}">
                </div>
                <div>
                    <label class="form-label">Currency</label>
                    <select name="settings[business][currency]">
                        <option value="TZS" {{ ($settings['currency'] ?? 'TZS') === 'TZS' ? 'selected' : '' }}>TZS - Tanzanian Shilling</option>
                        <option value="USD" {{ ($settings['currency'] ?? '') === 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors text-sm font-medium">Save Changes</button>
            </div>
        </form>
    </div>

    <!-- Receipt Tab -->
    <div x-show="activeTab === 'receipt'" x-transition>
        <form action="{{ route('settings.update') }}" method="POST" class="bg-white rounded-lg border border-border p-6 space-y-6">
            @csrf
            <input type="hidden" name="tab" value="receipt">
            <h2 class="text-lg font-semibold text-heading">Receipt Settings</h2>
            <div class="space-y-6">
                <div>
                    <label class="form-label">Header Text</label>
                    <input type="text" name="settings[receipt][receipt_header]" value="{{ old('settings.receipt.receipt_header', $settings['receipt_header'] ?? '') }}" placeholder="Thank you for your purchase!">
                </div>
                <div>
                    <label class="form-label">Footer Text</label>
                    <textarea name="settings[receipt][receipt_footer]" rows="2" placeholder="Returns accepted within 7 days with receipt">{{ old('settings.receipt.receipt_footer', $settings['receipt_footer'] ?? '') }}</textarea>
                </div>
                <div x-data="{ showLogo: {{ ($settings['receipt_show_logo'] ?? true) ? 'true' : 'false' }} }">
                    <div class="flex items-center justify-between p-4 bg-card-bg rounded-lg">
                        <div>
                            <p class="font-medium text-heading">Show Logo on Receipt</p>
                            <p class="text-sm text-muted">Display company logo at the top of receipts</p>
                        </div>
                        <button type="button" @click="showLogo = !showLogo" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors" :class="showLogo ? 'bg-accent-light0' : 'bg-control-bg'">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform" :style="showLogo ? 'transform: translateX(22px)' : 'transform: translateX(2px)'"></span>
                        </button>
                        <input type="hidden" name="settings[receipt][receipt_show_logo]" :value="showLogo ? '1' : '0'">
                    </div>
                </div>
                <div>
                    <label class="form-label">Paper Size</label>
                    <select name="settings[receipt][receipt_paper_size]">
                        <option value="58mm" {{ ($settings['receipt_paper_size'] ?? '80mm') === '58mm' ? 'selected' : '' }}>58mm (Thermal)</option>
                        <option value="80mm" {{ ($settings['receipt_paper_size'] ?? '80mm') === '80mm' ? 'selected' : '' }}>80mm (Thermal)</option>
                        <option value="A4" {{ ($settings['receipt_paper_size'] ?? '') === 'A4' ? 'selected' : '' }}>A4</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors text-sm font-medium">Save Changes</button>
            </div>
        </form>
    </div>

    <!-- System Tab -->
    <div x-show="activeTab === 'system'" x-transition>
        <form action="{{ route('settings.update') }}" method="POST" class="bg-white rounded-lg border border-border p-6 space-y-6">
            @csrf
            <input type="hidden" name="tab" value="system">
            <h2 class="text-lg font-semibold text-heading">System Settings</h2>
            <div class="space-y-6">
                <div>
                    <label class="form-label">Low Stock Threshold (default)</label>
                    <input type="number" name="settings[system][low_stock_threshold]" value="{{ old('settings.system.low_stock_threshold', $settings['low_stock_threshold'] ?? 10) }}" min="0">
                    <p class="text-xs text-muted mt-1">Items at or below this quantity will be flagged as low stock</p>
                </div>
                <div x-data="{ emailNotif: {{ ($settings['enable_email_notifications'] ?? true) ? 'true' : 'false' }} }">
                    <div class="flex items-center justify-between p-4 bg-card-bg rounded-lg">
                        <div>
                            <p class="font-medium text-heading">Email Notifications</p>
                            <p class="text-sm text-muted">Send email alerts for important events</p>
                        </div>
                        <button type="button" @click="emailNotif = !emailNotif" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors" :class="emailNotif ? 'bg-accent-light0' : 'bg-control-bg'">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform" :style="emailNotif ? 'transform: translateX(22px)' : 'transform: translateX(2px)'"></span>
                        </button>
                        <input type="hidden" name="settings[system][enable_email_notifications]" :value="emailNotif ? '1' : '0'">
                    </div>
                </div>
                <div x-data="{ smsNotif: {{ ($settings['enable_sms_notifications'] ?? false) ? 'true' : 'false' }} }">
                    <div class="flex items-center justify-between p-4 bg-card-bg rounded-lg">
                        <div>
                            <p class="font-medium text-heading">SMS Notifications</p>
                            <p class="text-sm text-muted">Send SMS alerts for important events</p>
                        </div>
                        <button type="button" @click="smsNotif = !smsNotif" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors" :class="smsNotif ? 'bg-accent-light0' : 'bg-control-bg'">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform" :style="smsNotif ? 'transform: translateX(22px)' : 'transform: translateX(2px)'"></span>
                        </button>
                        <input type="hidden" name="settings[system][enable_sms_notifications]" :value="smsNotif ? '1' : '0'">
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors text-sm font-medium">Save Changes</button>
            </div>
        </form>
    </div>

    <!-- Email Tab -->
    <div x-show="activeTab === 'email'" x-transition>
        <form action="{{ route('settings.update') }}" method="POST" class="bg-white rounded-lg border border-border p-6 space-y-6">
            @csrf
            <input type="hidden" name="tab" value="email">
            <h2 class="text-lg font-semibold text-heading">Email Notification Recipients</h2>
            <p class="text-sm text-muted">Specify which email addresses receive each notification type (comma-separated for multiple)</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="form-label">Low Stock Alerts</label>
                    <input type="text" name="settings[email][email_low_stock]" value="{{ old('settings.email.email_low_stock', $settings['email_low_stock'] ?? '') }}" placeholder="admin@example.com">
                </div>
                <div>
                    <label class="form-label">Sale Notifications</label>
                    <input type="text" name="settings[email][email_sale]" value="{{ old('settings.email.email_sale', $settings['email_sale'] ?? '') }}" placeholder="sales@example.com">
                </div>
                <div>
                    <label class="form-label">Purchase Notifications</label>
                    <input type="text" name="settings[email][email_purchase]" value="{{ old('settings.email.email_purchase', $settings['email_purchase'] ?? '') }}" placeholder="purchasing@example.com">
                </div>
                <div>
                    <label class="form-label">Expense Notifications</label>
                    <input type="text" name="settings[email][email_expense]" value="{{ old('settings.email.email_expense', $settings['email_expense'] ?? '') }}" placeholder="finance@example.com">
                </div>
                <div>
                    <label class="form-label">Void Transaction Alerts</label>
                    <input type="text" name="settings[email][email_void]" value="{{ old('settings.email.email_void', $settings['email_void'] ?? '') }}" placeholder="admin@example.com">
                </div>
                <div>
                    <label class="form-label">Adjustment Alerts</label>
                    <input type="text" name="settings[email][email_adjustment]" value="{{ old('settings.email.email_adjustment', $settings['email_adjustment'] ?? '') }}" placeholder="admin@example.com">
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors text-sm font-medium">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection