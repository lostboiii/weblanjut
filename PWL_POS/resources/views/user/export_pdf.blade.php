<!DOCTYPE html>
<html>
<head>
    <title>Export PDF User</title>
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
    <h2 style="text-align: center;">Data User</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Nama</th>
                <th>Level</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
            <tr>
                <td>{{ $item->user_id }}</td>
                <td>{{ $item->username }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->level->level_nama }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 