<?php	
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

$prosumer = $wpdb->get_results($wpdb->prepare("SELECT `ID` FROM `{$wpdb->base_prefix}prosumer_data` WHERE `ID` = %d", $current_id));
foreach($prosumer as $value){
	$prosumerID = $value->ID;
}

if(empty($prosumerID)){

	$wpdb->insert($wpdb->base_prefix.'prosumer_data', 
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
					  'strasse_verbrauch' => $strasse_verbrauch,
					  'hausnr_verbrauch' => $hausnr_verbrauch,
					  'PLZ_verbrauch' => $plz_verbrauch,
					  'stadt_verbrauch' => $stadt_verbrauch,
					  'anlagenUeberwachnung' => $tabellePros,
					  'nettonennleistung' => $nettonennleistung, 				
					  'inbetriebnahmedatum' => $inbetriebnahmedatum,
					  'ausrichtung' => $ausrichtung,
					  'neigung' => $neigung
				  ));	
} else{
	$wpdb->update($wpdb->base_prefix.'prosumer_data', 
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
					  'strasse_verbrauch' => $strasse_verbrauch,
					  'hausnr_verbrauch' => $hausnr_verbrauch,
					  'PLZ_verbrauch' => $plz_verbrauch,
					  'stadt_verbrauch' => $stadt_verbrauch,
					  'anlagenUeberwachnung' => $tabellePros,
					  'nettonennleistung' => $nettonennleistung, 				
					  'inbetriebnahmedatum' => $inbetriebnahmedatum,
					  'ausrichtung' => $ausrichtung,
					  'neigung' => $neigung,
					  'flag' => 1
				  ),array( 'ID' => $current_id ));
}

//set new user role
$wp_user_object = new WP_User($current_id);
$wp_user_object->set_role('prosumer');
?>
<h4>
	<?php	echo esc_html("Vielen Dank '$betreiber_name' für Ihre Anmeldung! Klicken Sie auf Login, um in Ihren persönlichen Bereich zu gelangen.");
	?>
</h4>
