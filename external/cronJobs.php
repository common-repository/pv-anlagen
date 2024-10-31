<?php

/**
* cron jobs: 
* 	-Retrieve smart meter data poweropti:
* 		-Set flag when data is not loaded
* 		-Clear flag after saving data
* 	-Retrieve smart meter data imsys
*	-calculate startpage data
* 	-Get weather data forecast
* 	-Get current weather data
*
* @since    1.0.0
*/


//add pages Gutschrift, Rechnung

//date_default_timezone_set('Europe/Berlin'); 
date_default_timezone_get();

/*if ( ! function_exists( 'PostCreator' ) ) {

	function PostCreator(
		$name      = 'AUTO POST',
		$type      = 'post',
		$content   = 'DUMMY CONTENT',
		$category  = array(1,2),
		$template  = NULL,
		$author_id = '1',
		$status    = 'publish'
	) {

		define( POST_NAME, $name );
		define( POST_TYPE, $type );
		define( POST_CONTENT, $content );
		define( POST_CATEGORY, $category );
		define( POST_TEMPLATE, '' );
		define( POST_AUTH_ID, $author_id );
		define( POST_STATUS, $status );

		if ( $type == 'page' ) {
			$post      = get_page_by_title( POST_NAME, 'OBJECT', $type );
			$post_id   = $post->ID;
			$post_data = get_page( $post_id );
			define( POST_TEMPLATE, $template );
		} else {
			$post      = get_page_by_title( POST_NAME, 'OBJECT', $type );
			$post_id   = $post->ID;
			$post_data = get_post( $post_id );
		}

		if (!function_exists('hbt_create_post'))   {
			function hbt_create_post() {
				$post_data = array(
					'post_title'    => wp_strip_all_tags( POST_NAME ),
					'post_content'  => POST_CONTENT,
					'post_status'   => POST_STATUS,
					'post_type'     => POST_TYPE,
					'post_author'   => POST_AUTH_ID,
					'post_category' => POST_CATEGORY,
					'page_template' => POST_TEMPLATE
				);
				wp_insert_post( $post_data, $error_obj );
			}
		}
		if ( ! isset( $post ) ) {
			add_action( 'admin_init', 'hbt_create_post' );
			return $error_obj;
		}

	}
}

if(!get_page_by_title('Rechnung')){
	PostCreator( 'Rechnung', 'page', '[sevz_show_invoice]' );
}
if(!get_page_by_title('Gutschrift')){
	PostCreator( 'Gutschrift', 'page', '[sevz_add_credit]' );
}*/




// add user roles: producer, consumer, prosumer
function sevz_add_user_roles_function() {

	/* Create Staff Member User Role */
	add_role(
		'producer', //  System name of the role.
		__( 'Producer'  ), // Display name of the role.
		array(
			'read'  => true,
			'delete_posts'  => false,
			'delete_published_posts' => false,
			'edit_posts'   => false,
			'publish_posts' => false,
			'upload_files'  => false,
			'edit_pages'  => false,
			'edit_published_pages'  =>  false,
			'publish_pages'  => false,
			'delete_published_pages' => false, // This user will NOT be able to  delete published pages.
		)
	);
	/* Create Staff Member User Role */
	add_role(
		'consumer', //  System name of the role.
		__( 'Consumer'  ), // Display name of the role.
		array(
			'read'  => true,
			'delete_posts'  => false,
			'delete_published_posts' => false,
			'edit_posts'   => false,
			'publish_posts' => false,
			'upload_files'  => false,
			'edit_pages'  => false,
			'edit_published_pages'  =>  false,
			'publish_pages'  => false,
			'delete_published_pages' => false, // This user will NOT be able to  delete published pages.
		)
	);
	/* Create Staff Member User Role */
	add_role(
		'prosumer', //  System name of the role.
		__( 'Prosumer'  ), // Display name of the role.
		array(
			'read'  => true,
			'delete_posts'  => false,
			'delete_published_posts' => false,
			'edit_posts'   => false,
			'publish_posts' => false,
			'upload_files'  => false,
			'edit_pages'  => false,
			'edit_published_pages'  =>  false,
			'publish_pages'  => false,
			'delete_published_pages' => false, // This user will NOT be able to  delete published pages.
		)
	);
}


// add tables consumer_data, prosumer_data, producer_data
function sevz_add_user_tables_function() {
	global $wpdb;
	//Tabelle Consumer kreieren
	$charset_collate = $wpdb->get_charset_collate();


	$sql = "CREATE TABLE `{$wpdb->base_prefix}consumer_data` (
  	ID mediumint(9) NOT NULL PRIMARY KEY,
  	name varchar(60) NOT NULL,
	anlagenUeberwachnung varchar(100),
	smartmeter varchar (60),
	benutzername varchar (60),
	passwort varchar (60),
		imsysid varchar (60),
	link1 varchar(255),
	link_forecast varchar(255),
	strasse_verbrauch varchar(255),
	hausnr_verbrauch int(11),
	PLZ_verbrauch int(255),
	stadt_verbrauch varchar(100),
	vertragsbeginn_ver date,
	grundpreis float,
	arbeitspreis_et float,
	arbeitspreis_ht float,
	arbeitspreis_nt float,
	abschlagspreis_p float,
	messstellenbetrieb float		
	) $charset_collate;";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);

	$sql_prod = "CREATE TABLE `{$wpdb->base_prefix}producer_data` (
  	ID mediumint(9) NOT NULL PRIMARY KEY,
  	name varchar(60) NOT NULL,
	anlagenUeberwachnung varchar(100),
	smartmeter varchar (60),
	benutzername varchar (60),
	passwort varchar (60),
	imsysid varchar (60),
	link1 varchar(255),
	link_forecast varchar(255),
	image_url varchar(255),
	strasse_pv varchar(255),
	hausnr_pv int(11),
	PLZ_pv int(255),
	stadt_pv varchar(100),
	vertragsbeginn_ein date,
	mwst_ausweis text,
	preis float,
	abschlagspreis_credit float,
	zaehlergebuehr float,
	einspeisemanagement float,
	nettonennleistung float,
	inbetriebnahmedatum date,
	ausrichtung  varchar(60),
	neigung int,
	flag int		
	) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql_prod);

	$sql_pros = "CREATE TABLE `{$wpdb->base_prefix}prosumer_data` (
  	ID mediumint(9) NOT NULL PRIMARY KEY,
  	name varchar(60) NOT NULL,
	anlagenUeberwachnung varchar(100),
	smartmeter varchar (60),
	benutzername varchar (60),
	passwort varchar (60),
	imsysid varchar (60),
	link1 varchar(255),
	link_forecast varchar(255),
	image_url varchar(255),
	strasse_verbrauch varchar(255),
	hausnr_verbrauch int(11),
	PLZ_verbrauch int(255),
	stadt_verbrauch varchar(100),
	vertragsbeginn_ver date,
	strasse_pv varchar(255),
	hausnr_pv int(11),
	PLZ_pv int(255),
	stadt_pv varchar(100),
	vertragsbeginn_ein date,
	mwst_ausweis text,
	preis float,
	abschlagspreis_credit float,
	zaehlergebuehr float,
	einspeisemanagement float,
	nettonennleistung float,
	inbetriebnahmedatum date,
	ausrichtung  varchar(60),
	neigung int,
	grundpreis float,
	arbeitspreis_et float,
	arbeitspreis_ht float,
	arbeitspreis_nt float,
	abschlagspreis_p float,
	messstellenbetrieb float,
	flag int
	) $charset_collate;";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql_pros);
}


/****************retrieve smart meter data poweropti****************************/

function sevz_get_json_data_function_0() {
	global $wpdb;

	$year = date("Y");
	$month = date("m");

	//new yesterday-calculation at 01.09.2021
	$yesterday_calculate = date('Y-m-d', strtotime("-1 days"));
	$yesterday_year = (new DateTime("$yesterday_calculate"))->format('Y');
	$yesterday_month = (new DateTime("$yesterday_calculate"))->format('m');
	$yesterday_day = (new DateTime("$yesterday_calculate"))->format('d');

	//JSON-Daten "OptiPower" jedes Produzenten in Mysql speichern
	$produzent = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}producer_data` WHERE `link1` <> ''");

	//alle Produzenten durchgehen
	foreach($produzent as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;


		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$tablename = 'producer_'.$nachname.'_'.$ID; 

		//für jeden Produzenten Tabelle kreieren
		$sql = "CREATE TABLE `{$wpdb->base_prefix}{$tablename}`(
  			count mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			erstellt_am_tag datetime,
			zeitstempel datetime,
			verbrauch_kwh float(24),
  			einspeisung_kwh float(24),
			flag datetime
		) $charset_collate;";

		//falls Tabelle nicht existiert, wird eine creiert
		maybe_create_table( $wpdb->prefix . $tablename, $sql );

		//JSON-Links der OptiPower für jede 6 Stunden ab 01:00 Uhr			
		$json_url0 = "https://$link@backend.powerfox.energy/api/2.0/my/all/report?year=$yesterday_year&month=$yesterday_month&day=$yesterday_day&fromhour=01";


		$json0= wp_remote_get( $json_url0 );

		if( is_wp_error( $json0 ) ) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $json0 );

		$data0 = json_decode($body, TRUE);

		if( ! empty( $data0 ) ) {

			//Daten ab 01:00-07:00Uhr speichern
			for($i = count($data0['Consumption']['ReportValues'])-1; $i >=0; $i--){
				${'verbrauch_timestamp_'.$i} = $data0['FeedIn']['ReportValues'][$i]['Timestamp'];
				${'verbrauch_delta_'.$i} = $data0['Consumption']['ReportValues'][$i]['Delta'];
				${'einspeisung_delta_'.$i} = $data0['FeedIn']['ReportValues'][$i]['Delta'];

				$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

				//falls Daten bereits existiren, werden nur neue eingefügt
				$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}producer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 

				if($datenvergleich ==NULL){ 
					$wpdb->insert($wpdb->base_prefix.'producer_'.$nachname.'_'.$ID, 
								  array(
									  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
									  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
									  'einspeisung_kwh' => ${'einspeisung_delta_'.$i}
								  ));
				}
			}
		}
	}


	//JSON-Daten "OptiPower" jedes Prosumers in Mysql speichern
	$prosumer = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}prosumer_data` WHERE `link1` <> '' ");


	//alle Prosumer durchgehen
	foreach($prosumer as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;


		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$tablename = 'prosumer_'.$nachname.'_'.$ID; 

		//für jeden Produzenten Tabelle kreieren
		$sql = "CREATE TABLE `{$wpdb->base_prefix}{$tablename}`(
  			count mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			erstellt_am_tag datetime,
			zeitstempel datetime,
			verbrauch_kwh float(24),
  			einspeisung_kwh float(24),
			flag datetime
		) $charset_collate;";

		//falls Tabelle nicht existiert, wird eine creiert
		maybe_create_table( $wpdb->prefix . $tablename, $sql );

		//JSON-Links der OptiPower für jede 6 Stunden ab 01:00 Uhr			
		$json_url0 = "https://$link@backend.powerfox.energy/api/2.0/my/all/report?year=$yesterday_year&month=$yesterday_month&day=$yesterday_day&fromhour=01";
		$json0= wp_remote_get( $json_url0 );

		if( is_wp_error( $json0 ) ) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $json0 );

		$data0 = json_decode($body, TRUE);

		if( ! empty( $data0 ) ) {

			//Daten ab 01:00-07:00Uhr speichern
			for($i = count($data0['Consumption']['ReportValues'])-1; $i >=0; $i--){
				${'verbrauch_timestamp_'.$i} = $data0['Consumption']['ReportValues'][$i]['Timestamp'];
				${'verbrauch_delta_'.$i} = $data0['Consumption']['ReportValues'][$i]['Delta'];
				${'einspeisung_delta_'.$i} = $data0['FeedIn']['ReportValues'][$i]['Delta'];

				$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

				//falls Daten bereits existiren, werden nur neue eingefügt
				$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}prosumer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 

				if($datenvergleich ==NULL){ 
					$wpdb->insert($wpdb->base_prefix.'prosumer_'.$nachname.'_'.$ID, 
								  array(
									  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
									  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
									  'verbrauch_kwh' => ${'verbrauch_delta_'.$i},
									  'einspeisung_kwh' => ${'einspeisung_delta_'.$i}
								  ));
				}
			}
		}
	}

	//JSON-Daten "OptiPower" jedes Verbrauchers in Mysql speichern
	$customer = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}consumer_data` WHERE `link1` <> '' ");


	//alle Verbraucher durchgehen
	foreach($customer as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$tablename = 'consumer_'.$nachname.'_'.$ID; 

		//für jeden Consumer Tabelle kreieren
		$sql = "CREATE TABLE `{$wpdb->base_prefix}{$tablename}`(
  			count mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			erstellt_am_tag datetime,
			zeitstempel datetime,
			verbrauch_kwh float(24),
			flag datetime
		) $charset_collate;";

		//falls Tabelle nicht existiert, wird eine creiert
		maybe_create_table( $wpdb->prefix . $tablename, $sql );

		//JSON-Links der OptiPower für jede 6 Stunden ab 01:00 Uhr			
		$json_url0 = "https://$link@backend.powerfox.energy/api/2.0/my/all/report?year=$yesterday_year&month=$yesterday_month&day=$yesterday_day&fromhour=01";
		$json0= wp_remote_get( $json_url0 );

		if( is_wp_error( $json0 ) ) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $json0 );

		$data0 = json_decode($body, TRUE);

		if( ! empty( $data0 ) ) {

			//Daten ab 01:00-07:00Uhr speichern
			for($i = count($data0['Consumption']['ReportValues'])-1; $i >=0; $i--){
				${'verbrauch_timestamp_'.$i} = $data0['Consumption']['ReportValues'][$i]['Timestamp'];
				${'verbrauch_delta_'.$i} = $data0['Consumption']['ReportValues'][$i]['Delta'];

				$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

				//falls Daten bereits existiren, werden nur neue eingefügt
				$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}consumer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 

				if($datenvergleich ==NULL){ 
					$wpdb->insert($wpdb->base_prefix.'consumer_'.$nachname.'_'.$ID, 
								  array(
									  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
									  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
									  'verbrauch_kwh' => ${'verbrauch_delta_'.$i}
								  ));
				}
			}
		}
	}
}


function sevz_get_json_data_function_1() {
	global $wpdb;

	$year = date("Y");
	$month = date("m");
	$today = date("Y-m-d\T00");


	//new yesterday-calculation at 01.09.2021
	$yesterday_calculate = date('Y-m-d', strtotime("-1 days"));
	$yesterday_year = (new DateTime("$yesterday_calculate"))->format('Y');
	$yesterday_month = (new DateTime("$yesterday_calculate"))->format('m');
	$yesterday_day = (new DateTime("$yesterday_calculate"))->format('d');

	//JSON-Daten "OptiPower" jedes Produzenten in Mysql speichern
	$produzent = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}producer_data` WHERE `link1` <> '' ");

	//alle Produzenten durchgehen
	foreach($produzent as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;

		$json_url6 = "https://$link@backend.powerfox.energy/api/2.0/my/all/report?year=$yesterday_year&month=$yesterday_month&day=$yesterday_day&fromhour=07";
		$json6= wp_remote_get( $json_url6 );

		if( is_wp_error( $json6 ) ) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $json6 );

		$data6 = json_decode($body, TRUE);

		if( ! empty( $data6 ) ) {

			//Daten ab 07:00-13:00Uhr speichern
			for($i = count($data6['Consumption']['ReportValues'])-1; $i >=0; $i--){
				${'verbrauch_timestamp_'.$i} = $data6['Consumption']['ReportValues'][$i]['Timestamp'];
				${'verbrauch_delta_'.$i} = $data6['Consumption']['ReportValues'][$i]['Delta'];
				${'einspeisung_delta_'.$i} = $data6['FeedIn']['ReportValues'][$i]['Delta'];

				$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

				//falls Daten bereits existiren, werden nur neue eingefügt
				$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}producer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 

				if($datenvergleich ==NULL){ 
					$wpdb->insert($wpdb->base_prefix.'producer_'.$nachname.'_'.$ID, 
								  array(
									  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
									  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
									  'einspeisung_kwh' => ${'einspeisung_delta_'.$i}
								  ));
				}
			}
		}
	}
	//JSON-Daten "OptiPower" jedes Prosumers in Mysql speichern
	$prosumer = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}prosumer_data` WHERE `link1` <> '' ");


	//alle Prosumer durchgehen
	foreach($prosumer as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;

		$json_url6 = "https://$link@backend.powerfox.energy/api/2.0/my/all/report?year=$yesterday_year&month=$yesterday_month&day=$yesterday_day&fromhour=07";
		$json6= wp_remote_get( $json_url6 );

		if( is_wp_error( $json6 ) ) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $json6 );

		$data6 = json_decode($body, TRUE);

		if( ! empty( $data6 ) ) {

			//Daten ab 07:00-13:00Uhr speichern
			for($i = count($data6['Consumption']['ReportValues'])-1; $i >=0; $i--){
				${'verbrauch_timestamp_'.$i} = $data6['Consumption']['ReportValues'][$i]['Timestamp'];
				${'verbrauch_delta_'.$i} = $data6['Consumption']['ReportValues'][$i]['Delta'];
				${'einspeisung_delta_'.$i} = $data6['FeedIn']['ReportValues'][$i]['Delta'];

				$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

				//falls Daten bereits existiren, werden nur neue eingefügt
				$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}prosumer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 

				if($datenvergleich ==NULL){ 
					$wpdb->insert($wpdb->base_prefix.'prosumer_'.$nachname.'_'.$ID, 
								  array(
									  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
									  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
									  'verbrauch_kwh' => ${'verbrauch_delta_'.$i},
									  'einspeisung_kwh' => ${'einspeisung_delta_'.$i}
								  ));
				}
			}
		}
	}
	//JSON-Daten "OptiPower" jedes Verbrauchers in Mysql speichern
	$customer = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}consumer_data` WHERE `link1` <> '' ");


	//alle Verbraucher durchgehen
	foreach($customer as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;


		$json_url6 = "https://$link@backend.powerfox.energy/api/2.0/my/all/report?year=$yesterday_year&month=$yesterday_month&day=$yesterday_day&fromhour=07";
		$json6= wp_remote_get( $json_url6 );

		if( is_wp_error( $json6 ) ) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $json6 );

		$data6 = json_decode($body, TRUE);

		if( ! empty( $data6 ) ) {


			//Daten ab 07:00-13:00Uhr speichern
			for($i = count($data6['Consumption']['ReportValues'])-1; $i >=0; $i--){
				${'verbrauch_timestamp_'.$i} = $data6['Consumption']['ReportValues'][$i]['Timestamp'];
				${'verbrauch_delta_'.$i} = $data6['Consumption']['ReportValues'][$i]['Delta'];

				$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

				//falls Daten bereits existiren, werden nur neue eingefügt
				$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}consumer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 

				if($datenvergleich ==NULL){ 
					$wpdb->insert($wpdb->base_prefix.'consumer_'.$nachname.'_'.$ID, 
								  array(
									  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
									  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
									  'verbrauch_kwh' => ${'verbrauch_delta_'.$i}
								  ));
				}
			}
		}
	}
}


