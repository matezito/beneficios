<?php

class Beneficios_Options
{

    public $sender;
    public $home_url;

    public function __construct()
    {

        $this->sender = get_option('beneficios_email_sender', get_bloginfo('admin_email')); //If options no exists, use admin email.
        $this->home_url = get_home_url();


        $this->beneficios_save();
        $this->emails_save();
        

        add_filter( 'wp_mail_content_type',[$this,'set_content_type'] );
    }
    /**
     * emails
     */
    public function set_content_type()
    {
        return "text/html";
    }

    public function email_headers()
    {
        $headers = [
            'From: ' . get_bloginfo('name') . ' <' . $this->sender . '>',
            'Content-Type: text/html; charset=UTF-8'
        ];

        if (has_filter('subscriptions_email_headers'))
            $headers = apply_filters('subscriptions_email_headers', $headers);

        return $headers;
    }

    /**
     * Email body
     */
    public function smart_tags($content, $values = [])
    {
        $tags = [
            '{{site_name}}',
            '{{home_url}}',
            '{{email}}',
            '{{first_name}}',
            '{{last_name}}',
            '{{username}}',
            '{{beneficio_name}}',
        ];

        $default_values = [
            'site_name' => get_bloginfo('name'),
            'home_url' => $this->home_url,
            'email' => '',
            'first_name' => '',
            'last_name' => '',
            'username' => '',
            'beneficio_name' => '',
        ];
        $values = wp_parse_args($values, $default_values);

        /**
         * User data
         */
        if (!empty($values['email'])) {
            $user = get_user_by('email', $values['email']);
            $user_data = [
                'user_email' => $user->user_email,
                'first_name' => $user->user_firstname,
                'last_name' => $user->user_lastname,
                'username' => $user->user_login
            ];

            $values = array_merge($values, $user_data);
            $user_tags = array_keys($user_data);
            array_walk(
                $user_tags,
                function (&$value) {
                    $value = '{{' . trim($value, '{}') . '}}';
                }
            );
            $tags = array_merge($tags, $user_tags);
        }

        /**
         * Content
         */
        $content = str_replace($tags, array_values($values), $content);
        return $content;
    }

    public function email_beneficio($subject,$content,$email,$beneficio="")
    {
        $values = [
            'email' => $email,
            'beneficio_name' => $beneficio
        ];

        $subject = $subject;
        $content = $content;

        $subject = $this->smart_tags($subject, $values);
        $content = $this->smart_tags($content, $values);

        $headers = $this->email_headers();
        return wp_mail($email, $subject, $content, $headers);
    }
    /**
     * config
     */
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

    public function emails_save()
    {
        if(isset($_POST['subject_automatico'])){
            update_option('subject_automatico',$_POST['subject_automatico'],true);
        }
        if(isset($_POST['mail_automatico'])){
            update_option('mail_automatico',$_POST['mail_automatico'],true);
        }
        if(isset($_POST['subject_sorteo'])){
            update_option('subject_sorteo',$_POST['subject_sorteo'],true);
        }
        if(isset($_POST['mail_sorteo'])){
            update_option('mail_sorteo',$_POST['mail_sorteo'],true);
        }
    }
}

function beneficios_options()
{
    return new Beneficios_Options();
}
beneficios_options();