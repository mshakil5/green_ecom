<div class="col-sm-12 col-md-3 col-lg-3 no-print">
    <!-- Nav tabs -->
    <div class="dashboard_tab_button" data-aos="fade-up"  data-aos-delay="0">
        <ul role="tablist" class="nav flex-column dashboard-list">

            <li><a href="{{ route('user.dashboard') }}" class="nav-link {{ Request::routeIs('user.dashboard') ? 'active' : '' }}">Dashboard</a></li>

            <li> <a href="{{ route('orders.index') }}" class="nav-link {{ Request::routeIs('orders.index') || Request::routeIs('orders.details') ? 'active' : '' }}">Orders</a></li>
            
            <li><a href="{{ route('user.profile') }}" class="nav-link  {{ Request::routeIs('user.profile') ? 'active' : '' }}" href="{{ route('user.profile') }}">Account details</a></li>
            
            <li><a href="{{ route('clearSessionData') }}" class="nav-link">logout</a></li>
        </ul>
    </div>
</div>





