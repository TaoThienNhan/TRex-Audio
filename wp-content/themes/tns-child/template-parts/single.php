<?php

$home_url = get_home_url();

$archive_url = get_post_type_archive_link('post');

$single_post_title = get_the_title();

$post_thumbnail_id = get_post_thumbnail_id();

$post_thumbnail_url = wp_get_attachment_image_src($post_thumbnail_id, 'full');

$post_title = get_the_title();

$post_date = get_the_date('d/m/Y');

$categories = get_the_category();

?>

<section class="single-wrapper">
	<div class="container">
		<div class="breadcrumb">
			<a href="<?php echo $home_url; ?>">Trang chủ </a> &nbsp; <span>/</span> &nbsp;
			<?php
			if ($categories) {
				foreach ($categories as $category) {
					echo '<a href="' . get_category_link($category->term_id) . '">' . $category->name . '</a> &nbsp;<span>/</span>';
				}
			}
			?>
			&nbsp;<?php echo $single_post_title; ?>
		</div>
		<div class="row justify-content-center">
			<div class="col-lg-8">
				<?php
				if ($post_thumbnail_url) {
					$image_url = $post_thumbnail_url[0];
					?>

					<div class="post-thumbnail">
						<img src="<?php echo $image_url; ?>" alt="<?php echo $post_title; ?>">
					</div>

				<?php } ?>
				<h1 class="pt-5"><?php echo $post_title; ?></h1>
				<div class="entry-content">
					<?php the_content() ?>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="card">
					<div class="order-service-title py-3 text-dark">Bài Viết Khác</div>

					<?php
					$args = array(
						'post_type' => 'post',
						'posts_per_page' => 5,
					);

					$query = new WP_Query($args);

					if ($query->have_posts()) :
						while ($query->have_posts()) :
							$query->the_post();
							$post_thumbnail_id = get_post_thumbnail_id();
							$post_thumbnail_url = wp_get_attachment_image_src($post_thumbnail_id, 'full');
							$post_title = get_the_title();
							$post_date = get_the_date('d/m/Y');
							?>

							<div class="row post-item">
								<div class="col-lg-4">
									<?php if ($post_thumbnail_url) : ?>
										<a href="<?=get_permalink()?>">
											<img class="post-thumbnail-2" src="<?php echo $post_thumbnail_url[0]; ?>" alt="<?php echo $post_title; ?>">
										</a>
									<?php endif; ?>
								</div>
								<div class="col-lg-8 mt-3 mt-lg-0">
									<a href="<?=get_permalink()?>">
										<span class="post-title d-block"><?php echo $post_title; ?></span>
									</a>
									<a class="see-more" href="/">Xem Thêm <i class="fa-solid fa-caret-right"></i></a>
								</div>
							</div>

						<?php
						endwhile;
						wp_reset_postdata();
					else :
						echo 'Không có bài viết nào.';
					endif;
					?>
				</div>
			</div>
		</div>
	</div>
</section>