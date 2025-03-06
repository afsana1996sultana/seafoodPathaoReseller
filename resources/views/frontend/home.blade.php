@extends('layouts.frontend')
@push('css')
    <style>
        .preloader1 {
            background-color: #fff;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 999999;
            -webkit-transition: .6s;
            transition: .6s;
            margin: 0 auto;
        }

        .preloader-active1 {
            position: absolute;
            top: 100px;
            width: 100%;
            height: 100%;
            z-index: 100;
        }

        .add-to-cart-buttons {
            position: absolute;
            margin-top: 20px;
            display: flex;
        }
    </style>
@endpush


@section('content-frontend')
@include('frontend.common.add_to_cart_modal')
    <!--main slider start-->
    <div class="slider-area">
        <div class="slider-active">
            @foreach($sliders as $slider)
            <a href="" target="_blank"><img src="{{ asset($slider->slider_img) }}" alt=""></a>
             @endforeach
        </div>
        
    </div>
    <!--main slider end-->


    <!--shop collection start-->
    <div class="shop-collection-area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="section-heading text-center">
                        <h3>দোকান সংগ্রহ</h3>
                    </div>
                </div>
            </div>
            <div class="category_slider_wrapper">
                @foreach(get_categories() as $category)
                <div class="category_slider_iteminner">
                    <a href="{{ route('product.category', $category->slug) }}" target="_blank" class="single-shop-collection">
                        <img src="{{ asset($category->image) }}" alt="">
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!--shop collection end-->
    
    
    <!-- Campaign Slider Start-->
	@php
        $campaign = \App\Models\Campaing::where('status', 1)->where('is_featured', 1)->first();
    @endphp
	
    @if($campaign)
        @php
            $start_diff=date_diff(date_create($campaign->flash_start), date_create(date('d-m-Y H:i:s')));
            $end_diff=date_diff(date_create(date('d-m-Y H:i:s')), date_create($campaign->flash_end));
        @endphp
        @if ($start_diff->invert == 0 && $end_diff->invert == 0)
        <section class="common-product section-padding">
            <div class="container wow animate__animated animate__fadeIn">
                <div class="section-title">
                    <div class="title">
                        <h3>{{$campaign->name_en }}</h3>
                        <div class="deals-countdown-wrap">
                            <div class="deals-countdown" data-countdown="{{ date(('Y-m-d H:i:s'), strtotime($campaign->flash_end)) }}"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <ul class="product_button product-view-more d-flex justify-content-center">
                                <li><a href="{{ route('campaing.all') }}">আরও দেখুন</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="carausel-5-columns-cover position-relative">
                    <div class="slider-arrow slider-arrow-2 carausel-5-columns-common-arrow" id="carausel-5-columns-common-arrows"></div>
                    <div class="carausel-5-columns-common carausel-arrow-center" id="carausel-5-columns-common">
                        @foreach($campaign->campaing_products->take(20) as $campaing_product)
                            @php
                                $product = \App\Models\Product::find($campaing_product->product_id);
                            @endphp
                            @if ($product != null && $product->status != 0)
                                @include('frontend.common.product_grid_view',['product' => $product])
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
        @endif
	@endif
	<!-- Campaign Slider End-->


    <!--special collection start -->
    @if(count($home2_featured_categories) > 0)
        <h3 class="area-heading">উৎসবের সময়</h3>
        @foreach($home2_featured_categories->take(8) as $home2_featured_category)
            @if(count($home2_featured_category->cat_products) > 0)
            <div class="special-collection-area">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="section-heading text-center">
                                <h5>
                                    {{ $home2_featured_category->name_bn ?? '$home2_featured_category->name_en' }}
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="special-product-active">
                            @forelse($home2_featured_category->cat_products as $product)
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
                                            <?php $p_name_bn = strip_tags(html_entity_decode($product->name_bn ?? '$product->name_en')); ?>
                                            {{ Str::limit($p_name_bn, $limit = 30, $end = '. . .') }}
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
                            @endforeach
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <ul class="product_button product-view-more d-flex justify-content-center">
                                <li><a href="{{ route('product.category', $home2_featured_category->slug) }}">আরও দেখুন</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @endforeach
    @endif
    <!--special collection end -->

    <!--feature product start -->
    <section class="product-tabs section-padding position-relative">
        <div class="container-fluid">
            <div class="section-title style-2 wow animate__animated animate__fadeIn">
                <h3>বৈশিষ্ট্যযুক্ত পণ্য</h3>
                <ul class="nav nav-tabs links" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="nav-tab-one" data-bs-toggle="tab" data-bs-target="#tab-one"
                            type="button" role="tab" aria-controls="tab-one" aria-selected="true">All</button>
                    </li>
                    @foreach (get_categories() as $category)
                            <li class="nav-item mb-1" role="presentation">
                                <button class="nav-link" id="nav-tab-two_{{ $category->id }}" data-bs-toggle="tab"
                                    data-bs-target="#category{{ $category->id }}" type="button" role="tab"
                                    aria-selected="false">{{ $category->name_bn  ?? '$category->name_en' }}</button>
                            </li>
                    @endforeach
                </ul>
            </div>
            <!--End nav-tabs-->
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="tab-one" role="tabpanel">
                    <div class="row product-grid-4 gutters-5">
                        @foreach ($products->take(10) as $product)
                            @include('frontend.common.product_grid_view', ['product' => $product])
                            <!--end product card-->
                        @endforeach
                    </div>
                    <!--End product-grid-4-->
                </div>
                <!--En tab one-->
                @foreach (get_categories() as $category)
                    <div class="tab-pane fade" id="category{{ $category->id }}" role="tabpanel">
                        @php
                            $products = get_category_products($category->slug);
                        @endphp
                        <div class="row product-grid-4">
                            @forelse($products->take(10) as $product)
                                @include('frontend.common.product_grid_view')
                            @empty
                                <h5 class="text-danger">এখানে কোন পণ্য খুঁজে পাওয়া যায়নি!</h5>
                            @endforelse
                        </div>
                        <!--End product-grid-4-->
                    </div>
                @endforeach
                <!--En tab two-->
            </div>
            
             <div class="row">
                <div class="col-12">
                    <ul class="product_button product-view-more d-flex justify-content-center">
                        <li><a href="{{ route('featured.product') }}">আরও দেখুন</a></li>
                    </ul>
                </div>
            </div>
            <!--End tab-content-->
        </div>
    </section>
    <!--feature product end -->


    <!--latest collection start -->
    <div class="special-collection-area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="section-heading text-center">
                        <h3>নতুন কি?</h3>
                        <span>সর্বশেষ সংস্করণ</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="special-product-active">
                    @foreach($product_recently_adds->take(8) as $product_recently_add)
                    <div class="special-image">
                        <div class="speciall__images">
                            <div class="special-image-front">
                                <img src="{{ asset($product_recently_add->product_thumbnail) }}" alt="sd">
                            </div>

                            <div class="special-image-back">
                                @foreach ($product_recently_add->multi_imgs->take(1) as $img)
                                    <a href="{{ route('product.details', $product->slug) }}"><img src="{{ asset($img->photo_name) }}" alt="sd"></a>
                                @endforeach
                            </div>
                            
                            <div class="back-quick-view">
                                <a aria-label="Quick view" id="{{ $product_recently_add->id }}" onclick="productView(this.id)" class="action-btn" data-bs-toggle="modal" data-bs-target="#quickViewModal"><i class="fa-regular fa-eye"></i></a>
                            </div>
                        </div>
                        <div class="special-image-content">
                            <a href="{{ route('product.details', $product_recently_add->slug) }}">
                                    <?php $p_name_bn = strip_tags(html_entity_decode($product_recently_add->name_bn ?? '$product_recently_add->name_en')); ?>
                                    {{ Str::limit($p_name_bn, $limit = 30, $end = '. . .') }}
                            </a>
                            @php
                                $reviews = \App\Models\Review::where('product_id', $product_recently_add->id)
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
                                            if ($product_recently_add->discount_type == 1) {
                                                $price_after_discount = $product_recently_add->reseller_price - $product_recently_add->discount_price;
                                            } elseif ($product_recently_add->discount_type == 2) {
                                                $price_after_discount =
                                                    $product_recently_add->reseller_price - ($product_recently_add->reseller_price * $product_recently_add->discount_price) / 100;
                                            }
                                        } else {
                                            if ($product_recently_add->discount_type == 1) {
                                                $price_after_discount = $product_recently_add->regular_price - $product_recently_add->discount_price;
                                            } elseif ($product_recently_add->discount_type == 2) {
                                                $price_after_discount =
                                                    $product_recently_add->regular_price - ($product_recently_add->regular_price * $product_recently_add->discount_price) / 100;
                                            }
                                        }
                                    @endphp

                                    @if ($product_recently_add->discount_price > 0)
                                        @if (auth()->check() && auth()->user()->role == 7)
                                            <div class="product-price">
                                                <span class="price">৳{{ formatNumberInBengali($product_recently_add->reseller_price) }}</span>
                                            </div>
                                        @else
                                            <div class="product-price">
                                                <span class="price">৳{{ formatNumberInBengali($price_after_discount) }}</span>
                                                <span class="old-price" style="color: #DD1D21;">৳{{ formatNumberInBengali($product_recently_add->regular_price) }}</span>
                                            </div>
                                        @endif
                                    @else
                                        @if (auth()->check() && auth()->user()->role == 7)
                                            <div class="product-price">
                                                <span class="price">৳{{ formatNumberInBengali($product_recently_add->reseller_price) }}</span>
                                            </div>
                                        @else
                                            <div class="product-price">
                                                <span class="price">৳{{ formatNumberInBengali($product_recently_add->regular_price) }}</span>
                                            </div>
                                        @endif
                                    @endif
                                </span>
                                @php
                                    $productsellcount = \App\Models\OrderDetail::where('product_id', $product_recently_add->id)->sum('qty') ?? 0;
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
                    @endforeach
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <ul class="product_button product-view-more d-flex justify-content-center">
                        <li><a href="{{ route('product.show') }}">আরও দেখুন</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--latest collection end -->


    <!-- Start Hot Deals -->
    @if (count($hot_deals) > 0)
        <section class="section-padding">
            <div class="container-fluid">
                <div class="section-title wow animate__animated animate__fadeIn" data-wow-delay="0">
                    <h3 class="">
                        হট ডিলস
                    </h3>
                    <a class="show-all btn btn-primary text-white" href="{{ route('hot_deals.all') }}">
                        All Deals
                        <i class="fi-rs-angle-right"></i>
                    </a>
                </div>
                <div class="hotdeal_slider">
                    @foreach ($hot_deals as $product)
                        @include('frontend.common.deals')
                        <!--end product card-->
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    <!-- End Hot Deals -->

   <!-- announcement start -->
    <div class="announcement-area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="section-heading text-center">
                        <h3>ঘোষণা</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="announcement-active">
                    @foreach ($blogs as $blog)
                   <div class='blog-image'>
                        <a href="{{ route('blog.details', $blog->slug) }}" class="single-announcement">
                            <div class="announcement-image">
                                <img src="{{ asset($blog->blog_img) }}" alt="">
                            </div>
                            <div class="announcement-content">
                                <h6>{{ $blog->title_en }}</h6>
                                <a href="{{ route('blog.details', $blog->slug) }}">Read more <i class="fa fa-right-long"></i></a>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- announcement end -->