function sevz_get_json_data_function_2() {
	global $wpdb;

	$year = date("Y");
	$month = date("m");
	$today = date("Y-m-d\T00");


	//new yesterday-calculation at 01.09.2021
	$yesterday_calculate = date('Y-m-d', strtotime("-1 days"));
	$yesterday_year = (new DateTime("$yesterday_calculate"))->format('Y');
	$yesterday_month = (new DateTime("$yesterday_calculate"))->format('m');
	$yesterday_day = (new DateTime("$yesterday_calculate"))->format('d');

	//JSON-Daten "OptiPower" jedes Produzenten in Mysql speichern
	$produzent = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}producer_data` WHERE `link1` <> '' ");

	//alle Produzenten durchgehen
	foreach($produzent as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;

		$json_url12 = "https://$link@backend.powerfox.energy/api/2.0/my/all/report?year=$yesterday_year&month=$yesterday_month&day=$yesterday_day&fromhour=13";
		$json12= wp_remote_get( $json_url12 );

		if( is_wp_error( $json12 ) ) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $json12 );

		$data12 = json_decode($body, TRUE);

		if( ! empty( $data12 ) ) {

			//Daten ab 13:00-19:00Uhr speichern
			for($i = count($data12['Consumption']['ReportValues'])-1; $i >=0; $i--){
				${'verbrauch_timestamp_'.$i} = $data12['Consumption']['ReportValues'][$i]['Timestamp'];
				${'verbrauch_delta_'.$i} = $data12['Consumption']['ReportValues'][$i]['Delta'];
				${'einspeisung_delta_'.$i} = $data12['FeedIn']['ReportValues'][$i]['Delta'];

				$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

				//falls Daten bereits existiren, werden nur neue eingefügt
				$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}producer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'");

				if($datenvergleich ==NULL){ 
					$wpdb->insert($wpdb->base_prefix.'producer_'.$nachname.'_'.$ID, 
								  array(
									  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
									  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
									  'einspeisung_kwh' => ${'einspeisung_delta_'.$i}
								  ));
				}
			}
		}
	}

	//JSON-Daten "OptiPower" jedes Prosumers in Mysql speichern
	$prosumer = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}prosumer_data` WHERE `link1` <> '' ");


	//alle Prosumer durchgehen
	foreach($prosumer as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;

		$json_url12 = "https://$link@backend.powerfox.energy/api/2.0/my/all/report?year=$yesterday_year&month=$yesterday_month&day=$yesterday_day&fromhour=13";
		$json12= wp_remote_get( $json_url12 );

		if( is_wp_error( $json12 ) ) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $json12 );

		$data12 = json_decode($body, TRUE);

		if( ! empty( $data12 ) ) {
			//Daten ab 13:00-19:00Uhr speichern
			for($i = count($data12['Consumption']['ReportValues'])-1; $i >=0; $i--){
				${'verbrauch_timestamp_'.$i} = $data12['Consumption']['ReportValues'][$i]['Timestamp'];
				${'verbrauch_delta_'.$i} = $data12['Consumption']['ReportValues'][$i]['Delta'];
				${'einspeisung_delta_'.$i} = $data12['FeedIn']['ReportValues'][$i]['Delta'];

				$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

				//falls Daten bereits existiren, werden nur neue eingefügt
				$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}prosumer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'");

				if($datenvergleich ==NULL){ 
					$wpdb->insert($wpdb->base_prefix.'prosumer_'.$nachname.'_'.$ID, 
								  array(
									  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
									  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
									  'verbrauch_kwh' => ${'verbrauch_delta_'.$i},
									  'einspeisung_kwh' => ${'einspeisung_delta_'.$i}
								  ));
				}
			}
		}
	}

	//JSON-Daten "OptiPower" jedes Verbrauchers in Mysql speichern
	$customer = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}consumer_data` WHERE `link1` <> '' ");


	//alle Verbraucher durchgehen
	foreach($customer as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;

		$json_url12 = "https://$link@backend.powerfox.energy/api/2.0/my/all/report?year=$yesterday_year&month=$yesterday_month&day=$yesterday_day&fromhour=13";
		$json12= wp_remote_get( $json_url12 );

		if( is_wp_error( $json12 ) ) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $json12 );

		$data12 = json_decode($body, TRUE);

		if( ! empty( $data12 ) ) {

			//Daten ab 13:00-19:00Uhr speichern
			for($i = count($data12['Consumption']['ReportValues'])-1; $i >=0; $i--){
				${'verbrauch_timestamp_'.$i} = $data12['Consumption']['ReportValues'][$i]['Timestamp'];
				${'verbrauch_delta_'.$i} = $data12['Consumption']['ReportValues'][$i]['Delta'];

				$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

				//falls Daten bereits existiren, werden nur neue eingefügt
				$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}consumer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'");

				if($datenvergleich ==NULL){ 
					$wpdb->insert($wpdb->base_prefix.'consumer_'.$nachname.'_'.$ID, 
								  array(
									  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
									  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
									  'verbrauch_kwh' => ${'verbrauch_delta_'.$i}
								  ));
				}
			}
		}
	}
}




function sevz_get_json_data_function_3() {
	global $wpdb;

	$year = date("Y");
	$month = date("m");
	$today = date("Y-m-d\T00");


	//new yesterday-calculation at 01.09.2021
	$yesterday_calculate = date('Y-m-d', strtotime("-1 days"));
	$yesterday_year = (new DateTime("$yesterday_calculate"))->format('Y');
	$yesterday_month = (new DateTime("$yesterday_calculate"))->format('m');
	$yesterday_day = (new DateTime("$yesterday_calculate"))->format('d');

	//JSON-Daten "OptiPower" jedes Produzenten in Mysql speichern
	$produzent = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}producer_data` WHERE `link1` <> '' ");

	//alle Produzenten durchgehen
	foreach($produzent as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;

		$json_url18 = "https://$link@backend.powerfox.energy/api/2.0/my/all/report?year=$yesterday_year&month=$yesterday_month&day=$yesterday_day&fromhour=19";

		$json18= wp_remote_get( $json_url18 );

		if( is_wp_error( $json18 ) ) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $json18 );

		$data18 = json_decode($body, TRUE);


		if( ! empty( $data18 ) ) {

			//Daten ab 19:00-01:00Uhr speichern
			for($i = count($data18['Consumption']['ReportValues'])-1; $i >=0; $i--){
				${'verbrauch_timestamp_'.$i} = $data18['Consumption']['ReportValues'][$i]['Timestamp'];
				${'verbrauch_delta_'.$i} = $data18['Consumption']['ReportValues'][$i]['Delta'];
				${'einspeisung_delta_'.$i} = $data18['FeedIn']['ReportValues'][$i]['Delta'];

				$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

				//falls Daten bereits existiren, werden nur neue eingefügt
				$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}producer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 

				if($datenvergleich ==NULL){ 
					$wpdb->insert($wpdb->base_prefix.'producer_'.$nachname.'_'.$ID, 
								  array(
									  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
									  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
									  'einspeisung_kwh' => ${'einspeisung_delta_'.$i}
								  ));
				}
			}	
		}
	}




	//JSON-Daten "OptiPower" jedes Prosumers in Mysql speichern
	$prosumer = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}prosumer_data` WHERE `link1` <> '' ");


	//alle Prosumer durchgehen
	foreach($prosumer as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;

		$json_url18 = "https://$link@backend.powerfox.energy/api/2.0/my/all/report?year=$yesterday_year&month=$yesterday_month&day=$yesterday_day&fromhour=19";

		$json18= wp_remote_get( $json_url18 );

		if( is_wp_error( $json18 ) ) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $json18 );

		$data18 = json_decode($body, TRUE);

		if( ! empty( $data18 ) ) {


			//Daten ab 19:00-01:00Uhr speichern
			for($i = count($data18['Consumption']['ReportValues'])-1; $i >=0; $i--){
				${'verbrauch_timestamp_'.$i} = $data18['Consumption']['ReportValues'][$i]['Timestamp'];
				${'verbrauch_delta_'.$i} = $data18['Consumption']['ReportValues'][$i]['Delta'];
				${'einspeisung_delta_'.$i} = $data18['FeedIn']['ReportValues'][$i]['Delta'];

				$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

				//falls Daten bereits existiren, werden nur neue eingefügt
				$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}prosumer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 

				if($datenvergleich ==NULL){ 
					$wpdb->insert($wpdb->base_prefix.'prosumer_'.$nachname.'_'.$ID, 
								  array(
									  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
									  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
									  'verbrauch_kwh' => ${'verbrauch_delta_'.$i},
									  'einspeisung_kwh' => ${'einspeisung_delta_'.$i}
								  ));
				}
			}	
		}
	}



	//JSON-Daten "OptiPower" jedes Verbrauchers in Mysql speichern
	$customer = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}consumer_data` WHERE `link1` <> '' ");


	//alle Verbraucher durchgehen
	foreach($customer as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;

		$json_url18 = "https://$link@backend.powerfox.energy/api/2.0/my/all/report?year=$yesterday_year&month=$yesterday_month&day=$yesterday_day&fromhour=19";

		$json18= wp_remote_get( $json_url18 );

		if( is_wp_error( $json18 ) ) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $json18 );

		$data18 = json_decode($body, TRUE);
		if( ! empty( $data18 ) ) {

			//Daten ab 19:00-01:00Uhr speichern
			for($i = count($data18['Consumption']['ReportValues'])-1; $i >=0; $i--){
				${'verbrauch_timestamp_'.$i} = $data18['Consumption']['ReportValues'][$i]['Timestamp'];
				${'verbrauch_delta_'.$i} = $data18['Consumption']['ReportValues'][$i]['Delta'];

				$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

				//falls Daten bereits existiren, werden nur neue eingefügt
				$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}consumer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 

				if($datenvergleich ==NULL){ 
					$wpdb->insert($wpdb->base_prefix.'consumer_'.$nachname.'_'.$ID, 
								  array(
									  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
									  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
									  'verbrauch_kwh' => ${'verbrauch_delta_'.$i}
								  ));
				}
			}	
		}
	}
}


