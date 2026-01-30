@extends('dashboard.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <h3 class="fw-bold text-dark mb-4"><i class="bi bi-sliders me-2"></i> Business Settings</h3>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf

                        <h5 class="fw-bold text-primary mb-3">General Configuration</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="form-check form-switch p-3 bg-light rounded-3 border">
                                    <input class="form-check-input" type="checkbox" role="switch" id="require_supplier"
                                        name="require_supplier" @checked($config['require_supplier'] ?? false)>
                                    <label class="form-check-label fw-bold" for="require_supplier">Require Supplier
                                        Info</label>
                                    <div class="text-muted small mt-1">Force users to select a supplier when adding new
                                        stock.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch p-3 bg-light rounded-3 border">
                                    <input class="form-check-input" type="checkbox" role="switch" id="require_expiry_date"
                                        name="require_expiry_date" @checked($config['require_expiry_date'] ?? false)>
                                    <label class="form-check-label fw-bold" for="require_expiry_date">Require Expiry
                                        Date</label>
                                    <div class="text-muted small mt-1">Mandatory expiry date field for medicines.</div>
                                </div>
                            </div>
                        </div>

                        <h5 class="fw-bold text-primary mb-3">Feature Management</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <div class="form-check form-switch p-3 bg-light rounded-3 border h-100">
                                    <input class="form-check-input" type="checkbox" role="switch" id="receipt_printing"
                                        name="receipt_printing" @checked($config['receipt_printing'] ?? false)>
                                    <label class="form-check-label fw-bold" for="receipt_printing">Receipt Printing</label>
                                    <div class="text-muted small mt-1">Enable print options after sales.</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch p-3 bg-light rounded-3 border h-100">
                                    <input class="form-check-input" type="checkbox" role="switch" id="sales_notebook"
                                        name="sales_notebook" @checked($config['sales_notebook'] ?? false)>
                                    <label class="form-check-label fw-bold" for="sales_notebook">Sales Notebook</label>
                                    <div class="text-muted small mt-1">Enable simple credit sales/debt recording.</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch p-3 bg-light rounded-3 border h-100">
                                    <input class="form-check-input" type="checkbox" role="switch" id="stock_transfers"
                                        name="stock_transfers" @checked($config['stock_transfers'] ?? false)>
                                    <label class="form-check-label fw-bold" for="stock_transfers">Stock Transfers</label>
                                    <div class="text-muted small mt-1">Allow moving stock between branches.</div>
                                </div>
                            </div>
                        </div>

                        <h5 class="fw-bold text-primary mb-3">Inventory</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Low Stock Alert Level</label>
                                <input type="number" class="form-control" name="low_stock_alert_level"
                                    value="{{ $config['low_stock_alert_level'] ?? 10 }}">
                                <div class="form-text">Notify when item quantity falls below this number.</div>
                            </div>
                        </div>

                        <h5 class="fw-bold text-primary mb-3">Notifications</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="email_notification"
                                        name="email_notification" @checked($config['email_notification'] ?? false)>
                                    <label class="form-check-label" for="email_notification">Email</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="sms_notification"
                                        name="sms_notification" @checked($config['sms_notification'] ?? false)>
                                    <label class="form-check-label" for="sms_notification">SMS</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                        id="whatsapp_notification" name="whatsapp_notification"
                                        @checked($config['whatsapp_notification'] ?? false)>
                                    <label class="form-check-label" for="whatsapp_notification">WhatsApp</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                        id="in_app_notification" name="in_app_notification" @checked($config['in_app_notification'] ?? false)>
                                    <label class="form-check-label" for="in_app_notification">In-App</label>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 shadow fw-bold">
                                <i class="bi bi-save me-2"></i> Save Settings
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
