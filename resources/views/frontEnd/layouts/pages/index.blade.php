@extends('frontEnd.layouts.master') @section('title', $generalsetting->meta_title) 
@push('seo')
<meta name="app-url" content="{{ route('home') }}" />
<meta name="robots" content="index, follow" />
<meta name="description" content="{{$generalsetting->meta_description}}" />
<meta name="keywords" content="{{$generalsetting->meta_tag}}" />

<!-- Open Graph data -->
<meta property="og:title" content="{{$generalsetting->meta_title}}" />
<meta property="og:type" content="website" />
<meta property="og:url" content="{{ route('home') }}" />
<meta property="og:image" content="{{ asset($generalsetting->white_logo) }}" />
<meta property="og:description" content="{{$generalsetting->meta_description}}" />
@endpush @push('css')
<link rel="stylesheet" href="{{ asset('public/frontEnd/css/owl.carousel.min.css') }}" />
<link rel="stylesheet" href="{{ asset('public/frontEnd/css/owl.theme.default.min.css') }}" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.css" rel="stylesheet" />
@endpush @section('content')
<section class="slider-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="home-slider-container">
                    <div class="main_slider owl-carousel">
                        @foreach ($sliders as $key => $value)
                            <div class="slider-item">
                                <img src="{{ asset($value->image) }}" alt="" />
                            </div>
                            <!-- slider item -->
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- slider end -->

