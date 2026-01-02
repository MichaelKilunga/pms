@extends("layouts.app")

@section("content")
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Edit User: {{ $user->name }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route("superadmin.users.update", $user->id) }}" method="POST">
                            @csrf
                            @method("PUT")

                            <div class="mb-3">
                                <label class="form-label" for="name">Name</label>
                                <input class="form-control" id="name" name="name" required type="text"
                                    value="{{ old("name", $user->name) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="email">Email</label>
                                <input class="form-control" id="email" name="email" required type="email"
                                    value="{{ old("email", $user->email) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="role">Role</label>
                                <select class="form-select" id="role" name="role">
                                    <option {{ $user->role == "User" ? "selected" : "" }} value="User">User (Owner)
                                    </option>
                                    <option {{ $user->role == "Admin" ? "selected" : "" }} value="Admin">Admin</option>
                                    <option {{ $user->role == "Superadmin" ? "selected" : "" }} value="Superadmin">
                                        Superadmin</option>
                                    <option {{ $user->role == "Agent" ? "selected" : "" }} value="Agent">Agent</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-primary" for="pricing_mode">Pricing Strategy</label>
                                <select class="form-select" id="pricing_mode" name="pricing_mode">
                                    <option {{ $user->pricing_mode === null ? "selected" : "" }} value="">Default (Use
                                        System Global)</option>
                                    <option {{ $user->pricing_mode === "standard" ? "selected" : "" }} value="standard">
                                        Standard (Pre-defined Packages)</option>
                                    <option {{ $user->pricing_mode === "dynamic" ? "selected" : "" }} value="dynamic">
                                        Dynamic (Item Based Formula)</option>
                                    <option {{ $user->pricing_mode === "profit_share" ? "selected" : "" }}
                                        value="profit_share">Profit Share (Percentage of Item Profit)</option>
                                </select>
                                <div class="form-text text-muted">Override the global pricing strategy for this specific
                                    user.</div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a class="btn btn-secondary me-2" href="{{ route("superadmin.users") }}">Cancel</a>
                                <button class="btn btn-primary" type="submit">Update User</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
