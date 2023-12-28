<section class="archive-wrapper">
    <?php $category = get_queried_object(); ?>
    <div class="container py-5">
        <div class="title">
            <div class="text text-white">
                <h2>Danh Mục: <?= $category->name ?></h2>
            </div>
        </div>
        <div class="content pt-5">
            <div class="row" id="post-container">
                <?php
                if (have_posts()) :
                    while (have_posts()) : the_post();
                        $post_title = get_the_title();
                        $post_date = get_the_date();
                        $post_content = get_the_content();
                        $post_thumbnail = get_the_post_thumbnail();

                        ?>
                        <div class="col-lg-4 mb-4">
                            <div class="card">
                                <a href="<?=get_permalink()?>"><?=$post_thumbnail?></a>
                                <div class="card-body text-center">
                                    <a class="card-title py-2 text-warning fw-bold" href="<?=get_permalink()?>"><?=$post_title?></a>
                                    <p class="card-text"><?=wp_trim_words($post_content, 30)?></p>
                                    <p class="card-date"><?= $post_date ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    <div class="pagination d-flex justify-content-center align-items-center">
                        <?php
                        global $wp_query;
                        $big = 999999999;
                        echo paginate_links(array(
                            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                            'format' => '?paged=%#%',
                            'current' => max(1, get_query_var('paged')),
                            'total' => $wp_query->max_num_pages,
                            'prev_text' => '&laquo;',
                            'next_text' => '&raquo;'
                        ));
                        ?>
                    </div>
                <?php else : ?>
                    <p class="no-posts">Không có bài viết nào trong danh mục này.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
