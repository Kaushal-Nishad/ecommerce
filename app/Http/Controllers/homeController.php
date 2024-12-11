<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Banner;
use App\Models\Users;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Attribute_value;
use App\Models\Attrvalue;
use App\Models\Cart;
use App\Models\City;
use App\Models\FlashDeal;
use App\Models\FlashProduct;
use App\Models\Review;
use App\Models\Page;
use Illuminate\Pagination\Paginator;

class homeController extends Controller
{
    public function index(){
        $banner = Banner::select(['banner.*'])->get();
        
        $flash_deals = FlashDeal::select(['flash_deals.*'])
                       ->orderBy('flash_deals.id','DESC')
                       ->where('status','1')
                       ->limit(3)
                       ->get();

        $new_products = Product::select(['products.id','products.product_name','products.gallery_img','products.thumbnail_img','products.slug','brands.brand_name'])
                        ->leftjoin('brands','brands.id','=','products.brand')
                        ->where('products.status','1')
                        ->where('products.quantity','>','1')
                        ->orderBy('products.id','DESC')
                        ->limit(10)
                        ->get();

        $flash_products = FlashProduct::select(['products.id','products.product_name','products.thumbnail_img','products.slug','brands.brand_name','flash_deals.flash_date_range'])
                          ->leftjoin('flash_deals','flash_deals.id','=','flash_products.deals_id')
                          ->leftjoin('products','products.id','=','flash_products.product_id')
                          ->leftjoin('brands','brands.id','=','products.brand')
                          ->where('products.status','1')
                          ->where('flash_deals.status','1')
                          ->orderBy('flash_products.id','DESC')
                          ->limit(10)
                          ->get();

        $today_deals = Product::select(['products.id','products.product_name','products.gallery_img','products.thumbnail_img','products.slug','brands.brand_name'])
                        ->leftjoin('brands','brands.id','=','products.brand')
                        ->where('products.status','1')
                        ->where('products.today_deal','1')
                        ->where('products.quantity','>','1')
                        ->orderBy('products.id','DESC')
                        ->limit(10)
                        ->get();

        return view('public.index',['banner'=>$banner,'flash_deals'=>$flash_deals,'flash_products'=>$flash_products,'latest_products'=>$new_products,'today_deal_products'=>$today_deals]);
    }

    public function todayDeals(){
        Paginator::useBootstrap();
        $today_deals = Product::select(['products.id','products.product_name','products.gallery_img','products.thumbnail_img','products.slug','brands.brand_name'])
                        ->leftjoin('brands','brands.id','=','products.brand')
                        ->where('products.status','1')
                        ->where('products.today_deal','1')
                        ->where('products.quantity','>','1')
                        ->orderBy('products.id','DESC')
                        ->paginate(12);
        return view('public.today',['today_products'=>$today_deals]);
    }



    public function productpage($text){
        $cart = [];
        if(session()->has('user_id')){
            $user = session()->get('user_id');
            $cart = Cart::where('product_user',$user)->pluck('product_id')->toArray();
        }
        $product = Product::select('products.*','brands.brand_name',DB::raw('COUNT(reviews.product) as rating_col'),DB::raw('SUM(reviews.rating) as rating_sum'))
                   ->leftJoin('brands','brands.id','=','products.brand')
                   ->leftJoin('reviews','products.id','=','reviews.product')
                   ->where('products.slug',$text)
                   ->first();

        $reviews = Review::select(['reviews.*','users.name'])
                   ->leftJoin('users','reviews.user','=','users.user_id')
                   ->where('reviews.product',$product->id)
                   ->where('reviews.approved','1')
                   ->where('reviews.hide_by_admin','0')
                   ->get();
                   
        $related = Product::select(['products.*','brands.brand_name',DB::raw('COUNT(reviews.product) as rating_col'),DB::raw('SUM(reviews.rating) as rating_sum')])
                   ->leftjoin('brands','brands.id','=','products.brand')
                   ->leftjoin('reviews','products.id','=','reviews.product')
                   ->where('products.category',$product->category)
                   ->where('products.status','1')
                   ->groupBy('products.id')
                    ->orderBy('products.id','DESC')
                    ->limit(10)
                    ->get();

        $colors = Color::select(['colors.*'])->get();
        $attrvalues = Attrvalue::select(['attrvalues.*'])->get();
        $attributes = Attribute_value::select(['attributes_values.*','attributes.title'])
                ->leftjoin('attributes','attributes.id','=','attributes_values.attribute_id')
                ->where(['attributes_values.product_id'=>$product->id])->groupBy('attributes_values.attrvalues')->get();
        $cities = City::select(['cities.*','states.state_name'])
                  ->leftjoin('states','states.id','=','cities.state')  
                  ->get();
        
        return view('public.product',['attrvalues'=>$attrvalues,'attributes'=>$attributes,'colors'=>$colors,'product'=>$product,'cities'=>$cities,'related'=>$related,'cart'=>$cart,'reviews'=>$reviews]);
    }

