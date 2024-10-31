<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Solar-Energy-Visualizer
 * @subpackage Solar-Energy-Visualizer/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Solar-Energy-Visualizer
 * @subpackage Solar-Energy-Visualizer/includes
 * @author     Christina Neufeld <neufeld_christina@yahoo.com>
 */
class SEVZ_Plugin {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      SEVZ_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $solar_energy_visualizer    The string used to uniquely identify this plugin.
	 */
	protected $solar_energy_visualizer;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'SOLAR_ENERGY_VISUALIZER_VERSION' ) ) {
			$this->version = SOLAR_ENERGY_VISUALIZER_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->solar_energy_visualizer = 'solar_energy_visualizer';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - SEVZ_Loader. Orchestrates the hooks of the plugin.
	 * - SEVZ_i18n. Defines internationalization functionality.
	 * - SEVZ_Admin. Defines all hooks for the admin area.
	 * - SEVZ_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sevz-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sevz-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-sevz-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-sevz-public.php';

		/* add new producer*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/class_sevz_add_producer.php';
		
		/* add new prosumer*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/class_sevz_add_prosumer.php';

		/* add new consumer*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/class_sevz_add_consumer.php';

		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'external/jobmanager.php';

		$this->loader = new SEVZ_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the SEVZ_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new SEVZ_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new SEVZ_Admin( $this->get_solar_energy_visualizer(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action('admin_menu', $plugin_admin, 'anlagen_menu');
		//register general settings
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_custom_settings' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new SEVZ_Public( $this->get_solar_energy_visualizer(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );


		// Shortcode for start-page
		$this->loader->add_shortcode( 'sevz_show_total_pv', $plugin_public, 'sevz_show_total_pv' );

		// Shortcode for the Login-View
		$this->loader->add_shortcode( 'sevz_show_pv', $plugin_public, 'sevz_show_pv' );

		$this->loader->add_shortcode( 'sevz_show_invoice', $plugin_public, 'sevz_show_invoice' );
		$this->loader->add_shortcode( 'sevz_add_credit', $plugin_public, 'sevz_add_credit' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_solar_energy_visualizer() {
		return $this->solar_energy_visualizer;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    SEVZ_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
