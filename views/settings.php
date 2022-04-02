<div class="wpretail-main container-fluid">
    <div class="row flex-nowrap">
        <div class="wpretail-sidebar col-auto col-md-3 col-xl-2 px-0">
            <div class="d-flex flex-column px-0">
                <ul class="nav  nav-tabs flex-column mb-sm-auto mb-0" id="menu">
                    <li class="nav-item">
                        <a href="#addproduct" class="nav-link wpretail-sidebar-link align-middle"  role="tab" aria-controls="addproduct" data-bs-toggle="tab">
						<span class="ms-1 d-none d-sm-inline">Add Product</span>
                        </a>
                    </li>
					<li class="nav-item">
                        <a href="#listproduct" class="nav-link wpretail-sidebar-link align-middle"  role="tab" aria-controls="listproduct" data-bs-toggle="tab">
						<span class="ms-1 d-none d-sm-inline">List Product</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col py-3">
            <div class="container">
				<div class="row">
					<div class="col-md-12">
					<div class="tab-content">
                        <div class="tab-pane active" id="addproduct" role="tabpanel">
							<div class="container">
								<div class="row">
									<div class="col-md-4">
										<div class="mb-3">
											<label for="name" class="form-label">Product Name:*</label>
											<input type="text" class="form-control" id="name" placeholder="Product Name" aria-required="true">
										</div>
									</div>
									<div class="col-md-4">
										<div class="mb-3">
											<label for="sku" class="form-label">SKU:*</label>
											<input type="text" class="form-control" id="sku" placeholder="SKU" aria-required="true">
										</div>
									</div>
									<div class="col-md-4">
										<div class="mb-3">
											<label for="barcode_type" class="form-label">Barcode Type:*</label>
											<select class="form-select"   name="barcode_type" id="barcode_type" aria-required="true">
												<option value="1">Code 122</option>
												<option value="2">Code 123</option>
												<option value="3">Code 124</option>
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<div class="mb-3">
											<label for="brand_id"  class="form-label">Units Type:*</label>
											<select class="form-select" name="unit_id" id="unit_id" aria-required="true">
												<option selected>Please Select</option>
												<option value="1">Pieces</option>
												<option value="2">Packets</option>
												<option value="3">Grams</option>
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<div class="mb-3">
											<label for="brand_id"  class="form-label">Brand :*</label>
											<select class="form-select" name="brand_id" id="brand_id" aria-required="true">
											<option selected>Brands</option>
												<option value="1">Apple</option>
												<option value="2">Iphone</option>
												<option value="3">Samsung</option>
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<div class="mb-3">
											<label for="category_id"  class="form-label">Category:*</label>
											<select class="form-select" name="category_id" id="category_id" aria-required="true">
											<option selected>Brands</option>
												<option value="1">Apple</option>
												<option value="2">Iphone</option>
												<option value="3">Samsung</option>
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<div class="mb-3">
											<label for="sub_category_id"  class="form-label">Sub Category:*</label>
											<select class="form-select" name="sub_category_id" id="sub_category_id" aria-required="true">
											<option selected>Brands</option>
												<option value="1">Apple</option>
												<option value="2">Iphone</option>
												<option value="3">Samsung</option>
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<div class="mb-3">
											<label for="business_location"  class="form-label">Business Location:*</label>
											<select class="form-select" name="business_location" id="business_location" aria-required="true">
											<option selected>Brands</option>
												<option value="1">Apple</option>
												<option value="2">Iphone</option>
												<option value="3">Samsung</option>
											</select>
										</div>
									</div>
								</div>
							</div>
                        </div>
						<div class="tab-pane" id="listproduct" role="tabpanel" >
                           List Product
						</div>
					</div>
				</div>
			</div>
        </div>
    </div>
</div>
