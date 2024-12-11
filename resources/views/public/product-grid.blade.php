<div class="product-grid">
    <div class="product-image">
        <a href="{{url('/product/'.$item->slug)}}" class="image">
            @if($item->thumbnail_img != '')
            <img class="pic-1" src="{{asset('products/'.$item->thumbnail_img)}}">
            @else
            <img class="pic-1" src="{{asset('products/default.png')}}">
            @endif
        </a>
        <ul class="product-links">
            @if(Session::has('user_id'))
            <li><a href="javascript:void(0)" class="addwishlist" data-tip="Add to Wishlist" data-id="{{$item->id}}"><i class="far fa-heart"></i></a></li>
            @else
            <li><a href="{{url('user_login')}}" data-tip="Add to Wishlist" data-id="{{$item->id}}"><i class="far fa-heart"></i></a></li>
            @endif
        </ul>
        @php $product_price = get_product_price($item->id); @endphp
        @if($product_price->discount != '')
        <span class="product-discount-label">-{{$product_price->discount}}</span>
        @endif
    </div>
    <div class="product-content">
        <span class="category">{{$item->brand_name}}</span>
        @php $product_rating = product_rating($item->id);  @endphp
        <ul class="rating show-review-rating">
        @php $rating = 0;  @endphp
        @if($product_rating->rating_col > 0)
            @php $rating = ceil($product_rating->rating_sum/$product_rating->rating_col);  @endphp  
        @endif
        @for($i=1;$i<=5;$i++)
            @if($i <= $rating)
                <li class="fa fa-star"></li>
            @else
                <li class="far fa-star"></li>
            @endif
        @endfor
        </ul>
        <h3 class="title"><a href="{{url('/product/'.$item->slug)}}">{{substr($item->product_name,0,25).'...'}}</a></h3>
        
        @if($product_price->discount != '')
            <span class="old-price">{{site_settings()->currency}}{{$product_price->old_price}}</span>
        @endif
        <span class="price">{{site_settings()->currency}}{{$product_price->new_price}}</span>
    </div>
</div>