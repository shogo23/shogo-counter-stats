<?php

//Exit if accesssed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
*	Admin Menu
*	@version 1.0.0
*/
class Shogo_Admin_Menu {

	/*
	*	Page referencing for navigation menu marking current page.
	*	@version 1.0.0
	*/
	private static $page;

	/*
	*	Referencing if plugin has hits records from database.
	*	@version 1.0.0
	*/
	private static $has_hits;

	/*
	*	Plugins table name;
	*	@version 1.0.0;
	*/
	private static $table;

	/*
	*	Constructor
	*	@version 1.0.0
	*/
	public function __construct() {
		global $wpdb;

		//Globalize database table name of this plugin.
		self::$table = $wpdb->prefix.'shogo_counter_stats';

		$table = self::$table;

		//Globalize all hits from the database
		$count_hits = $wpdb->get_var( "SELECT COUNT(*) FROM $table" );

		Shogo_Admin_Menu::$has_hits = $count_hits;
	}

	/*
	*	Setup Admin Tabs
	*	@version 1.0.0
	*/
	public static function display() {
		add_menu_page( 'Shogo\'s Counter Stats', 'Shogo\'s Counter Stats', 'manage_options', 'shogo-counter-stats-options', array( 'Shogo_Admin_Menu', 'summary' ));
		add_submenu_page( 'shogo-counter-stats-options', 'Page Views', 'Page Views', 'manage_options', 'shogo-counter-stats-page-view', array( 'Shogo_Admin_Menu', 'page_views' ) );
		add_submenu_page( 'shogo-counter-stats-options', 'Logs', 'Logs', 'manage_options', 'shogo-counter-stats-logs', array( 'Shogo_Admin_Menu', 'logs' ) );
		add_submenu_page( 'shogo-counter-stats-options', 'Web Browsers/OS', 'Web Browsers/OS', 'manage_options', 'shogo-counter-stats-country-web-browser-os', array( 'Shogo_Admin_Menu', 'webbrowser_os' ) );
		add_submenu_page( 'shogo-counter-stats-options', 'Clear Statistics', 'Clear Statistics', 'manage_options', 'shogo-counter-stats-country-clear-stats', array( 'Shogo_Admin_Menu', 'clear_stats' ) );
		add_submenu_page( 'shogo-counter-stats-options', 'About Plugin', 'About Plugin', 'manage_options', 'shogo-counter-stats-about', array( 'Shogo_Admin_Menu', 'about' ) );

		//Call constructor;
		new Shogo_Admin_Menu();
	}

