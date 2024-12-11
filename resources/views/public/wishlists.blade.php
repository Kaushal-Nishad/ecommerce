@extends('public.layout')
@section('title','My Wishlist')
@section('content')
<div id="site-content">
    <div class="message"></div>
    <div class="container">
        <div class="section-heading">
            <h3 class="title">My Wishlist</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">My Wishlist</li>
            </ol>
        </div>
        <div class="row wishlist-data">
            @if($products->isNotEmpty())
            @foreach($products as $item)
            <div class="col-md-3">
              <div class="product-grid">
                <div class="product-image">
                    <a href="{{url('/product/'.$item->slug)}}" class="image">
                        <img class="pic-1" src="{{asset('products/'.$item->thumbnail_img)}}">
                    </a>
                    <ul class="product-links">
                        @if(Session::has('user_id'))
                        <li><a href="javascript:void(0)" class="wishlist-active" data-tip="Add to Wishlist" data-id="{{$item->id}}"><i class="far fa-heart"></i></a></li>
                        @else
                        <li><a href="{{url('user_login')}}" data-tip="Add to Wishlist" data-id="{{$item->id}}"><i class="far fa-heart"></i></a></li>
                        @endif
                    </ul>
                </div>
                <div class="product-content">
                    <span class="category"><a href="">{{$item->brand_name}}</a></span>
                    <ul class="rating">
                        <li class="fas fa-star"></li>
                        <li class="fas fa-star"></li>
                        <li class="fas fa-star"></li>
                        <li class="fas fa-star"></li>
                        <li class="far fa-star"></li>
                    </ul>
                    <h3 class="title"><a href="{{url('/product/'.$item->slug)}}">{{$item->product_name}}</a></h3>
                    <div class="price">{{$item->unit_price}}</div>
                    <button type="button" class="btn btn-danger btn-sm mt-2 remove-wishlist" data-id="{{$item->id}}">Remove from wishlist</button>
                </div>
              </div>
            </div>
          @endforeach
          @else
            <div class="col-md-12 text-center">
                <h4>Your Wishlist is Empty</h4>
                <a href="{{url('/')}}" class="btn btn-primary">Add Items to Wishlist</a>
            </div>
          @endif
        </div>
    </div>
</div>
@stop

@section('pageJsScripts')
<script src="{{asset('assets/js/action.js')}}"></script>
<script type="text/javascript">
    // $(window).on('load', function(){
    //     var items = localStorage.getItem('product_ids');
    //     var url = $('.demo').val();
    //     $.ajax({
    //         url: url + '/show_wishlists',
    //         type: 'POST',
    //         data : {"_token": "{{ csrf_token() }}",wishlist_id:items},
    //         success: function(dataResult){
    //             if(dataResult['data'] != ''){
    //                 $('.wishlist-data').html(dataResult['data']);
    //             }else{
    //                 $('.wishlist-data').html("<div class='col-md-12'><div class='content-box text-center'><p class='m-0'>No wishlist found.</p></div></div>");
    //             }
                
    //         }
    //     });
    // });
    
</script>
@stop