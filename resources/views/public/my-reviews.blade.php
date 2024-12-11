@extends('public.layout')
@section('title','My Reviews')
@section('content')
<div id="site-content">
    <div class="message"></div>
    <div class="container">
        <div class="section-heading">
            <h3 class="title">My Reviews</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">My Reviews</li>
            </ol>
        </div>
        <div class="row">
            <div class="col-md-12">
            @foreach($reviews as $row)
            <div class="card mb-4">
                <h5 class="card-header">{{$row->product_name}}</h5>
                <div class="card-body">
                    <h5>{{$row->title}}</h5>
                    <p>{{$row->desc}}</p>
                    <ul class="show-review-rating mb-2">
                        @for($i=1;$i<=5;$i++)
                            @if($i <= $row->rating)
                                <li class="fa fa-star"></li>
                            @else
                                <li class="far fa-star"></li>
                            @endif
                        @endfor
                    </ul>
                    @if($row->hide_by_admin == '1')
                    <div class="alert alert-danger p-2 py-0 m-0 d-inline-block">Hidden by Admin</div>
                    @endif
                    @if($row->approved == '0')
                    <div class="alert alert-danger p-2 py-0 m-0 d-inline-block">Under Approval Process</div>
                    @endif
                </div>
            </div>
            @endforeach
            </div>
        </div>
    </div>
</div>
@stop
