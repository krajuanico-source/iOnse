<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>CPR Certificate</title>
  <style>
    body {
      font-family: DejaVu Sans, sans-serif;
      text-align: center;
    }

    .certificate {
      border: 5px solid #333;
      padding: 50px;
      margin: 50px auto;
      width: 80%;
    }

    h1 {
      font-size: 42px;
      margin-bottom: 20px;
    }

    p {
      font-size: 20px;
      margin: 12px 0;
    }

    strong {
      color: #000;
    }
  </style>
</head>

<body>
  <div class="certificate">
    <h1>Certificate of Rating</h1>

    <p>This certifies that</p>

    <p>
      <strong>
        {{ $user->first_name }}
        {{ $user->last_name }}
        ({{ $user->id }})
      </strong>
    </p>

    <p>has achieved a rating of</p>

    <p>
      <strong>{{ $employee->rating }}</strong>
    </p>

    <p>
      for CPR ID:
      <strong>{{ $cpr->id }}</strong>
    </p>

    <p>
      Rating Period:
      <strong>
        {{ \Carbon\Carbon::parse($cpr->rating_period_start)->format('F d, Y') }}
        - {{ $cpr->semester }}
      </strong>
    </p>

    <p>
      Date Issued:
      <strong>{{ now()->format('F d, Y') }}</strong>
    </p>
  </div>
</body>

</html>
