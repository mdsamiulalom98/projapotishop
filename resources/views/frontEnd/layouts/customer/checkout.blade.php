@extends('frontEnd.layouts.master')
@section('title', 'Customer Checkout')
@push('css')
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/select2.min.css') }}" />
@endpush
@section('content')
    <section class="chheckout-section">
        @php
            $subtotal = Gloudemans\Shoppingcart\Facades\Cart::instance('shopping')->subtotal();
            $subtotal = str_replace(',', '', $subtotal);
            $subtotal = str_replace('.00', '', $subtotal);
            $shipping = Session::get('shipping') ?? 0;
            $coupon = Session::get('coupon_amount') ?? 0;
            $discount = Session::get('discount') ?? 0;
            $placeholderText = Session::get('reseller_id') ? 'Already Reseller code Used' : 'Apply Reseller Code';
        @endphp
        <div class="container">
            <div class="row">
                <div class="col-sm-5 cus-order-2">
                    <div class="checkout-shipping">
                        <form action="{{ route('customer.ordersave') }}" method="POST" data-parsley-validate="">
                            @csrf
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="potro-sans">আপনার অর্ডারটি কনফার্ম করতে তথ্যগুলো পূরণ করে <span
                                            style="color:#fe5200;">"অর্ডার
                                            করুন"</span> বাটন এ ক্লিক করুন অথবা ফোনে অর্ডার করতে এই নাম্বার <a
                                            href="tel:{{ $contact->hotline }}">{{ $contact->hotline }}</a> এর উপরে ক্লিক
                                        করুন। </h6>

                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group mb-3">
                                                <label for="name">আপনার নাম লিখুন *</label>
                                                <input type="text" id="name"
                                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                                    value="{{ old('name') }}" required />
                                                @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!-- col-end -->
                                        <div class="col-sm-12">
                                            <div class="form-group mb-3">
                                                <label for="phone">আপনার নাম্বার লিখুন *</label>
                                                <input type="text" minlength="11" id="number" maxlength="11"
                                                    pattern="0[0-9]+"
                                                    title="please enter number only and 0 must first character"
                                                    title="Please enter an 11-digit number." id="phone"
                                                    class="form-control @error('phone') is-invalid @enderror" name="phone"
                                                    value="{{ old('phone') }}" required />
                                                @error('phone')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!-- col-end -->
                                        <div class="col-sm-12">
                                            <div class="form-group mb-3">
                                                <label for="address">ঠিকানা লিখুন *</label>
                                                <input type="address" id="address"
                                                    class="form-control @error('address') is-invalid @enderror"
                                                    name="address" value="{{ old('address') }}" required />
                                                @error('address')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        @if (Auth::guard('customer')->user() && Auth::guard('customer')->user()->seller_type == 1)
                                        @else
                                            <div class="col-sm-12">
                                                <div class="form-group mb-3">
                                                    <label for="reseller_id">রিসেলার কোড (অপশনাল)</label>
                                                    <input type="reseller_id" id="reseller_id"
                                                        class="form-control @error('reseller_id') is-invalid @enderror"
                                                        name="reseller_id" value="{{ old('reseller_id') }}" />
                                                    @error('reseller_id')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        @endif

                                        <div class="col-sm-12">
                                            <div class="form-group mb-3">
                                                <label for="area">ডেলিভারি এরিয়া নিবার্চন করুন *</label>
                                                <div class="shipping-area-box">
                                                    @foreach ($shippingcharge as $key => $value)
                                                        <div class="area-item" data-id="{{ $value->id }}">
                                                            <input name="area" id="area-{{ $key + 1 }}"
                                                                type="radio" value="{{ $value->id }}">
                                                            <label
                                                                for="area-{{ $key + 1 }}">{{ $value->name }}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                @error('area')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!-- col-end -->

                                        <!-------------------->
                                        <!-- col-end -->
                                        <div class="col-sm-12">
                                            <div class="radio_payment">
                                                <label id="payment_method">পেমেন্ট মেথড</label>
                                                <div class="payment_option">

                                                </div>
                                            </div>
                                            <div class="payment-methods">
                                                @if (Session::get('free_shipping') != 1)
                                                    <div class="form-check p_cash">
                                                        <input class="form-check-input" type="radio" name="payment_method"
                                                            id="cod" value="Cash On Delivery" checked required />
                                                        <label class="form-check-label" for="cod">
                                                            Cash On Delivery
                                                        </label>
                                                    </div>
                                                @endif
                                                @if ($bkash_gateway)
                                                    <div class="form-check p_bkash">
                                                        <input class="form-check-input" type="radio"
                                                            @if (Session::get('free_shipping') == 1) checked @endif
                                                            name="payment_method" id="bKash" value="bkash" required />
                                                        <label class="form-check-label" for="bKash">
                                                            Bkash
                                                        </label>
                                                    </div>
                                                @endif

                                                @foreach ($paymentmethods as $key => $value)
                                                    <div class="form-check {{ $value->slug }}-label">
                                                        <input class="form-check-input" type="radio"
                                                            name="payment_method" id="{{ $value->slug }}"
                                                            value="{{ $value->slug }}" required />
                                                        <label class="form-check-label" for="{{ $value->slug }}">
                                                            {{ $value->name }}
                                                        </label>
                                                    </div>
                                                @endforeach

                                                @if ($shurjopay_gateway)
                                                    <div class="form-check p_shurjo">
                                                        <input class="form-check-input" type="radio"
                                                            name="payment_method" id="inlineRadio3" value="shurjopay"
                                                            required />
                                                        <label class="form-check-label" for="inlineRadio3">
                                                            Shurjopay
                                                        </label>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="payment-instruction">

                                                <div class="codform" style="display: block;">

                                                </div>

                                                @foreach ($paymentmethods as $key => $value)
                                                    <div class="{{ $value->slug }}-form payment-form"
                                                        style="display: none;">
                                                        {!! $value->description !!}
                                                    </div>
                                                @endforeach

                                                <div class="trxform row mt-3" style="display: none;">
                                                    <div class="col-sm-6">
                                                        <div class="form-group mb-3">
                                                            <label for="sender_number">Sender Number</label>
                                                            <i data-feather="link"></i>
                                                            <input type="text" id="sender_number" class="form-control"
                                                                name="sender_number" value="">
                                                        </div>
                                                    </div>
                                                    <!-- col-end -->
                                                    <div class="col-sm-6">
                                                        <div class="form-group mb-3">
                                                            <label for="trx_id">Transaction ID</label>
                                                            <i data-feather="key"></i>
                                                            <input type="text" id="trx_id" class="form-control "
                                                                name="trx_id" value="">
                                                        </div>
                                                    </div>
                                                    <!-- col-end -->

                                                </div>

                                            </div>
                                        </div>
                                        @if ($sms_gateway)
                                            <!-------------------->
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    @if (Auth::guard('customer')->user() && Auth::guard('customer')->user()->seller_type == 1)
                                                        <button class="order_place potro-sans" type="submit">অর্ডার
                                                            করুন</button>
                                                    @else
                                                        <button class="order_place potro-sans" type="button"
                                                            id="initialSubmitButton">অর্ডার করুন</button>
                                                    @endif
                                                </div>
                                                <!-- Add the OTP input field, initially hidden -->
                                                <div class="col-sm-12" id="otpSection" style="display: none;">
                                                    <div class="form-group mt-3">
                                                        <label for="otp">OTP কোড লিখুন *</label>
                                                        <input type="text" id="otp" class="form-control"
                                                            name="otp" />
                                                    </div>
                                                    <button type="submit" class="order_place" id="finalSubmitButton"
                                                        style="display: none;">কনফার্ম করুন</button>
                                                </div>
                                            </div>
                                        @else
                                            <!-------------------->
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    @if (Auth::guard('customer')->user() && Auth::guard('customer')->user()->seller_type == 1)
                                                        <button class="order_place" type="submit">অর্ডার করুন</button>
                                                    @else
                                                        <button type="submit" class="order_place">কনফার্ম করুন</button>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- card end -->
                        </form>
                    </div>
                </div>
                <!-- col end -->
                <div class="col-sm-7 cust-order-1">
                    <div class="cart_details table-responsive-sm">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="potro-sans">অর্ডারের তথ্য</h5>
                            </div>
                            <div class="card-body cartlist">
                                @include('frontEnd.layouts.ajax.cart')
                            </div>
                        </div>
                    </div>
                </div>
                <!-- col end -->
            </div>
        </div>
    </section>
    @endsection @push('script')
    <script src="{{ asset('public/frontEnd/') }}/js/parsley.min.js"></script>
    <script src="{{ asset('public/frontEnd/') }}/js/form-validation.init.js"></script>
    <script src="{{ asset('public/frontEnd/') }}/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".select2").select2();
        });
    </script>

    @foreach ($paymentmethods as $key => $value)
        <script>
            $(document).ready(function() {
                $('#{{ $value->slug }}').on('input focus', function() {
                    $('.payment-form').hide();
                    $('.{{ $value->slug }}-form').show();
                    $('.trxform').show();
                    $('#sender_number').attr('required', true);
                    $('#trx_id').attr('required', true);
                });
            });
        </script>
    @endforeach
    <script>
        $(document).ready(function() {
            $('#cod').on('input focus', function() {
                $('.payment-form').hide();
                $('.trxform').hide();
                $('#sender_number').removeAttr('required');
                $('#trx_id').removeAttr('required');
            });
            $('#bKash').on('input focus', function() {
                $('.payment-form').hide();
                $('.trxform').hide();
                $('#sender_number').removeAttr('required');
                $('#trx_id').removeAttr('required');
            });
        });
    </script>
    {{-- <script>
        $("#area").on("change", function() {
            var id = $(this).val();
            $.ajax({
                type: "GET",
                data: {
                    id: id
                },
                url: "{{ route('shipping.charge') }}",
                dataType: "html",
                success: function(response) {
                    $(".cartlist").html(response);
                },
            });
        });
    </script> --}}
    <script>
        var firstItem = $(".area-item").first();
        firstItem.addClass("active");
        var firstRadioInput = firstItem.find("input[type='radio']").first();
        firstRadioInput.prop("checked", true);

        $(".area-item").on("click", function() {
            var id = $(this).data("id");
            $(".area-item").removeClass('active');
            $(this).addClass('active');
            $.ajax({
                type: "GET",
                data: {
                    id: id
                },
                url: "{{ route('shipping.charge') }}",
                dataType: "html",
                success: function(response) {
                    $(".cartlist").html(response);
                },
            });
        });
    </script>
    <script type="text/javascript">
        dataLayer.push({
            ecommerce: null
        }); // Clear the previous ecommerce object.
        dataLayer.push({
            event: "view_cart",
            ecommerce: {
                value: {{ $subtotal + $shipping - ($discount + $coupon) }},
                items: [
                    @foreach (Cart::instance('shopping')->content() as $cartInfo)
                        {
                            item_name: "{{ $cartInfo->name }}",
                            item_id: "{{ $cartInfo->id }}",
                            price: "{{ $cartInfo->price }}",
                            item_brand: "{{ $cartInfo->options->brand }}",
                            item_category: "{{ $cartInfo->options->category }}",
                            item_size: "{{ $cartInfo->options->size }}",
                            item_color: "{{ $cartInfo->options->color }}",
                            currency: "BDT",
                            quantity: {{ $cartInfo->qty ?? 0 }}
                        },
                    @endforeach
                ]
            }
        });
    </script>
    <script type="text/javascript">
        // Clear the previous ecommerce object.
        dataLayer.push({
            ecommerce: null
        });

        // Push the begin_checkout event to dataLayer.
        dataLayer.push({
            event: "begin_checkout",
            ecommerce: {
                value: {{ $subtotal + $shipping - ($discount + $coupon) }},
                currency: "BDT",
                items: [
                    @foreach (Cart::instance('shopping')->content() as $cartInfo)
                        {
                            item_name: "{{ $cartInfo->name }}",
                            item_id: "{{ $cartInfo->id }}",
                            price: "{{ $cartInfo->price }}",
                            item_brand: "{{ $cartInfo->options->brands }}",
                            item_category: "{{ $cartInfo->options->category }}",
                            item_size: "{{ $cartInfo->options->size }}",
                            item_color: "{{ $cartInfo->options->color }}",
                            quantity: {{ $cartInfo->qty ?? 0 }}
                        },
                    @endforeach
                ]
            }
        });
    </script>
    <script>
        $("#initialSubmitButton").on("click", function() {
            var phone_number = $('#number').val();
            if (phone_number.length === 11) {

                $('#otpSection').show();
                $('#finalSubmitButton').show();
                $('#initialSubmitButton').hide();
                $('#otp').prop('required', true);
                $.ajax({
                    type: "GET",
                    data: {
                        phone_number
                    },
                    url: "{{ route('customer.order_otp') }}",
                    dataType: "html",
                    success: function(response) {
                        toastr.success("OTP Send", "Please check your phone number");
                    },
                });
            } else {
                toastr.error("Please input 11 digit phone number");
            }
        });
    </script>
@endpush
