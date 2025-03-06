@extends('layouts.frontend')
@section('content-frontend')
@include('frontend.common.add_to_cart_modal')
<main class="main">
    <div class="page-header">
        <div class="container-fluid">
            <div class="archive-header">
                <div class="row align-items-center">
                    <div class="col-12">
                        <h4 class="mb-5">
                            {{ $category->name_bn ?? '$category->name_en' }}
                        </h4>
                        <div class="breadcrumb">
                            <a href="{{ route('home') }}" rel="nofollow"><i class="fi-rs-home mr-5"></i>হোম</a>
                            <span></span>
                            {{ $category->name_bn ?? '$category->name_en' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid mb-30 category-wise-product">
        <div class="row">
            <div class="col-lg-4-5">
                <div class="shop-product-fillter">
                    <div class="totall-product">
                        <p>We found <strong class="text-brand">{{ count($products) }}</strong> items for you!</p>
                    </div>
                    <div class="sort-by-product-area">
                        <div class="sort-by-cover d-flex">
                            <div class="row">
                                <div class="form-group col-lg-6 col-12 col-md-6">
                                    <div class="custom_select">
                                        <select class="form-control select-active" onchange="updateBrandId(this.value); filter();" name="brand_id" id="brand_id_select">
                                            <option value="">All Brands</option>
                                            @foreach (\App\Models\Brand::all() as $brand)
                                                <option value="{{ $brand->id }}" @if ($brand_id == $brand->id) selected @endif>{{ $brand->name_en ?? 'Null' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-lg-6 col-12 col-md-6">
                                    <div class="custom_select">
                                        <select class="form-control select-active" name="sort_by" onchange="updateSortBy(this.value); filter();">
                                            <option value="newest" @if ($sort_by =='newest') selected @endif>Newest</option>
                                            <option value="price-asc" @if ($sort_by == 'price-asc') selected @endif>Price Low to High</option>
                                            <option value="price-desc" @if ($sort_by == 'price-desc') selected @endif>Price High to Low</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-3">
                    @foreach($products as $product)
                    <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6 col-sm-6 col-6">
                        <div class="special-image">
                           
                            <div class="speciall__images">
                                <div class="special-image-front">
                                    <a href="{{ route('product.details', $product->slug) }}"><img src="{{ asset($product->product_thumbnail) }}" alt="sd"></a>
                                </div>
        
                                <div class="special-image-back">
                                    @foreach ($product->multi_imgs->take(1) as $img)
                                        <a href="{{ route('product.details', $product->slug) }}"><img src="{{ asset($img->photo_name) }}" alt="sd"></a>
                                    @endforeach
                                </div>
                                
                                <div class="back-quick-view">
                                    <a aria-label="Quick view" id="{{ $product->id }}" onclick="productView(this.id)" class="action-btn" data-bs-toggle="modal" data-bs-target="#quickViewModal"><i class="fa-regular fa-eye"></i></a>
                                </div>
                            </div>
                            
    
                            <div class="special-image-content">
                                <a href="{{ route('product.details', $product->slug) }}">
                                    @if (session()->get('language') == 'bangla')
                                        <?php $p_name_bn = strip_tags(html_entity_decode($product->name_bn)); ?>
                                        {{ Str::limit($p_name_bn, $limit = 30, $end = '. . .') }}
                                    @else
                                        <?php $p_name_en = strip_tags(html_entity_decode($product->name_en)); ?>
                                        {{ Str::limit($p_name_en, $limit = 30, $end = '. . .') }}
                                    @endif
                                </a>
                                @php
                                   $reviews = \App\Models\Review::where('product_id', $product->id)
                                   ->where('status', 1)
                                   ->get();
                                   $averageRating = $reviews->avg('rating');
                                   $ratingCount = $reviews->count(); // Add this line to get the rating count
                               @endphp
                   
                               <div class="product__rating">
                                   @if ($reviews->isNotEmpty())
                                       @for ($i = 1; $i <= 5; $i++)
                                           @if ($i <= floor($averageRating))
                                               <i class="fa fa-star" style="color: #c90312;"></i>
                                           @elseif ($i == ceil($averageRating) && $averageRating - floor($averageRating) >= 0.5)
                                               {{-- Display a half-star with gradient --}}
                                               <i class="fa fa-star" style="background: linear-gradient(to right, #c90312 50%, gray 50%); -webkit-background-clip: text; color: transparent;"></i>
                                           @else
                                               <i class="fa fa-star" style="color: gray;"></i>
                                           @endif
                                       @endfor
                                   @else
                                       @for ($i = 1; $i <= 5; $i++)
                                           <i class="fa fa-star" style="color: gray;"></i>
                                       @endfor
                                   @endif
                                   <span class="rating-count">({{ number_format($averageRating, 1) }})</span>
                               </div>
                                <div class="price d-flex">
                                    <span>
                                        @php
                                            if (auth()->check() && auth()->user()->role == 7) {
                                                if ($product->discount_type == 1) {
                                                    $price_after_discount = $product->reseller_price - $product->discount_price;
                                                } elseif ($product->discount_type == 2) {
                                                    $price_after_discount =
                                                        $product->reseller_price - ($product->reseller_price * $product->discount_price) / 100;
                                                }
                                            } else {
                                                if ($product->discount_type == 1) {
                                                    $price_after_discount = $product->regular_price - $product->discount_price;
                                                } elseif ($product->discount_type == 2) {
                                                    $price_after_discount =
                                                        $product->regular_price - ($product->regular_price * $product->discount_price) / 100;
                                                }
                                            }
                                        @endphp
    
                                        @if ($product->discount_price > 0)
                                            @if (auth()->check() && auth()->user()->role == 7)
                                                <div class="product-price">
                                                    <span class="price">৳{{ formatNumberInBengali($product->reseller_price) }}</span>
                                                </div>
                                            @else
                                                <div class="product-price">
                                                    <span class="price">৳{{ formatNumberInBengali($price_after_discount) }}</span>
                                                    <span class="old-price" style="color: #DD1D21;">৳{{ formatNumberInBengali($product->regular_price) }}</span>
                                                </div>
                                            @endif
                                        @else
                                            @if (auth()->check() && auth()->user()->role == 7)
                                                <div class="product-price">
                                                    <span class="price">৳{{ formatNumberInBengali($product->reseller_price) }}</span>
                                                </div>
                                            @else
                                                <div class="product-price">
                                                    <span class="price">৳{{ formatNumberInBengali($product->regular_price) }}</span>
                                                </div>
                                            @endif
                                        @endif
                                    </span>
                                    @php
                                        $productsellcount = \App\Models\OrderDetail::where('product_id', $product->id)->sum('qty') ?? 0;
                                    @endphp
                                    <span class="price">Sold({{ $productsellcount }})</span>
                                </div>
                                @if (auth()->check() && auth()->user()->role == 7)
                                    <div>
                                        <span>Regular Price: <span class="text-info">৳ {{ formatNumberInBengali($product->regular_price) }}</span></span>
                                        <input type="hidden" id="regular_price" name="regular_price" value="{{ $product->regular_price }}" min="1">
                                    </div>
                                @endif
                                <!-- Add to Cart and Buy Now Buttons -->
                                <div class="add-to-cart-buttons">
                                    @if ($product->is_varient == 1)
                                        <a class="add" id="{{ $product->id }}" onclick="productView(this.id)"
                                            data-bs-toggle="modal" data-bs-target="#quickViewModal"><i
                                                class="fi-rs-shopping-cart mr-5"></i>Add </a>
                                    @else
                                        <input type="hidden" id="pfrom" value="direct">
                                        <input type="hidden" id="product_product_id" value="{{ $product->id }}" min="1">
                                        <input type="hidden" id="{{ $product->id }}-product_pname" value="{{ $product->name_en }}">
                                        <input type="hidden" id="buyNowCheck" value="0">
                                        <button type="submit" class="add_to_cart_home" onclick="addToCartDirect({{ $product->id }})"><i class="fi-rs-shoppi ng-cart"></i>Add to cart</button>
                                        <button type="submit" class="buy_now_home ml-5 bg-danger" onclick="buyNow({{ $product->id }})"><i class="fi-rs-shoppi ng-cart"></i>Buy Now</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <!--product grid-->
                <div class="pagination-area mt-20 mb-20">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-end">
                            {{ $products->links() }}
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="col-lg-1-5 primary-sidebar sticky-sidebar">
                <!-- Fillter By Price -->
                @include('frontend.common.filterby')
                <!-- SideCategory -->
                @include('frontend.common.sidecategory')
            </div>
        </div>
    </div>
</main>
@endsection
@push('footer-script')
    <script>
        function updateBrandId(value) {
            document.getElementById('brand_id').value = value;
        }
    
        function updateSortBy(value) {
            document.getElementById('sort_by').value = value;
        }
    
        function filter() {
            document.getElementById('search-form').submit();
        }
    </script>
    
    <script type="text/javascript">
        function sort_price_filter(){
           var start = $('#slider-range-value1').html();
           var end = $('#slider-range-value2').html();
           $('#filter_price_start').val(start);
           $('#filter_price_end').val(end);
           $('#search-form').submit();
        }
    </script>
    
    <script type="text/javascript">
        (function ($) {
            ("use strict");
            // Slider Range JS
            if ($("#slider-range").length) {
                $(".noUi-handle").on("click", function () {
                    $(this).width(50);
                });
                var rangeSlider = document.getElementById("slider-range");
                var moneyFormat = wNumb({
                    decimals: 0,
                });
                var start_price = document.getElementById("filter_price_start").value;
                var end_price = document.getElementById("filter_price_end").value;
                noUiSlider.create(rangeSlider, {
                    start: [start_price, end_price],
                    step: 1,
                    range: {
                        min: [1],
                        max: [5000]
                    },
                    format: moneyFormat,
                    connect: true
                });

                // Set visual min and max values and also update value hidden form inputs
                rangeSlider.noUiSlider.on("update", function (values, handle) {
                    document.getElementById("slider-range-value1").innerHTML = values[0];
                    document.getElementById("slider-range-value2").innerHTML = values[1];
                    document.getElementsByName("min-value").value = moneyFormat.from(values[0]);
                    document.getElementsByName("max-value").value = moneyFormat.from(values[1]);
                });
                
            }
        })(jQuery);
    </script>
@endpush