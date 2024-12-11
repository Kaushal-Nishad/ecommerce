<!------ FOOTER-WIDGET ------>
<div class="footer-widget">
    <div class="container-xl container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                <div class="site-info-widget">
                    <div class="footer-logo">
                        <a href="{{url('/')}}"><h6>{{site_settings()->site_name}}</h6></a>
                    </div>
                    <p>{{site_settings()->description}}</p>
                </div>
                
            </div>
            <div class="col-lg-3 col-md-6 pl-lg-5 pl-lg-0 mb-4 mb-lg-0">
                <div class="widget-box">
                    <h6 class="widget-title">Categories</h6>
                    <ul class="widget-list">
                        @foreach(all_category() as $f_cat)
                        @if($f_cat->parent_category == '0')
                        <li><a href="{{url('c/'.$f_cat->category_slug)}}"><i class="fa fa-angle-right" aria-hidden="true"></i> {{$f_cat->category_name}}</a></li>
                        @endif
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 pl-lg-5 pl-lg-0 mb-4 mb-lg-0">
                <div class="widget-box">
                    <h6 class="widget-title">Links</h6>
                    <ul class="widget-list">
                        @foreach(site_pages() as $pages)
                        <li><a href="{{$pages->page_slug}}"><i class="fa fa-angle-right" aria-hidden="true"></i> {{$pages->page_title}}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @if(site_settings()->address != '' || site_settings()->phone != '' || site_settings()->email != '')
            <div class="col-lg-3 col-md-6 d-flex justify-content-left justify-content-lg-center">
                <div class="contact-widget">
                    <h6 class="widget-title">Contact Us</h6>
                    <ul class="contact-list">
                        @if(site_settings()->address != '')
                        <li>
                            <span class="icon"><i class="fas fa-map-marker-alt"></i></span>
                            <span><b>Address: </b>{{site_settings()->address}}</span>
                        </li>
                        @endif
                        @if(site_settings()->email != '')
                        <li>
                            <span class="icon"><i class="fas fa-envelope"></i></span>
                            <span><b>Email: </b>{{site_settings()->email}}</span>
                        </li>
                        @endif
                        @if(site_settings()->phone != '')
                        <li>
                            <span class="icon"><i class="fas fa-phone-alt"></i></span>
                            <span><b>Contact Us: </b>{{site_settings()->phone}}</span>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
<!------/FOOTER-WIDGET------>

<!------ FOOTER ------>
<div class="footer-bottom py-2">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 col-12 align-self-center">
                <input type="hidden" class="demo" value="{{url('/')}}"></input>
                <span>{{site_settings()->copyright}}</span>
            </div>
            <div class="col-md-6 col-12">
                <ul class="social-links">
                    @if(social_links()->facebook != '')
                    <li>
                        <a href="{{social_links()->facebook}}" class="facebook"><i class="fab fa-facebook-f"></i></a>
                    </li>
                    @endif
                    @if(social_links()->twitter != '')
                    <li>
                        <a href="{{social_links()->twitter}}" class="twitter"><i class="fab fa-twitter"></i></a>
                    </li>
                    @endif
                    @if(social_links()->instagram != '')
                    <li>
                        <a href="{{social_links()->instagram}}" class="instagram"><i class="fab fa-instagram"></i></a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
        
    </div>
</div>
<!------/FOOTER------>

<script src="{{asset('assets/js/jquery.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
<script src="{{asset('assets/js/jquery.flexslider.js')}}"></script>
<script src="{{asset('assets/js/price-range.js')}}"></script>
<script src="{{asset('assets/js/jquery-ui.js')}}"></script>
<script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/js/sweetalert2.min.js')}}"></script>
<script src="https://unpkg.com/smartwizard@6/dist/js/jquery.smartWizard.min.js" type="text/javascript"></script>

<!-- <script src="{{asset('assets/js/main_ajax.js')}}"></script> -->
<script src="{{asset('assets/js/action.js')}}"></script>
<?php if(!(session()->has('user_name'))){ ?>
<!-- <script src="{{asset('assets/js/addcart.js')}}"></script> -->
<script>
    
</script>
<?php } ?>
<script type="text/javascript">
    $(document).ready(function(){
            
        $('.flexslider').flexslider({
            animation: "slide",
            controlNav: "thumbnails",
            start: function(slider){
                $('body').removeClass('loading');
            }
        });

        $('.navbar-light .dmenu').hover(function () {
            $(this).find('.sm-menu').first().stop(true, true).slideDown(300);
            }, function () {
            $(this).find('.sm-menu').first().stop(true, true).slideUp(300)
        });
        
        $('.select2').select2();
    
    });
</script>
@yield('pageJsScripts')
</body>
</html>