/************************Set flag "poweropti" when data is not loaded***************************************/
//set flags if data 01-07h does not exist
function sevz_set_flag_0(){
	global $wpdb;	

	$control_time = ' 06:45:00';


	$produzent = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}producer_data` WHERE `link1` <> '' ");
	//alle Produzenten durchgehen
	foreach($produzent as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;

		$anfang = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}producer_{$nachname}_{$ID}` ORDER BY `zeitstempel` ASC LIMIT 1"); 

		$ende = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}producer_{$nachname}_{$ID}` ORDER BY `zeitstempel` DESC LIMIT 1"); 


		foreach($anfang as $value){
			$beginzeit = $value->zeitstempel;

		}
		foreach($ende as $value){
			$endzeit = $value->zeitstempel;

		}


		$begin = new DateTime( "$beginzeit" );
		$end   = new DateTime( "$endzeit" );
		$count=1;
		for($i = $begin; $i <= $end; $i->modify('+1 day')){
			$controlflag = $i->format("Y-m-d").$control_time;
			$failed_time = $i->format("Y-m-d").$control_time;


			//falls Daten nicht existiren, wird am Anfang das fehlende Datum gesetzt
			$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}producer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$controlflag'"); 

			if($datenvergleich ==NULL){ 

				$wpdb->update($wpdb->base_prefix.'producer_'.$nachname.'_'.$ID, 
							  array(
								  'flag' => $failed_time
							  ),array( 'count' => $count));
				$count+=1;

			}
		}
	}



	$prosumer = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}prosumer_data` WHERE `link1` <> '' ");
	//alle Prosumer durchgehen
	foreach($prosumer as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;

		$anfang = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}prosumer_{$nachname}_{$ID}` ORDER BY `zeitstempel` ASC LIMIT 1"); 

		$ende = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}prosumer_{$nachname}_{$ID}` ORDER BY `zeitstempel` DESC LIMIT 1"); 


		foreach($anfang as $value){
			$beginzeit = $value->zeitstempel;		
		}
		foreach($ende as $value){
			$endzeit = $value->zeitstempel;			
		}

		$begin = new DateTime( "$beginzeit" );
		$end   = new DateTime( "$endzeit" );
		$count=1;
		for($i = $begin; $i <= $end; $i->modify('+1 day')){
			$controlflag = $i->format("Y-m-d").$control_time;
			$failed_time = $i->format("Y-m-d").$control_time;


			//falls Daten nicht existiren, wird am Anfang das fehlende Datum gesetzt
			$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}prosumer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$controlflag'"); 

			if($datenvergleich ==NULL){ 

				$wpdb->update($wpdb->base_prefix.'prosumer_'.$nachname.'_'.$ID, 
							  array(
								  'flag' => $failed_time
							  ),array( 'count' => $count));
				$count+=1;

			}
		}
	}
	$verbraucher = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}consumer_data` WHERE `link1` <> '' ");
	//alle Verbraucher durchgehen
	foreach($verbraucher as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;

		$anfang = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}consumer_{$nachname}_{$ID}` ORDER BY `zeitstempel` ASC LIMIT 1"); 

		$ende = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}consumer_{$nachname}_{$ID}` ORDER BY `zeitstempel` DESC LIMIT 1"); 


		foreach($anfang as $value){
			$beginzeit = $value->zeitstempel;
		}
		foreach($ende as $value){
			$endzeit = $value->zeitstempel;
		}

		$begin = new DateTime( "$beginzeit" );
		$end   = new DateTime( "$endzeit" );
		$count=1;
		for($i = $begin; $i <= $end; $i->modify('+1 day')){
			$controlflag = $i->format("Y-m-d").$control_time;
			$failed_time = $i->format("Y-m-d").$control_time;


			//falls Daten nicht existiren, wird am Anfang das fehlende Datum gesetzt
			$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}consumer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$controlflag'"); 

			if($datenvergleich ==NULL){ 
				$wpdb->update($wpdb->base_prefix.'consumer_'.$nachname.'_'.$ID, 
							  array(
								  'flag' => $failed_time
							  ),array( 'count' => $count));
				$count+=1;
			}
		}
	}
}



function sevz_set_flag_1(){
	global $wpdb;	

	$control_time = ' 12:45:00';


	$produzent = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}producer_data` WHERE `link1` <> '' ");
	//alle Produzenten durchgehen
	foreach($produzent as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;

		$anfang = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}producer_{$nachname}_{$ID}` ORDER BY `zeitstempel` ASC LIMIT 1"); 

		$ende = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}producer_{$nachname}_{$ID}` ORDER BY `zeitstempel` DESC LIMIT 1"); 


		foreach($anfang as $value){
			$beginzeit = $value->zeitstempel;

		}
		foreach($ende as $value){
			$endzeit = $value->zeitstempel;

		}


		$begin = new DateTime( "$beginzeit" );
		$end   = new DateTime( "$endzeit" );
		$count=1;
		for($i = $begin; $i <= $end; $i->modify('+1 day')){
			$controlflag = $i->format("Y-m-d").$control_time;
			$failed_time = $i->format("Y-m-d").$control_time;


			//falls Daten nicht existiren, wird am Anfang das fehlende Datum gesetzt
			$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}producer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$controlflag'"); 

			if($datenvergleich ==NULL){ 

				$wpdb->update($wpdb->base_prefix.'producer_'.$nachname.'_'.$ID, 
							  array(
								  'flag' => $failed_time
							  ),array( 'count' => $count));
				$count+=1;

			}
		}
	}



	$prosumer = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}prosumer_data` WHERE `link1` <> '' ");
	//alle Prosumer durchgehen
	foreach($prosumer as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;

		$anfang = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}prosumer_{$nachname}_{$ID}` ORDER BY `zeitstempel` ASC LIMIT 1"); 

		$ende = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}prosumer_{$nachname}_{$ID}` ORDER BY `zeitstempel` DESC LIMIT 1"); 


		foreach($anfang as $value){
			$beginzeit = $value->zeitstempel;		
		}
		foreach($ende as $value){
			$endzeit = $value->zeitstempel;			
		}

		$begin = new DateTime( "$beginzeit" );
		$end   = new DateTime( "$endzeit" );
		$count=1;
		for($i = $begin; $i <= $end; $i->modify('+1 day')){
			$controlflag = $i->format("Y-m-d").$control_time;
			$failed_time = $i->format("Y-m-d").$control_time;


			//falls Daten nicht existiren, wird am Anfang das fehlende Datum gesetzt
			$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}prosumer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$controlflag'"); 

			if($datenvergleich ==NULL){ 

				$wpdb->update($wpdb->base_prefix.'prosumer_'.$nachname.'_'.$ID, 
							  array(
								  'flag' => $failed_time
							  ),array( 'count' => $count));
				$count+=1;

			}
		}
	}
	$verbraucher = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}consumer_data` WHERE `link1` <> '' ");
	//alle Verbraucher durchgehen
	foreach($verbraucher as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;

		$anfang = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}consumer_{$nachname}_{$ID}` ORDER BY `zeitstempel` ASC LIMIT 1"); 

		$ende = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}consumer_{$nachname}_{$ID}` ORDER BY `zeitstempel` DESC LIMIT 1"); 


		foreach($anfang as $value){
			$beginzeit = $value->zeitstempel;
		}
		foreach($ende as $value){
			$endzeit = $value->zeitstempel;
		}

		$begin = new DateTime( "$beginzeit" );
		$end   = new DateTime( "$endzeit" );
		$count=1;
		for($i = $begin; $i <= $end; $i->modify('+1 day')){
			$controlflag = $i->format("Y-m-d").$control_time;
			$failed_time = $i->format("Y-m-d").$control_time;


			//falls Daten nicht existiren, wird am Anfang das fehlende Datum gesetzt
			$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}consumer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$controlflag'"); 

			if($datenvergleich ==NULL){ 
				$wpdb->update($wpdb->base_prefix.'consumer_'.$nachname.'_'.$ID, 
							  array(
								  'flag' => $failed_time
							  ),array( 'count' => $count));
				$count+=1;
			}
		}
	}
}


function sevz_set_flag_2(){
	global $wpdb;	

	$control_time = ' 18:45:00';


	$produzent = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}producer_data` WHERE `link1` <> '' ");
	//alle Produzenten durchgehen
	foreach($produzent as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;

		$anfang = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}producer_{$nachname}_{$ID}` ORDER BY `zeitstempel` ASC LIMIT 1"); 

		$ende = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}producer_{$nachname}_{$ID}` ORDER BY `zeitstempel` DESC LIMIT 1"); 


		foreach($anfang as $value){
			$beginzeit = $value->zeitstempel;

		}
		foreach($ende as $value){
			$endzeit = $value->zeitstempel;

		}


		$begin = new DateTime( "$beginzeit" );
		$end   = new DateTime( "$endzeit" );
		$count=1;
		for($i = $begin; $i <= $end; $i->modify('+1 day')){
			$controlflag = $i->format("Y-m-d").$control_time;
			$failed_time = $i->format("Y-m-d").$control_time;


			//falls Daten nicht existiren, wird am Anfang das fehlende Datum gesetzt
			$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}producer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$controlflag'"); 

			if($datenvergleich ==NULL){ 

				$wpdb->update($wpdb->base_prefix.'producer_'.$nachname.'_'.$ID, 
							  array(
								  'flag' => $failed_time
							  ),array( 'count' => $count));
				$count+=1;

			}
		}
	}



	$prosumer = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}prosumer_data` WHERE `link1` <> '' ");
	//alle Prosumer durchgehen
	foreach($prosumer as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;

		$anfang = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}prosumer_{$nachname}_{$ID}` ORDER BY `zeitstempel` ASC LIMIT 1"); 

		$ende = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}prosumer_{$nachname}_{$ID}` ORDER BY `zeitstempel` DESC LIMIT 1"); 


		foreach($anfang as $value){
			$beginzeit = $value->zeitstempel;		
		}
		foreach($ende as $value){
			$endzeit = $value->zeitstempel;			
		}

		$begin = new DateTime( "$beginzeit" );
		$end   = new DateTime( "$endzeit" );
		$count=1;
		for($i = $begin; $i <= $end; $i->modify('+1 day')){
			$controlflag = $i->format("Y-m-d").$control_time;
			$failed_time = $i->format("Y-m-d").$control_time;


			//falls Daten nicht existiren, wird am Anfang das fehlende Datum gesetzt
			$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}prosumer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$controlflag'"); 

			if($datenvergleich ==NULL){ 

				$wpdb->update($wpdb->base_prefix.'prosumer_'.$nachname.'_'.$ID, 
							  array(
								  'flag' => $failed_time
							  ),array( 'count' => $count));
				$count+=1;

			}
		}
	}
	$verbraucher = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}consumer_data` WHERE `link1` <> '' ");
	//alle Verbraucher durchgehen
	foreach($verbraucher as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;

		$anfang = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}consumer_{$nachname}_{$ID}` ORDER BY `zeitstempel` ASC LIMIT 1"); 

		$ende = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}consumer_{$nachname}_{$ID}` ORDER BY `zeitstempel` DESC LIMIT 1"); 


		foreach($anfang as $value){
			$beginzeit = $value->zeitstempel;
		}
		foreach($ende as $value){
			$endzeit = $value->zeitstempel;
		}

		$begin = new DateTime( "$beginzeit" );
		$end   = new DateTime( "$endzeit" );
		$count=1;
		for($i = $begin; $i <= $end; $i->modify('+1 day')){
			$controlflag = $i->format("Y-m-d").$control_time;
			$failed_time = $i->format("Y-m-d").$control_time;


			//falls Daten nicht existiren, wird am Anfang das fehlende Datum gesetzt
			$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}consumer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$controlflag'"); 

			if($datenvergleich ==NULL){ 
				$wpdb->update($wpdb->base_prefix.'consumer_'.$nachname.'_'.$ID, 
							  array(
								  'flag' => $failed_time
							  ),array( 'count' => $count));
				$count+=1;
			}
		}
	}
}










function sevz_set_flag_3(){
	global $wpdb;	

	$control_time = ' 00:45:00';


	$produzent = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}producer_data` WHERE `link1` <> '' ");
	//alle Produzenten durchgehen
	foreach($produzent as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;

		$anfang = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}producer_{$nachname}_{$ID}` ORDER BY `zeitstempel` ASC LIMIT 1"); 

		$ende = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}producer_{$nachname}_{$ID}` ORDER BY `zeitstempel` DESC LIMIT 1"); 


		foreach($anfang as $value){
			$beginzeit = $value->zeitstempel;

		}
		foreach($ende as $value){
			$endzeit = $value->zeitstempel;

		}


		$begin = new DateTime( "$beginzeit" );
		$end   = new DateTime( "$endzeit" );
		$count=1;
		for($i = $begin; $i <= $end; $i->modify('+1 day')){
			$controlflag = $i->format("Y-m-d").$control_time;
			$failed_time = $i->format("Y-m-d").$control_time;


			//falls Daten nicht existiren, wird am Anfang das fehlende Datum gesetzt
			$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}producer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$controlflag'"); 

			if($datenvergleich ==NULL){ 

				$wpdb->update($wpdb->base_prefix.'producer_'.$nachname.'_'.$ID, 
							  array(
								  'flag' => $failed_time
							  ),array( 'count' => $count));
				$count+=1;

			}
		}
	}



	$prosumer = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}prosumer_data` WHERE `link1` <> '' ");
	//alle Prosumer durchgehen
	foreach($prosumer as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;

		$anfang = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}prosumer_{$nachname}_{$ID}` ORDER BY `zeitstempel` ASC LIMIT 1"); 

		$ende = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}prosumer_{$nachname}_{$ID}` ORDER BY `zeitstempel` DESC LIMIT 1"); 


		foreach($anfang as $value){
			$beginzeit = $value->zeitstempel;		
		}
		foreach($ende as $value){
			$endzeit = $value->zeitstempel;			
		}

		$begin = new DateTime( "$beginzeit" );
		$end   = new DateTime( "$endzeit" );
		$count=1;
		for($i = $begin; $i <= $end; $i->modify('+1 day')){
			$controlflag = $i->format("Y-m-d").$control_time;
			$failed_time = $i->format("Y-m-d").$control_time;


			//falls Daten nicht existiren, wird am Anfang das fehlende Datum gesetzt
			$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}prosumer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$controlflag'"); 

			if($datenvergleich ==NULL){ 

				$wpdb->update($wpdb->base_prefix.'prosumer_'.$nachname.'_'.$ID, 
							  array(
								  'flag' => $failed_time
							  ),array( 'count' => $count));
				$count+=1;

			}
		}
	}
	$verbraucher = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}consumer_data` WHERE `link1` <> '' ");
	//alle Verbraucher durchgehen
	foreach($verbraucher as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;

		$anfang = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}consumer_{$nachname}_{$ID}` ORDER BY `zeitstempel` ASC LIMIT 1"); 

		$ende = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}consumer_{$nachname}_{$ID}` ORDER BY `zeitstempel` DESC LIMIT 1"); 


		foreach($anfang as $value){
			$beginzeit = $value->zeitstempel;
		}
		foreach($ende as $value){
			$endzeit = $value->zeitstempel;
		}

		$begin = new DateTime( "$beginzeit" );
		$end   = new DateTime( "$endzeit" );
		$count=1;
		for($i = $begin; $i <= $end; $i->modify('+1 day')){
			$controlflag = $i->format("Y-m-d").$control_time;
			$failed_time = $i->format("Y-m-d").$control_time;


			//falls Daten nicht existiren, wird am Anfang das fehlende Datum gesetzt
			$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}consumer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$controlflag'"); 

			if($datenvergleich ==NULL){ 
				$wpdb->update($wpdb->base_prefix.'consumer_'.$nachname.'_'.$ID, 
							  array(
								  'flag' => $failed_time
							  ),array( 'count' => $count));
				$count+=1;
			}
		}
	}
}




