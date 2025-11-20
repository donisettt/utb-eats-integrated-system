<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi - UTB Eats</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* CSS Khusus Cetak */
        @media print {
            @page {
                size: A4;
                margin: 2cm;
            }

            body {
                -webkit-print-color-adjust: exact;
            }

            .no-print {
                display: none;
            }
        }

        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .header-logo {
            width: 60px;
        }
    </style>
</head>

<body onload="window.print()">

    <div class="container mt-4">
        <div class="row mb-4 border-bottom pb-3 align-items-center">
            <div class="col-2 text-center">
                <img src="{{ asset('images/logo.png') }}" class="header-logo">
            </div>
            <div class="col-8 text-center">
                <h4 class="fw-bold mb-1">UNIVERSITAS TEKNOLOGI BANDUNG</h4>
                <h5 class="fw-bold text-primary mb-1">LAPORAN TRANSAKSI UTB EATS</h5>
                <p class="mb-0 text-muted">Jl. Soekarno Hatta, Bandung | utb-eats.ac.id</p>
            </div>
            <div class="col-2"></div>
        </div>

        <div class="d-flex justify-content-between mb-3">
            <div>
                <strong>Periode Laporan:</strong><br>
                {{ date('d F Y', strtotime($startDate)) }} s/d {{ date('d F Y', strtotime($endDate)) }}
            </div>
            <div class="text-end">
                <strong>Dicetak Oleh:</strong><br>
                {{ auth()->user()->name }} (Admin)<br>
                {{ date('d/m/Y H:i') }}
            </div>
        </div>

        <table class="table table-bordered table-striped">
            <thead class="table-dark text-center">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Mitra Warung</th>
                    <th>Pelanggan</th>
                    <th>Status</th>
                    <th>Total (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $index => $order)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                        <td>{{ $order->merchant->shop_name }}</td>
                        <td>{{ $order->user->name }}</td>
                        <td class="text-center">{{ ucfirst($order->status) }}</td>
                        <td class="text-end">{{ number_format($order->total_price) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-3">Data Kosong</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-end fw-bold">TOTAL PENDAPATAN:</td>
                    <td class="text-end fw-bold">Rp {{ number_format($totalOmzet) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="row mt-5">
            <div class="col-8"></div>
            <div class="col-4 text-center">
                <p class="mb-5">Bandung, {{ date('d F Y') }}<br>Mengetahui,</p>
                <br><br>
                <p class="fw-bold text-decoration-underline">Kepala Bagian Umum</p>
            </div>
        </div>
    </div>

</body>

</html>
