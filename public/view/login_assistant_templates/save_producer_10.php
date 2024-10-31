<?php	
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

$producer = $wpdb->get_results($wpdb->prepare("SELECT `ID` FROM `{$wpdb->base_prefix}producer_data` WHERE `ID` = %d", $current_id));
foreach($producer as $value){
	$producerID = $value->ID;
}

if(empty($producerID)){

	$wpdb->insert($wpdb->base_prefix.'producer_data', 
				  array(	
					  'ID' => $current_id,
					  'name' => $betreiber_name,
					  'smartmeter' => $smartmeter,
					  'benutzername' => $username,
					  'passwort' => $smartpasswort,
					  'link1' => $link,
					  'imsysid' => $smartname,
					  'strasse_pv' => $strasse_pv,
					  'hausnr_pv' => $hausnr_pv,
					  'PLZ_pv' => $plz_pv,
					  'stadt_pv' => $stadt_pv,
					  'anlagenUeberwachnung' => $tabelleProd,
					  'nettonennleistung' => $nettonennleistung, 				
					  'inbetriebnahmedatum' => $inbetriebnahmedatum,
					  'ausrichtung' => $ausrichtung,
					  'neigung' => $neigung,
					  'flag' => 1
				  ));	
}else{
	$wpdb->update($wpdb->base_prefix.'producer_data', 
				  array(
					  'smartmeter' => $smartmeter,
					  'benutzername' => $username,
					  'passwort' => $smartpasswort,
					  'link1' => $link,
					  'imsysid' => $smartname,
					  'strasse_pv' => $strasse_pv,
					  'hausnr_pv' => $hausnr_pv,
					  'PLZ_pv' => $plz_pv,
					  'stadt_pv' => $stadt_pv,
					  'anlagenUeberwachnung' => $tabelleProd,
					  'nettonennleistung' => $nettonennleistung, 				
					  'inbetriebnahmedatum' => $inbetriebnahmedatum,
					  'ausrichtung' => $ausrichtung,
					  'neigung' => $neigung,
					  'flag' => 1
				  ),array( 'ID' => $current_id ));
}
$wp_user_object = new WP_User($current_id);
$wp_user_object->set_role('producer');

?>
<h4>
	<?php	echo esc_html("Vielen Dank '$betreiber_name' für Ihre Anmeldung! Klicken Sie auf Login, um in Ihren persönlichen Bereich zu gelangen.");
	?>
</h4>