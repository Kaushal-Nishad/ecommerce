@extends('public.layout')
@section('title','User Login')
@section('content')
<div id="site-content" class="py-5"> 
    <div class="container">
        <div class="row">
              <div class="col-lg-4 offset-lg-4 col-md-6 offset-md-3">
                <div class="signup-form">
                    <!-- Form Start -->
                    @if (Session::has('message'))
                    <div class="alert alert-success" role="alert">
                    {{ Session::get('message') }}
                    </div>
                    @endif
                    <form class="form-horizontal mb-3" id="user_login" method ="POST" autocomplete="off">
                        <h4 class="user-heading">Login</h4>
                        @csrf
                        <input type="hidden" class="url" value="{{url('/')}}" >
                        <div class="form-group">
                            <!-- <label>User email</label> -->
                            <input type="email" name="username" class="form-control" placeholder="Email Address" required>
                        </div> 
                        <div class="form-group">
                            <!-- <label>Password</label> -->
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                        <div class="d-flex flex-row justify-content-between">
                            <input type="submit"  name="save" class="btn btn-primary login-btn" value="Login" required />
                            <a href="{{url('forgot-password')}}" class="forgot-password align-self-center">forgot password</a>
                        </div>
                    </form>
                    <!-- Form End-->
                    <span class="signup-link"><a href="{{url('signup')}}">Create Account</a></span>
                </div>
            </div>
        </div>
    </div>
</div>
@stop