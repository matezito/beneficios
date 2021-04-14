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
                <?php
                    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                     $args = [
                        'post_type' => 'beneficios',
                        'posts_per_page' => 12,
                        'paged' => $paged,
                        'meta_query' => [
                            'relation' => 'AND',
                            [
                                'key' => '_active',
                                'value' => '1',
                                'compare' => 'LIKE'
                            ],
                            [
                                'key' => '_finish',
                                'value' => date('Y-m-d'),
                                'compare' => '>=',
                                'type' => 'DATE'
                            ]
                        ]
                    ];
                    $beneficios = new WP_Query( $args );

                ?>
                <?php if($beneficios->have_posts()):?>
                    <?php while($beneficios->have_posts()): $beneficios->the_post();?>

   
                        <div class="col-md-4 col-12 beneficio <?php echo beneficios_front()->get_beneficio_by_user(wp_get_current_user()->ID,get_the_ID()) ? 'bg-secondary text-white' : '' ?>" data-term="<?php echo beneficios_front()->show_terms_slug_by_post(get_the_ID())?>">
                            <a href="<?php echo get_permalink(get_the_ID()) ?>"><img src="<?php echo get_the_post_thumbnail_url(get_the_ID()) ?>" class="img-fluid" /></a>

                            <h4 class="title-beneficio">
                                <a href="<?php echo get_permalink(get_the_ID()) ?>"><?php echo get_the_title(get_the_ID()) ?></a>
                            </h4>

                            <?php if (get_post_meta(get_the_ID(), '_beneficio_discount', true) !== null || get_post_meta(get_the_ID(), '_beneficio_discount', true) !== '') : ?>
                                <span class="discount"><strong><?php echo get_post_meta(get_the_ID(), '_beneficio_discount', true) ?></strong></span>
                            <?php endif; ?>

                            <div class="category-beneficio mt-3">
                                <span class="text-category text-uppercase d-block"><?php echo __('Beneficios Tiempo', 'beneficios') ?></span>
                                <span class="title-category d-block font-italic text-secondary"><strong><?php echo beneficios_front()->show_terms_name_by_post(get_the_ID())?></strong></span>
                            </div>

                            <div class="text mt-3 mb-3">
                           
                            <?php if(!beneficios_front()->get_beneficio_by_user(wp_get_current_user()->ID,get_the_ID())): ?>
                                <?php if(get_post_meta(get_the_ID(),'_beneficio_date',true)):?>
                                        <div id="fechas">
                                            <?php foreach(get_post_meta(get_the_ID(),'_beneficio_date',true) as $key => $val):?>
                                                <label><input type="radio" data-button="#solicitar-<?php echo get_the_ID()?>" <?php echo $check?> class="select-dates" name="gender" value="<?php echo date('Y-m-d H:i:s',strtotime($val));?>"> <?php echo date_i18n('d M H:i',  strtotime($val));?>hs</label><br />
                                            <?php endforeach;?>
                                        </div>
                                    <?php endif;?> 
                            <?php else: ?>
                                <p>Fecha elegida <?php echo beneficios_front()->get_beneficio_data(wp_get_current_user()->ID,get_the_ID())->{'date_hour'}?></p>
                            <?php endif?>       
                                <button type="button" 
                                <?php if(get_post_meta(get_the_ID(),'_beneficio_date',true)) { echo 'disabled'; } ?> 
                                class="solicitar btn <?php echo beneficios_front()->get_beneficio_by_user(wp_get_current_user()->ID,get_the_ID()) ? 'btn-warning' : 'btn-primary' ?>" 
                                data-id="<?php echo get_the_ID() ?>" 
                                data-user="<?php echo wp_get_current_user()->ID?>"
                                data-date=""
                                id="solicitar-<?php echo get_the_ID()?>"
                                >
                                    <?php echo beneficios_front()->get_beneficio_by_user(wp_get_current_user()->ID,get_the_ID()) ? __('Solicitado', 'beneficios') : __('Solicitar', 'beneficios') ?>
                                </button>

                                <div id="dni-<?php echo get_the_ID()?>" class="dni-field" style="display: none;">
                                <?php echo __('Agrega tu DNI para solicitar el beneficio','beneficios')?><br />
                                    <p>
                                    <input type="number" name="dni-number" 
                                    id="dni-number-<?php echo get_the_ID()?>"
                                    data-id="<?php echo get_the_ID() ?>" 
                                    data-user="<?php echo wp_get_current_user()->ID?>"
                                    data-date=""
                                    value=""
                                    class="form-control"
                                      />
                                    </p>
                                    <button type="button" data-id="#dni-number-<?php echo get_the_ID()?>" class="dni-button btn btn-primary">Solicitar</button>
                                </div>

                                <button type="button" class="menos btn btn-text" data-content="#content<?php echo get_the_ID() ?>" data-toggle="collapse" data-target="#content<?php echo get_the_ID() ?>" aria-expanded="false" aria-controls="content<?php echo get_the_ID()?>"><?php echo __('ver más', 'beneficios') ?></button>

                                <div class="content benefit-description mt-4 collapse mt-3" id="content<?php echo get_the_ID() ?>">
                                    <?php echo wp_trim_words(get_the_content(get_the_ID()), 55, null) ?>
                                </div>
                            </div>
                        </div>
 
                    <?php endwhile;?>
                    <div class="col-12 pagination">
                       <button type="button" class="btn btn-block btn-text"><?php next_posts_link( __( 'ver más', 'beneficios' ), $beneficios->max_num_pages ); ?></button>         
                    </div>
                    
                    <?php endif;?>
                </div>
                <div id="main" class="d-block w-100"></div>
            </div>
    </div>
</div>
<?php do_action('beneficios_loop_footer')?>
<?php get_footer() ?>