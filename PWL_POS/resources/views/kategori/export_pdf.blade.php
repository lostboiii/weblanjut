<!DOCTYPE html>
<html>
<head>
    <title>Export PDF Kategori</title>
    <style>
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
    <h2 style="text-align: center;">Data Kategori</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Kode Kategori</th>
                <th>Nama Kategori</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
            <tr>
                <td>{{ $item->kategori_id }}</td>
                <td>{{ $item->kategori_kode }}</td>
                <td>{{ $item->kategori_nama }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 