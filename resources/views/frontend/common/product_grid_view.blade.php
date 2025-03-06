 <style>
    button.button.add_to_cart {
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 14px;
        margin-right: 45px;
    }

    button.button.buy_now {
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 14px;
    }
 </style>
 <div class="col-lg-1-5 col-md-4 col-sm-6 col-6">
     <div class="product-cart-wrap mb-30 wow animate__animated animate__fadeIn" data-wow-delay=".1s">
         <div class="product-img-action-wrap">
             <div class="product-img product-img-zoom">
                 <a href="{{ route('product.details', $product->slug) }}">
                     @if ($product->product_thumbnail && $product->product_thumbnail != '' && $product->product_thumbnail != '')
                         <img class="default-img lazyload img-responsive"
                             data-original="{{ asset($product->product_thumbnail) }}"
                             src="{{ asset($product->product_thumbnail) }}" alt="">
                         <img class="hover-img" src="{{ asset($product->product_thumbnail) }}"
                             alt="" />
                     @else
                         <img class="img-lg mb-3" data-original="{{ asset('upload/no_image.jpg') }}" alt="" />
                     @endif
                 </a>
             </div>
             <div class="product-action-1 d-flex">
                 <a aria-label="Quick view" id="{{ $product->id }}" onclick="productView(this.id)" class="action-btn"
                     data-bs-toggle="modal" data-bs-target="#quickViewModal"><i class="fi-rs-eye"></i></a>
             </div>
             <!-- start product discount section -->
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
                <div class="product-badges-right product-badges-position-right product-badges-mrg">
                    @if ($product->discount_type == 1)
                        <span class="hot">৳{{ formatNumberInBengali($product->discount_price) }} ছাড়</span>
                    @elseif($product->discount_type == 2)
                        <span class="hot">{{ formatNumberInBengali($product->discount_price) }}% ছাড়</span>
                    @endif
                </div>
             @endif
         </div>
         
         <div class="product-content-wrap">
             <h2 class="mt-3">
                 <a href="{{ route('product.details', $product->slug) }}">
                     @if (session()->get('language') == 'bangla')
                         <?php $p_name_bn = strip_tags(html_entity_decode($product->name_bn)); ?>
                         {{ Str::limit($p_name_bn, $limit = 30, $end = '. . .') }}
                     @else
                         <?php $p_name_en = strip_tags(html_entity_decode($product->name_en)); ?>
                         {{ Str::limit($p_name_en, $limit = 30, $end = '. . .') }}
                     @endif
                 </a>
             </h2>
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
 
            <div class="product-card-bottom">
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

                @php
                    $productsellcount = \App\Models\OrderDetail::where('product_id', $product->id)->sum('qty') ?? 0;
                @endphp
                <span class="price">Sold({{ $productsellcount }})</span>
            </div>
            <div class="add-cart">
                @if ($product->is_varient == 1)
                    <a class="add" id="{{ $product->id }}" onclick="productView(this.id)"
                        data-bs-toggle="modal" data-bs-target="#quickViewModal"><i
                            class="fi-rs-shopping-cart mr-5"></i>Add </a>
                @else
                    <input type="hidden" id="pfrom" value="direct">
                    <input type="hidden" id="product_product_id" value="{{ $product->id }}" min="1">
                    <input type="hidden" id="{{ $product->id }}-product_pname" value="{{ $product->name_en }}">
                    <input type="hidden" id="buyNowCheck" value="0">
                    <button type="submit" class="button add_to_cart" onclick="addToCartDirect({{ $product->id }})"><i class="fi-rs-shoppi ng-cart"></i>Add to cart</button>
                    <button type="submit" class="button buy_now ml-5 bg-danger" onclick="buyNow({{ $product->id }})"><i class="fi-rs-shoppi ng-cart"></i>Buy Now</button>
                @endif
            </div>
            @if (auth()->check() && auth()->user()->role == 7)
                <div>
                    <span>Regular Price: <span class="text-info">৳ {{ formatNumberInBengali($product->regular_price) }}</span></span>
                    <input type="hidden" id="regular_price" name="regular_price" value="{{ $product->regular_price }}" min="1">
                </div>
            @endif
         </div>
     </div>
 </div>
 <script>
    function addToCartDetails(id){
        var total_attributes = parseInt($('#total_attributes').val()) || 0;
        var checkNotSelected = 0;
        var checkAlertHtml = '';
        for(var i=1; i<=total_attributes; i++){
            var checkSelected = parseInt($('#attribute_check_'+i).val());
            if(checkSelected == 0){
                checkNotSelected = 1;
                checkAlertHtml += `<div class="attr-detail mb-5">
                                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                                            <div>
                                                <i class="fa fa-warning mr-10"></i> <span> Select `+$('#attribute_name_'+i).val()+`</span>
                                            </div>
                                        </div>
                                    </div>`;
            }
        }
        if(checkNotSelected == 1){
            $('.qtyAlert').html('');
            $('.attributeAlert').html(`<div class="attr-detail mb-5">
                                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                                            <div>
                                                <i class="fa fa-warning mr-10"></i> <span> Select all attributes</span>
                                            </div>
                                        </div>
                                    </div>`);
            return false;
        }
        $('.size-filter li').removeClass("active");
        var product_name = $('.productName').val();
        var id = $('.productId').val();
        var price = $('.productPrice').val();
        var color = $('#color option:selected').val();
        var size = $('#size option:selected').val();
        var quantity = parseInt($('.quantityValue').val());
        var varient = $('#pvarient').val();
        var min_qty = parseInt($('.minimumBuyQty').val()) || 0;

        if(quantity < min_qty){
            $('#attribute_alert').html('');
            $('#qty_alert').html(`<div class="attr-detail mb-5">
                                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                                            <div>
                                                <i class="fa fa-warning mr-10"></i> <span> Minimum quantity `+ min_qty +` required.</span>
                                            </div>
                                        </div>
                                    </div>`);
            return false;
        }

        var p_qty = parseInt($('.stockQty').val());
        var options = $('#choice_form').serializeArray();
        var jsonString = JSON.stringify(options);
        //console.log(options);

        // Start Message
        const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                icon: 'success',
                showConfirmButton: false,
                timer: 1200
        });
        $.ajax({
        type:'POST',
        url:'/cart/data/store/'+id,
        dataType:'json',
        data:{
            color:color,size:size,quantity:quantity,product_name:product_name,product_price:price,product_varient:varient,options:jsonString,
        },
            success:function(data){
                console.log(data);
                miniCart();
                $('#closeModel').click();

                // Start Sweertaleart Message
                if($.isEmptyObject(data.error)){
                    const Toast = Swal.mixin({
                        toast:true,
                        position: 'top-end',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1200
                    })

                    Toast.fire({
                        type:'success',
                        title: data.success
                    })


                    $('#qty').val(min_qty);
                    $('#pvarient').val('');

                    for(var i=1; i<=total_attributes; i++){
                        $('#attribute_check_'+i).val(0);
                    }

                }else{
                    const Toast = Swal.mixin({
                        toast:true,
                        position: 'top-end',
                        icon: 'error',
                        showConfirmButton: false,
                        timer: 1200
                    })

                    Toast.fire({
                        type:'error',
                        title: data.error
                    })

                    $('#qty').val(min_qty);
                    $('#pvarient').val('');

                    for(var i=1; i<=total_attributes; i++){
                        $('#attribute_check_'+i).val(0);
                    }
                }
                // Start Sweertaleart Message
                var buyNowCheck = $('#buyNowCheck').val();
                if(buyNowCheck && buyNowCheck == 1){
                    $('#buyNowCheck').val(0);
                    window.location = '/checkout';
                }
            }
        });
    }
</script>