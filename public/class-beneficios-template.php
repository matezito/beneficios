<?php

class Beneficios_Templates
{
    public function __construct()
    {
        add_filter('template_include', [$this, 'beneficios_loop'], 99);
        add_filter('template_include', [$this, 'single_template'], 99);
        add_filter('template_include', [$this, 'taxonomy_template'], 99);   
    }
    /**
     * You must create a folder called "beneficios" into your main theme and copy the php file to override then
     */
    public function suscription_load_template($filename = '')
    {
        if (!empty($filename)) {
            if (locate_template('beneficios/' . $filename)) {
                /**
                 * Folder in theme for beneficios templates, this folder must be created into your theme.
                 */
                $template = locate_template('beneficios/' . $filename);
            } else {
                /**
                 * Default folder of templates
                 */
                $template = dirname(__FILE__) . '/partials/' . $filename;
            }
        }
        return $template;
    }
    public function beneficios_loop($template)
    {
        if (is_page(get_option('beneficios_loop_page')))
            $template = $this->suscription_load_template('pages/beneficios-loop.php');
        return $template;
    }

    public function single_template($template)
    {
        if (is_singular('beneficios'))
            $template = $this->suscription_load_template('pages/beneficios-post.php');
        return $template;
    }

    public function taxonomy_template($template)
    {
        if (is_tax('cat_beneficios'))
            $template = $this->suscription_load_template('pages/taxonomy-cat_beneficios.php');
        return $template;
    }
}

function beneficios_template()
{
    return new Beneficios_Templates();
}

beneficios_template();