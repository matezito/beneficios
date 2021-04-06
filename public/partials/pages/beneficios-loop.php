<?php get_header() ?>
<?php do_action('beneficios_loop_header')?>
<div class="container">
    <div class="row">
        <div class="col-12 text-center">
            <h3><?php echo __('Beneficios', 'beneficios') ?></h3>
        </div>
        <div class="col-12 terms-names-filters">
        <span class="term-name-filter active"><a href="<?php echo get_permalink( get_option('beneficios_loop_page') )?>"><strong><?php echo __('Todos','beneficios')?></strong></a></span>
        <?php foreach (beneficios_front()->show_terms() as $t) :?>
            <span class="term-name-filter"><a href="<?php echo get_term_link($t->{'term_id'})?>"><strong><?php echo $t->{'name'}?></strong></a></span>
        <?php endforeach?>
        </div>
            <div class="col-12 term-container mt-5">
                <div class="row">
                    <?php foreach (beneficios_front()->show_posts_all() as $b) : ?>
                        <div class="col-md-4 col-12 beneficio <?php echo beneficios_front()->get_beneficio_by_user(wp_get_current_user()->ID) == $b->{'ID'} ? 'bg-secondary text-white' : '' ?>" data-term="<?php echo beneficios_front()->show_terms_slug_by_post($b->{'ID'})?>">
                            <a href="<?php echo get_permalink($b->{'ID'}) ?>"><img src="<?php echo get_the_post_thumbnail_url($b->{'ID'}) ?>" class="img-fluid" /></a>
                            <h4 class="title-beneficio">
                                <a href="<?php echo get_permalink($b->{'ID'}) ?>"><?php echo $b->{'post_title'} ?></a>
                            </h4>
                            <?php if (get_post_meta($b->{'ID'}, '_beneficio_discount', true) !== null || get_post_meta($b->{'ID'}, '_beneficio_discount', true) !== '') : ?>
                                <span class="discount"><strong><?php echo get_post_meta($b->{'ID'}, '_beneficio_discount', true) ?></strong></span>
                            <?php endif; ?>
                            <div class="category-beneficio mt-3">
                                <span class="text-category text-uppercase d-block"><?php echo __('Beneficios Tiempo', 'beneficios') ?></span>
                                <span class="title-category d-block font-italic text-secondary"><strong><?php echo beneficios_front()->show_terms_name_by_post($b->{'ID'})?></strong></span>
                            </div>
                            <div class="text mt-3 mb-3">
                                <button type="button" class="solicitar btn <?php echo beneficios_front()->get_beneficio_by_user(wp_get_current_user()->ID) == $b->{'ID'} ? 'btn-warning' : 'btn-primary' ?>" data-id="<?php echo $b->{'ID'} ?>" data-user="<?php echo wp_get_current_user()->ID?>"><?php echo beneficios_front()->get_beneficio_by_user(wp_get_current_user()->ID) == $b->{'ID'} ? __('Solicitado', 'beneficios') : __('Solicitar', 'beneficios') ?></button>
                                <button type="button" class="menos btn btn-text" data-content="#content<?php echo $b->{'ID'} ?>"><?php echo __('ver menos', 'beneficios') ?></button>
                                <div class="content mt-3" id="content<?php echo $b->{'ID'} ?>">
                                    <?php echo wp_trim_words($b->{'post_content'}, 55, null) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
    </div>
</div>
<?php do_action('beneficios_loop_footer')?>
<?php get_footer() ?>