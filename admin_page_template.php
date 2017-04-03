<h1>PanoramicView  Back to Top, Custom CSS & Cookie Consent.</h1>
<?php settings_errors(); ?>
<br/>
<form id="save-panobtt-form" action="options.php" method="post">
  <?php settings_fields( 'panobtt-settings-group' ); ?>
  <?php do_settings_sections( 'panobtt_options' ) ?>
  <?php submit_button(); ?>
</form>
