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

require_once plugin_dir_path( __FILE__ ) . 'inputs/vehicle.php';
require_once plugin_dir_path( __FILE__ ) . 'inputs/plans.php';

function vehicle_form_enqueue( $form, $is_ajax ){
  
  $vehicle_fields = GFAPI::get_fields_by_type( $form, 'vehicle' );
  if ( count( $vehicle_fields )){
    wp_enqueue_style( 'vehicle-form-vehicle', plugins_url( '/assets/css/vehicle.css', __FILE__ ));
    wp_enqueue_script( 'vehicle-form-vehicle', plugins_url( '/assets/js/vehicle.js', __FILE__ ), array( 'jquery' ));
  }

  $plans_fields = GFAPI::get_fields_by_type( $form, 'pricing-plan' );
  if ( count( $plans_fields ) ){
    wp_enqueue_style( 'vehicle-form-plan', plugins_url( '/assets/css/plans.css', __FILE__ ));
    wp_enqueue_script( 'vehicle-form-plan', plugins_url( 'assets/js/plans.js', __FILE__ ));
  }
}

add_action( 'gform_enqueue_scripts', 'vehicle_form_enqueue', 10, 2 );