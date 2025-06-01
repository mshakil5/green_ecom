
<style>
    .header-sticky-logo {
        transition: opacity 0.3s ease-in-out;
    }

    .sticky-visible .header-sticky-logo {
        display: block !important;
    }

    .sticky-visible .header-bottom-logo {
        display: none !important;
    }
</style>



<header class="header-section d-lg-block d-none">
    <div class="header-center">
        <div class="container">
            <div class="row d-flex justify-content-between align-items-center">
                <div class="col-3">
                    <div class="header-logo">
                        <a href="{{ route('frontend.homepage') }}">
                            <img 
                            src="{{ asset('images/company/' . $company->company_logo) }}" 
                            alt="{{ $company->company_name }}" 
                            width="171" 
                            height="81" 
                            style="object-fit: contain; display: block;">
                        </a>
                    </div>
                </div>
                <div class="col-6">
                    <div class="header-search">
                        <form action="{{ route('getDiffTypeProducts', ['ptype' => 'search-products']) }}">
                            <div class="header-search-box default-search-style d-flex">
                                <input class="default-search-style-input-box border-around border-right-none" type="search" name="query" placeholder="Search entire store here ..." required>
                                <button class="default-search-style-input-btn" type="submit"><i class="icon-search"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-3 text-end">
                    <ul class="header-action-icon">
                        <li>
                            <a href="{{ route('wishlist.index') }}" class="offcanvas-toggle wishlistBtn">
                                <i class="icon-heart"></i>
                                <span class="header-action-icon-item-count wishlistCount">0</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('cart.index') }}" class="offcanvas-toggle cartBtn">
                                <i class="icon-shopping-cart"></i>
                                <span class="header-action-icon-item-count cartCount">0</span>
                            </a>
                        </li>
                        <li class="has-user-dropdown">
                            <a href=""><i class="icon-user"></i></a>
                            <ul class="user-sub-menu">
                                @if (Auth::check())
                                    <li><a href="{{ route('user.dashboard') }}">My Dashboard</a></li>
                                    <li><a href="{{ route('orders.index') }}">My Orders</a></li>
                                    <li><a href="{{ route('user.profile') }}">My Profile</a></li>

                                @else
                                    <li><a href="{{ route('login') }}">Sign In</a></li>
                                    <li><a href="{{ route('register') }}">Sign Up</a></li>
                                    
                                @endif
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="header-bottom sticky-header">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="header-sticky-logo d-none" style="flex: 0 0 auto;">
                            <a href="{{ route('frontend.homepage') }}">
                                <img 
                                    src="{{ asset('images/company/' . $company->company_logo) }}" 
                                    alt="{{ $company->company_name }}" 
                                    width="150" 
                                    height="60" 
                                    style="object-fit: contain; display: inline-block;">
                            </a>
                        </div>

                        <div style="width: 120px;"></div>

                        <div class="main-menu flex-grow-1 text-center ms-4">
                            <nav>
                                <ul class="d-flex align-items-center gap-3 mb-0">
                                    <li class="has-dropdown">
                                        <a class="main-menu-link {{ request()->routeIs('frontend.homepage') ? 'active' : '' }}" href="{{ route('frontend.homepage') }}">Home</a>
                                    </li>
                                    <li class="has-dropdown d-none">
                                        <a class="main-menu-link {{ request()->routeIs('frontend.shop') ? 'active' : '' }}" href="{{ route('frontend.shop') }}">Shop</a>
                                    </li>

                                    @php
                                        $products = \App\Models\Category::with('subcategories')->where('status', 1)->where('type', 'Product')->get();
                                    @endphp

                                    <li class="has-dropdown">
                                        <a href="">Product @if ($products->count() > 0) <i class="fa fa-angle-down"></i> @endif</a>
                                        <ul class="sub-menu">
                                            @foreach($products as $category)
                                                @if($category->products->count() > 0)
                                                    <li class="has-dropdown">
                                                        <a href="{{ route('category.show', $category->slug) }}">{{ $category->name }}
                                                            @if($category->subcategories->count() > 0)
                                                                <span style="float: right;"><i class="fa fa-angle-right"></i></span>
                                                            @endif
                                                        </a>
                                                        @if($category->subcategories->count() > 0)
                                                            <ul class="sub-menu" style="display: none; position: absolute; left: 100%; top: 0;">
                                                                @foreach($category->subcategories as $subcategory)
                                                                    <li class="has-dropdown">
                                                                        <a href="{{ route('subcategory.show', $subcategory->slug) }}">{{ $subcategory->name }}</a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>

                                    @php
                                        $spare = \App\Models\Category::with('subcategories')->where('status', 1)->where('type', 'Spare')->get();
                                    @endphp

                                    @if ($spare->count() > 0)
                                    <li class="has-dropdown">
                                        <a href="{{ route('frontend.shop') }}">Spare Parts @if ($spare->count() > 0) <i class="fa fa-angle-down"></i> @endif</a>
                                        <ul class="sub-menu">
                                            @foreach($spare as $category)
                                                <li class="has-dropdown">
                                                    <a href="{{ route('category.show', $category->slug) }}">{{ $category->name }}
                                                        @if($category->subcategories->count() > 0)
                                                            <span style="float: right;"><i class="fa fa-angle-right"></i></span>
                                                        @endif
                                                    </a>
                                                    @if($category->subcategories->count() > 0)
                                                        <ul class="sub-menu" style="display: none; position: absolute; left: 100%; top: 0;">
                                                            @foreach($category->subcategories as $subcategory)
                                                                <li class="has-dropdown">
                                                                    <a href="{{ route('subcategory.show', $subcategory->slug) }}">{{ $subcategory->name }}</a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                    @endif

                                    @php
                                        $service = \App\Models\Category::with('subcategories')->where('status', 1)->where('type', 'Service')->get();
                                    @endphp

                                    @if ($service->count() > 0)
                                    <li class="has-dropdown">
                                        <a href="{{ route('frontend.shop') }}">Repair Services @if ($service->count() > 0) <i class="fa fa-angle-down"></i> @endif</a>
                                        <ul class="sub-menu">
                                            @foreach($service as $category)
                                                <li class="has-dropdown">
                                                    <a href="{{ route('category.show', $category->slug) }}">{{ $category->name }}
                                                        @if($category->subcategories->count() > 0)
                                                            <span style="float: right;"><i class="fa fa-angle-right"></i></span>
                                                        @endif
                                                    </a>
                                                    @if($category->subcategories->count() > 0)
                                                        <ul class="sub-menu" style="display: none; position: absolute; left: 100%; top: 0;">
                                                            @foreach($category->subcategories as $subcategory)
                                                                <li class="has-dropdown">
                                                                    <a href="{{ route('subcategory.show', $subcategory->slug) }}">{{ $subcategory->name }}</a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                    @endif

                                    @php
                                        $VRF = \App\Models\Category::with('subcategories')->where('status', 1)->where('type', 'VRF')->get();
                                    @endphp

                                    @if ($VRF->count() > 0)
                                    <li class="has-dropdown">
                                        <a href="{{ route('frontend.shop') }}">VRF Solutions @if ($VRF->count() > 0) <i class="fa fa-angle-down"></i> @endif</a>
                                        <ul class="sub-menu">
                                            @foreach($VRF as $category)
                                                <li class="has-dropdown">
                                                    <a href="{{ route('category.show', $category->slug) }}">{{ $category->name }}
                                                        @if($category->subcategories->count() > 0)
                                                            <span style="float: right;"><i class="fa fa-angle-right"></i></span>
                                                        @endif
                                                    </a>
                                                    @if($category->subcategories->count() > 0)
                                                        <ul class="sub-menu" style="display: none; position: absolute; left: 100%; top: 0;">
                                                            @foreach($category->subcategories as $subcategory)
                                                                <li class="has-dropdown">
                                                                    <a href="{{ route('subcategory.show', $subcategory->slug) }}">{{ $subcategory->name }}</a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                    @endif

                                    <li class="has-dropdown">
                                        <a class="main-menu-link" href="{{route('corporate')}}">Corporate</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <div style="width: 120px;"></div>
                    </div>    
                </div>
            </div>    
        </div>
    </div>