	/*
	*	Summary and main page of admin tab.
	*	@version 1.0.0
	*/
	public function summary() {

		//Referencing current page.
		self::$page = 'summary';

		//Implement wordpred $wpdb.
		global $wpdb;

		/*
		*	Check if there is a hits from the database.
		*/
		if ( self::$has_hits !== "0" ) {

			//Get Local Timezone from wp_option.
			$local_timezone = get_option('timezone_string');

			//Implement Local Timezone.
			date_default_timezone_set($local_timezone);

			//Count all information from the database table.
			$count_hits = self::$has_hits;

			//table name
			$counter_table = self::$table;

			//Get all hits depends on the numbers of ip address.
			$hits = $wpdb->get_results("SELECT DISTINCT `country`, `country_code` FROM $counter_table GROUP BY `country` ORDER BY COUNT(*) DESC");

			//Get all unique
			$unique_counts = $wpdb->get_results("SELECT DISTINCT `ip` FROM $counter_table GROUP BY `ip` ORDER BY COUNT(*) DESC");

			//Count unique arrays results.
			$unique_counts = count($unique_counts);

			//Get dates results from the database.
			$dates = $wpdb->get_results("SELECT `date` FROM $counter_table");

			//Get current date from the server.
			$now = date("Y-m-d");

			//Default values fof foreach loop results.
			$today = array();
			$this_week = array();
			$this_month = array();
			$this_year = array();

			foreach ($dates as $d) {

				//Seperate Date Elements.
				$date = explode( ' ', $d->date );

				//The date("Y-m-d") format.
				$ymd = explode( '-', $date[0] );

				//Get Month
				$month = $ymd[1];

				//Get Year
				$year = $ymd[0];

				//Get Today Results.
				if ( $date[0] ==  $now ) {
					$today[] = $d;
				}

				//Get Yesterday Results.
				if ( $date[0] >  date("Y-m-d",strtotime("-1 day") ) ) {
					$yesterday[] = $d;
				}

				//Get Last 7 Days Results.
				if ( $date[0] >  date("Y-m-d",strtotime("-7 day") ) ) {
					$this_week[] = $d;
				}

				//Get This Month Results.
				if ( $month ==  date("m") ) {
					$this_month[] = $d;
				}

				//Get This Year Results.
				if ( $year == date("Y") ) {
					$this_year[] = $d;
				}
			}

			//Count Today Results as hits.
			$today = count($today);

			//Count Yesterday Results as hits.
			$yesterday = count($yesterday);

			//Count 7 Days Results as hits.
			$this_week = count($this_week);

			//Count This Month Results as hits.
			$this_month = count($this_month);

			//Count This Year Results as hits.
			$this_year = count($this_year);


			$path = '../wp-content/uploads/shogo_counter_stats_reports';
			//$files = scandir( $path );

			//Current Dir.
			chdir( $path );

			//Get File List from Dir and Sort Order by Date Descending.
			array_multisort( array_map( 'filemtime', ( $files = glob( "*.*" ) ) ), SORT_DESC, $files );

			//Check if Current Dir has .log Files. By Default is false.
			$has_weekly_reports = false;

			//Check if $files has .log files.
			if ( count( $files ) !== 1 && count( $files ) !== 0 ) {

				//Has .log files.
				$has_weekly_reports = true;
			}
		}

		ob_start();

		//Implement Navigation Menu for this page.
		self::nav_menu();
		?>

		<?php
		/*
		*	Check if there is a hits from the database.
		*/
		?>
		<?php if ( self::$has_hits !== "0" ): ?>

			<?php
			/*
			*	For Wide Screen.
			*	Container for Summary Page.
			*/
			?>
			<div class="shogo-counter-stats-admin-container">
				<div class="shogo-nav">
					<ul>
						<li><a href="javascript:" id="shogo_visitor_count">Visitor's Count</a></li>
						<li><a href="javascript:" id="shogo_stats">Statistics</a></li>
						<li><a href="javascript:" id="shogo_weekly_reports">Weekly Reports</a></li>
					</ul>
					<div class="shogo-clear"></div>
				</div>
				<div class="shogo-nav-mobile">
					<select id="summary_select">
						<option value="visitors_count">Visitor's Count</option>
						<option value="stats">Statistics</option>
						<option value="weekly_reports">Weekly Reports</option>
					</select>
				</div>
				<div class="shogo-contents">
					<div class="shogo-counter-stats-admin-visitors-count shogo-summary-tab">
						<table>
							<tbody>
								<tr>
									<td>Total Hits:</td>
									<td><?php echo $count_hits; ?></td>
								</tr>
								<tr>
									<td>Unique Visitor's Count:</td>
									<td><?php echo $unique_counts; ?></td>
								</tr>
								<tr>
									<td>This Day:</td>
									<td><?php echo $today; ?></td>
								</tr>
								<tr>
									<td>Yesterday:</td>
									<td><?php echo $yesterday; ?></td>
								</tr>
								<tr>
									<td>This 7 Days:</td>
									<td><?php echo $this_week; ?></td>
								</tr>
								<tr>
									<td>This Month:</td>
									<td><?php echo $this_month; ?></td>
								</tr>
								<tr>
									<td>This Year:</td>
									<td><?php echo $this_year; ?></td>
								</tr>
							</tbody>
						</table>
					</div>

					<?php
					/*
					*	For Wide Screen.
					*	Conatainer for statistics page.
					*/
					?>
					<div class="shogo-counter-stats-container shogo-summary-tab">
						<div class="shogo-counter-stats-statistics-container">
							<table>
								<?php foreach ($hits as $hit): ?>
								<tr>
									<td>
										<?php if ( $hit->country_code !== 'Unknown' ): ?>
											<img src="<?php echo plugins_url( '../flags/'.$hit->country_code.'.png', __FILE__ ); ?>" />
										<?php else: ?>
											&nbsp;
										<?php endif; ?>
									</td>
									<td><?php echo $hit->country; ?></td>
									<td>
										<div class="shogo-counter-stats-percentage-container">
											<div class="shogo-counter-stats-progressbar" style="width: <?php echo shogo_getPercent( $hit->country_code ); ?>%"><?php echo shogo_getPercent( $hit->country_code ); ?>%</div>
										</div>
									</td>
								</tr>
								<?php endforeach; ?>
							</table>
						</div>
					</div>
				</div>

				<div class="shogo-counter-stats-weekly-reports-container shogo-summary-tab">
					<?php if ( !$has_weekly_reports ): ?>
						<div class="shogo-weekly-reports-empty">
							<p>There are no Weekly Reports at this time.</p>
							<p>Weekly Reports are generated every Sundays.</p>
						</div>
					<?php else: ?>
						<div class="shogo-weekly-contents">
							<div class="shogo-weekly-select-report-container">
								<label for="select_report">Select Report:</label>
								<select id="shogo_select_report">
									<?php foreach( $files as $file ): ?>
										<?php
											$ext = explode( '.', $file );
											$ext = end( $ext );

											$file = chop( $file, '.log' );
										?>
										<?php if ( $ext == 'log' ): ?>
											<option value="<?php echo $file; ?>"><?php echo $file; ?></option>
										<?php endif; ?>

									<?php endforeach; ?>
								</select>
							</div>
							<div class="shogo-weekly-select-report-ajax-loader">
								<img class="shogo-counter-stats-ajax-loader" src="<?php echo plugins_url( '../img/loader.gif', __FILE__ ); ?>" />
							</div>
							<div class="shogo-ajax-contents"></div>
						</div>
					<?php endif; ?>
				</div>

				<?php
				/*
				*	For Mobile Devices.
				*	Container for Summary Page.
				*/
				?>
				<div class="shogo-contents-mobile">
					<div class="shogo-hits-container-mobile shogo-summary-tab-mobile">
						<div class="shogo-total-hits">
							<div class="shogo-header">Total Hits</div>
							<div class="shogo-hits-contents">
								<?php echo $count_hits; ?>
							</div>
						</div>
						<div class="shogo-unique-hits">
							<div class="shogo-header">Unique Visitors</div>
							<div class="shogo-unique-hits-contents">
								<?php echo $unique_counts; ?>
							</div>
						</div>
						<div class="shogo-day-hits">
							<div class="shogo-header">This Day</div>
							<div class="shogo-day-hits-contents">
								<?php echo $today; ?>
							</div>
						</div>
						<div class="shogo-yesterday-hits">
							<div class="shogo-header">Yesterday</div>
							<div class="shogo-yesterday-contents">
								<?php echo $yesterday; ?>
							</div>
						</div>
						<div class="shogo-7days-hits">
							<div class="shogo-header">This Week</div>
							<div class="shogo-7days-contents">
								<?php echo $this_week; ?>
							</div>
						</div>
						<div class="shogo-year-hits">
							<div class="shogo-header">This Year</div>
							<div class="shogo-year-contents">
								<?php echo $this_year; ?>
							</div>
						</div>
					</div>

					<?php
					/*
					*	For Mobile Devices.
					*	Container for Summary Page.
					*/
					?>
					<div class="shogo-stats-container-mobile shogo-summary-tab-mobile">
						<table>
							<?php foreach ($hits as $hit): ?>
							<tr>
								<td>
									<?php if ( $hit->country_code !== 'Unknown' ): ?>
										<img src="<?php echo plugins_url( '../flags/'.$hit->country_code.'.png', __FILE__ ); ?>" />
									<?php else: ?>
										&nbsp;
									<?php endif; ?>
								</td>
								<td><?php echo $hit->country; ?></td>
								<td><?php echo shogo_getPercent( $hit->country_code ); ?>%</td>
							</tr>
							<?php endforeach; ?>
						</table>
					</div>
					<div class="shogo-counter-report-log-container-mobile shogo-summary-tab-mobile">
						<div class="shogo-select">
							<select id="shogo_select_log">
								<?php foreach( $files as $file ): ?>
								<?php
									$ext = explode( '.', $file );
									$ext = end( $ext );

									$file = chop( $file, '.log' );
								?>
								<?php if ( $ext == 'log' ): ?>
									<option value="<?php echo $file; ?>"><?php echo $file; ?></option>
								<?php endif; ?>
							<?php endforeach; ?>
							</select>
						</div>
						<div class="shogo-counter-stats-ajax-loader-report-log-container">
							<img class="shogo-counter-stats-ajax-loader" src="<?php echo plugins_url( '../img/loader.gif', __FILE__ ); ?>" />
						</div>
						<div class="shogo-ajax-contents"></div>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				var $ = jQuery;

				//Default Page Query.
				var page = "visitors_count";

				//Default Navigation Menu selected.
				$(".shogo-counter-stats-admin-container .shogo-nav ul li:nth-child(1)").css({"background-color": "yellow"});

				//Show Default Content for this page.
				$(".shogo-counter-stats-admin-visitors-count, .shogo-hits-container-mobile").fadeIn(500);

				//#shogo_visitor_count Click Event.
				$("#shogo_visitor_count").on("click", function() {
					if (page !== "visitors_count") {
						page = "visitors_count";

						$(".shogo-summary-tab").each(function() {
							$(this).hide(10, function() {
								$(".shogo-counter-stats-admin-container .shogo-nav ul li:nth-child(3)").css({"background-color": "transparent"});
								$(".shogo-counter-stats-admin-container .shogo-nav ul li:nth-child(2)").css({"background-color": "transparent"});
								$(".shogo-counter-stats-admin-container .shogo-nav ul li:nth-child(1)").css({"background-color": "yellow"});
								$(".shogo-counter-stats-admin-visitors-count").show();
							});
						});
					}
				});

				//#shogo_stats Click Event.
				$("#shogo_stats").on("click", function() {
					if (page !== "stats") {
						page = "stats";

						$(".shogo-summary-tab").each(function() {
							$(this).hide(10, function() {
								$(".shogo-counter-stats-admin-container .shogo-nav ul li:nth-child(1)").css({"background-color": "transparent"});
								$(".shogo-counter-stats-admin-container .shogo-nav ul li:nth-child(3)").css({"background-color": "transparent"});
								$(".shogo-counter-stats-admin-container .shogo-nav ul li:nth-child(2)").css({"background-color": "yellow"});
								$(".shogo-counter-stats-container").show();
							});
						});
					}
				});

				$("#shogo_weekly_reports").on("click", function() {
					if (page !== "weekly_reports") {
						page = "weekly_reports";

						$(".shogo-summary-tab").each(function() {
							$(this).hide(10, function() {
								$(".shogo-counter-stats-admin-container .shogo-nav ul li:nth-child(1)").css({"background-color": "transparent"});
								$(".shogo-counter-stats-admin-container .shogo-nav ul li:nth-child(2)").css({"background-color": "transparent"});
								$(".shogo-counter-stats-admin-container .shogo-nav ul li:nth-child(3)").css({"background-color": "yellow"});
								$(".shogo-counter-stats-weekly-reports-container").show();
							});
						});
					}
				});

				//.shogo-nav-mobile #summary_select Click Event. (For Mobile)
				$(".shogo-nav-mobile #summary_select").on("change", function() {
					var s = $(this).val();

					switch ( s ) {
						case "visitors_count":
							$(".shogo-summary-tab-mobile").each(function() {
								$(this).hide(10, function() {
									$(".shogo-hits-container-mobile").show();
								});
							});
						break;

						case "stats":
							$(".shogo-summary-tab-mobile").each(function() {
								$(this).hide(10, function() {
									$(".shogo-stats-container-mobile").show();
								});
							});
						break;

						case "weekly_reports":
							$(".shogo-summary-tab-mobile").each(function() {
								$(this).hide(10, function() {
									$(".shogo-counter-report-log-container-mobile").show();
								});
							});
						break;
					}
				});

				<?php if ( $has_weekly_reports ): ?>
					var report_data = {
						"action": "shogo_ajax",
						"operation": "get_weekly_report",
						"log_date": "<?php echo $files[0]; ?>"
					};

					$.post(ajaxurl, report_data, function( d ) {
						$(".shogo-counter-stats-weekly-reports-container .shogo-ajax-contents").html( d );
					})

					$("#shogo_select_report").on("change", function() {
						var log_date = $(this).val();

						$(".shogo-counter-stats-weekly-reports-container .shogo-ajax-contents").hide();
						$(".shogo-weekly-select-report-ajax-loader").show();

						var data = {
							"action": "shogo_ajax",
							"operation": "get_weekly_report",
							"log_date": log_date + ".log"
						};

						$.post(ajaxurl, data, function( d ) {
							$(".shogo-weekly-select-report-ajax-loader").hide();
							$(".shogo-counter-stats-weekly-reports-container .shogo-ajax-contents").show().html( d );
						})
					});

					var report_data_mobile = {
						"action": "shogo_ajax",
						"operation": "get_weekly_report_mobile",
						"log_date": "<?php echo $files[0]; ?>"
					};

					$.post(ajaxurl, report_data_mobile, function( d ) {
						$(".shogo-counter-report-log-container-mobile .shogo-ajax-contents").html( d );
					})

					$("#shogo_select_log").on("change", function() {
						var log_date = $(this).val();

						$(".shogo-counter-report-log-container-mobile .shogo-ajax-contents").hide();
						$(".shogo-counter-stats-ajax-loader-report-log-container").show();

						var data = {
							"action": "shogo_ajax",
							"operation": "get_weekly_report_mobile",
							"log_date": log_date + ".log"
						};

						$.post(ajaxurl, data, function( d ) {
							$(".shogo-counter-stats-ajax-loader-report-log-container").hide();
							$(".shogo-counter-report-log-container-mobile .shogo-ajax-contents").show();
							$(".shogo-counter-report-log-container-mobile .shogo-ajax-contents").html( d );
						})
					});
				<?php endif; ?>
			</script>
		<?php else: ?>

			<?php
			/*
			*	If no results found, redirect admin to this content.
			*/
			?>
			<?php echo self::no_stats(); ?>
		<?php endif; ?>

		<?php
		echo ob_get_clean();
	}

