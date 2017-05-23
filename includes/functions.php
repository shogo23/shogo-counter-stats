<?php

//Exit if accesssed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
*   Get site_url() of the website and check the right path of wordpress main dir to avoid errors.
*   @version 1.0.0
*/
function shogo_site_url() {

    //Get WP site url from the database
    $site_url = site_url().'/';

    //Explode via slash (/).
    $site_url = explode( '/', $site_url );

    //Count Array Values.
    $count = count( $site_url );

    //Get the Last Value of the Array
    $primary_path = end( $site_url );

    //Check if this is the right Wordpress Main Dir.
    if ( $count == 4 ) {
        return '/';
    } else if ( $count == 5 ) {
        return '/'.$primary_path.'/';
    }
}

/*
*	Get User's Web browser name
*	@version 1.0.0
*/
function shogo_get_browser_name() {

    //Get HTTP_USER_AGENT for Web Browser's User.
	$ExactBrowserNameUA = $_SERVER['HTTP_USER_AGENT'];

    //If Web Browser is Opera.
	if (strpos(strtolower($ExactBrowserNameUA), "safari/") and strpos(strtolower($ExactBrowserNameUA), "opr/")) {
	    $ExactBrowserNameBR = "Opera";

    //If Web Browser is Chrome.
	} else if (strpos(strtolower($ExactBrowserNameUA), "safari/") and strpos(strtolower($ExactBrowserNameUA), "chrome/")) {
	    $ExactBrowserNameBR= "Chrome";

    //If Web Browser is Internet Explorer.
	} else if (strpos(strtolower($ExactBrowserNameUA), "msie")) {
	    $ExactBrowserNameBR = "Internet Explorer";

    //If Web Browser is Firefox.
	} else if (strpos(strtolower($ExactBrowserNameUA), "firefox/")) {
	    $ExactBrowserNameBR = "Firefox";

    //If Web Browser is Safari.
	} else if (strpos(strtolower($ExactBrowserNameUA), "safari/") and strpos(strtolower($ExactBrowserNameUA), "opr/") == false and strpos(strtolower($ExactBrowserNameUA), "chrome/") == false) {
	    $ExactBrowserNameBR = "Safari";
	} else {

        //If Web Browser is Unknown.
	    $ExactBrowserNameBR = "Unknown Web Browser";
	};

	return $ExactBrowserNameBR;
}

/*
*	Get user's Operating System name
*	@version 1.0.0
*/
function shogo_get_os_name() {
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$os_platform = 'Unknown OS Platform';

    $os_array =   array(
        '/windows nt 10/i' => 'Windows 10',
        '/windows nt 6.3/i' => 'Windows 8.1',
        '/windows nt 6.2/i' => 'Windows 8',
        '/windows nt 6.1/i' => 'Windows 7',
        '/windows nt 6.0/i' => 'Windows Vista',
        '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
        '/windows nt 5.1/i' => 'Windows XP',
        '/windows xp/i' => 'Windows XP',
        '/windows nt 5.0/i' => 'Windows 2000',
        '/windows me/i' => 'Windows ME',
        '/win98/i' => 'Windows 98',
        '/win95/i' => 'Windows 95',
        '/win16/i' => 'Windows 3.11',
        '/macintosh|mac os x/i' => 'Mac OS X',
        '/mac_powerpc/i' => 'Mac OS 9',
        '/linux/i' => 'Linux',
        '/ubuntu/i' => 'Ubuntu',
        '/iphone/i' => 'iPhone',
        '/ipod/i' => 'iPod',
        '/ipad/i' => 'iPad',
        '/android/i' => 'Android',
        '/blackberry/i' => 'BlackBerry',
        '/webos/i' => 'Mobile'
    );

    foreach ( $os_array as $regex => $value ) { 

        if ( preg_match( $regex, $user_agent ) ) {
            $os_platform = $value;
        }
    }   

    return $os_platform;
}

/*
*   Get the percentage of countries.
*   @version 1.0.0
*/
function shogo_getPercent( $country_code ) {

    //Implement Wordpress $wpdb.
    global $wpdb;

    //WP Prefix and Table Name.
    $counter_table = $wpdb->prefix.'shogo_counter_stats';

    //Get All Hits.
    $overall_hits = $wpdb->get_var( "SELECT COUNT(*) FROM $counter_table" );

    //Get Country Code Hits.
    $country_count = $wpdb->get_var( "SELECT COUNT(*) FROM $counter_table WHERE `country_code` = '$country_code'" );

    //Convert country code to percentage.
    $percent = $country_count / $overall_hits * 100;

    //Set Decimal Number Format.
    $percent = number_format( $percent, 2 );

    //Explode Whole Number and Decimal Numbers.
    $percent_array = explode( '.', $percent );

    //If Decimal Number is 00 return number percent as Integer.
    if ($percent_array[1] == "00") {
        return $percent_array[0];
    }

    return $percent;
}

