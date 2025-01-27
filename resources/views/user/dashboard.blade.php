@extends('frontend.layouts.app')

@section('content')
<style>
    @media print {
        .no-print {
            display: none;
        }
    }
</style>
@if(session('session_clear'))
  <script>
      localStorage.removeItem('wishlist');
      localStorage.removeItem('cart');
      @php
          session()->forget('session_clear');
      @endphp
  </script>
@endif

<!-- ...:::: Start Breadcrumb Section:::... -->
<div class="breadcrumb-section no-print">
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
                        
                        
                        <div class="row justify-content-center">

                            @php
                                use Carbon\Carbon;
                                $today = Carbon::today()->toDateString();
                                $user = auth()->user();
                                $todayOrdersCount = $user->orders()->whereDate('created_at', $today)->count();
                            @endphp

                            <div class="col-lg-4 col-sm-6">
                                <div class="icon-box text-center">
                                    <span class="icon-box-icon">
                                        <i class="icon-info-circle"></i>
                                    </span>
                                    <div class="icon-box-content">
                                        <h3 class="icon-box-title">Today's Orders</h3>
                                        <p>{{ $todayOrdersCount }}</p>
                                    </div>
                                </div>
                            </div>

                            @php
                                $startOfWeek = Carbon::now()->startOfWeek()->toDateString();
                                $endOfWeek = Carbon::now()->endOfWeek()->toDateString();
                                $thisWeekOrdersCount = $user->orders()->whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
                            @endphp

                            <div class="col-lg-4 col-sm-6">
                                <div class="icon-box text-center">
                                    <span class="icon-box-icon">
                                        <i class="icon-info-circle"></i>
                                    </span>
                                    <div class="icon-box-content">
                                        <h3 class="icon-box-title">This Week's Orders</h3>
                                        <p>{{ $thisWeekOrdersCount }}</p>
                                    </div>
                                </div>
                            </div>
                            
                        </div>




                    </div>







                    <div class="tab-pane fade" id="orders">
                        <h4>Orders</h4>
                        <div class="table_page table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Order</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>May 10, 2022</td>
                                        <td><span class="success">Completed</span></td>
                                        <td>$25.00 for 1 item </td>
                                        <td><a href="cart.html" class="view">view</a></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>May 10, 2022</td>
                                        <td>Processing</td>
                                        <td>$17.00 for 1 item </td>
                                        <td><a href="cart.html" class="view">view</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="downloads">
                        <h4>Downloads</h4>
                        <div class="table_page table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Downloads</th>
                                        <th>Expires</th>
                                        <th>Download</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Shopnovilla - Free Real Estate PSD Template</td>
                                        <td>May 10, 2022</td>
                                        <td><span class="danger">Expired</span></td>
                                        <td><a href="#" class="view">Click Here To Download Your File</a></td>
                                    </tr>
                                    <tr>
                                        <td>Organic - ecommerce html template</td>
                                        <td>Sep 11, 2022</td>
                                        <td>Never</td>
                                        <td><a href="#" class="view">Click Here To Download Your File</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="address">
                        <p>The following addresses will be used on the checkout page by default.</p>
                        <h5 class="billing-address">Billing address</h5>
                        <a href="#" class="view">Edit</a>
                        <p><strong>Bobby Jackson</strong></p>
                        <address>
                            Address: Your address goes here.
                        </address>
                    </div>
                    <div class="tab-pane fade" id="account-details">
                        <h3>Account details </h3>
                        <div class="login">
                            <div class="login_form_container">
                                <div class="account_login_form">
                                    <form action="#">
                                        <p>Already have an account? <a href="#">Log in instead!</a></p>
                                        <div class="input-radio">
                                            <span class="custom-radio"><input type="radio" value="1" name="id_gender"> Mr.</span>
                                            <span class="custom-radio"><input type="radio" value="1" name="id_gender"> Mrs.</span>
                                        </div> <br>
                                        <div class="default-form-box mb-20">
                                            <label>First Name</label>
                                            <input type="text" name="first-name">
                                        </div>
                                        <div class="default-form-box mb-20">
                                            <label>Last Name</label>
                                            <input type="text" name="last-name">
                                        </div>
                                        <div class="default-form-box mb-20">
                                            <label>Email</label>
                                            <input type="text" name="email-name">
                                        </div>
                                        <div class="default-form-box mb-20">
                                            <label>Password</label>
                                            <input type="password" name="user-password">
                                        </div>
                                        <div class="default-form-box mb-20">
                                            <label>Birthdate</label>
                                            <input type="date" name="birthday">
                                        </div>
                                        <span class="example">
                                                (E.g.: 05/31/1970)
                                            </span>
                                        <br>
                                        <label class="checkbox-default" for="offer">
                                            <input type="checkbox" id="offer">
                                            <span>Receive offers from our partners</span>
                                        </label>
                                        <br>
                                        <label class="checkbox-default checkbox-default-more-text" for="newsletter">
                                            <input type="checkbox" id="newsletter">
                                            <span>Sign up for our newsletter<br><em>You may unsubscribe at any moment. For that purpose, please find our contact info in the legal notice.</em></span>
                                        </label>
                                        <div class="save_button primary_btn default_button">
                                            <button type="submit">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- ...:::: End Account Dashboard Section:::... -->



@endsection