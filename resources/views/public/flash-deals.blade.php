@extends('public.layout')
@section('title',site_settings()->site_title)
@section('content')
<!------ BANNER GROUP ------>
<div class="banner-group">
  <div class="container">
    <div class="section-heading">
        <h3 class="title">Flash Deals</h3>
    </div>
    <div class="row">
      @foreach($flash_deals as $flash)
        @php 
          date_default_timezone_set('Asia/Kolkata');
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
        @if(($currentDatetime >= $startDatetime) && ($currentDatetime <= $endDatetime))
          <div class="col-md-4">
            <div class="banner-inner">
              <a href="{{url('/flash-products/'.$flash->flash_slug)}}">
                <img src="{{asset('flash-deals/'.$flash->flash_image)}}" alt="">
              </a>
            </div>
          </div>
        @endif
      @endforeach
    </div>
    <ul class='pagination justify-content-center'>
        <li>{{$flash_deals->appends(request()->query())->links()}}</li>
    </ul>
  </div>
</div>
<!------/BANNER GROUP------>
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