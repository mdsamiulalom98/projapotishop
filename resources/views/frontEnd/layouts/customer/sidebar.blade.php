<div class="customer-auth">
    <div class="customer-img">
        <img src="{{asset(Auth::guard('customer')->user()->image)}}" alt="">
    </div>
    <div class="customer-name">
        @php
            $customer = \App\Models\Customer::with('cust_area')->find(Auth::guard('customer')->user()->id);
        @endphp
        <h6>
        <p>{{Auth::guard('customer')->user()->name}}</p>
        </h6>
        <div class="sidebar-cus-type">
            <p class="mt-1">
            @if(Auth::guard('customer')->user()->seller_type == 1)
                Reseller
            @else
                Customer
            @endif
            </p>
            @if(Auth::guard('customer')->user()->seller_type != 0)
             <div class="balance-btn">
                 <a href="" class="mt-1"><i data-feather="credit-card"></i>  {{Auth::guard('customer')->user()->balance}}৳</a>
             </div>
            @endif
        </div>

    </div>
</div>
<div class="sidebar-menu">
 @if($customer->reseller_id)
    <div class="coupon-code mt-0 btn-grad">
        <span>Reseller ID :</span><span id="couponCode">{{$customer->reseller_id}}</span>
        <button onclick="copyCouponCode()"> <i class="fas fa-copy"></i>
        </button></p>
    </div>
    @endif
    <ul>
        <li><a href="{{route('customer.account')}}" class="{{request()->is('customer/account')?'active':''}}"><i data-feather="user"></i> My Account</a></li>
        <li><a href="{{route('customer.orders')}}" class="{{request()->is('customer/orders')?'active':''}}"><i data-feather="database"></i> My Order</a></li>
        <!-- total earnning start -->
        @if(Auth::guard('customer')->user()->seller_type != 0)
        <li><a href="{{route('customer.commisions')}}" class="{{request()->is('customer/commisions')?'active':''}}"><i data-feather="database"></i> Total Earning</a></li>
        @endif
        <!-- total earning end -->
        <!-- -----withdorw-strat---- -->
        @if(Auth::guard('customer')->user()->seller_type != 0)
        <li>
            <a href="{{route('customer.withdraw')}}" class="{{request()->is('customer/withdraw')?'active':''}}">
                <i data-feather="credit-card"></i> My Withdraw</a>
        </li>
        @endif
        <!-- -----withdorw-end---- -->
        <li><a href="{{route('customer.profile_edit')}}" class="{{request()->is('customer/profile-edit')?'active':''}}"><i data-feather="edit"></i> Profile Edit</a></li>
        <li><a href="{{route('customer.change_pass')}}" class="{{request()->is('customer/change-password')?'active':''}}"><i data-feather="lock"></i> Change Password</a></li>
        <li><a href="{{ route('customer.logout') }}"
            onclick="event.preventDefault();
            document.getElementById('logout-form').submit();"><i data-feather="log-out"></i> Logout</a></li>
        <form id="logout-form" action="{{ route('customer.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </ul>
</div>