	/*
	*	Page viewed for admin tabs
	*	@version 1.0.0
	*/
	public function page_views() {

		//Referencing current page.
		self::$page = 'page_view';

		//Implement wordpred $wpdb.
		global $wpdb;

		/*
		*	Check if there is a hits from the database.
		*/
		if ( self::$has_hits !== "0" ) {

			//WP Prefix and database table name.
			$counter_table = $wpdb->prefix.'shogo_counter_stats';

			//Get Permalinks Results via Count.
			$pages = $wpdb->get_results( "SELECT DISTINCT `permalinks` FROM $counter_table GROUP BY `permalinks` ORDER BY COUNT(*) DESC LIMIT 0, 50" );
		}

		ob_start();

		//Implement Navigation Menu for this page.
		self::nav_menu();
		?>

		<?php
		/*
		*	Check if there is a hits from the database.
		*/
		?>
		<?php if ( self::$has_hits !== "0" ): ?>
			<?php
			/*
			*	For Wide Screen.
			*/
			?>
			<div class="shogo_counter_stats_pageviews_container">
				<table cellpadding="0" cellspacing="0">
					<thead>
						<th>Permalinks</th>
						<th>Hits</th>
					</thead>
					<tbody>
						<?php foreach ( $pages as $page ): ?>
							<tr>
								<td>
									<?php
										if (strlen( $page->permalinks ) >= 100) {
											echo '<span style="cursor: pointer;" onclick="javascript: pageDetails(\''.$page->permalinks.'\')" title="'.$page->permalinks.'">'.substr($page->permalinks, 0, 100).'...</span>';
										} else {
											echo '<span style="cursor: pointer;" onclick="javascript: pageDetails(\''.$page->permalinks.'\')" title="'.$page->permalinks.'">'.$page->permalinks.'</span>';
										}
									?>
								</td>
								<td><?php echo shogo_getPageCount( $page->permalinks ); ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>

				<div class="shogo-counter-stats-ajax-loader-container">
					<img class="shogo-counter-stats-ajax-loader" src="<?php echo plugins_url( '../img/ajax-loader.gif', __FILE__ ); ?>" />
				</div>
				<div class="shogo-stats-counter-loadmore-container">
					<span title="Back to Top" id="shogo_back_to_top">
						<img class="shogo-counter-stats-top-btn" src="<?php echo plugins_url( '../img/top.png', __FILE__ ); ?>" />
					</span>
					<span class="shogo-stats-counter-loader button-primary" id="shogo_show_more">Show More</span>
				</div>
			</div>

			<?php
			/*
			*	For Mobile.
			*/
			?>
			<div class="shogo_counter_stats_pageviews_container_mobile">
				<div class="shogo_counter_stats_pageviews_contents_mobile">
					<?php foreach ( $pages as $page ): ?>
						<ul>
							<li>Hits: <?php echo shogo_getPageCount( $page->permalinks ); ?></li>
							<li>
								<?php
									if (strlen( $page->permalinks ) >= 80) {
										echo '<span title="'.$page->permalinks.'">'.substr($page->permalinks, 0, 80).'...</span>';
									} else {
										echo '<span title="'.$page->permalinks.'">'.$page->permalinks.'</span>';
									}
								?>
							</li>
						</ul>
					<?php endforeach; ?>
				</div>
				<div class="shogo-counter-stats-ajax-loader-container">
					<img class="shogo-counter-stats-ajax-loader" src="<?php echo plugins_url( '../img/ajax-loader.gif', __FILE__ ); ?>" />
				</div>
				<div class="shogo-counter-stats-ajax-loader-container-mobile">
					<span title="Back to Top" id="shogo_back_to_top_mobile">
						<img class="shogo-counter-stats-top-btn" src="<?php echo plugins_url( '../img/top.png', __FILE__ ); ?>" />
					</span>
					<span class="shogo-stats-counter-loader-mobile button-primary" id="shogo_show_more_mobile">Show More</span>
				</div>
			</div>

			<script type="text/javascript">
				var $ = jQuery;

				//Default number of result.
				var rows = 50;

				//#shogo_show_more CLick Event (Read More)
				$("#shogo_show_more").on("click", function() {

					//Hide Read More Button.
					$(".shogo-stats-counter-loadmore-container").hide();

					//Show Ajax Loader GIF.
					$(".shogo-counter-stats-ajax-loader-container").show();

					//Increment the number of results +50
					rows = rows + 50;

					//Data values send to php via post request
					var data = {
						"action": "shogo_ajax",
						"operation": "page_view_load_more",
						"rows": rows
					}

					//Ajax Request and Response to get results from the database.
					$.post(ajaxurl, data, function( d ) {

						//Hide Ajax Loader GIF.
						$(".shogo-counter-stats-ajax-loader-container").hide();

						//Show Read More Button
						$(".shogo-stats-counter-loadmore-container").show();

						//Append html elements with ajax results.
						$(".shogo_counter_stats_pageviews_container table tbody").html( d );
					});
				});

				//#shogo_back_to_top Click Event. scroll web browser to the top of the page.
				$("#shogo_back_to_top").on("click", function() {
					$("body").animate({
						scrollTop: "0px"
					}, 500);
				});

				//"#shogo_back_to_top_mobile Click Event. scroll web browser to the top of the page. (Mobile).
				$("#shogo_back_to_top_mobile").on("click", function() {
					$("body").animate({
						scrollTop: "0px"
					}, 500);
				});

				//#shogo_show_more CLick Event (Read More Mobile Mode).
				$("#shogo_show_more_mobile").on("click", function() {

					//Hide Read More Button.
					$(".shogo-counter-stats-ajax-loader-container-mobile").hide();

					//Show Ajax Loader GIF.
					$(".shogo-counter-stats-ajax-loader-container").show();

					//Increment the number of results +50
					rows = rows + 50;

					//data values send to php.
					var data = {
						"action": "shogo_ajax",
						"operation": "page_view_load_more_mobile",
						"rows": rows
					};

					//Ajax Request and Response to get results from the database.
					$.post(ajaxurl, data, function( d ) {

						//Hide Ajax Loader GIF.
						$(".shogo-counter-stats-ajax-loader-container").hide();

						//Show Read More Button
						$(".shogo-counter-stats-ajax-loader-container-mobile").show();

						//Append html elements with ajax results.
						$(".shogo_counter_stats_pageviews_contents_mobile").html( d );
					});
				});
			</script>
		<?php else: ?>

			<?php
			/*
			*	If no results found, redirect admin to this content.
			*/
			?>
			<?php echo self::no_stats(); ?>
		<?php endif; ?>

		<?php
		echo ob_get_clean();
	}

