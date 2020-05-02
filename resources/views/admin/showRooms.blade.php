@extends('layouts.app')

@section('content')
<div class="container">
           
    <div class="row">
        <div class="col-lg-4 offset-lg-4 mb-3">
            <h2>Booking 가능 Rooms</h2>
        </div>
    </div>

    <show-rooms></show-rooms>

</div>
@endsection