/***************************Clear flag "poweropti" after saving data**********************************/
//delete flags 01-07h
function sevz_delete_flag_function_0() {
	global $wpdb;


	//JSON-Daten "OptiPower" jedes Produzenten in Mysql speichern
	$produzent = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}producer_data` WHERE `link1` <> '' ");


	//alle Produzenten durchgehen
	foreach($produzent as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;

		$flagvergleich = $wpdb->get_results("SELECT `flag` FROM `{$wpdb->base_prefix}producer_{$nachname}_{$ID}` WHERE `flag` != 0");  
		if($flagvergleich){
			foreach($flagvergleich as $value){
				$flagprint = $value->flag;
				$yesterday_year = (new DateTime("$flagprint"))->format('Y');
				$yesterday_month = (new DateTime("$flagprint"))->format('m');
				$yesterday_day = (new DateTime("$flagprint"))->format('d');

				//JSON-Links der OptiPower für jede 6 Stunden ab 01:00 Uhr			
				$json_url0 = "https://$link@backend.powerfox.energy/api/2.0/my/all/report?year=$yesterday_year&month=$yesterday_month&day=$yesterday_day&fromhour=01";
				$json0= wp_remote_get( $json_url0 );

				if( is_wp_error( $json0 ) ) {
					return false; // Bail early
				}

				$body = wp_remote_retrieve_body( $json0 );

				$data0 = json_decode($body, TRUE);
				if( ! empty( $data0 ) ) {
					//Daten ab 01:00-07:00Uhr speichern
					for($i = count($data0['Consumption']['ReportValues'])-1; $i >=0; $i--){
						${'verbrauch_timestamp_'.$i} = $data0['Consumption']['ReportValues'][$i]['Timestamp'];
						${'verbrauch_delta_'.$i} = $data0['Consumption']['ReportValues'][$i]['Delta'];
						${'einspeisung_delta_'.$i} = $data0['FeedIn']['ReportValues'][$i]['Delta'];

						$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

						//falls Daten bereits existiren, werden nur neue eingefügt
						$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}producer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 

						if($datenvergleich ==NULL){ 
							$wpdb->insert($wpdb->base_prefix.'producer_'.$nachname.'_'.$ID, 
										  array(
											  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
											  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
											  'verbrauch_kwh' => ${'verbrauch_delta_'.$i},
											  'einspeisung_kwh' => ${'einspeisung_delta_'.$i}
										  ));

							$wpdb->update($wpdb->base_prefix.'producer_'.$nachname.'_'.$ID, 
										  array(
											  'flag' => 0
										  ),array( 'flag' => $flagprint));
						}
					}
				}
			}
		}
	}


	//JSON-Daten "OptiPower" jedes Prosumers in Mysql speichern
	$prosumer = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}prosumer_data` WHERE `link1` <> '' ");


	//alle Prosumer durchgehen
	foreach($prosumer as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;

		$flagvergleich = $wpdb->get_results("SELECT `flag` FROM `{$wpdb->base_prefix}prosumer_{$nachname}_{$ID}` WHERE `flag` != 0");  

		if($flagvergleich){
			foreach($flagvergleich as $value){
				$flagprint = $value->flag;
				$yesterday_year = (new DateTime("$flagprint"))->format('Y');
				$yesterday_month = (new DateTime("$flagprint"))->format('m');
				$yesterday_day = (new DateTime("$flagprint"))->format('d');

				//JSON-Links der OptiPower für jede 6 Stunden ab 01:00 Uhr			
				$json_url0 = "https://$link@backend.powerfox.energy/api/2.0/my/all/report?year=$yesterday_year&month=$yesterday_month&day=$yesterday_day&fromhour=01";
				$json0= wp_remote_get( $json_url0 );

				if( is_wp_error( $json0 ) ) {
					return false; // Bail early
				}

				$body = wp_remote_retrieve_body( $json0 );

				$data0 = json_decode($body, TRUE);
				if( ! empty( $data0 ) ) {


					//Daten ab 01:00-07:00Uhr speichern
					for($i = count($data0['Consumption']['ReportValues'])-1; $i >=0; $i--){
						${'verbrauch_timestamp_'.$i} = $data0['Consumption']['ReportValues'][$i]['Timestamp'];
						${'verbrauch_delta_'.$i} = $data0['Consumption']['ReportValues'][$i]['Delta'];
						${'einspeisung_delta_'.$i} = $data0['FeedIn']['ReportValues'][$i]['Delta'];

						$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

						//falls Daten bereits existiren, werden nur neue eingefügt
						$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}prosumer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 

						if($datenvergleich ==NULL){ 
							$wpdb->insert($wpdb->base_prefix.'prosumer_'.$nachname.'_'.$ID, 
										  array(
											  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
											  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
											  'verbrauch_kwh' => ${'verbrauch_delta_'.$i},
											  'einspeisung_kwh' => ${'einspeisung_delta_'.$i}
										  ));

							$wpdb->update($wpdb->base_prefix.'prosumer_'.$nachname.'_'.$ID, 
										  array(
											  'flag' => 0
										  ),array( 'flag' => $flagprint));
						}
					}
				}	
			}
		}
	}

	//JSON-Daten "OptiPower" jedes Verbrauchers in Mysql speichern
	$customer = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}consumer_data` WHERE `link1` <> '' ");


	//alle Verbraucher durchgehen
	foreach($customer as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;

		$flagvergleich = $wpdb->get_results("SELECT `flag` FROM `{$wpdb->base_prefix}consumer_{$nachname}_{$ID}` WHERE `flag` != 0");  
		if($flagvergleich){
			foreach($flagvergleich as $value){
				$flagprint = $value->flag;
				$yesterday_year = (new DateTime("$flagprint"))->format('Y');
				$yesterday_month = (new DateTime("$flagprint"))->format('m');
				$yesterday_day = (new DateTime("$flagprint"))->format('d');

				$json_url0 = "https://$link@backend.powerfox.energy/api/2.0/my/all/report?year=$yesterday_year&month=$yesterday_month&day=$yesterday_day&fromhour=01";
				$json0= wp_remote_get( $json_url0 );

				if( is_wp_error( $json0 ) ) {
					return false; // Bail early
				}

				$body = wp_remote_retrieve_body( $json0 );

				$data0 = json_decode($body, TRUE);
				if( ! empty( $data0 ) ) {

					//Daten ab 01:00-07:00Uhr speichern
					for($i = count($data0['Consumption']['ReportValues'])-1; $i >=0; $i--){
						${'verbrauch_timestamp_'.$i} = $data0['Consumption']['ReportValues'][$i]['Timestamp'];
						${'verbrauch_delta_'.$i} = $data0['Consumption']['ReportValues'][$i]['Delta'];

						$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

						//falls Daten bereits existiren, werden nur neue eingefügt
						$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}consumer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 

						if($datenvergleich ==NULL){ 
							$wpdb->insert($wpdb->base_prefix.'consumer_'.$nachname.'_'.$ID, 
										  array(
											  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
											  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
											  'verbrauch_kwh' => ${'verbrauch_delta_'.$i}
										  ));

							$wpdb->update($wpdb->base_prefix.'consumer_'.$nachname.'_'.$ID, 
										  array(
											  'flag' => 0
										  ),array( 'flag' => $flagprint));
						}
					}
				}
			}
		}
	}
}



//delete flags 07-13h
function sevz_delete_flag_function_1() {
	global $wpdb;

	//JSON-Daten "OptiPower" jedes Produzenten in Mysql speichern
	$produzent = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}producer_data` WHERE `link1` <> '' ");

	//alle Produzenten durchgehen
	foreach($produzent as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;

		$flagvergleich = $wpdb->get_results("SELECT `flag` FROM `{$wpdb->base_prefix}producer_{$nachname}_{$ID}` WHERE `flag` != 0");  
		if($flagvergleich){
			foreach($flagvergleich as $value){
				$flagprint = $value->flag;
				$yesterday_year = (new DateTime("$flagprint"))->format('Y');
				$yesterday_month = (new DateTime("$flagprint"))->format('m');
				$yesterday_day = (new DateTime("$flagprint"))->format('d');

				$json_url6 = "https://$link@backend.powerfox.energy/api/2.0/my/all/report?year=$yesterday_year&month=$yesterday_month&day=$yesterday_day&fromhour=07";
				$json6= wp_remote_get( $json_url6 );

				if( is_wp_error( $json6 ) ) {
					return false; // Bail early
				}

				$body = wp_remote_retrieve_body( $json6 );

				$data6 = json_decode($body, TRUE);
				if( ! empty( $data6 ) ) {

					//Daten ab 07:00-13:00Uhr speichern
					for($i = count($data6['Consumption']['ReportValues'])-1; $i >=0; $i--){
						${'verbrauch_timestamp_'.$i} = $data6['Consumption']['ReportValues'][$i]['Timestamp'];
						${'verbrauch_delta_'.$i} = $data6['Consumption']['ReportValues'][$i]['Delta'];
						${'einspeisung_delta_'.$i} = $data6['FeedIn']['ReportValues'][$i]['Delta'];

						$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

						//falls Daten bereits existiren, werden nur neue eingefügt
						$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}producer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 

						if($datenvergleich ==NULL){ 
							$wpdb->insert($wpdb->base_prefix.'producer_'.$nachname.'_'.$ID, 
										  array(
											  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
											  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
											  'verbrauch_kwh' => ${'verbrauch_delta_'.$i},
											  'einspeisung_kwh' => ${'einspeisung_delta_'.$i}
										  ));
							$wpdb->update($wpdb->base_prefix.'producer_'.$nachname.'_'.$ID, 
										  array(
											  'flag' => 0
										  ),array( 'flag' => $flagprint));
						}
					}
				}
			}
		}
	}

	//JSON-Daten "OptiPower" jedes Prosumers in Mysql speichern
	$prosumer = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}prosumer_data` WHERE `link1` <> '' ");


	//alle Prosumer durchgehen
	foreach($prosumer as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;


		$flagvergleich = $wpdb->get_results("SELECT `flag` FROM `{$wpdb->base_prefix}prosumer_{$nachname}_{$ID}` WHERE `flag` != 0");  
		if($flagvergleich){
			foreach($flagvergleich as $value){
				$flagprint = $value->flag;
				$yesterday_year = (new DateTime("$flagprint"))->format('Y');
				$yesterday_month = (new DateTime("$flagprint"))->format('m');
				$yesterday_day = (new DateTime("$flagprint"))->format('d');

				$json_url6 = "https://$link@backend.powerfox.energy/api/2.0/my/all/report?year=$yesterday_year&month=$yesterday_month&day=$yesterday_day&fromhour=07";
				$json6= wp_remote_get( $json_url6 );

				if( is_wp_error( $json6 ) ) {
					return false; // Bail early
				}

				$body = wp_remote_retrieve_body( $json6 );

				$data6 = json_decode($body, TRUE);
				if( ! empty( $data6 ) ) {

					//Daten ab 07:00-13:00Uhr speichern
					for($i = count($data6['Consumption']['ReportValues'])-1; $i >=0; $i--){
						${'verbrauch_timestamp_'.$i} = $data6['Consumption']['ReportValues'][$i]['Timestamp'];
						${'verbrauch_delta_'.$i} = $data6['Consumption']['ReportValues'][$i]['Delta'];
						${'einspeisung_delta_'.$i} = $data6['FeedIn']['ReportValues'][$i]['Delta'];

						$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

						//falls Daten bereits existiren, werden nur neue eingefügt
						$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}prosumer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 

						if($datenvergleich ==NULL){ 
							$wpdb->insert($wpdb->base_prefix.'prosumer_'.$nachname.'_'.$ID, 
										  array(
											  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
											  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
											  'verbrauch_kwh' => ${'verbrauch_delta_'.$i},
											  'einspeisung_kwh' => ${'einspeisung_delta_'.$i}
										  ));

							$wpdb->update($wpdb->base_prefix.'prosumer_'.$nachname.'_'.$ID, 
										  array(
											  'flag' => 0
										  ),array( 'flag' => $flagprint));
						}
					}
				}
			}
		}
	}
	//JSON-Daten "OptiPower" jedes Verbrauchers in Mysql speichern
	$customer = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}consumer_data` WHERE `link1` <> '' ");


	//alle Verbraucher durchgehen
	foreach($customer as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;

		$flagvergleich = $wpdb->get_results("SELECT `flag` FROM `{$wpdb->base_prefix}consumer_{$nachname}_{$ID}` WHERE `flag` != 0");  
		if($flagvergleich){
			foreach($flagvergleich as $value){
				$flagprint = $value->flag;
				$yesterday_year = (new DateTime("$flagprint"))->format('Y');
				$yesterday_month = (new DateTime("$flagprint"))->format('m');
				$yesterday_day = (new DateTime("$flagprint"))->format('d');


				$json_url6 = "https://$link@backend.powerfox.energy/api/2.0/my/all/report?year=$yesterday_year&month=$yesterday_month&day=$yesterday_day&fromhour=07";
				$json6= wp_remote_get( $json_url6 );

				if( is_wp_error( $json6 ) ) {
					return false; // Bail early
				}

				$body = wp_remote_retrieve_body( $json6 );

				$data6 = json_decode($body, TRUE);
				if( ! empty( $data6 ) ) {

					//Daten ab 07:00-13:00Uhr speichern
					for($i = count($data6['Consumption']['ReportValues'])-1; $i >=0; $i--){
						${'verbrauch_timestamp_'.$i} = $data6['Consumption']['ReportValues'][$i]['Timestamp'];
						${'verbrauch_delta_'.$i} = $data6['Consumption']['ReportValues'][$i]['Delta'];

						$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

						//falls Daten bereits existiren, werden nur neue eingefügt
						$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}consumer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 

						if($datenvergleich ==NULL){ 
							$wpdb->insert($wpdb->base_prefix.'consumer_'.$nachname.'_'.$ID, 
										  array(
											  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
											  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
											  'verbrauch_kwh' => ${'verbrauch_delta_'.$i}
										  ));
							$wpdb->update($wpdb->base_prefix.'consumer_'.$nachname.'_'.$ID, 
										  array(
											  'flag' => 0
										  ),array( 'flag' => $flagprint));
						}
					}
				}
			}
		}
	}
}





