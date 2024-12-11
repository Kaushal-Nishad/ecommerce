@extends('public.layout')
@section('title',$page->page_title)
@section('content')
<div class="product-box">
  <div class="message"></div>
  <div class="container">
    <div class="section-heading">
        <h3 class="title">{{$page->page_title}}</h3>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{$page->page_title}}</li>
        </ol>
    </div>
    <div class="row">
        <div class="col-md-12">
            {!!htmlspecialchars_decode($page->description)!!}
        </div>
    </div>
  </div>
</div>
@stop