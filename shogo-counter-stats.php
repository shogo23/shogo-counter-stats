<?php

/*
*	Plugin Name: Shogo's Counter Stats
*	Description: Track your visitors and monitor traffic and statistics of your website or blog.
*	Author: Victor Gerard Caviteno
*	Author URI: https://www.facebook.com/gervic23
*	Version: 1.0.0
*	Requires at least: 4.0
*	Tested up to: 4.6
*	License: GPLv2 or later
*	License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

//Exit if accesssed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Shogo_Counter_Stats {

	/*
	*	Initiates required methods
	*	@version 1.0.0
	*/
	public function __construct() {
		$this->includes();
		$this->init_hooks();
		$this->create_log();
	}

	/*
	*	Setup database table and page when plugin is activate.
	*	@version 1.0.0
	*/
	public function install() {
		global $wpdb;

		$table = $wpdb->prefix.'shogo_counter_stats';
		$charset_collate = $wpdb->get_charset_collate();

		//SQL for creating database table
		//@version 1.0.0
		$sql = "CREATE TABLE $table (
			id bigint(100) NOT NULL AUTO_INCREMENT,
			permalinks VARCHAR(255) DEFAULT '' NOT NULL,
			ip VARCHAR(200) DEFAULT '' NOT NULL,
			hostname VARCHAR(200) DEFAULT '' NOT NULL,
			country VARCHAR(255) DEFAULT '' NOT NULL,
			country_code VARCHAR(255) DEFAULT '' NOT NULL,
			region VARCHAR(255) DEFAULT '' NOT NULL,
			isp VARCHAR(255) DEFAULT '' NOT NULL,
			browser VARCHAR(255) DEFAULT '' NOT NULL,
			screensize VARCHAR(255) DEFAULT '' NOT NULL,
			os VARCHAR(255) DEFAULT '' NOT NULL,
			location VARCHAR(255) DEFAULT '' NOT NULL,
			date datetime DEFAULT '0000-00-00' NOT NULL,
			UNIQUE KEY id (id)
		) $charset_collate;";

		require_once( ABSPATH.'wp-admin/includes/upgrade.php' );

		dbDelta( $sql );

		//Database Table Version.
		add_option('shogo_counter_stats', '1.0');

		if ( ! file_exists( _('../wp-content/uploads/shogo_counter_stats_reports') )  ) {
			mkdir( _('../wp-content/uploads/shogo_counter_stats_reports') );

			$content = '';
			$fp = fopen( _('../wp-content/uploads/shogo_counter_stats_reports/index.php'), _('w') );
					fwrite( $fp, _($content) );
					fclose( $fp );
		}
	}

	/*
	*	Uninstall database table if plugin is deactivated.
	*	@version 1.0.0
	*/
	public function uninstall() {
		global $wpdb;
		$table = $wpdb->prefix.'shogo_counter_stats';

		$sql = "DROP TABLE IF EXISTS ".$table;
		$wpdb->query($sql);

		$this->delete_report_logs();
	}

	/*
	*	Includes required php files
	*	@version 1.0.0
	*/
	private function includes() {
		include_once( plugin_dir_path( __FILE__ ) . 'includes/functions.php' );
		include_once( plugin_dir_path( __FILE__ ) . 'includes/class_admin_dashboard.php' );
		include_once( plugin_dir_path( __FILE__ ) . 'includes/class_ajax.php' );
		include_once( plugin_dir_path( __FILE__ ) . 'includes/class_admin_menu.php' );
		include_once( plugin_dir_path( __FILE__ ) . 'includes/class_widget.php' );
	}

	/*
	*	Initiate wp hooks
	*	@version 1.0.0
	*/
	private function init_hooks() {

		//Initilize Hook After Plugin Activation.
		register_activation_hook( __FILE__, array( $this, 'install' ) );

		//Initilize Hook Before Plugin Deactivation.
		register_deactivation_hook( __FILE__, array( $this, 'uninstall' ) );

		//Initialize to include css scripts and js scripts in front end.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		//Initiate js tracker.
		add_action( 'wp_head', array( $this, 'tracker' ) );

		//Initialize to include css scripts and js scripts in Admin Panel.
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		//Initialize Dashboard Widget.
		add_action( 'wp_dashboard_setup', array( 'Shogo_Admin_Dashboard', 'display' ) );

		//Initialize Admin Tabs and Pages for this Plugin.
		add_action( 'admin_menu', array( 'Shogo_Admin_Menu', 'display' ) );

		//Initialize Ajax Actions for Admin.
		add_action( 'wp_ajax_shogo_ajax', array( 'Shogo_Ajax', 'init' ) );

		//Initialize Ajax Actions for Users.
		add_action( 'wp_ajax_nopriv_shogo_ajax', array( 'Shogo_Ajax', 'init' ) );

		//Initialize Plugin Widget.
		add_action( 'widgets_init', create_function( '', 'return register_widget( "Shogo_Widget" );') );
	}

	/*
	*	Visitor Tracker JS Script. Gathering information in Visitors.
	*	@version 1.0.0
	*/
	public function tracker() {
		?>

		<script type="text/javascript">
			var $ = jQuery;

			$.getJSON('http://ipinfo.io', function( data ){
			 	var shogo_uri = window.location.href;
				var shogo_screensize = $(window).width() + "x" + $(window).height();

				var shogo_cs_data = {
					"action": "shogo_ajax",
					"operation": "inserturi",
					"uri": shogo_uri,
					"screensize": shogo_screensize,
					"infos": data
				};

				$.post('<?php echo shogo_site_url(); ?>wp-admin/admin-ajax.php', shogo_cs_data);
			});
		</script>

		<?php
	}

	/*
	*	Includes css and js scripts for front end.
	*	@version 1.0.0
	*/
	public function enqueue_scripts() {

		//Enqueue jQuery.
		wp_enqueue_script( 'jquery' );
	}

	/*
	*	Includes css and js scripts for admin panel.
	*	@version 1.0.0
	*/
	public function admin_enqueue_scripts() {

		//Implement current page screening.
		global $pagenow;

		//Enqueue scripts only on selected pages.
		if ( $pagenow == 'index.php' || $pagenow == 'widgets.php' || $pagenow == 'admin.php' && ( $_GET['page'] == 'shogo-counter-stats-options' || $_GET['page'] == 'shogo-counter-stats-page-view' || $_GET['page'] == 'shogo-counter-stats-logs' || $_GET['page'] == 'shogo-counter-stats-country-web-browser-os' || $_GET['page'] == 'shogo-counter-stats-country-clear-stats' || $_GET['page'] == 'shogo-counter-stats-about' )  ) {
			wp_enqueue_style( 'shogo-admin-css', plugins_url( 'css/shogo-css-admin.css', __FILE__ ) );
		}
	}

	/*
	*	Create Weekly Hits Report.
	*	@version 1.0.0
	*/
	private function create_log() {

		//Implement Wordpress $wpdb.
		global $wpdb;

		//Get Local Timezone from wp_option table.
		$local_timezone = get_option( 'timezone_string' );

		//Set Local Timezone.
		date_default_timezone_set( $local_timezone );

		//Current Date.
		$now = date("Y-m-d");

		//Get the name of the date Ex. 2016-10-16 is Sunday.
		$day_name = date("D", strtotime($now));

		//Filename of Log File.
		$file_log = $now.'.log';

		//Current URI
		$url = $_SERVER['PHP_SELF'];

		//Seperate URI from (/) Slashes.
		$url = explode( '/', $url );

		//Get the 2nd Current URI.
		$url = $url[1];

		//Conditional Statements of URI.
		if ( $url == 'wp-admin' ) {

			//Set Path if 2nd Current URI is wp-admin.
			$path = '../wp-content/uploads/shogo_counter_stats_reports';
		} else {

			//Set Path if 2nd Current URI is not wp-admin.
			$path = './wp-content/uploads/shogo_counter_stats_reports';
		}

		//Report Log Creation only for Sunday.
		if ( $day_name == "Sun" && !file_exists( $path.'/'.$file_log ) ) {

			//Disable Error Header Sent during plugin activation in Sudays.
			error_reporting(0);

			//WP Prefix and Table Name.
			$counter_table = $wpdb->prefix.'shogo_counter_stats';

			//Get Hits' Date from database.
			$dates = $wpdb->get_results( "SELECT `date` FROM $counter_table" );

			//Set Array Values to get Result Counts.
			$sat = array();
			$fri = array();
			$thu = array();
			$wen = array();
			$tue = array();
			$mon = array();
			$sun = array();

			//Date after 7 days.
			$after_seven_days = date( "Y-m-d", strtotime("-7 day") );

			//Loop All Hits Dates.
			foreach ($dates as $d) {

				//Seperate Date and Time. Get "Y-m-d" Only.
				$date = explode( ' ', $d->date );

				//If results has Saturdays.
				if ( $date[0] ==  date("Y-m-d",strtotime("-1 day")) ) {

					//Get the Dates with Saturdays.
					$sat[] = $d;
				}

				//If results has Fridays.
				if ( $date[0] ==  date("Y-m-d",strtotime("-2 day")) ) {

					//Get the Dates with Fridays.
 					$fri[] = $d;
				}

				//If results has Thursdays.
				if ( $date[0] ==  date("Y-m-d",strtotime("-3 day")) ) {

					//Get the Dates with Thursdays.
 					$thu[] = $d;
				}

				//If results has Wednesday.
				if ( $date[0] ==  date("Y-m-d",strtotime("-4 day")) ) {

					//Get the Dates with Wednesdays.
 					$wen[] = $d;
				}

				//If results has Tuesdays.
				if ( $date[0] ==  date("Y-m-d",strtotime("-5 day")) ) {

					//Get the Dates with Tuesdays.
 					$tue[] = $d;
				}

				//If results has Mondays.
				if ( $date[0] ==  date("Y-m-d",strtotime("-6 day")) ) {

					//Get the Dates with Mondays.
 					$mon[] = $d;
				}

				//If results has Sundays.
				if ( $date[0] == date("Y-m-d",strtotime("-7 day")) ) {

					//Get the Dates with Sundays.
 					$sun[] = $d;
				}
			}

			//Count Saturday results as Hits.
			$sat = count( $sat );

			//Count Friday results as Hits.
			$fri = count( $fri );

			//Count Thursday results as Hits.
			$thu = count( $thu );

			//Count Wednesday results as Hits.
			$wen = count( $wen );

			//Count Tuesday results as Hits.
			$tue = count( $tue );

			//Count Monday results as Hits.
			$mon = count( $mon );

			//Count Sunday results as Hits.
			$sun = count( $sun );

			//Add all days hits to make weekly hits.
			$weekly_hits = $sat + $fri + $thu + $wen + $tue + $mon + $sun;

			//Get Unique Hits' Date from database.
			$unique_dates = $wpdb->get_results( "SELECT DISTINCT `date` FROM $counter_table GROUP BY `ip` ORDER BY COUNT(*) DESC" );

			//Set Array Values to get Result Counts.
			$u_sat = array();
			$u_fri = array();
			$u_thu = array();
			$u_wen = array();
			$u_tue = array();
			$u_mon = array();
			$u_sun = array();

			//Loop All Hits Dates.
			foreach ( $unique_dates as $u ) {
				//Seperate Date and Time. Get "Y-m-d" Only.
				$u = explode( ' ', $u->date );

				//If results has Saturdays.
				if ( $u[0] ==  date("Y-m-d",strtotime("-1 day")) ) {

					//Get the Dates with Saturdays.
					$u_sat[] = $d;
				}

				//If results has Fridays.
				if ( $u[0] ==  date("Y-m-d",strtotime("-2 day")) ) {

					//Get the Dates with Fridays.
 					$u_fri[] = $u;
				}

				//If results has Thursdays.
				if ( $u[0] ==  date("Y-m-d",strtotime("-3 day")) ) {

					//Get the Dates with Thursdays.
 					$u_thu[] = $u;
				}

				//If results has Wednesday.
				if ( $u[0] ==  date("Y-m-d",strtotime("-4 day")) ) {

					//Get the Dates with Wednesdays.
 					$u_wen[] = $u;
				}

				//If results has Tuesdays.
				if ( $u[0] ==  date("Y-m-d",strtotime("-5 day")) ) {

					//Get the Dates with Tuesdays.
 					$u_tue[] = $u;
				}

				//If results has Mondays.
				if ( $u[0] ==  date("Y-m-d",strtotime("-6 day")) ) {

					//Get the Dates with Mondays.
 					$u_mon[] = $u;
				}

				//If results has Sundays.
				if ( $date[0] == date("Y-m-d",strtotime("-7 day")) ) {

					//Get the Dates with Sundays.
 					$u_sun[] = $u;
				}
			}

			//Count Saturday results as Unique Hits.
			$u_sat = count( $u_sat );

			//Count Friday results as Unique Hits.
			$u_fri = count( $u_fri );

			//Count Thursday results as Unique Hits.
			$u_thu = count( $u_thu );

			//Count Wednesday results as Unique Hits.
			$u_wen = count( $u_wen );

			//Count Tuesday results as Unique Hits.
			$u_tue = count( $u_tue );

			//Count Monday results as Unique Hits.
			$u_mon = count( $u_mon );

			//Count Sunday results as Unique Hits.
			$u_sun = count( $u_sun );

			//Add all days hits to make weekly hits.
			$u_weekly_hits = $u_sat + $u_fri + $u_thu + $u_wen + $u_tue + $u_mon + $u_sun;

			//Set Header Statistics Date Report and Number of Day Hits into an Array.
			$data = array(
				'header' => $after_seven_days.'/'.$now.'.',
				'weekly_hits' => $weekly_hits,
				'u_weekly_hits' => $u_weekly_hits,
				'days' => array(
					'sat' => $sat,
					'fri' => $fri,
					'thu' => $thu,
					'wen' => $wen,
					'tue' => $tue,
					'mon' => $mon,
					'sun' => $sun
				),
				'u_days' => array(
					'sat' => $u_sat,
					'fri' => $u_fri,
					'thu' => $u_thu,
					'wen' => $u_wen,
					'tue' => $u_tue,
					'mon' => $u_mon,
					'sun' => $u_sun
				)
			);

			//Content of Log Weekly Report.
			$content = json_encode( $data );

			//Create a file log.
			$fp = fopen( $path.'/'.$file_log, 'w' );

				  //Write a content to the log file.
				  fwrite( $fp, $content );

				  //Close File Creation Init.
				  fclose( $fp );
		}
	}

	/*
	*	Delete Files and Dir of Weekly Logs Report during plugin deactivation.
	*	@version 1.0.0.
	*/
	private function delete_report_logs() {

		//Path of log files.
		$path = '../wp-content/uploads/shogo_counter_stats_reports';

		//Scanning the content of logs dir.
		$files = scandir( $path );

		//Unset 1st and 2nd index in array.
		unset( $files[0] );
		unset( $files[1] );

		//Deleting all files from Dir.
		foreach( $files as $file ) {
			unlink( $path.'/'.$file );
		}

		//Remove Dir.
		rmdir( $path );
	}
}

function SCS() {
	return new Shogo_Counter_Stats();
}

$GLOBALS['Shogo_Counter_Stats'] = SCS();
