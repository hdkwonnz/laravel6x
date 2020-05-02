@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                    <span class="hehe test_class">hehe...</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

{{-- 매우중요==>view에서 juery(javascript)사용을 위해...  --}}
{{-- layouts/app.blade.php 하단에 @yield('extra-js')로 정의 했다. --}}
{{-- for test --}}
@section('extra-js')
    <script src="/myJs/test.js"></script>
@endsection
