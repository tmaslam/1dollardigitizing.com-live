@extends('layouts.admin')

@section('title', 'Create Customer | 1Dollar Admin')
@section('page_heading', 'Create Customer')
@section('page_subheading', 'Create a new customer account directly from the admin portal.')

@section('content')
    <section class="card">
        <div class="card-body">
            <div class="section-head" style="margin-bottom:18px;">
                <div>
                    <h3 style="margin:0 0 6px;font-size:1.15rem;">New Customer</h3>
                    <p class="muted" style="margin:0;">Fill in the details below. The customer will be created as an active account and can sign in immediately.</p>
                </div>
                <a href="{{ url('/v/customer_list.php') }}" class="badge">Back to Customers</a>
            </div>

            @if ($errors->any())
                <div class="alert" style="margin-bottom:18px;">
                    <strong>Please fix the following issues:</strong>
                    <ul style="margin:8px 0 0 18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="post" action="{{ url('/v/create-customer.php') }}">
                @csrf
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px;">
                    <div class="field">
                        <label for="first_name">First Name <span style="color:#c56b22;">*</span></label>
                        <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required>
                    </div>
                    <div class="field">
                        <label for="last_name">Last Name <span style="color:#c56b22;">*</span></label>
                        <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required>
                    </div>
                    <div class="field">
                        <label for="user_email">Email <span style="color:#c56b22;">*</span></label>
                        <input id="user_email" type="email" name="user_email" value="{{ old('user_email') }}" required>
                    </div>
                    <div class="field">
                        <label for="user_password">Password <span style="color:#c56b22;">*</span></label>
                        <input id="user_password" type="text" name="user_password" value="{{ old('user_password') }}" required>
                        <span class="muted" style="font-size:0.82rem;">Minimum 6 characters. The customer can change this later.</span>
                    </div>
                    <div class="field">
                        <label for="user_country">Country <span style="color:#c56b22;">*</span></label>
                        <select id="user_country" name="user_country" required>
                            <option value="">Select Country</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country }}" @selected(old('user_country') === $country)>{{ $country }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="user_phone">Phone <span style="color:#c56b22;">*</span></label>
                        <input id="user_phone" type="text" name="user_phone" value="{{ old('user_phone') }}" required>
                    </div>
                    <div class="field">
                        <label for="company">Company (optional)</label>
                        <input id="company" type="text" name="company" value="{{ old('company') }}">
                    </div>
                    <div class="field">
                        <label for="company_type">Company Type (optional)</label>
                        <select id="company_type" name="company_type">
                            <option value="">Select Type</option>
                            @foreach ($companyTypes as $type)
                                <option value="{{ $type }}" @selected(old('company_type') === $type)>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="package_type">Package <span style="color:#c56b22;">*</span></label>
                        <select id="package_type" name="package_type" required>
                            <option value="BASIC" @selected(old('package_type', 'BASIC') === 'BASIC')>BASIC</option>
                            <option value="BUSINESS" @selected(old('package_type') === 'BUSINESS')>BUSINESS</option>
                            <option value="CORPORATE" @selected(old('package_type') === 'CORPORATE')>CORPORATE</option>
                        </select>
                    </div>
                </div>

                <div style="margin-top:22px;display:flex;gap:12px;align-items:center;">
                    <button type="submit">Create Customer</button>
                    <a href="{{ url('/v/customer_list.php') }}" class="badge" style="background:transparent;color:var(--muted);border:1px solid var(--line);">Cancel</a>
                </div>
            </form>
        </div>
    </section>
@endsection
