@extends('user.dashboard')

@section('content')



<!-- ...:::: Start Breadcrumb Section:::... -->
<div class="breadcrumb-section">
    <div class="breadcrumb-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12 d-flex justify-content-between justify-content-md-between  align-items-center flex-md-row flex-column">
                    <h3 class="breadcrumb-title">My Account</h3>
                    <div class="breadcrumb-nav">
                        <nav aria-label="breadcrumb">
                            <ul>
                                <li><a href="{{route('frontend.homepage')}}">Home</a></li>
                                <li><a href="{{route('frontend.shop')}}">Shop</a></li>
                                <li class="active" aria-current="page">My Account</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- ...:::: End Breadcrumb Section:::... -->

<!-- ...:::: Start Account Dashboard Section:::... -->
<div class="account_dashboard">
    <div class="container">
        <div class="row">


            @include('user.inc.sidebar')

            



            <div class="col-sm-12 col-md-9 col-lg-9">
                <!-- Tab panes -->
                <div class="tab-content dashboard_content" data-aos="fade-up"  data-aos-delay="200">
                    <div class="tab-pane fade show active" id="dashboard">
                        
                        
                        <div class="ermsg mb-2"></div>
                        <form id="updateProfileForm">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Name <span class="text-danger">*</span></label>
                                    <input id="name" type="text" class="form-control" name="name" value="{{ $user->name }}" required autofocus>
                                </div>
                                <div class="col-sm-6">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input id="email" type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Phone <span class="text-danger">*</span></label>
                                    <input id="phone" type="text" class="form-control" name="phone" value="{{ $user->phone }}" required>
                                </div>
                                <div class="col-sm-6">
                                    <label>NID <span class="text-danger">*</span></label>
                                    <input id="nid" type="text" class="form-control" name="nid" value="{{ $user->nid }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>House Number <span class="text-danger">*</span></label>
                                    <input id="house_number" type="text" class="form-control" name="house_number" value="{{ $user->house_number }}">
                                </div>
                                <div class="col-sm-6">
                                    <label>Street Name <span class="text-danger">*</span></label>
                                    <input id="street_name" type="text" class="form-control" name="street_name" value="{{ $user->street_name }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Town <span class="text-danger">*</span></label>
                                    <input id="town" type="text" class="form-control" name="town" value="{{ $user->town }}">
                                </div>
                                <div class="col-sm-6">
                                    <label>Post Code <span class="text-danger">*</span></label>
                                    <input id="postcode" type="text" class="form-control" name="postcode" value="{{ $user->postcode }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                <label>Password</label>
                                <input id="password" type="password" class="form-control" name="password">
                                </div>

                                <div class="col-sm-6">
                                <label>Confirm password</label>
                                <input id="confirm_password" type="password" class="form-control" name="confirm_password">
                                </div>
                            </div>
                            

                            <button type="submit" class="contact-submit-btn mt-3">
                                <span>SAVE CHANGES</span>
                                <i class="icon-long-arrow-right"></i>
                            </button>
                        </form>




                    </div>

                </div>
            </div>


        </div>
    </div>
</div> <!-- ...:::: End Account Dashboard Section:::... -->









@endsection

@section('script')

<script>
    $(document).ready(function () {
        $('#updateProfileForm').on('submit', function (e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{ route('user.profile.update') }}",
                data: formData,
                processData: false, 
                contentType: false, 
                success: function (response) {
                    if (response.status === 300) {
                        $(".ermsg").html(response.message).removeClass('alert-warning').addClass('alert-success');
                        window.setTimeout(function(){location.reload()},3000)
                    } else {
                        $(".ermsg").html(response.message).removeClass('alert-success').addClass('alert-warning');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                 }
            });
        });
    });
</script>

@endsection