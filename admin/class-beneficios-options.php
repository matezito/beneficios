<?php

class Beneficios_Options
{
    public function __construct()
    {
        $this->beneficios_save();
    }

    public function get_pages()
    {
        $args = [
            'post_type' => 'page',
            'status'    => 'publish',
            'numberposts' => -1
        ];
        $pages = get_posts($args);

        return $pages;
    }

    public function beneficios_loop_page_input()
    {
        $page_slug = get_option('beneficios_loop_page');
        $pages = $this->get_pages();

        $select = '<select name="beneficios_loop_page">';
        $select .= '<option value=""> -- select a page -- </option>';
        foreach ($pages as $p) {
            $select .= '<option value="' . $p->ID . '" ' . selected($page_slug, $p->ID, false) . '>' . $p->post_title . '</option>';
        }
        $select .= '</select>';
        return $select;
    }

    public function beneficios_save()
    {
        if(isset($_POST['beneficios_loop_page'])){
            update_option( 'beneficios_loop_page',sanitize_text_field( $_POST['beneficios_loop_page'] ),true );
        }
    }
}

function beneficios_options()
{
    return new Beneficios_Options();
}
beneficios_options();