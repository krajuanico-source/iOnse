@extends('layouts/contentNavbarLayout')

@section('title', 'CPR ')

@section('content')
<div class="container py-4">
  <h3>Create Travel Order </h3>
  <hr>
  @include('forms.cpr.form',['route' => route('forms.cpr.store'),'method'=>'POST'])
</div>
@endsection