/*
*   Get Permalinks Count
*   @version 1.0.0
*/
function shogo_getPageCount( $permalinks ) {

    //Implement Wordpress $wpdb;
    global $wpdb;

    //WP Prefix and Table Name.
    $counter_table = $wpdb->prefix.'shogo_counter_stats';

    //Get Specific Permalink Hits.
    $count = $wpdb->get_var( "SELECT COUNT(*) FROM $counter_table WHERE `permalinks` = '$permalinks'" );

    return $count;
}

/*
*   Get hits via ip address
*   @version 1.0.0
*/
function shogo_getHitsViaIp( $ip ) {

    //Implement Wordpress $wpdb;
    global $wpdb;

    //WP Prefix and Table Name.
    $counter_table = $wpdb->prefix.'shogo_counter_stats';

    //Get Specific IP Address Hits.
    $count = $wpdb->get_var( "SELECT COUNT(*) FROM $counter_table WHERE `ip` = '$ip'" );
    
    return $count;
}

/*
*   Get hits web browers used by users.
*   @version 1.0.0
*/
function shogo_getHitsViaBrowser( $browser ) {

    //Implement Wordpress $wpdb;
    global $wpdb;

    //WP Prefix and Table Name.
    $counter_table = $wpdb->prefix.'shogo_counter_stats';

    //Get Specific Web Browser Hits.
    $count = $wpdb->get_var( "SELECT COUNT(*) FROM $counter_table WHERE `browser` = '$browser'" );
    
    return $count;
}

/*
*   Get hits operating system used by users.
*   @version 1.0.0
*/
function shogo_getHitsViaOs( $os ) {

    //Implement Wordpress $wpdb;
    global $wpdb;

    //WP Prefix and Table Name.
    $counter_table = $wpdb->prefix.'shogo_counter_stats';

    //Get Specific Operating System Hits.
    $count = $wpdb->get_var( "SELECT COUNT(*) FROM $counter_table WHERE `os` = '$os'" );

    return $count;
}

/*
*   Get Users Used Web browsers.
*   @version 1.0.0
*/
function shogo_get_user_web_browsers( $ip ) {

    //Implement Wordpress $wpdb;
    global $wpdb;

    //WP Prefix and Table Name.
    $counter_table = $wpdb->prefix.'shogo_counter_stats';

    //Get List of Web Browsers Used by the Users.
    $browsers = $wpdb->get_results( "SELECT DISTINCT `browser` FROM $counter_table WHERE `ip` = '$ip'" );

    return $browsers;
}

/*
*   Get Users Used Operating System used.
*   @version 1.0.0
*/
function shogo_get_user_os( $ip ) {

    //Implement Wordpress $wpdb;
    global $wpdb;

    //WP Prefix and Table Name.
    $counter_table = $wpdb->prefix.'shogo_counter_stats';

    //Get List of Operating System Used by the Users.
    $oss = $wpdb->get_results( "SELECT DISTINCT `os` FROM $counter_table WHERE `ip` = '$ip'" );

    return $oss;
}

/*
*   Get users permalinks hits
*   @version 1.0.0
*/
function shogo_get_user_hits( $ip, $permalinks ) {

    //Implement Wordpress $wpdb;
    global $wpdb;

    //WP Prefix and Table Name.
    $counter_table = $wpdb->prefix.'shogo_counter_stats';

    //Get User's Permalinks Hits.
    $count = $wpdb->get_var( "SELECT COUNT(*) FROM $counter_table WHERE `ip` = '$ip' AND `permalinks` = '$permalinks'" );
   
    return $count;
}

/*
*   Convert date("Y-m-d") Format to January 1 2020 Format.
*   @version 1.0
*/
function shogo_convert_ymd( $date ) {

    //Seperate Date Element.
    $d = explode( '-', $date );

    //Get Year.
    $year = $d[0];

    //Get Month.
    $month = $d[1];

    //Get Day.
    $day = $d[2];

    //Change Month Format to Month Names.
    switch ( $month ) {
        case "01":
            $month = "January";
        break;

        case "02":
            $month = "Februrary";
        break;

        case "03":
            $month = "March";
        break;

        case "04":
            $month = "April";
        break;

        case "05":
            $month = "May";
        break;

        case "06":
            $month = "June";
        break;

        case "07":
            $month = "July";
        break;

        case "08":
            $month = "August";
        break;

        case "09":
            $month = "September";
        break;

        case "10":
            $month = "October";
        break;

        case "11":
            $month = "November";
        break;

        case "12":
            $month = "December";
        break;
    }


    //Remove 0 in the first digit of the day.
    switch ( $day ) {
        case "01":
            $day = "1";
        break;

        case "02":
            $day = "2";
        break;

        case "03":
            $day = "3";
        break;

        case "04":
            $day = "4";
        break;

        case "05":
            $day = "5";
        break;

        case "06":
            $day = "6";
        break;

        case "07":
            $day = "7";
        break;

        case "08":
            $day = "8";
        break;

        case "09":
            $day = "9";
        break;
    }

    return $month.' '.$day.' '.$year;
}