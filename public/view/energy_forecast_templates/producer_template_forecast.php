<div class="tabbed_forecast">
	<input checked="checked" id="forecast1" type="radio" name="tabsforecast" />
	<input id="forecast2" type="radio" name="tabsforecast" />
	<input id="forecast3" type="radio" name="tabsforecast" />
	<input id="forecast4" type="radio" name="tabsforecast" />

	<nav>
		<label for="forecast1">Tag</label>
		<label for="forecast2">Monat</label>
		<label for="forecast3">Jahr</label>
		<label for="forecast4">Gesamt</label>
	</nav>

	<figure>
		<div class="forecast1">
			<div class="slideshow-container">
				<p id="leistungForecast" name="leistung">Prognose: Netzeinspeisung in kWh am <?php echo esc_attr(SEVZ_TODAY) ?></p>

				<canvas id="myForecastChart" width="100" height="60"></canvas>
				<script>

					var timestampki = [], einspeisungki =[];
					<?php

	for($i = 0; $i<= count($timestampki)-1; $i++){
		${'timestampki'.$i} = $timestampki[$i];
		${'einspeisungki'.$i} = $feedinki[$i];?>

					timestampki.push(<?php echo json_encode(esc_attr(date("H:i\ ", strtotime(${'timestampki'.$i})))); ?>);
					einspeisungki.push(<?php echo esc_attr(${'einspeisungki'.$i});?>); 
					<?php
	}
					?>

					var canvaski = document.getElementById("myForecastChart");
					var ctxki = canvaski.getContext('2d');
					var chartTypeki = 'line';
					var myLineChartki;


					var dataki = {
						labels: timestampki,
						datasets: [{

							label: 'Prognose: Netzeinspeisung in kWh',
							data: einspeisungki,
							backgroundColor: '#4BAAB7',
							fill: 'origin',
							borderColor: '#535D64',
							borderWidth: 1
						}]
					};

					var optionski = {
						options: {
							scales: {
								yAxes: [{
									ticks: {
										beginAtZero: false
									}
								}]
							}
						}
					};

					initki();

					function initki() {
						// Chart declaration:
						myLineChartki = new Chart(ctxki, {
							type: chartTypeki,
							data: dataki,
							options: optionski
						});
					}

				</script>

				<a class="prev" id = "plusForecast" >&#10094;</a>
				<a class="next" id = "minusForecast" >&#10095;</a>
			</div>
		</div>
		<script>

			var today = new Date();
			optionsAll = {
				day: '2-digit', month: '2-digit', year: 'numeric',
				timeZone: 'Europe/Berlin'
			};
			optionsDateMonth = {
				month: '2-digit', year: 'numeric',
				timeZone: 'Europe/Berlin'
			};
			optionsDay = {
				day: '2-digit',
				timeZone: 'Europe/Berlin'
			};
			optionsMonth = {
				month: '2-digit',
				timeZone: 'Europe/Berlin'
			};
			optionsYear = {
				year: 'numeric',
				timeZone: 'Europe/Berlin'
			};


			var minusBtnForecast = document.getElementById("minusForecast"),
				plusBtnForecast = document.getElementById("plusForecast"),
				numberDayForecast = 0, /// number value
				min = -1, /// min number
				max = 1; /// max number




			minusBtnForecast.addEventListener('click', function(){
				if (numberDayForecast>min){
					numberDayForecast = numberDayForecast-1; /// Minus 1 of the number

					var lastDayOfMonth = new Date(today.getFullYear(), today.getMonth(), today.getDate()-numberDayForecast);
					var DateDay = new Intl.DateTimeFormat('de-DE', optionsAll).format(lastDayOfMonth);
					var lastDay = new Intl.DateTimeFormat('de-DE', optionsDay).format(lastDayOfMonth);
					var lastMonth = new Intl.DateTimeFormat('de-DE', optionsMonth).format(lastDayOfMonth);
					var lastYear = new Intl.DateTimeFormat('de-DE', optionsYear).format(lastDayOfMonth);

					<?php

					$last_day_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$wpdb->base_prefix}ki_{$table_name}_{$user_id}` WHERE DAY(`datetime`) = %d AND MONTH(`datetime`)=%d AND YEAR(`datetime`) = %d ORDER BY `datetime` ASC", SEVZ_DAY, SEVZ_MONTH, SEVZ_YEAR));


					foreach( $last_day_data as $value ) {
						$timestampdayBev[] = $value->datetime;
						$feedindayBev[] = $value->fed_into_grid;
					}
					?>

					var verbrauch_timestamp_dayBev = [], einspeisung_delta_dayBev =[];
					<?php
					for($i = 0; $i<= count($timestampdayBev)-1; $i++){
						${'verbrauch_timestamp_dayBev'.$i} = $timestampdayBev[$i];
						${'einspeisung_delta_dayBev'.$i} = $feedindayBev[$i];?>

					verbrauch_timestamp_dayBev.push(<?php echo json_encode(esc_attr(date("H:i\ ", strtotime(${'verbrauch_timestamp_dayBev'.$i})))); ?>);
					einspeisung_delta_dayBev.push(<?php echo esc_attr(${'einspeisung_delta_dayBev'.$i});?>); 
					<?php
					}
					?>
					myLineChartki.destroy();
					dataki = {
						labels: verbrauch_timestamp_dayBev,
						datasets: [{

							label: 'Prognose: Netzeinspeisung in kWh',
							data: einspeisung_delta_dayBev,
							backgroundColor: '#4BAAB7',
							fill: 'origin',
							borderColor: '#535D64',
							borderWidth: 1
						}]
					};
					initki();
					myLineChartki.update();

					var slides = document.getElementById('leistungForecast').innerHTML = "Prognose: Netzeinspeisung in kWh am " + DateDay;
				}
				if(numberDayForecast == min) {   
					numberDayForecast =-1;
					var lastDayOfMonth = new Date(today.getFullYear(), today.getMonth(), today.getDate()+1);
					var DateDay = new Intl.DateTimeFormat('de-DE', optionsAll).format(lastDayOfMonth);
					var slides = document.getElementById('leistungForecast').innerHTML = "Prognose: Netzeinspeisung in kWh am " + DateDay;
					<?php
					$last_day_data1 = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$wpdb->base_prefix}ki_{$table_name}_{$user_id}` WHERE DAY(`datetime`) = %d AND MONTH(`datetime`)=%d AND YEAR(`datetime`) = %d ORDER BY `datetime` ASC", SEVZ_TOMORROW_DAY, SEVZ_TOMORROW_MONTH, SEVZ_TOMORROW_YEAR));

					foreach( $last_day_data1 as $value ) {
						$timestampdayFor1[] = $value->datetime;
						$feedindayFor1[] = $value->fed_into_grid;
					}
					?>

					var verbrauch_timestamp_dayFor1 = [], einspeisung_delta_dayFor1 =[];
					<?php
					for($i = 0; $i<= count($timestampdayFor1)-1; $i++){
						${'verbrauch_timestamp_dayFor1'.$i} = $timestampdayFor1[$i];
						${'einspeisung_delta_dayFor1'.$i} = $feedindayFor1[$i];?>

					verbrauch_timestamp_dayFor1.push(<?php echo json_encode(esc_attr(date("H:i\ ", strtotime(${'verbrauch_timestamp_dayFor1'.$i})))); ?>);
					einspeisung_delta_dayFor1.push(<?php echo esc_attr(${'einspeisung_delta_dayFor1'.$i});?>); 
					<?php
					}
					?>
					//myLineChartki.destroy();
					myLineChartki.data.labels = verbrauch_timestamp_dayFor1;
					myLineChartki.data.datasets[0].data = einspeisung_delta_dayFor1;
					myLineChartki.update();

				}
				else {
				}


			});

			plusBtnForecast.addEventListener('click', function(){
				if(numberDayForecast ==0){
					numberDayForecast = numberDayForecast+1;

					var lastDayOfMonth = new Date(today.getFullYear(), today.getMonth(), today.getDate()-numberDayForecast);
					var DateDay = new Intl.DateTimeFormat('de-DE', optionsAll).format(lastDayOfMonth);
					var lastDay = new Intl.DateTimeFormat('de-DE', optionsDay).format(lastDayOfMonth);
					var lastMonth = new Intl.DateTimeFormat('de-DE', optionsMonth).format(lastDayOfMonth);
					var lastYear = new Intl.DateTimeFormat('de-DE', optionsYear).format(lastDayOfMonth);
					<?php

					$for_day_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$wpdb->base_prefix}ki_{$table_name}_{$user_id}` WHERE DAY(`datetime`) = %d AND MONTH(`datetime`)=%d AND YEAR(`datetime`) = %d ORDER BY `datetime` ASC", SEVZ_YESTERDAY_DAY, SEVZ_YESTERDAY_MONTH, SEVZ_YESTERDAY_YEAR));


					foreach( $for_day_data as $value ) {
						$timestampdayFor[] = $value->datetime;
						$feedindayFor[] = $value->fed_into_grid;
					}

					$last_day_exist = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$wpdb->base_prefix}{$table_name}_day_{$benutzername1}_{$user_id}` WHERE DAY(`zeitstempel`)=%d AND MONTH(`zeitstempel`)=%d AND YEAR(`zeitstempel`) = %d ORDER BY `zeitstempel` ASC", SEVZ_YESTERDAY_DAY, SEVZ_YESTERDAY_MONTH, SEVZ_YESTERDAY_YEAR));
					foreach( $last_day_exist as $value ) {
						$timestampreal[] = $value->zeitstempel;
						$feedinreal[] = $value->einspeisung_kwh;
					}


					?>

					var verbrauch_timestamp_dayFor = [], einspeisung_delta_dayFor =[], real_data_timestamp=[], real_data_feedin=[];
					<?php
					for($i = 0; $i<= count($timestampdayFor)-1; $i++){
						${'verbrauch_timestamp_dayFor'.$i} = $timestampdayFor[$i];
						${'einspeisung_delta_dayFor'.$i} = $feedindayFor[$i];?>

					verbrauch_timestamp_dayFor.push(<?php echo json_encode(esc_attr(date("H:i\ ", strtotime(${'verbrauch_timestamp_dayFor'.$i})))); ?>);
					einspeisung_delta_dayFor.push(<?php echo esc_attr(${'einspeisung_delta_dayFor'.$i});?>); 
					<?php
					}
					for($j = 0; $j<= count($timestampreal)-1; $j++){
						${'real_data_timestamp'.$j} = $timestampreal[$j];
						${'real_data_feedin'.$j} = $feedinreal[$j];?>

					real_data_timestamp.push(<?php echo json_encode(esc_attr(date("H:i\ ", strtotime(${'real_data_timestamp'.$j})))); ?>);
					real_data_feedin.push(<?php echo esc_attr(${'real_data_feedin'.$j});?>); 
					<?php
					}
					?>
					myLineChartki.destroy();
					dataki = {
						labels: verbrauch_timestamp_dayFor,
						datasets: [{

							label: 'tatsächliche Netzeinspeisung in kWh',
							data: real_data_feedin,
							backgroundColor: "rgba(83,93,100, 0.5)",
							borderColor: '#535D64',
							pointHoverBackgroundColor: '#535D64',
							fill: 'origin',
							lineTension: 0
						},
								   {
									   label: 'Prognose: Netzeinspeisung in kWh',
									   data: einspeisung_delta_dayFor,
									   backgroundColor: '#4BAAB7',
									   fill: 'origin',
									   borderColor: '#535D64',
									   borderWidth: 1
								   }]
					};
					initki();

					myLineChartki.update();

					var slides = document.getElementById('leistungForecast').innerHTML = "Prognose: Netzeinspeisung in kWh am " + DateDay;
				}
				if(numberDayForecast == min) {   
					numberDayForecast = 0;
					var todayCalc = new Date(today.getFullYear(), today.getMonth(), today.getDate());
					var todayDay = new Intl.DateTimeFormat('de-DE', optionsAll).format(todayCalc);
					slides = document.getElementById('leistungForecast').innerHTML = "Prognose: Netzeinspeisung in kWh am " + todayDay;

					myLineChartki.destroy();
					dataki = {
						labels: timestampki,
						datasets: [{

							label: 'Prognose: Netzeinspeisung in kWh',
							data: einspeisungki,
							backgroundColor: '#4BAAB7',
							fill: 'origin',
							borderColor: '#535D64',
							borderWidth: 1
						}]
					};
					initki();
					myLineChartki.update();


				}
				else {   
				}

			});

		</script>


		<div class="forecast2">

		</div>
		<div class="forecast3">

		</div>
		<div class="forecast4">

		</div>
	</figure>
</div>