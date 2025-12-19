<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <style>
    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 12px;
    }

    h2 {
      text-align: center;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th,
    td {
      border: 1px solid #000;
      padding: 6px;
      text-align: left;
    }
  </style>
</head>

<body>

  <h2>Authentic Copy of CPR Ratings</h2>

  <p>
    <strong>Employee:</strong> {{ $request->user->name }} <br>
    <strong>Date Generated:</strong> {{ now()->format('F d, Y') }}
  </p>

  <table>
    <thead>
      <tr>
        <th>CPR ID</th>
        <th>Rating</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      @foreach($ratings as $item)
      <tr>
        <td>{{ $item['cpr_id'] }}</td>
        <td>{{ $item['rating'] }}</td>
        <td>Validated</td>
      </tr>
      @endforeach
    </tbody>
  </table>

</body>

</html>