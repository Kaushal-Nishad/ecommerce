<ul class="subcategory-list">
    @foreach($category as $sub_row)
        <li><a href="{{$sub_row->category_slug}}">
        @if($cat_detail->id == $sub_row->id)
        <i class="fas fa-angle-right"></i>
        @endif
        {{$sub_row->category_name}}</a></li>
        @if($sub_row->sub_category)
            @include('public.partials.child-category',['category'=>$sub_row->sub_category,'cat_detail'=>$cat_detail])
        @endif
    @endforeach
</ul>
