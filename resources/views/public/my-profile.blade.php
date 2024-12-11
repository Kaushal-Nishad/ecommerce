@extends('public.layout')
@section('title','My Profile')
@section('content')
<div id="site-content">
    <div class="container">
        <div class="section-heading">
            <h3 class="title">My Profile</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">My Profile</li>
            </ol>
        </div>
            <form class="row" id="EditProfile" method="POST" style="width:100%;">
                @csrf
                <div class="col-md-3">
                    <div class="content-box">
                        @if($user->user_img != '')
                            <img id="image" class="mb-2 w-100" src="{{asset('users/'.$user->user_img)}}" alt="" >
                        @else
                            <img id="image" class="mb-2 w-100" src="{{asset('users/default.png')}}" alt="" width="100%">
                        @endif
                        <div>
                            <input type="hidden" name="old_img" value="{{$user->user_img}}" />
                            <input type="file" class="form-control" name="img" onChange="readURL(this);" width="100%">
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="content-box">
                        <div class="form-group row mb-3">
                            <label class="col-lg-3 col-sm-5 col-form-label">Full Name : </label>
                            <div class="col-lg-5 col-sm-7">
                                <input type="text" class="form-control" name="name" value="{{$user->name}}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-lg-3 col-sm-5 col-form-label">Email / Username : </label>
                            <div class="col-lg-5 col-sm-7">
                                <input type="email" class="form-control" name="email" value="{{$user->email}}" disabled>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="staticphone" class="col-lg-3 col-sm-5 col-form-label">Phone No : </label>
                            <div class="col-lg-5 col-sm-7">
                                <input type="number" class="form-control" name="phone" value="{{$user->phone}}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="staticphone" class="col-lg-3 col-sm-5 col-form-label">Country : </label>
                            <div class="col-lg-5 col-sm-7">
                                <select class="form-control select-country" name="country" id="">
                                    <option value="">Select Country</option>
                                    @if(!empty($country))
                                        @foreach($country as $countries)
                                            @php $selected = ($countries->id == $user->country) ? 'selected' : ''; @endphp
                                            <option value="{{$countries->id}}" data-country="{{$countries->id}}" {{$selected}}>{{$countries->country_name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="staticphone" class="col-lg-3 col-sm-5 col-form-label">State : </label>
                            <div class="col-lg-5 col-sm-7">
                                <select class="form-control select-state" name="state" id="state">
                                    <option value="">First Select Country</option>
                                    @if(!empty($state))
                                        @foreach($state as $states)
                                            @php $selected = ($states->id == $user->state) ? 'selected' : ''; @endphp
                                            <option value="{{$states->id}}" data-state="{{$states->id}}" {{$selected}}>{{$states->state_name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="staticphone" class="col-lg-3 col-sm-5 col-form-label">City : </label>
                            <div class="col-lg-5 col-sm-7">
                                <select class="form-control" name="city" id="city">
                                    <option value="">First Select State</option>
                                    @if(!empty($city))
                                        @foreach($city as $cities)
                                            @php $selected = ($cities->id == $user->city) ? 'selected' : ''; @endphp
                                            <option value="{{$cities->id}}" {{$selected}}>{{$cities->city_name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="staticphone" class="col-lg-3 col-sm-5 col-form-label">Address : </label>
                            <div class="col-lg-5 col-sm-7">
                                @if($user->address != '')
                                    <input type="text" class="form-control" name="address" value="{{$user->address}}">
                                @else
                                    <input type="text" class="form-control" name="address" value="">
                                @endif
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="staticphone" class="col-lg-3 col-sm-5 col-form-label">Pin Code : </label>
                            <div class="col-lg-5 col-sm-7">
                                @if($user->pin_code != '')
                                    <input type="number" class="form-control" name="code" value="{{$user->pin_code}}">
                                @else
                                    <input type="number" class="form-control" name="code" value="">
                                @endif
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mb-2">UPDATE</button> 
                    </div>
                    <div class="message"></div>
                </div>
            </form>
    </div>
</div>



<script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#image').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }
</script>
@stop