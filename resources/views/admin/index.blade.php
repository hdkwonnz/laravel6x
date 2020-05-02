@extends('layouts.app')

@section('content')
<div class="container">
           
    <div class="row">
        <div class="col-lg-4 offset-lg-4">
            <h2>일자별 Booking 건 수</h2>
        </div>
    </div>

    <show-calendar></show-calendar>

</div>
@endsection