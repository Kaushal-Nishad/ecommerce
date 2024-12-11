@extends('public.layout')
@section('title','Reset Password')
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
                @if (Session::has('error'))
                <div class="alert alert-danger" role="alert">
                {{ Session::get('error') }}
                </div>
                @endif
                <div class="signup-form">
                    <!-- Form Start -->
                    <form class="form-horizontal" action="{{url('reset-password')}}" method ="POST" autocomplete="off">
                        <h4 class="user-heading">Reset Password</h4>
                        @csrf
                        <input type="hidden" class="url" value="{{url('/')}}" >
                        <div class="form-group">
                            <input type="email" name="email" class="form-control" placeholder="Email Address" value="{{$email->email}}" readonly required>
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                            @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                            <input type="text" hidden name="token" value="{{$token}}">
                        </div>
                        <div class="form-group">
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
                            @if ($errors->has('password_confirmation'))
                                <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                            @endif
                        </div>
                        <input type="submit"  name="save" class="btn btn-primary" value="Reset" required />
                    </form>
                    <!-- Form End-->
                </div>
            </div>
        </div>
    </div>
</div>
@stop