	/*
	*	Logs page for admin tab
	*	@version 1.0.0
	*/
	public function logs() {

		//Referencing current page.
		self::$page = 'logs';

		//Implement wordpred $wpdb.
		global $wpdb;

		/*
		*	Check if there is a hits from the database.
		*/
		if ( self::$has_hits !== "0" ) {

			//WP Prefix and database table name.
			$counter_table = $wpdb->prefix.'shogo_counter_stats';

			//Get Hits Results from the Database.
			$hits = $wpdb->get_results( "SELECT DISTINCT * FROM $counter_table GROUP BY `ip` ORDER BY `id` DESC LIMIT 0, 50" );
		}

		ob_start();

		//Implement Navigation Menu for this page.
		self::nav_menu();
		?>

		<?php
		/*
		*	Check if there is a hits from the database.
		*/
		?>
		<?php if ( self::$has_hits !== "0" ): ?>
			<div class="shogo-counter-stats-visitors-info">
				<div class="shogo-header">
					<div class="shogo-left"></div>
					<div class="shogo-right">
						<img id="shogo_close" src="<?php echo plugins_url( '../img/x.png', __FILE__ ); ?>" />
					</div>
					<div class="shogo-clear"></div>
				</div>
				<div class="shogo-contents"></div>
			</div>

			<div class="shogo-counter-stats-visitors-info-mobile">
				<div class="shogo-header">
					<img id="shogo_close_mobile" src="<?php echo plugins_url( '../img/x.png', __FILE__ ); ?>" />
				</div>
				<div class="shogo-contents"></div>
			</div>

			<div class="shogo-counter-stats-csi-search-container">
				<form method="post" action="#" accept-charset="utf-8">
					<input type="text" name="s" placeholder="Search IP Adddress Here" autocomplete="off" />
					<input type="submit" value="Search" />
				</form>
			</div>

			<?php
			/*
			*	Default Container/Content if no POST (no search query) Request.
			*/
			?>
			<?php if ( !$_POST ): ?>

				<?php
				/*
				*	For Wide Screen.
				*/
				?>
				<div class="shogo-counter-stats-country_state_isp-container">
					<table cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<th>&nbsp;</th>
								<th>IP Address</th>
								<th>Hits</th>
								<th>Hostname</th>
								<th>Region/State</th>
								<th>ISP</th>
								<th>Location</th>
							</tr>
						</thead>
						<tbody>
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
						</tbody>
					</table>

					<div class="shogo-counter-stats-ajax-loader-container">
						<img class="shogo-counter-stats-ajax-loader" src="<?php echo plugins_url( '../img/ajax-loader.gif', __FILE__ ); ?>" />
					</div>
					<div class="shogo-stats-counter-loadmore-container">
						<span title="Back to Top" id="shogo_back_to_top">
							<img class="shogo-counter-stats-top-btn" src="<?php echo plugins_url( '../img/top.png', __FILE__ ); ?>" />
						</span>
						<span class="shogo-stats-counter-loader button-primary" id="shogo_show_more">Show More</span>
					</div>
				</div>

				<?php
				/*
				*	For Mobile.
				*/
				?>
				<div class="shogo-counter-stats-country_state_isp-container-mobile">
					<div class="shogo-stats-contents">
						<?php foreach ( $hits as $hit ): ?>
							<div class="shogo-stats-logs">
								<div class="shogo-header">IP Address: <?php echo $hit->ip; ?></div>
								<ul>
									<li>Hits: <?php echo shogo_getHitsViaIp( $hit->ip ); ?></li>
									<li>
										Country: <?php if ( $hit->country !== 'Unknown' ): ?> <img src="<?php echo plugins_url( '../flags/'.$hit->country_code.'.png', __FILE__ ); ?>" /> <?php  echo $hit->country; ?> <?php else: ?> &nbsp; <?php endif; ?>
									</li>
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
					</div>
					<div class="shogo-counter-stats-ajax-loader-container">
						<img class="shogo-counter-stats-ajax-loader" src="<?php echo plugins_url( '../img/ajax-loader.gif', __FILE__ ); ?>" />
					</div>
					<div class="shogo-stats-counter-loadmore-container">
						<span title="Back to Top" id="shogo_back_to_top_mobile">
							<img class="shogo-counter-stats-top-btn" src="<?php echo plugins_url( '../img/top.png', __FILE__ ); ?>" />
						</span>
						<span class="shogo-stats-counter-loader button-primary" id="shogo_show_more_mobile">Show More</span>
					</div>
				</div>
			<?php else: ?>

				<?php
				/*
				*	If There is a post request and has Search query.
				*/
				?>
				<?php
					//Get IP addres from post response.
					$ip = strip_tags($_POST['s']);

					//Check if there is a results from the keyword.
					$count = $wpdb->get_var( "SELECT COUNT(*) FROM $counter_table WHERE `ip` LIKE '%$ip%'" );

					//If there is a results.
					if ($count !== "0") {
						//Get the results depends on the keywords.
						$hits = $wpdb->get_results( "SELECT DISTINCT * FROM $counter_table WHERE `ip` LIKE '%$ip%' GROUP BY `ip` ORDER BY `id` DESC LIMIT 0, 50" );
					}
				?>
				<?php if ($count !== "0"): ?>

				<?php
				/*
				*	For Wide Screen.
				*/
				?>
				<div class="shogo-counter-stats-country_state_isp-container">
					<table cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<th>&nbsp;</th>
								<th>IP Address</th>
								<th>Hits</th>
								<th>Hostname</th>
								<th>Region/State</th>
								<th>ISP</th>
								<th>Location</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach( $hits as $hit ): ?>
								<tr>
									<td><img title="<?php echo $hit->country; ?>" src="<?php echo plugins_url( '../flags/'.$hit->country_code.'.png', __FILE__ ); ?>" /></td>
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
						</tbody>
					</table>
				</div>

				<?php
				/*
				*	For Mobile.
				*/
				?>
				<div class="shogo-counter-stats-country_state_isp-container-mobile">
					<div class="shogo-stats-contents">
						<?php foreach ( $hits as $hit ): ?>
							<div class="shogo-stats-logs">
								<div class="shogo-header">IP Address: <?php echo $hit->ip; ?></div>
								<ul>
									<li>Hits: <?php echo shogo_getHitsViaIp( $hit->ip ); ?></li>
									<li>Country: <img src="<?php echo plugins_url( '../flags/'.$hit->country_code.'.png', __FILE__ ); ?>" /> <?php  echo $hit->country; ?></li>
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
					</div>
				</div>
				<?php else: ?>
					<div class="shogo-counter-stats-no-results-found">
						No results found.
					</div>
				<?php endif; ?>
			<?php endif; ?>

			<script type="text/javascript">
				var $ = jQuery;

				//Default number of result.
				var rows = 50;

				//#shogo_close Click Event. Closing floating window.
				$("#shogo_close").on("click", function() {
					$(".shogo-counter-stats-visitors-info").fadeOut(500, function() {
						$(".shogo-counter-stats-visitors-info .shogo-contents").html("");
					});
				});

				//#shogo_close_mobile Click Event. Closing floating window. (Mobile)
				$("#shogo_close_mobile").on("click", function() {
					$(".shogo-counter-stats-visitors-info-mobile").fadeOut(500, function() {
						$(".shogo-counter-stats-visitors-info-mobile .shogo-contents").html("");
					});
				});

				//#shogo_show_more Click Event. Show more results.
				$("#shogo_show_more").on("click", function() {

					//Hide Read MOre Button.
					$(".shogo-stats-counter-loadmore-container").hide();

					//Show Ajax LOader GIF.
					$(".shogo-counter-stats-ajax-loader-container").show();

					//Increment the number of results +50
					rows = rows + 50;

					//Data values send to php via post request
					var data = {
						"action": "shogo_ajax",
						"operation": "csi_load_more",
						"rows": rows
					}

					//Ajax Request and Response to get results from the database.
					$.post(ajaxurl, data, function( d ) {

						//Hide Read More Button.
						$(".shogo-counter-stats-ajax-loader-container").hide();

						//Hide ajax loader gif.
						$(".shogo-stats-counter-loadmore-container").show();

						//Append html elements with ajax results.
						$(".shogo-counter-stats-country_state_isp-container table tbody").html( d );
					});
				});

				//#shogo_show_more_mobile Click Event. Show more results. (Mobile)
				$("#shogo_show_more_mobile").on("click", function() {

					//Hide Read MOre Button.
					$(".shogo-stats-counter-loadmore-container").hide();

					//Hide ajax loader gif.
					$(".shogo-counter-stats-ajax-loader-container").show();

					//Increment the number of results +50
					rows = rows + 50;

					//Data values send to php via post request
					var data = {
						"action": "shogo_ajax",
						"operation": "csi_load_more_mobile",
						"rows": rows
					}

					//Ajax Request and Response to get results from the database.
					$.post(ajaxurl, data, function( d ) {

						//Hide ajax loader GIF.
						$(".shogo-counter-stats-ajax-loader-container").hide();

						//Show Read More Button.
						$(".shogo-stats-counter-loadmore-container").show();

						//Append html elements with ajax results.
						$(".shogo-counter-stats-country_state_isp-container-mobile .shogo-stats-contents").html( d );
					});
				});

				//#shogo_back_to_top Click Event. Scroll window to the top.
				$("#shogo_back_to_top").on("click", function() {
					$("body").animate({
						scrollTop: "0px"
					}, 500);
				});

				//#shogo_back_to_top Click Event. Scroll window to the top. (Mobile)
				$("#shogo_back_to_top_mobile").on("click", function() {
					$("body").animate({
						scrollTop: "0px"
					}, 500);
				});

				//Method for IP Information window.
				function shogo_ipinfo( ip ) {

					//Data values send to php via post request.
					var data = {
						"action": "shogo_ajax",
						"operation": "user_ip_info",
						"ip": ip
					};

					//Ajax Request and Response to get results from the database.
					$.post(ajaxurl, data, function( d ) {

						//Set tile to the header floating window.
						$(".shogo-counter-stats-visitors-info .shogo-header .shogo-left").html( ip + " Information" );

						//Open floating window.
						$(".shogo-counter-stats-visitors-info").fadeIn( 500, function() {

							//Append results from the database.
							$(".shogo-counter-stats-visitors-info .shogo-contents").html( d );
						} );
					});
				}

				//Method for IP Information window. (Mobile)
				function shogo_ipinfo_mobile( ip ) {

					//Data values send to php via post request.
					var data = {
						"action": "shogo_ajax",
						"operation": "user_ip_info_mobile",
						"ip": ip
					};

					//Ajax Request and Response to get results from the database.
					$.post(ajaxurl, data, function( d ) {
						$(".shogo-counter-stats-visitors-info-mobile").fadeIn(500, function() {
							$(".shogo-counter-stats-visitors-info-mobile .shogo-contents").html( d );
						});
					});
				}
			</script>
		<?php else: ?>

			<?php
			/*
			*	If no results found, redirect admin to this content.
			*/
			?>
			<?php echo self::no_stats(); ?>
		<?php endif; ?>

		<?php
		echo ob_get_clean();
	}

