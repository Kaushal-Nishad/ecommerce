@extends('public.layout')
@section('title',site_settings()->site_title)
@section('content')

<!------ FLASH SALE PRODUCTS ------>
<div class="product-box">
  <div class="message"></div>
  <div class="container">
    <div class="section-heading">
        <h3 class="title">Flash Sale Products</h3>
    </div>
    <div class="row">
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
            <div class="col-md-3">
                @include('public.product-grid',$item)
            </div>
        @endif
    @endforeach
    </div>
    <ul class='pagination justify-content-center'>
        <li>{{$flash_products->appends(request()->query())->links()}}</li>
    </ul>
  </div>
</div>
<!------/FLASH SALE PRODUCTS------>

@stop

@section('pageJsScripts')
<script src="{{asset('assets/js/owl.carousel.js')}}"></script>
<script type="text/javascript">
   $(document).ready(function(){
        var owl = $('.owl-carousel');
        owl.owlCarousel({
            margin: 30,
            loop: true,
            nav: false,
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