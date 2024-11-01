<?php

namespace SharedVision\API\SharedVision;

use SharedVision\API\SharedVision\Request as SharedVision_API_Request;

class Plugins {

  /**
   * @throws \Exception
   */
  public static function health() :array {
    return SharedVision_API_Request::get_json( 'plugins/v1/health' );
  }

}