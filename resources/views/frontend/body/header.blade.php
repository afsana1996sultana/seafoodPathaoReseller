<header class="header-area header-style-1 header-height-2">
    <div class="header-top header-top-ptb-1 d-none d-lg-block">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-xl-3 col-lg-6">
                    <div class="header-info">
                        <ul>
                            <li class="contact_header" style="color: #C90312;">Need help? Call Us: <strong class="text-brand"> <a
                            class="text-brand" href="tel:{{ get_setting('phone')->value ?? 'null' }}"><i
                            class="fa fa-phone ms-1"></i>
                            {{ get_setting('phone')->value ?? 'null' }}</a></strong></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6">
                    <div class="text-center">
                        {{-- <div class="header__search position-relative">
                            <form action="{{ route('product.search')}}" method="post" class="mx-auto">
                                @csrf
                                <div class="form-group">
                                    <input type="search" class="form-control" onfocus="search_result_show()"
                                        onblur="search_result_hide()" name="search" placeholder="search here...">
                                    <button type="submit"><i class="fa fa-search"></i></button>
                                </div>
                            </form>
                        </div>
                        <div class="shadow-lg searchProducts"></div> --}}
                        <div class="search-area header__search position-relative">
                            <form action="{{ route('product.search') }}" method="post" class="mx-auto">
                                @csrf
                                <select class="select-active serch_category_select" name="searchCategory" id="searchCategory">
                                    <option value="0">Categories</option>
                                    @foreach(get_all_categories() as $cat)
                                        <option value="{{ $cat->id }}">
                                            @if(session()->get('language') == 'bangla') 
                                                {{ $cat->name_bn }}
                                            @else 
                                                {{ $cat->name_en }} 
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <input class="search-field search" onfocus="search_result_show()"
                                    onblur="search_result_hide()" name="search" placeholder="Search here..." />
                                <button type="submit" class="bg-brand btn btn-primary text-white btn-sm rounded-0"><i
                                        class="fa-solid fa-magnifying-glass"></i></button>
                            </form>
                        </div>
                        <div class="shadow-lg searchProducts" style="display: block"></div>
                    </div>
                </div>

                <div class="col-xl-5 col-lg-12 p-lg-3">
                    <div class="header-info header-info-right">
                        <ul>
                            <li><a href="#" data-bs-toggle="modal" data-bs-target="#vendor_service" class="vendorBtn">Become a Vendor</a></li>
                            <li><a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal" class="vendorBtn">Apply as Reseller</a></li>
                            <li><a href="{{ route('order.tracking') }}">Order Tracking</a></li>
                            <li>
                                @if (session()->get('language') == 'bangla')
                                    <a class="language-dropdown-active"
                                        href="{{ route('english.language') }}">English</a>
                                @else
                                    <a class="language-dropdown-active" href="{{ route('bangla.language') }}">বাংলা</a>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- header start --}}
    <div class="container-fluid d-none d-lg-block header-menu header-sticky">
        <div class="row align-items-center">
            <div class="col-lg-1">
                <div class="logo">
                    <a href="{{ url('/') }}">
                        @php
                            $logo = get_setting('site_logo');
                        @endphp
                        @if ($logo != null)
                            <img src="{{ asset(get_setting('site_logo')->value ?? 'null') }}"
                                alt="{{ env('APP_NAME') }}">
                        @else
                            <img src="{{ asset('upload/no_image.jpg') }}" alt="{{ env('APP_NAME') }}"
                                style="height: 60px !important; width: 80px !important; min-width: 80px !important;">
                        @endif
                    </a>
                </div>
            </div>
            <div class="col-lg-10">
                <div class="all_categories_wrapper">
                    <div>
                        <a class="home_btn" href="{{ url('/') }}">
                            <i class="fa-solid fa-house"></i>
                        </a>
                    </div>
                    
                <div class="main-menu">
                    <ul class="d-flex ">
                        @foreach (get_categories() as $category)
                            <li>
                                <a href="{{ route('product.category', $category->slug) }}">
                                    @if (session()->get('language') == 'bangla')
                                        {{ $category->name_bn }}
                                    @else
                                        @if($category->name_en == 'Buy1 Get1' || $category->name_en == 'Clearance Sale' || $category->name_en == 'Hot Offers')
                                            <span style="color: #DD1D21 !important;">{{ $category->name_en }}</span>
                                        @else
                                            {{ $category->name_en }}
                                        @endif
                                    @endif
                                </a>
                                @if ($category->sub_categories && count($category->sub_categories) > 0)
                                    <ul class="sub-menu">
                                        @foreach ($category->sub_categories as $sub_category)
                                            <li>
                                                <a href="{{ route('product.category', $sub_category->slug) }}">
                                                    @if (session()->get('language') == 'bangla')
                                                        {{ $sub_category->name_bn }}
                                                    @else
                                                        {{ $sub_category->name_en }}
                                                    @endif
                                                </a>
                                                @if ($sub_category->sub_sub_categories && count($sub_category->sub_sub_categories) > 0)
                                                    <i class="fa fa-angle-right"></i>
                                                @endif
                                                @if ($sub_category->sub_sub_categories && count($sub_category->sub_sub_categories) > 0)
                                                    <ul class="child-menu">
                                                        @foreach ($sub_category->sub_sub_categories as $sub_sub_category)
                                                            <li>
                                                                <a
                                                                    href="{{ route('product.category', $sub_sub_category->slug) }}">
                                                                    @if (session()->get('language') == 'bangla')
                                                                        {{ $sub_sub_category->name_bn }}
                                                                    @else
                                                                        {{ $sub_sub_category->name_en }}
                                                                    @endif
                                                                </a>
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
                        <li><a href="#">All Brands</a>
                            <ul class="sub-menu">
                                @foreach (get_all_brands()->take(20) as $brand)
                                    <li>
                                        <a href="{{ route('product.brand', $brand->slug) }}">
                                            @if (session()->get('language') == 'bangla')
                                                {{ $brand->name_bn }}
                                            @else
                                                {{ $brand->name_en }}
                                            @endif
                                        </a>
                                    </li>
                                @endforeach
                                <li>
                                    <a href="{{ route('brand_list.index') }}" style="color: #DD1D21 !important;">See More</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                </div>
    
            </div>
            <!--<div class="col-lg-7">-->
            <!--</div>-->
            <div class="col-lg-1">
                <div class="header-right">
                    <ul class="d-flex justify-content-end">
                        <li data-bs-toggle="offcanvas" href="#search_offcanvas" role="button" class="d-md-none"
                            aria-controls="search_offcanvas"><i class="fa fa-magnifying-glass"></i></li>

                        <li>
                            @auth
                                <a href="{{ route('dashboard') }}"><i class="fa fa-user"></i></a>
                            @endauth
                            @guest
                                <a href="{{ route('login') }}"><i class="fa fa-user"></i></a>
                            @endguest
                        </li>

                        <li data-bs-toggle="offcanvas" href="#shopping_offcanvas" role="button"
                            aria-controls="shopping_offcanvas"><i class="fa fa-cart-shopping"></i><span
                                class="pro-count blue cartQty"></span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    {{-- header end --}}

    {{-- cart_shopping --}}
    <div class="modal fade" id="cart_shopping" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="single-product d-flex">
                        <div class="search-product-image">
                            <a href=""><img src="{{ asset('frontend/assets/imgs/panjabi/panjabi.jpg') }}"
                                    alt="logo"></a>
                        </div>
                        <div class="search-product-content">
                            <a href="#">smart casual slim fit panjabi</a>
                            <span>Tk 145.22</span>

                            <div class="product-size d-flex mt-2 mb-1">
                                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                    <input type="radio" class="btn-check" name="btnradio" id="SS"
                                        autocomplete="off" checked>
                                    <label class="btn" for="SS">S</label>

                                    <input type="radio" class="btn-check" name="btnradio" id="SM"
                                        autocomplete="off">
                                    <label class="btn" for="SM">M</label>

                                    <input type="radio" class="btn-check" name="btnradio" id="SL"
                                        autocomplete="off">
                                    <label class="btn" for="SL">L</label>

                                    <input type="radio" class="btn-check" name="btnradio" id="SXL"
                                        autocomplete="off">
                                    <label class="btn" for="SXL">XL</label>

                                    <input type="radio" class="btn-check" name="btnradio" id="SXXL"
                                        autocomplete="off">
                                    <label class="btn" for="SXXL">XXL</label>
                                </div>
                            </div>


                            <div class="product_quantity">
                                <a href="#" class="quantity__minus"><span><i
                                            class="fa fa-minus"></i></span></a>
                                <input name="quantity" type="text" class="quantity__input" value="1">
                                <a href="#" class="quantity__plus"><span><i class="fa fa-plus"></i></span></a>
                            </div>

                            <ul class="product_button d-flex align-items-center">
                                <li><button>ADD TO CART</button></li>
                                <li><a href="">PRODUCT DETAILS</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- shopping offcanvas start --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="shopping_offcanvas"
        aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header border-bottom">
            <h6 class="offcanvas-title " id="offcanvasExampleLabel">SHOPPING CART
            </h6>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="shopping-empty d-none">
                <i class="fa fa-cart-shopping"></i>
                <p>Your Cart is Empty</p>
                <a href="{{ route('home') }}" class="return_shop">return to shop</a>
            </div>

            <div id="miniCart">

            </div>

            <ul class="cart-product-price d-flex justify-content-between">
                <li><strong>Subtotal : </strong></li>
                <li>TK <span id="cartSubTotal"></span></li>
            </ul>
            <ul class="cart-checkout d-flex justify-content-between">
                <li><a href="{{ route('cart.show') }}" class="btn">view cart</a></li>
                <li><a href="{{ route('checkout') }}" class="btn">checkout</a></li>
            </ul>
        </div>
    </div>
    {{-- shopping offcanvas end --}}

    {{-- search offcanvas start --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="search_offcanvas"
        aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header border-bottom">
            <h6 class="offcanvas-title " id="offcanvasExampleLabel">SEARCH OUR SITE</h6>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <form action="{{ route('product.search') }}" method="post" class=" search-system">
            @csrf
            <select class="form-select" name="searchCategory" id="searchCategory"
                aria-label="Default select example">
                <option value="0">All Categories</option>
                @foreach (get_all_categories() as $cat)
                    <option value="{{ $cat->id }}">
                        @if (session()->get('language') == 'bangla')
                            {{ $cat->name_bn }}
                        @else
                            {{ $cat->name_en }}
                        @endif
                    </option>
                @endforeach
            </select>

            <div class="form-group">
                <input type="search" name="search" placeholder="search for products">
                <button type='submit'> <i class="fa fa-magnifying-glass"></i></button>
            </div>
        </form>
    </div>
    {{-- search offcanvas end --}}

    <div class="d-lg-block sticky-bar">
        <div class="header-bottom header-bottom-bg-color">
            <div class="container-fluid">
                <div class="header-wrap header-space-between justify-content-between position-relative">
                    <div class="header-action-icon-2 d-block d-lg-none">
                        <div class="burger-icon burger-icon-white">
                            <span class="burger-icon-top"></span>
                            <span class="burger-icon-mid"></span>
                            <span class="burger-icon-bottom"></span>
                        </div>
                    </div>
                    <!--logo-->
                    <div class="logo logo_bottom d-block d-lg-none">
                        <a href="{{ url('/') }}">
                            @php
                                $logo = get_setting('site_logo');
                            @endphp
                            @if ($logo != null)
                                <img src="{{ asset(get_setting('site_logo')->value ?? 'null') }}" alt="{{ env('APP_NAME') }}">
                            @else
                                <img src="{{ asset('upload/no_image.jpg') }}" alt="{{ env('APP_NAME') }}">
                            @endif
                        </a>
                    </div>
                    
                    <div class="header-action-right d-block d-lg-none">
                        <div class="header-action-2">
                            <!--Mobile Header Search start-->
                            <a class="p-2 d-block text-reset active show">
                                <i class="fas fa-search la-flip-horizontal la-2x" data-bs-toggle="offcanvas"
                                    href="#search_offcanvas" role="button" aria-controls="search_offcanvas"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>


<!-- Mobile Side menu Start -->
<div class="mobile-header-active mobile-header-wrapper-style">
    <div class="mobile-header-wrapper-inner">
        <div class="mobile-header-top">
            <div class="mobile-header-logo">
                <a href="{{ route('home') }}">
                    @php
                        $logo = get_setting('site_footer_logo');
                    @endphp
                    @if ($logo != null)
                        <img src="{{ asset(get_setting('site_footer_logo')->value ?? 'null') }}"
                            alt="{{ env('APP_NAME') }}">
                    @else
                        <img src="{{ asset('upload/no_image.jpg') }}" alt="{{ env('APP_NAME') }}"
                            style="height: 60px !important; width: 80px !important; min-width: 80px !important;">
                    @endif
                </a>
            </div>
            <div class="mobile-menu-close close-style-wrap close-style-position-inherit">
                <button class="close-style search-close">
                    <i class="icon-top"></i>
                    <i class="icon-bottom"></i>
                </button>
            </div>
        </div>
        <div class="mobile-header-content-area">
            <div class="mobile-search search-style-3 mobile-header-border">
                <form action="#">
                    <input type="text" placeholder="Search for items…" />
                    <button type="submit"><i class="fi-rs-search"></i></button>
                </form>
            </div>
            <div class="mobile-menu-wrap mobile-header-border">
                <!-- mobile menu start -->
                <nav>
                    <ul class="mobile-menu font-heading">
                        <li class="menu-item-has-children">
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="menu-item-has-children">
                            <a href="{{ route('product.show') }}">
                                @if (session()->get('language') == 'bangla')
                                    দোকান
                                @else
                                    Shop
                                @endif
                            </a>
                        </li>
                        <li class="menu-item-has-children">
                            <a href="{{ route('category_list.index') }}">Category</a>
                            <ul class="dropdown">
                                @foreach (get_categories()->take(8) as $cat)
                                    <li class="menu-item-has-children">
                                        <a href="{{ route('product.category', $cat->slug) }}">
                                            @if (session()->get('language') == 'bangla')
                                                {{ $cat->name_bn }}
                                            @else
                                                {{ $cat->name_en }}
                                            @endif
                                        </a>
                                        @if ($cat->sub_categories && count($cat->sub_categories) > 0)
                                            <ul class="dropdown">
                                                @foreach ($cat->sub_categories as $subcategory)
                                                    <li>
                                                        <a href="{{ route('product.category', $subcategory->slug) }}">
                                                            @if (session()->get('language') == 'bangla')
                                                                {{ $subcategory->name_bn }}
                                                            @else
                                                                {{ $subcategory->name_en }}
                                                            @endif
                                                        </a>
                                                    </li>
                                                @endforeach
                                                @if ($subcategory->sub_sub_categories && count($subcategory->sub_sub_categories) > 0)
                                                    <ul class="dropdown">
                                                        @foreach ($subcategory->sub_sub_categories as $subsubcategory)
                                                            <li>
                                                                <a
                                                                    href="{{ route('product.category', $subsubcategory->slug) }}">
                                                                    @if (session()->get('language') == 'bangla')
                                                                        {{ $subsubcategory->name_bn }}
                                                                    @else
                                                                        {{ $subsubcategory->name_en }}
                                                                    @endif
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                        <li class="menu-item-has-children">
                            <a href="#">Pages</a>
                            <ul class="dropdown">
                                @foreach (get_pages_both_footer()->take(4) as $page)
                                    <li>
                                        <a href="{{ route('page.about', $page->slug) }}">{{ $page->title }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                        <li class="menu-item-has-children">
                            <a href="#">Language</a>
                            <ul class="dropdown">
                                @if (session()->get('language') == 'bangla')
                                    <li>
                                        <a href="{{ route('english.language') }}">English</a>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ route('bangla.language') }}">বাংলা</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    </ul>
                </nav>
                <!-- mobile menu end -->
            </div>
            <div class="mobile-header-info-wrap">
                <!-- <div class="single-mobile-header-info">
                    <a href="#"><i class="fi-rs-marker"></i> Our location </a>
                </div> -->
                <div class="single-mobile-header-info">
                    <a href="{{ route('login') }}"><i class="fi-rs-user"></i>Log In </a>
                </div>
                <div class="single-mobile-header-info">
                    <a href="{{ route('register') }}"><i class="fi-rs-user"></i>Sign Up </a>
                </div>
                <div class="single-mobile-header-info">
                    <a href="tel:{{ get_setting('phone')->value ?? 'null' }}"><i
                            class="fi-rs-headphones"></i>{{ get_setting('phone')->value ?? 'null' }} </a>
                </div>
            </div>
            <div class="mobile-social-icon mb-50">
                <h6 class="mb-15">Follow Us</h6>
                <a href="{{ get_setting('facebook_url')->value ?? 'null' }}"><img
                        src="{{ asset('frontend/assets/imgs/theme/icons/icon-facebook-white.svg') }}"
                        alt="" /></a>
                <a href="{{ get_setting('youtube_url')->value ?? 'null' }}"><img
                        src="{{ asset('frontend/assets/imgs/theme/icons/icon-twitter-white.svg') }}"
                        alt="" /></a>
                <a href="{{ get_setting('twitter_url')->value ?? 'null' }}"><img
                        src="{{ asset('frontend/assets/imgs/theme/icons/icon-instagram-white.svg') }}"
                        alt="" /></a>
                <a href="{{ get_setting('instagram_url')->value ?? 'null' }}"><img
                        src="{{ asset('frontend/assets/imgs/theme/icons/icon-pinterest-white.svg') }}"
                        alt="" /></a>
                <a href="{{ get_setting('pinterest_url')->value ?? 'null' }}"><img
                        src="{{ asset('frontend/assets/imgs/theme/icons/icon-youtube-white.svg') }}"
                        alt="" /></a>
            </div>
            {{-- <div class="site-copyright">
                Developed by:
                <a target="_blank"
                    href="{{ get_setting('developer_link')->value ?? 'null' }}">{{ get_setting('developed_by')->value ?? 'null' }}</a>
            </div> --}}
        </div>
    </div>
</div>
<!-- Mobile Side menu End -->
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    <div class="heading_s1">
                        <h3 class="mb-5">রিসেলার আবেদন তথ্য:</h3>
                    </div>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="page-content pt-10 pb-10">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="login_wrap widget-taber-content background-white">
                                    <div class="padding_eight_all bg-white">
                                        <form method="POST" action="{{ route('resellerApply') }}"
                                            class="needs-validation" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label for="name" class="fw-900">রিসেলারের নাম: <span class="text-danger"> *</span></label>
                                                <input type="text" name="name" placeholder="রিসেলারের নাম:"
                                                    id="name" value="{{ old('name') }}" required />
                                                @error('name')
                                                    <div class="text-danger" style="font-weight: bold;">
                                                        {{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="phone" class="fw-900">রিসেলারের বিকাশ নম্বর: <span class="text-denger"> *</span></label>
                                                <input type="number" name="phone" placeholder="রিসেলারের বিকাশ নম্বর: "
                                                    id="phone" value="{{ old('phone') }}" required />
                                                @error('phone')
                                                    <div class="text-danger" style="font-weight: bold;">
                                                        {{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="fb_web_url" class="fw-900">ফেসবুক পেজ লিংক / ওয়েবসাইট লিংক: </label>
                                                <input type="text" name="fb_web_url" id="fb_web_url"
                                                    placeholder="ফেসবুক পেজ লিংক / ওয়েবসাইট লিংক: " value="{{ old('fb_web_url') }}"
                                                    />
                                                @error('fb_web_url')
                                                    <div class="text-danger" style="font-weight: bold;">
                                                        {{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="email" class="fw-900">ইমেইল ঠিকানা: <span class="class-denger"> *</span></label>
                                                <input type="email" name="email" id="email"
                                                    placeholder="ইমেইল ঠিকানা:" value="{{ old('email') }}" required />
                                                @error('email')
                                                    <div class="text-danger" style="font-weight: bold;">
                                                        {{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <div class="mb-4">
                                                    <img id="showImage3" class="rounded avatar-lg" src="{{ (!empty($editData->nid)) ? asset($editData->nid) : asset('upload/no_image.jpg') }}" alt="NID Card" width="100px" height="80px;">
                                                </div>
                                                <div class="mb-4">
                                                    <label for="image3" class="col-form-label" style="font-weight: bold;">Nid Card: </label>
                                                    <input name="nid" class="form-control" type="file" id="image3">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="password" class="fw-900">পাসওয়ার্ড: <span class="text-danger"> *</span></label>
                                                <input type="password" name="password" placeholder="পাসওয়ার্ড:"  required />
                                                <span>পাসওয়ার্ড কমপক্ষে ৮ অক্ষরের হতে হবে</span>
                                                @error('password')
                                                    <div class="text-danger" style="font-weight: bold;">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group mb-30 seller__btn">
                                                <button type="submit" class="btn-primary " name="login">Submit &amp; Register</button>
                                            </div>
                                            <p class="font-xs fw-900"><strong>শর্তাবলী:</strong>
                                            আমি নিশ্চিত করি যে, আমি প্রদত্ত তথ্য সঠিক এবং পূর্ণাঙ্গ।
আবেদনটি সফলভাবে জমা দেওয়ার পর, আমাদের পক্ষ থেকে কিছুক্ষণের মধ্যে আপনাকে ফোন কল দিয়ে যোগাযোগ করা হবে।</p>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- vendor Modal form -->
<div class="modal fade" id="vendor_service" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Vendor Apply Form</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('vendor.Sellerstore') }}" enctype="multipart/form-data">
                    @csrf
                    <h6 class="mb-2 border-bottom pb-2">Basic Information</h6>
                    <div class="form-group">
                        <label for="name"><strong>Name: </strong><span class="text-danger">*</span></label>
                        <input type="text" id="vendor_name" name="vendor_name" class="form-control"
                            placeholder="Enter Your Name" value="{{ old('vendor_name') }}">
                        @error('vendor_name')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone"><strong>Phone Number: </strong><span
                                        class="text-danger">*</span></label>
                                <input type="text" id="phone" name="phone" class="form-control"
                                    placeholder="Enter Your Phone Number" value="{{ old('phone') }}">
                                @error('phone')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email"><strong>Email: </strong><span
                                        class="text-danger">*</span></label>
                                <input type="email" id="email" name="email" class="form-control"
                                    placeholder="Enter Your Email" value="{{ old('email') }}">
                                @error('email')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="mb-4">
                                    <img id="showImage3" class="rounded avatar-lg"
                                        src="{{ !empty($editData->profile_image) ? url('upload/admin_images/' . $editData->profile_image) : url('upload/no_image.jpg') }}"
                                        alt="Card image cap" width="100px" height="80px;">
                                </div>
                                <label for="nid"><strong>Nid Card: </strong> <span
                                        class="text-danger">*</span></label>
                                <input name="nid" class="form-control" type="file" id="image3">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="mb-4">
                                    <img id="showImage4" class="rounded avatar-lg"
                                        src="{{ !empty($editData->profile_image) ? url('upload/admin_images/' . $editData->profile_image) : url('upload/no_image.jpg') }}"
                                        alt="Card image cap" width="100px" height="80px;">
                                </div>
                                <label for="trade"><strong>Trade License(if any one have): </strong></label>
                                <input name="trade_license" class="form-control" type="file" id="image4">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password"><strong>Password : </strong><span
                                        class="text-danger">*</span></label>
                                <input class="form-control" id="password" type="password" name="password"
                                    placeholder="Enter Your Password">
                                @error('password')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cpassword"><strong>Confirm Password : </strong><span
                                        class="text-danger">*</span></label>
                                <input class="form-control" placeholder="Confirm Password" type="password"
                                    name="password_confirmation" id="rtpassword" />
                                @error('password_confirmation')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <h6 class="mb-2 border-bottom pb-2">Shop Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="shopname"><strong>Shop Name : </strong><span
                                        class="text-danger">*</span></label>
                                <input class="form-control" id="shop_name" type="text" name="shop_name"
                                    placeholder="Write vendor shop name" value="{{ old('shop_name') }}">
                                @error('shop_name')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address"><strong>Address : </strong></label>
                                <input class="form-control" id="address" type="text" name="address"
                                    placeholder="Enter Your Address" value="{{ old('address') }}">
                                @error('address')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="mb-4">
                                    <img id="showImage1" class="rounded avatar-lg"
                                        src="{{ !empty($editData->profile_image) ? url('upload/admin_images/' . $editData->profile_image) : url('upload/no_image.jpg') }}"
                                        alt="Card image cap" width="100px" height="80px;">
                                </div>
                                <label for="image"><strong>Shop Profile : </strong></label>
                                <input name="shop_profile" class="form-control" type="file" id="image1">
                                @error('shop_profile')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="mb-4">
                                    <img id="showImage2" class="rounded avatar-lg"
                                        src="{{ !empty($editData->profile_image) ? url('upload/admin_images/' . $editData->profile_image) : url('upload/no_image.jpg') }}"
                                        alt="Card image cap" width="100px" height="80px;">
                                </div>
                                <label for="image"><strong>Shop Cover Photo : </strong></label>
                                <input name="shop_cover" class="form-control" type="file" id="image2">
                                @error('shop_cover')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="shop"><strong>Bank Information</strong></label>
                        <textarea name="bank_information" id="bank_information" cols="30" rows="5" class="form-control"
                            placeholder="Enter Bank Information"></textarea>
                    </div>
                    <button type="submit" class="additional_menuBtn">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!--End header-->


@push('footer-script')
<script>
    $(document).ready(function() {
        // Hide the category initially
        $(".category").hide();

        // Toggle the category when clicking on span
        $(".all__categories li span").click(function(e) {
            e.stopPropagation(); // Prevent the click from reaching the body/window
            $(this).siblings(".category").toggle();
        });
    });
</script>

<script type="text/javascript">
    /* ================ Advance Product Search ============ */
    $("body").on("keyup", ".search", function() {
        let text = $(".search").val();
        let category_id = $("#searchCategory").val();
        // alert(category_id);
        // console.log(text);

        if (text.length > 0) {

            $.ajax({
                data: {
                    search: text,
                    category: category_id
                },
                url: "/search-product",
                method: 'post',
                beforSend: function(request) {
                    return request.setReuestHeader('X-CSRF-Token', ("meta[name='csrf-token']"))

                },
                success: function(result) {
                    $(".searchProducts").html(result);
                }

            }); // end ajax
        } // end if
        if (text.length < 1) $(".searchProducts").html("");
    }); // end function

    /* ================ Advance Product slideUp/slideDown ============ */
    function search_result_hide() {
        $(".searchProducts").slideUp();
    }

    function search_result_show() {
        $(".searchProducts").slideDown();
    }
</script>

<script>
    // sidebar menu count
var $menu = $('.sidebar__menu__system');

// Find all top-level menu items (categories)
var $topLevelItems = $menu.find('.show__item__category');

// Count the total number of items (including nested items)
var totalItems = $topLevelItems.length;

// Check if there are more than 12 items
if (totalItems > 10) {
    // Hide items beyond the first 12
    $topLevelItems.slice(10).hide();

    // Add "Show More" link with initial icon
    $menu.append('<div class="showMore">Show More <i class="fas fa-plus"></i></div>');

    // Event handler for "Show More" link
    $menu.find('.showMore').on('click', function () {
        $topLevelItems.slice(10).toggle(500); // Toggle visibility of hidden items

        // Toggle icon between plus and minus
        $(this).toggleClass('showLess');
        if ($(this).hasClass('showLess')) {
            $(this).html('Show Less <i class="fas fa-minus"></i>');
        } else {
            $(this).html('Show More <i class="fas fa-plus"></i>');
        }
    });
}
</script>

<script>
    $(document).ready(function() {
        $(".show").click(function() {
            $(".advance-search").show();
        });
        $(".hide").click(function() {
            $(".advance-search").hide();
        });
    });

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
            infinite: false,
            prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-angle-left"></i></button>',
            nextArrow: '<button type="button" class="slick-next"><i class="fa fa-angle-right"></i></button>',
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
@endpush