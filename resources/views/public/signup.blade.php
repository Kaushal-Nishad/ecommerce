@extends('public.layout')
@section('title','Sign Up')
@section('content')
<div id="site-content" class="py-5">
    <div class="container">
        <div class="row">
              <div class="offset-lg-3 col-lg-6 col-md-8 offset-md-2">
                <div class="signup-form">
                    <!-- Form Start -->
                    <form class="form-horizontal mb-3" id="signup_form" method ="POST" autocomplete="off">
                    <h4 class="user-heading">Sign Up</h4>
                        @csrf
                        <input type="hidden" class="url" value="{{url('/signup')}}" >
                        <input type="hidden" class="url-login" value="{{url('/user_login')}}" >
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="name" class="form-control" placeholder="Name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="number" name="phone" class="form-control" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="Phone" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="password" name="con_password" class="form-control" placeholder="Confirm Password" required>
                                </div> 
                            </div>
                        </div>
                        <input type="submit"  name="save" class="btn btn-primary" value="Signup" required />
                    </form>
                    <span class="login-link">Already have an account <a href="{{url('user_login')}}">Login</a></span>
                    <!-- Form End-->
                </div>
            </div>
        </div>
    </div>
</div>
@stop