<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Iuran - UTB Eats</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            @page {
                size: A4;
                margin: 2cm;
            }

            body {
                -webkit-print-color-adjust: exact;
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
                <h5 class="fw-bold text-primary mb-1">LAPORAN IURAN MITRA KANTIN</h5>
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
                    <th>Mitra Warung</th>
                    <th>Periode Tagihan</th>
                    <th>Status</th>
                    <th>Tgl Bayar</th>
                    <th>Nominal (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fees as $index => $fee)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $fee->merchant->shop_name }}</td>
                        <td>{{ DateTime::createFromFormat('!m', $fee->month)->format('F') }} {{ $fee->year }}</td>
                        <td class="text-center">
                            {{ $fee->status == 'paid' ? 'Lunas' : 'Belum Bayar' }}
                        </td>
                        <td class="text-center">
                            {{ $fee->paid_at ? date('d/m/Y', strtotime($fee->paid_at)) : '-' }}
                        </td>
                        <td class="text-end">{{ number_format($fee->amount) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-3">Data Kosong</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-end fw-bold">TOTAL PEMASUKAN:</td>
                    <td class="text-end fw-bold">Rp {{ number_format($totalPemasukan) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="row mt-5">
            <div class="col-8"></div>
            <div class="col-4 text-center">
                <p class="mb-5">Bandung, {{ date('d F Y') }}<br>Bagian Keuangan,</p>
                <br><br>
                <p class="fw-bold text-decoration-underline">Bendahara Kampus</p>
            </div>
        </div>
    </div>
</body>

</html>