    public function search_products(Request $request,$slug = ''){

        Paginator::useBootstrap();
        $where = '';

        if ($request->keyword && $request->keyword != '') {
            $where .= "products.product_name LIKE '%{$request->keyword}%' OR products.tags LIKE '%{$request->keyword}%'";
        }
        if($request->sort == 'h-l'){
            $order = 'products.unit_price DESC';
        }else if($request->sort == 'l-h'){
            $order = 'products.unit_price ASC';
        }elseif($request->sort == 'oldest'){
            $order = 'products.id ASC';
        }else{
            $order = 'products.id DESC';
        }

        $brands = [];
        $cat_detail = [];

        $cat_array = Category::where('parent_category','0')->get();
        if($slug != ''){
            $cat_detail = Category::select('*')->where('categories.category_slug',$slug)->first();
            $cat_array =  get_parent_category($cat_detail);

            if(!$cat_detail && $slug != ''){
                return abort(404);
            }

            $brands = Brand::whereRaw("FIND_IN_SET({$cat_detail->id},brand_subcat)")->get();
            $child_array = $this->get_child_id($cat_detail->id,[]);
            $child = implode(',',array_filter($child_array));
            $where .= "products.category IN ({$child})";
        }else{
            // $cat_detail = Category::select('categories.*')
            //             ->whereRaw("products.tags LIKE '%{$request->keyword}%'")
            //             ->leftJoin('products','products.category','=','categories.id')->first();
            // $cat_array =  get_parent_category($cat_detail);

            // if(!$cat_detail && $slug != ''){
            //     return abort(404);
            // }

            // $brands = Brand::whereRaw("FIND_IN_SET({$cat_detail->id},brand_subcat)")->get();
            // $child_array = $this->get_child_id($cat_detail->id,[]);
            // $child = implode(',',array_filter($child_array));
            // $where .= "products.category IN ({$child})";
        }
        
        // return $child_array;

        if($request->min_price && $request->min_price != ''){
            if($where != ''){ $where .= ' AND '; }
            $where .= 'products.taxable_price>= '.$request->min_price; 
        }

        if($request->max_price && $request->max_price != ''){
            if($where != ''){ $where .= ' AND '; }
            $where .= 'products.taxable_price<= '.$request->max_price;
        }

        if($request->brand && $request->brand != ''){
            if($where != ''){ $where .= ' AND '; }
            $where .= 'products.brand= '.$request->brand;
        }
        // return $where;
        $limit = 9;
       
        if($where != ''){
            $products = Product::select(['products.*','brands.brand_name',DB::raw('COUNT(reviews.product) as rating_col'),DB::raw('SUM(reviews.rating) as rating_sum')])
                    ->leftJoin('brands','brands.id','=','products.brand')
                    ->leftJoin('reviews','reviews.product','=','products.id')
                    ->whereRaw($where)
                    ->groupBy('products.id')
                    ->orderByRaw($order)->paginate($limit);
        }else{
            $products = Product::select(['products.*','brands.brand_name'])
                    ->leftJoin('brands','brands.id','=','products.brand')
                    ->orderByRaw($order)->paginate($limit);
        }
        
        // return $cat_detail;
        return view('public.all-products',['slug'=>$slug,'cat_detail'=>$cat_detail,'cat_array'=>$cat_array,'products'=>$products,'brands'=>$brands,'limit'=>$limit]);
    }
    
