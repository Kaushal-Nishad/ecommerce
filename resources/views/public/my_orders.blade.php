@extends('public.layout')
@section('title','My Orders')
@section('content')
<div id="site-content">
    <div class="message"></div>
    <div class="container">
        <div class="section-heading">
            <h3 class="title">My Orders</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">My Orders</li>
            </ol>
        </div>
        <div class="row">
            <div class="col-md-8">
            @if(!$my_orders->isEmpty())
                <table class="table table-bordered table-striped">
                    <thead>
                        <th>Order No</th>
                        <th>Products</th>
                        <th>Order Placed</th>
                        <th>View</th>
                    </thead>
                    <tbody class="cart-data">
                        @foreach($my_orders as $row)
                        <tr class="active">
                            <td><a href="javascript:void(0)" class="show-product" data-id="{{$row->id}}">{{'ODR00'.$row->id}}</a></td>
                            <td>
                                @php $products = array_filter(explode('|||',$row->names)); @endphp
                                <ul>
                                    @for($i=0;$i<count($products);$i++)
                                        <li class="mb-2">{{substr($products[$i],0,25).'...'}}</li>
                                    @endfor
                                </ul>
                            </td>
                            <td>{{date('d M, Y',strtotime($row->created_at))}}</td>
                            <td><button class="btn btn-primary show-product" data-id="{{$row->id}}"><i class="fa fa-eye"></i></button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="content-box text-center">
                    <p class="m-0">No Orders Found</p>
                </div>
            @endif
            </div>
            <div class="col-md-4 show-product-content">
                
            </div>
        </div>
    </div>
</div>
@stop
