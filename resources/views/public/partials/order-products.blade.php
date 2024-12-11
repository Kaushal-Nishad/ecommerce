<table class="table table-bordered">
    <tr>
        <td>Order No : ODR00{{$order->id}}</td>
    </tr>
</table>
@foreach($products as $item)
    <div class="d-flex flex-row mb-3">
        <img src="{{asset('products/'.$item->thumbnail_img)}}" class="mr-2" width="90px">
        <div>
            <h6>{{substr($item->product_name,0,25).'...'}}</h6>
            <ul>
                <li>Qty : {{$item->product_qty}}</li>
                @if($item->product_color != '')
                @foreach($colors as $color)
                @if($color->id == $item->product_color)
                <li>Color : {{$color->color_name}}</li>
                @endif
                @endforeach
                @endif
                @if($item->product_attr != '')
                    @php $p_attr = array_filter(explode(',',$item->product_attr)); @endphp
                    @for($i=0;$i<count($p_attr);$i++)
                        @php $atr_val = array_filter(explode(':',$p_attr[$i])); @endphp
                        <li>
                            @foreach($attributes as $attr_array)
                            @if($attr_array->id == $atr_val[0])
                            <span><b>{{$attr_array->title}}:</b></span>
                            @endif
                            @endforeach
                            @foreach($attrvalues as $attr_vals)
                            @if($attr_vals->id == $atr_val[1] && $atr_val[0] == $attr_vals->attribute)
                            <span>{{$attr_vals->value}}</span>
                            @endif
                            @endforeach
                        </li>
                    @endfor
                @endif
                <li>Amount : {{site_settings()->currency}}{{$item->product_amount}}</li>
                @if($item->product_delivery == '0')
                    <li>Delivery : Pending </li>
                    <li>Expected Delivery : {{date('d M, Y',strtotime($item->created_at.'+'.$item->shipping_days.' days'))}}</li>
                @else
                    <li>Delivery : Delivered</li>
                    <li>Delivered On : {{date('d M, Y',strtotime($item->updated_at))}}</li>
                    @if(!\App\Models\Review::where('product',$item->product_id)->where('user',session()->get('user_id'))->pluck('id')->first())
                    <li><a href="{{url('review/create/'.$item->product_id)}}" class="btn btn-primary btn-sm">Write a product review</a></li>
                    @endif
                @endif
            </ul>
        </div>
    </div>
@endforeach
<table class="table table-bordered">
    <tr>
        <td>Total Products</td>
        <td>{{$order->qty}}</td>
    </tr>
    <tr>
        <td>Total Amount</td>
        <th>{{site_settings()->currency}}{{$order->amount}}</th>
    </tr>
</table>