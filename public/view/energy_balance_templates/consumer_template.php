<div class="tabbed">
	<input for="b1" checked="checked" id="tab1" type="radio" name="tabs1" />
	<input for="b2" id="tab2" type="radio" name="tabs1" />
	<input for="b3" id="tab3" type="radio" name="tabs1" />
	<input for="b4" id="tab4" type="radio" name="tabs1" />

	<nav>
		<label for="tab1">Tag</label>
		<label for="tab2">Monat</label>
		<label for="tab3">Jahr</label>
		<label for="tab4">Gesamt</label>
	</nav>

	<figure>

		<script>

			/*********** chart options*********/
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

			var DayDate = new Date(today.getFullYear(), today.getMonth(), today.getDate());			
			var lastDayDate = new Intl.DateTimeFormat('de-DE', optionsAll).format(DayDate);
			var lastMonthDate = new Intl.DateTimeFormat('de-DE', optionsDateMonth).format(DayDate);
			var lastYearDate = new Intl.DateTimeFormat('de-DE', optionsYear).format(DayDate);


			/******load actual data on clic************/
			document.getElementById("tab1").onclick = function() {

				<?php
				if(!empty($link)){
				?>
				var slides = document.getElementById('leistung').innerHTML = "Verbrauch in kWh am " + lastDayDate;
				let resultDay;
				resultDay=loadDay(uri);
				return resultDay;
				<?php
				}else{

				}
				?>

			};

			document.getElementById("tab2").onclick = function() {
				var slidesMonth = document.getElementById('leistungMonth').innerHTML = "Verbrauch in kWh im Monat " + lastMonthDate;
				myBarChartMonth.data.labels = verbrauch_timestamp_month;
				myBarChartMonth.data.datasets[0].data = verbrauch_delta_month;
				myBarChartMonth.update();
			};
			document.getElementById("tab3").onclick = function() {
				var slidesYear = document.getElementById('leistungYear').innerHTML = "Verbrauch in kWh im Jahr " + lastYearDate;
				myBarChartYear.data.labels = verbrauch_timestamp_year;
				myBarChartYear.data.datasets[0].data = verbrauch_delta_year;
				myBarChartYear.update();

			};


		</script>


		<div class="tab1">
			<!--		<div class="loader" id="loaderDay"></div>--> 
			<div class="slideshow-container">
				<div class="mySlides">	
					<?php
					if(!empty($link)){
						$dateLeistung = SEVZ_TODAY;
					?>
					<form id="radioform" method="post">
						<p id="leistung" name="leistung">Verbrauch in kWh am <?php echo esc_attr($dateLeistung) ?></p>
					</form>

					<canvas id="myChartDay" width="100" height="60"></canvas>
					<script>

						/******************* poweropti live data ***********************/

						var lastDayOfMonth = new Date(today.getFullYear(), today.getMonth(), today.getDate());
						var lastDay = new Intl.DateTimeFormat('de-DE', optionsDay).format(lastDayOfMonth);
						var lastMonth = new Intl.DateTimeFormat('de-DE', optionsMonth).format(lastDayOfMonth);
						var lastYear = new Intl.DateTimeFormat('de-DE', optionsYear).format(lastDayOfMonth);

						var username = "<?php echo esc_attr($benutzername);?>";
						var password = "<?php echo esc_attr($passwort);?>"; 
						var canvas = document.getElementById("myChartDay");
						var ctx = canvas.getContext('2d');
						var chartType = 'line';
						var myLineChart;

						function make_base_auth(user, password) {
							var tok = user + ':' + password;
							var hash = btoa(tok);
							return "Basic " + hash;
						}

						var uri = "https://backend.powerfox.energy/api/2.0/my/all/report?year=" + lastYear + "&month=" + lastMonth + "&day=" + lastDay + "&fromhour=0";


						var data = {
							labels: [],
							datasets: [{
								label: 'Verbrauch in kWh',
								data: [],
								backgroundColor: '<?php echo esc_attr(get_option('chartconsumption'));?>',
								fill: 'origin',
								borderColor: '#535D64',
								borderWidth: 1
							}]
						};
						var optionsDay = {
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
						init();

						function init() {
							// Chart declaration:
							myLineChart = new Chart(ctx, {
								type: chartType,
								data: data,
								options: optionsDay
							});
						}

						async function loadDay(uri) {
							let result;

							try {
								result = await jQuery.ajax
								({
									type: "GET",
									url: uri,
									dataType: 'json',
									cache: false,
									data: '{}',
									beforeSend: function (obj){ 
										obj.onreadystatechange =obj.setRequestHeader('Authorization', make_base_auth(username, password), 'Access-Control-Allow-Origin', '*');  
									},
									success: function (obj){

										var counter = obj['Consumption']['ReportValues'].length-1;
										var verbrauch_timestamp = [], verbrauch_delta = [], einspeisung_delta =[];

										options = {
											hour: 'numeric', minute: 'numeric',
											timeZone: 'Europe/Berlin'
										};

										for ( let i=counter; i>=0; i-- ) {

											const date = new Date((obj['Consumption']['ReportValues'][i]['Timestamp']) * 1000);
											verbrauch_timestamp.push(new Intl.DateTimeFormat('de-DE', options).format(date));
											verbrauch_delta.push(obj['Consumption']['ReportValues'][i]['Delta']);
											einspeisung_delta.push(obj['FeedIn']['ReportValues'][i]['Delta']);

										}
										if (obj) {
											myLineChart.data.labels = verbrauch_timestamp;
											myLineChart.data.datasets[0].data = verbrauch_delta;
											myLineChart.update();
											//	$('#loaderDay').addClass("hide-loader");

										}

									}});
								return result;
							} catch (error) {
								console.error(error);
							}
						}

						loadDay(uri);

					</script>



					<?php
					}else{
						if(count($imsys_data)> 1){
							$dateLeistung = SEVZ_YESTERDAY;
						}else{
							$dateLeistung = SEVZ_LAST_TWO_DATE;
						}
					?>
					<form id="radioform" method="post">
						<p id="leistung" name="leistung">Verbrauch in kWh am <?php echo esc_attr($dateLeistung) ?></p>
					</form>

					<canvas id="myChartimsys" width="100" height="60"></canvas>
					<script>

						/**********************************imsys data from MySql*********************************/

						var verbrauch_timestamp_imsys = [], verbrauch_delta_imsys = [], einspeisung_delta_imsys =[];
						<?php
						for($i = 0; $i<= count($timestampimsys)-1; $i++){
							${'verbrauch_timestamp_imsys'.$i} = $timestampimsys[$i];
							${'verbrauch_delta_imsys'.$i} = $consumptionimsys[$i];
							${'einspeisung_delta_imsys'.$i} = $feedinimsys[$i];?>

						verbrauch_timestamp_imsys.push(<?php echo json_encode(esc_attr(date("H:i\ ", strtotime(${'verbrauch_timestamp_imsys'.$i})))); ?>);
						verbrauch_delta_imsys.push(<?php echo esc_attr(${'verbrauch_delta_imsys'.$i});?>);
						einspeisung_delta_imsys.push(<?php echo esc_attr(${'einspeisung_delta_imsys'.$i});?>); 
						<?php
						}
						?>

						var canvasimsys = document.getElementById("myChartimsys");
						var ctximsys = canvasimsys.getContext('2d');
						var chartTypeimsys = 'line';
						var myLineChartimsys;

						var dataimsys = {
							labels: verbrauch_timestamp_imsys,
							datasets: [{
								label: 'Verbrauch in kWh',
								data: verbrauch_delta_imsys,
								backgroundColor: '<?php echo esc_attr(get_option('chartconsumption'));?>',
								fill: 'origin',
								borderColor: '#535D64',
								borderWidth: 1
							}]
						};

						var optionsimsys = {
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

						initimsys();

						function initimsys() {
							// Chart declaration:
							myLineChartimsys = new Chart(ctximsys, {
								type: chartTypeimsys,
								data: dataimsys,
								options: optionsimsys
							});
						}
						//	$('#loaderDay').addClass("hide-loader");

					</script>
					<?php
					}
					?>
					<a class="prev" onclick="plusSlides(-1)">&#10094;</a>
					<a class="next" onclick="plusSlides(1)">&#10095;</a>
				</div>
				<?php

				for($j=30; $j>=0; $j--){
					$chartId= "myChart".$j;

					$last_day_data = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}{$table_name}_day_{$benutzername1}_{$user_id}` WHERE DAY(`zeitstempel`)='$allDatesDay[$j]' AND MONTH(`zeitstempel`)='$allDatesMonth[$j]' AND YEAR(`zeitstempel`) ='$allDatesYear[$j]'");

				?>
				<div class="mySlides">
					<p id="leistung" name="leistung">Verbrauch in kWh am <?php echo $allDates[$j] ?></p>
					<canvas id="<?php echo $chartId ?>" width="100" height="60"></canvas>

					<script>
						var verbrauch_timestamp_dayBev = [], verbrauch_delta_dayBev = [], einspeisung_delta_dayBev =[];
						<?php
					foreach( $last_day_data as $value ) {
						${'timestampdayBev'.$j}[] = $value->zeitstempel;
						${'consumptiondayBev'.$j}[] = $value->verbrauch_kwh;
						${'feedindayBev'.$j}[] = $value->einspeisung_kwh;

					}
					for($i = 0; $i <=23; $i++){
						//for($i = 0; $i<= count($timestampdayBev)-1; $i++){
						${'verbrauch_timestamp_dayBev'.$i} = ${'timestampdayBev'.$j}[$i];
						${'verbrauch_delta_dayBev'.$i} = ${'consumptiondayBev'.$j}[$i];
						${'einspeisung_delta_dayBev'.$i} = ${'feedindayBev'.$j}[$i];	
						?>

						verbrauch_timestamp_dayBev.push(<?php echo json_encode(esc_attr(date("H:i\ ", strtotime(${'verbrauch_timestamp_dayBev'.$i})))); ?>);
						verbrauch_delta_dayBev.push(<?php echo esc_attr(${'verbrauch_delta_dayBev'.$i});?>);
						einspeisung_delta_dayBev.push(<?php echo esc_attr(${'einspeisung_delta_dayBev'.$i});?>); 
						<?php

					}
						?>

						var ctx = document.getElementById('<?php echo $chartId ?>');
						var myChart = new Chart(ctx, {
							type: 'line',
							data: {
								labels: verbrauch_timestamp_dayBev,
								datasets: [{
									label: 'Verbrauch in kWh',
									data: verbrauch_delta_dayBev,
									backgroundColor: '<?php echo esc_attr(get_option('chartconsumption'));?>',
									fill: 'origin',
									borderColor: '#535D64',
									borderWidth: 1
								}]
							},
							options: {
								scales: {
									yAxes: [{
										ticks: {
											beginAtZero: false
										}
									}]
								}
							}
						}
											   );
						myChart.update();

					</script>
				</div>

				<a class="prev" onclick="plusSlides(-1)">&#10094;</a>
				<a class="next" onclick="plusSlides(1)">&#10095;</a>

				<?php

				}

				?>

			</div>
		</div>


		<script>
			var slideIndex = 1;
			showSlides(slideIndex);

			function plusSlides(n) {
				showSlides(slideIndex += n);
			}

			function currentSlide(n) {
				showSlides(slideIndex = n);
			}

			function showSlides(n) {
				var i;
				var slides = document.getElementsByClassName("mySlides");
				//var dots = document.getElementsByClassName("dot");
				if (n > slides.length) {slideIndex = 1}    
				if (n < 1) {slideIndex = slides.length}
				for (i = 0; i < slides.length; i++) {
					slides[i].style.display = "none";  
				}
				/*for (i = 0; i < dots.length; i++) {
								dots[i].className = dots[i].className.replace(" active", "");
							}*/
				slides[slideIndex-1].style.display = "block";  
				//dots[slideIndex-1].className += " active";
			}
		</script>






		<div class="tab2">
			<!--		<div class="loader" id="loaderMonth"></div>--> 
			<div class="slideshow-container">

				<?php
				$dateLeistungMonth = $monthYear;
				?>
				<p id="leistungMonth">Verbrauch in kWh im Monat <?php echo esc_attr($dateLeistungMonth) ?></p>

				<canvas id="myChartMonth" width="100" height="60"></canvas>
				<script>

					/************************ month data from MySql******************************/

					var verbrauch_timestamp_month = [], verbrauch_delta_month = [], einspeisung_delta_month =[];
					<?php
	for($i = 0; $i<= count($timestampMonth)-1; $i++){
		${'verbrauch_timestamp_month'.$i} = $timestampMonth[$i];
		${'verbrauch_delta_month'.$i} = $consumption[$i];
		${'einspeisung_delta_month'.$i} = $feedin[$i];?>

					verbrauch_timestamp_month.push(<?php echo json_encode(esc_attr(date("d.m.Y\ ", strtotime(${'verbrauch_timestamp_month'.$i})))); ?>);
					verbrauch_delta_month.push(<?php echo esc_attr(${'verbrauch_delta_month'.$i});?>);
					einspeisung_delta_month.push(<?php echo esc_attr(${'einspeisung_delta_month'.$i});?>); 
					<?php
	}
					?>

					var canvasMonth = document.getElementById("myChartMonth");
					var ctxMonth = canvasMonth.getContext('2d');
					var chartTypeMonth = 'bar';
					var myBarChartMonth;

					var dataMonth = {
						labels: verbrauch_timestamp_month,
						datasets: [{
							label: 'Verbrauch in kWh',
							data: verbrauch_delta_month,
							backgroundColor: '<?php echo esc_attr(get_option('chartconsumption'));?>',
							borderColor: '#535D64',
							borderWidth: 1
						}]
					};

					var optionsMonth = {
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

					initMonth();

					function initMonth() {
						// Chart declaration:
						myBarChartMonth = new Chart(ctxMonth, {
							type: chartTypeMonth,
							data: dataMonth,
							options: optionsMonth
						});
					}
					//$('#loaderMonth').addClass("hide-loader");

				</script>
				<a class="prev" id = "plusMonth" >&#10094;</a>
				<a class="next" id = "minusMonth" >&#10095;</a>

			</div>
		</div>


		<script>
			/***********************buttons last month, next month**********************************/

			var minusBtnMonth = document.getElementById("minusMonth"),
				plusBtnMonth = document.getElementById("plusMonth"),
				numberMonth = 0, /// number value
				minMonth = 0, /// min number
				maxMonth = 1; /// max number

			plusBtnMonth.addEventListener('click', function(){
				if(numberMonth<maxMonth){
					numberMonth = numberMonth+1;

					var lastDayOfMonth = new Date(today.getFullYear(), today.getMonth()-numberMonth);
					var DateDay = new Intl.DateTimeFormat('de-DE', optionsDateMonth).format(lastDayOfMonth);
					var lastMonth = new Intl.DateTimeFormat('de-DE', optionsMonth).format(lastDayOfMonth);
					var lastYear = new Intl.DateTimeFormat('de-DE', optionsYear).format(lastDayOfMonth);
					<?php
					$for_month_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$wpdb->base_prefix}{$table_name}_month_{$benutzername1}_{$user_id}` WHERE MONTH(`zeitstempel`)=%d AND YEAR(`zeitstempel`) = %d ORDER BY `zeitstempel` ASC", SEVZ_LAST_MONTH_MONTH, SEVZ_LAST_MONTH_YEAR));

					foreach( $for_month_data as $value ) {
						$timestampmonthFor[] = $value->zeitstempel;
						$consumptionmonthFor[] = $value->verbrauch_kwh;
						$feedinmonthFor[] = $value->einspeisung_kwh;
					}
					?>

					var verbrauch_timestamp_monthFor = [], verbrauch_delta_monthFor = [], einspeisung_delta_monthFor =[];
					<?php
					for($i = 0; $i<= count($timestampmonthFor)-1; $i++){
						${'verbrauch_timestamp_monthFor'.$i} = $timestampmonthFor[$i];
						${'verbrauch_delta_monthFor'.$i} = $consumptionmonthFor[$i];
						${'einspeisung_delta_monthFor'.$i} = $feedinmonthFor[$i];?>

					verbrauch_timestamp_monthFor.push(<?php echo json_encode(esc_attr(date("d.m.Y\ ", strtotime(${'verbrauch_timestamp_monthFor'.$i})))); ?>);
					verbrauch_delta_monthFor.push(<?php echo esc_attr(${'verbrauch_delta_monthFor'.$i});?>);
					einspeisung_delta_monthFor.push(<?php echo esc_attr(${'einspeisung_delta_monthFor'.$i});?>); 
					<?php
					}
					?>
					myBarChartMonth.data.labels = verbrauch_timestamp_monthFor;
					myBarChartMonth.data.datasets[0].data = verbrauch_delta_monthFor;
					myBarChartMonth.update();

					var slidesMonth = document.getElementById('leistungMonth').innerHTML = "Verbrauch in kWh im Monat " + DateDay;
				}     
				if(numberMonth == maxMonth){
				}

				else {

				}


			});

			minusBtnMonth.addEventListener('click', function(){
				if (numberMonth>minMonth){
					numberMonth = numberMonth-1; /// Minus 1 of the number

					var lastDayOfMonth = new Date(today.getFullYear(), today.getMonth()-numberMonth);
					var DateDay = new Intl.DateTimeFormat('de-DE', optionsDateMonth).format(lastDayOfMonth);
					var lastMonth = new Intl.DateTimeFormat('de-DE', optionsMonth).format(lastDayOfMonth);
					var lastYear = new Intl.DateTimeFormat('de-DE', optionsYear).format(lastDayOfMonth);


					myBarChartMonth.data.labels = verbrauch_timestamp_month;
					myBarChartMonth.data.datasets[0].data = verbrauch_delta_month;
					myBarChartMonth.update();

					var slidesMonth = document.getElementById('leistungMonth').innerHTML = "Verbrauch in kWh im Monat " + DateDay;
				}
				if(numberMonth == minMonth) {   
					numberMonth = 0;
					var lastDayOfMonth = new Date(today.getFullYear(), today.getMonth()-numberMonth);
					var DateDay = new Intl.DateTimeFormat('de-DE', optionsDateMonth).format(lastDayOfMonth);
					var slidesMonth = document.getElementById('leistungMonth').innerHTML = "Verbrauch in kWh im Monat " + DateDay;
				}
				else {    
				}

			});
		</script>






		<div class="tab3">
			<!--		<div class="loader" id="loaderYear"></div>--> 
			<div class="slideshow-container">
				<?php
				$dateLeistungYear = $year;
				?>
				<p id="leistungYear">Verbrauch in kWh im Jahr <?php echo esc_attr($dateLeistungYear) ?></p>

				<canvas id="myChartYear" width="100" height="60"></canvas>
				<script>

					/*************************** chart year, data from MySql*************************************************/

					var verbrauch_timestamp_year = [], verbrauch_delta_year = [], einspeisung_delta_year =[];
					<?php
	for($i = 0; $i<= count($timestampYear)-1; $i++){
		${'verbrauch_timestamp_year'.$i} = $timestampYear[$i];
		${'verbrauch_delta_year'.$i} = $consumptionYear[$i];
		${'einspeisung_delta_year'.$i} = $feedinYear[$i];?>

					verbrauch_timestamp_year.push(<?php echo json_encode(esc_attr(date("m.Y\ ", strtotime(${'verbrauch_timestamp_year'.$i})))); ?>);
					verbrauch_delta_year.push(<?php echo esc_attr(${'verbrauch_delta_year'.$i});?>);
					einspeisung_delta_year.push(<?php echo esc_attr(${'einspeisung_delta_year'.$i});?>); 
					<?php
	}
					?>

					var canvasYear = document.getElementById("myChartYear");
					var ctxYear = canvasYear.getContext('2d');
					var chartTypeYear = 'bar';
					var myBarChartYear;

					var dataYear = {
						labels: verbrauch_timestamp_year,
						datasets: [{
							label: 'Verbrauch in kWh',
							data: verbrauch_delta_year,
							backgroundColor: '<?php echo esc_attr(get_option('chartconsumption'));?>',
							borderColor: '#535D64',
							borderWidth: 1
						}]
					};

					var optionsYear = {
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

					initYear();

					function initYear() {
						// Chart declaration:
						myBarChartYear = new Chart(ctxYear, {
							type: chartTypeYear,
							data: dataYear,
							options: optionsYear
						});
					}
					//$('#loaderYear').addClass("hide-loader");


				</script>
				<a class="prev" id = "plusYear" >&#10094;</a>
				<a class="next" id = "minusYear" >&#10095;</a>

			</div>
		</div>


		<script>	

			/***********************buttons last year, this year**********************************/
			var minusBtnYear = document.getElementById("minusYear"),
				plusBtnYear = document.getElementById("plusYear"),
				numberYear = 0, /// number value
				minYear = 0, /// min number
				maxYear = 1; /// max number


			plusBtnYear.addEventListener('click', function(){
				if(numberYear<maxYear){
					numberYear = numberYear+1;

					var lastDayOfMonth = new Date(today.getFullYear()-numberYear, today.getMonth());
					var lastMonth = new Intl.DateTimeFormat('de-DE', optionsMonth).format(lastDayOfMonth);
					var lastYear = new Intl.DateTimeFormat('de-DE', optionsYear).format(lastDayOfMonth);

					<?php
					$for_year_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$wpdb->base_prefix}{$table_name}_year_{$benutzername1}_{$user_id}` WHERE YEAR(`zeitstempel`) = %d ORDER BY `zeitstempel` ASC", SEVZ_LAST_YEAR));

					foreach( $for_year_data as $value ) {
						$timestampyearFor[] = $value->zeitstempel;
						$consumptionyearFor[] = $value->verbrauch_kwh;
						$feedinyearFor[] = $value->einspeisung_kwh;
					}
					?>

					var verbrauch_timestamp_yearFor = [], verbrauch_delta_yearFor = [], einspeisung_delta_yearFor =[];
					<?php
					for($i = 0; $i<= count($timestampyearFor)-1; $i++){
						${'verbrauch_timestamp_yearFor'.$i} = $timestampyearFor[$i];
						${'verbrauch_delta_yearFor'.$i} = $consumptionyearFor[$i];
						${'einspeisung_delta_yearFor'.$i} = $feedinyearFor[$i];?>

					verbrauch_timestamp_yearFor.push(<?php echo json_encode(esc_attr(date("m.Y\ ", strtotime(${'verbrauch_timestamp_yearFor'.$i})))); ?>);
					verbrauch_delta_yearFor.push(<?php echo esc_attr(${'verbrauch_delta_yearFor'.$i});?>);
					einspeisung_delta_yearFor.push(<?php echo esc_attr(${'einspeisung_delta_yearFor'.$i});?>); 
					<?php
					}
					?>
					myBarChartYear.data.labels = verbrauch_timestamp_yearFor;
					myBarChartYear.data.datasets[0].data = verbrauch_delta_yearFor;
					myBarChartYear.update();

					var slidesYear = document.getElementById('leistungYear').innerHTML = "Verbrauch in kWh im Jahr " + lastYear;
				}     
				if(numberYear == maxYear){
				}

				else {
				}


			});

			minusBtnYear.addEventListener('click', function(){
				if (numberYear>minYear){
					numberYear = numberYear-1; /// Minus 1 of the number

					var lastDayOfMonth = new Date(today.getFullYear()-numberYear, today.getMonth());
					var lastMonth = new Intl.DateTimeFormat('de-DE', optionsMonth).format(lastDayOfMonth);
					var lastYear = new Intl.DateTimeFormat('de-DE', optionsYear).format(lastDayOfMonth);

					myBarChartYear.data.labels = verbrauch_timestamp_year;
					myBarChartYear.data.datasets[0].data = verbrauch_delta_year;
					myBarChartYear.update();

					var slidesYear = document.getElementById('leistungYear').innerHTML = "Verbrauch in kWh im Jahr " + lastYear;
				}
				if(numberYear == minYear) {   
				}
				else {   
				}

			});

		</script>



		<div class="tab4">

			<p id="leistungGesamt" name="leistungGesamt">Verbrauch in kWh gesamt</p>

			<canvas id="myChartSum" width="100" height="60"></canvas>
			<script>
				/*************************** chart total years, data from MySql*************************************************/

				function addData() {

					var verbrauch_timestamp_sum1 = [], verbrauch_delta_sum1 = [], einspeisung_delta_sum1 =[];

					verbrauch_timestamp_sum1.push(<?php echo esc_attr($allYears[1]) ?>);
					verbrauch_delta_sum1.push(<?php echo esc_attr($consumptionYearThisSum) ?>);
					einspeisung_delta_sum1.push(<?php echo esc_attr($feedinYearThisSum) ?>);

					myBarChartSum.data.labels.push(verbrauch_timestamp_sum1);
					myBarChartSum.data.datasets[0].data.push(verbrauch_delta_sum1);
					myBarChartSum.update();
				}
				<?php
	if($consumptionYearPrevSum > 0){
				?>
				var verbrauch_timestamp_sum = [], verbrauch_delta_sum = [], einspeisung_delta_sum =[];
				verbrauch_timestamp_sum.push(<?php echo esc_attr($allYears[0]) ?>);
				verbrauch_delta_sum.push(<?php echo esc_attr($consumptionYearPrevSum) ?>);
				einspeisung_delta_sum.push(<?php echo esc_attr($feedinYearPrevSum) ?>);
				var canvasSum = document.getElementById("myChartSum");
				var ctxSum = canvasSum.getContext('2d');
				var chartTypeSum = 'bar';
				var myBarChartSum;

				var dataSum = {
					labels: verbrauch_timestamp_sum,
					datasets: [{
						label: 'Verbrauch in kWh',
						data: verbrauch_delta_sum,
						backgroundColor: '<?php echo esc_attr(get_option('chartconsumption'));?>',
						borderColor: '#535D64',
						borderWidth: 1
					}]
				};

				var optionsSum = {
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

				function initSum() {
					// Chart declaration:
					myBarChartSum = new Chart(ctxSum, {
						type: chartTypeSum,
						data: dataSum,
						options: optionsSum
					});
				}
				initSum();
				addData();
				<?php
	}else{
				?>
				var verbrauch_timestamp_sum = [], verbrauch_delta_sum = [], einspeisung_delta_sum =[];
				verbrauch_timestamp_sum.push(<?php echo esc_attr(SEVZ_YEAR) ?>);
				verbrauch_delta_sum.push(<?php echo esc_attr($consumptionYearThisSum) ?>);
				einspeisung_delta_sum.push(<?php echo esc_attr($feedinYearThisSum) ?>);
				var canvasSum = document.getElementById("myChartSum");
				var ctxSum = canvasSum.getContext('2d');
				var chartTypeSum = 'bar';
				var myBarChartSum;


				var dataSum = {
					labels: verbrauch_timestamp_sum,
					datasets: [{
						label: 'Verbrauch in kWh',
						data: verbrauch_delta_sum,
						backgroundColor: '<?php echo esc_attr(get_option('chartconsumption'));?>',
						borderColor: '#535D64',
						borderWidth: 1
					}]
				};

				var optionsSum = {
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

				initSum1();

				function initSum1() {
					// Chart declaration:
					myBarChartSum = new Chart(ctxSum, {
						type: chartTypeSum,
						data: dataSum,
						options: optionsSum
					});
				}
				<?php
	}
				?>
			</script>
		</div>	
	</figure>
</div>