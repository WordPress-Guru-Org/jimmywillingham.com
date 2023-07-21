<?php
add_filter('plugins_api', 'mlsimport_plugin_info', 20, 3);
/*
 * $res contains information for plugins with custom update server 
 * $action 'plugin_information'
 * $args stdClass Object ( [slug] => woocommerce [is_ssl] => [fields] => Array ( [banners] => 1 [reviews] => 1 [downloaded] => [active_installs] => 1 ) [per_page] => 24 [locale] => en_US )
 */	
function mlsimport_plugin_info( $res, $action, $args ){
 
	// do nothing if this is not about getting plugin information
	if( $action !== 'plugin_information' )
		return false;
 
	// do nothing if it is not our plugin	
	if( 'mlsimport' !== $args->slug )
		return $res;
 
	// trying to get from cache first, to disable cache comment 18,28,29,30,32
	if( false == $remote = get_transient( 'mlsimport_upgrade_mlsimport' ) ) {
 
		// info.json is the file with the actual plugin information on your server
		$remote = wp_remote_get( 'http://update.mlsimport.com/info.json', array(
			'timeout' => 10,
			'headers' => array(
				'Accept' => 'application/json'
			) )
		);
 
		if ( !is_wp_error( $remote ) && isset( $remote['response']['code'] ) && $remote['response']['code'] == 200 && !empty( $remote['body'] ) ) {
			set_transient( 'mlsimport_upgrade_mlsimport', $remote, 43200 ); // 12 hours cache
		}
 
	}
 
    
	if( $remote ) {
 
		$remote = json_decode( $remote['body'] );
		$res = new stdClass();
		$res->name = $remote->name;
		$res->slug = 'mlsimport';
		$res->version = $remote->version;
		$res->tested = $remote->tested;
		$res->requires = $remote->requires;
		$res->author = '<a href="https://mlsimport.com">MlsImport</a>'; // I decided to write it directly in the plugin
		$res->author_profile = ''; // WordPress.org profile
		$res->download_link = $remote->download_url;
		$res->trunk = $remote->download_url;
		$res->last_updated = $remote->last_updated;
		$res->sections = array(
			'description' => $remote->sections->description, // description tab
			'installation' => $remote->sections->installation, // installation tab
			
		);
 
		// in case you want the screenshots tab, use the following HTML format for its content:
		// <ol><li><a href="IMG_URL" target="_blank" rel="noopener noreferrer"><img src="IMG_URL" alt="CAPTION" /></a><p>CAPTION</p></li></ol>
		if( !empty( $remote->sections->screenshots ) ) {
			$res->sections['screenshots'] = $remote->sections->screenshots;
		}
 
		$res->banners = array(
			'low'   => '',
            		'high'  => ''
		);
                
                print_r($res);
           	return $res;
 
	}
 
	return false;
 
}

add_filter('site_transient_update_plugins', 'mlsimport_push_update' );
 
function mlsimport_push_update( $transient ){
 
	if ( empty($transient->checked ) ) {
            return $transient;
        }
 
	// trying to get from cache first, to disable cache comment 10,20,21,22,24
	if( false == $remote = get_transient( 'mlsimport_upgrade_mlsimport' ) ) {
 
		// info.json is the file with the actual plugin information on your server
		$remote = wp_remote_get( 'http://update.mlsimport.com/info.json', array(
			'timeout' => 10,
			'headers' => array(
				'Accept' => 'application/json'
			) )
		);
 
		if ( !is_wp_error( $remote ) && isset( $remote['response']['code'] ) && $remote['response']['code'] == 200 && !empty( $remote['body'] ) ) {
			set_transient( 'mlsimport_upgrade_mlsimport', $remote, 43200 ); // 12 hours cache
		}
 
	}
 
	if( $remote ) {
 
		$remote = json_decode( $remote['body'] );
 
		// your installed plugin version should be on the line below! You can obtain it dynamically of course 
		if( $remote && version_compare( '1.0', $remote->version, '<' ) && version_compare($remote->requires, get_bloginfo('version'), '<' ) ) {
			$res = new stdClass();
			$res->slug = 'mlsimport';
			$res->plugin = 'mlsimport/mlsimport.php'; // it could be just mlsimport.php if your plugin doesn't have its own directory
			$res->new_version = $remote->version;
			$res->tested = $remote->tested;
			$res->package = $remote->download_url;
           		$transient->response[$res->plugin] = $res;
           		$transient->checked[$res->plugin] = $remote->version;
           	}
 
	}
        return $transient;
}