@endsection


@push('footer-script')
    <script type="text/javascript">
        // slider - active
        function mainSlider() {
            var BasicSlider = $('.slider-active');

            BasicSlider.on('init', function(e, slick) {
                var $firstAnimatingElements = $('.single-slider:first-child').find('[data-animation]');
                doAnimations($firstAnimatingElements);
            });

            BasicSlider.on('beforeChange', function(e, slick, currentSlide, nextSlide) {
                var $animatingElements = $('.single-slider[data-slick-index="' + nextSlide + '"]').find(
                    '[data-animation]');
                doAnimations($animatingElements);
            });

            BasicSlider.slick({
                autoplay: true,
                autoplaySpeed: 3000,
                dots: false,
                infinite: true,
                prevArrow: '<button class="slick-prev"><i class="fa fa-angle-left"></i></button>',
                nextArrow: '<button class="slick-next"><i class="fa fa-angle-right"></i></button>',
                fade: true,
                arrows: true,
                responsive: [{
                    breakpoint: 767,
                }]
            });

            function doAnimations(elements) {
                var animationEndEvents = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
                elements.each(function() {
                    var $this = $(this);
                    var $animationDelay = $this.data('delay');
                    var $animationType = 'animated ' + $this.data('animation');
                    $this.css({
                        'animation-delay': $animationDelay,
                        '-webkit-animation-delay': $animationDelay
                    });
                    $this.addClass($animationType).one(animationEndEvents, function() {
                        $this.removeClass($animationType);
                    });
                });
            }
        }
        mainSlider();
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            const minus = $('.quantity__minus');
            const plus = $('.quantity__plus');
            const input = $('.quantity__input');
            minus.click(function(e) {
                e.preventDefault();
                var value = input.val();
                if (value > 1) {
                    value--;
                }
                input.val(value);
            });

            plus.click(function(e) {
                e.preventDefault();
                var value = input.val();
                value++;
                input.val(value);
            })
        });
    </script>
@endpush