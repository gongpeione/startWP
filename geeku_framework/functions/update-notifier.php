<?php

// Constants for the theme name, folder and remote XML url
define( 'NOTIFIER_THEME_NAME', 'Geekust' ); // The theme name
define( 'NOTIFIER_THEME_FOLDER_NAME', 'Geekust' ); // The theme folder name
define( 'NOTIFIER_XML_FILE', 'http://geeku.net/themes/update/geekust.xml' ); // The remote notifier XML file containing the latest version of the theme and changelog
define( 'NOTIFIER_CACHE_INTERVAL', 21600 ); // The time interval for the remote XML cache in the database (21600 seconds = 6 hours)



// Adds an update notification to the WordPress Dashboard menu
function update_notifier_menu() {  
	if (function_exists('simplexml_load_string')) { // Stop if simplexml_load_string funtion isn't available
	    $xml = get_latest_theme_version(NOTIFIER_CACHE_INTERVAL); // Get the latest remote XML file on our server
		$theme_data = get_theme_data(TEMPLATEPATH . '/style.css'); // Read theme current version from the style.css
		
		$latest_version = $xml->latest;
		$theme_version = $theme_data['Version'];
		$latest_version = explode(".",$latest_version);
		$theme_version = explode(".",$theme_version);
		if(empty($latest_version[2])){
		$latest_version[2] = 0;
		}		
		if(empty($theme_version[2])){
		$theme_version[2] = 0;
		}
		
		if( (float)$xml->latest > (float)$theme_data['Version']) { // Compare current theme version with the remote XML version
			add_theme_page( NOTIFIER_THEME_NAME . ' Theme Updates', NOTIFIER_THEME_NAME . ' <span class="update-plugins count-1"><span class="update-count">更新了！</span></span>', 'administrator', 'theme-update-notifier', 'update_notifier');
		}elseif($latest_version[2]>$theme_version[2]){
			add_theme_page( NOTIFIER_THEME_NAME . ' Theme Updates', NOTIFIER_THEME_NAME . ' <span class="update-plugins count-1"><span class="update-count">更新了！</span></span>', 'administrator', 'theme-update-notifier', 'update_notifier');		
		}
	}	
}
add_action('admin_menu', 'update_notifier_menu');  



// Adds an update notification to the WordPress 3.1+ Admin Bar
function update_notifier_bar_menu() {
	if (function_exists('simplexml_load_string')) { // Stop if simplexml_load_string funtion isn't available
		global $wp_admin_bar, $wpdb;
	
		if ( !is_super_admin() || !is_admin_bar_showing() ) // Don't display notification in admin bar if it's disabled or the current user isn't an administrator
		return;
		
		$xml = get_latest_theme_version(NOTIFIER_CACHE_INTERVAL); // Get the latest remote XML file on our server
		$theme_data = get_theme_data(TEMPLATEPATH . '/style.css'); // Read theme current version from the style.css
		
		$latest_version = $xml->latest;
		$theme_version = $theme_data['Version'];
		$latest_version = explode(".",$latest_version);
		$theme_version = explode(".",$theme_version);
		if(empty($latest_version[2])){
		$latest_version[2] = 0;
		}		
		if(empty($theme_version[2])){
		$theme_version[2] = 0;
		}		
		
	
		if( (float)$xml->latest > (float)$theme_data['Version']) { // Compare current theme version with the remote XML version
			$wp_admin_bar->add_menu( array( 'id' => 'update_notifier', 'title' => '<span>' . NOTIFIER_THEME_NAME . ' <span id="ab-updates">更新了！</span></span>', 'href' => get_admin_url() . 'themes.php?page=theme-update-notifier' ) );
		}elseif($latest_version[2]>$theme_version[2]){
			$wp_admin_bar->add_menu( array( 'id' => 'update_notifier', 'title' => '<span>' . NOTIFIER_THEME_NAME . ' <span id="ab-updates">更新了！</span></span>', 'href' => get_admin_url() . 'themes.php?page=theme-update-notifier' ) );
		}	}
}
add_action( 'admin_bar_menu', 'update_notifier_bar_menu', 1000 );



// The notifier page
function update_notifier() { 
	$xml = get_latest_theme_version(NOTIFIER_CACHE_INTERVAL); // Get the latest remote XML file on our server
	$theme_data = get_theme_data(TEMPLATEPATH . '/style.css'); // Read theme current version from the style.css ?>
	
	<style>
		.update-nag { display: none; }
		#instructions {max-width: 670px;}
		h3.title {margin: 30px 0 0 0; padding: 30px 0 0 0; border-top: 1px solid #ddd;}
	</style>

	<div class="wrap">
	
		<div id="icon-tools" class="icon32"></div>
		<h2><?php echo NOTIFIER_THEME_NAME ?> 主题更新</h2>
	    <div id="message" class="updated below-h2"><p><strong> <?php echo NOTIFIER_THEME_NAME; ?> 主题有更新了 </strong> 你现在的版本是 <?php echo $theme_data['Version']; ?> . 请更新到最新版本 <?php echo $xml->latest; ?>.</p></div>

		<img style="float: left; margin: 0 20px 20px 0; border: 1px solid #ddd;" src="<?php echo get_template_directory_uri(). '/screenshot.jpg'; ?>" />
		
		<div id="instructions">
		    <h3>更新提示</h3>
		    <p>想要更新 <?php echo NOTIFIER_THEME_NAME; ?>, 只需要从以下地址下载最新主题然后安装即可.</p>
            <p>主题详情及下载：<a href="http://www.geeku.net/1634.html" target="_blank">点击此处</a></p>
		</div>
	    
	</div>
    
<?php } 



// Get the remote XML file contents and return its data (Version and Changelog)
// Uses the cached version if available and inside the time interval defined
function get_latest_theme_version($interval) {
	$notifier_file_url = NOTIFIER_XML_FILE;	
	$db_cache_field = 'notifier-cache';
	$db_cache_field_last_updated = 'notifier-cache-last-updated';
	$last = get_option( $db_cache_field_last_updated );
	$now = time();
	// check the cache
	if ( !$last || (( $now - $last ) > $interval) ) {
		// cache doesn't exist, or is old, so refresh it
		if( function_exists('curl_init') ) { // if cURL is available, use it...
			$ch = curl_init($notifier_file_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			$cache = curl_exec($ch);
			curl_close($ch);
		} else {
			$cache = file_get_contents($notifier_file_url); // ...if not, use the common file_get_contents()
		}
		
		if ($cache) {			
			// we got good results	
			update_option( $db_cache_field, $cache );
			update_option( $db_cache_field_last_updated, time() );
		} 
		// read from the cache file
		$notifier_data = get_option( $db_cache_field );
	}
	else {
		// cache file is fresh enough, so read from it
		$notifier_data = get_option( $db_cache_field );
	}
	
	// Let's see if the $xml data was returned as we expected it to.
	// If it didn't, use the default 1.0 as the latest version so that we don't have problems when the remote server hosting the XML file is down
	if( strpos((string)$notifier_data, '<notifier>') === false ) {
		$notifier_data = '<?xml version="1.0" encoding="UTF-8"?><notifier><latest>1.0</latest><changelog></changelog></notifier>';
	}
	
	// Load the remote XML data into a variable and return it
	$xml = simplexml_load_string($notifier_data); 
	return $xml;
}

?>