	/*
	*	Web browsers / Operating System Page
	*	@version 1.0.0
	*/
	public function webbrowser_os() {

		//Referencing current page.
		self::$page = 'webbrowser_os';

		//Implementing Wordpress $wpdb.
		global $wpdb;

		/*
		*	Check if there is a hits from the database.
		*/
		if ( self::$has_hits !== "0" ) {

			//WP Prefix and Database Table name.
			$counter_table = $wpdb->prefix.'shogo_counter_stats';

			//Get Web Browsers Results.
			$browsers = $wpdb->get_results( "SELECT DISTINCT `browser` FROM $counter_table GROUP BY `browser` ORDER BY COUNT(*) DESC" );

			//Get Operating System Results.
			$oss = $wpdb->get_results( "SELECT DISTINCT `os` FROM $counter_table GROUP BY `os` ORDER BY COUNT(*) DESC" );
		}

		ob_start();

		//Implement Navigation Menu for this page.
		self::nav_menu();
		?>

		<?php
		/*
		*	Check if there is a hits from the database.
		*/
		?>
		<?php if ( self::$has_hits !== "0" ): ?>
			<div class="shogo-counter-stats-webbrowser_os-container">

				<?php
				/*
				*	For Wide Screen.
				*/
				?>
				<div class="shogo-nav">
					<ul>
						<li><a href="javascript:" id="shogo_web_browsers">Web Browsers</a></li>
						<li><a href="javascript:" id="shogo_os">Operating System</a></li>
					</ul>
					<div class="shogo-clear"></div>
				</div>

				<?php
				/*
				*	For Mobile.
				*/
				?>
				<div class="shogo-nav-mobile">
					<select>
						<option value="web_browsers">Web Browsers</option>
						<option value="os">Operating System</option>
					</select>
				</div>
				<div class="shogo-contents">
					<div class="shogo-counter-stats-webbrowser-container">
						<table cellpadding="0" cellspacing="0">
							<thead>
								<tr>
									<th>Web Browser</th>
									<th>Hits</th>
								</tr>
								<tbody>
									<?php foreach( $browsers as $browser ): ?>
										<tr>
											<td><?php echo $browser->browser; ?></td>
											<td><?php echo shogo_getHitsViaBrowser( $browser->browser ); ?></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</thead>
						</table>
					</div>
					<div class="shogo-counter-stats-os-container">
						<table cellpadding="0" cellspacing="0">
							<thead>
								<tr>
									<th>Operating System</th>
									<th>Hits</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach( $oss as $os ): ?>
									<tr>
										<td><?php echo $os->os; ?></td>
										<td><?php echo shogo_getHitsViaOs( $os->os ); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				var $ = jQuery;

				//Default Tab and Referencing Tab.
				var page = "web_browsers";

				//Default Content for this page.
				$(".shogo-counter-stats-webbrowser-container").fadeIn(500);

				//Default selected Tab.
				$(".shogo-counter-stats-webbrowser_os-container .shogo-nav ul li:first-child").css({"background-color": "yellow"});

				//Switch Content to Web Browsers.
				$("#shogo_web_browsers").on("click", function() {

					//If Page reference is not "web_browsers".
					if (page !== "web_browsers") {

						//Change Page Referencing to "web_browsers".
						page = "web_browsers";

						//Fading out Operating System Content.
						$(".shogo-counter-stats-os-container").fadeOut(500, function() {

							//Set hilight color to Web Browsers Tab.
							$(".shogo-counter-stats-webbrowser_os-container .shogo-nav ul li:first-child").css({"background-color": "yellow"});

							//Remove hilight of Operating System Tab.
							$(".shogo-counter-stats-webbrowser_os-container .shogo-nav ul li:last-child").css({"background-color": "transparent"});

							//Fade in Web Browsers Content.
							$(".shogo-counter-stats-webbrowser-container").fadeIn( 500 );
						});
					}
				});

				//Switch Content to Web Browsers.
				$("#shogo_os").on("click", function() {

					//If Page reference is not "os".
					if (page !== "os") {

						//Change Page Referencing to "os".
						page = "os";

						//Fading out Web Browsers Content.
						$(".shogo-counter-stats-webbrowser-container").fadeOut(500, function() {

							//Set hilight color to Operating System Tab.
							$(".shogo-counter-stats-webbrowser_os-container .shogo-nav ul li:last-child").css({"background-color": "yellow"});

							//Remove hilight of Web Browsers Tab.
							$(".shogo-counter-stats-webbrowser_os-container .shogo-nav ul li:first-child").css({"background-color": "transparent"});

							//Fade in Operating System Content.
							$(".shogo-counter-stats-os-container").fadeIn( 500 );
						});
					}
				});

				//Content Switching on Mobile Mode.
				$(".shogo-counter-stats-webbrowser_os-container .shogo-nav-mobile select").on("change", function() {

					//Get the value of <select />.
					var s = $(this).val();

					//Conditional Statement
					switch ( s ) {

						//If var s is = "web_browsers"
						case "web_browsers":

							//Fade Out Operating System Content.
							$(".shogo-counter-stats-os-container").fadeOut(500, function() {

								//Change Page Referencing to "web_browsers".
								page = "web_browsers";

								//Set hilight color to Web Browsers Tab.
								$(".shogo-counter-stats-webbrowser_os-container .shogo-nav ul li:first-child").css({"background-color": "yellow"});

								//Remove hilight of Operating System Tab.
								$(".shogo-counter-stats-webbrowser_os-container .shogo-nav ul li:last-child").css({"background-color": "transparent"});

								//Fade in Web Browsers Content.
								$(".shogo-counter-stats-webbrowser-container").fadeIn( 500 );
							});
						break;

						case "os":
							$(".shogo-counter-stats-webbrowser-container").fadeOut(500, function() {

								//Change Page Referencing to "os".
								page = "os";

								//Set hilight color to Operating System Tab.
								$(".shogo-counter-stats-webbrowser_os-container .shogo-nav ul li:last-child").css({"background-color": "yellow"});

								//Remove hilight of Web Browsers Tab.
								$(".shogo-counter-stats-webbrowser_os-container .shogo-nav ul li:first-child").css({"background-color": "transparent"});

								//Fade in Operating System Content.
								$(".shogo-counter-stats-os-container").fadeIn( 500 );
							});
						break;
					}
				});
			</script>
		<?php else: ?>

			<?php
			/*
			*	If no results found, redirect admin to this content.
			*/
			?>
			<?php echo self::no_stats(); ?>
		<?php endif; ?>
		<?php
		echo ob_get_clean();
	}

