<?php get_header() ?>
<div class="container">
    <div class="row">
        <?php
        while (have_posts()) : the_post();
        ?>
            <div class="col-md-10 col-12 mx-auto beneficio-single mt-5">
                <?php the_title('<h2>', '</h2>') ?>
                <p class="excerpt"><?php echo get_the_excerpt(get_the_ID()) ?></p>
                <div class="row">
                    <div class="col-md-6 fecha text-md-left text-center">
                        <?php the_date() ?>
                    </div>
                    <div class="col-md-6 redes text-md-right text-center">
                        redes
                    </div>
                </div>
            </div>
            <div class="col-12 img-principal mb-5">
                <img src="<?php echo get_the_post_thumbnail_url(get_the_ID()) ?>" class="img-fluid" />
            </div>
            <div class="col-md-10 col-12 mx-auto">
                <div class="row">
                    <div class="col-md-4 text-md-left text-center">
                        <span class="text-category text-uppercase d-block"><?php echo __('Beneficios Tiempo', 'beneficios') ?></span>
                        <span class="title-category d-block font-italic text-secondary"><strong><?php
                                                                                                $terms = wp_get_post_terms(get_the_ID(), 'cat_beneficios');
                                                                                                foreach ($terms as $term) {
                                                                                                    echo $term->name . ' ';
                                                                                                }
                                                                                                ?></strong></span>
                    </div>
                    <div class="col-md-4 text-md-right text-center">
                        autor foto
                    </div>
                    <div class="col-12 content mt-5 mb-5">
                        <?php the_content() ?>
                    </div>
                    <?php if (!beneficios_front()->get_beneficio_by_user(wp_get_current_user()->ID, get_the_ID())) : ?>
                        <!-- fechas -->
                        <?php if (!beneficios_front()->get_beneficio_by_user(wp_get_current_user()->ID, get_the_ID())) : ?>
                            <?php if (get_post_meta(get_the_ID(), '_beneficio_date', true)) : ?>
                                <div id="fechas">
                                    <?php foreach (get_post_meta(get_the_ID(), '_beneficio_date', true) as $key => $val) : ?>
                                        <label><input type="radio" data-button="#solicitar-<?php echo get_the_ID() ?>" <?php echo $check ?> class="select-dates" name="gender" value="<?php echo date('Y-m-d H:i:s', strtotime($val)); ?>"> <?php echo date_i18n('d M H:i',  strtotime($val)); ?>hs</label><br />
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        <?php else : ?>
                            <p>Fecha elegida <?php echo beneficios_front()->get_beneficio_data(wp_get_current_user()->ID, get_the_ID())->{'date_hour'} ?></p>
                        <?php endif ?>
                        <!-- fechas -->
                        <div class="col-md-6 mx-auto col-12 text-center pb-5">
                            <button type="button" class="btn btn-block btn-primary solicitar"
                             data-id="<?php echo get_the_ID() ?>"
                              data-user="<?php echo wp_get_current_user()->ID ?>"
                              <?php if(get_post_meta(get_the_ID(),'_beneficio_date',true)) { echo 'disabled'; } ?>
                              id="solicitar-<?php echo get_the_ID()?>"
                              >
                                <?php echo __('Quiero Participar', 'beneficios') ?>
                            </button>
                        </div>
                        <div id="dni-<?php echo get_the_ID() ?>" class="dni-field" style="display: none;">
                            <?php echo __('Agrega tu DNI para solicitar el beneficio', 'beneficios') ?><br />
                            <p>
                                <input type="number" name="dni-number" id="dni-number-<?php echo get_the_ID() ?>" data-id="<?php echo get_the_ID() ?>" data-user="<?php echo wp_get_current_user()->ID ?>" data-date="" value="" class="form-control" />
                            </p>
                            <button type="button" data-id="#dni-number-<?php echo get_the_ID() ?>" class="dni-button btn btn-primary"><?php echo __('Quiero Participar', 'beneficios') ?></button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php
        endwhile;
        ?>
    </div>
</div>

<?php get_footer() ?>