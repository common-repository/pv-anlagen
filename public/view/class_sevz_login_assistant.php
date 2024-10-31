<?php

/*
 * login assistant
 * Here you can add new user.
 * 
 * @param		int		$count		count the forms.
 * 
 */


class SEVZ_Add_user{

	public $count;

	public function __construct($count) {

		$this->count = $count;
		global $wpdb;
		$err="";
		$errZaehler= "";


		$user= wp_get_current_user();
		if ( isset($_POST['abnehmer'])) {
			$abnehmer = sanitize_user($_POST['abnehmer']);
		}
		if ( isset($_POST['betreiber'])) {
			$betreiber = sanitize_user($_POST['betreiber']);
		}

		$betreiber_name = $user->user_login;
		$current_id = $user->ID;

		if ( isset($_POST['bezugsanlage'])) {
			$bezugsanlage = sanitize_text_field($_POST['bezugsanlage']);
		}
		if ( isset($_POST['identisch'])) {
			$identisch = sanitize_text_field($_POST['identisch']);
		}
		$tabelleProd = $wpdb->base_prefix.'producer_'.$betreiber_name.'_'.$current_id;
		$tabellePros = $wpdb->base_prefix.'prosumer_'.$betreiber_name.'_'.$current_id;
		$tabelleCons = $wpdb->base_prefix.'consumer_'.$betreiber_name.'_'.$current_id;


		if ( isset($_POST['strasse_verbrauch'])) {
			$strasse_verbrauch = sanitize_text_field($_POST['strasse_verbrauch']);
		}
		if ( isset($_POST['hausnr_verbrauch'])) {
			$hausnr_verbrauch = sanitize_text_field($_POST['hausnr_verbrauch']);
		}
		if ( isset($_POST['PLZ_verbrauch'])) {
			$plz_verbrauch = sanitize_text_field($_POST['PLZ_verbrauch']);
		}
		if ( isset($_POST['stadt_verbrauch'])) {
			$stadt_verbrauch = sanitize_text_field($_POST['stadt_verbrauch']);
		}

		if ( isset($_POST['strasse_pv'])) {
			$strasse_pv= sanitize_text_field($_POST['strasse_pv']); 
		}
		if ( isset($_POST['hausnr_pv'])) {
			$hausnr_pv = sanitize_text_field($_POST['hausnr_pv']);
		}
		if ( isset($_POST['PLZ_pv'])) {
			$plz_pv = sanitize_text_field($_POST['PLZ_pv']);
		}
		if ( isset($_POST['stadt_pv'])) {
			$stadt_pv = sanitize_text_field($_POST['stadt_pv']);
		}

		if ( isset($_POST['smartmeter'])) {
			$smartmeter = sanitize_text_field($_POST['smartmeter']);
		}
		if ( isset($_POST['smartname'])) {
			$smartname = sanitize_text_field($_POST['smartname']);
		}
		if ( isset($_POST['username'])) {
			$username = sanitize_text_field($_POST['username']);
		}
		if ( isset($_POST['passwort'])) {
			$smartpasswort = sanitize_text_field($_POST['passwort']);
		}
		if ( isset($_POST['nettonennleistung'])) {
			$nettonennleistung = sanitize_text_field($_POST['nettonennleistung']);
		}
		if ( isset($_POST['inbetriebnahmedatum'])) {
			$inbetriebnahmedatum = sanitize_text_field($_POST['inbetriebnahmedatum']);
		}
		if ( isset($_POST['ausrichtung'])) {
			$ausrichtung = sanitize_text_field($_POST['ausrichtung']);
		}
		if ( isset($_POST['neigung'])) {
			$neigung = sanitize_text_field($_POST['neigung']);
		}

		//$erriMSys = $wpdb->get_results("SELECT `Zaehler Nr_` FROM `{$wpdb->base_prefix}iMSys` WHERE `Zaehler Nr_` = '$smartname'");
		//$erriMSys = $wpdb->get_results("SELECT `Zaehler Nr_` FROM `{$wpdb->base_prefix}iMSys` WHERE `Zaehler Nr_` = '$smartname'");

		switch ($smartmeter) {
			case "Poweropti":
				$link = str_replace('@', '%40', $username).':'.$smartpasswort;
				break;
			case "imsys":
				//echo get_option('selectimsys');
				//$imsys = 
				break;

			case "CSV":				
				break;
		}	

		//1. Auswalmöglichkeit sich anzumelden
		if(!isset($_POST['button_standort']) && !isset($_POST['button_save_consumer']) && !isset($_POST['button_identic_producer']) && !isset($_POST['button_identic_prosumer']) && !isset($_POST['button_bezugsanlage']) && !isset($_POST['button_back']) && !isset($_POST['button_startseite'])&& !isset($_POST['button_save_prosumer']) && !isset($_POST['button_save_producer'])){
			$this->count = 1;
		}

		switch ($abnehmer) {
			case 'Ja': 
				if($betreiber=="Ja" && !isset($_POST['button_startseite'])){
					$this->count = 4;
				}elseif($betreiber=="Nein" && !isset($_POST['button_startseite'])){
					$this->count = 2;
				}
				break;
			case 'Nein':
				if($betreiber=="Ja" && !isset($_POST['button_startseite'])){
					$this->count = 3;

				}elseif(isset($_POST['button_back'])){
					$this->count = 1;
				}elseif($betreiber=="Nein" && !isset($_POST['button_startseite'])){
					$this->count = 1;
				}
				break;
		}


		switch ($bezugsanlage) {
			case 'Ja': 
				if(!isset($_POST['button_back'])&&((empty($_POST["smartname"]) && empty($_POST["username"])) || empty($_POST["PLZ_pv"]) || empty($_POST["nettonennleistung"]) ||  empty($_POST["neigung"]))) {
					$err = "Bitte tragen Sie die Pflichtfelder ein! ";
					$this->count = 3;

				} elseif(!isset($_POST['button_back'])&& (empty($_POST["smartname"]) && empty($_POST["username"]))){ 
					$errZaehler= "sehr geehrter Nutzer, leider haben Sie kein intelligentes Messsystem der ÜZW verbaut, bitte setzten Sie sich bezüglich eines Einbautermins mit uns unter der 08703 / 92 55 – 190 in Verbindung";
					$this->count = 3;
				} elseif(!isset($_POST['button_back'])){ 
					$this->count = 7;
				}
				break;
			case 'Nein':
				if(!isset($_POST['button_back'])&&((empty($_POST["smartname"]) && empty($_POST["username"])) || empty($_POST["PLZ_pv"]) || empty($_POST["nettonennleistung"]) ||  empty($_POST["neigung"]))) {
					$err = "Bitte tragen Sie die Pflichtfelder ein! ";
					$this->count = 3;

				} elseif(!isset($_POST['button_back'])&& (empty($_POST["smartname"]) && empty($_POST["username"]))){
					$errZaehler= "sehr geehrter Nutzer, leider haben Sie kein intelligentes Messsystem der ÜZW verbaut, bitte setzten Sie sich bezüglich eines Einbautermins mit uns unter der 08703 / 92 55 – 190 in Verbindung";
					$this->count = 3;
				}elseif(!isset($_POST['button_back'])){
					$this->count = 6;
				}
				break;
		}

		if(isset($_POST['button_identic_prosumer'])){
			switch ($identisch) {
				case 'Ja': 
					if((empty($_POST["smartname"]) && empty($_POST["username"])) || empty($_POST["PLZ_pv"]) || empty($_POST["nettonennleistung"]) ||  empty($_POST["neigung"])) {
						$err = "Bitte tragen Sie die Pflichtfelder ein! ";
						$this->count = 4;
					} elseif(!isset($_POST['button_back'])&& (empty($_POST["smartname"]) && empty($_POST["username"]))){ 
						$errZaehler= "sehr geehrter Nutzer, leider haben Sie kein intelligentes Messsystem der ÜZW verbaut, bitte setzten Sie sich bezüglich eines Einbautermins mit uns unter der 08703 / 92 55 – 190 in Verbindung";
						$this->count = 4;
					}else{
						$this->count = 8;
					}
					break;
				case 'Nein':
					if((empty($_POST["smartname"]) && empty($_POST["username"])) || empty($_POST["PLZ_pv"]) || empty($_POST["nettonennleistung"]) ||  empty($_POST["neigung"])) {
						$err = "Bitte tragen Sie die Pflichtfelder ein! ";
						$this->count = 4;
					} elseif(!isset($_POST['button_back'])&& (empty($_POST["smartname"]) && empty($_POST["username"]))){ 
						$errZaehler= "sehr geehrter Nutzer, leider haben Sie kein intelligentes Messsystem der ÜZW verbaut, bitte setzten Sie sich bezüglich eines Einbautermins mit uns unter der 08703 / 92 55 – 190 in Verbindung";
						$this->count = 4;
					}else{
						$this->count = 9;
					}
					break;
			}
		}

		if(isset($_POST['button_identic_producer'])){	
			switch ($identisch) {
				case 'Ja': 
					$this->count = 11;
					break;
				case 'Nein':
					$this->count = 12;
					break;
			}
		}

		if(isset($_POST['button_save_consumer'])&&((empty($_POST["smartname"]) && (empty($_POST["passwort"]) || empty($_POST["username"]))) || empty($_POST["PLZ_verbrauch"]))) {
			$err = "Bitte tragen Sie die Pflichtfelder ein! ";
			$this->count = 2;
		}elseif(isset($_POST['button_save_consumer'])&&(((isset($_POST["username"]) && isset($_POST["passwort"])) || isset($_POST["smartname"])) && isset($_POST["PLZ_verbrauch"]))) {
			$this->count = 5;
		}

		if(isset($_POST['button_save_producer'])){
			$this->count = 10;
		}

		if(isset($_POST['button_save_prosumer'])){
			$this->count = 13;
		}elseif(isset($_POST['button_save_prosumer']) && empty($_POST["PLZ_verbrauch"])){
			$err = "Bitte tragen Sie die Pflichtfelder ein! ";
			$this->count = 9;
		}


		switch ($this->count) {
			case 1: 
				require_once 'login_assistant_templates/standard_1.php';		
				break;
			case 2:
				require_once 'login_assistant_templates/consumer_2.php';
				break;
			case 3:
				require_once 'login_assistant_templates/producer_3.php';
				break;
			case 4:
				require_once 'login_assistant_templates/prosumer_4.php';
				break;
			case 5:
				require_once 'login_assistant_templates/save_consumer_5.php';
				break;
			case 6:
				require_once 'login_assistant_templates/not_consumer_6.php';
				break;
			case 7:
				require_once 'login_assistant_templates/produce_consumer_7.php';				
				break;
			case 8:
				require_once 'login_assistant_templates/identic_prosumer_8.php';
				break;
			case 9:
				require_once 'login_assistant_templates/not_identic_prosumer_9.php';
				break;
			case 10:
				require_once 'login_assistant_templates/save_producer_10.php';
				break;
			case 11:
				require_once 'login_assistant_templates/identic_producer_11.php';
				break;
			case 12:
				require_once 'login_assistant_templates/not_identic_producer_12.php';
				break;
			case 13:
				require_once 'login_assistant_templates/save_prosumer_13.php';
				break;

		}

		if(isset($_POST['button_back'])){
			require_once 'login_assistant_templates/standard_1.php';
		}
		if(isset($_POST['button_startseite'])){
			require_once 'login_assistant_templates/standard_1.php';
		}
	}
}
