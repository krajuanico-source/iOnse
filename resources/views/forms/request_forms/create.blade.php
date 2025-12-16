@extends('layouts/contentNavbarLayout')

@section('title', 'Request Forms ')

@section('content')
<div class="container py-4">
  <h3>Create Request </h3>
  <hr>
  @include('forms.request_forms.form',['route' => route('forms.request_forms.store'),'method'=>'POST'])
</div>
@endsection
