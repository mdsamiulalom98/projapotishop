@extends('frontEnd.layouts.master')
@section('title', $childcategory->meta_title)
@push('css')
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/jquery-ui.css') }}" />
@endpush
@push('seo')
    <meta name="app-url" content="{{ route('products', $childcategory->slug) }}" />
    <meta name="robots" content="index, follow" />
    <meta name="description" content="{{ $childcategory->meta_description }}" />
    <meta name="keywords" content="{{ $childcategory->childcategoryName }}" />
    <meta name="title" content="{{ $childcategory->meta_title }}" />

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product" />
    <meta name="twitter:site" content="{{ $childcategory->childcategoryName }}" />
    <meta name="twitter:title" content="{{ $childcategory->meta_title }}" />
    <meta name="twitter:description" content="{{ $childcategory->meta_description }}" />
    <meta name="twitter:creator" content="projapotishop.com" />
    <meta property="og:url" content="{{ route('products', $childcategory->slug) }}" />
    <meta name="twitter:image" content="{{ asset($childcategory->image) }}" />

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $childcategory->meta_title }}" />
    <meta property="og:type" content="product" />
    <meta property="og:url" content="{{ route('products', $childcategory->slug) }}" />
    <meta property="og:image" content="{{ asset($childcategory->image) }}" />
    <meta property="og:description" content="{{ $childcategory->meta_description }}" />
    <meta property="og:site_name" content="{{ $childcategory->childcategoryName }}" />
