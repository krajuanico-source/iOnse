<!DOCTYPE html>
<html>

<head>
  <title>Notice of Vacancy</title>
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

    table th,
    table td {
      border: 1px solid #000;
      padding: 5px;
      text-align: left;
    }
  </style>
</head>

<body>

  <div class="header">
    <h2>DSWD</h2>
    <h3>Notice of Vacancy</h3>
  </div>

  <p><strong>Item Number:</strong> {{ $item->item_number }}</p>
  <p><strong>Position:</strong> {{ $item->position->position_name }}</p>
  <p><strong>Salary Grade:</strong> {{ $item->salaryGrade->id }}</p>
  <p><strong>Employment Status:</strong> {{ $item->employmentStatus->name }}</p>
  <p><strong>Fund Source:</strong> {{ $item->fundSource->fund_source ?? '-' }}</p>
  <p><strong>Date of Posting:</strong> {{ \Carbon\Carbon::parse($item->date_posting)->format('M d, Y') }}</p>
  <p><strong>End of Submission:</strong> {{ \Carbon\Carbon::parse($item->date_end_submission)->format('M d, Y') }}</p>

  <h4>Qualification Standards</h4>
  <table>
    <tr>
      <th>Minimum Qualifications</th>
      <th>Preferred Qualifications</th>
    </tr>
    <tr>
      <td>Education, Eligibility, Training, Experience</td>
      <td>Education, Eligibility, Training, Experience</td>
    </tr>
  </table>

  <h4>Brief Description</h4>
  <p>Under general supervision, implements home life services to the wards of the Center. Performs other related tasks.</p>

  <h4>Competencies Required</h4>
  <ul>
    <li>Core: Commitment to Credible Public Service, Delivering Excellent Results</li>
    <li>Functional: Interpersonal Skills, Verbal Communication</li>
  </ul>

  <p>Interested applicants may submit their application at HR Management and Development.</p>

</body>

</html>