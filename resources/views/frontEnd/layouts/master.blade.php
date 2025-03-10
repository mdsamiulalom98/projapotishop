<!DOCTYPE html>

<meta name="google-site-verification" content="w-8P-z3yDwtpLjhc5Mhp0QfDYz8xxSSTRJQmcLGPQXg" />
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<title>@yield('title') - {{ $generalsetting->name }}</title>
<!-- App favicon -->

<link rel="shortcut icon" href="{{ asset($generalsetting->favicon) }}" alt="{{ $generalsetting->name }}" />
<meta name="author" content="{{ $generalsetting->name }}" />
<link rel="canonical" href="{{ route('home') }}" />
@stack('seo')
@stack('css')
<link rel="stylesheet" href="{{ asset('public/frontEnd/css/bootstrap.min.css') }}" />
<link rel="stylesheet" href="{{ asset('public/frontEnd/css/animate.css') }}" />
<link rel="stylesheet" href="{{ asset('public/frontEnd/css/woodmart-font.css') }}" />
<link rel="stylesheet" href="{{ asset('public/frontEnd/css/all.min.css') }}" />
<link rel="stylesheet" href="{{ asset('public/frontEnd/css/owl.carousel.min.css') }}" />
<link rel="stylesheet" href="{{ asset('public/frontEnd/css/owl.theme.default.min.css') }}" />
<link rel="stylesheet" href="{{ asset('public/frontEnd/css/mobile-menu.css') }}" />
<link rel="stylesheet" href="{{ asset('public/frontEnd/css/select2.min.css') }}" />
<!-- toastr css -->
<link rel="stylesheet" href="{{ asset('public/backEnd/') }}/assets/css/toastr.min.css" />

<link rel="stylesheet" href="{{ asset('public/frontEnd/css/wsit-menu.css') }}" />
<link rel="stylesheet" href="{{ asset('public/frontEnd/css/style.css?v=2.0.22') }}" />
<link rel="stylesheet" href="{{ asset('public/frontEnd/css/responsive.css?v=2.0.13') }}" />
<link rel="stylesheet" href="{{ asset('public/frontEnd/css/main.css') }}" />

<!-- sc -->

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-EHHESR0YN5"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-EHHESR0YN5');
</script>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-EHHESR0YN5"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-EHHESR0YN5');
</script>
<!-- sc -->
@foreach($pixels as $pixel)
<!-- Meta Pixel Code -->
<script>
    ! function(f, b, e, v, n, t, s) {
        if (f.fbq) return;
        n = f.fbq = function() {
            n.callMethod ?
                n.callMethod.apply(n, arguments) : n.queue.push(arguments)
        };
        if (!f._fbq) f._fbq = n;
        n.push = n;
        n.loaded = !0;
        n.version = '2.0';
        n.queue = [];
        t = b.createElement(e);
        t.async = !0;
        t.src = v;
        s = b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t, s)
    }(window, document, 'script',
        'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '{{ $pixel->code }}');
    fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id={{ $pixel->code }}&ev=PageView&noscript=1" /></noscript>
<!-- End Meta Pixel Code -->
@endforeach
@foreach ($gtm_code as $gtm)
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })
        (window, document, 'script', 'dataLayer', '{{ $gtm->code }}');
    </script>
    <!-- End Google Tag Manager -->
    <!-- Google tag (gtag.js) -->
@endforeach

<style>
    .desc-nav-ul li a.active {
        background-color: #0a3a66;
        color: #fff;
    }
</style>
</head>

