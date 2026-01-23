<html>

<head>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 12px;
    }

    .header {
      text-align: center;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th,
    td {
      border: 1px solid #000;
      padding: 5px;
      font-size: 11px;
    }
  </style>
</head>

<body>


  <div class="header">
    <h3>GAD PROFILE FORM</h3>
  </div>


  <table>
    <tr>
      <th>Employee ID</th>
      <td>{{ $profile->user_id }}</td>
    </tr>
    <tr>
      <th>Gender</th>
      <td>{{ $profile->gender }}</td>
    </tr>
    <tr>
      <th>Honorifics</th>
      <td>{{ $profile->honorifics }}</td>
    </tr>
  </table>
  <br>


  <h4>GAD Questions</h4>
  <table>
    @for($i=1;$i<=26;$i++)
      <tr>
      <th>Q{{ $i }}</th>
      <td>{{ $profile->{'q'.$i} }}</td>
      </tr>
      @endfor
  </table>


</body>

</html>