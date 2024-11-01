<?php

namespace SharedVision\AdminCoordinator;

use SharedVision\Template as SharedVision_Template;
use SharedVision\Settings as SharedVision_Settings;
use SharedVision\API\SharedVision\Plugins as SharedVision_API_Plugins;

class Settings {

  public function setup() {
    add_action( 'admin_init', [ $this, '_action_admin_init' ] );
    add_action( 'admin_menu', [ $this, '_action_admin_menu' ] );

    add_action( 'allowed_options', [ $this, '_allowed_options' ], 5 );
  }

  public function _action_admin_init() {
    register_setting('shared_vision_settings', SharedVision_Settings::OPTION_NAME, [
      'type'         => 'array',
      'description'  => __( "This contains all the information about the SharedVision integration from WordPress", 'sharedvision' ),
      'show_in_rest' => false,
      'default'      => [],
      'sanitize_callback' => [ $this, '_sanitize_shared_vision_settings_callback' ],
    ] );

    add_settings_section(
      SHARED_VISION_ALIAS . '_settings_general',
      __( 'General Settings' ),
      [ $this, '_settings_section_general_callback' ],
      SHARED_VISION_SLUG_PREFIX . '-settings'
    );

    add_settings_field(
      'sharedvision_mode',
      __( 'Mode', "sharedvision" ),
      [ $this, '_setting_field_mode_callback' ],
      SHARED_VISION_SLUG_PREFIX . '-settings',
      SHARED_VISION_ALIAS . '_settings_general'
    );

    add_settings_field(
      'sharedvision_bearer_token',
      __( 'API Key', "sharedvision" ),
      [ $this, '_setting_field_bearer_token_callback' ],
      SHARED_VISION_SLUG_PREFIX . '-settings',
      SHARED_VISION_ALIAS . '_settings_general'
    );
  }

  public function _action_admin_menu() {
    add_options_page(
      sprintf( __( "%s Settings", "sharedvision" ), SHARED_VISION_NAME ),
      SHARED_VISION_NAME,
      'manage_options',
      SHARED_VISION_SLUG_PREFIX . '-settings',
      [ $this, '_display_options_page' ]
    );
  }

  public function _allowed_options( $allowed_options ) {
    $allowed_options[ SHARED_VISION_SLUG_PREFIX . '-settings' ] = [
      'shared_vision_settings'
    ];

    return $allowed_options;
  }

  public function _display_options_page() {
    // Refresh Transients, even if a change happens, or doesn't.

    delete_transient( 'shared_vision_lists' );

    SharedVision_Template::load_template( 'admin/settings.php' );
  }

  public function _sanitize_shared_vision_settings_callback( $settings ) :array {
    $response = get_option( SharedVision_Settings::OPTION_NAME, [] );

    foreach( $settings as $key => $value ){
      if( $key === 'bearer_token' && str_ends_with( $value, '**' ) )
        continue;

      $response[$key] = sanitize_text_field(trim( $value ) );

      // If either value changes, this will become irrelevant.
      delete_transient( 'shared_vision_is_valid_license' );
    }

    return $response;
  }

  public function _settings_section_general_callback() {
    echo esc_html( __( "Add your SharedVision authentication credentials", "sharedvision" ) );
  }

  public function _setting_field_mode_callback() {
    echo '<select name="shared_vision_settings[mode]" class="regular-text">';

    foreach( [
               SHARED_VISION_API_LIVE_ALIAS => __( "Live", "sharedvision" ),
               SHARED_VISION_API_DEV_ALIAS  => __( "Sandbox", "sharedvision" )
             ] as $key => $value ) {
      echo '<option value="' . esc_attr( $key ) . '" ' . selected( SharedVision_Settings::instance()->get( 'mode' ), $key, false ). '>' . esc_html( $value ) . '</option>';
    }

    echo '</select>';
  }

  public function _setting_field_bearer_token_callback() {
    $value = SharedVision_Settings::instance()->get( 'bearer_token' );

    if( !empty( $value ) )
      $value = substr( $value, 0, 5 ) . shared_vision_utility_replace_string_contents_to( substr( $value, 4 ), '*', [ '.' ] );

    echo '<input name="shared_vision_settings[bearer_token]" class="regular-text" value="' . esc_attr( $value ) . '"/>';

    if( !empty( $value ) ) {
      try {
        $health_status = SharedVision_API_Plugins::health();

        if( isset( $health_status[ 'success' ] ) && !empty( $health_status[ 'success' ] ) ) {
          echo '<p style="color : #2ecc71">' . esc_html( sprintf( __( "Valid API Key : %s", 'sharedvision' ), $health_status[ 'name' ] ) ) . '</p>';
        } else {
          $message = __( "Invalid API Key", 'sharedvision' );

          if( isset( $health_status[ 'message' ] ) )
            $message = $health_status[ 'message' ];

          echo '<p style="color : #e74c3c">' . esc_html( $message ) . '</p>';
        }

      } catch ( \Exception $e ) {
        echo '<p style="color : #e74c3c">' . esc_html( $e->getMessage() ) . '</p>';
      }

      echo '<p>' . esc_html( __( "The API Key has been masked for security & privacy reasons.", "sharedvision" ) ) . '</p>';
    }
  }

}