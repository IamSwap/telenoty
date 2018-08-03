@extends('layouts.app')

@section('content')
<Dashboard :user="{{ auth()->user() }}"></Dashboard>
@endsection