//Delete Flags 13-19h
function sevz_delete_flag_function_2() {
	global $wpdb;


	//JSON-Daten "OptiPower" jedes Produzenten in Mysql speichern
	$produzent = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}producer_data` WHERE `link1` <> '' ");

	//alle Produzenten durchgehen
	foreach($produzent as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;


		$flagvergleich = $wpdb->get_results("SELECT `flag` FROM `{$wpdb->base_prefix}producer_{$nachname}_{$ID}` WHERE `flag` != 0");  
		if($flagvergleich){
			foreach($flagvergleich as $value){
				$flagprint = $value->flag;
				$yesterday_year = (new DateTime("$flagprint"))->format('Y');
				$yesterday_month = (new DateTime("$flagprint"))->format('m');
				$yesterday_day = (new DateTime("$flagprint"))->format('d');

				$json_url12 = "https://$link@backend.powerfox.energy/api/2.0/my/all/report?year=$yesterday_year&month=$yesterday_month&day=$yesterday_day&fromhour=13";
				$json12= wp_remote_get( $json_url12 );

				if( is_wp_error( $json12 ) ) {
					return false; // Bail early
				}

				$body = wp_remote_retrieve_body( $json12 );

				$data12 = json_decode($body, TRUE);
				if( ! empty( $data12 ) ) {

					//Daten ab 13:00-19:00Uhr speichern
					for($i = count($data12['Consumption']['ReportValues'])-1; $i >=0; $i--){
						${'verbrauch_timestamp_'.$i} = $data12['Consumption']['ReportValues'][$i]['Timestamp'];
						${'verbrauch_delta_'.$i} = $data12['Consumption']['ReportValues'][$i]['Delta'];
						${'einspeisung_delta_'.$i} = $data12['FeedIn']['ReportValues'][$i]['Delta'];

						$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

						//falls Daten bereits existiren, werden nur neue eingefügt
						$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}producer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'");

						if($datenvergleich ==NULL){ 
							$wpdb->insert($wpdb->base_prefix.'producer_'.$nachname.'_'.$ID, 
										  array(
											  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
											  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
											  'verbrauch_kwh' => ${'verbrauch_delta_'.$i},
											  'einspeisung_kwh' => ${'einspeisung_delta_'.$i}
										  ));
							$wpdb->update($wpdb->base_prefix.'producer_'.$nachname.'_'.$ID, 
										  array(
											  'flag' => 0
										  ),array( 'flag' => $flagprint));
						}
					}
				}
			}
		}
	}

	//JSON-Daten "OptiPower" jedes Prosumers in Mysql speichern
	$prosumer = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}prosumer_data` WHERE `link1` <> '' ");


	//alle Prosumer durchgehen
	foreach($prosumer as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;


		$flagvergleich = $wpdb->get_results("SELECT `flag` FROM `{$wpdb->base_prefix}prosumer_{$nachname}_{$ID}` WHERE `flag` != 0");  

		if($flagvergleich){
			foreach($flagvergleich as $value){
				$flagprint = $value->flag;
				$yesterday_year = (new DateTime("$flagprint"))->format('Y');
				$yesterday_month = (new DateTime("$flagprint"))->format('m');
				$yesterday_day = (new DateTime("$flagprint"))->format('d');
				$json_url12 = "https://$link@backend.powerfox.energy/api/2.0/my/all/report?year=$yesterday_year&month=$yesterday_month&day=$yesterday_day&fromhour=13";
				$json12= wp_remote_get( $json_url12 );

				if( is_wp_error( $json12 ) ) {
					return false; // Bail early
				}

				$body = wp_remote_retrieve_body( $json12 );

				$data12 = json_decode($body, TRUE);
				if( ! empty( $data12 ) ) {

					//Daten ab 13:00-19:00Uhr speichern
					for($i = count($data12['Consumption']['ReportValues'])-1; $i >=0; $i--){
						${'verbrauch_timestamp_'.$i} = $data12['Consumption']['ReportValues'][$i]['Timestamp'];
						${'verbrauch_delta_'.$i} = $data12['Consumption']['ReportValues'][$i]['Delta'];
						${'einspeisung_delta_'.$i} = $data12['FeedIn']['ReportValues'][$i]['Delta'];

						$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

						//falls Daten bereits existiren, werden nur neue eingefügt
						$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}prosumer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'");

						if($datenvergleich ==NULL){ 
							$wpdb->insert($wpdb->base_prefix.'prosumer_'.$nachname.'_'.$ID, 
										  array(
											  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
											  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
											  'verbrauch_kwh' => ${'verbrauch_delta_'.$i},
											  'einspeisung_kwh' => ${'einspeisung_delta_'.$i}
										  ));
							$wpdb->update($wpdb->base_prefix.'prosumer_'.$nachname.'_'.$ID, 
										  array(
											  'flag' => 0
										  ),array( 'flag' => $flagprint));
						}
					}
				}
			}
		}
	}

	//JSON-Daten "OptiPower" jedes Verbrauchers in Mysql speichern
	$customer = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}consumer_data` WHERE `link1` <> '' ");


	//alle Verbraucher durchgehen
	foreach($customer as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;

		$flagvergleich = $wpdb->get_results("SELECT `flag` FROM `{$wpdb->base_prefix}consumer_{$nachname}_{$ID}` WHERE `flag` != 0");  
		if($flagvergleich){
			foreach($flagvergleich as $value){
				$flagprint = $value->flag;
				$yesterday_year = (new DateTime("$flagprint"))->format('Y');
				$yesterday_month = (new DateTime("$flagprint"))->format('m');
				$yesterday_day = (new DateTime("$flagprint"))->format('d');

				$json_url12 = "https://$link@backend.powerfox.energy/api/2.0/my/all/report?year=$yesterday_year&month=$yesterday_month&day=$yesterday_day&fromhour=13";
				$json12= wp_remote_get( $json_url12 );

				if( is_wp_error( $json12 ) ) {
					return false; // Bail early
				}

				$body = wp_remote_retrieve_body( $json12 );

				$data12 = json_decode($body, TRUE);
				if( ! empty( $data12 ) ) {

					//Daten ab 13:00-19:00Uhr speichern
					for($i = count($data12['Consumption']['ReportValues'])-1; $i >=0; $i--){
						${'verbrauch_timestamp_'.$i} = $data12['Consumption']['ReportValues'][$i]['Timestamp'];
						${'verbrauch_delta_'.$i} = $data12['Consumption']['ReportValues'][$i]['Delta'];

						$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

						//falls Daten bereits existiren, werden nur neue eingefügt
						$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}consumer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'");

						if($datenvergleich ==NULL){ 
							$wpdb->insert($wpdb->base_prefix.'consumer_'.$nachname.'_'.$ID, 
										  array(
											  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
											  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
											  'verbrauch_kwh' => ${'verbrauch_delta_'.$i}
										  ));
							$wpdb->update($wpdb->base_prefix.'consumer_'.$nachname.'_'.$ID, 
										  array(
											  'flag' => 0
										  ),array( 'flag' => $flagprint));
						}
					}
				}
			}
		}
	}
}







//Delete flags 19-01h
function sevz_delete_flag_function_3() {
	global $wpdb;


	//JSON-Daten "OptiPower" jedes Produzenten in Mysql speichern
	$produzent = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}producer_data` WHERE `link1` <> '' ");

	//alle Produzenten durchgehen
	foreach($produzent as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;

		$flagvergleich = $wpdb->get_results("SELECT `flag` FROM `{$wpdb->base_prefix}producer_{$nachname}_{$ID}` WHERE `flag` != 0");  
		if($flagvergleich){
			foreach($flagvergleich as $value){
				$flagprint = $value->flag;
				$aDayPrev = date('Y-m-d', strtotime( $flagprint . " -1 days"));
				$yesterday_year = (new DateTime("$aDayPrev"))->format('Y');
				$yesterday_month = (new DateTime("$aDayPrev"))->format('m');
				$yesterday_day = (new DateTime("$aDayPrev"))->format('d');


				$json_url18 = "https://$link@backend.powerfox.energy/api/2.0/my/all/report?year=$yesterday_year&month=$yesterday_month&day=$yesterday_day&fromhour=19";
				$json18= wp_remote_get( $json_url18 );

				if( is_wp_error( $json18 ) ) {
					return false; // Bail early
				}

				$body = wp_remote_retrieve_body( $json18 );

				$data18 = json_decode($body, TRUE);
				if( ! empty( $data18 ) ) {


					//Daten ab 19:00-01:00Uhr speichern
					for($i = count($data18['Consumption']['ReportValues'])-1; $i >=0; $i--){
						${'verbrauch_timestamp_'.$i} = $data18['Consumption']['ReportValues'][$i]['Timestamp'];
						${'verbrauch_delta_'.$i} = $data18['Consumption']['ReportValues'][$i]['Delta'];
						${'einspeisung_delta_'.$i} = $data18['FeedIn']['ReportValues'][$i]['Delta'];

						$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

						//falls Daten bereits existiren, werden nur neue eingefügt
						$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}producer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 

						if($datenvergleich ==NULL){ 
							$wpdb->insert($wpdb->base_prefix.'producer_'.$nachname.'_'.$ID, 
										  array(
											  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
											  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
											  'verbrauch_kwh' => ${'verbrauch_delta_'.$i},
											  'einspeisung_kwh' => ${'einspeisung_delta_'.$i}
										  ));
							$wpdb->update($wpdb->base_prefix.'producer_'.$nachname.'_'.$ID, 
										  array(
											  'flag' => 0
										  ),array( 'flag' => $flagprint));

						}
					}

				}	
			}
		}
	}



	//JSON-Daten "OptiPower" jedes Prosumers in Mysql speichern
	$prosumer = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}prosumer_data` WHERE `link1` <> '' ");


	//alle Prosumer durchgehen
	foreach($prosumer as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;

		$flagvergleich = $wpdb->get_results("SELECT `flag` FROM `{$wpdb->base_prefix}prosumer_{$nachname}_{$ID}` WHERE `flag` != 0");  
		if($flagvergleich){
			foreach($flagvergleich as $value){
				$flagprint = $value->flag;
				$aDayPrev = date('Y-m-d', strtotime( $flagprint . " -1 days"));
				$yesterday_year = (new DateTime("$aDayPrev"))->format('Y');
				$yesterday_month = (new DateTime("$aDayPrev"))->format('m');
				$yesterday_day = (new DateTime("$aDayPrev"))->format('d');

				$json_url18 = "https://$link@backend.powerfox.energy/api/2.0/my/all/report?year=$yesterday_year&month=$yesterday_month&day=$yesterday_day&fromhour=19";
				$json18= wp_remote_get( $json_url18 );

				if( is_wp_error( $json18 ) ) {
					return false; // Bail early
				}

				$body = wp_remote_retrieve_body( $json18 );

				$data18 = json_decode($body, TRUE);
				if( ! empty( $data18 ) ) {



					//Daten ab 19:00-01:00Uhr speichern
					for($i = count($data18['Consumption']['ReportValues'])-1; $i >=0; $i--){
						${'verbrauch_timestamp_'.$i} = $data18['Consumption']['ReportValues'][$i]['Timestamp'];
						${'verbrauch_delta_'.$i} = $data18['Consumption']['ReportValues'][$i]['Delta'];
						${'einspeisung_delta_'.$i} = $data18['FeedIn']['ReportValues'][$i]['Delta'];

						$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

						//falls Daten bereits existiren, werden nur neue eingefügt
						$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}prosumer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 

						if($datenvergleich ==NULL){ 
							$wpdb->insert($wpdb->base_prefix.'prosumer_'.$nachname.'_'.$ID, 
										  array(
											  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
											  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
											  'verbrauch_kwh' => ${'verbrauch_delta_'.$i},
											  'einspeisung_kwh' => ${'einspeisung_delta_'.$i}
										  ));

							$wpdb->update($wpdb->base_prefix.'prosumer_'.$nachname.'_'.$ID, 
										  array(
											  'flag' => 0
										  ),array( 'flag' => $flagprint));
						}
					}
				}	
			}
		}
	}


	//JSON-Daten "OptiPower" jedes Verbrauchers in Mysql speichern
	$customer = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}consumer_data` WHERE `link1` <> '' ");


	//alle Verbraucher durchgehen
	foreach($customer as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;

		$flagvergleich = $wpdb->get_results("SELECT `flag` FROM `{$wpdb->base_prefix}consumer_{$nachname}_{$ID}` WHERE `flag` != 0");  
		if($flagvergleich){
			foreach($flagvergleich as $value){
				$flagprint = $value->flag;
				$aDayPrev = date('Y-m-d', strtotime( $flagprint . " -1 days"));			
				$yesterday_year = (new DateTime("$aDayPrev"))->format('Y');
				$yesterday_month = (new DateTime("$aDayPrev"))->format('m');
				$yesterday_day = (new DateTime("$aDayPrev"))->format('d');

				//JSON-Links der OptiPower für jede 6 Stunden ab 19:00 Uhr			
				$json_url18 = "https://$link@backend.powerfox.energy/api/2.0/my/all/report?year=$yesterday_year&month=$yesterday_month&day=$yesterday_day&fromhour=19";
				$json18= wp_remote_get( $json_url18 );

				if( is_wp_error( $json18 ) ) {
					return false; // Bail early
				}

				$body = wp_remote_retrieve_body( $json18 );

				$data18 = json_decode($body, TRUE);
				if( ! empty( $data18 ) ) {


					//Daten ab 19:00-01:00Uhr speichern
					for($i = count($data18['Consumption']['ReportValues'])-1; $i >=0; $i--){
						${'verbrauch_timestamp_'.$i} = $data18['Consumption']['ReportValues'][$i]['Timestamp'];
						${'verbrauch_delta_'.$i} = $data18['Consumption']['ReportValues'][$i]['Delta'];

						$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

						//falls Daten bereits existiren, werden nur neue eingefügt
						$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}consumer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 

						if($datenvergleich ==NULL){ 
							$wpdb->insert($wpdb->base_prefix.'consumer_'.$nachname.'_'.$ID, 
										  array(
											  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
											  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
											  'verbrauch_kwh' => ${'verbrauch_delta_'.$i}
										  ));

							$wpdb->update($wpdb->base_prefix.'consumer_'.$nachname.'_'.$ID, 
										  array(
											  'flag' => 0
										  ),array( 'flag' => $flagprint));
						}
					}
				}	
			}
		}
	}
}




