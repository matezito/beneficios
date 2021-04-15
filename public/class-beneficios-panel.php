<?php

class Beneficios_Panel
{
    public function __construct()
    {
    }

    public function show_user_beneficios($user_id)
    {
        global $wpdb;
        $get_beneficios = $wpdb->get_results('SELECT id_beneficio FROM ' . $wpdb->prefix . 'beneficios WHERE id_user=' . $user_id);

        return $get_beneficios;
    }

    public function show_taken_beneficios($user_id)
    {
        global $wpdb;
        $get_beneficios = $wpdb->get_results('SELECT id_beneficio FROM ' . $wpdb->prefix . 'beneficios WHERE id_user=' . $user_id . ' AND taken=1');

        return $get_beneficios;
    }

    public function delete_beneficio($post_id)
    {
        global $wpdb;
        $delete = $wpdb->delete($wpdb->prefix . 'beneficios',['ID' => $post_id],['%d']);
        return $delete;
    }
}

function beneficios_panel()
{
    return new Beneficios_Panel();
}

beneficios_panel();
