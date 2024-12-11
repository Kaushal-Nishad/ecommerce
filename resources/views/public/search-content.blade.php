@if (sizeof($keywords) > 0)
<div>
    <ul class="list-group">
        @foreach ($keywords as $key => $keyword)
            <li class="list-group-item rounded-0 border-left-0 border-right-0 py-2 text-capitalize">
                <a href="{{ url('search?keyword='.ltrim($keyword)) }}">{{ $keyword }}</a>
            </li>
        @endforeach 
    </ul>
</div>
@endif
<div>
@if (count($categories) > 0)
    <small class="text-right font-italic p-1 d-block bg-light">Category</small>
    <ul class="list-group list-group-raw">
        @foreach ($categories as $key => $category)
            <li class="list-group-item rounded-0 border-left-0 border-right-0 py-2 text-capitalize">
                <a class="" href="{{ url('c/'.$category->category_slug) }}">{{ $category->category_name }}</a>
            </li>
        @endforeach
    </ul>
@endif
</div>