@php
$container = 'container-fluid';
$containerNav = 'container-fluid';
@endphp


@extends('layouts/contentNavbarLayout')

@section('title', 'Tables - Basic Tables')


@section('title', 'Payroll')


@section('content')
<!-- Basic Bootstrap Table -->
<div class="container-fluid p-2">


<div class="container text-center">

<div class="card px-3 py-2 container text-center">
  <div class="row row-cols-lg-7 ">
        <div class="col-md-3">
         <label for="fundsource" class="float-start form-label fw-semibold">Fund Source</label>
        <select class="form-select form-select-sm" aria-label="Small select example">
          <option selected disabled>Select Fund Source</option>
          <option value="1">One</option>
          <option value="2">Two</option>
          <option value="3">Three</option>
        </select>
        </div>
        <div class="col-md-3">
         <label for="tranche" class="float-start form-label fw-semibold">Tranche</label>
        <select class="form-select form-select-sm" aria-label="Small select example">
          <option selected disabled>Select Tranche</option>
          <option value="1">One</option>
          <option value="2">Two</option>
          <option value="3">Three</option>
        </select>
        </div>

        <div class="col-md-3">
          <label for="selectmonth" class="float-start form-label fw-semibold">Select Month</label>
          <input type="month" id="bdaymonth" name="bdaymonth" class="form-control form-control-sm">
        </div>

        <div class="col-md-3">
                    <label for="submitbutton" class="float-start form-label fw-semibold">Submit</label>
          <button id="openModalBtn" class=" float-start btn btn-success">+ Add New Tax Column</button>
        </div>

</div>

</div>

</div>

</>@endsection
