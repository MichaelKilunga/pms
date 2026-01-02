@extends("layouts.app")

@section("content")
    <div class="row">
        <div class="col-md-12">
            <div class="card card-custom">
                <div class="card-header">
                    <h3 class="card-title">Global Notification Settings</h3>
                </div>
                <form action="{{ route("superAdmin.notifications.update") }}" method="POST">
                    @csrf
                    <div class="card-body">
                        @if (session("success"))
                            <div class="alert alert-success">{{ session("success") }}</div>
                        @endif

                        <div class="row">
                            <!-- WhatsApp Configuration -->
                            <div class="col-md-6">
                                <h4 class="text-primary mb-4">WhatsApp Configuration (Meta Cloud API)</h4>

                                <div class="form-group mb-3">
                                    <div class="custom-control custom-switch">
                                        <input
                                            {{ filter_var($settings["whatsapp_enabled"] ?? false, FILTER_VALIDATE_BOOLEAN) ? "checked" : "" }}
                                            class="custom-control-input" id="whatsapp_enabled" name="whatsapp_enabled"
                                            type="checkbox" value="true">
                                        <label class="custom-control-label" for="whatsapp_enabled">Enable WhatsApp
                                            Integration</label>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="whatsapp_phone_number_id">Phone Number ID</label>
                                    <input class="form-control" name="whatsapp_phone_number_id"
                                        placeholder="Enter Phone Number ID" type="text"
                                        value="{{ $settings["whatsapp_phone_number_id"] ?? "" }}">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="whatsapp_business_account_id">Business Account ID</label>
                                    <input class="form-control" name="whatsapp_business_account_id"
                                        placeholder="Enter Business Account ID" type="text"
                                        value="{{ $settings["whatsapp_business_account_id"] ?? "" }}">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="whatsapp_access_token">Access Token</label>
                                    <input class="form-control" name="whatsapp_access_token"
                                        placeholder="Enter Permanent Access Token" type="password"
                                        value="{{ $settings["whatsapp_access_token"] ?? "" }}">
                                </div>
                            </div>

                            <!-- SMS Configuration -->
                            <div class="col-md-6">
                                <h4 class="text-primary mb-4">SMS Configuration (Skypush)</h4>

                                <div class="form-group mb-3">
                                    <div class="custom-control custom-switch">
                                        <input
                                            {{ filter_var($settings["sms_enabled"] ?? false, FILTER_VALIDATE_BOOLEAN) ? "checked" : "" }}
                                            class="custom-control-input" id="sms_enabled" name="sms_enabled" type="checkbox"
                                            value="true">
                                        <label class="custom-control-label" for="sms_enabled">Enable SMS Integration</label>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="sms_sender_id">Sender ID</label>
                                    <input class="form-control" name="sms_sender_id" placeholder="e.g. PILPOINTONE"
                                        type="text" value="{{ $settings["sms_sender_id"] ?? "" }}">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="sms_api_key">API Key</label>
                                    <input class="form-control" name="sms_api_key" placeholder="Enter Skypush API Key"
                                        type="password" value="{{ $settings["sms_api_key"] ?? "" }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
