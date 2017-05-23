<?php

//Exit if accesssed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
*	Ajax functionalities of plugin.
*	@version 1.0.0
*/
class Shogo_Ajax {
	public function init() {

		//Implement Wordpress $wpdb.
		global $wpdb;

		//Different types of ajax request.
		$operation = $_POST['operation'];

		//Conditional Method.
		switch ($operation) {

			/*
			* 	Insert user's data to database. See js/tracker.js
			*	@version 1.0.0
			*/
			case 'inserturi':

				//Get IP Address of the Visitor.
				$ip = $_SERVER['REMOTE_ADDR'];

				//Get all Visitor's Information.
				$details = $_POST['infos'];

				//Current Date and Time.
				$date = date("Y-m-d H:i:s");

				//Get Visitor's HIstname.
				$hostname = $details['hostname'];

				//Get Visitor's Country name.
				$country = $details['country'];

				//Get Visitor's Country Code.
				$country_code = $details['country'];

				//Get Visitor's Region.
				$region = $details['region'];

				//Get Visitor's ISP Provider Name.
				$org = $details['org'];

				//Get Visitor's Goole Map Coordination Location.
				$loc = $details['org'];

				//If hostname is null.
				if (!$details->hostname) {
					$hostname = 'Could not find hostname.';
				}

				//If Country is null.
				if (!$details['country']) {
					$country = 'Unkown';
					$country_code = 'Unknown';
				}

				//If Region is null.
				if (!$details['region']) {
					$region = 'Could not find region.';
				}

				//If ISP Provider is null.
				if (!$details['org']) {
					$org = 'Could not find ISP Provider.';
				}

				//If Google Map Coordination is null.
				if (!isset($details['loc'])) {
					$loc = 'Unknown';
				}

				//Conditional Statement for Country Code to set Country's Full Name.
				switch ( $country ) {
					case 'A1':
						$country = '(Anonymous Proxy)';
					break;

					case 'A2':
						$country = '(Satellite Provider)';
					break;

					case 'AC':
						$country = 'Acension Island';
					break;

					case 'AD':
						$country = 'Andorra';
					break;

					case 'AE':
						$country = 'United Arab Emirates';
					break;

					case 'AF':
						$country = 'Afghanistan';
					break;

					case 'AG':
						$country = 'Antigua and Barbuda';
					break;

					case 'AI':
						$country = 'Anguilla';
					break;

					case 'AL':
						$country = 'Abania';
					break;

					case 'AM';
						$country = 'Armenia';
					break;

					case 'AN':
						$country = 'Netherlands Antilles';
					break;

					case 'AO':
						$country = 'Angola';
					break;

					case 'AQ':
						$country = 'Antartica';
					break;

					case 'AR':
						$country = 'Argentina';
					break;

					case 'AS':
						$country = 'American Samoa';
					break;

					case 'AT':
						$country = 'Austria';
					break;

					case 'AU':
						$country = 'Australia';
					break;

					case 'AW':
						$country = 'Aruba';
					break;

					case 'AX':
						$country = 'Aland';
					break;

					case 'AZ':
						$country = 'Azerbaijan';
					break;

					case 'BA':
						$country = 'Bosnia and Herzegovina';
					break;

					case 'BB':
						$country = 'Barbados';
					break;

					case 'BD':
						$country = 'Bangladesh';
					break;

					case 'BE':
						$country = 'Belgium';
					break;

					case 'BF':
						$country = 'Burkina Faso';
					break;

					case 'BG':
						$country = 'Bulgaria';
					break;

					case 'BH':
						$country = 'Bahrain';
					break;

					case 'BI':
						$country = 'Burundi';
					break;

					case 'BJ':
						$country = 'Benin';
					break;

					case 'BM':
						$country = 'Bermuda';
					break;

					case 'BN':
						$country = 'Brunei';
					break;

					case 'BO':
						$country = 'Bolivia';
					break;

					case 'BQ':
						$country = 'Bonaire';
					break;

					case 'BR':
						$country = 'Brazil';
					break;

					case 'BS':
						$country = 'Bahamas';
					break;

					case 'BT':
						$country = 'Butan';
					break;

					case 'BV':
						$country = 'Bouvet Island';
					break;

					case 'BW':
						$country = 'Borswana';
					break;

					case 'BY':
						$country = 'Belarus';
					break;

					case 'BZ':
						$country = 'Belize';
					break;

					case 'CA':
						$country = 'Canada';
					break;

					case 'CC':
						$country = 'Cocos (Keeling) Islands';
					break;

					case 'CD':
						$country = 'Democratic Republic of the Congo';
					break;

					case 'CF':
						$country = 'Central African Republic';
					break;

					case 'CG':
						$country = 'Republic of the Congo';
					break;

					case 'CH':
						$country = 'Switzerland';
					break;

					case 'CI':
						$country = 'Côte d`lvoire';
					break;

					case 'CK':
						$country = 'Cook Island';
					break;

					case 'CL':
						$country = 'Chile';
					break;

					case 'CM':
						$country = 'Cameroon';
					break;

					case 'CN':
						$country = 'People`s Republic of China';
					break;

					case 'CO':
						$country = 'Colombia';
					break;

					case 'CS':
						$country = 'Czechoslovakia, Serbia and Montenegro';
					break;

					case 'CU':
						$country = 'Cuba';
					break;

					case 'CV':
						$country = 'Cape Verde';
					break;

					case 'CW':
						$country = 'Curacao';
					break;

					case 'CX':
						$country = 'Christmas Island';
					break;

					case 'CY':
						$country = 'Cyprus';
					break;

					case 'CZ':
						$country = 'Czech Republic';
					break;

					case 'DD':
						$country = 'East Germany';
					break;

					case 'DE':
						$country = 'Germany';
					break;

					case 'DJ':
						$country = 'Djbouti';
					break;

					case 'DK':
						$country = 'Denmark';
					break;

					case 'DM':
						$country = 'Dominica';
					break;

					case 'DO':
						$country = 'Dominican Republic';
					break;

					case 'DZ':
						$country = 'Algeria';
					break;

					case 'EC':
						$country = 'Ecuador';
					break;

					case 'EE':
						$country = 'Estonia';
					break;

					case 'EG':
						$country = 'Egypt';
					break;

					case 'EH':
						$country = 'Western Sahara';
					break;

					case 'ER':
						$country = 'Eritrea';
					break;

					case 'ES':
						$country = 'Spain';
					break;

					case 'ET':
						$country = 'Ethiopia';
					break;

					case 'EU':
						$country = 'Europian Union';
					break;

					case 'FI':
						$country = 'Finland';
					break;

					case 'FJ':
						$country = 'Fiji';
					break;

					case 'FK':
						$country = 'Falkland Islands';
					break;

					case 'FM':
						$country = 'Federated States of Micronesia';
					break;

					case 'FO':
						$country = 'Faroe Islands';
					break;

					case 'FR':
						$country = 'France';
					break;

					case 'GA':
						$country = 'Gabon';
					break;

					case 'GB':
						$country = 'United Kingdom';
					break;

					case 'GD':
						$country = 'Grenada';
					break;

					case 'GF':
						$country = 'French Guiana';
					break;

					case 'GG':
						$country = 'Guernsey';
					break;

					case 'GH':
						$country = 'Ghana';
					break;

					case 'GI':
						$country = 'Gibraltar';
					break;

					case 'GL':
						$country = 'Greenland';
					break;

					case 'GM':
						$country = 'The Gambia';
					break;

					case 'GN':
						$country = 'Guinea';
					break;

					case 'GP':
						$country = 'Guadeloupe';
					break;

					case 'GQ':
						$country = 'Equatorial Guinea';
					break;

					case 'GR':
						$country = 'Greece';
					break;

					case 'GS':
						$country = 'South Geogia and the South Sandwich Islands';
					break;

					case 'GT':
						$country = 'Guatamala';
					break;

					case 'GU':
						$country = 'Guam';
					break;

					case 'GW':
						$country = 'Guinea-Bissau';
					break;

					case 'GY':
						$country = 'Guyana';
					break;

					case 'HK':
						$country = 'Hongkong';
					break;

					case 'HM':
						$country = ' Heard Island and McDonald Islands';
					break;

					case 'HN':
						$country = 'Honduras';
					break;

					case 'HR':
						$country = 'Croatia';
					break;

					case 'HT':
						$country = 'Haiti';
					break;

					case 'HU':
						$country = 'Hungary';
					break;

					case 'ID':
						$country = 'Indonesia';
					break;

					case 'IE':
						$country = 'Ireland';
					break;

					case 'IL':
						$country = 'Israel';
					break;

					case 'IM':
						$country = 'Isle of Man';
					break;

					case 'IN':
						$country = 'India';
					break;

					case 'IO':
						$country = 'British Indian Ocean Territory';
					break;

					case 'IQ':
						$country = 'Iraq';
					break;

					case 'IR':
						$country = 'IRAN';
					break;

					case 'IS':
						$country = 'Iceland';
					break;

					case 'IT':
						$country = 'Italy';
					break;

					case 'JE':
						$country = 'Jersey';
					break;

					case 'JM':
						$country = 'Jamaica';
					break;

					case 'JO':
						$country = 'Jordan';
					break;

					case 'JP':
						$country = 'Japan';
					break;

					case 'KE':
						$country = 'Kenya';
					break;

					case 'KG':
						$country = 'Kygryzstan';
					break;

					case 'KH':
						$country = 'Cambodia';
					break;

					case 'KI':
						$country = 'Kribati';
					break;

					case 'KM':
						$country = 'Comoros';
					break;

					case 'KN':
						$country = 'Saints Kitts and Nevis';
					break;

					case 'KP':
						$country = 'Democratic People`s Republic of Korea';
					break;

					case 'KR':
						$country = 'Republic of Korea';
					break;

					case 'KRD':
						$country = 'Kurdistan';
					break;

					case 'KW':
						$country = 'Kuwait';
					break;

					case 'KY':
						$country = 'Cayman Islands';
					break;

					case 'KZ':
						$country = 'Kazakhstan';
					break;

					case 'LA':
						$country = 'LAOS';
					break;

					case 'LB':
						$country = 'Lebanon';
					break;

					case 'LC':
						$country = 'Saint Lucia';
					break;

					case 'LI':
						$country = 'Liechtenstein';
					break;

					case 'LK':
						$country = 'Sri Lanka';
					break;

					case 'LR':
						$country = 'Liberia';
					break;

					case 'LS':
						$country = 'Lesotho';
					break;

					case 'LT':
						$country = 'Lithuania';
					break;

					case 'LU':
						$country = 'Luexmbourg';
					break;

					case 'LV':
						$country = 'Latvia';
					break;

					case 'LY':
						$country = 'Libya';
					break;

					case 'MA':
						$country = 'Morocco';
					break;

					case 'MC':
						$country = 'Monaco';
					break;

					case 'MD':
						$country = 'Moldova';
					break;

					case 'ME':
						$country = 'Montenegro';
					break;

					case 'MG':
						$country = 'Madagascar';
					break;

					case 'MH':
						$country = 'Marshall Islands';
					break;

					case 'MK':
						$country = 'Macedonia';
					break;

					case 'ML':
						$country = 'Mali';
					break;

					case 'MM':
						$country = 'Myanmar';
					break;

					case 'MN':
						$country = 'Mongolia';
					break;

					case 'MO':
						$country = 'Macau';
					break;

					case 'MP':
						$country = 'Northern Mariana Islands';
					break;

					case 'MQ':
						$country = 'Martinique';
					break;

					case 'MR':
						$country = 'Mauritania';
					break;

					case 'MS':
						$country = 'Montserrat';
					break;

					case 'MT':
						$country = 'Malta';
					break;

					case 'MU':
						$country = 'Mauritius';
					break;

					case 'MV':
						$country = 'Maldives';
					break;

					case 'MW':
						$country = 'Malawi';
					break;

					case 'MX':
						$country = 'Mexico';
					break;

					case 'MY':
						$country = 'Malaysia';
					break;

					case 'MZ':
						$country = 'Mozambique';
					break;

					case 'NA':
						$country = 'Namibia';
					break;

					case 'NC':
						$country = 'New Caledonia';
					break;

					case 'NE':
						$country = 'Niger';
					break;

					case 'NF':
						$country = 'Norfolk Island';
					break;

					case 'NG':
						$country = 'Nigeria';
					break;

					case 'NI':
						$country = 'Nicaragua';
					break;

					case 'NL':
						$country = 'Netherlands';
					break;

					case 'NO':
						$country = 'Norway';
					break;

					case 'NP':
						$country = 'Nepal';
					break;

					case 'NR':
						$country = 'Nauru';
					break;

					case 'NU':
						$country = 'Ninue';
					break;

					case 'NZ':
						$country = 'New Zeland';
					break;

					case 'OM':
						$country = 'Oman';
					break;

					case 'PA':
						$country = 'Panama';
					break;

					case 'PE':
						$country = 'Peru';
					break;

					case 'PF':
						$country = 'French Polynesia';
					break;

					case 'PG':
						$country = 'Papau New Guinea';
					break;

					case 'PH':
						$country = 'Philippines';
					break;

					case 'PK':
						$country = 'Pakistan';
					break;

					case 'PL':
						$country = 'Poland';
					break;

					case 'PM':
						$country = 'Saint-Pierre and Miquelon';
					break;

					case 'PN':
						$country = 'Pitcairn Islands';
					break;

					case 'PR':
						$country = 'Puerto Rico';
					break;

					case 'PS':
						$country = 'State of Palestine';
					break;

					case 'PT':
						$country = 'Portugal';
					break;

					case 'PW':
						$country = 'Palau';
					break;

					case 'PY':
						$country = 'Paraguay';
					break;

					case 'QA':
						$country = 'Qatar';
					break;

					case 'RE':
						$country = 'Reunion';
					break;

					case 'RO':
						$country = 'Romania';
					break;

					case 'RS':
						$country = 'Sebria';
					break;

					case 'RU':
						$country = 'Russia';
					break;

					case 'RW':
						$country = 'Rwanda';
					break;

					case 'SA':
						$country = 'Saudi Arabia';
					break;

					case 'SB':
						$country = 'Solomon Islands';
					break;

					case 'SC':
						$country = 'Seychelles';
					break;

					case 'SD':
						$country = 'Sudan';
					break;

					case 'SE':
						$country = 'Sweden';
					break;

					case 'SG':
						$country = 'Singapore';
					break;

					case 'SH':
						$country = 'Saint Helena';
					break;

					case 'SI':
						$country = 'Slovenia';
					break;

					case 'SJ':
						$country = 'Svalbard and Jan Mayen Islands';
					break;

					case 'SK':
						$country = 'Slovakia';
					break;

					case 'SL':
						$country = 'Sierra Leone';
					break;

					case 'SM':
						$country = 'San Marino';
					break;

					case 'SN':
						$country = 'Senegal';
					break;

					case 'SO':
						$country = 'Somalia';
					break;

					case 'SR':
						$country = 'Suriname';
					break;

					case 'SS':
						$country = 'South Sudan';
					break;

					case 'ST':
						$country = 'Sao Tome and Principe';
					break;

					case 'SU':
						$country = 'Soviet Union';
					break;

					case 'SV':
						$country = 'El Salvador';
					break;

					case 'SX':
						$country = ' Sint Maarten';
					break;

					case 'SY':
						$country = 'Syria';
					break;

					case 'SZ':
						$country = 'Swaziland';
					break;

					case 'TC':
						$country = 'Turks and Caicos Islands';
					break;

					case 'TD':
						$country = 'Chad';
					break;

					case 'TF':
						$country = 'French Southern and Atarctic Lands';
					break;

					case 'TG':
						$country = 'Togo';
					break;

					case 'TH':
						$country = 'Thailand';
					break;

					case 'TJ':
						$country = 'Tajikistan';
					break;

					case 'TK':
						$country = 'Tokelau';
					break;

					case 'TL':
						$country = 'East Timor';
					break;

					case 'TR':
						$country = 'Turkey';
					break;

					case 'TT':
						$country = 'Trinidad and Tobago';
					break;

					case 'TV':
						$country = 'Tuvalu';
					break;

					case 'TW':
						$country = 'Taiwan';
					break;

					case 'TZ':
						$country = 'Tazania';
					break;

					case 'UA':
						$country = 'Ukraine';
					break;

					case 'UG':
						$country = 'Uganda';
					break;

					case 'UK':
						$country = 'United Kingdom';
					break;

					case 'US':
						$country = 'United States of America';
					break;

					case 'UY':
						$country = 'Urugay';
					break;

					case 'UZ':
						$country = 'Uzbekistan';
					break;

					case 'VC':
						$country = 'Vatican City';
					break;

					case 'VC':
						$country = 'Sain Vincent and The Grenadines';
					break;

					case 'VE':
						$country = 'Venenzuela';
					break;

					case 'VG':
						$country = 'British Virgin Islands';
					break;

					case 'VI':
						$country = 'United States Virgin Islands';
					break;

					case 'VN':
						$country = 'Vietnam';
					break;

					case 'VU':
						$country = 'Vanuatu';
					break;

					case 'WF':
						$country = 'Wallis and Futuna';
					break;

					case 'YE':
						$country = 'Yemen';
					break;

					case 'YT':
						$country = 'Mayotte';
					break;

					case 'YU':
						$country = 'SFR Yugoslavia, FR Yugoslavia';
					break;

					case 'ZA':
						$country = 'South Africa';
					break;

					case 'ZM':
						$country = 'Zambia';
					break;

					case 'ZR':
						$country = 'Zaire';
					break;

					case 'ZW':
						$country = 'Zimbabwe';
					break;

					default:
						$country = 'Unknown';
				}

				//Insert Visitor's Information as Hit to the database.
				$wpdb->insert($wpdb->prefix.'shogo_counter_stats', array(
					'permalinks' => $_POST['uri'],
					'ip' => $ip,
					'hostname' => $hostname,
					'country' => $country,
					'country_code' => $country_code,
					'region' => $region,
					'isp' => $org,
					'browser' => shogo_get_browser_name(),
					'screensize' => $_POST['screensize'],
					'os' => shogo_get_os_name(),
					'location' => $loc,
					'date' => $date
				));
			break;

			/*
			*	Show more Results for Admin Page's Page View.
			*	@version 1.0.0
			*/
			case 'page_view_load_more':

				//Number of Results to show.
				$rows = $_POST['rows'];

				//WP Prefix and Table Name.
				$table = $wpdb->prefix.'shogo_counter_stats';

				//Get Permalinks Results.
				$permalinks = $wpdb->get_results( "SELECT DISTINCT `permalinks` FROM $table GROUP BY `permalinks` ORDER BY COUNT(*) DESC LIMIT 0, $rows" );
				?>

				<?php
				/*
				*	Append on the Admin Page's Page View's Page.
				*/
				?>
				<?php foreach( $permalinks as $permalink ): ?>
					<tr>
						<td>
							<?php
								if (strlen( $permalink->permalinks ) >= 100) {
									echo '<span style="cursor: pointer;" onclick="javascript: pageDetails(\''.$permalink->permalinks.'\')" title="'.$permalink->permalinks.'">'.substr($permalink->permalinks, 0, 100).'...</span>';
								} else {
									echo '<span style="cursor: pointer;" onclick="javascript: pageDetails(\''.$permalink->permalinks.'\')" title="'.$permalink->permalinks.'">'.$permalink->permalinks.'</span>';
								}
							?>
						</td>
						<td><?php echo shogo_getPageCount( $permalink->permalinks ); ?></td>
					</tr>
				<?php endforeach; ?>

				<?php
			break;

			/*
			*	Show more Results for Admin Page's Page View. (For Mobile Mode)
			*	@version 1.0.0
			*/
			case 'page_view_load_more_mobile':

				//Number of Results to show.
				$rows = $_POST['rows'];

				//WP Prefix and Table Name.
				$table = $wpdb->prefix.'shogo_counter_stats';

				//Get Permalinks Results.
				$permalinks = $wpdb->get_results( "SELECT DISTINCT `permalinks` FROM $table GROUP BY `permalinks` ORDER BY COUNT(*) DESC LIMIT 0, $rows" );
				?>


				<?php
				/*
				*	Append on the Admin Page's Page View's Page.
				*/
				?>
				<?php foreach( $permalinks as $permalink ): ?>
					<ul>
						<li>Hits: <?php echo shogo_getPageCount( $permalink->permalinks ); ?></li>
						<li>
							<?php
								if (strlen( $permalink->permalinks ) >= 80) {
									echo '<span>'.substr($permalink->permalinks, 0, 80).'...</span>';
								} else {
									echo '<span>'.$permalink->permalinks.'</span>';
								}
							?>
						</li>
						<li></li>
					</ul>
				<?php endforeach; ?>

				<?php
			break;

			/*
			*	Show more Results for Admin Page's Logs.
			*	@version 1.0.0
			*/
			case 'csi_load_more':

				//Number of Results to show.
				$rows = $_POST['rows'];

				//WP Prefix and Table Name.
				$table = $wpdb->prefix.'shogo_counter_stats';

				//Get Logs Results.
				$hits = $wpdb->get_results( "SELECT DISTINCT * FROM $table GROUP BY `ip` ORDER BY `id` DESC LIMIT 0, $rows" );
				?>

				<?php
				/*
				*	Append on the Admin Page's Log's Page.
				*/
				?>
				<?php foreach( $hits as $hit ): ?>
					<tr>
						<td>
							<?php if ( $hit->country !== 'Unknown' ): ?>
								<img title="<?php echo $hit->country; ?>" src="<?php echo plugins_url( '../flags/'.$hit->country_code.'.png', __FILE__ ); ?>" />
							<?php else: ?>
								&nbsp;
							<?php endif; ?>
						</td>
						<td><span style="cursor: pointer; text-decoration: underline" onclick="shogo_ipinfo( '<?php echo $hit->ip; ?>' )"><?php echo $hit->ip ?></span></td>
						<td><?php echo shogo_getHitsViaIp( $hit->ip ); ?></td>
						<td><?php echo $hit->hostname; ?></td>
						<td><?php echo $hit->region; ?></td>
						<td><?php echo $hit->isp; ?></td>
						<td>
							<a target="_blank" href="https://www.google.com/maps/@<?php echo $hit->location; ?>,11z?hl=fil">Click Here To View Map</a>
						</td>
					</tr>
				<?php endforeach; ?>

				<?php
			break;

			/*
			*	Show more Results for Admin Page's Logs. (For Mobile Mode)
			*	@version 1.0.0
			*/
			case 'csi_load_more_mobile':

				//Number of Results to show.
				$rows = $_POST['rows'];

				//WP Prefix and Table Name.
				$table = $wpdb->prefix.'shogo_counter_stats';

				//Get Logs Results.
				$hits = $wpdb->get_results( "SELECT DISTINCT * FROM $table GROUP BY `ip` ORDER BY `id` DESC LIMIT 0, $rows" );
				?>

				<?php
				/*
				*	Append on the Admin Page's Log's Page.
				*/
				?>
				<?php foreach ( $hits as $hit ): ?>
					<div class="shogo-stats-logs">
						<div class="shogo-header">IP Address: <?php echo $hit->ip; ?></div>
						<ul>
							<li>Hits: <?php echo shogo_getHitsViaIp( $hit->ip ); ?></li>
							<li>Country: <?php if ( $hit->country !== 'Unknown' ): ?> <img src="<?php echo plugins_url( '../flags/'.$hit->country_code.'.png', __FILE__ ); ?>" /> <?php  echo $hit->country; ?> <?php else: ?> &nbsp; <?php endif; ?></li>
							<li>Hostname: <?php echo $hit->hostname; ?></li>
							<li>Region/State: <?php echo $hit->region; ?></li>
							<li>ISP: <?php echo $hit->isp; ?></li>
							<li>Location: <a target="_blank" href="https://www.google.com/maps/@<?php echo $hit->location; ?>,11z?hl=fil">Click Here To View Map</a></li>
						</ul>
						<div class="shogo-button-container">
							<span class="button-primary" onclick="javascript: shogo_ipinfo_mobile( '<?php echo $hit->ip; ?>' )">View More Info</span>
						</div>
					</div>
				<?php endforeach; ?>

				<?php
			break;

			/*
			*	IP Information window from Logs Page. Show Results.
			*	Version 1.0.0
			*/
			case 'user_ip_info':

				//IP Address from Ajax Request.
				$ip = $_POST['ip'];

				//WP Prefix and Table Name.
				$table = $wpdb->prefix.'shogo_counter_stats';

				//Get Visitor's Information.
				$infos = $wpdb->get_results( "SELECT DISTINCT * FROM $table WHERE `ip` = '$ip' GROUP BY `ip` ORDER BY `id` DESC" );

				//Get Visitor's Visited Permalinks.
				$permalinks = $wpdb->get_results( "SELECT DISTINCT `permalinks` FROM $table WHERE `ip` = '$ip' GROUP BY `permalinks` ORDER BY COUNT(*) DESC" );
				?>

				<?php
				/*
				*	Append to the window.
				*/
				?>
				<div class="shogo-counter-stats-user-ip-info-container">
					<div class="shogo-nav">
						<ul>
							<li><a id="shogo_ip_info" href="javascript:">IP Information</a></li>
							<li><a id="shogo_page_visited" href="javascript:">Page Visited</a></li>
						</ul>
						<div class="shogo-clear"></div>
					</div>
					<div class="shogo-contents">
						<div class="shogo-counter-stats-ip-information">
							<table cellpadding="0" cellspacing="0">
								<tbody>
									<tr>
										<td>Total Hits:</td>
										<td><?php echo shogo_getHitsViaIp( $ip ); ?></td>
									</tr>
									<tr>
										<td>Flag:</td>
										<td>
											<?php if ( $infos[0]->country_code !== 'Unknown' ): ?>
												<img src="<?php echo plugins_url( '../flags/'.$infos[0]->country_code.'.png', __FILE__ ); ?>" />
											<?php else: ?>
												&nbsp;
											<?php endif; ?>
										</td>
									</tr>
									<tr>
										<td>Country Code:</td>
										<td><?php echo $infos[0]->country_code; ?></td>
									</tr>
									<tr>
										<td>Country:</td>
										<td><?php echo $infos[0]->country; ?></td>
									</tr>
									<tr>
										<td>Region:</td>
										<td><?php echo $infos[0]->region; ?></td>
									</tr>
									<tr>
										<td>Hostname:</td>
										<td><?php echo $infos[0]->hostname; ?></td>
									</tr>
									<tr>
										<td>Inernet Service Provider:</td>
										<td><?php echo $infos[0]->isp; ?></td>
									</tr>
									<tr>
										<td>Screen size:</td>
										<td><?php echo $infos[0]->screensize; ?></td>
									</tr>
									<tr>
										<td>Web Browser(s):</td>
										<td>
											<ul>
												<?php foreach( shogo_get_user_web_browsers( $ip ) as $b ): ?>
													<li><?php echo $b->browser; ?></li>
												<?php endforeach; ?>
											</ul>
										</td>
									</tr>
									<tr>
										<td>Operating System(s):</td>
										<td>
											<ul>
												<?php foreach( shogo_get_user_os( $ip ) as $os ): ?>
													<li><?php echo $os->os; ?></li>
												<?php endforeach; ?>
											</ul>
										</td>
									</tr>
									<tr>
										<td>Location:</td>
										<td><a target="_blank" href="https://www.google.com/maps/@<?php echo $infos[0]->location; ?>,11z?hl=fil">Click Here To View Map</a></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="shogo-counter-stats-user-page-viewed">
						<table cellpadding="0" cellspacing="0">
							<thead>
								<tr>
									<th>Page Viewed/Permalinks</th>
									<th>Hits</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach( $permalinks as $p ): ?>
									<tr>
										<td>
											<?php
												if (strlen( $p->permalinks ) >= 100) {
													echo '<span title="'.$p->permalinks.'">'.substr($p->permalinks, 0, 100).'...</span>';
												} else {
													echo '<span title="'.$p->permalinks.'">'.$p->permalinks.'</span>';
												}
											?>
										</td>
										<td><?php echo shogo_get_user_hits( $ip, $p->permalinks ); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>

				<script type="text/javascript">
					var $ = jQuery;

					//Reference and Default Tab.
					var page = "ip_info";

					//Default Active Tab.
					$(".shogo-counter-stats-visitors-info .shogo-nav ul li:first-child").css( {"background-color": "yellow"} );

					//Default Content Loaded.
					$(".shogo-counter-stats-ip-information").fadeIn( 500 );

					//#shogo_ip_info Click Event. Visitor's Information.
					$("#shogo_ip_info").on("click", function() {

						//If Reference is not equal to "ip_info".
						if (page !== "ip_info") {

							//Change Page Reference to "ip_info".
							page = "ip_info";

							//Fade Out Page Visted Content.
							$(".shogo-counter-stats-user-page-viewed").fadeOut(500, function() {

								//Change current tab.
								$(".shogo-counter-stats-visitors-info .shogo-nav ul li:first-child").css( {"background-color": "yellow"} );

								//Remove background color of Page Visited Tab.
								$(".shogo-counter-stats-visitors-info .shogo-nav ul li:last-child").css( {"background-color": "transparent"} );

								//Fade In IP Information Content.
								$(".shogo-counter-stats-ip-information").fadeIn( 500 );
							});
						}
					});

					//#shogo_page_visited Click Event. Visitor's Visted Permalinks.
					$("#shogo_page_visited").on("click", function() {

						//If Reference is not equal to "page_visited".
						if (page !== "page_visited") {

							//Change Page Reference to "page_visited".
							page = "page_visited";

							//Fade Out Visitor's Information Coontent.
							$(".shogo-counter-stats-ip-information").fadeOut(500, function() {

								//Change current tab.
								$(".shogo-counter-stats-visitors-info .shogo-nav ul li:last-child").css( {"background-color": "yellow"} );

								//Remove background color of Visitor's Information.
								$(".shogo-counter-stats-visitors-info .shogo-nav ul li:first-child").css( {"background-color": "transparent"} );

								//Fade In Visitor's Permalinks Content.
								$(".shogo-counter-stats-user-page-viewed").fadeIn( 500 );
							});
						}
					});
				</script>

				<?php
			break;

			/*
			*	IP Information window from Logs Page. Show Results. (Mobile)
			*	Version 1.0.0
			*/
			case 'user_ip_info_mobile':

				//IP Address from Ajax Request.
				$ip = $_POST['ip'];

				//WP Prefix and Table Name.
				$table = $wpdb->prefix.'shogo_counter_stats';

				//Get Visitor's Information.
				$infos = $wpdb->get_results( "SELECT DISTINCT * FROM $table WHERE `ip` = '$ip' GROUP BY `ip` ORDER BY `id` DESC" );

				//Get Visitor's Visited Permalinks.
				$permalinks = $wpdb->get_results( "SELECT DISTINCT `permalinks` FROM $table WHERE `ip` = '$ip' GROUP BY `permalinks` ORDER BY COUNT(*) DESC" );
				?>

				<?php
				/*
				*	Append to the window.
				*/
				?>
				<div class="shogo-counter-stats-user-ip-info-container-mobile">
					<div class="shogo-selector">
						<select>
							<option value="ip_info">IP Information</option>
							<option value="page_visited">Page Visited</option>
						</select>
					</div>

					<div class="shogo-counter-stats-ip-information-mobile">
						<table cellpadding="0" cellspacing="0">
							<tbody>
								<tr>
									<td>IP Address:</td>
									<td><?php echo $infos[0]->ip; ?></td>
								</tr>
								<tr>
									<td>Total Hits:</td>
									<td><?php echo shogo_getHitsViaIp( $ip ); ?></td>
								</tr>
								<tr>
									<td>Flag:</td>
									<td>
										<?php if ( $infos[0]->country_code !== 'Unknown' ): ?>
											<img src="<?php echo plugins_url( '../flags/'.$infos[0]->country_code.'.png', __FILE__ ); ?>" />
										<?php else: ?>
											&nbsp;
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<td>Country Code:</td>
									<td><?php echo $infos[0]->country_code; ?></td>
								</tr>
								<tr>
									<td>Country:</td>
									<td><?php echo $infos[0]->country; ?></td>
								</tr>
								<tr>
									<td>Region:</td>
									<td><?php echo $infos[0]->region; ?></td>
								</tr>
								<tr>
									<td>Hostname:</td>
									<td><?php echo $infos[0]->hostname; ?></td>
								</tr>
								<tr>
									<td>Inernet Service Provider:</td>
									<td><?php echo $infos[0]->isp; ?></td>
								</tr>
								<tr>
									<td>Web Browser(s):</td>
									<td>
										<ul>
											<?php foreach( shogo_get_user_web_browsers( $ip ) as $b ): ?>
												<li><?php echo $b->browser; ?></li>
											<?php endforeach; ?>
										</ul>
									</td>
								</tr>
								<tr>
									<td>Operating System(s):</td>
									<td>
										<ul>
											<?php foreach( shogo_get_user_os( $ip ) as $os ): ?>
												<li><?php echo $os->os; ?></li>
											<?php endforeach; ?>
										</ul>
									</td>
								</tr>
								<tr>
									<td>Location:</td>
									<td><a target="_blank" href="https://www.google.com/maps/@<?php echo $infos[0]->location; ?>,11z?hl=fil">Click Here To View Map</a></td>
								</tr>
							</tbody>
						</table>
					</div>

					<div class="shogo-counter-stats-user-page-viewed-mobile">
						<?php foreach ( $permalinks as $p ): ?>
						<div class="shogo-loop-contents">
							<div class="shogo-header">Hits: <?php echo shogo_get_user_hits( $ip, $p->permalinks ); ?></div>
							<div class="shogo-viewed-content">
								<?php echo $p->permalinks; ?>
							</div>
						</div>
						<?php endforeach; ?>
					</div>
				</div>

				<script type="text/javascript">
					var $ = jQuery;

					//Dropdown Select Event. Content Changer.
					$(".shogo-counter-stats-user-ip-info-container-mobile .shogo-selector select").on("change", function() {

						//Dropdown Select Value.
						var s = $(this).val();

						//Conditional Statement.
						switch ( s ) {

							//If Value ip_info is selected.
							case "ip_info":

								//Fade Out Permalinks Content.
								$(".shogo-counter-stats-user-page-viewed-mobile").fadeOut(500, function() {

									//Fade In Visitor's Information Content.
									$(".shogo-counter-stats-ip-information-mobile").fadeIn( 500 );
								});
							break;

							//If Value page_visited is selected.
							case "page_visited":

								//Fade Out Visitor's Information Content.
								$(".shogo-counter-stats-ip-information-mobile").fadeOut(500, function() {

									//Fade In Permalinks Content.
									$(".shogo-counter-stats-user-page-viewed-mobile").fadeIn( 500 );
								});
							break;
						}
					});
				</script>

				<?php
			break;

			/*
			*	Clear Statistic Initializer.
			*	@version 1.0.0
			*/
			case 'clear-stats':

				//WP Prefix and Table Name.
				$table = $wpdb->prefix.'shogo_counter_stats';

				//Truncate all entries from the database table.
				$wpdb->query( "TRUNCATE $table" );

				//Path of log files.
				$path = '../wp-content/uploads/shogo_counter_stats_reports';

				//Scanning the content of logs dir.
				$files = scandir( $path );

				//Unset 1st and 2nd index in array.
				unset( $files[0] );
				unset( $files[1] );

				//Deleting all files from Dir.
				foreach( $files as $file ) {
					if ( $file !== 'index.php' ) {
						unlink( $path.'/'.$file );
					}

				}
			break;

			case 'get_weekly_report':

				//Get Log date request.
				$log_date = $_POST['log_date'];

				//Path to logs dir.
				$path = '../wp-content/uploads/shogo_counter_stats_reports';

				//Get file list of dir.
				$files = scandir( $path );

				//Find the log file.
				foreach ( $files as $file ) {
					if ( $file ==  $log_date ) {
						$current_log = $file;
					}
				}

				//Get the content of log file.
				$current_log = json_decode( file_get_contents( $path.'/'.$current_log ) );

				//Explode dates in log file.
				$log_header = explode( '/', $current_log->header );

				//Get Starting Week Date.
				$date_from = $log_header[0];

				//Get End Week Date.
				$date_to = $log_header[1];

				//Get Weekly Hits.
				$weekly_hits = $current_log->weekly_hits;

				//Get Unique Hits.
				$u_weekly_hits = $current_log->u_weekly_hits;

				//Get Hits  of Days
				$sat = $current_log->days->sat;
				$fri = $current_log->days->fri;
				$thu = $current_log->days->thu;
				$wen = $current_log->days->wen;
				$tue = $current_log->days->tue;
				$mon = $current_log->days->mon;
				$sun = $current_log->days->sun;

				//Get Unique Hits  of Days
				$u_sat = $current_log->u_days->sat;
				$u_fri = $current_log->u_days->fri;
				$u_thu = $current_log->u_days->thu;
				$u_wen = $current_log->u_days->wen;
				$u_tue = $current_log->u_days->tue;
				$u_mon = $current_log->u_days->mon;
				$u_sun = $current_log->u_days->sun;

				?>

				<div class="shogo-counter-stats-weekly-report-ajax">
					<div class="shogo-header">Weekly report from <?php echo shogo_convert_ymd( $date_from ); ?> to <?php echo shogo_convert_ymd( $date_to ); ?>.</div>
					<div class="shogo-contents">
						<table cellpadding="0" cellspacing="0">
							<thead>
								<tr>
									<th>&nbsp;</th>
									<th>Sunday</th>
									<th>Monday</th>
									<th>Tuesday</th>
									<th>Wednesday</th>
									<th>Thursday</th>
									<th>Friday</th>
									<th>Saturday</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>All Hits:</td>
									<td><?php echo $sun; ?></td>
									<td><?php echo $mon; ?></td>
									<td><?php echo $tue; ?></td>
									<td><?php echo $wen; ?></td>
									<td><?php echo $thu; ?></td>
									<td><?php echo $fri; ?></td>
									<td><?php echo $sat; ?></td>
								</tr>
								<tr>
									<td>Unique Hits:</td>
									<td><?php echo $u_sun; ?></td>
									<td><?php echo $u_mon; ?></td>
									<td><?php echo $u_tue; ?></td>
									<td><?php echo $u_wen; ?></td>
									<td><?php echo $u_thu; ?></td>
									<td><?php echo $u_fri; ?></td>
									<td><?php echo $u_sat; ?></td>
								</tr>
							</tbody>
						</table>
						<div class="shogo-total-hits">
							<p>Weekly Total Hits: <?php echo $weekly_hits; ?>.</p>
							<p>Weekly Unique Hits: <?php echo $u_weekly_hits; ?>.</p>
						</div>
					</div>
				</div>

				<?php
			break;

			case 'get_weekly_report_mobile':

				//Get Log date request.
				$log_date = $log_date = $_POST['log_date'];

				//Path to logs dir.
				$path = '../wp-content/uploads/shogo_counter_stats_reports';

				//Get file list of dir.
				$files = scandir( $path );

				//Find the log file.
				foreach ( $files as $file ) {
					if ( $file ==  $log_date ) {
						$current_log = $file;
					}
				}

				//Get the content of log file.
				$current_log = json_decode( file_get_contents( $path.'/'.$current_log ) );

				//Explode dates in log file.
				$log_header = explode( '/', $current_log->header );

				//Explode dates in log file.
				$date_from = $log_header[0];

				//Get End Week Date.
				$date_to = $log_header[1];

				//Get Weekly Hits.
				$weekly_hits = $current_log->weekly_hits;

				//Get Unique Hits.
				$u_weekly_hits = $current_log->u_weekly_hits;

				//Get Hits  of Days
				$sat = $current_log->days->sat;
				$fri = $current_log->days->fri;
				$thu = $current_log->days->thu;
				$wen = $current_log->days->wen;
				$tue = $current_log->days->tue;
				$mon = $current_log->days->mon;
				$sun = $current_log->days->sun;

				//Get Unique Hits  of Days
				$u_sat = $current_log->u_days->sat;
				$u_fri = $current_log->u_days->fri;
				$u_thu = $current_log->u_days->thu;
				$u_wen = $current_log->u_days->wen;
				$u_tue = $current_log->u_days->tue;
				$u_mon = $current_log->u_days->mon;
				$u_sun = $current_log->u_days->sun;

				?>

				<div class="shogo-counter-stats-weekly-report-ajax-mobile">
					<div class="shogo-header">Weekly report from <?php echo shogo_convert_ymd( $date_from ); ?> to <?php echo shogo_convert_ymd( $date_to ); ?>.</div>
					<div class="shogo-content">
						<div class="shogo-weekly-hits-mobile">
							<div class="shogo-weekly-hits-header">Weekly Hits</div>
							<table cellpadding="0" cellspacing="0">
								<thead>
									<tr>
										<th>Days</th>
										<th>Hits</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th>Saturday:</th>
										<td><?php echo $sat; ?></td>
									</tr>
									<tr>
										<th>Friday:</th>
										<td><?php echo $fri; ?></td>
									</tr>
									<tr>
										<th>Thursday:</th>
										<td><?php echo $thu; ?></td>
									</tr>
									<tr>
										<th>Wednesday:</th>
										<td><?php echo $wen; ?></td>
									</tr>
									<tr>
										<th>Tuesday:</th>
										<td><?php echo $tue; ?></td>
									</tr>
									<tr>
										<th>Monday:</th>
										<td><?php echo $mon ?></td>
									</tr>
									<tr>
										<th>Sun:</th>
										<td><?php echo $sun; ?></td>
									</tr>
									<tr>
										<th>Weekly Total Hits:</th>
										<td><?php echo $weekly_hits; ?></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="shogo-weekly-hits-mobile">
							<div class="shogo-weekly-hits-header">Weekly Unique Hits</div>
							<table cellpadding="0" cellspacing="0">
								<thead>
									<tr>
										<th>Days</th>
										<th>Hits</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th>Saturday:</th>
										<td><?php echo $u_sat; ?></td>
									</tr>
									<tr>
										<th>Friday:</th>
										<td><?php echo $u_fri; ?></td>
									</tr>
									<tr>
										<th>Thursday:</th>
										<td><?php echo $u_thu; ?></td>
									</tr>
									<tr>
										<th>Wednesday:</th>
										<td><?php echo $u_wen; ?></td>
									</tr>
									<tr>
										<th>Tuesday:</th>
										<td><?php echo $u_tue; ?></td>
									</tr>
									<tr>
										<th>Monday:</th>
										<td><?php echo $u_mon ?></td>
									</tr>
									<tr>
										<th>Sun:</th>
										<td><?php echo $u_sun; ?></td>
									</tr>
									<tr>
										<th>Weekly Total Hits:</th>
										<td><?php echo $u_weekly_hits; ?></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<?php
			break;
		}

		wp_die();
	}
}
