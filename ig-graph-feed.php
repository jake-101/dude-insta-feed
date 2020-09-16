<?php

/*
Plugin Name: IG Graph Feed
Description: Add Instagram User feed
Version: 0.1
Author: Jake Peterson
Author URI: https://jake101.com
Text Domain: ig-graph-feed
Domain Path:       /languages
 */
require plugin_dir_path( __FILE__ ).'/vendor/autoload.php';

use EspressoDev\InstagramBasicDisplay\InstagramBasicDisplay;


if( !defined( 'ABSPATH' )  )
	exit();

Class Ig_Graph_Feed {
  
  private static $_instance = null;

  /**
   * Construct everything and begin the magic!
   *
   * @since   0.1.0
   * @version 0.1.0
   */
  public function __construct() {
    // Add actions to make magic happen
    add_action( 'init', array( $this, 'load_depencies' ) );
    add_action( 'init', array( $this, 'load_plugin_textdomain' ) );


  } // end function __construct

  /**
   *  Prevent cloning
   *
   *  @since   0.1.0
   *  @version 0.1.0
   */
  public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'ig-graph-feed' ) );
	} // end function __clone

  /**
   *  Prevent unserializing instances of this class
   *
   *  @since   0.1.0
   *  @version 0.1.0
   */
  public function __wakeup() {
    _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'ig-graph-feed' ) );
  } // end function __wakeup

  /**
   *  Ensure that only one instance of this class is loaded and can be loaded
   *
   *  @since   0.1.0
   *  @version 0.1.0
	 *  @return  Main instance
   */
  public static function instance() {
    if( is_null( self::$_instance ) ) {
      self::$_instance = new self();
    }

    return self::$_instance;
  } // end function instance

  /**
   *  Load plugin localisation
   *
   *  @since   0.1.0
   *  @version 0.1.0
   */
  public function load_plugin_textdomain() {
    load_plugin_textdomain( 'ig-graph-feed', false, dirname( plugin_basename( __FILE__ ) ).'/languages/' );
  } // end function load_plugin_textdomain
  public function load_depencies() {

    require_once( plugin_dir_path( __FILE__ ).'/vendor/autoload.php' );

  } // end function load_depencies
	public function get_user_images() {

    $token = "**TOKEN**";
		$transient_name = apply_filters( 'ig-graph-feed/user_images_transient', 'ig-token'.$token, $token );
		$images = get_transient( $transient_name );
	  if( !empty( $images ) || false != $images )
	    return $images;



		$response = self::_call_api($token);
		if( $response === FALSE || is_wp_error( $response ) ) {
			set_transient( $transient_name, $response, apply_filters( 'ig-graph-feed/user_images_lifetime', '600' ) / 2 );
			return;
    }
    $instagram = new InstagramBasicDisplay($token);

    $newtoken = $instagram->refreshToken($token, true);
    $transient_name = apply_filters( 'ig-graph-feed/user_images_transient', 'ig-token'.$newtoken, $newtoken );

		$response = apply_filters( 'ig-graph-feed/user_images', $response  );
		set_transient( $transient_name, $response, apply_filters( 'ig-graph-feed/user_images_lifetime', '600' ) );

		return $response;
	} // end function get_user_images

	private function _call_api($token) {
    




	 $instagram = new InstagramBasicDisplay($token);
    $response = $instagram->getUserMedia();
    

	

		if( is_wp_error( $response ) ) {
			self::_write_log( 'response status code not 200 OK, user: '.$token );
			return false;
    }
   $newresponse = $response->data; 
    foreach ($newresponse as $item) {
      if ($item->media_type == "VIDEO") {
        $item->media_url = $item->thumbnail_url;
      }

    }

		return $newresponse;
	} // end function _call_api

	private function _write_log ( $log )  {
    if( true === WP_DEBUG ) {
      if( is_array( $log ) || is_object( $log ) ) {
        error_log( print_r( $log, true ) );
      } else {
        error_log( $log );
      }
    }
  } // end _write_log
} // end class Ig_Graph_Feed

function ig_graph_feed() {
  return new Ig_Graph_Feed();
} // end function ig_graph_feed