<?php

namespace SharedVision;

class Settings {

  const OPTION_NAME = 'shared_vision_settings';

  /**
   * @var null|Settings;
   */
  protected static ?Settings $_instance = null;

  /**
   * @return Settings
   */
  public static function instance(): Settings {
    if( self::$_instance === null )
      self::$_instance = new self();

    return self::$_instance;
  }

  protected array $_settings = [];
  public array $_defaults = [
    'mode'          => SHARED_VISION_API_LIVE_ALIAS,
    'bearer_token'  => '',
  ];

  public function __construct() {
    $this->_settings = get_option( self::OPTION_NAME, [] );
  }

  public function get( $key ) {
    return ($this->_settings[$key] ?? ($this->_defaults[$key] ?? null));
  }

}