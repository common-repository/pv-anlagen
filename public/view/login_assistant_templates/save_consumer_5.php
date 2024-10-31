<?php	

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
$link = str_replace('@', '%40', $username).':'.$smartpasswort;

$verbraucher = $wpdb->get_results($wpdb->prepare("SELECT `ID` FROM `{$wpdb->base_prefix}consumer_data` WHERE `ID` = %d", $current_id));
foreach($verbraucher as $value){
	$verbraucherID = $value->ID;
}

if(empty($verbraucherID)){

	$wpdb->insert($wpdb->base_prefix.'consumer_data', 
				  array(	
					  'ID' => $current_id,
					  'name' => $betreiber_name,
					  'smartmeter' => $smartmeter,
					  'benutzername' => $username,
					  'passwort' => $smartpasswort,
					  'link1' => $link,
					  'imsysid' => $smartname,
					  'strasse_verbrauch' => $strasse_verbrauch,
					  'hausnr_verbrauch' => $hausnr_verbrauch,
					  'PLZ_verbrauch' => $plz_verbrauch,
					  'stadt_verbrauch' => $stadt_verbrauch,
					  'anlagenUeberwachnung' => $tabelleCons
				  ));
} else{
	$wpdb->update($wpdb->base_prefix.'consumer_data', 
				  array(
					  'smartmeter' => $smartmeter,
					  'benutzername' => $username,
					  'passwort' => $smartpasswort,
					  'link1' => $link,
					  'imsysid' => $smartname,
					  'strasse_verbrauch' => $strasse_verbrauch,
					  'hausnr_verbrauch' => $hausnr_verbrauch,
					  'PLZ_verbrauch' => $plz_verbrauch,
					  'stadt_verbrauch' => $stadt_verbrauch,
					  'anlagenUeberwachnung' => $tabelleCons
				  ),array( 'ID' => $current_id ));
}
//set new user role
$wp_user_object = new WP_User($current_id);
$wp_user_object->set_role('consumer');
?>
<h4>
	<?php	echo esc_html("Vielen Dank '$betreiber_name' für Ihre Anmeldung! Klicken Sie auf Login, um in Ihren persönlichen Bereich zu gelangen.");
	?>
</h4>