<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Solar-Energy-Visualizer
 * @subpackage Solar-Energy-Visualizer/public/partials
 */

//MySQL
global $wpdb;
$producer_data = "producer_data";
$prosumer_data = "prosumer_data";

$produzent = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}{$producer_data}` WHERE 1");
$prosumer = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}{$prosumer_data}` WHERE 1");
$allPV = count($produzent)+count($prosumer);

if(empty($allPV))
	return;
$startpage_pv = "startpage_pv";
$sum_startpage = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}{$startpage_pv}` GROUP BY `count`");
foreach($sum_startpage as $value){
	$sum_production = $value ->production;	
	$countCo2= $value ->co2;
	$tree = $value ->trees;
}

$gesamt_produktion = str_replace(".",",",number_format($sum_production, 2, ',', ''));
$co2 = str_replace(".",",",number_format($countCo2, 2, ',', ''));

$apiKey = get_option('googlekey'); // Google maps now requires an API key.

if($apiKey){
	//add new producer to the map
	$betreiber_adr = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}{$producer_data}` WHERE `flag`= 1");
	foreach($betreiber_adr as $value){
		$plz = $value->PLZ_pv;
		$flag = $value->flag;
		$id = $value->ID;

		$address = $plz.', Germany';


		$json_url= 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false&key='.$apiKey;

		$json= wp_remote_get( $json_url );

		if( is_wp_error( $json ) ) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $json );

		$output = json_decode($body, TRUE);
		if( ! empty( $output ) ) {


			$latitude = $output->results[0]->geometry->location->lat;
			$longitude = $output->results[0]->geometry->location->lng;

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

			$punkt = json_decode('Point('.$latitude.' '.$longitude.')');

			$wpdb->insert($wpdb->base_prefix.'wpgmza', 
						  array(
							  'map_id' => 1,
							  'address' => $address,
							  'description' => '',
							  'pic' => '',
							  'link' => '',
							  'icon' => '',
							  'lat' => $latitude,
							  'lng' => $longitude,
							  'anim' => '1',
							  'title' => '',
							  'infoopen' => '0',
							  'category' => '',
							  'approved' => 1,
							  'retina' => 0,
							  'type' => 0,
							  'did' => '',
							  'sticky' => 0,
							  'other_data' => '',
							  'latlng' => $punkt
						  ));

			$wpdb->update($wpdb->base_prefix.$producer_data, 
						  array(
							  'flag' => 0
						  ),array( 'ID' => $id ));
		}
	}
	//add new prosumer to the map
	$betreiber_adr = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}{$prosumer_data}` WHERE `flag`= 1");
	foreach($betreiber_adr as $value){
		$plz = $value->PLZ_pv;
		$flag = $value->flag;
		$id = $value->ID;

		$address = $plz.', Germany';

		$json_url='https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false&key='.$apiKey;

		$json= wp_remote_get( $json_url );

		if( is_wp_error( $json ) ) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $json );

		$output = json_decode($body, TRUE);
		if( ! empty( $output ) ) {
			$latitude = $output->results[0]->geometry->location->lat;
			$longitude = $output->results[0]->geometry->location->lng;

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

			$punkt = json_decode('Point('.$latitude.' '.$longitude.')');

			$wpdb->insert($wpdb->base_prefix.'wpgmza', 
						  array(
							  'map_id' => 1,
							  'address' => $address,
							  'description' => '',
							  'pic' => '',
							  'link' => '',
							  'icon' => '',
							  'lat' => $latitude,
							  'lng' => $longitude,
							  'anim' => '1',
							  'title' => '',
							  'infoopen' => '0',
							  'category' => '',
							  'approved' => 1,
							  'retina' => 0,
							  'type' => 0,
							  'did' => '',
							  'sticky' => 0,
							  'other_data' => '',
							  'latlng' => $punkt
						  ));

			$wpdb->update($wpdb->base_prefix.$prosumer_data, 
						  array(
							  'flag' => 0
						  ),array( 'ID' => $id ));
		}			
	}
}
$icon_path = plugin_dir_url( dirname( __FILE__ ) ) .'icons/';
?>

<div class="legendPV">

	<p class="row-first">
		<span class="fs-l" style="font-size: 40px;"><?php echo esc_attr($allPV); ?> Anlagen</span>
		<span class="imageAnlage"><img src="<?php echo esc_attr($icon_path.'kisspng-art-photography-clip-art-icon-sun-5b361804556db5.6012688415302717483499.png') ?>">
		</span>
	</p>


	<p class="row-second"><span class="fs-s" style="font-size: 16px;">produzieren 2023 bereits für uns</span>
	</p>

	<p class="row-third"><span class="fs-xl" style="font-size: 60px;"><?php echo esc_attr($gesamt_produktion); ?></span>
		<span class="fs-m">kWh</span></p>
	<div class="lastRow">
		<p class="row-fourth half">
			<span class="fs-xs" style="font-size: 16px;">das entspricht</span><br>
			<span class="fs-l" style="font-size: 40px;"><?php echo esc_attr($co2); ?></span>
			<span class="fs-m">t</span>
			<span class="imageAnlage"><img src="<?php echo esc_attr($icon_path.'kisspng-carbon-dioxide-clip-art-portable-network-graphics-5ba018ffad2554.9931904415372188157092.png') ?>">
			</span><br>
			<span class="fs-xs" style="font-size: 16px;">vermiedenes CO<sub>2</sub></span>
		</p>

		<p class="row-fifth half">
			<span class="fs-xs" style="font-size: 16px;">oder</span><br>
			<span class="fs-l" style="font-size: 40px;"><?php echo esc_attr($tree); ?></span>			
			<span class="imageAnlage3"><img src="<?php echo esc_attr($icon_path.'kisspng-computer-icons-download-symbol-clip-art-tree-icon-5b2086618001d5.8870070815288582095243.png') ?>">
			</span>
			<br>
			<span class="fs-xs" style="font-size: 16px;">zur Bindung dieser CO<sub>2</sub> Menge</span>
		</p>
	</div>
</div>


