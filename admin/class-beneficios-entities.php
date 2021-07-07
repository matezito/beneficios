<?php

class Beneficios_Entities
{
    private $nonce = 'beneficios-admin-nonce';
    private $action = 'beneficios-admin-ajax-action';

    private $url;

    public function __construct()
    {
        $this->url = admin_url('admin-ajax.php');

        add_action('init', [$this, 'beneficios_post_type']);
        add_action('init', [$this, 'cat_beneficios']);


        $this->url = admin_url('admin-ajax.php');

        add_action('admin_enqueue_scripts', [$this, 'beneficios_admin_script']);

        add_action('admin_menu', [$this, 'beneficios_cat_menu']);

        add_filter('views_edit-beneficios', [$this, 'export_data']);

        add_action('wp_ajax_nopriv_' . $this->action, [$this, 'export_data_ajax']);
        add_action('wp_ajax_' . $this->action, [$this, 'export_data_ajax']);

        add_action('admin_init',[$this,'export_donwload']);

        
    }

    public function beneficios_admin_script()
    {
        wp_enqueue_script('beneficios_ajax_admin_script', plugin_dir_url(__FILE__) . 'js/beneficios-admin.js', array('jquery'), '1.0', true);
        $this->export_vars();
    }

    public function beneficios_localize_admin_script($var_data, $data)
    {
        $fields = [
            'url'    => $this->url,
            '_ajax_nonce'  => wp_create_nonce($this->nonce),
            'action' => $this->action
        ];

        $fields = array_merge($fields, $data);

        wp_localize_script('beneficios_ajax_admin_script', $var_data, $fields);
    }

    public function export_vars()
    {
        $export = isset($_POST['export']) ? $_POST['export'] : '';
        $from = isset($_POST['from']) ? $_POST['from'] : '';
        $to = isset($_POST['to']) ? $_POST['to'] : '';

        $fields = [
            'export' => $export,
            'from' => $from,
            'to' => $to
        ];

        return $this->beneficios_localize_admin_script('ajax_export_admin_beneficio', $fields);
    }

    public function export_data_ajax()
    {
        if (isset($_POST['export'])) {
            $from = explode('-', $_POST['from']);
            $from_year = $from[0];
            $from_month = $from[1];
            $from_day = $from[2];

            $to = explode('-', $_POST['to']);
            $to_year = $to[0];
            $to_month = $to[1];
            $to_day = $to[2];


            $log_filename = plugin_dir_path(dirname(__FILE__)) . "/admin/export";

            if (!file_exists($log_filename)) {
                mkdir($log_filename, 0777, true);
            }
            $name = 'export_' . date('d-M-Y-H-m-i') . '.txt';
            $log_file_data = $log_filename . '/' . $name;
            file_put_contents($log_file_data, 'el mensaje', FILE_APPEND);

            header('Content-Type: application/octet-stream');
            echo plugin_dir_url(__DIR__) . 'export/' . $name;

            wp_die();
        }
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
       return true;
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
            'beneficios_manage_options',
            [$this, 'opciones']
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
            'capability_type' => ['beneficio','beneficios'],
            'map_meta_cap' => true,
            'hierarchical' => false,
            'rewrite' => ['slug' => 'beneficios', 'with_front' => true],
            'query_var' => true,
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields']
        ];

        register_post_type('beneficios', $args);
    }

    public function cat_beneficios()
    {

        /**
         * Taxonomy: Categorías Beneficios.
         */

        $labels = [
            'name' => __('Categorías Beneficios', 'beneficios'),
            'singular_name' => __('Categoría Beneficio', 'beneficios'),
        ];

        $args = [
            'label' => __('Categorías Beneficios', 'beneficios'),
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'hierarchical' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'categoria-beneficios', 'with_front' => true,],
            'show_admin_column' => false,
            'show_in_rest' => true,
            'rest_base' => 'cat_beneficios',
            'rest_controller_class' => 'WP_REST_Terms_Controller',
            'show_in_quick_edit' => false,
            'capabilities' => [
                'manage_terms'  =>   'manage_cat_beneficios',
                'edit_terms'    =>   'edit_cat_beneficios',
                'delete_terms'  =>   'delete_cat_beneficios',
                'assign_terms'  =>   'assign_cat_beneficios',
            ],
        ];
        register_taxonomy('cat_beneficios', ['beneficios'], $args);
    }

    /**
     * Exportar
     */

    public function export_data()
    {
        echo '<div id="export-beneficios">
        <form method="post" id="export-beneficio-form">
            <input type="date" name="from-benenficio" id="from-benenficio" class="export-beneficio-input" value="" />
            <input type="date" name="to-benenficio" id="to-benenficio" class="export-beneficio-input" value="" />
            <button name="export-button-beneficios" type="submit" id="export-beneficios-button" class="button action">Exportar</button>
        </form>
        </div>';
    }

    public function generate_file($name_prefix,$content)
    {
        $log_filename = plugin_dir_path(dirname(__FILE__)) . "/admin/export";

        if (!file_exists($log_filename)) {
            mkdir($log_filename, 0777, true);
        }

        $name = $name_prefix . date('d-M-Y-H-m-i') . '.txt';
        $log_file_data = $log_filename . '/' . $name;
        
        $txt = fopen($log_file_data, "w") or die("Unable to open file!");
        fwrite($txt, $content);
        fclose($txt);

        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename='.basename($log_file_data));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($log_file_data));
        header("Content-Type: text/plain");
        readfile($log_file_data);
        exit();
    }

    public function export_donwload()
    {
        if (isset($_POST['from-benenficio']) && isset($_POST['to-benenficio']) && isset($_POST['export-button-beneficios'])) {
            if(!empty($_POST['from-benenficio']) && !empty($_POST['to-benenficio'])) {

                global $wpdb;

                $args = [
                    'post_type' => 'beneficios',
                    'date_query' => array(
                        array(
                            'after'     => $_POST['from-benenficio'],
                            'before'    => $_POST['to-benenficio'],
                            'inclusive' => true,
                        ),
                    ),
                ];

                $query = get_posts($args);

                $content = '';

               foreach($query as $q) {
         
                   $content .= PHP_EOL.strtoupper($q->{'post_title'}).PHP_EOL;
                   $content .= '-----------------------------' .PHP_EOL;
                   
                   $line = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.'beneficios WHERE id_beneficio = '.$q->{'ID'} );

                   foreach($line as $l) {
                       $user = get_user_by('id',$l->{'id_user'});
                       $content .= $user->first_name. ' ' .$user->last_name. ' DNI: ' .get_user_meta($l->{'id_user'},'_user_dni',true). ' ';
                       if($l->{'date_hour'}){
                           $content .= 'Día y hora: '.$l->{'date_hour'}.PHP_EOL;
                       }
                       $content .= PHP_EOL;
                   }
               }
               
                $this->generate_file('export_',$content);
            } else {
                echo '<div class="notice notice-error is-dismissible">
                            <p>Las fechas no pueden estar vacias.</p>
                        </div>';
            }
        }
    }

    
}

function benenficios()
{
    return new Beneficios_Entities();
}

benenficios();