//Speicherung im Stunden-Takt
function sevz_get_json_data_function_day() {
	global $wpdb;

	$year = date("Y");
	$month = date("m");
	$today = date("Y-m-d\T00");


	//new yesterday-calculation at 01.09.2021
	$yesterday_calculate = date('Y-m-d', strtotime("-1 days"));
	$yesterday_year = (new DateTime("$yesterday_calculate"))->format('Y');
	$yesterday_month = (new DateTime("$yesterday_calculate"))->format('m');
	$yesterday_day = (new DateTime("$yesterday_calculate"))->format('d');

	//JSON-Daten "OptiPower" jedes Produzenten in Mysql speichern
	$produzent = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}producer_data` WHERE `link1` <> ''");

	//alle Produzenten durchgehen
	foreach($produzent as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;


		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$tablename = 'producer_day_'.$nachname.'_'.$ID; 

		//für jeden Produzenten Tabelle kreieren
		$sql = "CREATE TABLE `{$wpdb->base_prefix}{$tablename}`(
  			count mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			erstellt_am_tag datetime,
			zeitstempel datetime,
			verbrauch_kwh float(24),
  			einspeisung_kwh float(24),
			flag datetime
		) $charset_collate;";

		//falls Tabelle nicht existiert, wird eine creiert
		maybe_create_table( $wpdb->prefix . $tablename, $sql );

		$json_url18 = "https://$link@backend.powerfox.energy/api/2.0/my/all/report?year=$yesterday_year&month=$yesterday_month&day=$yesterday_day";
		$json18= wp_remote_get( $json_url18 );

		if( is_wp_error( $json18 ) ) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $json18 );

		$data18 = json_decode($body, TRUE);
		if( ! empty( $data18 ) ) {


			//Daten für den Vortag/Stunde speichern
			for($i = count($data18['Consumption']['ReportValues'])-1; $i >=0; $i--){
				${'verbrauch_timestamp_'.$i} = $data18['Consumption']['ReportValues'][$i]['Timestamp'];
				${'verbrauch_delta_'.$i} = $data18['Consumption']['ReportValues'][$i]['Delta'];
				${'einspeisung_delta_'.$i} = $data18['FeedIn']['ReportValues'][$i]['Delta'];

				$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

				//falls Daten bereits existiren, werden nur neue eingefügt
				$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}producer_day_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 

				if($datenvergleich ==NULL){ 
					$wpdb->insert($wpdb->base_prefix.'producer_day_'.$nachname.'_'.$ID, 
								  array(
									  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
									  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
									  'verbrauch_kwh' => ${'verbrauch_delta_'.$i},
									  'einspeisung_kwh' => ${'einspeisung_delta_'.$i}
								  ));
				}
			}	
		}
	}




	//JSON-Daten "OptiPower" jedes Prosumers in Mysql speichern
	$prosumer = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}prosumer_data` WHERE `link1` <> '' ");


	//alle Prosumer durchgehen
	foreach($prosumer as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;


		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$tablename = 'prosumer_day_'.$nachname.'_'.$ID; 

		//für jeden Produzenten Tabelle kreieren
		$sql = "CREATE TABLE `{$wpdb->base_prefix}{$tablename}`(
  			count mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			erstellt_am_tag datetime,
			zeitstempel datetime,
			verbrauch_kwh float(24),
  			einspeisung_kwh float(24),
			flag datetime
		) $charset_collate;";

		//falls Tabelle nicht existiert, wird eine creiert
		maybe_create_table( $wpdb->prefix . $tablename, $sql );

		$json_url18 = "https://$link@backend.powerfox.energy/api/2.0/my/all/report?year=$yesterday_year&month=$yesterday_month&day=$yesterday_day";
		$json18= wp_remote_get( $json_url18 );

		if( is_wp_error( $json18 ) ) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $json18 );

		$data18 = json_decode($body, TRUE);
		if( ! empty( $data18 ) ) {

			for($i = count($data18['Consumption']['ReportValues'])-1; $i >=0; $i--){
				${'verbrauch_timestamp_'.$i} = $data18['Consumption']['ReportValues'][$i]['Timestamp'];
				${'verbrauch_delta_'.$i} = $data18['Consumption']['ReportValues'][$i]['Delta'];
				${'einspeisung_delta_'.$i} = $data18['FeedIn']['ReportValues'][$i]['Delta'];

				$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

				//falls Daten bereits existiren, werden nur neue eingefügt
				$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}prosumer_day_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 

				if($datenvergleich ==NULL){ 
					$wpdb->insert($wpdb->base_prefix.'prosumer_day_'.$nachname.'_'.$ID, 
								  array(
									  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
									  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
									  'verbrauch_kwh' => ${'verbrauch_delta_'.$i},
									  'einspeisung_kwh' => ${'einspeisung_delta_'.$i}
								  ));
				}
			}	
		}
	}



	//JSON-Daten "OptiPower" jedes Verbrauchers in Mysql speichern
	$customer = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}consumer_data` WHERE `link1` <> '' ");


	//alle Verbraucher durchgehen
	foreach($customer as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$tablename = 'consumer_day_'.$nachname.'_'.$ID; 

		//für jeden Consumer Tabelle kreieren
		$sql = "CREATE TABLE `{$wpdb->base_prefix}{$tablename}`(
  			count mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			erstellt_am_tag datetime,
			zeitstempel datetime,
			verbrauch_kwh float(24),
  			einspeisung_kwh float(24),
			flag datetime
		) $charset_collate;";

		//falls Tabelle nicht existiert, wird eine creiert
		maybe_create_table( $wpdb->prefix . $tablename, $sql );

		//JSON-Link Poweropti
		$json_url18 = "https://$link@backend.powerfox.energy/api/2.0/my/all/report?year=$yesterday_year&month=$yesterday_month&day=$yesterday_day";
		$json18= wp_remote_get( $json_url18 );

		if( is_wp_error( $json18 ) ) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $json18 );

		$data18 = json_decode($body, TRUE);
		if( ! empty( $data18 ) ) {

			for($i = count($data18['Consumption']['ReportValues'])-1; $i >=0; $i--){
				${'verbrauch_timestamp_'.$i} = $data18['Consumption']['ReportValues'][$i]['Timestamp'];
				${'verbrauch_delta_'.$i} = $data18['Consumption']['ReportValues'][$i]['Delta'];

				$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

				//falls Daten bereits existiren, werden nur neue eingefügt
				$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}consumer_day_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 

				if($datenvergleich ==NULL){ 
					$wpdb->insert($wpdb->base_prefix.'consumer_day_'.$nachname.'_'.$ID, 
								  array(
									  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
									  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
									  'verbrauch_kwh' => ${'verbrauch_delta_'.$i}
								  ));
				}
			}	
		}
	}
}





//Speicherung im Tages-Takt
function sevz_get_json_data_function_month() {
	global $wpdb;

	$year = date("Y");
	$month = date("m");
	$today = date("Y-m-d\T00");


	//new yesterday-calculation at 01.09.2021
	$yesterday_calculate = date('Y-m-d', strtotime("-1 days"));
	$yesterday_year = (new DateTime("$yesterday_calculate"))->format('Y');
	$yesterday_month = (new DateTime("$yesterday_calculate"))->format('m');
	$yesterday_day = (new DateTime("$yesterday_calculate"))->format('d');

	//JSON-Daten "OptiPower" jedes Produzenten in Mysql speichern
	$produzent = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}producer_data` WHERE `link1` <> '' ");

	//alle Produzenten durchgehen
	foreach($produzent as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;


		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$tablename = 'producer_month_'.$nachname.'_'.$ID; 

		//für jeden Consumer Tabelle kreieren
		$sql = "CREATE TABLE `{$wpdb->base_prefix}{$tablename}`(
  			count mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			erstellt_am_tag datetime,
			zeitstempel datetime,
			verbrauch_kwh float(24),
  			einspeisung_kwh float(24),
			flag datetime
		) $charset_collate;";

		//falls Tabelle nicht existiert, wird eine creiert
		maybe_create_table( $wpdb->prefix . $tablename, $sql );

		$json_url18 = "https://$link@backend.powerfox.energy/api/2.0/my/all/report?year=$year&month=$month";
		$json18= wp_remote_get( $json_url18 );

		if( is_wp_error( $json18 ) ) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $json18 );

		$data18 = json_decode($body, TRUE);
		if( ! empty( $data18 ) ) {


			for($i = count($data18['Consumption']['ReportValues'])-1; $i >=0; $i--){
				${'verbrauch_timestamp_'.$i} = $data18['Consumption']['ReportValues'][$i]['Timestamp'];
				${'verbrauch_delta_'.$i} = $data18['Consumption']['ReportValues'][$i]['Delta'];
				${'einspeisung_delta_'.$i} = $data18['FeedIn']['ReportValues'][$i]['Delta'];

				$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

				//falls Daten bereits existiren, werden nur neue eingefügt
				$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}producer_month_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 

				if($datenvergleich ==NULL){ 


					$wpdb->insert($wpdb->base_prefix.'producer_month_'.$nachname.'_'.$ID, 
								  array(
									  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
									  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
									  'verbrauch_kwh' => ${'verbrauch_delta_'.$i},
									  'einspeisung_kwh' => ${'einspeisung_delta_'.$i}
								  ));
				}else{
					$wpdb->update($wpdb->base_prefix.'producer_month_'.$nachname.'_'.$ID, 
								  array(
									  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
									  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
									  'verbrauch_kwh' => ${'verbrauch_delta_'.$i},
									  'einspeisung_kwh' => ${'einspeisung_delta_'.$i}
								  ),array( 'zeitstempel' => $temp_time ));


				}
			}	
		}
	}




	//JSON-Daten "OptiPower" jedes Prosumers in Mysql speichern
	$prosumer = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}prosumer_data` WHERE `link1` <> '' ");


	//alle Prosumer durchgehen
	foreach($prosumer as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;


		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$tablename = 'prosumer_month_'.$nachname.'_'.$ID; 

		//für jeden Prosumer Tabelle kreieren
		$sql = "CREATE TABLE `{$wpdb->base_prefix}{$tablename}`(
 			count mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			erstellt_am_tag datetime,
			zeitstempel datetime,
			verbrauch_kwh float(24),
			verbrauch_ht_kwh float(24),
			verbrauch_nt_kwh float(24),		
  			einspeisung_kwh float(24),
			flag datetime
		) $charset_collate;";

		//falls Tabelle nicht existiert, wird eine creiert
		maybe_create_table( $wpdb->prefix . $tablename, $sql );


		$json_url18 = "https://$link@backend.powerfox.energy/api/2.0/my/all/report?year=$year&month=$month";
		$json18= wp_remote_get( $json_url18 );

		if( is_wp_error( $json18 ) ) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $json18 );

		$data18 = json_decode($body, TRUE);
		if( ! empty( $data18 ) ) {

			$arbeitspreis_ht = $data18['Consumption']['ReportValues'][0]['DeltaHT'];
			if(!empty($arbeitspreis_ht)){
				for($i = count($data18['Consumption']['ReportValues'])-1; $i >=0; $i--){
					${'verbrauch_delta_ht'.$i} = $data18['Consumption']['ReportValues'][$i]['DeltaHT'];
					${'verbrauch_delta_nt'.$i} = $data18['Consumption']['ReportValues'][$i]['DeltaNT'];
					${'verbrauch_timestamp_'.$i} = $data18['Consumption']['ReportValues'][$i]['Timestamp'];
					${'verbrauch_delta_'.$i} = $data18['Consumption']['ReportValues'][$i]['Delta'];
					${'einspeisung_delta_'.$i} = $data18['FeedIn']['ReportValues'][$i]['Delta'];

					$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

					//falls Daten bereits existiren, werden nur neue eingefügt
					$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}prosumer_month_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 
					if($datenvergleich ==NULL){ 
						$wpdb->insert($wpdb->base_prefix.'prosumer_month_'.$nachname.'_'.$ID, 
									  array(
										  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
										  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
										  'verbrauch_kwh' => ${'verbrauch_delta_'.$i},
										  'verbrauch_ht_kwh' => ${'verbrauch_delta_ht'.$i},
										  'verbrauch_nt_kwh' => ${'verbrauch_delta_nt'.$i},
										  'einspeisung_kwh' => ${'einspeisung_delta_'.$i}
									  ));
					}else{
						$wpdb->update($wpdb->base_prefix.'prosumer_month_'.$nachname.'_'.$ID, 
									  array(
										  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
										  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
										  'verbrauch_kwh' => ${'verbrauch_delta_'.$i},
										  'verbrauch_ht_kwh' => ${'verbrauch_delta_ht'.$i},
										  'verbrauch_nt_kwh' => ${'verbrauch_delta_nt'.$i},
										  'einspeisung_kwh' => ${'einspeisung_delta_'.$i}
									  ),array( 'zeitstempel' => $temp_time ));
					}
				}

			}else{

				for($i = count($data18['Consumption']['ReportValues'])-1; $i >=0; $i--){
					${'verbrauch_timestamp_'.$i} = $data18['Consumption']['ReportValues'][$i]['Timestamp'];
					${'verbrauch_delta_'.$i} = $data18['Consumption']['ReportValues'][$i]['Delta'];
					${'einspeisung_delta_'.$i} = $data18['FeedIn']['ReportValues'][$i]['Delta'];

					$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

					//falls Daten bereits existiren, werden nur neue eingefügt
					$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}prosumer_month_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 

					if($datenvergleich ==NULL){ 
						$wpdb->insert($wpdb->base_prefix.'prosumer_month_'.$nachname.'_'.$ID, 
									  array(
										  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
										  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
										  'verbrauch_kwh' => ${'verbrauch_delta_'.$i},
										  'einspeisung_kwh' => ${'einspeisung_delta_'.$i}
									  ));
					}else{
						$wpdb->update($wpdb->base_prefix.'prosumer_month_'.$nachname.'_'.$ID, 
									  array(
										  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
										  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
										  'verbrauch_kwh' => ${'verbrauch_delta_'.$i},
										  'einspeisung_kwh' => ${'einspeisung_delta_'.$i}
									  ),array( 'zeitstempel' => $temp_time ));
					}
				}	
			}
		}
	}


	//JSON-Daten "OptiPower" jedes Verbrauchers in Mysql speichern
	$customer = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}consumer_data` WHERE `link1` <> '' ");


	//alle Verbraucher durchgehen
	foreach($customer as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;


		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$tablename = 'consumer_month_'.$nachname.'_'.$ID; 

		//für jeden Prosumer Tabelle kreieren
		$sql = "CREATE TABLE `{$wpdb->base_prefix}{$tablename}`(
 			count mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			erstellt_am_tag datetime,
			zeitstempel datetime,
			verbrauch_kwh float(24),
			verbrauch_ht_kwh float(24),
			verbrauch_nt_kwh float(24),		
  			einspeisung_kwh float(24),
			flag datetime
		) $charset_collate;";

		//falls Tabelle nicht existiert, wird eine creiert
		maybe_create_table( $wpdb->prefix . $tablename, $sql );

		//JSON-Links der OptiPower für jede 6 Stunden ab 01:00 Uhr			
		$json_url18 = "https://$link@backend.powerfox.energy/api/2.0/my/all/report?year=$year&month=$month";
		$json18= wp_remote_get( $json_url18 );

		if( is_wp_error( $json18 ) ) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $json18 );

		$data18 = json_decode($body, TRUE);
		if( ! empty( $data18 ) ) {


			$arbeitspreis_ht = $data18['Consumption']['ReportValues'][0]['DeltaHT'];
			if(!empty($arbeitspreis_ht)){
				for($i = count($data18['Consumption']['ReportValues'])-1; $i >=0; $i--){
					${'verbrauch_delta_ht'.$i} = $data18['Consumption']['ReportValues'][$i]['DeltaHT'];
					${'verbrauch_delta_nt'.$i} = $data18['Consumption']['ReportValues'][$i]['DeltaNT'];
					${'verbrauch_timestamp_'.$i} = $data18['Consumption']['ReportValues'][$i]['Timestamp'];

					$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

					//falls Daten bereits existiren, werden nur neue eingefügt
					$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}consumer_month_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 
					if($datenvergleich ==NULL){ 
						$wpdb->insert($wpdb->base_prefix.'consumer_month_'.$nachname.'_'.$ID, 
									  array(
										  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
										  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
										  'verbrauch_kwh' => ${'verbrauch_delta_'.$i},
										  'verbrauch_ht_kwh' => ${'verbrauch_delta_ht'.$i},
										  'verbrauch_nt_kwh' => ${'verbrauch_delta_nt'.$i}
									  ));
					}else{
						$wpdb->update($wpdb->base_prefix.'consumer_month_'.$nachname.'_'.$ID, 
									  array(
										  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
										  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
										  'verbrauch_kwh' => ${'verbrauch_delta_'.$i},
										  'verbrauch_ht_kwh' => ${'verbrauch_delta_ht'.$i},
										  'verbrauch_nt_kwh' => ${'verbrauch_delta_nt'.$i}
									  ),array( 'zeitstempel' => $temp_time ));
					}
				}

			}else{
				for($i = count($data18['Consumption']['ReportValues'])-1; $i >=0; $i--){
					${'verbrauch_timestamp_'.$i} = $data18['Consumption']['ReportValues'][$i]['Timestamp'];
					${'verbrauch_delta_'.$i} = $data18['Consumption']['ReportValues'][$i]['Delta'];

					$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

					//falls Daten bereits existiren, werden nur neue eingefügt
					$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}consumer_month_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 

					if($datenvergleich ==NULL){ 
						$wpdb->insert($wpdb->base_prefix.'consumer_month_'.$nachname.'_'.$ID, 
									  array(
										  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
										  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
										  'verbrauch_kwh' => ${'verbrauch_delta_'.$i}
									  ));
					}else{
						$wpdb->update($wpdb->base_prefix.'consumer_month_'.$nachname.'_'.$ID, 
									  array(
										  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
										  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
										  'verbrauch_kwh' => ${'verbrauch_delta_'.$i}
									  ),array( 'zeitstempel' => $temp_time ));
					}
				}
			}	
		}
	}
}










