@extends('public.layout')
@section('title','Change Password')
@section('content')
<div id="user-content">
    <div class="container">
        <div class="section-heading">
            <h3 class="title">Change Password</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Change Password</li>
            </ol>
        </div>
        <div class="row">
              <div class="offset-md-3 col-md-6">
                <div class="signup-form">
                    <!-- Form Start -->
                    <form class="form-horizontal" id="changepassword" method ="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" class="url" value="{{url('/changepassword')}}" >
                        <div class="form-group">
                            <label>Old Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Old Password" required>
                        </div>
                        <div class="form-group">
                            <label>New Password</label>
                            <input type="password" name="new_pass" class="form-control" id="new-pass" placeholder="New Password" required>
                        </div>
                        <div class="form-group">
                            <label>Re-enter New Password</label>
                            <input type="password" name="re_pass" class="form-control" placeholder="Re-enter New Password" required>
                        </div>
                        <input type="submit"  name="save" class="btn btn-primary" value="Update" required />
                    </form>
                    <!-- Form End-->
                </div>
            </div>
        </div>
    </div>
</div>
@stop