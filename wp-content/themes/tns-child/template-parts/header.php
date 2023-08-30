<div class="header-wrapper d-flex justify-content-between align-items-center">
	<div class="container d-lg-flex justify-content-center justify-content-lg-between align-items-center">
		<div class="logo d-flex justify-content-center justify-content-lg-start">
			<img src="<?= get_stylesheet_directory_uri(); ?>/assets/src/images/logo.png" alt="">
		</div>
		<div class="search mt-3 mt-lg-0 lg-mt-0 d-sm-flex justify-content-center">
			<form role="search" method="get" class="custom-search-form d-flex align-items-center" action="<?php echo esc_url(home_url('/')); ?>">
				<input type="search" class="search-field px-3" placeholder="Bạn cầm tìm gì?" value="<?php echo get_search_query(); ?>" name="s">
				<button type="submit" class="search-submit px-4 border-0" style="background: url( <?= get_stylesheet_directory_uri(); ?>/assets/src/images/background-gold.png)">
					Tìm Kiếm
				</button>
			</form>
		</div>
		<div class="phone d-none d-lg-flex border border-white p-1 d-flex justify-content-center align-items-center">
			<span class="phone-icon d-flex justify-content-center align-items-center" style="background: url( <?= get_stylesheet_directory_uri(); ?>/assets/src/images/background-gold.png)"><i class="fas fa-phone-volume"></i></span>
			<span class="text-white px-1"><i>0937 410 557</i></span>
		</div>
		<div class="menu-mobile d-flex d-lg-none justify-content-between p-4">
			<a href="#" class="phone-icon d-flex justify-content-center align-items-center text-black-50" style="background: url( <?= get_stylesheet_directory_uri(); ?>/assets/src/images/background-gold.png)">
				<i class="fas fa-phone-volume"></i>
			</a>
			<?php
			wp_nav_menu(array(
				'theme_location' => 'danh-muc-menu',
				'container' => 'nav',
				'container_id' => 'mmenu',
			));
			?>
			<button id="mmenu-btn" class="menu-cate-mobile" style="background: url( <?= get_stylesheet_directory_uri(); ?>/assets/src/images/background-gold.png)"><i class="fa-solid fa-bars"></i></button>
		</div>
	</div>
</div>