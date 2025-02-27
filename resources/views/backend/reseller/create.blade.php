@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<section class="content-main">
    <div class="content-header">
        <h2 class="content-title">Reseller Create</h2>
        <div class="">
            <a href="{{ route('reseller.index') }}" class="btn btn-primary"><i class="material-icons md-list"></i> Reseller List</a>
        </div>
    </div> 
    <div class="row justify-content-center">
    	<div class="col-sm-8">
    		<div class="card">
		        <div class="card-body">
		            <div class="row">
		                <div class="col-md-12">
		                    <form method="POST" action="{{ route('reseller.store') }}" enctype="multipart/form-data">
								@csrf
								<div class="form-group">
									<label for="name" class="col-form-label col-md-4 fw-bold">Reseller Name : <span class="text-danger"> *</span></label>
									<input type="text" class="form-control" name="name" placeholder="Name" id="name" value="{{ old('name') }}" required/>
									@error('name')
										<div class="text-danger" style="font-weight: bold;">{{ $message }}</div>
									@enderror
								</div>
								<div class="form-group">
									<label for="phone" class="col-form-label col-md-4 fw-bold">Reseller Bkash Number : <span class="text-danger"> *</span></label>
									<input type="number" class="form-control" name="phone" id="phone" placeholder="Phone Number" value="{{ old('phone') }}" required/>
									@error('phone')
										<div class="text-danger" style="font-weight: bold;">{{ $message }}</div>
									@enderror
								</div>
								<div class="form-group">
									<label for="email" class="col-form-label col-md-4 fw-bold">Reseller Email : <span class="text-danger"> *</span></label>
									<input type="email" class="form-control" name="email" id="email" placeholder="Email" value="{{ old('email') }}" required/>
									@error('email')
										<div class="text-danger" style="font-weight: bold;">{{ $message }}</div>
									@enderror
								</div>
								
								<div class="form-group">
									<label for="fb_web_url" class="col-form-label col-md-4 fw-bold">Facebook page link/ Website link : </label>
									<input type="text" class="form-control" name="fb_web_url" id="fb_web_url" placeholder="Facebook page link/ Website link" value="{{ old('fb_web_url') }}"/>
									@error('fb_web_url')
										<div class="text-danger" style="font-weight: bold;">{{ $message }}</div>
									@enderror
								</div>
								
								<div class="form-group">
									<label for="password" class="col-form-label col-md-4 fw-bold">Password : <span class="text-danger"> *</span></label>
									<input type="password" class="form-control" name="password" placeholder="Password" id="password" autocomplete="new-password" required/>
									<span>password must be at least 8 characters</span>
									@error('password')
										<div class="text-danger" style="font-weight: bold;">{{ $message }}</div>
									@enderror
								</div>

								<div class="form-group">
									<label class="col-form-label col-md-4 fw-bold">Confirm password : <span class="text-danger"> *</span></label>
									<input type="password" class="form-control" placeholder="Confirm password" name="password_confirmation" required/>
									@error('password')
										<div class="text-danger" style="font-weight: bold;">{{ $message }}</div>
									@enderror
								</div>

								<div class="form-group">
									<label for="reseller_discount_percent" class="col-form-label col-md-4 fw-bold">Discount Percentage :</label>
									<input type="text" class="form-control" name="reseller_discount_percent" id="reseller_discount_percent" placeholder="Discount amount in percentage" value="{{ old('reseller_discount_percent') }}"/>
									@error('reseller_discount_percent')
										<div class="text-danger" style="font-weight: bold;">{{ $message }}</div>
									@enderror
								</div>

								<div class="form-group">
									<div class="mb-4">
										<img id="showImage3" class="rounded avatar-lg" src="{{ (!empty($editData->profile_image))? url('upload/admin_images/'.$editData->profile_image):url('upload/no_image.jpg') }}" alt="Card image cap" width="100px" height="80px;">
									</div>
									<div class="mb-4">
										<label for="image3" class="col-form-label" style="font-weight: bold;">Nid Card: </label>
										<input name="nid" class="form-control" type="file" id="image3">
									</div>
								</div>

								<div class="row mb-4 justify-content-sm-end mt-30">
									<div class="col-lg-3 col-md-4 col-sm-5 col-6">
									<button type="submit" class="btn btn-primary" name="login">Submit</button>
									</div>
								</div>
							</form>
		                </div>
		            </div>
		            <!-- .row // -->
		        </div>
		        <!-- card body .// -->
		    </div>
		    <!-- card .// -->
    	</div>
    </div>
</section>
@endsection
@push('footer-script')
    <!-- Nid Card Show -->
    <script type="text/javascript">
        $(document).ready(function(){
            $('#image3').change(function(e){
                var reader = new FileReader();
                reader.onload = function(e){
                    $('#showImage3').attr('src',e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });
        });
    </script>
@endpush