	/*
	*	Clear Stats Page
	*	@version 1.0.0
	*/
	public function clear_stats() {

		//Default Tab and Referencing Tab.
		self::$page = 'clear_stats';

		ob_start();

		//Implement Navigation Menu for this page.
		self::nav_menu();
		?>

		<?php
		/*
		*	Check if there is a hits from the database.
		*/
		?>
		<?php if (self::$has_hits !== "0"): ?>
			<div class="shogo-counter-stats-clear-stats-container">
				<p>Clicking the button will clear all statistics record and hits.</p>
				<div class="shogo-button-container">
					<span class="button-primary" id="shogo_clear">Clear Statistics</span>
				</div>
				<div class="shogo-counter-stats-clear-stats-loader">
					<img src="<?php echo plugins_url( '../img/loader.gif', __FILE__ ); ?>" />
				</div>
			</div>

			<script type="text/javascript">
				var $ = jQuery;

				//#shogo_clear Click Event. Clear Statistics Button.
				$("#shogo_clear").on("click", function() {

					//Confirm Admin if he is sure.
					var c = confirm( "Are you sure you want to clear your stats? It cannot be undone." );

					//If Admin is sure to clear his stats.
					if ( c ) {

						//Hide Clear Button and for Mobile.
						$(".shogo-counter-stats-clear-stats-container .shogo-button-container").hide();

						//Show Ajax Loader.
						$(".shogo-counter-stats-clear-stats-loader").show();

						//Timer before executing action.
						setTimeout(function() {

							//Data values send to php via post request.
							var data = {
								"action": "shogo_ajax",
								"operation": "clear-stats"
							};

							//Execute Clearing Stats Action.
							$.post(ajaxurl, data, function( d ) {

								//Redirect to Summary page after Clearing Stats.
								location = "<?php echo site_url(); ?>/wp-admin/admin.php?page=shogo-counter-stats-options";
							});
						}, 3000);
					}
				});
			</script>
		<?php else: ?>

			<?php
			/*
			*	If no results found, redirect admin to this content.
			*/
			?>
			<?php echo self::no_stats(); ?>
		<?php endif; ?>
		<?php
	}

