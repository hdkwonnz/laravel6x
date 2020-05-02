@extends('layouts.app')

@section('content')
{{-- for test --}}
<link href="/myCss/test.css" rel="stylesheet">

<div class="container">
    <div class="row">
        <div class="col col-sm-4 col-md-4 col-lg-4"></div>
        <div class="mt-3 mb-3"><h1>ROOM BOOKING</h1></div>
        <div class="col col-sm-4 col-md-4 col-lg-4"></div>
    </div>
        
    <direct-booking></direct-booking>

</div>
@endsection
{{-- for test --}}
@section('extra-js')
    <script src="/myJs/test.js"></script>
@endsection