//Speicherung im Monats-Takt
function sevz_get_json_data_function_year() {
	global $wpdb;

	$year = date("Y");
	$month = date("m");
	$today = date("Y-m-d\T00");


	//new yesterday-calculation at 01.09.2021
	$yesterday_calculate = date('Y-m-d', strtotime("-1 years"));
	$yesterday_year = (new DateTime("$yesterday_calculate"))->format('Y');
	$yesterday_month = (new DateTime("$yesterday_calculate"))->format('m');
	$yesterday_day = (new DateTime("$yesterday_calculate"))->format('d');

	//JSON-Daten "OptiPower" jedes Produzenten in Mysql speichern
	$produzent = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}producer_data` WHERE `link1` <> '' ");

	//alle Produzenten durchgehen
	foreach($produzent as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$tablename = 'producer_year_'.$nachname.'_'.$ID; 

		//für jeden Prosumer Tabelle kreieren
		$sql = "CREATE TABLE `{$wpdb->base_prefix}{$tablename}`(
 			count mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			erstellt_am_tag datetime,
			zeitstempel datetime,
			verbrauch_kwh float(24),
			verbrauch_ht_kwh float(24),
			verbrauch_nt_kwh float(24),		
  			einspeisung_kwh float(24),
			flag datetime
		) $charset_collate;";

		//falls Tabelle nicht existiert, wird eine creiert
		maybe_create_table( $wpdb->prefix . $tablename, $sql );


		$json_url18 = "https://$link@backend.powerfox.energy/api/2.0/my/all/report?year=$year";
		$json18= wp_remote_get( $json_url18 );

		if( is_wp_error( $json18 ) ) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $json18 );

		$data18 = json_decode($body, TRUE);
		if( ! empty( $data18 ) ) {

			if($data18){

				for($i = count($data18['Consumption']['ReportValues'])-1; $i >=0; $i--){
					${'verbrauch_timestamp_'.$i} = $data18['Consumption']['ReportValues'][$i]['Timestamp'];
					${'verbrauch_delta_'.$i} = $data18['Consumption']['ReportValues'][$i]['Delta'];
					${'einspeisung_delta_'.$i} = $data18['FeedIn']['ReportValues'][$i]['Delta'];

					$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

					//falls Daten bereits existiren, werden nur neue eingefügt
					$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}producer_year_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 

					if($datenvergleich == NULL){ 
						$wpdb->insert($wpdb->base_prefix.'producer_year_'.$nachname.'_'.$ID, 
									  array(
										  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
										  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
										  'verbrauch_kwh' => ${'verbrauch_delta_'.$i},
										  'einspeisung_kwh' => ${'einspeisung_delta_'.$i}
									  ));
					}else{
						$wpdb->update($wpdb->base_prefix.'producer_year_'.$nachname.'_'.$ID, 
									  array(
										  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
										  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
										  'verbrauch_kwh' => ${'verbrauch_delta_'.$i},
										  'einspeisung_kwh' => ${'einspeisung_delta_'.$i}
									  ),array( 'zeitstempel' => $temp_time ));

					}
				}	
			}else{
				//die;
			}
		}
	}



	//JSON-Daten "OptiPower" jedes Prosumers in Mysql speichern
	$prosumer = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}prosumer_data` WHERE `link1` <> '' ");


	//alle Prosumer durchgehen
	foreach($prosumer as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;


		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$tablename = 'prosumer_year_'.$nachname.'_'.$ID; 

		//für jeden Prosumer Tabelle kreieren
		$sql = "CREATE TABLE `{$wpdb->base_prefix}{$tablename}`(
 			count mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			erstellt_am_tag datetime,
			zeitstempel datetime,
			verbrauch_kwh float(24),
			verbrauch_ht_kwh float(24),
			verbrauch_nt_kwh float(24),		
  			einspeisung_kwh float(24),
			flag datetime
		) $charset_collate;";

		//falls Tabelle nicht existiert, wird eine creiert
		maybe_create_table( $wpdb->prefix . $tablename, $sql );


		$json_url18 = "https://$link@backend.powerfox.energy/api/2.0/my/all/report?year=$year";
		$json18= wp_remote_get( $json_url18 );

		if( is_wp_error( $json18 ) ) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $json18 );

		$data18 = json_decode($body, TRUE);
		if( ! empty( $data18 ) ) {

			if($data18){
				$arbeitspreis_ht = $data18['Consumption']['ReportValues'][0]['DeltaHT'];
				if(!empty($arbeitspreis_ht)){
					for($i = count($data18['Consumption']['ReportValues'])-1; $i >=0; $i--){
						${'verbrauch_delta_ht'.$i} = $data18['Consumption']['ReportValues'][$i]['DeltaHT'];
						${'verbrauch_delta_nt'.$i} = $data18['Consumption']['ReportValues'][$i]['DeltaNT'];
						${'verbrauch_timestamp_'.$i} = $data18['Consumption']['ReportValues'][$i]['Timestamp'];
						${'verbrauch_delta_'.$i} = $data18['Consumption']['ReportValues'][$i]['Delta'];
						${'einspeisung_delta_'.$i} = $data18['FeedIn']['ReportValues'][$i]['Delta'];

						$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

						//falls Daten bereits existiren, werden nur neue eingefügt
						$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}prosumer_year_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 
						if($datenvergleich ==NULL){ 
							$wpdb->insert($wpdb->base_prefix.'prosumer_year_'.$nachname.'_'.$ID, 
										  array(
											  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
											  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
											  'verbrauch_kwh' => ${'verbrauch_delta_'.$i},
											  'verbrauch_ht_kwh' => ${'verbrauch_delta_ht'.$i},
											  'verbrauch_nt_kwh' => ${'verbrauch_delta_nt'.$i},
											  'einspeisung_kwh' => ${'einspeisung_delta_'.$i}
										  ));
						}else{
							$wpdb->update($wpdb->base_prefix.'prosumer_year_'.$nachname.'_'.$ID, 
										  array(
											  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
											  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
											  'verbrauch_kwh' => ${'verbrauch_delta_'.$i},
											  'verbrauch_ht_kwh' => ${'verbrauch_delta_ht'.$i},
											  'verbrauch_nt_kwh' => ${'verbrauch_delta_nt'.$i},
											  'einspeisung_kwh' => ${'einspeisung_delta_'.$i}
										  ),array( 'zeitstempel' => $temp_time ));

						}
					}

				}else{

					for($i = count($data18['Consumption']['ReportValues'])-1; $i >=0; $i--){
						${'verbrauch_timestamp_'.$i} = $data18['Consumption']['ReportValues'][$i]['Timestamp'];
						${'verbrauch_delta_'.$i} = $data18['Consumption']['ReportValues'][$i]['Delta'];
						${'einspeisung_delta_'.$i} = $data18['FeedIn']['ReportValues'][$i]['Delta'];

						$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

						//falls Daten bereits existiren, werden nur neue eingefügt
						$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}prosumer_year_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 

						if($datenvergleich ==NULL){ 
							$wpdb->insert($wpdb->base_prefix.'prosumer_year_'.$nachname.'_'.$ID, 
										  array(
											  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
											  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
											  'verbrauch_kwh' => ${'verbrauch_delta_'.$i},
											  'einspeisung_kwh' => ${'einspeisung_delta_'.$i}
										  ));
						}else{
							$wpdb->update($wpdb->base_prefix.'prosumer_year_'.$nachname.'_'.$ID, 
										  array(
											  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
											  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
											  'verbrauch_kwh' => ${'verbrauch_delta_'.$i},
											  'einspeisung_kwh' => ${'einspeisung_delta_'.$i}
										  ),array( 'zeitstempel' => $temp_time ));

						}
					}	
				}
			}else{

			}
		}
	}



	//JSON-Daten "OptiPower" jedes Verbrauchers in Mysql speichern
	$customer = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}consumer_data` WHERE `link1` <> '' ");


	//alle Verbraucher durchgehen
	foreach($customer as $value){
		$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;


		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$tablename = 'consumer_year_'.$nachname.'_'.$ID; 

		//für jeden Prosumer Tabelle kreieren
		$sql = "CREATE TABLE `{$wpdb->base_prefix}{$tablename}`(
 			count mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			erstellt_am_tag datetime,
			zeitstempel datetime,
			verbrauch_kwh float(24),
			verbrauch_ht_kwh float(24),
			verbrauch_nt_kwh float(24),		
  			einspeisung_kwh float(24),
			flag datetime
		) $charset_collate;";

		//falls Tabelle nicht existiert, wird eine creiert
		maybe_create_table( $wpdb->prefix . $tablename, $sql );

		//JSON-Links der OptiPower für dieses Jahr		
		$json_url18 = "https://$link@backend.powerfox.energy/api/2.0/my/all/report?year=$year";
		$json18= wp_remote_get( $json_url18 );

		if( is_wp_error( $json18 ) ) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $json18 );

		$data18 = json_decode($body, TRUE);
		if( ! empty( $data18 ) ) {

			if($data18){
				$arbeitspreis_ht = $data18['Consumption']['ReportValues'][0]['DeltaHT'];
				if(!empty($arbeitspreis_ht)){
					for($i = count($data18['Consumption']['ReportValues'])-1; $i >=0; $i--){
						${'verbrauch_delta_ht'.$i} = $data18['Consumption']['ReportValues'][$i]['DeltaHT'];
						${'verbrauch_delta_nt'.$i} = $data18['Consumption']['ReportValues'][$i]['DeltaNT'];
						${'verbrauch_timestamp_'.$i} = $data18['Consumption']['ReportValues'][$i]['Timestamp'];
						${'verbrauch_delta_'.$i} = $data18['Consumption']['ReportValues'][$i]['Delta'];

						$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

						//falls Daten bereits existiren, werden nur neue eingefügt
						$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}consumer_year_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 
						if($datenvergleich ==NULL){ 
							$wpdb->insert($wpdb->base_prefix.'consumer_year_'.$nachname.'_'.$ID, 
										  array(
											  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
											  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
											  'verbrauch_kwh' => ${'verbrauch_delta_'.$i},
											  'verbrauch_ht_kwh' => ${'verbrauch_delta_ht'.$i},
											  'verbrauch_nt_kwh' => ${'verbrauch_delta_nt'.$i}
										  ));
						}else{
							$wpdb->update($wpdb->base_prefix.'consumer_year_'.$nachname.'_'.$ID, 
										  array(
											  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
											  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
											  'verbrauch_kwh' => ${'verbrauch_delta_'.$i},
											  'verbrauch_ht_kwh' => ${'verbrauch_delta_ht'.$i},
											  'verbrauch_nt_kwh' => ${'verbrauch_delta_nt'.$i}
										  ),array( 'zeitstempel' => $temp_time ));

						}
					}

				}else{
					for($i = count($data18['Consumption']['ReportValues'])-1; $i >=0; $i--){
						${'verbrauch_timestamp_'.$i} = $data18['Consumption']['ReportValues'][$i]['Timestamp'];
						${'verbrauch_delta_'.$i} = $data18['Consumption']['ReportValues'][$i]['Delta'];

						$temp_time = date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i}));

						//falls Daten bereits existiren, werden nur neue eingefügt
						$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}consumer_year_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 

						if($datenvergleich ==NULL){ 
							$wpdb->insert($wpdb->base_prefix.'consumer_year_'.$nachname.'_'.$ID, 
										  array(
											  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
											  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
											  'verbrauch_kwh' => ${'verbrauch_delta_'.$i}
										  ));
						}else{
							$wpdb->update($wpdb->base_prefix.'consumer_year_'.$nachname.'_'.$ID, 
										  array(
											  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
											  'zeitstempel' => date("Y-m-d\TH:i:s\Z",(${'verbrauch_timestamp_'.$i})),
											  'verbrauch_kwh' => ${'verbrauch_delta_'.$i}
										  ),array( 'zeitstempel' => $temp_time ));

						}
					}
				}	
			}else{

			}
		}
	}
}



/*********************calculate startpage data*******************************/

function sevz_compute_startpage(){
	global $wpdb;	

	$timestamp = json_encode(date("H"), JSON_NUMERIC_CHECK);
	$year = date("Y");
	$month = date("m");
	$today = date("Y-m-d\TH:i:s\Z");
	$yesterday_calculate = date('Y-m-d', strtotime("-1 days"));
	$yesterday_day = (new DateTime("$yesterday_calculate"))->format('d');
	$produzent = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}producer_data` WHERE `link1` <> '' ");
	$prosumer = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}prosumer_data` WHERE `link1` <> '' ");
	$allPV = count($produzent)+count($prosumer);

	if(empty($allPV))
		return;
	//alle Produzenten durchgehen
	foreach($produzent as $value){
		//$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;
		$table_prod = $wpdb->base_prefix.'producer_year_'.$nachname.'_'.$ID;

		$selected_year = $wpdb->get_results("SELECT * FROM $table_prod WHERE YEAR(`zeitstempel`) = '$year'");
		foreach($selected_year as $value1){
			$last_month_data[] = $value1->einspeisung_kwh;
		}


		$gesamte_anlagen_produktion = array_sum($last_month_data);
	}

	//alle Prosumer durchgehen
	foreach($prosumer as $value){
		//$link = $value ->link1;		
		$ID = $value->ID;
		$nachname =$value->name;
		$table_prosumer = $wpdb->base_prefix.'prosumer_year_'.$nachname.'_'.$ID;

		$selected_year = $wpdb->get_results("SELECT * FROM $table_prosumer WHERE YEAR(`zeitstempel`) = '$year'");
		foreach($selected_year as $value1){
			$last_data[] = $value1->einspeisung_kwh;
		}

		$gesamte_anlagen_produktion_prosumer = array_sum($last_data);
	}

	$sum_production = $gesamte_anlagen_produktion + $gesamte_anlagen_produktion_prosumer;
	$countCo2= ($sum_production * 0.584)/1000;
	$tree = number_format($countCo2 * 80, 0, ',', '');
	$temp_time = $today;
	$datenvergleich = $wpdb->get_results("SELECT `timestamp` FROM `{$wpdb->base_prefix}startpage_pv` WHERE `timestamp`= '$temp_time'"); 

	if($datenvergleich ==NULL){ 
		$wpdb->insert($wpdb->base_prefix.'startpage_pv', 
					  array(
						  'timestamp' => $temp_time,
						  'production' => $sum_production,
						  'co2' => $countCo2,
						  'trees' => $tree
					  ));
	}

}











/*iMSys***************************************************************
 * Retrieve smart meter data imsys*/

//save imsys 15min data in database
function sevz_save_imsys_data(){
	global $wpdb;

	$verzeichnis = get_home_path().get_option('imsyspath');
	$imsysidstringcons = get_option('imsysidstringcons');
	$imsysidfirstcons = get_option('imsysidfirstcons');
	$imsysidlastcons = get_option('imsysidlastcons');
	$imsysqtystringcons = get_option('imsysqtystringcons');
	$imsysqtyfirstcons = get_option('imsysqtyfirstcons');
	$imsysqtylastcons = get_option('imsysqtylastcons');
	$imsysqtyfirstdate = get_option('imsysqtyfirstdate');
	$imsysqtylastdate = get_option('imsysqtylastdate');

	if ( is_dir ( $verzeichnis ))
	{
		if ( $handlever = opendir($verzeichnis)) {
			// einlesen der Verzeichnisses
			while (($fileimsys = readdir($handlever)) !== FALSE){
				$row = 1;
				if (($handle = fopen( $verzeichnis.$fileimsys, "r")) !== FALSE) {

					while (($data = fgetcsv($handle, 1000, "'")) !== FALSE) {
						$num = count($data);
						$row++;
						for ($c=0; $c < $num; $c++) {
							/*	$string ="LOC+172+DE001068";
							$string2 = "QTY+220:";
							$string3 = "QTY+220:0.012 
				DTM+163:202206140000?+02:303";
				S0000000000001197953
				S0000000000001197952
				S0000000000001195928*/
							if (str_starts_with($data[$c], $imsysidstringcons)) {
								//echo strlen($data[$c]);
								//echo "PLZ: ".substr($data[$c], 16, 5)."<br />";
								//echo "ID: ".substr($data[$c], 21, 41)."<br />";
								$idcomp = substr($data[$c], $imsysidfirstcons, $imsysidlastcons);
							} if (str_starts_with($data[$c], $imsysqtystringcons)) {

								//Daten "imsys" jedes Verbrauchers in Mysql speichern
								$consumer = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}consumer_data` WHERE `imsysid` <> '' ");

								//alle Verbraucher durchgehen
								foreach($consumer as $value){
									//$link = $value ->link1;	

									$imsysid = $value->imsysid;

									//if id is the same as user-id
									if($imsysid==$idcomp){
										$ID = $value->ID;
										$nachname =$value->name;

										require_once ABSPATH . 'wp-admin/includes/upgrade.php';
										$tablename = 'consumer_'.$nachname.'_'.$ID; 

										//für jeden Prosumer Tabelle kreieren
										$sql = "CREATE TABLE `{$wpdb->base_prefix}{$tablename}`(
 			count mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			erstellt_am_tag datetime,
			zeitstempel datetime,
			verbrauch_kwh float(24),
			flag datetime
		) $charset_collate;";

										//falls Tabelle nicht existiert, wird eine creiert
										maybe_create_table( $wpdb->prefix . $tablename, $sql );


										${'verbrauch_timestamp_'.$c} = substr($data[$c+1], $imsysqtyfirstdate, $imsysqtylastdate);
										${'verbrauch_delta_'.$c} = substr($data[$c], $imsysqtyfirstcons, $imsysqtylastcons);

										$temp_time = date("Y-m-d\TH:i:s\Z", strtotime(${'verbrauch_timestamp_'.$c}));

										//falls Daten bereits existiren, werden nur neue eingefügt
										$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}consumer_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 

										if($datenvergleich ==NULL){ 
											$wpdb->insert($wpdb->base_prefix.'consumer_'.$nachname.'_'.$ID, 
														  array(
															  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
															  'zeitstempel' => $temp_time,
															  'verbrauch_kwh' => ${'verbrauch_delta_'.$c}
														  ));
										}
									}
								}
							}else {

							}
						}
					}
					fclose($handle);
				}
				unlink($verzeichnis.$fileimsys);
			}closedir($handlever);
		}
	}
}







/*iMSys***************************************************************+*/

//save imsys h data in database
function sevz_save_imsys_h(){
	global $wpdb;

	//JSON-Daten "OptiPower" jedes Verbrauchers in Mysql speichern
	$customer = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}consumer_data` WHERE `imsysid` <> '' ");


	//alle Verbraucher durchgehen
	foreach($customer as $value){
		$link = $value ->link1;	
		$imsysid = $value ->imsysid;		
		$ID = $value->ID;
		$nachname =$value->name;


		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$tablename = 'consumer_day_'.$nachname.'_'.$ID; 

		//für jeden Prosumer Tabelle kreieren
		$sql = "CREATE TABLE `{$wpdb->base_prefix}{$tablename}`(
 			count mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			erstellt_am_tag datetime,
			zeitstempel datetime,
			verbrauch_kwh float(24),
			verbrauch_ht_kwh float(24),
			verbrauch_nt_kwh float(24),
			flag datetime
		) $charset_collate;";

		//falls Tabelle nicht existiert, wird eine creiert
		maybe_create_table( $wpdb->prefix . $tablename, $sql );


		for($i =0; $i <= 23; $i++){

			$stunden = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$wpdb->base_prefix}consumer_{$nachname}_{$ID}` WHERE DAY(`zeitstempel`)= %d AND MONTH(`zeitstempel`)= %d AND YEAR(`zeitstempel`) = %d AND HOUR(`zeitstempel`) = %d" , SEVZ_YESTERDAY_DAY, SEVZ_YESTERDAY_MONTH, SEVZ_YESTERDAY_YEAR, $i)); 

			foreach($stunden as $value){
				$verbrauch_count[$i] +=$value->verbrauch_kwh;
				$verbrauch =$verbrauch_count[$i];
				$time[$i] =$value->zeitstempel;
				$temp_time = date("Y-m-d\TH:i:s\Z", strtotime($time[$i]));

			}


			//falls Daten bereits existiren, werden nur neue eingefügt
			$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}consumer_day_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 

			if($datenvergleich ==NULL){ 

				$wpdb->insert($wpdb->base_prefix.'consumer_day_'.$nachname.'_'.$ID, 
							  array(
								  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
								  'zeitstempel' => $temp_time,
								  'verbrauch_kwh' => $verbrauch
							  ));
			}
		}
	}
}


