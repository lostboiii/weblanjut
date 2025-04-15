<!DOCTYPE html>
<html>
<head>
    <title>Export PDF Level</title>
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
    <h2 style="text-align: center;">Data Level</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Kode Level</th>
                <th>Nama Level</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
            <tr>
                <td>{{ $item->level_id }}</td>
                <td>{{ $item->level_kode }}</td>
                <td>{{ $item->level_nama }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 