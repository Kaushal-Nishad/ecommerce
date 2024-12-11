@extends('public.layout')
@section('title',site_settings()->site_title)
@section('content')
<!------ BANNER ------>
<div id="banner">
    <div class="flexslider">
        <ul class="slides">
           @foreach($banner as $item)
           @if($item->status == '1')
           <li >
               <div class="row">
                 <div class="col-md-12 col-sm-12">
                   <div>
                      <a href="{{url($item->pagelink)}}" target="_blank"><img class="banner-img" src="{{asset('banner/'.$item->banner_img)}}"></a>
                   </div>
                 </div>
               </div>
           </li>
           @endif
          @endforeach
        </ul>
    </div>
</div>
<!------/BANNER------>
@if($today_deal_products->isNotEmpty())
<!------ Today Deal PRODUCTS ------>
<div class="product-box">
  <div class="container">
    <div class="section-heading">
        <h3 class="title">Today Deals</h3>
        <a href="{{url('/today-deals')}}" class="btn btn-primary btn-sm">View All</a>
    </div>
    <div class="row"> 
      <div class="col-md-12">
        <div class=" today-carousel owl-carousel owl-theme">
          @foreach($today_deal_products as $item)
              <div class="item">
                @include('public.product-grid',$item)
              </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
<!------/Today Deal PRODUCTS------>
@endif
<!------ LATEST PRODUCTS ------>
@if($latest_products->isNotEmpty())
<div class="product-box">
  <div class="message"></div>
  <div class="container">
    <div class="section-heading">
        <h3 class="title">Latest Products</h3>
        <a href="{{url('/search')}}" class="btn btn-primary btn-sm">View All</a>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class=" latest-carousel owl-carousel owl-theme">
          @foreach($latest_products as $item)
              <div class="item">
                @include('public.product-grid',$item)
              </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
@endif
<!------/LATEST PRODUCTS------>
@if($flash_deals->isNotEmpty())
<!------ BANNER GROUP ------>
<div class="banner-group flash-deals">
  <div class="container">
    <div class="section-heading">
        <h3 class="title">Flash Deals</h3>
          <a href="{{url('/flash-deals')}}" class="btn btn-primary btn-sm">View All</a>
    </div>
    <div class="row">
      @foreach($flash_deals as $flash)
        @php 
            $datetime = explode('-',$flash->flash_date_range);
            $currentDatetime = date('Y-m-d H:i A');
            if($flash->flash_date_range != ''){
              $startDatetime = date('Y-m-d H:i A', strtotime("$datetime[0]"));
              $endDatetime = date('Y-m-d H:i A', strtotime("$datetime[1]"));
            }else{
              $startDatetime = '';
              $endDatetime = '';
            }
        @endphp
        @if($flash->status == '1')
          @if(($currentDatetime >= $startDatetime) && ($currentDatetime <= $endDatetime))
          <div class="col-md-4 flash-deal-box">
            <div class="banner-inner">
              <a href="{{url('/flash-products/'.$flash->flash_slug)}}">
                <img src="{{asset('flash-deals/'.$flash->flash_image)}}" alt="">
              </a>
            </div>
          </div>
          @endif
        @endif
      @endforeach
    </div>
  </div>
</div>
@endif
<!------/BANNER GROUP------>

<!------ FLASH SALE PRODUCTS ------>
@if($flash_products->isNotEmpty())
<div class="product-box flash-products">
  <div class="message"></div>
  <div class="container">
    <div class="section-heading">
        <h3 class="title">Flash Sale</h3>
          <a href="{{url('/flash-products')}}" class="btn btn-primary btn-sm">View All</a>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="flash-carousel owl-carousel owl-theme position-relative">
          @foreach($flash_products as $item)
            @php 
              date_default_timezone_set('Asia/Kolkata');
              $datetimes = explode('-',$item->flash_date_range);
              $currentDatetimes = date('Y-m-d H:i A');
              if($item->flash_date_range != ''){
                $startDatetimes = date('Y-m-d H:i A', strtotime("$datetimes[0]"));
                $endDatetimes = date('Y-m-d H:i A', strtotime("$datetimes[1]"));
              }else{
                $startDatetimes = '';
                $endDatetimes = '';
              }
            @endphp
            @if(($currentDatetimes >= $startDatetimes) && ($currentDatetimes <= $endDatetimes))
            <div class="item flash-product">
              @include('public.product-grid',$item)
            </div>
            @endif
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
@endif
<!------/FLASH SALE PRODUCTS------>
@stop

@section('pageJsScripts')
<script src="{{asset('assets/js/owl.carousel.js')}}"></script>
<script type="text/javascript">
   $(document).ready(function(){
      if($('.flash-deal-box').length < 1){
        $('.banner-group.flash-deals').hide();
      }
      if($('.flash-product').length < 1){
        $('.flash-products').hide();
      }


        var owl = $('.latest-carousel');
        owl.owlCarousel({
            margin: 30,
            loop: true,
            nav: true,
            responsive: {
                0: {
                    items: 1
                },
                450: {
                    items: 2
                },
                600: {
                    items: 3
                },
                1000: {
                    items: 4
                },
            }
        });

        $('.flash-carousel').owlCarousel({
            margin: 30,
            nav: true,
            responsive: {
                0: {
                    items: 1
                },
                450: {
                    items: 2
                },
                600: {
                    items: 3
                },
                1000: {
                    items: 4
                },
            }
        });
        $('.today-carousel').owlCarousel({
            margin: 30,
            loop: false,
            nav: true,
            responsive: {
                0: {
                    items: 1
                },
                450: {
                    items: 2
                },
                600: {
                    items: 3
                },
                1000: {
                    items: 4
                },
            }
        });
    });

    
</script>

@stop