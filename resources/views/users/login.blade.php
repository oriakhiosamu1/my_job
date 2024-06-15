@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            {{-- @include('message') --}}

            <div class="card shadow-lg">
                <div class="card-header">Login</div>

                <form action="{{ route('login.post') }}" method="post">
                    @csrf

                    <div class="card-body">
                        <div class="form-group">
                            <label for="">Email</label>
                            <input type="email" name="email" class="form-control">
                            @if($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email')}}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="">Password</label>
                            <input type="password" name="password" class="form-control">
                            @if($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password')}}</span>
                            @endif
                        </div>
                        <br>
                        <div class="form-group text-center">
                            <button class="btn btn-primary" type="submit">Login</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-4">
            Employer test credentails:
            <p>Email: maxoh@mailinator.com<p>
            <p>Password: password123</p>
            <hr>
            Job seeker test credentails:
            <p>Email: dekivik@mailinator.com</p>
            <p>Password: password123</p>
        </div>
    </div>
</div>

{{-- styles --}}
<style>
body{
    background-color: #f5f5f5;
}
</style>

@endsection
