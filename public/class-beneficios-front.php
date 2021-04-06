<?php

class Beneficios_Front
{
    private $nonce = 'beneficios-nonce';
    private $action = 'beneficios-ajax-action';

    private $url;
    public function __construct()
    {
        $this->url = admin_url('admin-ajax.php');

        add_action('wp_enqueue_scripts', [$this, 'beneficios_ajax_script']);

        add_action('wp_ajax_nopriv_' . $this->action, [$this, 'add_beneficio_ajax']);
        add_action('wp_ajax_' . $this->action, [$this, 'add_beneficio_ajax']);
    }
    /**
     * Ajax script
     */
    public function beneficios_ajax_script($extra = '')
    {
        wp_enqueue_script('beneficios_ajax_script', plugin_dir_url(__FILE__) . 'js/beneficios-ajax.js', array('jquery'), '1.0', true);
        $this->add_beneficio_vars();


        if (has_filter('beneficios_ajax_ext')) {
            apply_filters('beneficios_ajax_ext', $extra);
        }
    }

    public function beneficios_localize_script($var_data, $data)
    {
        $fields = [
            'url'    => $this->url,
            '_ajax_nonce'  => wp_create_nonce($this->nonce),
            'action' => $this->action
        ];

        $fields = array_merge($fields, $data);

        wp_localize_script('beneficios_ajax_script', $var_data, $fields);
    }

    public function show_terms()
    {
        return get_terms([
            'taxonomy' => 'cat_beneficios',
            'hide_empty' => 'true'
        ]);
    }

    public function show_terms_name_by_post($post_id)
    {
        $terms = wp_get_post_terms($post_id, 'cat_beneficios');
        foreach($terms as $term)
        {
            $category[] = $term->name;
        }
        return $category[0];
    }

    public function show_terms_slug_by_post($post_id)
    {
        $terms = wp_get_post_terms($post_id, 'cat_beneficios',);
        foreach($terms as $term)
        {
            $category[] = $term->slug;
        }
        return $category[0];
    }

    public function show_posts_by_term($id_term)
    {
        $args = [
            'post_type' => 'beneficios',
            'numberposts' => 12,
            'tax_query' => [
                [
                    'taxonomy' => 'cat_beneficios',
                    'field' => 'id',
                    'terms' => $id_term
                ]
            ],
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

        $query = get_posts($args);

        return $query;
    }

    public function show_posts_all()
    {
        $args = [
            'post_type' => 'beneficios',
            'numberposts' => 12,
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

        $query = get_posts($args);

        return $query;
    }

    public function get_beneficio_by_user($user_id)
    {
        global $wpdb;
        $get_beneficio = $wpdb->get_results(
            $wpdb->prepare('SELECT id_beneficio FROM '.$wpdb->prefix.'beneficios WHERE id_user=%d',$user_id),ARRAY_N
        );
        return $get_beneficio[0][0];
    }
    /**
     * ajax
     */
    public function add_beneficio_vars()
    {
        $add_beneficio = isset($_POST['add_beneficio']) ? $_POST['add_beneficio'] : '';
        $bene_id = isset($_POST['bene_id']) ? $_POST['bene_id'] : '';
        $user = isset($_POST['user']) ? $_POST['user'] : '';

        $fields = [
            'add_beneficio' => $add_beneficio,
            'bene_id' => $bene_id,
            'user' => $user
        ];

        return $this->beneficios_localize_script('ajax_add_beneficio', $fields);
    }

    public function add_beneficio_ajax()
    {
        if(isset($_POST['add_beneficio'])) {
            global $wpdb;

            $nonce = sanitize_text_field($_POST['_ajax_nonce']);

            if (!wp_verify_nonce($nonce, $this->nonce)) {
                echo wp_send_json_error( __('No se pudo agregar el beneficio','beneficios') );
                wp_die();
            }

            if (!isset($_POST['user']) || $_POST['user'] === '0') {
                echo wp_send_json_error(__('Inicia sesión para agregar este beneficio.', 'beneficios'));
                wp_die();
            }
        
            if(!isset($_POST['bene_id'])) {
                echo wp_send_json_error(__('Este no es un beneficio.', 'beneficios'));
                wp_die();
            }

            $get_beneficio = $wpdb->get_results(
                $wpdb->prepare('SELECT id_user FROM '.$wpdb->prefix.'beneficios WHERE id_user=%d AND id_beneficio=%d',[$_POST['user'],$_POST['bene_id']])
            );
            if(count($get_beneficio) !== 0){
                echo wp_send_json_error(__('Ya estas incripto en este beneficio.', 'beneficios'));
                wp_die();
            }

            $insert = $wpdb->insert($wpdb->prefix.'beneficios',['id_beneficio' => $_POST['bene_id'], 'id_user' => $_POST['user'], 'taken' => 0]);
            if($insert) {
                echo wp_send_json_success(__('Beneficio Agregado','beneficios'));
               
                wp_die();
            } else {
                echo wp_send_json_error( __('Ocurrio un error','beneficios') );
                wp_die();
            }
        }
    }
}

function beneficios_front()
{
    return new Beneficios_Front();
}

beneficios_front();
