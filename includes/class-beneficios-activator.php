<?php

/**
 * Fired during plugin activation
 *
 * @link       https://genosha.com.ar
 * @since      1.0.0
 *
 * @package    Beneficios
 * @subpackage Beneficios/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Beneficios
 * @subpackage Beneficios/includes
 * @author     Juan Iriart <juan.e@genosha.com.ar>
 */
class Beneficios_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
		self::create_default_pages();
		add_action('init', [self::class, 'flush']);

		self::create_beneficios_table();
		self::create_email_options();
	}
	public static function flush()
	{
		flush_rewrite_rules();
	}

	public static function create_beneficios_table()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . 'beneficios';
		$charset_collate = $wpdb->get_charset_collate();
		$sql = 'CREATE TABLE IF NOT EXISTS ' . $table_name . ' ( `ID` INT NOT NULL AUTO_INCREMENT , `id_beneficio` INT NOT NULL , `date_hour` DATETIME NULL, `id_user` INT NOT NULL , `taken` INT NULL DEFAULT \'0\' , `taken_date` DATE NULL , PRIMARY KEY (`ID`)) ' . $charset_collate;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

	public static function create_email_options()
	{
		update_option('subject_automatico','Hola {{first_name}} ya puede usar tu beneficio:  {{beneficio_name}}',true);
		update_option('mail_automatico','Hola {{first_name}} ya puede usar tu beneficio:  {{beneficio_name}}',true);

		update_option('subject_sorteo','Hola {{first_name}}, beneficio {{beneficio_name}}',true);
		update_option('mail_sorteo','Hola {{first_name}} ya estas inscripto para el sorteo del beneficio {{beneficio_name}}',true);
	}

	public static function page_exists($page_slug)
	{
		global $wpdb;
		$post_title = wp_unslash(sanitize_post_field('post_name', $page_slug, 0, 'db'));

		$query = "SELECT ID FROM $wpdb->posts WHERE 1=1";
		$args  = array();

		if (!empty($page_slug)) {
			$query .= ' AND post_name = %s';
			$args[] = $post_title;
		}

		if (!empty($args)) {
			return (int) $wpdb->get_var($wpdb->prepare($query, $args));
		}

		return 0;
	}
	public static function create_default_pages()
	{
		if (self::page_exists(get_option('beneficios_loop_page', 'beneficios')) === 0) {
			$page = self::create_beneficios_loop();
			update_option('beneficios_loop_page', $page);
		}
	}

	public static function create_beneficios_loop()
	{
		$args = [
			'post_title' => __('Beneficios', 'beneficios'),
			'post_status'   => 'publish',
			'post_type'     => 'page',
			'post_content'  => 'This page is for the subscription template, please modify the content in your-theme/beneficios/beneficios-loop.php',
			'post_author'   => 1,
		];

		$page = wp_insert_post($args);
		return $page;
	}
}
