<div class="product_item_inner">
    @if ($value->variable_count > 0 && $value->type == 0)
        @if ($value->variable->old_price)
            <div class="discount">
                <p>@php $discount=(((($value->variable->old_price)-($value->variable->new_price))*100) / ($value->variable->old_price)) @endphp -{{ number_format($discount, 0) }}%</p>

            </div>
        @endif
    @else
        @if ($value->old_price)
            <div class="discount">
                <p>@php $discount=(((($value->old_price)-($value->new_price))*100) / ($value->old_price)) @endphp -{{ number_format($discount, 0) }}%</p>
            </div>
        @endif
    @endif
    <div class="pro_img">
        <a href="{{ route('product', $value->slug) }}">
            <img src="{{ asset($value->image ? $value->image->image : '') }}" alt="{{ $value->name }}" />
        </a>
    </div>
    <div class="pro_des">
        <div class="pro_name">
            <a href="{{ route('product', $value->slug) }}">{{ Str::limit($value->name, 80) }}</a>
        </div>
        <div class="pro_price">
            @if ($value->variable_count > 0 && $value->type == 0)
                <p>
                    @if ($value->variable->old_price)
                        <del>৳ {{ $value->variable->old_price }}</del>
                    @endif

                    ৳ {{ $value->variable->new_price }}

                </p>
            @else
                <p>
                    @if ($value->old_price)
                        <del>৳ {{ $value->old_price }}</del>
                    @endif
                    ৳ {{ $value->new_price }}
                </p>
            @endif
        </div>
    </div>
</div>

@if ($value->variable_count > 0 && $value->type == 0)
    <div class="pro_btn">
        <div class="cart_btn order_button">
            <a href="{{ route('product', $value->slug) }}" class="addcartbutton">
                <i class="fa fa-shopping-basket"></i>
                অর্ডার করুন
            </a>
        </div>
    </div>
@else
    <div class="pro_btn">

        <form action="{{ route('cart.store') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $value->id }}" />
            <input type="hidden" name="qty" value="1" />
            <input type="hidden" name="order_now" value="অর্ডার করুন" />
            <button type="submit">
                <i class="fa fa-shopping-basket"></i>
                অর্ডার করুন
            </button>
        </form>
    </div>
@endif
