@extends('public.layout')
@section('title',$product->product_name)
@section('content')
<div id="site-content">
    <div class="message"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="content-box single-product">
                    <div class="flexslider">
                        <ul class="slides">
                            <!-- <input type="hidden" name="id[]" id="product-id" value="{{$product->id}}"> -->
                            @php 
                                $images = array_filter(explode(',',$product->gallery_img));
                            @endphp
                            @for($i=0; $i < count($images); $i++)
                                <li data-thumb="{{asset('products/'.$images[$i])}}" >
                                    <img src="{{asset('products/'.$images[$i])}}" />
                                </li>
                            @endfor
                        </ul>
                    </div>
                    <span class="add-favourite addwishlist" data-id="{{$product->id}}"><i class="fas fa-heart"></i></span>
                </div>
            </div>
            <div class="col-md-6">
                <!-- <form action="{{url('/checkout')}}"> -->
                    @csrf
                <input type="hidden" name="product_id" id="product_id" value="{{$product->id}}">
                <div class="product-info">
                    <span class="brand-name">{{$product->brand_name}}</span>
                    <p class="product-name">{{$product->product_name}}</p>
                    @php $product_price = get_product_price($product->id); @endphp
                    <div class="product-price">
                        <span class="special-price">{{site_settings()->currency}}{{$product_price->new_price}}</span>
                        @if($product_price->discount != '')
                        <span class="old-price">{{site_settings()->currency}}{{$product_price->old_price}}</span>
                        <span class="discount-price">{{$product_price->discount}} off</span>
                        @endif
                    </div>
                    <ul class="rating">
                    @if($product->rating_col > 0)
                          @php $rating = ceil($product->rating_sum/$product->rating_col);  @endphp  
                        @else
                          @php $rating = 0;  @endphp  
                        @endif
                        @for($i=1;$i<=5;$i++)
                            @if($i <= $rating)
                              <li class="fa fa-star"></li>
                            @else
                              <li class="far fa-star"></li>
                            @endif
                        @endfor
                        <li>({{$product->rating_col}} reviews)</li>
                    </ul>
                    <div class="product-color">
                        @php
                            $p_colors = array_filter(explode(',',$product->colors));
                            $i=0;
                        @endphp
                        @if(!empty($p_colors))
                        <label>Color:</label>
                            <ul class="option-list">
                            @foreach($colors as $item1)
                                @if(in_array($item1->id,$p_colors))
                                    @php $color_check = ($i==0) ? 'checked' : '';  @endphp
                                    <li class="radio-button">
                                        <input type="radio" name="product_color" {{$color_check}} id="color{{$item1->id}}" required value="{{$item1->id}}" data-id="{{$product->id}}">
                                        <label for="color{{$item1->id}}" style="background-color:{{$item1->color_code}}"></label>
                                    </li>
                                    @php $i++;  @endphp
                                @endif
                            @endforeach
                            </ul>
                        @endif
                    </div>
                    @foreach($attributes as $row)
                        @php
                            $value = array_filter(explode(',',$row->attrvalues));
                        @endphp
                        <div class="product-attributes">
                            <span>{{$row->title}}:</span>
                            @php $j=0;  @endphp
                            @foreach($attrvalues as $item1)
                                @if(in_array($item1->id,$value))
                                @php $attr_check = ($j==0) ? 'checked' : '';  @endphp
                                    <input type="hidden" name="product_attrvalues"  value="{{$item1->id}}" data-id="{{$row->product_id}}">
                                    <input type="radio" class="attrvalue" data-attr="{{$item1->attribute}}" id="attrvalue{{$item1->id}}" name="{{strtolower($row->title)}}" {{$attr_check}} value="{{$item1->id}}" required>
                                    <label for="attrvalue{{$item1->id}}">{{$item1->value}}</label>
                                    @php $j++;  @endphp
                                @endif
                            @endforeach
                        </div>
                    @endforeach
                    <!-- if($product->shipping_charges == 'free')
                        <div class="product-shipping">
                            <span><b>Shipping Charges: </b></span>
                            <p class="badge badge-success">Free</p>
                            <input type="hidden" name="shipping" value="0" data-id="{{$product->id}}">
                        </div>
                    else -->
                        <div class="product-shipping">
                            <span class="shipping-head">Delivery: </span>
                            <select class="form-control shipping" name="shipping" id="" required>
                                <option value="" selected disabled>Select Location</option>
                                @foreach($cities as $city)
                                    @php $selected = ''; @endphp
                                    @if(session()->get('user_city') == $city->id)
                                        @php $selected = 'selected'; @endphp
                                    @endif
                                    <option value="{{$city->id}}" data-p-ship="{{$product->shipping_charges}}" {{$selected}} data-shipping="{{$city->cost_city}}">{{$city->city_name}} ({{$city->state_name}})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="shipping-charges"></div>
                    <!-- endif -->
                    <div class="product-btn">
                        <?php if(session()->has('user_name')){ ?>
                            @if(in_array($product->id,$cart))
                            <a href="{{url('/cart')}}" class="btn btn-primary">Go to cart</a>
                            @else
                            <button type="button" class="btn btn-primary mr-2" id="addcart" data-user="{{session()->get('user_id')}}" data-id="{{$product->id}}">Add to cart</button>
                            @endif
                                <a href="#" class="btn btn-danger" id="checkout">Buy Now</a>
                                <!-- <button type="submit" class="btn btn-danger">Buy Now</button> -->
                        <?php }else{ ?>
                            <button type="button" class="btn btn-primary mr-2" id="addcart" data-user='' data-id="{{$product->id}}">Add to cart</button>
                            <a href="{{url('/user_login')}}" class="btn btn-danger">Buy Now</a>
                        <?php } ?>
                    </div>
                    <!-- </form> -->
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="section-heading">
                    <h4 class="title">Description</h4>
                </div>
                <p>{!!htmlspecialchars_decode($product->description)!!}</p>
            </div>
        </div>
        <div class="row">
            @if($reviews->isNotEmpty())
            <div class="col-md-6">
                <div class="section-heading">
                    <h4 class="title">Reviews</h4>
                </div>
                <div class="product-reviews">
                    @foreach($reviews as $review)
                    <div class="review-item">
                        <h6><span class="bg-success"><i class="fa fa-star"></i> {{$review->rating}}</span> {{$review->title}}</h6>
                        <p>{{$review->desc}}</p>
                        <span class="user">{{$review->name}}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            @if($product->video_link != '')
            <div class="col-md-6">
                <div class="section-heading">
                    <h4 class="title">Video</h4>
                </div>
                
            </div>
            @endif
        </div>
    </div>
