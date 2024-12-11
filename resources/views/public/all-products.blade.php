@extends('public.layout')
@section('title','Search')
@section('content')
<div id="site-content">
    <div class="container">
        <form class="row" action="{{url(Request::url())}}">
            <div class="col-md-3">
                <!-- <form action=""> -->
                    <div class="filter">
                        <div class="filter-header">
                            <h4 class="title">Filter</h4>
                        </div>
                        <div class="filter-item">
                            @if(request()->get('keyword') && request()->get('keyword') !='')
                                <input type="text" hidden name="keyword" value="{{request()->get('keyword')}}">
                            @endif
                            <h5 class="title">Categories</h5>
                            <div class="dropdown">
                                <ul>
                                    <li class="category_name"><a href="{{url('search')}}">
                                        @if((!request()->get('keyword') || request()->get('keyword') == '') && !$cat_detail )
                                        <i class="fas fa-angle-right"></i>
                                        @endif
                                    All Categories
                                    </a></li>
                                    @if($cat_detail)
                                        <li class="category_name"><a href="{{url('c/'.$cat_array->category_slug)}}">
                                        @if($cat_detail->id == $cat_array->id)
                                        <i class="fas fa-angle-right"></i>
                                        @endif
                                        {{$cat_array->category_name}}</a></li>
                                        @if($cat_array->sub_category)
                                            @include('public.partials.child-category',['category'=>$cat_array->sub_category,'cat_detail'=>$cat_detail])
                                        @endif
                                    @else
                                        @foreach($cat_array as $row)
                                            <li class="category_name"><a href="{{url('c/'.$row->category_slug)}}">
                                            {{$row->category_name}}</a></li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <div class="filter-item">
                            <h5 class="title">Price</h5>
                            <div id="slider-range" class="price-filter-range" name="rangeInput" style="display:none;" ></div>

                            <div class="row">
                                <div class="col-md-6">
                                    <span class="d-block">Min</span>
                                    @php 
                                    $min_price = 0;
                                    if(request()->get('min_price') && request()->get('min_price') != ''){
                                        $min_price = request()->get('min_price');
                                    }                                        
                                    @endphp
                                    <input type="number" name="min_price" min=0 max="1000000" oninput="validity.valid||(value='0');" class="price-range-field" value="{{$min_price}}" />
                                </div>
                                <div class="col-md-6">
                                    <span class="d-block">Max</span>
                                    @php 
                                    $max_price = 1000000;
                                    if(request()->get('max_price') && request()->get('max_price') != ''){
                                        $max_price = request()->get('max_price');
                                    }    
                                    @endphp
                                    <input type="number" name="max_price" min=0 max=1000000 class="price-range-field" value="{{$max_price}}" />
                                </div>
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-primary btn-sm mt-2" onclick="form.submit()">Apply</button>
                                </div>
                            </div>
                        </div>
                        @if($brands)
                        <div class="filter-item">
                            <h5 class="title">Brands</h5>
                            @foreach($brands as $item)
                                <div class="radio-button">
                                    @php 
                                    $select_brand = '';
                                    if(request()->get('brand') && request()->get('brand') != ''){
                                        $select_brand = ($item->id == request()->get('brand')) ? 'checked' : '';
                                    }                                        
                                    @endphp
                                    <input type="checkbox" class="brand" id="{{$item->id}}" name="brand" value="{{$item->id}}" {{$select_brand}}  onchange="form.submit()">
                                    <label for="{{$item->id}}">{{$item->brand_name}}</label>
                                </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                <!-- </form> -->
            </div>
            <div class="col-md-9">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                        @if(!empty($cat_detail))
                            @php
                            $breadcrumb_ids = get_category_breadcrumb($cat_detail->id);
                            $breadcrumb = \App\Models\Category::select(['id','category_name','category_slug'])->whereIn('id',$breadcrumb_ids)->orderBy('id','ASC')->get();
                            @endphp
                            @foreach($breadcrumb as $b_row)
                                    @if($b_row->id == $cat_detail->id)
                                    <li class="breadcrumb-item active">
                                    {{$b_row->category_name}}
                                    </li>
                                    @else
                                    <li class="breadcrumb-item">
                                        <a href="{{url('c/'.$b_row->category_slug)}}">{{$b_row->category_name}}</a>
                                    </li>
                                    @endif
                            @endforeach
                        @else
                        <li class="breadcrumb-item"><a href="{{url('all-products')}}">All Products</a></li>
                        @endif
                    </ol>
                </nav>
                <div class="content-box d-flex flex-row justify-content-between align-items-center">
                    <h5 class="title">
                        @if($cat_detail)
                            {{$cat_detail->category_name}}
                        @elseif(request()->get('keyword') && request()->get('keyword') != '')
                            {{request()->get('keyword')}}
                        @else
                            All Products
                        @endif
                    </h5>
                    <div class="d-flex flex-row">
                        <label for="" class="text-nowrap my-auto mr-2">Sort By</label>
                        @php $sort = ''; @endphp
                        @if(request()->sort && request()->sort != '')
                        @php $sort = request()->sort; @endphp
                        @endif
                        
                        <select name="sort" class="form-control" onChange="form.submit()">
                            <option value="latest" {{(($sort == 'latest') ? 'selected' : '')}}>Latest</option>
                            <option value="oldest" {{(($sort == 'oldest') ? 'selected' : '')}}>Oldest</option>
                            <option value="l-h" {{(($sort == 'l-h') ? 'selected' : '')}}>Price:Low to High</option>
                            <option value="h-l" {{(($sort == 'h-l') ? 'selected' : '')}}>Price:High to Low</option>
                        </select>
                    </div>
                </div>
                <div class="row search-res-list">
                    @if(!empty($products) && $products->isNotEmpty())
                        @foreach($products as $item)
                        <div class="col-md-4">
                            @include('public.product-grid',$item)
                        </div>
                        @endforeach
                    @else
                        <div class="col-12">
                            <div class="content-box text-center">
                                <p class="m-0">No Products Found</p>
                            </div>    
                        </div>
                    @endif
                    @if(!empty($products))
                    <div class="col-md-12">
                        {{$products->appends(request()->query())->links()}}
                    </div>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

@stop