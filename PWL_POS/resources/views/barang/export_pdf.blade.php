<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Data Barang</title>
    <style>
        @media print {
            body {
                font-family: "Times New Roman", Times, serif;
                margin: 6px 20px 5px 20px;
                line-height: 1.5;
            }

            .header-container {
                border-bottom: 1px solid #000;
                margin-bottom: 1rem;
            }

            .logo-cell {
                width: 15%;
                vertical-align: middle;
            }

            .institution-info span {
                display: block;
                text-align: center;
            }

            .report-title {
                text-align: center;
                margin: 1.5rem 0;
            }

            .data-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 1rem;
            }

            .data-table th,
            .data-table td {
                padding: 4px 3px;
                border: 1px solid #000;
            }

            .text-right {
                text-align: right;
            }

            .text-center {
                text-align: center;
            }

            .currency {
                font-family: 'Courier New', monospace;
            }

            .image-optimized {
                height: 80px;
                max-width: 150px;
                object-fit: contain;
            }
        }
    </style>
</head>
<body>
    <div class="header-container">
        <table>
            <tr>
                <td class="logo-cell text-center">
                    <img 
                        src="{{ public_path('polinema-bw.png') }}" 
                        class="image-optimized"
                        alt="Polinema Logo"
                        onerror="this.style.display='none'"
                    >
                </td>
                <td class="institution-info">
                    <span class="font-11 font-bold">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</span>
                    <span class="font-13 font-bold">POLITEKNIK NEGERI MALANG</span>
                    <span class="font-10">Jl. Soekarno-Hatta No. 9 Malang 65141</span>
                    <span class="font-10">Telepon (0341) 404424 Pes. 101-105, 0341-404420, Fax. (0341) 404420</span>
                    <span class="font-10">Laman: www.polinema.ac.id</span>
                </td>
            </tr>
        </table>
    </div>

    <h3 class="report-title">LAPORAN DATA BARANG</h3>

    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th class="text-right">Harga Beli</th>
                <th class="text-right">Harga Jual</th>
                <th>Kategori</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barang as $b)
                @php
                    $hargaBeli = number_format($b->harga_beli, 0, ',', '.');
                    $hargaJual = number_format($b->harga_jual, 0, ',', '.');
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $b->barang_kode }}</td>
                    <td>{{ $b->barang_nama }}</td>
                    <td class="text-right currency">Rp{{ $hargaBeli }}</td>
                    <td class="text-right currency">Rp{{ $hargaJual }}</td>
                    <td>{{ $b->kategori->kategori_nama ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data barang</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>