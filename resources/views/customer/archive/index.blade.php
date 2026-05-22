@extends('layouts.customer')

@section('title', 'Paid Orders - '.$siteContext->displayLabel())
@section('hero_title', 'Paid Orders')
@section('hero_text', 'Review completed paid orders and reopen the details whenever you need them.')

@section('content')
    <section class="content-card">
        <div class="section-head">
            <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 90 90" width="72" height="72" style="flex-shrink:0;" aria-label="Quality Guarantee Seal">
                    <defs>
                        <linearGradient id="sealGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#169fe6;stop-opacity:1"/>
                            <stop offset="100%" style="stop-color:#0d6ea3;stop-opacity:1"/>
                        </linearGradient>
                    </defs>
                    <!-- Starburst points -->
                    <polygon points="45,4 49,20 55,8 56,24 64,14 62,30 72,23 67,38 79,34 70,47 83,47 72,57 84,61 70,67 80,75 65,77 71,87 57,85 59,90 47,84 45,90 43,84 31,90 33,85 19,87 25,77 10,75 20,67 6,61 18,57 7,47 20,47 11,34 23,38 18,23 28,30 26,14 34,24 35,8 41,20" fill="url(#sealGrad)"/>
                    <!-- Inner circle -->
                    <circle cx="45" cy="47" r="24" fill="#fff" fill-opacity="0.18"/>
                    <circle cx="45" cy="47" r="22" fill="none" stroke="#fff" stroke-width="1.2" stroke-opacity="0.5"/>
                    <!-- Check mark -->
                    <polyline points="34,47 42,55 57,38" fill="none" stroke="#fff" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div>
                    <h3>Paid Orders</h3>
                    <p>All paid orders are backed by our quality guarantee. To request an edit, please email 1dollardigitizing@gmail.com with your Order ID.</p>
                </div>
            </div>
            <button class="button ghost" onclick="document.getElementById('dlPaidOrdersModal').showModal()">Download Paid Orders</button>
        </div>

        <dialog id="dlPaidOrdersModal" style="border:1px solid var(--line,#e2e6ea);border-radius:12px;padding:28px 32px;max-width:400px;width:90%;box-shadow:0 8px 32px rgba(0,0,0,0.15);">
            <h3 style="margin:0 0 18px;font-size:1.05rem;">Download Paid Orders</h3>
            <form method="get" action="{{ url('/download-paid-orders.php') }}" id="dlPaidOrdersForm">
                <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:18px;">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                        <input type="radio" name="range_type" value="all" checked onchange="dlToggleRange()"> All Time
                    </label>
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                        <input type="radio" name="range_type" value="range" onchange="dlToggleRange()"> Date Range
                    </label>
                    <div id="dlDateRange" style="display:none;flex-direction:column;gap:8px;padding-left:22px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <label style="font-size:0.85rem;min-width:32px;">From</label>
                            <input type="date" name="date_from" id="dlDateFrom" style="flex:1;">
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <label style="font-size:0.85rem;min-width:32px;">To</label>
                            <input type="date" name="date_to" id="dlDateTo" style="flex:1;">
                        </div>
                    </div>
                </div>
                <div style="display:flex;gap:10px;justify-content:flex-end;">
                    <button type="button" class="button secondary" onclick="document.getElementById('dlPaidOrdersModal').close()">Cancel</button>
                    <button type="submit">Download ZIP</button>
                </div>
            </form>
        </dialog>
        <script>
        function dlToggleRange() {
            var isRange = document.querySelector('input[name="range_type"]:checked').value === 'range';
            var el = document.getElementById('dlDateRange');
            el.style.display = isRange ? 'flex' : 'none';
            document.getElementById('dlDateFrom').required = isRange;
            document.getElementById('dlDateTo').required = isRange;
        }
        document.getElementById('dlPaidOrdersForm').addEventListener('submit', function (e) {
            var isRange = document.querySelector('input[name="range_type"]:checked').value === 'range';
            if (!isRange) {
                document.getElementById('dlDateFrom').removeAttribute('name');
                document.getElementById('dlDateTo').removeAttribute('name');
            }
        });
        </script>

        <form method="get" action="{{ url('/view-archive-orders.php') }}" class="filter-bar">
            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Order ID or design name"
                style="flex:1; min-width:180px;"
            >
            <button type="submit">Search</button>
            @if ($search !== '')
                <a class="button secondary" href="{{ url('/view-archive-orders.php') }}">Clear</a>
            @endif
        </form>

        @if ($orders->count())
            <div class="table-wrap responsive-stack">
                <table class="responsive-table">
                    <thead>
                    @php
                        $sortLink = fn($col) => '/view-archive-orders.php?'.http_build_query(array_merge(request()->query(), [
                            'sort' => $col,
                            'dir' => ($sort === $col && $dir === 'asc') ? 'desc' : 'asc',
                            'page' => 1,
                        ]));
                        $sortIcon = fn($col) => $sort === $col ? ($dir === 'asc' ? ' ▲' : ' ▼') : '';
                    @endphp
                    <tr>
                        <th><a href="{{ $sortLink('order_id') }}">Order ID{!! $sortIcon('order_id') !!}</a></th>
                        <th><a href="{{ $sortLink('design_name') }}">Design Name{!! $sortIcon('design_name') !!}</a></th>
                        <th><a href="{{ $sortLink('completion_date') }}">Completion Date{!! $sortIcon('completion_date') !!}</a></th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->order_num ?: $order->order_id }}</td>
                            <td>{{ $order->design_name }}</td>
                            <td>{{ $order->completion_date ?: '-' }}</td>
                            <td><a class="button secondary" href="{{ url('/view-order-detail.php?order_id=' . $order->order_id . '&origin=archive') }}">View Detail</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                {{ $orders->links() }}
            </div>
        @else
            <div class="empty-state">
                @if ($search !== '' || ! $isDefaultRange)
                    No paid orders found matching your search.
                @else
                    No paid orders are currently available.
                @endif
            </div>
        @endif
    </section>
@endsection
