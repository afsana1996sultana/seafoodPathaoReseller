@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<section class="content-main">
    <div class="content-header">
        <h2 class="content-title">Update Reseller Info</h2>
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
		                    <form method="POST" action="{{ route('reseller.update',['id'=>$reseller->id]) }}" enctype="multipart/form-data">
								@csrf
								<div class="form-group">
									<label for="name" class="col-form-label col-md-4 fw-bold">Reseller Name : <span class="text-danger"> *</span></label>
									<input type="text" class="form-control" name="name" placeholder="Name" id="name" value="{{ $reseller->name }}" required/>
									@error('name')
										<div class="text-danger" style="font-weight: bold;">{{ $message }}</div>
									@enderror
								</div>
								<div class="form-group">
									<label for="phone" class="col-form-label col-md-4 fw-bold">Reseller Bkash Number : <span class="text-danger"> *</span></label>
									<input type="number" class="form-control" name="phone" id="phone" placeholder="Phone Number" value="{{ $reseller->phone }}" required/>
									@error('phone')
										<div class="text-danger" style="font-weight: bold;">{{ $message }}</div>
									@enderror
								</div>
								<div class="form-group">
									<label for="email" class="col-form-label col-md-4 fw-bold">Reseller Email : <span class="text-danger"> *</span></label>
									<input type="email" class="form-control" name="email" id="email" placeholder="Email" value="{{ $reseller->email }}" required/>
									@error('email')
										<div class="text-danger" style="font-weight: bold;">{{ $message }}</div>
									@enderror
								</div>
								
								<div class="form-group">
									<label for="fb_web_url" class="col-form-label col-md-4 fw-bold">Facebook page link/ Website link : </label>
									<input type="text" class="form-control" name="fb_web_url" id="fb_web_url" placeholder="Facebook page link/ Website link" value="{{ $reseller->fb_web_url }}"/>
									@error('fb_web_url')
										<div class="text-danger" style="font-weight: bold;">{{ $message }}</div>
									@enderror
								</div>
								
								<div class="form-group">
									<label for="reseller_discount_percent" class="col-form-label col-md-4 fw-bold">Discount Percentage : <span class="text-danger"> *</span></label>
									<input type="text" class="form-control" name="reseller_discount_percent" id="reseller_discount_percent" placeholder="Discount amount in percentage" value="{{ $reseller->reseller_discount_percent }}"/>
									@error('reseller_discount_percent')
										<div class="text-danger" style="font-weight: bold;">{{ $message }}</div>
									@enderror
								</div>

								<div class="form-group">
									<div class="mb-4">
										<label for="image3" class="col-form-label" style="font-weight: bold;">Nid Card: </label>
										<input name="nid" class="form-control" type="file" id="image3">				                        
									</div>
										<div class="mb-4">
										<img id="showImage3" class="rounded avatar-lg" src="{{asset($reseller->nid) }}" alt="Card image cap" width="100px" height="80px;">
									</div>
								</div>

								<div class="mb-4 mt-30">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="form-check-input me-2 cursor" name="status" id="status" value="1" {{ $reseller->status == 1 ? 'checked': '' }} >
                                        <label class="form-check-label cursor" for="status">Status</label>
                                    </div>
                                </div>
								<div class="row mb-4 justify-content-sm-end mt-30">
									<div class="col-lg-3 col-md-4 col-sm-5 col-6">
									<button type="submit" class="btn btn-primary" name="login">Update</button>
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