@extends('layouts.admin')

@php
    $currentColumn = request('column_name', 'user_id');
    $currentDirection = strtolower(request('sort', 'desc'));
    $nextDirection = fn ($column) => $currentColumn === $column && $currentDirection === 'asc' ? 'desc' : 'asc';
@endphp

@section('title', 'Inactive Customers | 1Dollar Admin')
@section('page_heading', 'Inactive Customers')
@section('page_subheading', 'Previously active customer accounts that are currently inactive or blocked. Pending signup approvals are managed separately.')

@section('content')
    <section class="card">
        <div class="card-body">
            <form method="get" action="{{ url('/v/block-customer_list.php') }}" class="toolbar">
                <div class="field"><label>User ID</label><input type="text" name="txtUserID" value="{{ request('txtUserID') }}"></div>
                <div class="field"><label>Username</label><input type="text" name="txtUserName" value="{{ request('txtUserName') }}"></div>
                <div class="field"><label>Email</label><input type="text" name="txtEmail" value="{{ request('txtEmail') }}"></div>
                <div class="field" style="min-width:auto;"><label>&nbsp;</label><button type="submit">Search</button></div>
            </form>
        </div>
    </section>

    <section class="card">
        <div class="card-body">
            <div class="table-wrap">
                <table>
                    <thead>
                    <tr>
                        <th><a href="{{ request()->fullUrlWithQuery(['column_name' => 'user_id', 'sort' => $nextDirection('user_id')]) }}">User ID</a></th>
                        <th><a href="{{ request()->fullUrlWithQuery(['column_name' => 'user_name', 'sort' => $nextDirection('user_name')]) }}">Name</a></th>
                        <th><a href="{{ request()->fullUrlWithQuery(['column_name' => 'user_email', 'sort' => $nextDirection('user_email')]) }}">Email</a></th>
                        <th><a href="{{ request()->fullUrlWithQuery(['column_name' => 'user_country', 'sort' => $nextDirection('user_country')]) }}">Country</a></th>
                        <th><a href="{{ request()->fullUrlWithQuery(['column_name' => 'topup', 'sort' => $nextDirection('topup')]) }}">Credits</a></th>
                        <th><a href="{{ request()->fullUrlWithQuery(['column_name' => 'subscription_plan', 'sort' => $nextDirection('subscription_plan')]) }}">Subscription</a></th>
                        <th><a href="{{ request()->fullUrlWithQuery(['column_name' => 'is_active', 'sort' => $nextDirection('is_active')]) }}">Status</a></th>
                        <th><a href="{{ request()->fullUrlWithQuery(['column_name' => 'userip_addrs', 'sort' => $nextDirection('userip_addrs')]) }}">IP Address</a></th>
                        <th><a href="{{ request()->fullUrlWithQuery(['column_name' => 'date_added', 'sort' => $nextDirection('date_added')]) }}">Date Added</a></th>
                        <th class="action-col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (collect($customers)->isEmpty())
                        <tr><td colspan="10" class="muted">No inactive customers found.</td></tr>
                    @else
                    @foreach ($customers as $customer)
                        <tr>
                            <td class="cell-nowrap"><a href="{{ url('/v/customer-detail.php?uid='.$customer->user_id.'&source=inactive-customers') }}" style="font-weight:700;color:var(--accent);">{{ $customer->user_id }}</a></td>
                            <td class="cell-nowrap">{{ $customer->display_name ?: $customer->user_name ?: '-' }}</td>
                            <td class="cell-nowrap">{{ $customer->user_email ?: '-' }}</td>
                            <td class="cell-nowrap">{{ $customer->user_country ?: '-' }}</td>
                            <td class="cell-nowrap">
                                @php $credit = round((float) $customer->topup, 2); @endphp
                                @if ($credit > 0)
                                    <strong style="color:#1b8d5a;">${{ number_format($credit, 2) }}</strong>
                                @else
                                    <span class="muted">$0.00</span>
                                @endif
                            </td>
                            <td class="cell-nowrap">
                                @if ($customer->subscription_plan)
                                    <span>{{ ucfirst((string) $customer->subscription_plan) }}</span>
                                    @if ($customer->subscription_status)
                                        &nbsp;<span class="muted" style="font-size:0.78rem;">{{ ucfirst((string) $customer->subscription_status) }}</span>
                                    @endif
                                @else
                                    <span class="muted">—</span>
                                @endif
                            </td>
                            <td class="cell-nowrap">{{ (int) $customer->is_active === 1 ? 'Active' : 'Inactive' }}</td>
                            <td class="cell-nowrap">{{ $customer->userip_addrs ?: '-' }}</td>
                            <td class="cell-nowrap">{{ $customer->date_added ?: '-' }}</td>
                            <td class="action-col">
                                <div class="action-row">
                                    <a class="badge" href="{{ url('/v/edit-customer-detail.php?uid='.$customer->user_id) }}">Edit</a>
                                    <form method="post" action="{{ url('/v/block-customer_list/'.$customer->user_id.'/unblock') }}" onsubmit="return confirm('Unblock this customer?');">
                                        @csrf
                                        @foreach (request()->query() as $key => $value)
                                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                        @endforeach
                                        <button type="submit">Unblock</button>
                                    </form>
                                    <form method="post" action="{{ url('/v/block-customer_list/'.$customer->user_id.'/delete') }}" onsubmit="return confirm('Delete this customer?');">
                                        @csrf
                                        @foreach (request()->query() as $key => $value)
                                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                        @endforeach
                                        <button type="submit" style="background:linear-gradient(135deg,#a24d2a,#7f2e14);">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @endif
                    </tbody>
                </table>
            </div>

            @if ($customers->hasPages())
                <div style="margin-top:18px;">{{ $customers->links() }}</div>
            @endif
        </div>
    </section>
@endsection