add_action( 'upgrader_process_complete', 'mlsimport_after_update', 10, 2 );
 
function mlsimport_after_update( $upgrader_object, $options ) {
	if ( $options['action'] == 'update' && $options['type'] === 'plugin' )  {
		// just clean the cache when new plugin version is installed
		delete_transient( 'mlsimport_upgrade_mlsimport' );
	}
}




/*
// TEMP: Enable update check on every request. Normally you don't need this! This is for testing only!
// NOTE: The 
//	if (empty($checked_data->checked))
//		return $checked_data; 
// lines will need to be commented in the check_for_plugin_update function as well.
 * 
 */
//set_site_transient('update_plugins', null);
//
//// TEMP: Show which variables are being requested when query plugin API
//add_filter('plugins_api_result', 'mlsimport_plugin_update_result', 10, 3);
//function mlsimport_plugin_update_result($res, $action, $args) {
//	print_r($res);
//	return $res;
//}
//// NOTE: All variables and functions will need to be prefixed properly to allow multiple plugins to be updated
//
//
//$api_url = 'http://update.mlsimport.com/';
//$plugin_slug = 'mlsimport';
//
//
//add_filter('pre_set_site_transient_update_plugins', 'mlsimport_check_for_plugin_update');
//
//function mlsimport_check_for_plugin_update($checked_data) {
//	global $api_url, $plugin_slug, $wp_version;
//	
//	//Comment out these two lines during testing.
//	if (empty($checked_data->checked))
//		return $checked_data;
//	
//	$args = array(
//		'slug' => $plugin_slug,
//		'version' => $checked_data->checked[$plugin_slug .'/'. $plugin_slug .'.php'],
//	);
//	$request_string = array(
//			'body' => array(
//				'action' => 'basic_check', 
//				'request' => serialize($args),
//				'api-key' => md5(get_bloginfo('url'))
//			),
//			'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
//		);
//	
//	// Start checking for an update
//	$raw_response = wp_remote_post($api_url, $request_string);
//	
//	if (!is_wp_error($raw_response) && ($raw_response['response']['code'] == 200))
//		$response = unserialize($raw_response['body']);
//	
//	if (is_object($response) && !empty($response)) // Feed the update data into WP updater
//		$checked_data->response[$plugin_slug .'/'. $plugin_slug .'.php'] = $response;
//	
//	return $checked_data;
//}
//
//
//add_filter('plugins_api', 'mlsimport_plugin_api_call', 10, 3);
//function mlsimport_plugin_api_call($def, $action, $args) {
//	global $plugin_slug, $api_url, $wp_version;
//	
//    
//	if (!isset($args->slug) || ($args->slug != $plugin_slug))
//		return false;
//	
//	// Get the current version
//        print_r($args);
//            
//	$plugin_info = get_site_transient('update_plugins');
//	
//        print_r($plugin_info);
//        
//        $current_version = $plugin_info->checked[$plugin_slug .'/'. $plugin_slug .'.php'];
//	$args->version = $current_version;
//	
//	$request_string = array(
//			'body' => array(
//				'action' => $action, 
//				'request' => serialize($args),
//				'api-key' => md5(get_bloginfo('url'))
//			),
//			'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
//		);
//	
//	$request = wp_remote_post($api_url, $request_string);
//	
//	if (is_wp_error($request)) {
//		$res = new WP_Error('plugins_api_failed', __('An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>'), $request->get_error_message());
//	} else {
//		$res = unserialize($request['body']);
//		
//		if ($res === false)
//			$res = new WP_Error('plugins_api_failed', __('An unknown error occurred'), $request['body']);
//	}
//	
//	return $res;
//}
