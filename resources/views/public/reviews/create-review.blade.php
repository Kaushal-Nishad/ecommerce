@extends('public.layout')
@section('title','Create Review')
@section('content')
<div id="site-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-heading">
                    <h3 class="title">Create Review</h3>
                    
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <form id="createReview" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="">Add a Headline</label>
                                <input type="text" class="form-control" name="title">
                            </div>
                            <div class="form-group">
                                <label for="">Write your review</label>
                                <textarea name="review" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="">Overall Raing</label>
                                <ul class="review-rating">
                                    <li>
                                        <input class="star star-1" value="1" id="star-1" type="radio" name="star"/>
                                        <label class="star star-1" for="star-1"></label> 
                                    </li>
                                    <li>
                                        <input class="star star-2" value="2" id="star-2" type="radio" name="star"/>
                                        <label class="star star-2" for="star-2"></label>
                                    </li>
                                    <li>
                                        <input class="star star-3" value="3" id="star-3" type="radio" name="star"/>
                                        <label class="star star-3" for="star-3"></label>
                                    </li>
                                    <li>
                                        <input class="star star-4" value="4" id="star-4" type="radio" name="star"/>
                                        <label class="star star-4" for="star-4"></label>
                                    </li>
                                    <li>
                                        <input class="star star-5" value="5" id="star-5" type="radio" name="star"/>
                                        <label class="star star-5" for="star-5"></label>
                                    </li>
                                    
                                </ul>
                            </div>
                            <input type="text" hidden name="product" value="{{$product->id}}">
                            <input type="text" hidden name="user" value="{{Session::get('user_id')}}">
                            <input type="submit" class="btn btn-sm btn-primary" name="submit-review" value="Submit">
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex flex-row">
                    <img src="{{asset('products/'.$product->thumbnail_img)}}" class="img-thumbnail" alt="{{$product->product_name}}" width="250px">
                    <div class="text-left px-3">
                        <h6>{{$product->product_name}}</h6>
                        <span>{{site_settings()->currency}}{{$product->taxable_price}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('pageJsScripts')


@stop