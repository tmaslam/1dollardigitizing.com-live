@extends('layouts.customer')

@section('title', 'Paid Orders - '.$siteContext->displayLabel())
@section('hero_title', 'Paid Orders')
@section('hero_text', 'Review completed paid orders and reopen the details whenever you need them.')

@section('content')
    <section class="content-card">
        <div class="section-head">
            <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" width="90" height="90" style="flex-shrink:0;" aria-label="Quality Guarantee Seal">
                    <defs>
                        <radialGradient id="qgStar" cx="38%" cy="32%" r="68%">
                            <stop offset="0%" stop-color="#ececec"/>
                            <stop offset="100%" stop-color="#9e9e9e"/>
                        </radialGradient>
                        <radialGradient id="qgCirc" cx="38%" cy="32%" r="68%">
                            <stop offset="0%" stop-color="#f4f4f4"/>
                            <stop offset="60%" stop-color="#d8d8d8"/>
                            <stop offset="100%" stop-color="#b4b4b4"/>
                        </radialGradient>
                        <linearGradient id="qgBlue" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="#0d6ea3"/>
                            <stop offset="25%" stop-color="#169fe6"/>
                            <stop offset="75%" stop-color="#169fe6"/>
                            <stop offset="100%" stop-color="#0d6ea3"/>
                        </linearGradient>
                    </defs>
                    <!-- Starburst (20 teeth, silver) -->
                    <polygon points="100,5 113.1,17 129.4,9.7 138.1,25.1 155.8,23.1 159.4,40.6 176.9,44.2 174.8,61.9 190.3,70.6 183,86.9 195,100 183,113.1 190.3,129.4 174.8,138.1 176.9,155.8 159.4,159.4 155.8,176.9 138.1,174.9 129.4,190.3 113.1,183 100,195 86.9,183 70.6,190.3 61.9,174.9 44.2,176.9 40.6,159.4 23.1,155.8 25.2,138.1 9.7,129.4 17,113.1 5,100 17,86.9 9.7,70.6 25.2,61.9 23.1,44.2 40.6,40.6 44.2,23.1 61.9,25.1 70.6,9.7 86.9,17" fill="url(#qgStar)"/>
                    <!-- Inner silver circle with rings -->
                    <circle cx="100" cy="100" r="78" fill="url(#qgCirc)"/>
                    <circle cx="100" cy="100" r="73" fill="none" stroke="#bbb" stroke-width="1.5"/>
                    <circle cx="100" cy="100" r="69" fill="url(#qgCirc)" opacity="0.5"/>
                    <circle cx="100" cy="100" r="65" fill="none" stroke="#ccc" stroke-width="0.8"/>
                    <!-- Text arc paths -->
                    <path id="qgTopArc" d="M 38,100 A 62,62 0 0,0 162,100" fill="none"/>
                    <path id="qgBotArc" d="M 38,100 A 62,62 0 0,1 162,100" fill="none"/>
                    <!-- Top curved text -->
                    <text font-size="9" font-weight="bold" fill="#555" letter-spacing="1.2" font-family="Arial,sans-serif">
                        <textPath href="#qgTopArc" startOffset="50%" text-anchor="middle">QUALITY GUARANTEE</textPath>
                    </text>
                    <!-- Bottom curved text -->
                    <text font-size="9" font-weight="bold" fill="#555" letter-spacing="1.2" font-family="Arial,sans-serif">
                        <textPath href="#qgBotArc" startOffset="50%" text-anchor="middle">QUALITY GUARANTEE</textPath>
                    </text>
                    <!-- Stars top (3) -->
                    <text x="82" y="67" text-anchor="middle" font-size="11" fill="#909090">★</text>
                    <text x="100" y="62" text-anchor="middle" font-size="11" fill="#909090">★</text>
                    <text x="118" y="67" text-anchor="middle" font-size="11" fill="#909090">★</text>
                    <!-- Stars bottom (2) -->
                    <text x="88" y="151" text-anchor="middle" font-size="11" fill="#909090">★</text>
                    <text x="112" y="151" text-anchor="middle" font-size="11" fill="#909090">★</text>
                    <!-- Ribbon left notched tail -->
                    <polygon points="22,85 0,85 13,100 0,115 22,115" fill="#0a5a8a"/>
                    <!-- Ribbon main band -->
                    <rect x="22" y="85" width="156" height="30" fill="url(#qgBlue)"/>
                    <!-- Ribbon right notched tail -->
                    <polygon points="178,85 200,85 187,100 200,115 178,115" fill="#0a5a8a"/>
                    <!-- Ribbon highlights/shadow for depth -->
                    <rect x="22" y="85" width="156" height="9" fill="#fff" opacity="0.1"/>
                    <rect x="22" y="109" width="156" height="6" fill="#000" opacity="0.07"/>
                    <!-- Ribbon text -->
                    <text x="100" y="100" text-anchor="middle" dominant-baseline="middle" font-size="13" font-weight="900" fill="#fff" font-family="Arial Black,Arial,sans-serif" letter-spacing="1">QUALITY GUARANTEE</text>
                </svg>
                <div>
                    <h3>Paid Orders</h3>
                    <p>Need an edit? Email your Order ID to support@1dollardigitizing.com or 1dollardigitizing@gmail.com.</p>
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
