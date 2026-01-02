@extends("layouts.app")

@section("content")
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">System Configuration</h5>
                    </div>
                    <div class="card-body">
                        @if (session("success"))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session("success") }}
                                <button aria-label="Close" class="btn-close" data-bs-dismiss="alert" type="button"></button>
                            </div>
                        @endif

                        <form action="{{ route("admin.settings.system.update") }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <h6 class="text-primary border-bottom pb-2">Pricing Module</h6>

                                @foreach ($settings as $setting)
                                    <div class="mb-3">
                                        <label class="form-label text-capitalize" for="{{ $setting->key }}">
                                            {{ str_replace("_", " ", $setting->key) }}
                                        </label>

                                        @if ($setting->key === "pricing_mode")
                                            <select class="form-select" id="{{ $setting->key }}" name="{{ $setting->key }}">
                                                <option {{ $setting->value === "standard" ? "selected" : "" }}
                                                    value="standard">Standard (Package Based)</option>
                                                <option {{ $setting->value === "dynamic" ? "selected" : "" }}
                                                    value="dynamic">Dynamic (Item Based)</option>
                                            </select>
                                        @elseif($setting->key === "profit_share_percentage")
                                            <div class="input-group">
                                                <input class="form-control" id="{{ $setting->key }}"
                                                    name="{{ $setting->key }}" step="0.01" type="number"
                                                    value="{{ $setting->value }}">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        @else
                                            <input class="form-control" id="{{ $setting->key }}"
                                                name="{{ $setting->key }}" type="text" value="{{ $setting->value }}">
                                        @endif
                                        @if ($setting->description)
                                            <div class="form-text text-muted">{{ $setting->description }}</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <div class="mb-4">
                                <h6 class="text-primary border-bottom pb-2">WhatsApp Configuration (Meta Cloud API)</h6>

                                <div class="mb-3">
                                    <label class="form-label" for="whatsapp_enabled">Enable WhatsApp Integration</label>
                                    <select class="form-select" id="whatsapp_enabled" name="whatsapp_enabled">
                                        <option
                                            {{ ($settings["whatsapp_enabled"]["value"] ?? "false") === "false" ? "selected" : "" }}
                                            value="false">Disabled</option>
                                        <option
                                            {{ ($settings["whatsapp_enabled"]["value"] ?? "false") === "true" ? "selected" : "" }}
                                            value="true">Enabled</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="whatsapp_phone_number_id">Phone Number ID</label>
                                    <input class="form-control" id="whatsapp_phone_number_id"
                                        name="whatsapp_phone_number_id" type="text"
                                        value="{{ $settings["whatsapp_phone_number_id"]["value"] ?? "" }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="whatsapp_business_account_id">Business Account ID</label>
                                    <input class="form-control" id="whatsapp_business_account_id"
                                        name="whatsapp_business_account_id" type="text"
                                        value="{{ $settings["whatsapp_business_account_id"]["value"] ?? "" }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="whatsapp_access_token">Access Token</label>
                                    <input class="form-control" id="whatsapp_access_token" name="whatsapp_access_token"
                                        type="password" value="{{ $settings["whatsapp_access_token"]["value"] ?? "" }}">
                                    <div class="form-text">Permanent user access token or system user token recommended.
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="bi bi-save me-1"></i> Save Changes
                                    </button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
