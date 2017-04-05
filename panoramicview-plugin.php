<?php
/**
* Plugin Name: PanoramicView Plugin
* Plugin URI: http://www.panoramicview.ro
* Description: Un plugin care afiseaza un buton <strong>Back to Top</strong> pentru revenirea in partea de sus a paginii, posibilitatea de a avea <strong>Custom CSS</strong> si <strong>Cookie Consent.</strong>
* Author:  Dan Simoaica
* Author URI: http://www.simoaica.ro
* Version: 3.2
* License: GPLv2
*/

// Exit if acces directly
if ( ! defined( 'ABSPATH' )){
  echo "Nu ai ce cauta aici!!!!";
  exit;
}

/*
=================
  ADMIN PAGE
=================
*/


if ( is_admin() ) {
  add_action( 'admin_enqueue_scripts', 'panobtt_enqueue_color_picker' );
  function panobtt_enqueue_color_picker() {
      wp_enqueue_style( 'wp-color-picker' );
      wp_enqueue_script( 'panobtt-color', plugins_url('panobtt_color.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
  }

  // Delete Option
  function panobtt_uninstall() {
    delete_option( 'buton_activ' );
    delete_option( 'culoare_buton' );
    delete_option( 'css_activ' );
    delete_option( 'custom_css' );
    delete_option( 'cookie_activ' );
    delete_option( 'cookie_text' );
    delete_option( 'cookie_id' );
  }
  register_uninstall_hook( __FILE__, 'panobtt_uninstall' );
}

function panobtt_add_admin_page() {
  // generate Admin Page

add_menu_page( 'PanoramicView BacktoTop Options', 'PanoramicView', 'manage_options', 'panobtt_options', 'panobtt_admin_create_page', plugins_url('img/panoramicview-icon.png', __FILE__ ), 110 );
add_action( 'admin_init', 'panobtt_custom_settings' );
}
add_action( 'admin_menu', 'panobtt_add_admin_page' );

function panobtt_custom_settings() {
  register_setting( 'panobtt-settings-group', 'buton_activ' );
  register_setting( 'panobtt-settings-group', 'culoare_buton' );
  register_setting( 'panobtt-settings-group', 'css_activ' );
  register_setting( 'panobtt-settings-group', 'cookie_activ' );
  $option = get_option( 'cookie_activ' );
  if ( $option == 'on' ) {
    register_setting( 'panobtt-settings-group', 'cookie_text', 'panobtt_sanitize_cookie_text' );
    register_setting( 'panobtt-settings-group', 'cookie_id', 'panobtt_sanitize_cookie_id' );
  }
  $option = get_option( 'css_activ' );
  if ( $option == 'on' ) {
    register_setting( 'panobtt-settings-group', 'custom_css', 'panobtt_sanitize_custom_css' );
  }
  add_settings_section( 'panobtt-buton-section', 'Butonul Back to Top', 'panobtt_setari_callback', 'panobtt_options' );
  add_settings_section( 'panobtt-customcss-section', 'Custom CSS', 'panobtt_custom_css_callback', 'panobtt_options' );
  add_settings_section( 'panobtt-cookie-section', 'Cookie Consent', 'panobtt_cookie_callback', 'panobtt_options' );
  add_settings_field( 'panobtt-buton-checkbox', 'Vizibil?', 'panobtt_buton_checkbox_callback', 'panobtt_options', 'panobtt-buton-section' );
  add_settings_field( 'panobtt-buton-culoare', 'Alegeti culoarea', 'panobtt_buton_culoare_callback', 'panobtt_options', 'panobtt-buton-section' );
  add_settings_field( 'panobtt-custom-css-checkbox', 'Activati Custom CSS?', 'panobtt_custom_css_checkbox_callback', 'panobtt_options', 'panobtt-customcss-section' );
  $option = get_option( 'css_activ' );
  if ( $option == 'on' ) {
    add_settings_field( 'panobtt-custom-css-field', 'Custom CSS:', 'panobtt_custom_css_field_callback', 'panobtt_options', 'panobtt-customcss-section' );
  }
  add_settings_field( 'panobtt-cookie-checkbox', 'Activati Cookie Consent?', 'panobtt_cookie_checkbox_callback', 'panobtt_options', 'panobtt-cookie-section' );
  $option = get_option( 'cookie_activ' );
  if ( $option == 'on' ) {
    add_settings_field( 'panobtt-cookie-field', 'Cookie Consent Text:', 'panobtt_cookie_field_callback', 'panobtt_options', 'panobtt-cookie-section' );
    add_settings_field( 'panobtt-cookie-field1', 'Pagina cu detalii despre cookie:', 'panobtt_cookie_field1_callback', 'panobtt_options', 'panobtt-cookie-section' );
  }
}

function panobtt_sanitize_cookie_id( $input ) {
  $output = esc_attr( $input );
  return $output;
}

function panobtt_sanitize_cookie_text( $input ) {
  $output = esc_textarea( $input );
  return $output;
}

function panobtt_sanitize_custom_css( $input ) {
  $output = esc_textarea( $input );
  return $output;
}

function panobtt_custom_css_enqueue_scripts() {
  wp_enqueue_style( 'ace', plugins_url('panobtt_ace.css', __FILE__ ), array(), '1.0.0', 'all' );
  wp_enqueue_script( 'ace', plugins_url('/ace/ace.js', __FILE__ ), array( 'jquery' ), '1.2.1', true );
  wp_enqueue_script( 'panobtt-custom-css-script', plugins_url('panobtt_custom_css.js', __FILE__ ), array( 'jquery' ), '1.0.0', true );
}

$option = get_option( 'css_activ' );
if ( $option == 'on' ) {
  add_action( 'admin_enqueue_scripts', 'panobtt_custom_css_enqueue_scripts' );
}


function panobtt_custom_css_field_callback() {
  $css_pornit = get_option( 'css_activ' );
  $css = get_option( 'custom_css' );
  $css = ( empty($css) ? '/* PanoramicView Custom CSS */' : $css );
  echo '<div id="customCss">'. $css .'</div><textarea id="custom_css" name="custom_css" style="display:none;visibility:hidden;">'. $css .'</textarea>';
  echo '<br><button id="incarca-customCSS" class="button button-secondary">Incarca Custom CSS de pe harddisk</button>';
}

function panobtt_cookie_field_callback() {
  $cookie_text = esc_attr( get_option( 'cookie_text' ) );
  $cookie_text = ( empty($cookie_text) ? 'Acest site foloseste cookies. Continuarea navigarii pe acest site se considera acceptare a politicii de utilizare a cookies.' : $cookie_text );
  echo '<textarea id="cookie_text" name="cookie_text" style="width: 500px;">'. $cookie_text .'</textarea>';
}

function panobtt_cookie_field1_callback() {
  $cookie_id = esc_attr( get_option( 'cookie_id' ) );
  $args = array(
    'depth'                 => 0,
    'child_of'              => 0,
    'selected'              => $cookie_id,
    'echo'                  => 1,
    'name'                  => 'cookie_id',
    'id'                    => null, // string
    'class'                 => null, // string
    'show_option_none'      => 'Alege pagina de link', // string
    'show_option_no_change' => null, // string
    'option_none_value'     => null, // string
  );
  wp_dropdown_pages( $args );
}

function panobtt_custom_css_checkbox_callback() {
  $option = get_option( 'css_activ' );
  $checked = (@$option =='on' ? 'checked' : '' );
  echo '<input type="checkbox" name="css_activ" '. $checked .' /><br/>';
}

function panobtt_buton_checkbox_callback() {
  $option = get_option( 'buton_activ' );
  $checked = (@$option =='on' ? 'checked' : '' );
  echo '<input type="checkbox"  name="buton_activ" '. $checked .' /><br/>';
}

function panobtt_cookie_checkbox_callback() {
  $option = get_option( 'cookie_activ' );
  $checked = (@$option =='on' ? 'checked' : '' );
  echo '<input type="checkbox"  name="cookie_activ" '. $checked .' /><br/>';
}

function panobtt_buton_culoare_callback() {
  add_option( 'culoare_buton', '#f34763' );
  $culoarebuton = esc_attr( get_option( 'culoare_buton' ));
  echo '<input id="link-color" type="text"  name="culoare_buton" value="'. $culoarebuton.'" />';
}

function panobtt_admin_create_page() {
  // generation of admin page
  require_once 'admin_page_template.php';
}

function panobtt_setari_callback() {

}

function panobtt_custom_css_callback() {

}

function panobtt_culoare() {

}

function panobtt_cookie_callback() {

}

/*
================================
  FRONTEND
  Incarca JS CSS si Butonul daca nu sunt in mod admin
================================
*/
add_option( 'buton_activ', 'on' );
add_option( 'culoare_buton', '#f34763' );
$option = get_option( 'css_activ' );
if ( ! is_admin() &&  $option == 'on') {
  // Add Frontend CSS Query
  function panobtt_ccsswp_register_style() {
    $url = site_url();

    wp_register_style( 'panobtt_ccss', add_query_arg( array( 'panobtt_ccss' => 1 ), $url ) );
    wp_enqueue_style( 'panobtt_ccss' );
  }
  add_action( 'wp_enqueue_scripts', 'panobtt_ccsswp_register_style', 999 );

  // Use custom css when query used.
  function panobtt_ccss_css() {

    // Only print CSS if this is a stylesheet request
    if( ! isset( $_GET['panobtt_ccss'] ) || intval( $_GET['panobtt_ccss'] ) !== 1 ) {
      return;
    }

    ob_start();
    header( 'Content-type: text/css' );
    $option     = get_option( 'custom_css' );
    $raw_content = isset( $option ) ? $option : '';
    $content     = wp_kses( $raw_content, array( '\'', '\"' ) );
    $content     = str_replace( '&gt;', '>', $content );
    echo $content;
    die();
  }

  add_action( 'plugins_loaded', 'panobtt_ccss_css' );
}

if ( get_option( 'buton_activ' ) == 'on') {
function panobtt_frontend() {
  if ( ! is_admin() ) {

      add_action( 'wp_enqueue_scripts', 'panobtt_script_enqueue' );
      add_action( 'wp_footer', 'panobtt_add_sageata');

  }
}

function panobtt_frontend_cookie() {
  if (( ! is_admin() ) && ( get_option( 'cookie_activ' ) == 'on')) {
      add_action( 'wp_footer', 'panobtt_add_cookie_div');
  }
}

add_action( 'init', 'panobtt_frontend' );
add_action( 'init', 'panobtt_frontend_cookie' );

function panobtt_script_enqueue() {
  wp_enqueue_script( 'panobtt-custom' , plugin_dir_url( __FILE__ ) . 'panobtt.js', array( 'jquery' ), '1.0.0', true );
  wp_enqueue_style( 'panobtt-custom', plugin_dir_url( __FILE__ ) . 'panobtt.css', array(), '1.0.0');
}

function panobtt_add_sageata(){
      $culoare = esc_attr( get_option( 'culoare_buton' ));
      ?>
      <div class="sageata">
        <svg  x="0px" y="0px" width="100%" height="100%" viewBox="0 0 512 512" xml:space="preserve" fill="<?php  echo $culoare; ?>" opacity=.7>
        <g>
          <path d="M512,256c0,141.385-114.615,256-256,256C114.615,512,0,397.385,0,256S114.615,0,256,0S512,114.615,512,256z M48,256
            c0,114.875,93.125,208,208,208s208-93.125,208-208S370.875,48,256,48S48,141.125,48,256z M278.627,105.372l128,128.001
            c12.496,12.496,12.496,32.757,0,45.254s-32.758,12.497-45.256,0L288,205.255V384c0,17.673-14.327,32-32,32s-32-14.327-32-32
            V205.255l-73.372,73.373c-12.497,12.497-32.759,12.497-45.256,0C99.124,272.379,96,264.189,96,256
            c0-8.189,3.124-16.379,9.372-22.627l128-128.001C245.869,92.876,266.131,92.876,278.627,105.372z"/>
        </g>
        </svg>
      </div>
      <?php
}

function panobtt_add_cookie_div(){
  $cuchi= $_COOKIE['cuchi'];
  if(!isset($cuchi)) {
    $cookie_id = esc_attr( get_option( 'cookie_id' ) );
    $cookie_url = esc_url( get_permalink( $cookie_id ) );
    $cookie_text = esc_attr( get_option( 'cookie_text' ));
    ?>
    <div id="cookie-div">
      <p><?php echo $cookie_text; ?><?php echo ( $cookie_id )? '<a href="'. $cookie_url .'">Detalii despre politica cookie aici</a>' : '' ?><span id="cookie-span">X</span></p>
    </div>
    <?php
  }
}
}
