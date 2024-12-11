@extends('public.layout')
@section('title','Payment Successfull')
@section('content')

<div class="successfull-page">
    <div class="row m-0">
        <div class="offset-md-4 col-md-4">
            <div class="success-message">
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h4>Payment Successfull!</h4>
            </div>
        </div>
        <div class="col-md-4"></div>
        <div class="col-md-12">
            <div class="booking-btn text-center">
                <a href="{{url('/my_orders')}}" class="btn btn-primary text-center">My Orders</a>
            </div>
        </div>
    </div>
</div>

@stop