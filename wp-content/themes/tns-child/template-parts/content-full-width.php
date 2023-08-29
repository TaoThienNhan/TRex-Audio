<?php while (have_posts()) : ?>

	<?php the_post(); ?>

	<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="entry-content">
						<?php the_content() ?>
					</div>
				</div>
			</div>
		</div>
	</article>

<?php endwhile;
