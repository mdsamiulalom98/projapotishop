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
            @if (!empty($value->campaign_id))
                <p>
                    @if ($value->variable_count > 0 && $value->type == 0)
                        @if ($value->variable->old_price)
                            <del>৳ {{ $value->variable->old_price }}</del>
                        @endif
                        ৳
                        {{ round($value->variable->new_price - $value->variable->new_price * ($value->campaign->discount / 100)) }}
                    @else
                        @if ($value->old_price)
                            <del>৳ {{ $value->old_price }}</del>
                        @endif
                        ৳ {{ round($value->new_price - $value->new_price * ($value->campaign->discount / 100)) }}
                    @endif
                </p>
            @else
                <p>
                    @if ($value->variable_count > 0 && $value->type == 0)
                        @if ($value->variable->old_price)
                            <del>৳ {{ $value->variable->old_price }}</del>
                        @endif
                        ৳ {{ $value->variable->new_price }}
                    @else
                        @if ($value->old_price)
                            <del>৳ {{ $value->old_price }}</del>
                        @endif
                        ৳ {{ $value->new_price }}
                    @endif
                </p>
            @endif
        </div>
    </div>
</div>

<div class="pro_btn">
    <div class="cart_btn order_button">
        <a @if ($value->variable_count > 0 && $value->type == 0) href="{{ route('product', $value->slug) }}" @else data-id="{{ $value->id }}" @endif
            class="addcartbutton">
            <img src="{{ asset('public/frontEnd/images/shopping_cart.png') }}">
        </a>
    </div>

    @if ($value->variable_count > 0 && $value->type == 0)
        <div class="variable-details-button">
            <a href="{{ route('product', $value->slug) }}" class="potro-sans">
                অর্ডার করুন
            </a>
        </div>
    @else
        <form action="{{ route('cart.store') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $value->id }}" />
            <input type="hidden" name="qty" value="1" />
            <input type="hidden" name="order_now" value="অর্ডার করুন" />
            <button type="submit" class="potro-sans">
                অর্ডার করুন
            </button>
        </form>
    @endif
</div>
