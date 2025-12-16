<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Document Request Form</title>

  <style>
    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 11px;
      margin: 0;
      padding: 0;
    }

    .wrapper-table {
      width: 100%;
      border-collapse: collapse;
      table-layout: fixed;
    }

    .col {
      width: 50%;
      padding: 10px 15px;
      vertical-align: top;
      border-right: 1px solid #000;
      box-sizing: border-box;
    }

    .col:last-child {
      border-right: none;
    }

    /* Force the content to fit within one page */
    .pdf-copy {
      font-size: 11px;
      page-break-inside: avoid;
    }

    .logo-row {
      width: 100%;
    }

    .logo {
      width: 120px;
    }

    .options td {
      font-size: 12px;
    }

    .header-code {
      font-size: 9px;
    }

    .title {
      text-align: center;
      font-size: 14px;
      font-weight: bold;
      margin: 10px 0;
    }

    .signature-block {
      margin-top: 25px;
      text-align: center;
      font-size: 11px;
    }

    .signature-line {
      width: 200px;
      border-top: 1px solid #000;
      margin: 5px auto 0 auto;
    }

    .footer {
      text-align: center;
      font-size: 9px;
      margin-top: 20px;
    }

    .footer-logos {
      text-align: right;
    }

    .footer-logo {
      width: 50px;
      margin-left: 5px;
    }
  </style>
</head>

<body>

  <table class="wrapper-table">
    <tr>
      <!-- LEFT COPY -->
      <td class="col">
        <div class="pdf-copy">
          @include('forms.request_forms.pdf_copy', ['requestForm' => $requestForm, 'user' => $user])
        </div>
      </td>

      <!-- RIGHT COPY -->
      <td class="col">
        <div class="pdf-copy">
          @include('forms.request_forms.pdf_copy', ['requestForm' => $requestForm, 'user' => $user])
        </div>
      </td>
    </tr>
  </table>

</body>

</html>
