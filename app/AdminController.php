<?php

namespace SharedVision;

use SharedVision\AdminCoordinator\Settings as Coordinator_Settings;

class AdminController {

  /**
   * @var null|AdminController;
   */
  protected static ?AdminController $_instance = null;

  /**
   * @return AdminController
   */
  public static function instance(): AdminController {
    if( self::$_instance === null )
      self::$_instance = new self();

    return self::$_instance;
  }

  /**
   * @var Coordinator_Settings|null
   */
  public ?Coordinator_Settings $settings;

  public function __construct() {
    $this->settings = new Coordinator_Settings();
  }

  public function setup() {
    $this->settings->setup();
  }



}