	/*
	*	About Plugin Page
	*	@version 1.0.0
	*/
	public function about() {
		self::$page = 'about';
		ob_start();
		self::nav_menu();
		?>

		<div class="shogo-counter-stats-about-container">
			<p>Shogo's Counter Stats Version 1.0.0</p>
			<p>Plugin Created by Gervic</p>
			<p>Email: gervic@gmx.com</p>
			<p>Facebook: <a href="https://www.facebook.com/gervic23" target="_blank">https://www.facebook.com/gervic23</a></p>
		</div>

		<?php
		echo ob_get_clean();
	}

	/*
	*	Admin Navigation Menu Layout.
	*	@version 1.0.0
	*/
	private static function nav_menu() {
		ob_start();
		?>

		<?php
		/*
		*	For Wide Screen.
		*/
		?>
		<div class="shogo-counter-stats-admin-nav-bar">
			<ul>
				<li <?php if (self::$page == 'summary'): ?> style="border: 1px solid #a6a8a7; background-color: yellow;" <?php endif; ?>><a href="<?php echo site_url().'/wp-admin/admin.php?page=shogo-counter-stats-options'; ?>">Summary</a></li>
				<li <?php if (self::$page == 'page_view'): ?> style="border: 1px solid #a6a8a7; background-color: yellow;" <?php endif; ?>><a href="<?php echo site_url().'/wp-admin/admin.php?page=shogo-counter-stats-page-view'; ?>">Page Views</a></li>
				<li <?php if (self::$page == 'logs'): ?> style="border: 1px solid #a6a8a7; background-color: yellow;" <?php endif; ?>><a href="<?php echo site_url().'/wp-admin/admin.php?page=shogo-counter-stats-logs'; ?>">Logs</a></li>
				<li <?php if (self::$page == 'webbrowser_os'): ?> style="border: 1px solid #a6a8a7; background-color: yellow;" <?php endif; ?>><a href="<?php echo site_url().'/wp-admin/admin.php?page=shogo-counter-stats-country-web-browser-os'; ?>">Web Browsers/OS</a></li>
				<li <?php if (self::$page == 'clear_stats'): ?> style="border: 1px solid #a6a8a7; background-color: yellow;" <?php endif; ?>><a href="<?php echo site_url().'/wp-admin/admin.php?page=shogo-counter-stats-country-clear-stats'; ?>">Clear Statistics</a></li>
				<li <?php if (self::$page == 'about'): ?> style="border: 1px solid #a6a8a7; background-color: yellow;" <?php endif; ?>><a href="<?php echo site_url().'/wp-admin/admin.php?page=shogo-counter-stats-about'; ?>">About Plugin</a></li>
			</ul>
			<div class="clear"></div>
		</div>

		<?php
		/*
		* For Mobile
		*/
		?>
		<div class="shogo-counter-stats-admin-nav-bar-mobile">
			<select id="shogo-nav-select">
				<option value="summary" <?php if (self::$page == 'summary'): ?>selected<?php endif; ?>>Summary</option>
				<option value="page_views" <?php if (self::$page == 'page_view'): ?>selected<?php endif; ?>>Page Views</option>
				<option value="logs" <?php if (self::$page == 'logs'): ?>selected<?php endif; ?>>Logs</option>
				<option value="webbrowsers_os" <?php if (self::$page == 'webbrowser_os'): ?>selected<?php endif; ?>>Web Browsers/OS</option>
				<option value="clear_stats" <?php if (self::$page == 'clear_stats'): ?>selected<?php endif; ?>>Clear Statistics</option>
				<option value="about" <?php if (self::$page == 'about'): ?>selected<?php endif; ?>>About Plugin</option>
			</select>
		</div>

		<script type="text/javascript">
			var $ = jQuery;

			/*
			*	Works only for Mobile Mode.
			*/
			$(".shogo-counter-stats-admin-nav-bar-mobile #shogo-nav-select").on("change", function() {
				var v = $(this).val();

				switch (v) {
					case "summary":
						location = "<?php echo site_url().'/wp-admin/admin.php?page=shogo-counter-stats-options'; ?>";
					break;

					case "page_views":
						location = "<?php echo site_url().'/wp-admin/admin.php?page=shogo-counter-stats-page-view'; ?>";
					break;

					case "logs":
						location = "<?php echo site_url().'/wp-admin/admin.php?page=shogo-counter-stats-logs'; ?>";
					break;

					case "webbrowsers_os":
						location = "<?php echo site_url().'/wp-admin/admin.php?page=shogo-counter-stats-country-web-browser-os'; ?>";
					break;

					case "clear_stats":
						location = "<?php echo site_url().'/wp-admin/admin.php?page=shogo-counter-stats-country-clear-stats'; ?>";
					break;

					case "about":
						location = "<?php echo site_url().'/wp-admin/admin.php?page=shogo-counter-stats-about'; ?>";
					break;
				}
			});
		</script>

		<?php
		echo ob_get_clean();
	}

	/*
	*	No result content. This shows if there no web traffics.
	*	@version 1.0.0
	*/
	private static function no_stats() {
		$str = '
			<div class="shogo-counter-stats-no-traffic">
				There are no traffic at this time.
			</div>
		';

		return $str;
	}
}
