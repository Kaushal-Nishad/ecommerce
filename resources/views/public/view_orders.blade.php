@extends('public.layout')
@section('title','View Orders')
@section('content')
<div id="site-content">
    <div class="message"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            @if(!$my_orders->isEmpty())
                
                <table class="table table-bordered">
                    
                    <tbody class="cart-data">
                            <tr class="active">
                                <th colspan="4"><h5><b>ORDER No. :  {{'ODR00'.$orders->order_id}} </b></h5></th>
                                <th width="250px"><b>Order Placed : {{date('d M, Y',strtotime($orders->order_date))}}</b></th>
                            </tr>
                            @foreach($my_orders as $row)
                                <input type="hidden" name="order_id" value="{{$row->order_id}}">
                                @php
                                    $product_attrvalues = json_decode($row->attrvalues);
                                    $product_qty = json_decode($row->product_qty,true);
                                    $color_name = json_decode($row->color_code);
                                    $shipping_price = json_decode($row->shipping_price,true);
                                    $delivery_status = json_decode($row->delivery);
                                @endphp
                                <tr>
                                    <td><img class="img-thumbnail" src="{{asset('products/'.$row->thumbnail_img)}}" alt="" width="100px"></td>
                                    <td>
                                        <span><b>Product Name :</b> {{$row->product_name}}</span><br>
                                        <span><b>Product Price :</b> ${{$row->taxable_price}}</span><br>
                                        <span>
                                            <b>Shipping Charges :</b>
                                                
                                        </span><br>
                                        <span>
                                            <b>Quantity :</b>
                                                @foreach($product_qty as $key => $value)
                                                    @if($key == $row->p_id)
                                                        {{$value}}
                                                    @endif
                                                @endforeach
                                        </span><br>
                                    </td>
                                    <td>
                                        @foreach($delivery_status as $key => $value)
                                            @if($key == $row->p_id)
                                                @if($value == '1')
                                                    @php  $status = '<label class="badge badge-success">Delivered</label>'; @endphp
                                                @else
                                                    @php  $status = '<label class="badge badge-primary">In - Process</label>'; @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                        <b>Status :</b> @php  echo $status; @endphp
                                    </td>
                                    <td>
                                        <b>Sub Total :</b> {{$row->taxable_price*$product_qty[$row->p_id]}}
                                    </td>
                                    <td>
                                        @php 
                                            $date = $row->order_date;
                                            $totalDuration = +$row->shipping_days ;
                                        @endphp
                                        @foreach($delivery_status as $key => $value)
                                            @if($key == $row->p_id)
                                                @if($value == '1')
                                                    <b>Delivered On : </b>{{date('d F, Y',strtotime($row->updated_at))}}<br>
                                                    <a href="{{url('review/create/'.$row->p_id)}}" class="btn btn-primary btn-sm">Write a product review</a>
                                                @else
                                                    <b>Delivery Expected By : </b>{{date('d F, Y',strtotime($date.$totalDuration.'day'))}}  
                                                @endif
                                            @endif
                                        @endforeach
                                        
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" align="right"><b>Total Amount ($)</b></td>
                                <td>{{$row->total_amount}}</td>
                            </tr>
                    </tbody>
                </table>
                
            @else
                <div class="content-box text-center">
                    <p class="m-0">No Orders Found</p>
                </div>
            @endif
            </div>
        </div>
    </div>
</div>
@stop
