@php
$container = 'container-fluid';
$containerNav = 'container-fluid';
@endphp


@extends('layouts/contentNavbarLayout')

@section('title', 'Import Payroll')

@section('content')
<div class="card">
  <div class="container py-4">
    <div class="justify-content-between align-items-center mb-3">
      <h4>Import Payroll</h4>

      <div class="container mb-3">
      <div class="row">
        <div class="col-5 p-3 mb-2 mx-auto">
          <h5 class="text-center text-danger">Reminders Before Importing Payroll</h5>
          <li >Ensure the file is in <b>CSV format</b> before uploading.</li>
          <li >Check that all required columns are present in the file.</li>
          <li >Do not close the page while the process is running.</li></div>
        </div>
      </div>

      <!-- <div class="row justify-content-center">
    <div class="col-4">
      One of two columns
    </div>
    <div class="col-4">
      One of two columns
    </div> -->
</div>

    <div class="container mt-4">
  <form>
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="table-responsive">
          <table class="table table-bordered align-middle">
            <tbody>
              <tr>
                <td><label for="payrolldate" class="form-label fw-semibold text-secondary">PAYROLL DATE</label></td>
                <td>
                  <div class="input-group">
                    <input type="date" id="payrolldate" class="form-control" />
                  </div>
                </td>
              </tr>
              <tr>
                <td><label for="tranche" class="form-label fw-semibold text-secondary">TRANCHE</label></td>
                <td>
                  <select id="tranche" class="form-select">
                    <option selected disabled>Select Tranche</option>
                    <option value="1">Tranche 1</option>
                    <option value="2">Tranche 2</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td><label for="fund" class="form-label fw-semibold text-secondary">FUND</label></td>
                <td>
                  <select id="fund" class="form-select">
                    <option selected disabled>Select Fund Source</option>
                    <option value="pas">PAS</option>
                    <option value="gaa">GAA</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td><label for="payrollFile" class="form-label fw-semibold text-secondary">UPLOAD PAYROLL FILE</label></td>
                <td>
                  <input type="file" id="payrollFile" class="form-control" />
                </td>
              </tr>
            </tbody>
          </table>
        </div>
         <div class="text-center">
          <button class="mb-2 mt-2 btn btn-outline-primary hover-button">IMPORT LIST</button>
        </div>
      </div>
    </div>
  </form>
</div>


  </div>
</div>

</>@endsection
