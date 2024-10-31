<?php

/**
 * Login View Energy Balance
 * 
 * @param		array		$sys_data		array with current user data.
 * @param		string		$table_name		Table name: producer, prosumer, consumer.
 * 
 */

class SEVZ_Add_energy_balance{

	function __construct($sys_data, $table_name) {

		$user= wp_get_current_user();
		$user_id = $user->ID;


		//MySQL
		global $wpdb;

		$now = time();
		$aMonthPrev = strtotime('-1 Month', $now);
		$prevday = strtotime('-1 Day', $now);

		// Dates for 1 month
		while(1){
			if($prevday < $aMonthPrev) break 1;
			$allDates[] = date('d.m.Y', $prevday); // Change the date-format to whatever you want
			$allDatesDay[] = date('d', $prevday);
			$allDatesMonth[] = date('m', $prevday);
			$allDatesYear[] = date('Y', $prevday); 
			$prevday = strtotime('-1 Day', $prevday);
		}

		//Show PV-Anlage if exists   
		if(!empty($sys_data)){

			$monthYear = date("m.Y");
			$allYears = [SEVZ_LAST_YEAR, SEVZ_YEAR,];


			/* search for user data*/
			foreach( $sys_data as $value ) {
				$link = $value->link1;
				$imsys = $value->imsysid;
				$benutzername = $value->benutzername;
				$passwort = $value->passwort;
				$benutzername1 = $value->name;
			}

			//********************imsys data*************************************************************
			$imsys_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$wpdb->base_prefix}{$table_name}_{$benutzername1}_{$user_id}` WHERE DAY(`zeitstempel`)= %d AND MONTH(`zeitstempel`)= %d AND YEAR(`zeitstempel`) = %d ORDER BY `zeitstempel` ASC", SEVZ_YESTERDAY_DAY, SEVZ_YESTERDAY_MONTH, SEVZ_YESTERDAY_YEAR));

			/* load if yesterday's data is available, otherwise load data from the day before yesterday*/
			if (count($imsys_data)> 1){
				foreach( $imsys_data as $value ) {
					$timestampimsys[] = $value->zeitstempel;
					$consumptionimsys[] = $value->verbrauch_kwh;
					$feedinimsys[] = $value->einspeisung_kwh;
				}
			}else{

				$imsys_data1 = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$wpdb->base_prefix}{$table_name}_{$benutzername1}_{$user_id}` WHERE DAY(`zeitstempel`)= %d AND MONTH(`zeitstempel`)= %d AND YEAR(`zeitstempel`) = %d ORDER BY `zeitstempel` ASC", SEVZ_LAST_TWO_DAY, SEVZ_LAST_TWO_MONTH, SEVZ_LAST_TWO_YEAR ));
				foreach( $imsys_data1 as $value ) {
					$timestampimsys[] = $value->zeitstempel;
					$consumptionimsys[] = $value->verbrauch_kwh;
					$feedinimsys[] = $value->einspeisung_kwh;
				}
			}


			/* data for chart month*/
			$last_month_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$wpdb->base_prefix}{$table_name}_month_{$benutzername1}_{$user_id}` WHERE MONTH(`zeitstempel`)= %d AND YEAR(`zeitstempel`) = %d ORDER BY `zeitstempel` ASC", SEVZ_MONTH, SEVZ_YEAR ));


			foreach( $last_month_data as $value ) {
				$timestampMonth[] = $value->zeitstempel;
				$consumption[] = $value->verbrauch_kwh;
				$feedin[] = $value->einspeisung_kwh;
			}

			/*data for chart year*/
			$year_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$wpdb->base_prefix}{$table_name}_year_{$benutzername1}_{$user_id}` WHERE YEAR(`zeitstempel`) = %d ORDER BY `zeitstempel` ASC", SEVZ_YEAR));

			foreach( $year_data as $value ) {
				$timestampYear[] = $value->zeitstempel;
				$consumptionYear[] = $value->verbrauch_kwh;
				$feedinYear[] = $value->einspeisung_kwh;
			}

			/*data for last year*/
			$last_year_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$wpdb->base_prefix}{$table_name}_year_{$benutzername1}_{$user_id}` WHERE YEAR(`zeitstempel`) = %d ORDER BY `zeitstempel` ASC", SEVZ_LAST_YEAR));

			foreach( $last_year_data as $value ) {
				$consumptionYearPrev[] = $value->verbrauch_kwh;
				$feedinYearPrev[] = $value->einspeisung_kwh;
			}
			$consumptionYearPrevSum = array_sum($consumptionYearPrev);
			$feedinYearPrevSum = array_sum($feedinYearPrev);

			/*data for this year*/
			$this_year_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$wpdb->base_prefix}{$table_name}_year_{$benutzername1}_{$user_id}` WHERE YEAR(`zeitstempel`) = %d ORDER BY `zeitstempel` ASC", SEVZ_YEAR));

			foreach( $this_year_data as $value ) {
				$consumptionYearThis[] = $value->verbrauch_kwh;
				$feedinYearThis[] = $value->einspeisung_kwh;
			}
			$consumptionYearThisSum = array_sum($consumptionYearThis);
			$feedinYearThisSum = array_sum($feedinYearThis);

			/***************************Plant data register with charts for day, month, year, total****************************/
			if ($table_name =="prosumer"){
				require_once 'energy_balance_templates/prosumer_template.php';
			}elseif($table_name =="producer"){
				require_once 'energy_balance_templates/producer_template.php';
			}elseif($table_name =="consumer"){
				require_once 'energy_balance_templates/consumer_template.php';
			}
		}
	}
}