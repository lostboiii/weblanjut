<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Export PDF Barang</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
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

    <h2 style="text-align: center;">Data Barang</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Kategori</th>
                <th>Supplier</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
            <tr>
                <td>{{ $item->barang_id }}</td>
                <td>{{ $item->barang_kode }}</td>
                <td>{{ $item->barang_nama }}</td>
                <td>{{ $item->harga_beli }}</td>
                <td>{{ $item->harga_jual }}</td>
                <td>{{ $item->kategori->kategori_nama }}</td>
                <td>{{ $item->supplier->supplier_nama }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>