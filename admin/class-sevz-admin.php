<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Solar-Energy-Visualizer
 * @subpackage Solar-Energy-Visualizer/admin
 * @author     Christina Neufeld <neufeld_christina@yahoo.com>
 */
class SEVZ_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $solar_energy_visualizer    The ID of this plugin.
	 */
	private $solar_energy_visualizer;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $solar_energy_visualizer       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $solar_energy_visualizer, $version ) {

		$this->solar_energy_visualizer = $solar_energy_visualizer;
		$this->version = $version;

	}


	/**
	 * Add custom menu
	 *
	 * @since    1.0.0
	 */
	function anlagen_menu() {
		add_menu_page(
			'Solar-Energy-Visualizer',
			'Solar-Energy-Visualizer',
			NULL,
			'solar_energy_visualizer',
			'',
			'dashicons-groups'
		);

		add_submenu_page(
			'solar_energy_visualizer',
			__( 'Einstellungen', 'solar_energy_visualizer' ),
			__( 'Einstellungen', 'solar_energy_visualizer' ),
			'manage_options',
			'myplugin_admin_page',
			array($this, 'myplugin_admin_page'),
		);

		add_submenu_page(
			'solar_energy_visualizer',
			__( 'Produzenten', 'solar_energy_visualizer' ),
			__( 'Produzenten', 'solar_energy_visualizer' ),
			'manage_options',
			'my-add-submenu-page',
			'SEVZ_Add_producer::my_add_submenu_page_content',
		);
		add_submenu_page(
			'solar_energy_visualizer',
			__( 'Stromabnehmer', 'solar_energy_visualizer' ),
			__( 'Stromabnehmer', 'solar_energy_visualizer' ),
			'manage_options',
			'my_customer_submenu_page',
			'SEVZ_Add_consumer::my_consumer_submenu_page_content',
		);
		add_submenu_page(
			'solar_energy_visualizer',
			__( 'Prosumer', 'solar_energy_visualizer' ),
			__( 'Prosumer', 'solar_energy_visualizer' ),
			'manage_options',
			'my_prosumer_submenu_page',
			'SEVZ_Add_prosumer::my_add_prosumer_submenu_page_content',
		);
		add_submenu_page(
			'solar_energy_visualizer',
			__( 'Dokumentation', 'solar_energy_visualizer' ),
			__( 'Dokumentation', 'solar_energy_visualizer' ),
			'manage_options',
			'my-doku-submenu-page',
			array($this, 'my_doku_submenu_page_content'),
		);
	}


	//view doku
	function my_doku_submenu_page_content() {
		require_once 'partials/documentation.php';
	}


	// return view admin settings
	public function myplugin_admin_page(){
		//return views
		require_once 'partials/sevz-admin-display.php';

	}


	/**
	 * Register custom fields for plugin settings.
	 *
	 * @since    1.0.0
	 */
	public function register_custom_settings(){
		//registers all settings for general settings page
		register_setting( 'customsettings', 'weatherapiVisual' );
		register_setting( 'customsettings', 'weatherkeyForecast' );
		register_setting( 'customsettings', 'weatherPLZ' );
		register_setting( 'customsettings', 'weatherapiUnderground' );
		register_setting( 'customsettings', 'weatherstationid' );
		register_setting( 'customsettings', 'googlekey' );
		register_setting( 'customsettings', 'selectpoweropti' );
		register_setting( 'customsettings', 'selectimsys' );

		register_setting( 'customsettings', 'imsyspath' );

		register_setting( 'customsettings', 'imsysidstringcons' );
		register_setting( 'customsettings', 'imsysidfirstcons' );
		register_setting( 'customsettings', 'imsysidlastcons' );
		register_setting( 'customsettings', 'imsysqtystringcons' );
		register_setting( 'customsettings', 'imsysqtyfirstcons' );
		register_setting( 'customsettings', 'imsysqtylastcons' );
		register_setting( 'customsettings', 'imsysqtyfirstdate' );
		register_setting( 'customsettings', 'imsysqtylastdate' );

		register_setting( 'customsettings', 'menucolorback' );
		register_setting( 'customsettings', 'menucolorover' );
		register_setting( 'customsettings', 'buttoncolor' );
		register_setting( 'customsettings', 'chartfeedin' );
		register_setting( 'customsettings', 'chartconsumption' );
	}


	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in PV_Anlagen_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The PV_Anlagen_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->solar_energy_visualizer, plugin_dir_url( __FILE__ ) . 'css/sevz-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in SEVZ_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The SEVZ_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->solar_energy_visualizer, plugin_dir_url( __FILE__ ) . 'js/sevz-admin.js', array( 'jquery' ), $this->version, false );

	}
}
