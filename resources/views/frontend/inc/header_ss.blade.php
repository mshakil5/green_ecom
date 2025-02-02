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
                            width="105" 
                            height="25" 
                            style="object-fit: contain; display: block;">
                        </a>
                    </div>
                </div>
                <div class="col-6">
                    <div class="header-search">
                        <form action="#" method="post">
                            <div class="header-search-box default-search-style d-flex">
                                <input class="default-search-style-input-box border-around border-right-none" type="search" placeholder="Search entire store here ..." required>
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
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="header-bottom sticky-header">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="main-menu">
                        <nav>
                            <ul>
                                <li class="has-dropdown">
                                    <a class="main-menu-link {{ request()->routeIs('frontend.homepage') ? 'active' : '' }}" href="{{ route('frontend.homepage') }}">Home</a>
                                </li>
                                <li class="has-dropdown">
                                    <a class="main-menu-link {{ request()->routeIs('frontend.shop') ? 'active' : '' }}" href="{{ route('frontend.shop') }}">Shop</a>
                                </li>

                                @php
                                    $refrigerations = \App\Models\Category::with('subcategories')->where('status', 1)->where('type', 'Refrigeration')->get();
                                @endphp

                                <li class="has-dropdown">
                                    <a href="">Refrigeration @if ($refrigerations->count() > 0) <i class="fa fa-angle-down"></i> @endif</a>
                                    <ul class="sub-menu">
                                        @foreach($refrigerations as $category)
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
                                    $cooking = \App\Models\Category::with('subcategories')->where('status', 1)->where('type', 'Cooking')->get();
                                @endphp

                                @if ($cooking->count() > 0)
                                <li class="has-dropdown">
                                    <a href="{{ route('frontend.shop') }}">Cooking @if ($cooking->count() > 0) <i class="fa fa-angle-down"></i> @endif</a>
                                    <ul class="sub-menu">
                                        @foreach($cooking as $category)
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
                                @endif


                                @php
                                    $appliances = \App\Models\Category::with('subcategories')->where('status', 1)->where('type', 'Appliances')->get();
                                @endphp

                                @if ($appliances->count() > 0)
                                <li class="has-dropdown">
                                    <a href="{{ route('frontend.shop') }}">Appliances @if ($appliances->count() > 0) <i class="fa fa-angle-down"></i> @endif</a>
                                    <ul class="sub-menu">
                                        @foreach($appliances as $category)
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
                                @endif

                                @php
                                    $foodPreparation = \App\Models\Category::with('subcategories')->where('status', 1)->where('type', 'Food Preparation')->get();
                                @endphp

                                @if ($foodPreparation->count() > 0)
                                <li class="has-dropdown">
                                    <a href="{{ route('frontend.shop') }}">Food Preparation @if ($foodPreparation->count() > 0) <i class="fa fa-angle-down"></i> @endif</a>
                                    <ul class="sub-menu">
                                        @foreach($foodPreparation as $category)
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
                                @endif


                                @php
                                    $beverage = \App\Models\Category::with('subcategories')->where('status', 1)->where('type', 'Beverage')->get();
                                @endphp

                                @if ($beverage->count() > 0)
                                <li class="has-dropdown">
                                    <a href="{{ route('frontend.shop') }}">Beverage @if ($beverage->count() > 0) <i class="fa fa-angle-down"></i> @endif</a>
                                    <ul class="sub-menu">
                                        @foreach($beverage as $category)
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
                                @endif

                                
                                @php
                                    $stainless = \App\Models\Category::with('subcategories')->where('status', 1)->where('type', 'Stainless Steel')->get();
                                @endphp

                                @if ($stainless->count() > 0)
                                <li class="has-dropdown">
                                    <a href="{{ route('frontend.shop') }}">Stainless Steel @if ($stainless->count() > 0) <i class="fa fa-angle-down"></i> @endif</a>
                                    <ul class="sub-menu">
                                        @foreach($stainless as $category)
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
                                @endif


                                <li class="has-dropdown">
                                    <a href="">Brands<i class="fa fa-angle-down"></i></a>
                                    <ul class="sub-menu">
                                        @foreach($categories as $category)
                                            @if($category->products->count() > 0)
                                                <li>
                                                    <a href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </li>



                                {{-- @foreach ($categories->take(2) as $category)
                                <li class="has-dropdown has-megaitem">
                                    <a href="{{ route('category.show', $category->slug) }}">{{ $category->name }} <i class="fa fa-angle-down"></i></a>
                                    
                                    <!-- Mega Menu -->
                                    <div class="mega-menu">
                                        <ul class="mega-menu-inner">
                                            <!-- Mega Menu Sub Link -->
                                            <li class="mega-menu-item">
                                                <a href="#" class="mega-menu-item-title">Shop Layouts</a>
                                                <ul class="mega-menu-sub">
                                                    <li><a href="shop-grid-sidebar-left.html">Grid Left Sidebar</a></li>
                                                    <li><a href="shop-grid-sidebar-right.html">Grid Right Sidebar</a></li>
                                                </ul>
                                            </li>
                                            
                                        </ul>
                                        
                                    </div>
                                </li>
                                @endforeach --}}


                                



                                
                            </ul>
                        </nav>
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