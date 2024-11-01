<div class="wrap">
  <h1><?php echo esc_html( sprintf( __( "%s Settings", "sharedvision" ), SHARED_VISION_NAME ) ); ?></h1>
  <form method="post" action="options.php">
    <?php
      settings_fields(SHARED_VISION_SLUG_PREFIX . '-settings' );
      do_settings_sections(SHARED_VISION_SLUG_PREFIX . '-settings' );
      submit_button();
    ?>
  </form>
</div>