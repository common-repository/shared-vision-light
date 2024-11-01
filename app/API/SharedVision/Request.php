<?php

namespace SharedVision\API\SharedVision;

use SharedVision\Settings as SharedVision_Settings;

class Request {

  public static function get_endpoint() :string {
    if( SharedVision_Settings::instance()->get( 'mode' ) === SHARED_VISION_API_DEV_ALIAS )
      return SHARED_VISION_API_DEV_URL;

    return SHARED_VISION_API_LIVE_URL;
  }

  public static function get_default_headers() :array {
    if( empty( SharedVision_Settings::instance()->get( 'bearer_token' ) ) )
      throw new \Exception( __( "Missing bearer token", "sharedvision" ) );

    return [
      'Authorization' => 'Bearer ' . SharedVision_Settings::instance()->get( 'bearer_token' ),
      'Accept'        => 'application/json'
    ];
  }

  /**
   * @throws \Exception
   */
  public static function get_json( string $path, array $request_data = [] ) {
    $request_args = [
      'body'    => $request_data,
      'method'  => 'GET',
      'headers' => self::get_default_headers()
    ];

    $request_args = apply_filters( 'sharedvision_api_request_args', $request_args, $path );

    $response = wp_remote_request( self::get_endpoint() . $path, $request_args );

    if( is_wp_error( $response ) )
      throw new \Exception( $response->get_error_message() );

    $response = wp_remote_retrieve_body( $response );

    $response = json_decode( $response, true );

    if( empty( $response ) )
      throw new \Exception( __( "Invalid response from API", "sharedvision" ) );

    return $response;
  }

}