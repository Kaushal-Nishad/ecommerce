@extends('public.layout')
@section('title','Checkout')
@section('content')
<div id="site-content">
    <div class="message"></div>
    <div class="container">
        <div class="row">
            <div class="col-12">
                @if(Session::has('error'))
                    <p class="alert alert-danger">{{Session::get('error')}}</p>
                @endif
                @if(Session::has('success'))
                    <p class="alert alert-success">{{Session::get('success')}}</p>
                @endif
                <!-- <form action=""></form> -->
                <div id="smartwizard">
                    <ul class="nav">
                        <li class="nav-item">
                        <a class="nav-link" href="#step-1">
                            <div class="num">1</div>
                            Delivery Address
                        </a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="#step-2">
                            <span class="num">2</span>
                            Product Details
                        </a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="#step-3">
                            <span class="num">3</span>
                            Confirm Order
                        </a>
                        </li>
                    </ul>
                
                    <div class="tab-content">
                        <div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1">
                            <form id="form-1" class="content-box row mb-0">
                                <div class="col-md-6 form-group">
                                    <label class="col-form-label">Name : </label>
                                    <input type="text" class="form-control" name="name" value="{{$user->name}}" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="col-form-label">Phone No. : </label>
                                    <input type="number" class="form-control" name="phone" value="{{$user->phone}}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="col-form-label">Country : </label>
                                    <select class="form-control select-country" name="country" required>
                                        <option value="" disabled selected>Select Country</option>
                                        @if(!empty($country))
                                            @foreach($country as $countries)
                                                @php $selected = ($countries->id == $user->country) ? 'selected' : ''; @endphp
                                                <option value="{{$countries->id}}" data-country="{{$countries->id}}" {{$selected}}>{{$countries->country_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label  class="col-form-label">State : </label>
                                    <select class="form-control select-state" name="state" id="state" required>
                                        <option value="" disabled>First Select Country</option>
                                        @if(!empty($state))
                                            @foreach($state as $states)
                                                @php $selected = ($states->id == $user->state) ? 'selected' : ''; @endphp
                                                <option value="{{$states->id}}" data-state="{{$states->id}}" {{$selected}}>{{$states->state_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="col-form-label">City : </label>
                                    <select class="form-control checkout-city" name="city" id="city" required>
                                        <option value="" disabled>First Select State</option>
                                        @if(!empty($city))
                                            @foreach($city as $cities)
                                                @if($user->city != '')
                                                    @php $selected = ($cities->id == $user->city) ? 'selected' : ''; @endphp
                                                @elseif(request()->get('location') && request()->get('location') != '')
                                                    @php $selected = ($cities->id == request()->get('location')) ? 'selected' : ''; @endphp
                                                @endif
                                                <option value="{{$cities->id}}" {{$selected}}>{{$cities->city_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="col-form-label">Address : </label>
                                    @if($user->address != '')
                                        <input type="text" class="form-control" name="address" value="{{$user->address}}" required>
                                    @else
                                        <input type="text" class="form-control" name="address" value="" required>
                                    @endif
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="col-form-label">Pin Code : </label>
                                        @if($user->pin_code != '')
                                            <input type="number" class="form-control" name="code" value="{{$user->pin_code}}" required>
                                        @else
                                            <input type="number" class="form-control" name="code" value="" required>
                                        @endif
                                </div>
                            </form>
                        </div>
                        <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2">
                            <form id="form-2" class="content-box">
                                <table class="table table-bordered">
                                    <thead>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                    </thead>
                                    <tbody>
                                        @php $total = 0; @endphp
                                        @foreach($products as $product)
                                            <input type="hidden" name="product_id[]" value="{{$product->id}}">
                                            @if(count((array) request()->get('product_attr')) > 1)
                                            <input type="hidden" name="product_attr[{{$product->id}}]" value="{{request()->get('product_attr')[$product->id]}}">
                                            @else
                                                @php
                                                if(is_array(request()->get('product_attr'))){
                                                    $product_attr = (array) request()->get('product_attr');
                                                }else{
                                                    $product_attr = (array) json_decode(request()->get('product_attr'));
                                                }
                                                @endphp
                                                @if($product_attr)
                                                <input type="hidden" name="product_attr[{{$product->id}}]" value="{{$product_attr[$product->id]}}">
                                                @endif
                                            @endif
                                            @if(is_array(request()->get('product_color')))
                                            <input type="hidden" name="product_color[{{$product->id}}]" value="{{request()->get('product_color')[$product->id]}}">
                                            @else
                                            <input type="hidden" name="product_color[{{$product->id}}]" value="{{request()->get('product_color')}}">

                                            @endif
                                            @if($product->shipping_charges == 'free')
                                                @php $shipping =0; @endphp
                                            @else
                                                @php
                                                    $city = \App\Models\Users::where('user_id',session()->get('user_id'))->pluck('city')->first();
                                                    $shipping = \App\Models\City::where('id',$city)->pluck('cost_city')->first();
                                                @endphp
                                            @endif
                                            <tr>
                                                <td class="d-flex flex-row">
                                                    <img class="pic-1" src="{{asset('products/'.$product->thumbnail_img)}}" alt="" width="70px">
                                                    <div class="ml-2">{{$product->product_name}}
                                                        @if($product->color_code != '')
                                                            <span><b>Color : </b> <label for="color{{$product->id}}" style="background-color:{{$product->color_code}};cursor:auto;"></label></span>
                                                        @endif
                                                        @if($product->attrvalues != '')
                                                            @php
                                                                echo '<ul>';
                                                                $p_attr = array_filter(explode(',',$product->attrvalues));
                                                                for($i=0;$i<count($p_attr);$i++){
                                                                    $atr_val = array_filter(explode(':',$p_attr[$i]));
                                                                    echo '<li>';
                                                                    foreach($attributes as $attr_array){
                                                                        if($attr_array->id == $atr_val[0]){
                                                                            echo '<span><b>'.$attr_array->title.':</b></span>';
                                                                        }
                                                                    }
                                                                    foreach($attrvalues as $attr_vals){
                                                                        if($attr_vals->id == $atr_val[1] && $atr_val[0] == $attr_vals->attribute){
                                                                            echo ' <span>'.$attr_vals->value.'</span>';
                                                                        }
                                                                    }
                                                                    echo '</li>';
                                                                }
                                                                echo '</ul>';
                                                            @endphp
                                                        @endif
                                                        @if($shipping == '0')
                                                            </br><span>Free Delivery</span>
                                                        @else
                                                            </br><span>Delivery Charges : {{site_settings()->currency}}{{$shipping}}</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    {{site_settings()->currency}}{{get_product_price($product->id)->new_price}}
                                                </td>
                                                <td>
                                                    @if(is_array(request()->get('qty')))
                                                    <input type="number" class="form-control item-qty" style="width:80px;" min='1' name="qty[{{$product->id}}]"value="{{request()->get('qty')[$product->id]}}">
                                                    @else
                                                    <input type="number" class="form-control item-qty" style="width:80px;" min='1' name="qty[{{$product->id}}]"value="{{request()->get('qty')}}">
                                                    @endif

                                                    <input type="number" class="product-price" name="price[{{$product->id}}]" value="{{get_product_price($product->id)->new_price}}" hidden>
                                                    
                                                    <input type="number" class="product-shipping" value="{{$shipping}}" hidden>
                                                </td>
                                                @if(is_array(request()->get('qty')))
                                                    <td>
                                                    {{site_settings()->currency}}<span class="product-total">{{(get_product_price($product->id)->new_price*request()->get('qty')[$product->id]) + $shipping}}</span>
                                                    </td>
                                                    @php $total += (get_product_price($product->id)->new_price*request()->get('qty')[$product->id]) + $shipping;  @endphp
                                                @else
                                                <td>
                                                    {{site_settings()->currency}}<span class="product-total">{{(get_product_price($product->id)->new_price*request()->get('qty')) + $shipping}}</span>
                                                    @php $total += (get_product_price($product->id)->new_price*request()->get('qty')) + $shipping;  @endphp
                                                @endif
                                                </td>
                                            </tr>
                                            
                                        @endforeach
                                            <tr>
                                                <td colspan="3" align="right"><b>Total Amount</b></td>
                                                <td class="">{{site_settings()->currency}}<span class="total-amount">{{$total}}</span></td>
                                            </tr>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                        <div id="step-3" class="tab-pane" role="tabpanel" aria-labelledby="step-3">
                            <form id="from-3">
                                <div class="card">
                                    <div class="card-header">
                                        Payment
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group mb-3">
                                        @foreach($payment_method as $pay_button)
                                            @if($pay_button->payment_name == 'Paypal' && $pay_button->payment_status == '1')
                                            <li class="list-group-item">
                                                <input type="radio" name="pay_method" value="paypal" required>
                                                <img src="{{asset('images/paypal.png')}}" alt="" height="20px">
                                            </li>
                                            @endif
                                            @if($pay_button->payment_name == 'Razorpay' && $pay_button->payment_status == '1')
                                            <li class="list-group-item">
                                                <input type="radio" name="pay_method" value="razorpay" required>
                                                <img src="{{asset('images/razorpay.png')}}" alt="" height="20px">
                                                <input type="text" hidden name="razor_key" value="{{env('RAZOR_KEY')}}">
                                            </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@stop
@section('pageJsScripts')
<script src="https://checkout.razorpay.com/v1/checkout.js" type="text/javascript"></script>
<script>
    $(function(){
        var uRL = $('meta[name=site-url]').attr('content');

            // Leave step event is used for validating the forms
            $("#smartwizard").on("leaveStep", function(e, anchorObject, currentStepIdx, nextStepIdx, stepDirection) {
                // Validate only on forward movement  
                if (stepDirection == 'forward') {
                  let form = document.getElementById('form-' + (currentStepIdx + 1));
                  if (form) {
                    if (!form.checkValidity()) {
                      form.classList.add('was-validated');
                      $('#smartwizard').smartWizard("setState", [currentStepIdx], 'error');
                      $("#smartwizard").smartWizard('fixHeight');
                      return false;
                    }
                    $('#smartwizard').smartWizard("unsetState", [currentStepIdx], 'error');
                  }
                }
            });

            // Step show event
            $("#smartwizard").on("showStep", function(e, anchorObject, stepIndex, stepDirection, stepPosition) {
                $("#prev-btn").removeClass('disabled').prop('disabled', false);
                $("#next-btn").removeClass('disabled').prop('disabled', false);
                if(stepPosition === 'first') {
                    $("#prev-btn").addClass('disabled').prop('disabled', true);
                } else if(stepPosition === 'last') {
                    $("#next-btn").addClass('disabled').prop('disabled', true);
                } else {
                    $("#prev-btn").removeClass('disabled').prop('disabled', false);
                    $("#next-btn").removeClass('disabled').prop('disabled', false);
                }

                // Get step info from Smart Wizard
                let stepInfo = $('#smartwizard').smartWizard("getStepInfo");
                $("#sw-current-step").text(stepInfo.currentStep + 1);
                $("#sw-total-step").text(stepInfo.totalSteps);

                if (stepPosition == 'last') {
                //   showConfirm();
                  $("#btnFinish").prop('disabled', false);
                } else {
                  $("#btnFinish").prop('disabled', true);
                }

                // Focus first name
                if (stepIndex == 1) {
                  setTimeout(() => {
                    $('#first-name').focus();
                  }, 0);
                }
            });

            // Smart Wizard
            $('#smartwizard').smartWizard({
                selected: 0,
                // autoAdjustHeight: false,
                theme: 'round', // basic, arrows, square, round, dots
                transition: {
                  animation:'none'
                },
                toolbar: {
                  showNextButton: true, // show/hide a Next button
                  showPreviousButton: true, // show/hide a Previous button
                  position: 'bottom', // none/ top/ both bottom
                  extraHtml: `<button class="btn btn-success confirm-order" id="btnFinish" disabled>Complete Order</button>`
                },
                anchor: {
                    enableNavigation: true, // Enable/Disable anchor navigation 
                    enableNavigationAlways: false, // Activates all anchors clickable always
                    enableDoneState: true, // Add done state on visited steps
                    markPreviousStepsAsDone: true, // When a step selected by url hash, all previous steps are marked done
                    unDoneOnBackNavigation: true, // While navigate back, done state will be cleared
                    enableDoneStateNavigation: true // Enable/Disable the done state navigation
                },
            });

            $("#state_selector").on("change", function() {
                $('#smartwizard').smartWizard("setState", [$('#step_to_style').val()], $(this).val(), !$('#is_reset').prop("checked"));
                return true;
            });

            $("#style_selector").on("change", function() {
                $('#smartwizard').smartWizard("setStyle", [$('#step_to_style').val()], $(this).val(), !$('#is_reset').prop("checked"));
                return true;
            });

            // function onConfirm() {
            $(document).on('click','.confirm-order',function(){
                var amount = $('.total-amount').html();
                var method = $('input[name=pay_method]:checked').val();
                if(method == undefined || method == ''){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Select Payment Method',
                        showConfirmButton: false,
                        timer: 1500
                    })
                }else{
                    if(method == 'paypal'){
                        var formdata = $('#form-1,#form-2,#form-3').serialize();
                        console.log(formdata);
                        window.location.href = uRL+'/pay-with-paypal/'+amount+'?'+formdata;
                    }else{
                        var tr = '';
                        var razorpay = new Razorpay({
                            key: $('input[name=razor_key]').val(),
                            amount: amount*100, 
                            name: 'Wallet Top Up',
                            order_id: '',
                            handler: function (transaction) {
                                tr = transaction.razorpay_payment_id;
                                var formdata = $('#form-1,#form-2,#form-3').serialize();
                                window.location.href = uRL+'/pay-with-razorpay/'+amount+'/'+tr+'?'+formdata;
                            }
                        });
                        razorpay.open();
                    }
                }
                
            })
    });
</script>
@stop