</header>
<script>
    document.querySelectorAll('.has-dropdown').forEach(function(element) {
        element.addEventListener('mouseover', function() {
            this.querySelector('.sub-menu').style.display = 'block';
        });
        element.addEventListener('mouseout', function() {
            this.querySelector('.sub-menu').style.display = 'none';
        });
    });
</script>

<script>
    window.addEventListener('scroll', function () {
        const header = document.querySelector('.header-bottom');
        if (window.scrollY > 50) {
            header.classList.add('sticky-visible');
        } else {
            header.classList.remove('sticky-visible');
        }
    });
</script>



<!-- ...:::: Start Mobile Header Section:::... -->
<div class="mobile-header-section d-block d-lg-none">
    <!-- Start Mobile Header Wrapper -->
    <div class="mobile-header-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    
                    <div class="mobile-header--left">
                        <a href="#mobile-menu-offcanvas" class="mobile-menu offcanvas-toggle">
                            <span class="mobile-menu-dash"></span>
                            <span class="mobile-menu-dash"></span>
                            <span class="mobile-menu-dash"></span>
                        </a>
                    </div>

                    <div class="mobile-header--center">
                         <a href="{{ route('frontend.homepage') }}" class="mobile-logo-link">
                            <img src="{{ asset('images/company/' . $company->company_logo) }}" alt=""
                            width="121" 
                            height="51" 
                            class="mobile-logo-img">
                        </a>
                    </div>

                    <div class="mobile-header--right">
                        <a href="{{ route('cart.index') }}" class="mobile-action-icon-link cartBtn mx-2">
                            <i class="icon-shopping-cart"></i>
                            <span class="mobile-action-icon-item-count cartCount">0</span>
                        </a>
                   </div>

                </div>
            </div>
        </div>
    </div> <!-- End Mobile Header Wrapper -->
</div> <!-- ...:::: Start Mobile Header Section:::... -->

    <!-- ...:::: Start Offcanvas Mobile Menu Section:::... -->
