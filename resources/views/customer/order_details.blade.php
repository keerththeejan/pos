<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Order Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --bg: #f5f7fb;
            --border: #e5e7eb;
            --muted: #6b7280;
            --text: #111827;
            --accent: #2563eb;
            --header: #f3f6fb;
        }

        html, body { height: 100%; }
        body {
            background: var(--bg);
            color: var(--text);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .card-clean {
            background:#fff;
            border:1px solid var(--border);
            border-radius:10px;
            box-shadow: 0 8px 24px rgba(0,0,0,.06);
        }

        .card-clean .card-header {
            background: var(--header);
            border-bottom:1px solid var(--border);
            font-weight:600;
            letter-spacing:.2px;
        }

        .order-items img {
            width:48px; height:48px; object-fit:cover;
            border-radius:6px; border:1px solid var(--border);
        }

        /* Table polish */
        table.table thead th {
            font-weight: 700;
            color: #374151;
            white-space: nowrap;
        }
        table.table tbody td { vertical-align: middle; }
        table.table tbody tr:hover { background: #f9fbff; }

        /* Empty state */
        .empty { color: var(--muted); }
        .empty::before {
            content: "";
            display:inline-block;
            width:10px; height:10px;
            border-radius:50%;
            background:#d1d5db;
            margin-right:.5rem;
            vertical-align: middle;
        }

        /* Header spacing */
        h2 { font-weight: 700; letter-spacing:.2px; }
        .btn-outline-secondary.btn-sm { border-color: var(--border); color:#4b5563; }
        .btn-outline-secondary.btn-sm:hover { background:#eef2ff; border-color:#c7d2fe; color:#3730a3; }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="d-flex align-items-center mb-3">
        <h2 class="mb-0">My Order Details</h2>
        <div class="ms-auto"><a class="btn btn-primary btn-sm" href="{{ route('cart.show') }}">← Back to Cart</a></div>
    </div>

    <div class="row g-3">
        <div class="col-12">
            <div class="card card-clean">
                <div class="card-header">Items @if(!empty($order_no))<span class="text-muted small ms-2">(Order No: {{ $order_no }})</span>@endif</div>
                <div class="card-body p-0">
                    <div class="table-responsive order-items">
                        <table id="order-items-table" class="table mb-0 align-middle">
                            <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($items as $it)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            {{-- No image saved in order_details; show placeholder --}}
                                            <img src="{{ asset('img/default.png') }}" alt="{{ $it->product_name }}">
                                            <div>
                                                <div class="fw-semibold">{{ $it->product_name }}</div>
                                                <div class="text-muted small">{{ $it->sku }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>CHF {{ number_format($it->unit_price, 2) }}</td>
                                    <td>{{ (float) $it->quantity }}</td>
                                    <td>CHF {{ number_format($it->line_total, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center empty py-4">No confirmed order found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(function(){
        var $tbl = $('#order-items-table');
        if ($tbl.length) {
            // Initialize only if there is at least one real data row (not the empty-state with colspan)
            var hasRows = $tbl.find('tbody tr td:not([colspan])').length > 0;
            if (hasRows) {
                $tbl.DataTable({
                    paging: true,
                    searching: false,
                    info: false,
                    ordering: false,
                    pageLength: 10
                });
            }
        }
    });
    </script>
</body>
</html>