<section class="homeproduct">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="sec_title">
                    <h3 class="section-title-header">
                        <div class="timer_inner">
                            <div class="">
                                <span class="section-title-name"> Top Categories </span>
                            </div>
                        </div>
                    </h3>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="topcategory">
                    @foreach ($menucategories as $key => $value)
                        <div class="cat_item">
                            <div class="cat_img">
                                <a href="{{ route('category', $value->slug) }}">
                                    <img src="{{ asset($value->image) }}" alt="" />
                                </a>
                            </div>
                            <div class="cat_name">
                                <a href="{{ route('category', $value->slug) }}">
                                    {{ $value->name }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>

<section class="homeproduct">
    <div class="container">
        <div class="row">
           <div class="col-sm-12">
                <div class="sec_title">
                <h3 class="section-title-header">
                    <div class="timer_inner">
                        <div class="">
                            <span class="section-title-name"> Hot Deal </span>
                        </div>

                        <div class="">
                            <div class="offer_timer" id="simple_timer"></div>
                        </div>
                    </div>
                </h3>
            </div>
           </div>
            <div class="col-sm-12">
                <div class="product_slider owl-carousel">
                    @foreach ($hotdeal_top as $key => $value)
                        <div class="product_item wist_item">
                            <div class="product_item_inner">
                                @if($value->variable_count > 0 && $value->type == 0)
                                    @if($value->variable->old_price)
                                        <div class="discount">
                                            <p>@php $discount=(((($value->variable->old_price)-($value->variable->new_price))*100) / ($value->variable->old_price)) @endphp -{{ number_format($discount, 0) }}%</p>
                                            
                                        </div>
                                    @endif
                                @else
                                    @if($value->old_price)
                                     <div class="discount">
                                        <p>@php $discount=(((($value->old_price)-($value->new_price))*100) / ($value->old_price)) @endphp -{{ number_format($discount, 0) }}%</p>
                                    </div>
                                    @endif
                                @endif
                                <div class="pro_img">
                                    <a href="{{ route('product', $value->slug) }}">
                                        <img src="{{ asset($value->image ? $value->image->image : '') }}"
                                            alt="{{ $value->name }}" />
                                    </a>
                                </div>
                                <div class="pro_des">
                                    <div class="pro_name">
                                        <a
                                            href="{{ route('product', $value->slug) }}">{{ Str::limit($value->name, 80) }}</a>
                                    </div>
                                    <div class="pro_price">
                                        @if($value->variable_count > 0 && $value->type == 0)
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

                            @if($value->variable_count > 0 && $value->type == 0)
                                <div class="pro_btn">
                                   
                                    <div class="cart_btn order_button">
                                        <a href="{{ route('product', $value->slug) }}"
                                            class="addcartbutton">অর্ডার করুন </a>
                                    </div>
                                </div>
                            @else
                                <div class="pro_btn">
                                    
                                    <form action="{{ route('cart.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $value->id }}" />
                                        <input type="hidden" name="qty" value="1" />
                                        <input type="hidden" name="order_now" value="অর্ডার করুন" />
                                        <button type="submit">অর্ডার করুন</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@foreach ($homecategory as $homecat)
    <section class="homeproduct">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                   <div class="title-inner">
                      <div class="section-title">
                           <h2>{{$homecat->name}}</h2>
                      </div>
                      <div class="section-btn">
                          <a href="{{ route('category', $homecat->slug) }}">View More</a>
                       </div>
                   </div>
               </div>
                @php
                    $products = App\Models\Product::where(['status' => 1, 'category_id' => $homecat->id])
                        ->orderBy('id', 'DESC')
                        ->select('id', 'name', 'slug', 'new_price', 'old_price','type')
                        ->withCount('variable')
                        ->limit(12)
                        ->get();
                @endphp
                <div class="col-sm-12">
                    <div class="product_sliders">
                        @foreach($products as $key => $value)
                         <div class="product_item wist_item">
                            <div class="product_item_inner">
                                @if($value->variable_count > 0 && $value->type == 0)
                                    @if($value->variable->old_price)
                                        <div class="discount">
                                            <p>@php $discount=(((($value->variable->old_price)-($value->variable->new_price))*100) / ($value->variable->old_price)) @endphp -{{ number_format($discount, 0) }}%</p>
                                            
                                        </div>
                                    @endif
                                    @else
                                    @if($value->old_price)
                                     <div class="discount">
                                        <p>@php $discount=(((($value->old_price)-($value->new_price))*100) / ($value->old_price)) @endphp -{{ number_format($discount, 0) }}%</p>
                                    </div>
                                    @endif
                                @endif
                                <div class="pro_img">
                                    <a href="{{ route('product', $value->slug) }}">
                                        <img src="{{ asset($value->image ? $value->image->image : '') }}"
                                            alt="{{ $value->name }}" />
                                    </a>
                                </div>
                                <div class="pro_des">
                                    <div class="pro_name">
                                        <a
                                            href="{{ route('product', $value->slug) }}">{{ Str::limit($value->name, 80) }}</a>
                                    </div>
                                     <div class="pro_price">
                                    @if($value->variable_count > 0 && $value->type == 0)
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

                            @if($value->variable_count > 0 && $value->type == 0)
                                <div class="pro_btn">
                                   
                                    <div class="cart_btn order_button">
                                        <a href="{{ route('product', $value->slug) }}"
                                            class="addcartbutton">অর্ডার করুন </a>
                                    </div>
                                </div>
                            @else
                                <div class="pro_btn">
                                    
                                    <form action="{{ route('cart.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $value->id }}" />
                                        <input type="hidden" name="qty" value="1" />
                                        <input type="hidden" name="order_now" value="অর্ডার করুন" />
                                        <button type="submit">অর্ডার করুন</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endforeach

@endsection @push('script')
<script src="{{ asset('public/frontEnd/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('public/frontEnd/js/jquery.syotimer.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $(".main_slider").owlCarousel({
            items: 1,
            loop: true,
            dots: false,
            autoplay: true,
            nav: false,
            autoplayHoverPause: false,
            margin: 0,
            mouseDrag: true,
            smartSpeed: 1000,
            autoplayTimeout: 4000
            
        });
    });
</script>
<script>
    $(document).ready(function() {
        $(".hotdeals-slider").owlCarousel({
            margin: 15,
            loop: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 6000,
            autoplayHoverPause: true,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 3,
                    nav: true,
                },
                600: {
                    items: 3,
                    nav: false,
                },
                1000: {
                    items: 6,
                    nav: true,
                    loop: false,
                },
            },
        });
    });
</script>

<script>
    $(document).ready(function() {

        $(".product_slider").owlCarousel({
            margin: 15,
            items: 6,
            loop: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 6000,
            autoplayHoverPause: true,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 2,
                    nav: false,
                },
                600: {
                    items: 5,
                    nav: false,
                },
                1000: {
                    items: 6,
                    nav: false,
                },
            },
        });
    });
</script>
<script>
    $("#simple_timer").syotimer({
        date: new Date(2015, 0, 1),
        layout: "hms",
        doubleNumbers: false,
        effectType: "opacity",

        periodUnit: "d",
        periodic: true,
        periodInterval: 1,
    });
</script>
@endpush