</div>

<!------ RELATED PRODUCTS ------>
@if($related->isNotEmpty($related))
<div class="product-box">
  <div class="container">
    <div class="section-heading">
        <h3 class="title">Related Products</h3>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="owl-carousel owl-theme related-carousel">
            @foreach($related as $item)
                @include('public.product-grid',$item)
            @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
@endif
<!------/RELATED PRODUCTS------>
@stop

@section('pageJsScripts')
<script src="{{asset('assets/js/owl.carousel.js')}}"></script>
<script type="text/javascript">
   $(document).ready(function(){
       
        $(document).on('click','#checkout',function(){
            var attr = {};
            var p_id = $('input[name=product_id]').val();
    
            if($('input[name=product_color]').length > 0){
            var color_id = $('input[name="product_color"]:checked').val();
                if(color_id == ''){
                    alert('Select Color');
                }
            }
            if($('.product-attributes').length > 0){
                var attr_val = '';
                $('.product-attributes').each(function(){
                    var key = $(this).children('input[class=attrvalue]:checked').attr('data-attr');
                    var val = $(this).children('input[class=attrvalue]:checked').val();
                    attr_val += key+':'+val+',';
                }); 
                attr[p_id] = attr_val;
            }

            var base_url = $('.demo').val();
            var location = $('.shipping option:selected').val();
            if(location == ''){
                Swal.fire({
                    icon: 'warning',
                    title: 'Select Location',
                    showConfirmButton: false,
                    timer: 1000
                });
            }else{
                var url = base_url+"/checkout?product_id=" + p_id + "&product_color=" +color_id + "&product_attr=" + encodeURIComponent(JSON.stringify(attr))+"&location="+location+"&qty=1";
                window.location.href = url;
            }
        
        });

        var owl = $('.related-carousel');
        owl.owlCarousel({
            margin: 30,
            loop: false,
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

    function increment() {
            document.getElementById('demoInput').stepUp();
        }
    function decrement() {
        document.getElementById('demoInput').stepDown();
    }

    function show_shipping_charges(){
        var shipping_charges = $('.shipping').children('option:selected').data('shipping');
        var product_shipping = $('.shipping').children('option:selected').data('p-ship');
        if(product_shipping != 'free'){
            if(shipping_charges != undefined && shipping_charges > -1){
                var row = '<p><span><b>Shipping Charges :</b> {{site_settings()->currency}}'+shipping_charges+'</span></p>';
            }
        }else{
            var row = '<p><span><b>Shipping Charges :</b> Free</span></p>';
        }
        $('.shipping-charges').html(row);
    }
    show_shipping_charges();

    $(document).on('change','.shipping',function(){
        show_shipping_charges();
    });

        
</script>
@stop