<div id="mobile-menu-offcanvas" class="offcanvas offcanvas-leftside offcanvas-mobile-menu-section">
    <!-- Start Offcanvas Header -->
    <div class="offcanvas-header d-flex justify-content-end">
        <button class="offcanvas-close"><i class="fa fa-times"></i></button>
    </div> <!-- End Offcanvas Header -->
    <!-- Start Offcanvas Mobile Menu Wrapper -->
    <div class="offcanvas-mobile-menu-wrapper">
        <!-- Start Mobile Menu User Center -->
        <div class="mobile-menu-center">
            <form action="{{ route('getDiffTypeProducts', ['ptype' => 'search-products']) }}" class="pb-3">
                <div class="header-search-box default-search-style d-flex">
                    <input class="default-search-style-input-box border-around border-right-none" type="search" name="query" placeholder="Search entire store here ..." required>
                    <button class="default-search-style-input-btn" type="submit"><i class="icon-search"></i></button>
                </div>
            </form>
            
            <!-- Start Header Action Icon -->
            <ul class="mobile-action-icon">
                <li class="mobile-action-icon-item">
                    <a href="{{ route('wishlist.index') }}" class="mobile-action-icon-link wishlistBtn">
                        <i class="icon-heart"></i>
                        <span class="mobile-action-icon-item-count wishlistCount">0</span>
                    </a>
                </li>
                <li class="mobile-action-icon-item">
                    <a href="{{ route('cart.index') }}" class="mobile-action-icon-link cartBtn">
                        <i class="icon-shopping-cart"></i>
                        <span class="mobile-action-icon-item-count cartCount">0</span>
                    </a>
                </li>

                <li class="has-mobile-user-dropdown">
                    <a href="#" class="mobile-action-icon-link"><i class="icon-user"></i></a>
                    <!-- Header Top Menu's Dropdown -->
                    <ul class="mobile-user-sub-menu">
                        @if (Auth::check())
                            <li><a href="{{ route('user.dashboard') }}">My Dashboard</a></li>
                            <li><a href="{{ route('orders.index') }}">My Orders</a></li>
                            <li><a href="{{ route('user.profile') }}">My Profile</a></li>

                        @else
                            <li><a href="{{ route('login') }}">Sign In</a></li>
                            <li><a href="{{ route('register') }}">Sign Up</a></li>
                            
                        @endif
                    </ul>
                </li>


            </ul> <!-- End Header Action Icon -->
        </div> <!-- End Mobile Menu User Center -->
        <!-- Start Mobile Menu Bottom -->
        <div class="mobile-menu-bottom">
            <!-- Start Mobile Menu Nav -->
            <div class="offcanvas-menu">
                <ul>



                    <li>
                        <a href="{{ route('frontend.homepage') }}"><span>Home</span></a>
                    </li>
                    @php
                        $menucategories = \App\Models\Category::with('subcategories')->where('status', 1)->orderby('id','ASC')->get();
                    @endphp

                    @foreach ($menucategories as $category)
                    <li>
                        <a href="{{ route('category.show', $category->slug) }}"><span>{{ $category->name }} </span></a>
                        <ul class="mobile-sub-menu">
                            @foreach($category->subcategories as $subcategory)
                            <li>
                                <a href="{{ route('subcategory.show', $subcategory->slug) }}">{{ $subcategory->name }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    @endforeach


                </ul>
            </div> <!-- End Mobile Menu Nav -->

            <!-- Mobile Manu Mail Address -->
            <a class="mobile-menu-email icon-text-end" href="mailto:{{ $company->email1 }}"><i class="fa fa-envelope-o"> {{ $company->email1 }}</i></a>

            <!-- Mobile Manu Social Link -->
            <ul class="mobile-menu-social">
                @if($company->facebook)
                            <li><a href="{{ $company->facebook }}" class="facebook" target="_blank"><i class="fa fa-facebook"></i></a></li>
                        @endif
                        @if($company->twitter)
                            <li><a href="{{ $company->twitter }}" class="twitter" target="_blank"><i class="fa fa-twitter"></i></a></li>
                        @endif
                        @if($company->youtube)
                            <li><a href="{{ $company->youtube }}" class="youtube" target="_blank"><i class="fa fa-youtube"></i></a></li>
                        @endif
                        @if($company->instagram)
                            <li><a href="{{ $company->instagram }}" class="instagram" target="_blank"><i class="fa fa-instagram"></i></a></li>
                        @endif
            </ul>


            <div class="mobile-menu-customer-support">
                <div class="mobile-menu-customer-support-icon">
                    <img src="{{ asset('frontend/images/icon/support-icon.png') }}" alt="">
                </div>
                <div class="mobile-menu-customer-support-text">
                    <span>Customer Support</span>
                    <a class="mobile-menu-customer-support-text-phone" href="tel:{{ $company->phone1 }}">{{ $company->phone1 }}</a>
                </div>
            </div>
        </div> <!-- End Mobile Menu Bottom -->
    </div> <!-- End Offcanvas Mobile Menu Wrapper -->
</div> <!-- ...:::: End Offcanvas Mobile Menu Section:::... -->