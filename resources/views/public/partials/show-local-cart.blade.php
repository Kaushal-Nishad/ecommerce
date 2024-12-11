<table class="table table-bordered cart-data">
    <thead>
        <th>Product</th>
        <th>Price</th>
        <th>Qty</th>
        <th>Total</th>
        <th>Remove</th>
    </thead>
    <tbody>
        @foreach($products as $product)
            <input type="hidden" name="product_id[]" value="{{$product->id}}">
            <input type="hidden" name="product_attr[{{$product->id}}]" value="{{$product->attrvalues}}">
            <input type="hidden" name="product_color[{{$product->id}}]" value="{{$product->color}}">
            @if($product->shipping_charges == 'free')
                @php $shipping =0; @endphp
            @else
                @php
                    $shipping = \App\Models\City::where('id',session()->get('user_city'))->pluck('cost_city')->first();
                @endphp
            @endif
            <tr>
                <td class="d-flex flex-row">
                    <img class="pic-1" src="{{asset('products/'.$product->thumbnail_img)}}" alt="" width="70px">
                    <div class="ml-2">{{$product->product_name}}
                        @if($product->color_code != '')
                            <span><b>Color : </b> <label for="color{{$product->id}}" style="background-color:{{$product->color_code}};cursor:auto;"></label></span>
                        @endif
                        @if($product->attrvalues != '')
                            @php
                                echo '<ul>';
                                $p_attr = array_filter(explode(',',$product->attrvalues));
                                for($i=0;$i<count($p_attr);$i++){
                                    $atr_val = array_filter(explode(':',$p_attr[$i]));
                                    echo '<li>';
                                    foreach($attributes as $attr_array){
                                        if($attr_array->id == $atr_val[0]){
                                            echo '<span><b>'.$attr_array->title.':</b></span>';
                                        }
                                    }
                                    foreach($attrvalues as $attr_vals){
                                        if($attr_vals->id == $atr_val[1] && $atr_val[0] == $attr_vals->attribute){
                                            echo ' <span>'.$attr_vals->value.'</span>';
                                        }
                                    }
                                    echo '</li>';
                                }
                                echo '</ul>';
                            @endphp
                        @endif
                        @if($shipping == '0')
                        <span>Free Delivery</span>
                        @else
                            <span>Delivery Charges : {{site_settings()->currency}}{{$shipping}}</span>
                        @endif
                    </div>
                </td>
                <td>
                    {{site_settings()->currency}}{{get_product_price($product->id)->new_price}}
                </td>
                <td>
                    <input type="number" class="form-control item-qty" min='1' name="qty[{{$product->id}}]" style="width: 80px;" value="1">
                    <input type="number" class="product-price" name="price[{{$product->id}}]" value="{{get_product_price($product->id)->new_price}}" hidden>
                    
                    <input type="number" class="product-shipping" value="{{$shipping}}" hidden>
                </td>
                <td>
                    {{site_settings()->currency}}<span class="product-total">{{get_product_price($product->id)->new_price + $shipping}}</span>
                </td>
                <td>
                    <button type="button" class="btn btn-danger remove-cart" data-type="local" data-id="{{$product->cart_id}}"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
        @endforeach
        <tr>
            <td colspan="3" align="right"><b>Total Amount</b></td>
            <td class="">{{site_settings()->currency}}<span class="total-amount"></span></td>
        </tr>
    </tbody>
</table>
<a class="btn btn-primary" href="{{url('/')}}">Continue Shopping</a>
<input type="submit" class="btn btn-success float-right" name="checkout" value="Proceed to Checkout">
