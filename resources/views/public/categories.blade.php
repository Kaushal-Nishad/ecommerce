@extends('public.layout')
@section('title','Category')
@section('content')
<div id="site-content">
    <div class="container">
        <form class="row" action="{{url('c/'.$cat_detail->category_slug)}}">
            <div class="col-md-3">
                <!-- <form action=""> -->
                    <div class="filter">
                        <div class="filter-header">
                            <h4 class="title">Filter</h4>
                        </div>
                        <div class="filter-item">
                            <h5 class="title">Categories</h5>
                            <div class="dropdown">
                                <ul>
                                @foreach(all_category() as $cat_list)
                                @if($cat_list->id == $cat_detail->parent_category)
                                    <li class="category_name">
                                        <a href="{{url('c/'.$cat_list->category_slug)}}">
                                            @if($cat_list->category_slug == $slug)
                                            <i class="fas fa-angle-left"></i> 
                                            @endif
                                            {{$cat_list->category_name}}
                                        </a>
                                    </li>
                                    <ul class="subcategory-list">
                                        @foreach(all_category() as $sub_cat)
                                        @if($sub_cat->parent_category == $cat_list->id)
                                        <li><a href="{{url('c/'.$sub_cat->category_slug)}}" class="">
                                            @if($sub_cat->category_slug == $slug)
                                            <i class="fas fa-angle-left"></i> 
                                            @endif{{$sub_cat->category_name}}</a></li>
                                            @if($sub_cat->category_slug == $slug)
                                            <ul class="subcategory-list">
                                                @foreach(all_category() as $types)
                                                @if($types->parent_category == $sub_cat->id)
                                                <li><a href="{{url('c/'.$types->category_slug)}}" class="">
                                                    @if($types->category_slug == $slug)
                                                    <i class="fas fa-angle-left"></i> 
                                                    @endif{{$types->category_name}}</a></li>
                                                @endif
                                                @endforeach
                                            </ul>
                                            @endif
                                        @endif
                                        @endforeach
                                    </ul>
                                @endif
                                @endforeach
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
                        @if($brands->isNotEmpty($brands))
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
                        @if($cat_detail->parent_name != '')
                            <li class="breadcrumb-item"><a href="{{url('c/'.$cat_detail->parent_slug)}}">{{$cat_detail->parent_name}}</a></li>
                            <li class="breadcrumb-item active">{{$cat_detail->category_name}}</li>
                        @else
                            <li class="breadcrumb-item active">{{$cat_detail->category_name}}</li>
                        @endif
                    </ol>
                </nav>
                <!-- <form action=""> -->
                <div class="content-box d-flex flex-row justify-content-between align-items-center">
                        <h5 class="title">{{$cat_detail->category_name}} 
                        <!-- <span>(Showing 1 - 10 products of {{$productsCount}} products)</span> -->
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
                <!-- </form> -->
                <div class="row search-res-list">
                    @if(!empty($products))
                    @foreach($products as $item)
                        <div class="col-md-4">
                            <div class="product-grid">
                                <div class="product-image">
                                    <a href="{{url('product/'.$item->id)}}" class="image">
                                        <img class="pic-1" src="{{asset('products/'.$item->thumbnail_img)}}">
                                    </a>
                                    <ul class="product-links">
                                        <li><a href="#" data-tip="Add to Wishlist"><i class="far fa-heart"></i></a></li>
                                    </ul>
                                </div>
                                <div class="product-content">
                                    <span class="category"><a href="">{{$item->brand_name}}</a></span>
                                    <ul class="rating">
                                        <li class="fas fa-star"></li>
                                        <li class="fas fa-star"></li>
                                        <li class="fas fa-star"></li>
                                        <li class="fas fa-star"></li>
                                        <li class="far fa-star"></li>
                                    </ul>
                                    <h3 class="title"><a href="{{url('product/'.$item->slug)}}">{{substr($item->product_name,0,25).'...'}}</a></h3>
                                    <div class="price">${{$item->taxable_price}}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @endif
                    <div class="col-md-12">
                        {{$products->appends(request()->query())->links()}}
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@stop