/*iMSys***************************************************************+*/

//save imsys day data in database
function sevz_save_imsys_day(){
	global $wpdb;

	//JSON-Daten "OptiPower" jedes Verbrauchers in Mysql speichern
	$customer = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}consumer_data` WHERE `imsysid` <> '' ");


	//alle Verbraucher durchgehen
	foreach($customer as $value){
		$link = $value ->link1;	
		$imsysid = $value ->imsysid;		
		$ID = $value->ID;
		$nachname =$value->name;


		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$tablename = 'consumer_month_'.$nachname.'_'.$ID; 

		//für jeden Prosumer Tabelle kreieren
		$sql = "CREATE TABLE `{$wpdb->base_prefix}{$tablename}`(
 			count mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			erstellt_am_tag datetime,
			zeitstempel datetime,
			verbrauch_kwh float(24),
			verbrauch_ht_kwh float(24),
			verbrauch_nt_kwh float(24),
			flag datetime
		) $charset_collate;";

		//falls Tabelle nicht existiert, wird eine creiert
		maybe_create_table( $wpdb->prefix . $tablename, $sql );

		$tage = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$wpdb->base_prefix}consumer_day_{$nachname}_{$ID}` WHERE DAY(`zeitstempel`)= %d AND MONTH(`zeitstempel`)= %d AND YEAR(`zeitstempel`) = %d", SEVZ_YESTERDAY_DAY, SEVZ_YESTERDAY_MONTH, SEVZ_YESTERDAY_YEAR)); 


		foreach($tage as $value){
			$verbrauch_count +=$value->verbrauch_kwh;
			$time =$value->zeitstempel;
			$temp_time = date("Y-m-d\TH:i:s\Z", strtotime($time));
		}
		$verbrauch =$verbrauch_count;

		//falls Daten bereits existiren, werden nur neue eingefügt
		$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}consumer_month_{$nachname}_{$ID}` WHERE `zeitstempel`= '$temp_time'"); 

		if($datenvergleich ==NULL){ 

			$wpdb->insert($wpdb->base_prefix.'consumer_month_'.$nachname.'_'.$ID, 
						  array(
							  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
							  'zeitstempel' => $temp_time,
							  'verbrauch_kwh' => $verbrauch
						  ));
		}
	}
}



/*iMSys***************************************************************+*/

//save imsys month data in database
function sevz_save_imsys_month(){
	global $wpdb;

	//JSON-Daten "OptiPower" jedes Verbrauchers in Mysql speichern
	$customer = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}consumer_data` WHERE `imsysid` <> '' ");


	//alle Verbraucher durchgehen
	foreach($customer as $value){
		$link = $value ->link1;	
		$imsysid = $value ->imsysid;		
		$ID = $value->ID;
		$nachname =$value->name;


		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$tablename = 'consumer_year_'.$nachname.'_'.$ID; 

		//für jeden Prosumer Tabelle kreieren
		$sql = "CREATE TABLE `{$wpdb->base_prefix}{$tablename}`(
 			count mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			erstellt_am_tag datetime,
			zeitstempel datetime,
			verbrauch_kwh float(24),
			verbrauch_ht_kwh float(24),
			verbrauch_nt_kwh float(24),
			flag datetime
		) $charset_collate;";

		//falls Tabelle nicht existiert, wird eine creiert
		maybe_create_table( $wpdb->prefix . $tablename, $sql );


		$tage = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$wpdb->base_prefix}consumer_month_{$nachname}_{$ID}` WHERE MONTH(`zeitstempel`)= %d AND YEAR(`zeitstempel`) = %d", SEVZ_YESTERDAY_MONTH, SEVZ_YESTERDAY_YEAR)); 

		foreach($tage as $value){
			$verbrauch_count +=$value->verbrauch_kwh;
			$time =$value->zeitstempel;
			$temp_time = date("Y-m-d\TH:i:s\Z", strtotime($time));
		}
		$verbrauch =$verbrauch_count;

		$datenvergleich = $wpdb->get_results($wpdb->prepare("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}consumer_year_{$nachname}_{$ID}` WHERE MONTH(`zeitstempel`)= %d AND YEAR(`zeitstempel`) = %d", SEVZ_YESTERDAY_MONTH, SEVZ_YESTERDAY_YEAR)); 

		foreach($datenvergleich as $value){
			$time =$value->zeitstempel;
			$updateTime = date("Y-m-d\TH:i:s\Z", strtotime($time));
		}

		if($datenvergleich ==NULL){ 		
			$wpdb->insert($wpdb->base_prefix.'consumer_year_'.$nachname.'_'.$ID, 
						  array(
							  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
							  'zeitstempel' => $temp_time,
							  'verbrauch_kwh' => $verbrauch
						  ));
		}else{
			$wpdb->update($wpdb->base_prefix.'consumer_year_'.$nachname.'_'.$ID, 
						  array(
							  'erstellt_am_tag' => date("Y-m-d\TH:i:s\Z"),
							  'verbrauch_kwh' => $verbrauch
						  ),array( 'zeitstempel' => $updateTime ));
		}
	}
}






/*forecast weather****************************************************************************************************/
//save weather forecast data
function sevz_weather_data_forecast(){

	global $wpdb;

	$year = date("Y");
	$month = date("m");
	$today = date("Y-m-d");


	//new tomorrow-calculation
	$tomorrow_calculate = date('Y-m-d', strtotime("+1 days"));
	$tomorrow_year = (new DateTime("$tomorrow_calculate"))->format('Y');
	$tomorrow_month = (new DateTime("$tomorrow_calculate"))->format('m');
	$tomorrow_day = (new DateTime("$tomorrow_calculate"))->format('d');

	$plz = get_option('weatherPLZ');
	$weatherkey = get_option( 'weatherkeyForecast' );

	if($weatherkey){
		if($plz){
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			$tablename = 'forecast_weather_data_'.$plz; 
			$tablename_trunk = $wpdb->prefix.$tablename; 

			//für Wetterdaten Tabelle kreieren
			$sql = "CREATE TABLE `{$wpdb->base_prefix}{$tablename}`(
    			count mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			erstellt_am_tag datetime,
			zeitstempel datetime,
			temperatur_gradC float(24),
			niederschlag_mm float(24),
			niederschlagsdecke_proz float(24),
			schnee_cm float(24),
			schneetiefe_cm float(24),
			windgeschwindigkeit_kph float(24),
			windrichtung_grad float(24),
			windstoss_kph float(24),

			feuchtigkeit_proz float(24),
			bewoelkung_proz float(24),
  			bewoelkungStr varchar(60),
			sonnenaufgang datetime,
			sonnenuntergang datetime,
			sonnenstrahlung_W_qm float(24),
			solarenergie_J_qm float(24),

			taupunkt_gradC float(24),
			mondphase float(24),
			sichtbarkeit_km float(24),
			flag date
		) $charset_collate;";

			//falls Tabelle nicht existiert, wird eine creiert
			maybe_create_table( $wpdb->prefix . $tablename, $sql );

			$wpdb->query("TRUNCATE TABLE $tablename_trunk");


			$json_url = "https://weather.visualcrossing.com/VisualCrossingWebServices/rest/services/timeline/$plz%20Germany/$today/$tomorrow_calculate?aggregateHours=0&aggregateMinutes=15&includeAstronomy=true&combinationMethod=aggregate&maxStations=-1&maxDistance=-1&contentType=json&unitGroup=metric&locationMode=single&key=$weatherkey&dataElements=all&locations=$plz%20Germany";

			$json= wp_remote_get( $json_url );

			if( is_wp_error( $json ) ) {
				return false; // Bail early
			}

			$body = wp_remote_retrieve_body( $json );

			$data = json_decode($body, TRUE);
			if( ! empty( $data ) ) {

				for($j=0; $j<=1; $j++){
					for($i = 23; $i >=0; $i--){
						${'timestamp_'.$i} = $data['days'][$j]['hours'][$i]['datetimeEpoch'];
						${'temperatur_'.$i} = $data['days'][$j]['hours'][$i]['temp'];
						${'niederschlag_'.$i} = $data['days'][$j]['hours'][$i]['precip'];
						${'niederschlagsdecke_'.$i} = $data['days'][$j]['precipcover'];
						${'schnee_'.$i} = $data['days'][$j]['hours'][$i]['snow'];
						${'schneetiefe_'.$i} = $data['days'][$j]['hours'][$i]['snowdepth'];
						${'windgeschwindigkeit_'.$i} = $data['days'][$j]['hours'][$i]['windspeed'];			
						${'windrichtung_'.$i} = $data['days'][$j]['hours'][$i]['winddir'];		
						${'windstoss_'.$i} = $data['days'][$j]['hours'][$i]['windgust'];	

						${'feuchtigkeit_'.$i} = $data['days'][$j]['hours'][$i]['humidity'];
						${'bewoelkung_'.$i} = $data['days'][$j]['hours'][$i]['cloudcover'];
						${'bewoelkungStr_'.$i} = $data['days'][$j]['hours'][$i]['conditions'];
						${'sonnenaufgang_'.$i} = $data['days'][$j]['sunrise'];
						${'sonnenuntergang_'.$i} = $data['days'][$j]['sunset'];
						${'sonnenstrahlung_'.$i} = $data['days'][$j]['hours'][$i]['solarradiation'];
						${'solarenergie_'.$i} = $data['days'][$j]['hours'][$i]['solarenergy'];

						${'taupunkt_'.$i} = $data['days'][$j]['hours'][$i]['dew'];
						${'mondphase_'.$i} = $data['days'][$j]['moonphase'];
						${'sichtbarkeit_'.$i} = $data['days'][$j]['hours'][$i]['visibility'];



						//$date = strtotime(${'timestamp_'.$i});
						$date_sunrise = strtotime(${'sonnenaufgang_'.$i});
						$date_sunset = strtotime(${'sonnenuntergang_'.$i});
						$temp_time = date("H:i:s\Z", $date);
						$temp_time = date("Y-m-d\TH:i:s\Z",(${'timestamp_'.$i}));



						$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}{$tablename}` WHERE `zeitstempel`='$temp_time'"); 

						if($datenvergleich ==NULL){ 
							$insertdate1 = date("Y-m-d\TH:i:s\Z");
							$datesunrise = date("Y-m-d\TH:i:s\Z", $date_sunrise);
							$datesunset = date("Y-m-d\TH:i:s\Z", $date_sunset);

							$wpdb->insert($wpdb->base_prefix.$tablename, 
										  array(
											  'erstellt_am_tag' => $insertdate1,
											  'zeitstempel' => $temp_time,
											  'temperatur_gradC' => ${'temperatur_'.$i},
											  'niederschlag_mm' => ${'niederschlag_'.$i},
											  'niederschlagsdecke_proz' => ${'niederschlagsdecke_'.$i},
											  'schnee_cm' => ${'schnee_'.$i},
											  'schneetiefe_cm' => ${'schneetiefe_'.$i},
											  'windgeschwindigkeit_kph' => ${'windgeschwindigkeit_'.$i},
											  'windrichtung_grad' => ${'windrichtung_'.$i},
											  'windstoss_kph' => ${'windstoss_'.$i},

											  'feuchtigkeit_proz' => ${'feuchtigkeit_'.$i},
											  'bewoelkung_proz' => ${'bewoelkung_'.$i},
											  'bewoelkungStr' => ${'bewoelkungStr_'.$i},
											  'sonnenaufgang' => $datesunrise,
											  'sonnenuntergang' => $datesunset,
											  'sonnenstrahlung_W_qm' => ${'sonnenstrahlung_'.$i},
											  'solarenergie_J_qm' => ${'solarenergie_'.$i},

											  'taupunkt_gradC' => ${'taupunkt_'.$i},
											  'mondphase' => ${'mondphase_'.$i},
											  'sichtbarkeit_km' => ${'sichtbarkeit_'.$i}
										  ));
						}
					}
				}
			}
		}else{
			die;
		}
	}else{
		die;

	}
}




//save current weather data
function sevz_weather_data(){

	global $wpdb;

	$stationsid = get_option('weatherstationid');
	$weatherkey = '6532d6454b8aa370768e63d6ba5a832e';
	$plz = get_option('weatherPLZ');

	if($stationsid){
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$tablename = 'weather_data_'.$plz; 


		//für Wetterdaten PLZ Tabelle kreieren
		$sql = "CREATE TABLE `{$wpdb->base_prefix}{$tablename}`(
   			count mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			erstellt_am_tag datetime,
			zeitstempel datetime,
			temperatur_gradC float(24),
			niederschlag_mm float(24),
			niederschlagsdecke_proz float(24),
			schnee_cm float(24),
			schneetiefe_cm float(24),
			windgeschwindigkeit_kph float(24),
			windrichtung_grad float(24),
			windstoss_kph float(24),
			windchill_gradC float(24),
			feuchtigkeit_proz float(24),
			bewoelkung_proz float(24),
  			bewoelkungStr varchar(60),
			sonnenaufgang datetime,
			sonnenuntergang datetime,
			sonnenstrahlung_W_qm float(24),
			solarenergie_J_qm float(24),
			hitzeindex_gradC float(24),
			taupunkt_gradC float(24),
			mondphase float(24),
			sichtbarkeit_km float(24),
			meeresspiegeldruck_mb float(24),
			wettertyp varchar(60),
			flag date
		)$charset_collate;";

		//falls Tabelle nicht existiert, wird eine creiert
		maybe_create_table( $wpdb->prefix . $tablename, $sql );


		//JSON-Link aus weather underground

		$json_url = "https://api.weather.com/v2/pws/observations/current?apiKey=$weatherkey&stationId=$stationsid&format=json&units=m";

		$json= wp_remote_get( $json_url );

		if( is_wp_error( $json ) ) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $json );

		$data = json_decode($body, TRUE);
		if( ! empty( $data ) ) {



			$timestamp = $data['observations'][0]['obsTimeLocal'];
			$temperatur = $data['observations'][0]['metric']['temp'];
			$niederschlag = $data['observations'][0]['metric']['precipRate'];
			$niederschlagsdecke = $data['observations'][0]['metric']['precipTotal'];
			$windgeschwindigkeit = $data['observations'][0]['metric']['windSpeed'];			
			$windstoss = $data['observations'][0]['metric']['windGust'];	
			$windchill = $data['observations'][0]['metric']['windChill'];
			$feuchtigkeit = $data['observations'][0]['humidity'];
			$sonnenstrahlung = $data['observations'][0]['solarRadiation'];
			$hitzeindex = $data['observations'][0]['metric']['heatIndex'];

			$date = strtotime($timestamp);

			$temp_time = date("Y-m-d\TH:i:s\Z", $date);

			$datenvergleich = $wpdb->get_results("SELECT `zeitstempel` FROM `{$wpdb->base_prefix}{$tablename}` WHERE `zeitstempel`='$temp_time'"); 

			if($datenvergleich ==NULL){ 
				$insertdate1 = date("Y-m-d\TH:i:s\Z");
				//$insertdate2 = date("H:i:s\Z", $date);
				$datesunrise = date("Y-m-d\TH:i:s\Z", $date_sunrise);
				$datesunset = date("Y-m-d\TH:i:s\Z", $date_sunset);

				$wpdb->insert($wpdb->base_prefix.$tablename, 
							  array(						  

								  'erstellt_am_tag' => $insertdate1,
								  'zeitstempel' => $temp_time,
								  'temperatur_gradC' => $temperatur,
								  'niederschlag_mm' => $niederschlag,
								  'niederschlagsdecke_proz' => $niederschlagsdecke,

								  'windgeschwindigkeit_kph' => $windgeschwindigkeit,

								  'windstoss_kph' => $windstoss,
								  'windchill_gradC'=> $windchill,
								  'feuchtigkeit_proz' => $feuchtigkeit,

								  'sonnenstrahlung_W_qm' => $sonnenstrahlung,
								  'hitzeindex_gradC' => $hitzeindex
							  ));

			}
		}
	}
}