@endpush
@section('content')
    <section class="homeproduct product-section">
        <div class="container">
            <div class="sorting-section">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="category-breadcrumb d-flex align-items-center">
                            <a href="{{ route('home') }}">Home</a>
                            <span>/</span>
                            <strong>{{ $childcategory->childcategoryName }}</strong>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="showing-data">
                                    <span>Showing {{ $products->firstItem() }}-{{ $products->lastItem() }} of
                                        {{ $products->total() }} Results</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="filter_sort">
                                    <div class="filter_btn">
                                        <i class="fa fa-list-ul"></i>
                                    </div>
                                    <div class="page-sort">
                                        <form action="" class="sort-form">
                                            <select name="sort" class="form-control form-select sort">
                                                <option value="1" @if (request()->get('sort') == 1) selected @endif>
                                                    Product: Latest</option>
                                                <option value="2" @if (request()->get('sort') == 2) selected @endif>
                                                    Product: Oldest</option>
                                                <option value="3" @if (request()->get('sort') == 3) selected @endif>
                                                    Price: High To Low</option>
                                                <option value="4" @if (request()->get('sort') == 4) selected @endif>
                                                    Price: Low To High</option>
                                                <option value="5" @if (request()->get('sort') == 5) selected @endif>
                                                    Name: A-Z</option>
                                                <option value="6" @if (request()->get('sort') == 6) selected @endif>
                                                    Name: Z-A</option>
                                            </select>
                                            <input type="hidden" name="min_price"
                                                value="{{ request()->get('min_price') }}" />
                                            <input type="hidden" name="max_price"
                                                value="{{ request()->get('max_price') }}" />
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-3 filter_sidebar">
                    <div class="filter_close"><i class="fa fa-long-arrow-left"></i> Filter</div>
                    <form action="" class="attribute-submit">
                        <div class="sidebar_item wraper__item">
                            <div class="accordion" id="category_sidebar">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseCat" aria-expanded="true" aria-controls="collapseOne">
                                            {{ $childcategory->childcategoryName }}
                                        </button>
                                    </h2>
                                    <div id="collapseCat" class="accordion-collapse collapse show"
                                        data-bs-parent="#category_sidebar">
                                        <div class="accordion-body cust_according_body">
                                            <ul>
                                                @foreach ($childcategories as $key => $childcat)
                                                    <li>
                                                        <a
                                                            href="{{ url('products/' . $childcat->slug) }}">{{ $childcat->childcategoryName }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--sidebar item end-->
                        @if ($products->count() > 0)
                            <div class="sidebar_item wraper__item">
                                <div class="accordion" id="price_sidebar">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapsePrice" aria-expanded="true"
                                                aria-controls="collapseOne">
                                                Price
                                            </button>
                                        </h2>
                                        <div id="collapsePrice" class="accordion-collapse collapse show"
                                            data-bs-parent="#price_sidebar">
                                            <div class="accordion-body cust_according_body">
                                                <div class="category-filter-box category__wraper" id="categoryFilterBox">
                                                    <div class="category-filter-item">
                                                        <div class="filter-body">
                                                            <div class="slider-box">
                                                                <div class="filter-price-inputs">
                                                                    <p class="min-price">৳<input type="text"
                                                                            name="min_price" id="min_price"
                                                                            readonly="" />
                                                                    </p>
                                                                    <p class="max-price">৳<input type="text"
                                                                            name="max_price" id="max_price"
                                                                            readonly="" />
                                                                    </p>
                                                                </div>
                                                                <div id="price-range" class="slider form-attribute"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <!--sidebar item end-->
                        <!--sidebar brand end-->
                        <div class="sidebar_item wraper__item">
                            <div class="accordion" id="filter_sidebar">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseFilter1" aria-expanded="true"
                                            aria-controls="collapseOne">
                                            Brand
                                        </button>
                                    </h2>
                                    <div id="collapseFilter1" class="accordion-collapse collapse show"
                                        data-bs-parent="#filter_sidebar">
                                        <div class="accordion-body cust_according_body">
                                            <div class="filter-body">
                                                <form action="" class="brand-submit">
                                                    <ul class="space-y-3">
                                                        @foreach ($brands as $key => $value)
                                                            <li class="subcategory-filter-list">
                                                                <label for="{{ $loop->iteration . '-' . $key }}"
                                                                    class="subcategory-filter-label">
                                                                    <input class="brand-checkbox form-attribute"
                                                                        id="{{ $loop->iteration . '-' . $key }}"
                                                                        name="brand[]" value="{{ $value->id }}"
                                                                        type="checkbox"
                                                                        @if (is_array(request()->get('brand')) && in_array($value->id, request()->get('brand'))) checked @endif />
                                                                    <p class="brand-filter-name">
                                                                        {{ $value->name }}</p>
                                                                </label>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--sidebar brand end-->
                    </form>
                </div>
                <div class="col-sm-9">
                    <div class="product_sliders">
                        @foreach ($products as $key => $value)
                            <div class="product_item wist_item">
                                @include('frontEnd.layouts.partials.product')
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="custom_paginate">
                        {{ $products->links('pagination::bootstrap-4') }}

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
    <script>
        $("#price-range").click(function() {
            $(".price-submit").submit();
        })
        $(".form-attribute").on('change click', function() {
            $(".attribute-submit").submit();
        })
        $(".sort").change(function() {
            $(".sort-form").submit();
        })
        $(".form-checkbox").change(function() {
            $(".subcategory-submit").submit();
        })
        $(".brand-checkbox").change(function() {
            $(".brand-submit").submit();
        })
    </script>
    <script>
        $(function() {
            $("#price-range").slider({
                step: 5,
                range: true,
                min: {{ $min_price }},
                max: {{ $max_price }},
                values: [
                    {{ request()->get('min_price') ? request()->get('min_price') : $min_price }},
                    {{ request()->get('max_price') ? request()->get('max_price') : $max_price }}
                ],
                slide: function(event, ui) {
                    $("#min_price").val(ui.values[0]);
                    $("#max_price").val(ui.values[1]);
                }
            });
            $("#min_price").val({{ request()->get('min_price') ? request()->get('min_price') : $min_price }});
            $("#max_price").val({{ request()->get('max_price') ? request()->get('max_price') : $max_price }});
            $("#priceRange").val($("#price-range").slider("values", 0) + " - " + $("#price-range").slider("values",
                1));

            $("#mobile-price-range").slider({
                step: 5,
                range: true,
                min: {{ $min_price }},
                max: {{ $max_price }},
                values: [
                    {{ request()->get('min_price') ? request()->get('min_price') : $min_price }},
                    {{ request()->get('max_price') ? request()->get('max_price') : $max_price }}
                ],
                slide: function(event, ui) {
                    $("#min_price").val(ui.values[0]);
                    $("#max_price").val(ui.values[1]);
                }
            });
            $("#min_price").val({{ request()->get('min_price') ? request()->get('min_price') : $min_price }});
            $("#max_price").val({{ request()->get('max_price') ? request()->get('max_price') : $max_price }});
            $("#priceRange").val($("#price-range").slider("values", 0) + " - " + $("#price-range").slider("values",
                1));

        });
    </script>
@endpush
