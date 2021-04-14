<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://genosha.com.ar
 * @since      1.0.0
 *
 * @package    Beneficios
 * @subpackage Beneficios/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Beneficios
 * @subpackage Beneficios/includes
 * @author     Juan Iriart <juan.e@genosha.com.ar>
 */
class Beneficios_Deactivator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate()
	{
		self::delete_some_options();
		self::remove_tables();
		self::remove_email();
	}

	public static function remove_tables()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . 'beneficios';
		$sql = 'DROP TABLE IF EXISTS ' . $table_name;
		$wpdb->query($sql);
	}

	public static function delete_some_options()
	{
		if (get_option('beneficios_loop_page')) {
			wp_delete_post(get_option('beneficios_loop_page'));
			delete_option('beneficios_loop_page');
		}
	}

	public static function remove_email()
	{
		delete_option('subject_automatico');
		delete_option('mail_automatico');
		delete_option('subject_sorteo');
		delete_option('mail_sorteo');
	}
}
