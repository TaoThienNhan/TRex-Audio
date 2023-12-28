<div class="header-wrapper d-flex justify-content-between align-items-center">
	<div class="container d-lg-flex justify-content-center justify-content-lg-between align-items-center">
		<div class="logo d-flex justify-content-center justify-content-lg-start">
			<a href="/">
				<img src="<?=get_field('logo', 'option')?>" alt="logo">
			</a>
		</div>
		<div class="search mt-3 d-none d-md-block mt-lg-0 lg-mt-0 d-sm-flex justify-content-center">
			<?php
			if ( class_exists( 'WooCommerce' ) ) {
				?>
				<form role="search" method="get" class="custom-search-form woocommerce-product-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<label class="screen-reader-text" for="woocommerce-product-search-field"><?php esc_html_e( 'Search for:', 'woocommerce' ); ?></label>
					<input type="search" id="woocommerce-product-search-field" class="search-field px-3" placeholder="<?php echo esc_attr_e( 'Search products...', 'woocommerce' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
					<button type="submit" class="search-submit px-4 border-0" style="background: url( <?= get_stylesheet_directory_uri(); ?>/assets/src/images/background-gold.png)"><?php echo esc_html_e( 'Search', 'woocommerce' ); ?></button>
					<input type="hidden" name="post_type" value="product" />
				</form>
				<?php
			}
			?>

		</div>
		<div class="phone d-none d-lg-flex border border-white p-1 d-flex justify-content-center align-items-center">
			<span class="phone-icon d-flex justify-content-center align-items-center" style="background: url( <?= get_stylesheet_directory_uri(); ?>/assets/src/images/background-gold.png)"><i class="fas fa-phone-volume"></i></span>
			<span class="text-white px-1"><i><?=get_field('phone_primary', 'option')?></i></span>
		</div>
		<div class="menu-mobile d-flex d-lg-none justify-content-between p-4">
			<a href="tel:<?=get_field('phone_primary', 'option')?>" class="phone-icon d-flex justify-content-center align-items-center text-black-50" style="background: url( <?= get_stylesheet_directory_uri(); ?>/assets/src/images/background-gold.png)">
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