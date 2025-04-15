<!DOCTYPE html>
<html>
<head>
    <title>Export PDF Supplier</title>
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
    <h2 style="text-align: center;">Data Supplier</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Supplier</th>
                <th>Kontak Supplier</th>
                <th>Alamat Supplier</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
            <tr>
                <td>{{ $item->supplier_id }}</td>
                <td>{{ $item->supplier_nama }}</td>
                <td>{{ $item->supplier_kontak }}</td>
                <td>{{ $item->supplier_alamat }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 