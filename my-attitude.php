<?php
/*
Plugin Name: my-attitude
Plugin URI: https://github.com/zurazurataicho/wp-plugin-my-attitude
Description: My Attitude Theme Customization For WordPress.
Version: 1.0.0
Author: EZURA, Atsushi
Author URI:
License: GPLv2
*/
define('MY_PLUGIN_PATH', 'my-attitude/my-attitude.php');

if (!class_exists('MyAttitudePlugin')) {
	class MyAttitudePlugin {
		private static $instance;

		public static function get_instance() {
			if (!isset(self::$instance)) {
				self::$instance = new MyAttitudePlugin();
			}
			return self::$instance;
		}

		private function __construct() {}

		public function init() {
			add_action('activated_plugin', array($this, 'load_at_last'));

			add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
			//add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

			add_action('wp_head', array($this, 'add_favicon'));
			add_action('init', array($this, 'replace_footer'));
		}

		public function load_at_last() {
			$my_plugin = MY_PLUGIN_PATH;
			$target_active_plugins = get_option('active_plugins');
			if (!in_array($my_plugin, $target_active_plugins)) {
				return;
			}
			$active_plugins = array_filter($target_active_plugins, function($val) use ($my_plugin) {
					return $val !== $my_plugin;
				});
			array_push($active_plugins, $my_plugin);
			update_option('active_plugins', $active_plugins);
		}

		public function enqueue_styles() {
			wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
		}

		public function enqueue_scripts() {
		}

		public function add_favicon() {
			echo '<link rel="shortcut icon" href="' . get_stylesheet_directory_uri() . '/favicon.ico"/>';
		}

		public function replace_footer() {
			remove_action('attitude_footer', 'attitude_footer_info', 30);
			add_action('attitude_footer', array($this, 'alternative_footer_info'), 20);
		}

		public function alternative_footer_info() {
			echo '<div class="copyright">Copyright &copy; 2015-' . attitude_the_year() . ' Atsushi Ezura</div><!-- .copyright -->';
		}
	}
	$instance = MyAttitudePlugin::get_instance();
	$instance->init();
}
