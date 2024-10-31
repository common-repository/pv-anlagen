<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Solar-Energy-Visualizer
 * @subpackage Solar-Energy-Visualizer/public
 * @author     Christina Neufeld <neufeld_christina@yahoo.com>
 */
class SEVZ_Public {

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
	 * @param      string    $solar_energy_visualizer       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $solar_energy_visualizer, $version ) {

		$this->solar_energy_visualizer = $solar_energy_visualizer;
		$this->version = $version;

	}
	public function sevz_show_total_pv() {
		require_once 'partials/sevz-public-display.php';
	}

	function sevz_show_invoice() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/invoice.php';
		sevz_add_invoice();
	}
	function sevz_add_credit() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/credit.php';
		sevz_add_credit();
	}

	public function sevz_show_pv() {

?>

<style>
	nav label {
		background: <?php echo esc_attr(get_option('menucolorback'));?>;
	}
	nav label:hover { 
		background: <?php echo esc_attr(get_option('menucolorover'));?>;
	}
	button.button.button_for {
		background-color: <?php echo esc_attr(get_option('buttoncolor'));?>;
	}
	summary::after {
		color: <?php echo esc_attr(get_option('buttoncolor'));?>;
	}

</style>
<?php
									$user= wp_get_current_user();
									$user_id = $user->ID;


									//MySQL
									global $wpdb;

									if(empty($user))
										return;

									//Anzeige für Abonnent
									if(current_user_can('subscriber')){
										require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/view/class_sevz_login_assistant.php';		
										$obj = new SEVZ_Add_user($count);
									}

									//Anzeige für Administartor
									if( current_user_can('administrator')) { 

									}

									//Anzeige für Prosumer
									if( current_user_can('prosumer')) { 
										$prosumer_table = "prosumer";
										$table_name = $wpdb->base_prefix.$prosumer_table.'_data';
										$prosumer_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM `$table_name` WHERE %d = `ID`", $user_id));
										$image = $wpdb->get_results($wpdb->prepare("SELECT `image_url` FROM `$table_name` WHERE %d = `ID`", $user_id));
										require_once 'partials/sevz-template-view.php';
										sevz_add_view($prosumer_table, $prosumer_data, $image);
									}

									//Anzeige für Stromlieferanten
									if( current_user_can('producer')) { 
										$producer_table = "producer";
										$table_name = $wpdb->base_prefix.$producer_table.'_data';
										$producer_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM `$table_name` WHERE %d = `ID`", $user_id));
										$image = $wpdb->get_results($wpdb->prepare("SELECT `image_url` FROM `$table_name` WHERE %d = `ID`", $user_id));
										require_once 'partials/sevz-template-view.php';
										sevz_add_view($producer_table, $producer_data, $image);
									}


									//Daten Stromabnehmer
									if( current_user_can('consumer')) { 
										$consumer_table = "consumer";
										$table_name = $wpdb->base_prefix.$consumer_table.'_data';
										$consumer_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM `$table_name` WHERE %d = `ID`", $user_id));
										$image = "";
										require_once 'partials/sevz-template-view.php';
										sevz_add_view($consumer_table, $consumer_data, $image);
									}
								   }



	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

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

		wp_enqueue_style( $this->solar_energy_visualizer, plugin_dir_url( __FILE__ ) . 'css/sevz-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->solar_energy_visualizer, plugin_dir_url( __FILE__ ) . 'js/sevz-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'chart-js', plugin_dir_url( __FILE__ ) . 'js/chart.js', array( 'jquery' ), $this->version, false );
	}
}
