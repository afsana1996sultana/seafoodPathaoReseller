@extends('admin.admin_master')
@push('css')
	<style>
		.note-editable ul {
            list-style: disc !important;
            list-style-position: inside !important;
        }

        .note-editable ol {
            list-style: decimal !important;
            list-style-position: inside !important;
        }
	</style>
@endpush
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<section class="content-main">
    <div class="content-header">
        <h2 class="content-title">Add Product</h2>
        <div class="">
            <a href="{{ route('product.all') }}" class="btn btn-primary"><i class="material-icons md-plus"></i> Product List</a>
        </div>
    </div> 
	<div class="row">
        <div class="col-md-10 mx-auto">
			<form method="post" action="{{ route('product.store') }}" enctype="multipart/form-data">
				@csrf

				<div class="card">
					<div class="card-header">
						<h3>Basic Info</h3>
					</div>
		        	<div class="card-body">
		        		<div class="row">
		                	<div class="col-md-6 mb-4">
		                        <label for="product_name_en" class="col-form-label" style="font-weight: bold;">Product Name (En): <span class="text-danger">*</span></label>
		                        <input class="form-control" id="product_name_en" type="text" name="name_en" placeholder="Write product name english" value="{{old('name_en')}}">
		                        @error('name_en')
		                            <p class="text-danger">{{$message}}</p>
		                        @enderror
		                    </div>
		                    <div class="col-md-6 mb-4">
	                           	<label for="product_name_bn" class="col-form-label" style="font-weight: bold;">Product Name (Bn):</label>
	                           	<input class="form-control" id="product_name_bn" type="text" name="name_bn" placeholder="Write product name bangla" value="{{old('name_bn')}}" required>
		                    </div>
		        		</div>

		        		<div class="row">
		        			<div class="col-md-6 mb-4 d-none">
	                          <label for="product_code" class="col-form-label" style="font-weight: bold;">Product Code:</label>
	                            <input class="form-control" id="product_code" type="text" name="product_code" placeholder="Write product code" value="{{old('product_code')}}">
	                        </div>
							<div class="col-md-6 mb-4">
								<label for="product_category" class="col-form-label" style="font-weight: bold;">Category: <span class="text-danger">*</span></label>
								<a style="background-color: #3BB77E; "class="btn btn-sm float-end" data-bs-toggle="modal" data-bs-target="#category"><i class="fa-solid fa-plus text-white"></i></a>
								@php
									$selectedCategory = 0;
								@endphp
								<div class="custom_select">
									<select class="form-control select-active w-100 form-select select-nice" name="category_id" id="product_category">
										<option disabled hidden {{old('category_id') ? '' : 'selected'}} readonly value="">--Select Category--</option>
										@foreach ($categories as $category)
											<option value="{{ $category->id }}" {{old('category_id')== $category->id ? 'selected' :  '' }}>{{ $category->name_en }}</option>
											@foreach ($category->childrenCategories as $childCategory)
												@include('backend.include.child_category', ['child_category' => $childCategory])
											@endforeach
										@endforeach
									</select>
									@error('category_id')
										<p class="text-danger">{{$message}}</p>
									@enderror
								</div>
							</div>
		        		
							<div class="col-md-6 mb-4">
		        				<a style="background-color: #3BB77E; " type="button" class="btn btn-sm float-end" id="closeModal1" data-bs-toggle="modal" data-bs-target="#brand"><i class="fa-solid fa-plus text-white"></i></a>
	                           <label for="brand_id" class="col-form-label" style="font-weight: bold;">Brand:</label>
				                <div class="custom_select">
                                    <select class="form-control select-active w-100 form-select select-nice" name="brand_id" id="brand_id">
                                    	<option {{old('brand_id') ? '' : 'selected'}} readonly value="">--Select Brand--</option>
		                                @foreach ($brands as $brand)
		                                    <option value="{{ $brand->id }}" {{ old('brand_id')== $brand->id ? 'selected' : '' }}>{{ $brand->name_en }}</option>
		                                @endforeach
                                    </select>
                                </div>
	                        </div>
		        		
		        			@if(get_setting('multi_vendor')->value)
		        			    @if(Auth::guard('admin')->user()->role == '2')
		        			        <input type="hidden" name="vendor_id" id="vendor_id" value="{{ Auth::guard('admin')->user()->id }}" />
		        			    @else
								<div class="col-md-6 mb-4">
									<label for="vendor_id" class="col-form-label" style="font-weight: bold;">Vendor: </label>
									<div class="custom_select">
										<select class="form-control select-active w-100 form-select select-nice" name="vendor_id" id="vendor_id">
											<!--<option selected="">Select Vendor</option>-->
											<option {{old('vendor_id') ? '' : 'selected'}} readonly value="">--Select Vendor--</option>
											@foreach($vendors as $vendor)
												<option value="{{ $vendor->user_id }}">{{ $vendor->shop_name }}</option>
											@endforeach
										</select>
										@error('vendor_id')
        		                            <p class="text-danger">{{$message}}</p>
        		                        @enderror
									</div>
								</div>
								@endif
							@endif

	                        <div class="col-md-6 mb-4">
								<label for="supplier_id" class="col-form-label" style="font-weight: bold;">Supplier:</label>
								<div class="custom_select">
									<select class="form-control select-active w-100 form-select select-nice" name="supplier_id" id="supplier_id">
										<option {{old('supplier_id') ? '' : 'selected'}} readonly value="">--Select Supplier--</option>
										@foreach($suppliers as $supplier)
											<option value="{{ $supplier->id }}" {{ old('supplier_id')== $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
										@endforeach
									</select>
								</div>
						   	</div>
							<div class="col-md-6 mb-4">
								<label for="unit_id" class="col-form-label" style="font-weight: bold;">Unit Type:</label>
								<div class="custom_select">
									<select class="form-control select-active w-100 form-select select-nice" name="unit_id" id="unit_id">
										<option disabled hidden {{old('unit_id') ? '' : 'selected'}} readonly value="">--Select Unit Type--</option>
										@foreach($units as $unit)
											<option value="{{ $unit->id }}" {{ old('unit_id')== $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
										@endforeach
									</select>
								</div>
						   	</div>
							<div class="col-md-6 mb-4">
								<label for="unit_weight" class="col-form-label" style="font-weight: bold;">Unit Weight (e.g. 10 mg, 1 Carton, 15 Pcs)</label>
								<input class="form-control" id="unit_weight" type="number" name="unit_weight" placeholder="Write unit weight" value="{{old('unit_weight')}}">
						   	</div>
							<div class="col-md-6 mb-4">
		                        <label for="" class="col-form-label" style="font-weight: bold;">Tags:</label>
			                    <input class="form-control tags-input" type="text"name="tags[]"placeholder="Type and hit enter to add a tag" value="{{old('tags[]')}}">
			                    <small class="text-muted d-block">This is used for search.</small>
		                    </div>
		        		</div>
		        		<!-- row //-->
		        	</div>
		        	<!-- card body .// -->
			    </div>
			    <!-- card .// --> 

			    <div class="card">
					<div class="card-header" style="background-color: #fff !important;">
						<h3 style="color: #4f5d77 !important">Product Variation</h3>
					</div>
		        	<div class="card-body">
		        		<div class="row">
	                        <!-- Variation Start -->
	                        <div class="col-md-6 mb-4">
				                <div class="custom_select cit-multi-select">
				                	<label for="choice_attributes" class="col-form-label" style="font-weight: bold;">Attributes:</label>
                                    <select class="form-control select-active w-100 form-select select-nice" name="choice_attributes[]" id="choice_attributes" multiple="multiple" data-placeholder="Choose Attributes">
					                	@foreach($attributes as $attribute)
					                		<option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
					               		@endforeach
                                    </select>
                                </div>
	                        </div>

	                        <div class="col-md-12 mb-4">
	                        	<div class="customer_choice_options" id="customer_choice_options">
									
	                        	</div>
	                        </div>
	                        <!-- Variation End -->
		        		</div>
		        	</div>
		        </div>
		        <!-- card //-->

		        <div class="card">
					<div class="card-header" style="background-color: #fff !important;">
						<h3 style="color: #4f5d77 !important">Pricing</h3>
					</div>
		        	<div class="card-body">
		        		<div class="row">
		        			<div class="col-md-6 mb-4">
	                          	<label for="purchase_price" class="col-form-label" style="font-weight: bold;">Product Buying Price: <span class="text-danger">*</span></label>
	                            <input class="form-control" id="purchase_price" type="number" name="purchase_price" placeholder="Write product bying price" value="{{old('purchase_price')}}">
		                        @error('purchase_price')
                                    <p class="text-danger">{{$message}}</p>
                                @enderror
		                    </div>

							<div class="col-md-6 mb-4">
	                          	<label for="regular_price" class="col-form-label" style="font-weight: bold;">Regular Price: <span class="text-danger">*</span></label>
	                            <input class="form-control" id="regular_price" type="number" name="regular_price" placeholder="Write product regular price" min="0" value="{{old('regular_price')}}">
		                        @error('regular_price')
	                                <p class="text-danger">{{$message}}</p>
	                            @enderror
	                        </div>
		        		</div>

						<div class="row">
							<div class="col-md-6 mb-4">
	                          	<label for="whole_sell_price" class="col-form-label" style="font-weight: bold;">Whole Sell Price:</label>
	                            <input class="form-control" id="whole_sell_price" type="number" name="wholesell_price" placeholder="Write product whole sell price" min="0" value="{{old('wholesell_price', 0)}}" >
	                        </div>
	                        <div class="col-md-6 mb-4">
	                          	<label for="whole_sell_qty" class="col-form-label" style="font-weight: bold;">Whole Sell Minimum Quantity:</label>
	                            <input class="form-control" id="whole_sell_qty" type="number" name="wholesell_minimum_qty" placeholder="Write product whole sell qty" value="{{old('wholesell_minimum_qty', 0)}}">
	                        </div>
						</div>

		        		<!-- Row //-->
		        		<div class="row">
	                        <div class="col-md-6 mb-4">
	                          	<label for="discount_price" class="col-form-label" style="font-weight: bold;">Discount Price:</label>
	                            <input class="form-control" id="discount_price" type="number" name="discount_price" value="{{old('discount_price', 0)}}" min="0" placeholder="Write product discount price">
	                        </div>

	                        <div class="col-md-6 mb-4">
	                         	<label for="discount_type" class="col-form-label" style="font-weight: bold;">Discount Type:</label>
				                <div class="custom_select">
                                    <select class="form-control select-active w-100 form-select select-nice" name="discount_type" id="discount_type">
					                	<option value="1">Flat</option>
	                            		<option value="2">Parcent %</option>
                                    </select>
                                </div>
	                        </div>

	                        <div class="col-md-6 mb-4">
								<label for="minimum_buy_qty" class="col-form-label" style="font-weight: bold;">Minimum Buy Quantity:</label>
								<input class="form-control" id="minimum_buy_qty" type="number" name="minimum_buy_qty" placeholder="Write product qty" value="{{old('minimum_buy_qty', 1)}}" min="1">
								@error('minimum_buy_qty')
									<p class="text-danger">{{$message}}</p>
								@enderror
							</div>

							<div class="col-md-6 mb-4">
								<label for="stock_qty" class="col-form-label" style="font-weight: bold;">Stock Quantity: <span class="text-danger">*</span></label>
								<input class="form-control" id="stock_qty" type="number" name="stock_qty" value="{{old('stock_qty', 0)}}" min="0" placeholder="Write product stock  qty">
								@error('stock_qty')
								   <p class="text-danger">{{$message}}</p>
							   	@enderror
							</div>

							<div class="col-md-6 mb-4">
								<label for="reseller_price" class="col-form-label" style="font-weight: bold;">Reseller Price: </label>
								<input class="form-control" id="reseller_price" type="number" name="reseller_price" placeholder="Write product regular price" min="0" value="{{ old('reseller_price') }}">
								@error('reseller_price')
									<p class="text-danger">{{ $message }}</p>
								@enderror
							</div>

							<!-- Product Attribute Price combination Starts -->
							<div class="col-12 mt-2 mb-2" id="variation_wrapper">
								<label for="" class="col-form-label" style="font-weight: bold;">Price Variation:</label>
								<table class="table table-active table-success table-bordered" id="combination_table">
									<thead>
										<tr>
											<th>Variant</th>
											<th>Price</th>
											<th>SKU</th>
											<th>Quantity</th>
											<th>Photo</th>
										</tr>
									</thead>
									<tbody>

									</tbody>
								</table>
							</div>
							<!-- Product Attribute Price combination Ends -->
			        	</div>
			        	<!-- Row //-->
		        	</div>
		        </div>
		        <!-- card //-->

		        <div class="card">
		        	<div class="card-header" style="background-color: #fff !important;">
						<h3 style="color: #4f5d77 !important">Short Description</h3>
					</div>
		        	<div class="card-body">
		        		<div class="row">
		        			<!-- Short Description Start -->
	                        <div class="col-md-6 mb-4">
	                          	<label for="short_descp_en" class="col-form-label" style="font-weight: bold;">Description (En):</label>
	                            <textarea name="short_description_en" rows="2" cols="2" class="form-control summernote" placeholder="Write Short Description English">{{old('short_description_en')}}</textarea>
	                        </div>
	                        <div class="col-md-6 mb-4">
	                          	<label for="short_descp_bn" class="col-form-label" style="font-weight: bold;">Description (Bn):</label>
	                            <textarea name="short_description_bn" id="short_descp_bn" rows="2" cols="2" class="form-control summernote" placeholder="Write Short Description Bangla">{{old('short_description_bn')}}</textarea> 
	                        </div>
	                        <!-- Short Description End -->
		        		</div>
		        	</div>
		        </div>
		        <!-- card //-->
		        
		        
		        <div class="card">
		        	<div class="card-header" style="background-color: #fff !important;">
						<h3 style="color: #4f5d77 !important">Long Description</h3>
					</div>
		        	<div class="card-body">
		        		<div class="row">
		        			<!-- Long Description Start -->
	                        <div class="col-md-6 mb-4">
	                          	<label for="long_descp_en" class="col-form-label" style="font-weight: bold;">Description (En):</label>
	                            <textarea name="description_en" rows="2" cols="2" class="form-control summernote" placeholder="Write Long Description English">{{old('description_en')}}</textarea>
	                        </div>
	                        <div class="col-md-6 mb-4">
	                          	<label for="long_descp_bn" class="col-form-label" style="font-weight: bold;">Description (Bn):</label>
	                            <textarea name="description_bn" id="long_descp_bn" rows="2" cols="2" class="form-control summernote" placeholder="Write Long Description Bangla">{{old('description_bn')}}</textarea> 
	                        </div>
	                        <!-- Long Description End -->
		        		</div>
		        	</div>
		        </div>
		        <!-- card //-->

		        <div class="card">
		        	<div class="card-header" style="background-color: #fff !important;">
						<h3 style="color: #4f5d77 !important">Product Image</h3>
					</div>
		        	<div class="card-body">
	        			<!-- Porduct Image Start -->
                        <div class="mb-4">
							<label for="product_thumbnail" class="col-form-label" style="font-weight: bold;">Product Image: <span class="text-danger">*</span></label>
							<input type="file" name="product_thumbnail" class="form-control" id="product_thumbnail" onChange="mainThamUrl(this)">
							<img src="" class="p-2" id="mainThmb">
							@error('product_thumbnail')
								<p class="text-danger">{{$message}}</p>
							@enderror
						</div>
						<div class="mb-4">
							<label for="multiImg" class="col-form-label" style="font-weight: bold;">Product Gallery Image:</label>
							<input type="file" name="multi_img[]" class="form-control" multiple="" id="multiImg" >
							<div class="row  p-2" id="preview_img">
							</div>
						</div>
						<!-- Porduct Image End -->
		        		<!-- Checkbox Start -->
                        <div class="mb-4">
                        	<div class="row">
                          		<div class="custom-control custom-switch">
                                    <input type="checkbox" class="form-check-input me-2 cursor" name="is_deals" id="is_deals" value="1">
                                    <label class="form-check-label cursor" for="is_deals">Today's Deal</label>
                                </div>
                          	</div>
                          	<div class="row">
                          		<div class="custom-control custom-switch">
									@if (Auth::guard('admin')->user()->role != '2')
										<input type="checkbox" class="form-check-input me-2 cursor" name="is_resell" id="is_resell" checked value="1">
									@else
										<input type="checkbox" class="form-check-input me-2 cursor" name="is_resell" id="is_resell" value="1">
									@endif
									<label class="form-check-label cursor" for="is_resell">Reselling</label>
                                </div>
                          	</div>
                          	<div class="row">
                          		<div class="custom-control custom-switch">
                                    <input type="checkbox" class="form-check-input me-2 cursor" name="is_featured" id="is_featured" value="1">
                                    <label class="form-check-label cursor" for="is_featured">Featured</label>
                                </div>
                          	</div>
                          	<div class="row">
                          		<div class="custom-control custom-switch">
                                    <input type="checkbox" class="form-check-input me-2 cursor" name="status" id="status" checked value="1">
                                    <label class="form-check-label cursor" for="status">Status</label>
                                </div>
                          	</div>
                        </div>
                        <!-- Checkbox End -->
		        	</div>
		        </div>
		        <!-- card -->

			    <div class="row mb-4 justify-content-sm-end">
					<div class="col-lg-2 col-md-4 col-sm-5 col-6">
						<input type="submit" class="btn btn-primary" value="Submit">
					</div>
				</div>
		    </form>  
		</div>
	</div>
</section>


<!--  Category Modal -->
<div class="modal fade" id="category" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header card-header">
        <h3>Category Create</h3>
        <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      	<form method="POST" enctype="multipart/form-data" id="category_store" action="" >
      		<input type="hidden" name="_token" id="csrf" value="{{Session::token()}}">
	      	<div class="row">
	        	<div class="col-lg-6">
	        		<div class="mb-1">
		                <label class="col-form-label" style="font-weight: bold;">Name English:</label>
		                <input class="form-control" type="text" id="name_en" name="name_en" placeholder="Write category name english">
		            </div>
	        	</div>
	        	<div class="col-lg-6">
	        		<div class="mb-1">
		                <label class="col-form-label" style="font-weight: bold;">Name Bangla:</label>
		                <input type="text" placeholder="Write category name bangla" id="name_bn" name="name_bn" class="form-control" >
		            </div>
	        	</div>
	        	<div class="col-lg-6">
	        		<div class="mb-1">
		            	<label class="col-form-label" style="font-weight: bold;">Parent Category:</label>
		                <div class="custom_select">
		                    <select class="form-control select-active form-select select-nice" style="width: 220px;" name="parent_id" id="parent_id">
		                    	<option value="0">--No Parent--</option>
		                        @foreach ($categories as $category)
		                            <option id="cat{{$category->id}}" value="{{ $category->id }}">{{ $category->name_en }}</option>
		                            @foreach ($category->childrenCategories as $childCategory)
		                                @include('backend.include.child_category', ['child_category' => $childCategory])
		                            @endforeach
		                        @endforeach
		                    </select>
		                </div>
		            </div>
	        	</div>
	            <div class="mb-1 mt-2">
	                <img id="showImage" class="rounded avatar-lg" src="{{ (!empty($editData->profile_image))? url('upload/admin_images/'.$editData->profile_image):url('upload/no_image.jpg') }}" alt="Card image cap" width="100px" height="80px;">
	            </div>
	            <div class="mb-1">
	                <label for="image" class="col-form-label" style="font-weight: bold;">Image:</label>
	                <input name="image" class="form-control" type="file" id="image">
	            </div>
	            <div class="modal-footer">
			        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			        <button type="submit" id="submit" class="btn btn-primary">Submit</button>
			    </div>
			</div>
		</form>
      </div>
    </div>
  </div>
</div>


<!--  Brand Modal -->
<div class="modal fade" id="brand" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header card-header">
      	<h3>Brand Create</h3>
        <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      	<form method="POST" enctype="multipart/form-data" id="brand_store" action="" >
      		<input type="hidden" name="_token" id="csrf" value="{{Session::token()}}">
	      	<div class="row">
	        	<div class="col-lg-6">
	        		<div class="mb-1">
		                <label class="col-form-label" style="font-weight: bold;">Name English:</label>
		                <input class="form-control name_en" type="text" name="name_en" placeholder="Write brand name english">
		            </div>
	        	</div>
	        	<div class="col-lg-6">
	        		<div class="mb-1">
		                <label class="col-form-label" style="font-weight: bold;">Name Bangla:</label>
		                <input type="text" placeholder="Write brand name bangla"  name="name_bn" class="form-control name_bn" >
		            </div>
	        	</div>
	        	<div class="col-lg-6">
	        		 <div class="mb-1">
		                <img  class="rounded avatar-lg showImage" src="{{ (!empty($editData->profile_image))? url('upload/admin_images/'.$editData->profile_image):url('upload/no_image.jpg') }}" alt="Card image cap" width="100px" height="80px;">
		            </div>
	        	</div>
	            <div class="mb-1">
	                <label for="image" class="col-form-label" style="font-weight: bold;">Image:</label>
	                <input name="brand_image" class="form-control brand_image" type="file">
	            </div>
	     		<div class="modal-footer">
				    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				    <button type="submit" id="submit" class="btn btn-primary">Submit</button>
				</div>
			</div>
		</form>
    </div>
  </div>
</div>
@endsection
@push('footer-script')
<script>
    function makeCombinationTable(el) {
        $.ajax({
            url: '{{ route('admin.api.attributes.index') }}',
            type: 'get',
            dataType: 'json',
            processData: true,
            data: $(el).closest('form').serializeArray().filter(function (field) {
                return field.name.includes('choice');
            }),
            success: function (response) {
				//console.log(response);
                if (!response.success) {
                    return;
                }
				if(Object.keys(response.data).length > 0) {
					let price = $('#regular_price').val();
					let qty = $('#stock_qty').val();
					$('#combination_table tbody').html($.map(response.data, function (item, index) {
						return `<tr>
									<td>${index}<input type="hidden" name="vnames[]" class="form-control" value="${index}" required></td>
									<td><input type="text" name="vprices[]" class="form-control vdp" value="`+price+`" required></td>
									<td><input type="text" name="vskus[]" class="form-control" required value="sku-${index}"></td>
									<td><input type="text" name="vqtys[]" class="form-control" value="10" required></td>
									<td><input type="file" name="vimages[]" class="form-control"></td>
								</tr>`;
					}).join());
					$('#variation_wrapper').show();
				}else{
					$('#combination_table tbody').html();
				}
				
            }
        });
    }
</script>
<!-- Attribute -->
<script type="text/javascript">
	function add_more_customer_choice_option(i, name){
        $.ajax({
            type:"POST",
            url:'{{ route('products.add-more-choice-option') }}',
            data:{
               attribute_ids: i,
               _token:  "{{ csrf_token() }}"
            },
            success: function(data) {
                $('#customer_choice_options').append(data);
           }
       });
    }

	$('#choice_attributes').on('change', function() {
        $('#customer_choice_options').html(null);
     
    	$('#choice_attributes').val();
    	add_more_customer_choice_option($(this).val(), $(this).text());
    });

    $('#regular_price').on('keyup', function() {
    	var price = $('#regular_price').val();
    	$('.vdp').val(price);
    });	
</script>

<!-- Attribute end -->


<!-- Product Image -->
<script type="text/javascript">
	function mainThamUrl(input){
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e){
				$('#mainThmb').attr('src',e.target.result).width(100).height(80);
			};
			reader.readAsDataURL(input.files[0]);
		}
	}	
</script>

<!-- Product MultiImg -->
<script>
  $(document).ready(function(){
	$('#variation_wrapper').hide();
   	$('#multiImg').on('change', function(){ //on file input change
      if (window.File && window.FileReader && window.FileList && window.Blob) //check File API supported browser
      {
          var data = $(this)[0].files; //this file data
           
          $.each(data, function(index, file){ //loop though each file
              if(/(\.|\/)(gif|jpe?g|png)$/i.test(file.type)){ //check supported file type
                  var fRead = new FileReader(); //new filereader
                  fRead.onload = (function(file){ //trigger function on successful read
                  return function(e) {
                      var img = $('<img/>').addClass('thumb').attr('src', e.target.result) .width(100)
                  .height(80); //create image element 
                      $('#preview_img').append(img); //append image to output element
                  };
                  })(file);
                  fRead.readAsDataURL(file); //URL representing the file's data.
              }
          });
           
      }else{
          alert("Your browser doesn't support File API!"); //if File API is absent
      }
   });
  });
</script>


<!-- Malti Tags  -->
<script type="text/javascript">
	$(document).ready(function(){        
	  var tagInputEle = $('.tags-input');
	  tagInputEle.tagsinput();
	});
</script>

<!-- Ajax Update Category Store -->
<script type="text/javascript">
	$(document).ready(function (e) {
		
			$('#category_store').submit(function(e) {
				e.preventDefault();
				var formData = new FormData(this);

			$.ajax({
				type:'POST',
				url: "{{ route('category.ajax.store') }}",
				data: formData,
				cache:false,
				contentType: false,
				processData: false,
				success: (data) => {
					$('select[name="category_id"]').html('<option value="" selected="" disabled="">Select Category</option>');
                    $.each(data.categories, function(key, value){
                        $('select[name="category_id"]').append('<option value="'+ value.id +'">' + value.name_en + '</option>');
                        $.each(value.children_categories, function(k, sub) {
                        	var stx = '';
						    for (var i=0; i < sub.type; i++){
						        stx += '--';
						    }
                        	$('select[name="category_id"]').append('<option value="'+ sub.id +'">'+ stx + sub.name_en + '</option>');
                        });
                    });

					// console.log(data);
					$('#category' ).modal('hide');
					$('#showImage').remove();
					$('#cat{{$category->id}}').remove();
					this.reset();
					// Start Message 
                    const Toast = Swal.mixin({
                          toast: true,
                          position: 'top-end',
                          icon: 'success',
                          showConfirmButton: false,
                          timer: 2000
                        })
                    if ($.isEmptyObject(data.error)) {
                        Toast.fire({
                            type: 'success',
                            title: data.success
                        })
                    }else{
                        Swal.fire({
						  icon: 'error',
						  title: data.error,
						})
                    }
                    // End Message


					// alert('Image has been uploaded using jQuery ajax successfully');
				},
				
				error: function(data){
					console.log(data);
				}
			});
		});
	});
</script>

<!-- Ajax Brand Update Store -->
<script type="text/javascript">
	$(document).ready(function (e) {
		
			$('#brand_store').submit(function(e) {
				e.preventDefault();
				var formData = new FormData(this);

			$.ajax({
				type:'POST',
				url: "{{ route('brand.ajax.store') }}",
				data: formData,
				cache:false,
				contentType: false,
				processData: false,
				success: (data) => {
					$('select[name="brand_id"]').html('<option value="" selected="" disabled="">Select Brand</option>');
                    $.each(data.brands, function(key, value){
                        $('select[name="brand_id"]').append('<option value="'+ value.id +'">' + value.name_en + '</option>');
                    });

					$( '#brand' ).modal('hide');
					$('.showImage').remove();
					this.reset();
					// Start Message 
                    const Toast = Swal.mixin({
                          toast: true,
                          position: 'top-end',
                          icon: 'success',
                          showConfirmButton: false,
                          timer: 2000
                        })
                    if ($.isEmptyObject(data.error)) {
                        Toast.fire({
                            type: 'success',
                            title: data.success
                        })
                    }else{
                        Swal.fire({
						  icon: 'error',
						  title: data.error,
						})
                    }
                    // End Message

					// alert('Image has been uploaded using jQuery ajax successfully');
				},
				
				error: function(data){
					console.log(data);
				}
			});
		});
	});
</script>


<!-- modal brand show image  -->
<script type="text/javascript">
    $(document).ready(function(){
        $('.brand_image').change(function(e){
            var reader = new FileReader();
            reader.onload = function(e){
                $('.showImage').attr('src',e.target.result);
            }
            reader.readAsDataURL(e.target.files['0']);
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const isResellCheckbox = document.getElementById("is_resell");
        const resellerPriceInput = document.getElementById("reseller_price");

        function toggleResellerPrice() {
            resellerPriceInput.disabled = !isResellCheckbox.checked;
        }

        // Initial check when the page loads
        toggleResellerPrice();

        // Add event listener for changes
        isResellCheckbox.addEventListener("change", toggleResellerPrice);
    });
</script>
@endpush