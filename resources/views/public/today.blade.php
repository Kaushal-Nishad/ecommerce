@extends('public.layout')
@section('title','Today Deals')
@section('content')
<div id="site-content" class="py-5"> 
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-heading">
                    <h2 class="title">Today Deals</h2>
                </div>
            </div>
        @foreach($today_products as $item)
            <div class="col-md-3 col-sm-6">
            @include('public.product-grid',$item)
            </div>
        @endforeach
        <div class="col-md-12">
            {{$today_products->links()}}
        </div>
        </div>
    </div>
</div>
@stop