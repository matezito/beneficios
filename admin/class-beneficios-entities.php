<?php

class Beneficios_Entities
{

    public function __construct()
    {
        add_action('init', [$this, 'beneficios_post_type']);
        add_action('init', [$this,'cat_beneficios'] );

        // add_action('admin_menu', [$this, 'beneficios_menu']);
        // add_action('admin_menu', [$this, 'beneficios_type_menu']);
       add_action('admin_menu', [$this, 'beneficios_cat_menu']);
        
    }
    /**
     * Menu
     */
    public function beneficios_menu()
    {
        add_menu_page(
            __('Beneficios', 'suscriptions'),
            __('Beneficios', 'suscriptions'),
            'manage_options',
            'beneficios_menu_main',
            [$this, 'beneficios_callback'],
            'dashicons-tickets',
            30
        );
    }
    /**
     * panel
     */
    public function beneficios_callback()
    {
        echo 'hola';
    }
    /**
     * Submenu
     */
    public function beneficios_type_menu()
    {
        add_submenu_page(
            'beneficios_menu_main',
            __('Lista', 'beneficios'),
            __('Lista Beneficios', 'beneficios'),
            'edit_posts',
            'edit.php?post_type=beneficios'
        );
    }

    public function beneficios_cat_menu()
    {
        add_submenu_page(
            'edit.php?post_type=beneficios',
            __('Opciones', 'beneficios'),
            __('Opciones', 'beneficios'),
            'manage_options',
            'tutorial_subpage_example',
            [$this,'opciones']
        );
    }
    public function opciones()
    {
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/beneficios-admin-display.php';
    }
    public function beneficios_post_type()
    {
        /**
         * Post Type: Beneficios.
         */

        $labels = [
            'name' => __('Beneficios', 'beneficios'),
            'singular_name' => __('Beneficio', 'beneficios'),
        ];

        $args = [
            'label' => __('Beneficios', 'beneficios'),
            'labels' => $labels,
            'description' => '',
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_rest' => true,
            'rest_base' => '',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
            'has_archive' => false,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'delete_with_user' => false,
            'exclude_from_search' => true,
            'capability_type' => 'post',
            'map_meta_cap' => true,
            'hierarchical' => false,
            'rewrite' => ['slug' => 'beneficios', 'with_front' => true],
            'query_var' => true,
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields']
        ];

        register_post_type('beneficios', $args);
    }

    public function cat_beneficios() {

        /**
         * Taxonomy: Categorías Beneficios.
         */
    
        $labels = [
            'name' => __( 'Categorías Beneficios', 'beneficios' ),
            'singular_name' => __( 'Categoría Beneficio', 'beneficios' ),
        ];
    
        $args = [
            'label' => __( 'Categorías Beneficios', 'beneficios' ),
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'hierarchical' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' =>true,
            'query_var' => true,
            'rewrite' => [ 'slug' => 'categoria-beneficios', 'with_front' => true, ],
            'show_admin_column' => false,
            'show_in_rest' => true,
            'rest_base' => 'cat_beneficios',
            'rest_controller_class' => 'WP_REST_Terms_Controller',
            'show_in_quick_edit' => false,
                ];
        register_taxonomy( 'cat_beneficios', [ 'beneficios' ], $args );
    }
    
    
}

function benenficios()
{
    return new Beneficios_Entities();
}

benenficios();
