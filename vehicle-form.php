<?php 
/**
 
 * @wordpress-plugin
 * Plugin Name:       Vehicle form
 * Description:       Support for gravity form allowing users to rent vehicle.
 * Version:           1.0.0
 * Author:            Peter Steele
 * Text Domain:       vehicle-form
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ){
  exit( 'no direct access' );
}

if ( ! class_exists( 'GF_Field' ) ){
  exit( 'This page requires Gravity forms. Please try again later' );
}

define( 'VEHICLE_FORM_PLATES', plugins_url( 'assets/img/', __FILE__ ) );
define( 'VEHICLE_FORM_ASSETS', plugin_dir_path( __FILE__ ) . 'assets/' );
define( 'VEHICLE_FORM_COUNTRIES', [
  'austria' => [
    'cssSuffix' => 'at',
    'url' => VEHICLE_FORM_PLATES . 'generic-at.svg',
    'placeholders' => array( 'W', '12345X', '', ),
    'maxlengths' => [ '2', '6', '0']
  ],
  'bulgaria' => [
    'cssSuffix' => 'bg',
    'url' => VEHICLE_FORM_PLATES . 'generic-bg.svg',
    'placeholders' => array( 'AB12CDYZ', '', '' ),
    'maxlengths' => [ '8', '0', '0' ],
  ],
  'croatia' => [
    'cssSuffix' => 'cr',
    'url' => VEHICLE_FORM_PLATES . 'generic-cr.svg',
    'placeholders' => [ 'AB1234YZ', '', '' ],
    'maxlengths' => [ '9', '0', '0' ],
  ],
  'cyprus' => [
    'cssSuffix' => 'cy',
    'url' => VEHICLE_FORM_PLATES . 'generic-cy.svg',
    'placeholders' => ['ABC', '123', '' ],
    'maxlengths' => ['3', '3', '0' ],
  ],
  'czechrepublic' => [
    'cssSuffix' => 'cz',
    'url' => VEHICLE_FORM_PLATES . 'bg-cz.svg',
    'placeholders' => [ '1A2', '3456', '0' ],
    'maxlengths' => [ '3', '4', '0' ]
  ],
  'denmark' => [
    'cssSuffix' => 'dk',
    'url' => VEHICLE_FORM_PLATES . 'generic-dk.svg',
    'placeholders' => [ 'AB', '12', '123' ],
    'maxlengths' => ['2', '2', '3' ],
  ],
  'estonia' => [
    'cssSuffix' => 'ee',
    'url' => VEHICLE_FORM_PLATES . 'generic-ee.svg',
    'placeholders' => ['123ABC', '', ''],
    'maxlengths' => [ '10', '0', '0' ],
  ],
  'finland' => [
    'cssSuffix' => 'fi',
    'url' => VEHICLE_FORM_PLATES . 'generic-fi.svg',
    'placeholders' => [ 'ABC', '123', '' ],
    'maxlengths' => [ '3', '3', '0' ],
  ],
  'france' => [
    'cssSuffix' => 'fr',
    'url' => VEHICLE_FORM_PLATES . 'generic-fr.svg',
    'placeholders' => [ 'AB', '123', 'YZ' ],
    'maxlengths' => [ '3', '3', '2' ]
  ],
  'greece' => [
    'cssSuffix' => 'gr',
    'url' => VEHICLE_FORM_PLATES . 'generic-gr.svg',
    'placeholders' => ['ABC', '1234', '' ],
    'maxlengths' => [ '3', '4', '0' ],
  ],
  'hungary' => [
    'cssSuffix' => 'hu',
    'url' => VEHICLE_FORM_PLATES . 'bg-hu.svg',
    'placeholders' => [ 'ABC', '123', '' ],
    'maxlengths' => [ '4', '4', '0' ],
  ],
  'germany' => [
    'cssSuffix' => 'ge',
    'url' => VEHICLE_FORM_PLATES . 'bg-de.svg',
    'placeholders' => array( 'XYZ', 'AB', '1234', ),
    'maxlengths' => [ '2', '2', '4' ],
  ],
  'romania' => [
    'cssSuffix' => 'ro',
    'url' => VEHICLE_FORM_PLATES . 'bg-ro.svg',
    'placeholders' => array( 'CC', '12', 'ABC', ),
    'maxlengths' => [ '2', '2', '3' ],
  ],

]);

define( 'VEHICLE_FORM_PLANS', [
  [
    'name' => __( '10 Day Vignette Austria', 'vehicle-form' ),
    'price' => 12.50,
    'info' => __( 'Valid immediately or later if desired.', 'vehicle-form' ),
    'duration' => 'ten-day'
  ],
  [
    'name' => __( '2 Months Vignette Austria', 'vehicle-form'),
    'price' => 23.60,
    'info' => __( 'Valid immediately or later if desired.', 'vehicle-form' ),
    'duration' => 'two-month'
  ],
  [
    'name' => __( 'Annual Vignette Austria 2024', 'vehicle-form' ),
    'price' => 49.90,
    'info' => __( 'Valid from December 1st, 2023 - January 31st, 2025' ),
    'duration' => 'annual'
  ],
  [
    'name' => __( 'Route Toll', 'vehicle-form' ),
    'price' => 11.50,
    'info' => 'Valid for 1 year from the date of purchase/start of validity',
    'duration' => 'single'
  ]
]);

require_once plugin_dir_path( __FILE__ ) . 'inputs/vehicle.php';
require_once plugin_dir_path( __FILE__ ) . 'inputs/plans.php';
require_once plugin_dir_path( __FILE__ ) . 'inputs/license-plate.php';
require_once plugin_dir_path( __FILE__ ) . 'inputs/cart-terms.php';

function vehicle_form_enqueue( $form, $is_ajax ){

  if ( 3 == $form['id'] || 'sigtest' == $form['title'] ){//change
    wp_enqueue_style( 'vehicle-form-general' , plugins_url( 'assets/css/general.css', __FILE__ ));
    wp_enqueue_style( 'montserrat', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap' );
  }
  
  $vehicle_fields = GFAPI::get_fields_by_type( $form, 'vehicle' );
  if ( count( $vehicle_fields )){
    wp_enqueue_style( 'vehicle-form-vehicle', plugins_url( '/assets/css/vehicle.css', __FILE__ ), array( 'vehicle-form-general' ));
    wp_enqueue_script( 'vehicle-form-vehicle', plugins_url( '/assets/js/vehicle.js', __FILE__ ), array( 'jquery' ));
  }

  $plans_fields = GFAPI::get_fields_by_type( $form, 'pricing-plan' );
  if ( count( $plans_fields ) ){
    wp_enqueue_style( 'vehicle-form-plan', plugins_url( '/assets/css/plans.css', __FILE__ ), array( 'vehicle-form-general' ));
    wp_enqueue_script( 'vehicle-form-plan', plugins_url( 'assets/js/plans.js', __FILE__ ));
    wp_localize_script( 'vehicle-forms-cart-terms', 'vfplans', VEHICLE_FORM_PLANS );
  }

  $license_plate_fields = GFAPI::get_fields_by_type( $form, 'license_plate' );
  if ( count( $license_plate_fields )){
    wp_enqueue_style( 'vehicle-form-license', plugins_url( 'assets/css/license-plate.css', __FILE__ ), array( 'vehicle-form-general' ));
    wp_enqueue_script( 'vehicle-form-license', plugins_url( 'assets/js/license-plate.js', __FILE__ ));
    wp_enqueue_style( 'gforms_datepicker_css', plugins_url( '/gravityforms/legacy/css/datepicker.min.css'));
    wp_enqueue_script( 'vehicle-form-date' , plugins_url( 'assets/js/date.js', __FILE__ ), array( 'jquery', 'jquery-ui-datepicker' ));

    wp_localize_script( 'vehicle-form-license', 'countries', VEHICLE_FORM_COUNTRIES );
  }

  $cart_terms_fields = GFAPI::get_fields_by_type( $form, 'cart_and_terms' );
  if ( count( $cart_terms_fields )){
    wp_enqueue_style( 'vehicle-forms-cart-terms', plugins_url( 'assets/css/cart-terms.css', __FILE__), array( 'vehicle-form-general' ));
    wp_enqueue_script( 'vehicle-forms-cart-terms', plugins_url( 'assets/js/cart.js', __FILE__ ));
    wp_localize_script( 'vehicle-forms-cart-terms', 'vfplans', VEHICLE_FORM_PLANS );
    wp_localize_script( 'vehicle-forms-cart-terms', 'img', array( 'src' => VEHICLE_FORM_PLATES . 'close.svg' ));
  }
}

add_action( 'gform_enqueue_scripts', 'vehicle_form_enqueue', 10, 2 );