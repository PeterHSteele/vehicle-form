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
  ]
]);

require_once plugin_dir_path( __FILE__ ) . 'inputs/vehicle.php';
require_once plugin_dir_path( __FILE__ ) . 'inputs/plans.php';
require_once plugin_dir_path( __FILE__ ) . 'inputs/license-plate.php';

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
  }

  $license_plate_fields = GFAPI::get_fields_by_type( $form, 'license_plate' );
  if ( count( $license_plate_fields )){
    wp_enqueue_style( 'vehicle-form-license', plugins_url( 'assets/css/license-plate.css', __FILE__ ), array( 'vehicle-form-general' ));
    wp_enqueue_script( 'vehicle-form-license', plugins_url( 'assets/js/license-plate.js', __FILE__ ));
    wp_enqueue_style( 'gforms_datepicker_css', plugins_url( '/gravityforms/legacy/css/datepicker.min.css'));
    wp_enqueue_script( 'vehicle-form-date' , plugins_url( 'assets/js/date.js', __FILE__ ), array( 'jquery', 'jquery-ui-datepicker' ));

    /*$countries = [
      'austria' => [
        'cssSuffix' => 'at',
        'url' => VEHICLE_FORM_PLATES . 'generic-at.svg',
        'placeholders' => array( 'W', '12345X', '', ),
        'maxlengths' => [ '2', '6', '0']
      ],
      'bulgaria' => [
        'cssSuffix' => 'bg',
        'url' => VEHICLE_FORM_PLATES . 'generic-bg.svg',
        'placeholders' => array( 'AB12CDYZ' ),
        'maxlengths' => [ '8', '0', '0' ],
      ],
      'germany' => [
        'cssSuffix' => 'ge',
        'url' => VEHICLE_FORM_PLATES . 'bg-de.svg',
        'placeholders' => array( 'B', 'AB', '1234', ),
        'maxlengths' => [ '2', '2', '4' ],
      ],
      'romania' => [
        'cssSuffix' => 'ro',
        'url' => VEHICLE_FORM_PLATES . 'bg-ro.svg',
        'placeholders' => array( 'CC', '12', 'ABC', ),
        'maxlengths' => [ '2', '2', '3' ],
      ]
    ];*/

    wp_localize_script( 'vehicle-form-license', 'countries', VEHICLE_FORM_COUNTRIES );
  }
}

add_action( 'gform_enqueue_scripts', 'vehicle_form_enqueue', 10, 2 );