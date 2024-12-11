@extends('public.layout')
@section('title','Forgot Password')
@section('content')
<div id="site-content" class="py-5"> 
    <div class="container">
        <div class="row">
              <div class="offset-md-4 col-md-4">
              @if (Session::has('message'))
                <div class="alert alert-success" role="alert">
                {{ Session::get('message') }}
                </div>
            @endif
                <div class="signup-form">
                    <!-- Form Start -->
                    <form class="form-horizontal mb-3" action="{{url('forgot-password')}}" method ="POST" autocomplete="off">
                        <h4 class="user-heading mb-4">Forgot Password</h4>
                        @csrf
                        <input type="hidden" class="url" value="{{url('/')}}" >
                        <div class="form-group mb-4">
                            <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                        </div>
                        <input type="submit"  name="save" class="btn btn-primary" value="Send Password Reset Link" required />
                    </form>
                    <span class="login-link"><a href="{{url('user_login')}}">Back to Login</a></span>
                    <!-- Form End-->
                </div>
            </div>
        </div>
    </div>
</div>
@stop