    public function get_child_id($id,$ids){
        array_push($ids,$id);
        $child = Category::where('parent_category',$id)->get();
        if(!empty($child)){
            foreach($child as $row){
                $child_ids = $this->get_child_id($row->id,$ids);
                $ids = array_unique(array_merge($ids,$child_ids));
            }
        }
        return $ids;
    }


    // get products name in search box (auto suggestion)
    public function get_suggestions(Request $request){

        $keywords = array();
        $query = $request->search;
        $products = Product::where('status', 1)->where('tags', 'like', '%' . $query . '%')->groupBy('id')->get();
        foreach ($products as $key => $product) {
            foreach (explode(',', $product->tags) as $key => $tag) {
                if (stripos($tag, $query) !== false) {
                    if (sizeof($keywords) > 5) {
                        break;
                    } else {
                        if (!in_array(strtolower($tag), $keywords)) {
                            array_push($keywords, strtolower($tag));
                        }
                    }
                }
            }
        }
        $categories = Category::where('category_name', 'like', '%' . $query . '%')->get()->take(3);
        if (sizeof($keywords) > 0 || sizeof($categories) > 0) {
            $keywords = array_unique($keywords);
            return view('public.search-content', compact('categories', 'keywords'));
        }
        return '0';
    }

    public function allflashdeals(){
        Paginator::useBootstrap();
        $flash_deals = FlashDeal::select(['flash_deals.*'])
                        ->where('status','1')
                        ->orderBy('flash_deals.id','DESC')
                        ->paginate(8);
        return view('public.flash-deals',['flash_deals'=>$flash_deals]);
    }

    public function flashproducts($text){
        Paginator::useBootstrap();
        $flash_deal = FlashDeal::select(['flash_deals.*'])
                    ->where(['flash_deals.flash_slug'=>$text])
                    ->first();
        $flash_products = FlashProduct::select(['flash_products.*','products.id','products.product_name','products.taxable_price','products.thumbnail_img','products.slug','brands.brand_name','flash_deals.status','flash_deals.flash_date_range'])
                        ->leftjoin('flash_deals','flash_deals.id','=','flash_products.deals_id')
                        ->leftjoin('products','products.id','=','flash_products.product_id')
                        ->leftjoin('brands','brands.id','=','products.brand')
                        ->where('flash_products.deals_id',$flash_deal->id)
                        ->where('flash_deals.status','1')
                        ->orderBy('flash_products.id','DESC')
                        ->paginate(8);
        return view('public.flash-products',['flash_deal'=>$flash_deal,'flash_products'=>$flash_products]);
    }

    public function allflashproducts(){
        Paginator::useBootstrap();
        $flash_products = FlashProduct::select(['flash_products.*','products.id','products.product_name','products.taxable_price','products.thumbnail_img','products.slug','brands.brand_name','flash_deals.status','flash_deals.flash_date_range'])
                          ->leftjoin('flash_deals','flash_deals.id','=','flash_products.deals_id')
                          ->leftjoin('products','products.id','=','flash_products.product_id')
                          ->leftjoin('brands','brands.id','=','products.brand')
                          ->orderBy('flash_products.id','DESC')
                          ->paginate(8);
        return view('public.flash-products',['flash_products'=>$flash_products]);
    }

    public function site_pages($slug){
        $page = Page::where('page_slug',$slug)->first();
        if($page){
            return view('public.single',compact('page'));
        }else{
            return abort('404');
        }
    }

}
