<?php

class Beneficios_Metabox
{
    public function __construct()
    {

        add_action('cat_beneficios_add_form_fields', [$this, 'cats_form'], 10, 2);
        add_action('cat_beneficios_edit_form_fields', [$this, 'cats_edit_custom_meta_field'], 10, 2);
        add_action('edited_cat_beneficios', [$this, 'cat_save_meta_field'], 10, 2);
        add_action('create_cat_beneficios', [$this, 'cat_save_meta_field'], 10, 2);
        add_filter('manage_cat_beneficios_custom_column', [$this, 'colum_cat_columns'], 10, 3);
        add_filter('manage_edit-cat_beneficios_columns', [$this, 'cat_beneficio_columns']);

        add_action('add_meta_boxes', [$this, 'add_metabox_beneficios']);
        add_action('save_post_beneficios', [$this, 'save_beneficios']);
    }
    /** 
     * Categorías metabox
     */
    public function cats_form()
    {
        echo '<div class="metabox-cat"><label>' . __('¿Activa?', 'beneficios') . ' <input type="checkbox" name="activa" id="activa" /></label></div>';
    }

    public function cats_edit_custom_meta_field($term)
    {

        $t_id = $term->term_id;
        $term_meta = get_option("active_$t_id");

        echo '<tr class="form-field">
		<th scope="row" valign="top"><label for="activa">' . __('¿Activa?', 'beneficios') . '</label></th>
			<td>
                <p><label>' . __('¿Activa?', 'beneficios') . ' <input type="checkbox" ' . checked('1', $term_meta, false) . ' name="activa" value="" /></label></p>
			</td>
		</tr>';
    }

    public function cat_save_meta_field($term_id)
    {
        $t_id = $term_id;
        if (isset($_POST['activa'])) {
            update_option('active_' . $t_id, 1);
        } else {
            update_option('active_' . $t_id, 0);
        }
    }
    /**
     * Columna metabox
     */
    public function cat_beneficio_columns()
    {
        $new_columns = array(
            'cb' => '<input type="checkbox" />',
            'name' => __('Name'),
            'activa' => '¿Activa?',
            'slug' => __('Slug'),
            'posts' => __('Posts')
        );
        return $new_columns;
    }

    public function colum_cat_columns($out, $column_name, $term_id)
    {
        switch ($column_name) {
            case 'activa':
                // get header image url
                $data = get_option("active_$term_id");
                $out .= $data === '1' ? __('Si') : 'No';
                break;

            default:
                break;
        }
        echo $out;
    }
    /**
     * Beneficios metabox
     */
    public function add_metabox_beneficios()
    {
        add_meta_box(
            'beneficios-metabox',
            __('Opciones', 'beneficios'),
            [$this,'form_metabox'],
            ['beneficios'],
            'advanced',
            'default'
        );
    }

    public function form_metabox($post)
    {

        $post_id = $_GET['post'];

        wp_nonce_field('beneficios_nonce_action', 'beneficios_nonce');

        $active = get_post_meta($post_id,'_active',true);
        $type = get_post_meta($post_id, '_beneficio_type', true);
        $finish = get_post_meta($post_id,'_finish',true);
        $feature = get_post_meta($post_id,'_feature',true);
        $dates = get_post_meta($post_id,'_beneficio_date',true);
        $discount = get_post_meta($post_id,'_beneficio_discount',true);

        $form = '<table class="form-table">';
        $form .= '<tr>
            <th scope="row">'.__('¿Beneficio activo?','beneficios').'</th>
            <td><input type="checkbox" name="active" '.checked('1',$active,false).'  /></td>
            <th scope="row">' . __('Tipo', 'beneficios') . '</th>
            <td><select name="type" class="regular-text">
                <option value="">' . __('-seleccionar-', 'beneficios') . '</option>
                <option value="automatico" ' . selected($type, 'automatico', false) . '>' . __('Automatico', 'beneficios') . '</option>
                <option value="sorteo" ' . selected($type, 'sorteo', false) . '>' . __('Por Sorteo', 'beneficios') . '</option>
            </select></td>
            <th scope="row">'.__('Caduca','beneficios').'</th>
            <td><input type="date" name="_finish" class="regular-text" value="'.$finish.'" /></td>
        </tr>';
        $form .= '<tr>
                <th style="vertical-align:top" scope="row">'.__('Destacado','beneficios').'</th>
                <td style="vertical-align:top"><input type="checkbox" name="_feature" '.checked( '1',$feature,false ).' /> </td>
                <th scope="row">'.__('Fechas','beneficios').'</th>
                <td><button type="button" class="components-button is-primary" id="add_fecha">'.__('Agregar fecha y hora','beneficios').'</button><div id="beneficio-fechas">';
                if($dates && count($dates) > 0) {
                    foreach($dates as $key => $value) {
                        $form .= '<div id="_date_'.$key.'"> <input type="datetime-local" class="regular-text date-field" name="_beneficio_date[]" value="'.$value.'" /> <span class="remove-date dashicons dashicons-trash" data-id="#_date_'.$key.'"></span></div>';
                    }
                }
                $form .= '</div>
                </td>
            </tr>';
        $form .= '<tr>
                <th scope="row">'.__('Texto descuento','beneficios').'</th>
                <td><input type="text" class="regular-text" name="_beneficio_discount" value="'.$discount.'" /></td>
        </tr>';
        $form .= '</table>';
        echo $form;
    }

    public function save_beneficios($post_id)
    {
        $nonce_name   = isset($_POST['beneficios_nonce']) ? $_POST['beneficios_nonce'] : '';
        $nonce_action = 'beneficios_nonce_action';

        if (!wp_verify_nonce($nonce_name, $nonce_action)) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (wp_is_post_autosave($post_id)) {
            return;
        }

        if (wp_is_post_revision($post_id)) {
            return;
        }

        $type = $_POST['type'];

        if (isset($type)) {
            update_post_meta($post_id, '_beneficio_type', $type);
        }

        if(isset($_POST['active'])) {
            update_post_meta($post_id,'_active',1);
        } else {
            update_post_meta($post_id,'_active',0);
        }

        if(isset($_POST['_finish'])) {
            update_post_meta($post_id,'_finish',$_POST['_finish']);
        }

        if(isset($_POST['_feature'])) {
            update_post_meta($post_id,'_feature',1);
        } else {
            update_post_meta($post_id,'_feature',0);
        }

        $dates = $_POST['_beneficio_date'];

        if (isset($dates) && count($dates) > 0) {
            for ($i = 0; $i < count($dates); $i++) {
                update_post_meta($post_id, '_beneficio_date', $dates);
            }
        } else {
            delete_post_meta( $post_id, '_beneficio_date' );
        }

        if(isset($_POST['_beneficio_discount'])){
            update_post_meta($post_id,'_beneficio_discount',$_POST['_beneficio_discount']);
        }
    }
}


function beneficios_metabox()
{
    return new Beneficios_Metabox();
}

beneficios_metabox();
