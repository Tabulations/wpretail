<nav class="navbar navbar-expand-lg navbar-light bg-wpretail">
	<div class="container-fluid">
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarTogglerDemo01">
			<a class="navbar-brand" href="#">
				<strong>WPRetail</strong> - <small>Milan Shop</small>
			</a>
			<ul class="wpretail-header navbar-nav navbar-right  ms-auto mb-2 mb-lg-0">
				<li class="nav-item">
					<a class="nav-link active" aria-current="page" href="#"> <i class="fa-solid fa-chart-pie"></i> Analytics</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" aria-current="page" href="<?php echo admin_url( 'admin.php?page=wpretail&target=product' ); ?>"> <i class="fa fas fa-cubes"></i> Products</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" aria-current="page" href="<?php echo admin_url( 'admin.php?page=wpretail&target=purchase' ); ?>"> <i class="fa-solid fa-bag-shopping"></i>  Purchase</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" aria-current="page" href="#"> <i class="fa-solid fa-sack-dollar"></i> Sales</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" aria-current="page" href="#"> <i class="fa-solid fa-square-poll-horizontal"></i> Reports</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" aria-current="page" href="<?php echo admin_url( 'admin.php?page=wpretail&target=settings' ); ?>"> <i class="fa-solid fa-cog"></i> Settings</a>
				</li>
			</ul>
		</div>
	</div>
</nav>
