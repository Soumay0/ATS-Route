@extends('layouts.dashboard')

@section('title', 'Create Navigational Aid')
@section('page-title', 'Create Navigational Aid')

@section('content')
    @include('navigational-aids._form', ['action' => route('navigational-aids.store'), 'method' => 'POST'])
@endsection