<body class="gotop wsit-nav-enabled">
    @php
        $subtotal = Cart::instance('shopping')->subtotal();
    @endphp
    <div class="wsit-sticky-nav">
        <div class="wsit-sticky-nav-title">
            <span>All Categories</span>
        </div>
        <ul class="wsit-nav-sticky">
            @foreach ($menucategories as $scategory)
                <li class="{{ $scategory->subcategories->count() > 0 ? 'menu-item-has-children' : '' }}">
                    <a href="{{ route('category', $scategory->slug) }}">
                        <img class="wsit-nav-img" src="{{ asset($scategory->image) }}" alt="">
                        <span class="nav-link-text">{{ $scategory->name }}</span>
                    </a>
                    @if ($scategory->subcategories->count() > 0)
                        <span class="nav-link-icon">
                            <i class="fa fa-chevron-right"></i>
                        </span>
                        <div class="subcategory-sidemenu" style="display: none;">
                            <ul>
                                @foreach ($scategory->subcategories as $subcategory)
                                    <li>
                                        <a href="{{ route('subcategory', $subcategory->slug) }}">
                                            {{ $subcategory->subcategoryName }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>

    <div class="mobile-menu">
        <div class="mobile-menu-logo">
            <div class="logo-image">
                <img src="{{ asset($generalsetting->white_logo) }}" alt="" />
            </div>
            <div class="mobile-menu-close">
                <i class="fa fa-times"></i>
            </div>
        </div>
        <ul class="first-nav">
            @foreach ($menucategories as $scategory)
                <li class="parent-category">
                    <a href="{{ url('category/' . $scategory->slug) }}" class="menu-category-name">
                        <img src="{{ asset($scategory->image) }}" alt="" class="side_cat_img" />
                        {{ $scategory->name }}
                    </a>
                    @if ($scategory->subcategories->count() > 0)
                        <span class="menu-category-toggle">
                            <i class="fa fa-chevron-down"></i>
                        </span>
                    @endif
                    <ul class="second-nav" style="display: none;">
                        @foreach ($scategory->subcategories as $subcategory)
                            <li class="parent-subcategory">
                                <a href="{{ url('subcategory/' . $subcategory->slug) }}"
                                    class="menu-subcategory-name">{{ $subcategory->subcategoryName }}</a>
                                @if ($subcategory->childcategories->count() > 0)
                                    <span class="menu-subcategory-toggle"><i class="fa fa-chevron-down"></i></span>
                                @endif
                                <ul class="third-nav" style="display: none;">
                                    @foreach ($subcategory->childcategories as $childcat)
                                        <li class="childcategory"><a href="{{ url('products/' . $childcat->slug) }}"
                                                class="menu-childcategory-name">{{ $childcat->childcategoryName }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
        </ul>
    </div>
    <header id="navbar_top">
        <div class="mobile-header">
            <div class="mobile-logo">
                <div class="menu-bar">
                    <a class="toggle">
                        <i class="fa-solid fa-bars"></i>
                    </a>
                </div>
                <div class="menu-logo">
                    <a href="{{ route('home') }}"><img src="{{ asset($generalsetting->white_logo) }}"
                            alt="" /></a>
                </div>
                <div class="menu-bag">
                    <a href="{{ route('customer.checkout') }}" class="margin-shopping">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span class="mobilecart-qty">{{ Cart::instance('shopping')->count() }}</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="mobile-search">
            <form id="searchForm" action="{{ route('search') }}">
                <input type="text" placeholder="Search Product ... " value=""
                    class="msearch_keyword msearch_click src" name="keyword" />
                <button><i data-feather="search"></i></button>
            </form>
            <div class="search_result"></div>
        </div>

        <div class="main-header">
            <!-- header to end -->
            <div class="logo-area">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="logo-header">
                                <div class="main-logo">
                                    <a href="{{ route('home') }}"><img
                                            src="{{ asset($generalsetting->white_logo) }}" alt="" /></a>
                                </div>
                                <div class="main-search">
                                    <form id="MainSearch" action="{{ route('search') }}">
                                        <input type="text" placeholder="Search Product..."
                                            class="search_keyword search_click mainsrc" name="keyword" />
                                        <button>
                                            <i data-feather="search"></i>
                                        </button>
                                    </form>
                                    <div class="search_result"></div>
                                </div>
                                <div class="header-list-items">
                                    <div class="helpline-wrapper">
                                        <div class="icon">
                                            <img src="{{ asset('public/frontEnd/images/phone-call-chat.png') }}" />
                                        </div>
                                        <div class="text">
                                            <h4>24 Support</h4>
                                            <a href="tel:{{ $contact->hotline }}">{{ $contact->hotline }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="menu-area">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 d-flex justify-content-between">
                            <div class="catagory_menu">
                                <ul>
                                    <li>
                                        <a class="desktop-toggle">
                                            <div class="category-toggle-wrapper">
                                                <div class="toggle-round">

                                                </div>
                                                <strong>All Categories</strong>
                                            </div>
                                        </a>
                                    </li>
                                    {{--
                                        @foreach ($menucategories as $scategory)
                                        <li class="cat_bar ">
                                            <a href="{{ url('category/' . $scategory->slug) }}">
                                                <span class="cat_head">{{ $scategory->name }}</span>
                                                @if ($scategory->subcategories->count() > 0)
                                                <i class="fa-solid fa-angle-down cat_down"></i>
                                                @endif
                                            </a>
                                            @if ($scategory->subcategories->count() > 0)
                                            <ul class="Cat_menu">
                                                @foreach ($scategory->subcategories as $subcat)
                                                <li class="Cat_list cat_list_hover">
                                                    <a href="{{ url('subcategory/' . $subcat->slug) }}">
                                                        <span>{{ Str::limit($subcat->subcategoryName, 25) }}</span>
                                                        @if ($subcat->childcategories->count() > 0)<i class="fa-solid fa-chevron-right cat_down"></i>@endif
                                                    </a>
                                                    @if ($subcat->childcategories->count() > 0)
                                                    <ul class="child_menu">
                                                        @foreach ($subcat->childcategories as $childcat)
                                                        <li class="child_main">
                                                            <a href="{{ url('products/'.$childcat->slug) }}">{{ $childcat->childcategoryName }}</a>

                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                    @endif
                                                </li>
                                                @endforeach
                                            </ul>
                                            @endif
                                        </li>
                                        @endforeach
                                        --}}
                                    <li>
                                        <a href="{{ route('videogallery') }}" class="">
                                            Video Gallery
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('page', 'about-us') }}" class="">
                                            About Us
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('contact') }}" class="">
                                            Contact Us
                                        </a>
                                    </li>
                                </ul>


                            </div>
                            <div class="header-action-menu">
                                <div class="action-item">
                                    <a href="{{ route('customer.order_track') }}">
                                        <img class="track-image"
                                            src="{{ asset('public/frontEnd/images/truck-icon.png') }}">
                                    </a>
                                </div>

                                @if (Auth::guard('customer')->user())
                                    <div class="action-item">
                                        <a href="{{ route('customer.account') }}"
                                            title="{{ Str::limit(Auth::guard('customer')->user()->name, 14) }}">
                                            <i class="woodmart woodmart-user"></i>
                                        </a>
                                    </div>
                                @else
                                    <div class="action-item">
                                        <a href="{{ route('customer.login') }}" title="Login / Sign Up">
                                            <i class="woodmart woodmart-user"></i>
                                        </a>
                                    </div>
                                @endif
                                <div class="action-item" id="cart-qty">
                                    <a href="">
                                        <i class="woodmart woodmart-cart"></i>
                                        <span>{{ Cart::instance('shopping')->count() }}</span>
                                    </a>
                                    <div class="cshort-summary">
                                        <ul>
                                            @foreach (Cart::instance('shopping')->content() as $key => $value)
                                                <li>
                                                    <a href=""><img src="{{ asset($value->options->image) }}"
                                                            alt="" /></a>
                                                </li>
                                                <li><a href="">{{ Str::limit($value->name, 30) }}</a></li>
                                                <li>Qty: {{ $value->qty }}</li>
                                                <li>
                                                    <p>৳{{ $value->price }}</p>
                                                    <button class="remove-cart cart_remove"
                                                        data-id="{{ $value->rowId }}"><i
                                                            class="fa-regular fa-trash-can trash_icon"
                                                            title="Delete this item"></i></button>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <p><strong>সর্বমোট : ৳{{ $subtotal }}</strong></p>
                                        <a href="{{ route('customer.checkout') }}" class="go_cart"> অর্ডার করুন </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- main-header end -->
    </header>
    <div id="content">
        @yield('content')
    </div>
    <!-- content end -->
    <footer>
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="footer_inner">
                            <div class="footer-about footer-logo">
                                <a href="{{ route('home') }}" class="text-center">
                                    <img src="{{ asset($generalsetting->white_logo) }}" alt="" />
                                </a>
                                <p class="footer_des">{!! $generalsetting->description !!}</p>
                            </div>
                            <div class="footer-about">

                                <li class="con_title"><a>Contact Us</a></li>
                                <p><i class="fa-solid fa-map"></i>{{ $contact->address }}</p>
                                <p><i class="fa-solid fa-headphones"></i><a href="tel:{{ $contact->hotline }}"
                                        class="footer-hotlint">{{ $contact->hotline }}</a></p>
                                <p><i class="fa-solid fa-envelope"></i><a href="mailto:{{ $contact->hotmail }}"
                                        class="footer-hotlint">{{ $contact->hotmail }}</a></p>
                            </div>
                            <div class="footer-menu useful-link">
                                <ul>
                                    <li class="title"><a>Useful Link</a></li>
                                    <li>
                                        <a href="{{ route('contact') }}"> <a href="{{ route('contact') }}">Contact
                                                Us</a></a>
                                    </li>
                                    @foreach ($cmnmenu as $page)
                                        <li><a
                                                href="{{ route('page', ['slug' => $page->slug]) }}">{{ $page->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="footer-menu">
                                <ul>
                                    <li class="title stay_conn"><a>Stay Connected</a></li>
                                </ul>
                                <ul class="social_link">
                                    @foreach ($socialicons as $value)
                                        <li class="social_list">
                                            <a style="background: {{ $value->color }}" class="mobile-social-link"
                                                href="{{ $value->link }}"><i class="{{ $value->icon }}"></i></a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="footer-menu">
                                <ul>
                                    <li class="title"><a>Our Facebook Page</a></li>
                                    <div>
                                        <div class="fb-page" data-href="{{ $contact->facebook }}"
                                            data-tabs="timeline" data-height="150" data-small-header="false"
                                            data-adapt-container-width="true" data-hide-cover="false"
                                            data-show-facepile="true">
                                            <blockquote cite="{{ $contact->facebook }}"
                                                class="fb-xfbml-parse-ignore"><a
                                                    href="{{ $contact->facebook }}">Facebook</a></blockquote>
                                        </div>
                                    </div>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- col end -->
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-sm-9">
                        <div class="copyright">
                            <p>{!! $generalsetting->copyright !!}</p>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="copyright-img">
                            <img src="{{ asset('public/frontEnd/images/payment2.png') }}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--=====-->
    <div class="fixed_whats">
        <a href="https://api.whatsapp.com/send?phone={{ $contact->hotline }}" target="_blank"><i
                class="fa-brands fa-whatsapp"></i></a>
    </div>
    <!--=========-->
    <div class="footer_nav">
        <ul>
            <li>
                <a class="toggle">
                    <span>
                        <i class="fa-solid fa-bars"></i>
                    </span>
                    <span>Category</span>
                </a>
            </li>

            <li>
                <a href="{{ $contact->facebook }}">
                    <span>
                        <i class="fa-brands fa-facebook"></i>
                    </span>
                    <span>Facebook</span>
                </a>
            </li>

            <li class="mobile_home">
                <a href="{{ route('home') }}">
                    <span><i class="fa-solid fa-home"></i></span> <span>Home</span>
                </a>
            </li>

            <li>
                <a href="{{ route('customer.checkout') }}">
                    <span>
                        <i class="fa-solid fa-cart-shopping"></i>
                    </span>
                    <span>Cart (<b class="mobilecart-qty">{{ Cart::instance('shopping')->count() }}</b>)</span>
                </a>
            </li>
            @if (Auth::guard('customer')->user())
                <li>
                    <a href="{{ route('customer.account') }}">
                        <span>
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <span>Account</span>
                    </a>
                </li>
            @else
                <li>
                    <a href="{{ route('customer.login') }}">
                        <span>
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <span>Login</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>


    <div class="scrolltop" style="">
        <div class="scroll">
            <i class="fa fa-angle-up"></i>
        </div>
    </div>

    <!-- /. fixed sidebar -->

    <div id="custom-modal"></div>
    <div id="page-overlay"></div>
    <div id="loading">
        <div class="custom-loader"></div>
    </div>

    <script src="{{ asset('public/frontEnd/js/jquery-3.6.3.min.js') }}"></script>
    <script src="{{ asset('public/frontEnd/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/frontEnd/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('public/frontEnd/js/mobile-menu.js') }}"></script>
    <script src="{{ asset('public/frontEnd/js/wsit-menu.js') }}"></script>
    <script src="{{ asset('public/frontEnd/js/mobile-menu-init.js') }}"></script>
    <script src="{{ asset('public/frontEnd/js/wow.min.js') }}"></script>
    <script>
        new WOW().init();
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- feather icon -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js"></script>
    <script>
        feather.replace();
    </script>
    <script src="{{ asset('public/backEnd/') }}/assets/js/toastr.min.js"></script>
    {!! Toastr::message() !!}
    @stack('script')
    <script>
        $(".quick_view").on("click", function() {
            var id = $(this).data("id");
            $("#loading").show();
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        id: id
                    },
                    url: "{{ route('quickview') }}",
                    success: function(data) {
                        if (data) {
                            $("#custom-modal").html(data);
                            $("#custom-modal").show();
                            $("#loading").hide();
                            $("#page-overlay").show();
                        }
                    },
                });
            }
        });
    </script>
    <!-- quick view end -->
    <!-- cart js start -->
    <script>
        $(".addcartbutton").on("click", function() {
            var id = $(this).data("id");
            var qty = 1;
            if (id) {
                $.ajax({
                    cache: "false",
                    type: "GET",
                    url: "{{ url('add-to-cart') }}/" + id + "/" + qty,
                    dataType: "json",
                    success: function(data) {
                        if (data) {
                            toastr.success('Success', 'Product add to cart successfully');
                            return cart_count() + mobile_cart();
                        }
                    },
                });
            }
        });
        $(".cart_store").on("click", function() {
            var id = $(this).data("id");
            var qty = $(this).parent().find("input").val();
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        id: id,
                        qty: qty ? qty : 1
                    },
                    url: "{{ route('cart.store') }}",
                    success: function(data) {
                        if (data) {
                            toastr.success('Success', 'Product add to cart succfully');
                            return cart_count() + mobile_cart();
                        }
                    },
                });
            }
        });

        $(document).on('click', '.cart_remove', function(e) {
            e.preventDefault();
            var id = $(this).data("id");
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        id: id
                    },
                    url: "{{ route('cart.remove') }}",
                    success: function(data) {
                        if (data) {
                            $(".cartlist").html(data);
                            return cart_count() + mobile_cart() + cart_summary();
                        }
                    },
                });
            }
        });

        $(document).on('click', '.cart_increment', function(e) {
            e.preventDefault();
            var id = $(this).data("id");
            var product = $(this).data("product");
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        id: id,
                        product: product
                    },
                    url: "{{ route('cart.increment') }}",
                    success: function(data) {
                        if (data) {
                            $(".cartlist").html(data);
                            return cart_count() + mobile_cart();
                        }
                    },
                });
            }
        });

        $(document).on('click', '.cart_decrement', function(e) {
            e.preventDefault();
            var id = $(this).data("id");
            var product = $(this).data("product");
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        id: id,
                        product: product
                    },
                    url: "{{ route('cart.decrement') }}",
                    success: function(data) {
                        if (data) {
                            $(".cartlist").html(data);
                            return cart_count() + mobile_cart();
                        }
                    },
                });
            }
        });

        function cart_count() {
            $.ajax({
                type: "GET",
                url: "{{ route('cart.count') }}",
                success: function(data) {
                    if (data) {
                        $("#cart-qty").html(data);
                    } else {
                        $("#cart-qty").empty();
                    }
                },
            });
        }

        function mobile_cart() {
            $.ajax({
                type: "GET",
                url: "{{ route('mobile.cart.count') }}",
                success: function(data) {
                    if (data) {
                        $(".mobilecart-qty").html(data);
                    } else {
                        $(".mobilecart-qty").empty();
                    }
                },
            });
        }

        function cart_summary() {
            $.ajax({
                type: "GET",
                url: "{{ route('shipping.charge') }}",
                dataType: "html",
                success: function(response) {
                    $(".cart-summary").html(response);
                },
            });
        }
    </script>
    <!-- cart js end -->
    <script>
        $(document).ready(function() {
            $(document).on("submit", function(event) {
                if ($(event.target).is("#searchForm, #MainSearch")) {
                    event.preventDefault();

                    var keywordInput = $(event.target).find("input[name='keyword']");
                    var keyword = keywordInput.val().trim();

                    if (keyword === "") {
                        toastr.error('Please enter a search keyword.');
                        return false;
                    } else {
                        event.target.submit();
                    }
                }
            });
        });
        $(".search_click").on("keyup change", function() {
            var keyword = $(".search_keyword").val();
            $.ajax({
                type: "GET",
                data: {
                    keyword: keyword
                },
                url: "{{ route('livesearch') }}",
                success: function(products) {
                    if (products) {
                        $(".search_result").html(products);
                    } else {
                        $(".search_result").empty();
                    }
                },
            });
        });
        $(".msearch_click").on("keyup change", function() {
            var keyword = $(".msearch_keyword").val();
            $.ajax({
                type: "GET",
                data: {
                    keyword: keyword
                },
                url: "{{ route('livesearch') }}",
                success: function(products) {
                    if (products) {
                        $("#loading").hide();
                        $(".search_result").html(products);
                    } else {
                        $(".search_result").empty();
                    }
                },
            });
        });
    </script>
    <!-- search js start -->
    <script></script>
    <script></script>
    <script>
        $(".district").on("change", function() {
            var id = $(this).val();
            $.ajax({
                type: "GET",
                data: {
                    id: id
                },
                url: "{{ route('districts') }}",
                success: function(res) {
                    if (res) {
                        $(".area").empty();
                        $(".area").append('<option value="">Select..</option>');
                        $.each(res, function(key, value) {
                            $(".area").append('<option value="' + key + '" >' + value +
                                "</option>");
                        });
                    } else {
                        $(".area").empty();
                    }
                },
            });
        });
    </script>
    <script>
        $(".toggle").on("click", function() {
            $("#page-overlay").show();
            $(".mobile-menu").addClass("active");
        });

        $(".desktop-toggle").on("click", function() {
            $(".wsit-sticky-nav").addClass("wsit-opened");
        });

        $(".wsit-sticky-nav").hover(function() {
            $(".wsit-sticky-nav").removeClass("wsit-opened");
        });

        $("#page-overlay").on("click", function() {
            $("#page-overlay").hide();
            $(".mobile-menu").removeClass("active");
            $(".feature-products").removeClass("active");
        });

        $(".mobile-menu-close").on("click", function() {
            $("#page-overlay").hide();
            $(".mobile-menu").removeClass("active");
        });

        $(".mobile-filter-toggle").on("click", function() {
            $("#page-overlay").show();
            $(".feature-products").addClass("active");
        });
    </script>
    <script>
        $(document).ready(function() {
            $(".parent-category").each(function() {
                const menuCatToggle = $(this).find(".menu-category-toggle");
                const secondNav = $(this).find(".second-nav");

                menuCatToggle.on("click", function() {
                    menuCatToggle.toggleClass("active");
                    secondNav.slideToggle("fast");
                    $(this).closest(".parent-category").toggleClass("active");
                });
            });
            $(".parent-subcategory").each(function() {
                const menuSubcatToggle = $(this).find(".menu-subcategory-toggle");
                const thirdNav = $(this).find(".third-nav");

                menuSubcatToggle.on("click", function() {
                    menuSubcatToggle.toggleClass("active");
                    thirdNav.slideToggle("fast");
                    $(this).closest(".parent-subcategory").toggleClass("active");
                });
            });
            // desktop sidebar
            $(".menu-item-has-children").each(function() {
                const menuCatToggle = $(this).find(".nav-link-icon");
                const secondNav = $(this).find(".subcategory-sidemenu");

                menuCatToggle.on("click", function() {
                    menuCatToggle.toggleClass("active");
                    secondNav.slideToggle("fast");
                    $(this).closest(".menu-item-has-children").toggleClass("active");
                });
            });
            $(".parent-subcategory").each(function() {
                const menuSubcatToggle = $(this).find(".menu-subcategory-toggle");
                const thirdNav = $(this).find(".third-nav");

                menuSubcatToggle.on("click", function() {
                    menuSubcatToggle.toggleClass("active");
                    thirdNav.slideToggle("fast");
                    $(this).closest(".parent-subcategory").toggleClass("active");
                });
            });
        });
    </script>

    <script>
        var menu = new MmenuLight(document.querySelector("#menu"), "all");

        var navigator = menu.navigation({
            selectedClass: "Selected",
            slidingSubmenus: true,
            // theme: 'dark',
            title: "ক্যাটাগরি",
        });

        var drawer = menu.offcanvas({
            // position: 'left'
        });

        //  Open the menu.
        document.querySelector('a[href="#menu"]').addEventListener("click", (evnt) => {
            evnt.preventDefault();
            drawer.open();
        });
    </script>
    <script>
        $(".filter_btn").click(function() {
            $(".filter_sidebar").addClass('active');
            $("body").css("overflow-y", "hidden");
        })
        $(".filter_close").click(function() {
            $(".filter_sidebar").removeClass('active');
            $("body").css("overflow-y", "auto");
        })
    </script>

    <!-- Google Tag Manager (noscript) -->
    @foreach ($gtm_code as $gtm)
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtm->code }}" height="0"
                width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
    @endforeach
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous"
        src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v20.0&appId=740431513324176" nonce="DfzQTqZ6">
    </script>

</body>
<script>
    $(window).scroll(function() {
        if ($(this).scrollTop() > 50) {
            $(".scrolltop:hidden").stop(true, true).fadeIn();
        } else {
            $(".scrolltop").stop(true, true).fadeOut();
        }
    });
    $(function() {
        $(".scroll").click(function() {
            $("html,body").animate({
                scrollTop: $(".gotop").offset().top
            }, "1000");
            return false;
        });
    });
</script>
<script>
    function copyCouponCode() {
        var couponCode = document.getElementById("couponCode").innerText;
        var tempInput = document.createElement("input");
        tempInput.value = couponCode;
        document.body.appendChild(tempInput);
        tempInput.select();
        tempInput.setSelectionRange(0, 99999);
        document.execCommand("copy");
        document.body.removeChild(tempInput);
        toastr.success('Coupon Code copied successfully!');
    }
</script>

</html>
