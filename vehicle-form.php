<?php 
/**
 
 * @wordpress-plugin
 * Plugin Name:       Vehicle form
 * Description:       Support for gravity form allowing users to get a vehicle permit.
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
  'belgium' => [
    'cssSuffix' => 'be',
    'url' => VEHICLE_FORM_PLATES . 'generic-bg.svg',
    'placeholders' => [ 'ABC123', '', '' ],
    'maxlengths' => [ '10', '0', '0' ]
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
  'ireland' => [
    'cssSuffix' => 'ie',
    'url' => VEHICLE_FORM_PLATES . 'generic-ie.svg',
    'placeholders' => ['123','AB', '123456' ],
    'maxlengths' => [ '3', '2', '6' ],
  ],
  'italy' => [
    'cssSuffix' => 'it',
    'url' => VEHICLE_FORM_PLATES . 'generic-it.svg',
    'placeholders' => ['AB', '123', 'YZ' ],
    'maxlengths' => [ '2', '3', '2' ],
  ],
  'lithuania' => [
    'cssSuffix' => 'lt',
    'url' => VEHICLE_FORM_PLATES . 'generic-lt.svg',
    'placeholders' => [ 'ABC', '123', '' ],
    'maxlengths' => [ '3', '3', '0' ],
  ],
  'luxembourg' => [
    'cssSuffix' => 'lu',
    'url' => VEHICLE_FORM_PLATES . 'generic-lu.svg',
    'placeholders' => [ 'AB1234', '', '' ],
    'maxlengths' => [ '10', '0', '0' ]
  ],
  'malta' => [
    'cssSuffix' => 'mt',
    'url' => VEHICLE_FORM_PLATES . 'generic-mt.svg',
    'placeholders' => [ 'ABC', '123', '' ],
    'maxlengths' => [ '3', '3', '0' ],
  ],
  'netherlands' => [
    'cssSuffix' => 'nl',
    'url' => VEHICLE_FORM_PLATES . 'generic-nl.svg',
    'placeholders' => [ '1XA', '1XA', '1XA' ],
    'maxlengths' => [ '3', '3', '3' ],
  ],
  'poland' => [
    'cssSuffix' => 'pl',
    'url' => VEHICLE_FORM_PLATES . 'bg-pl.svg',
    'placeholders' => [ 'XY', '1234J', '' ],
    'maxlengths' => [ '3', '6', '0' ]
  ],
  'portugal' => [
    'cssSuffix' => 'pt',
    'url' => VEHICLE_FORM_PLATES . 'generic-pt.svg',
    'placeholders' => [ 'AB01BB', '', ''],
    'maxlengths' => [ '6', '0', '0' ],
  ],
  'slovakia' => [
    'cssSuffix' => 'sk',
    'url' => VEHICLE_FORM_PLATES . 'bg-sk.svg',
    'placeholders' => [ 'AB', '123YZ', '' ],
    'maxlengths' => ['2', '5', '0' ],
  ],
  'slovenia' => [
    'cssSuffix' => 'sl',
    'url' => VEHICLE_FORM_PLATES . 'bg-sl.svg',
    'placeholders' => [ 'AB', 'A12', 'B12' ],
    'maxlengths' => [ '2', '3', '3' ],
  ],
  'spain' => [
    'cssSuffix' => 'es',  
    'url' => VEHICLE_FORM_PLATES . 'generic-es.svg',
    'placeholders' => [ '1234ABC', '' , ''],
    'maxlengths' => [ '10', '0', '0' ],
  ],
  'sweden' => [
    'cssSuffix' => 'se',
    'url' => VEHICLE_FORM_PLATES . 'generic-se.svg',
    'placeholders' => [ 'ABC', '12A', '' ],
    'maxlengths' => [ '3', '3', '0' ],
  ],
  'switzerland' => [
    'cssSuffix' => 'ch',
    'url' => VEHICLE_FORM_PLATES . 'bg-ch.svg',
    'placeholders' => [ 'AB', '123456', '' ],
    'maxlengths' => [ '2', '6', '0' ]
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
  'ten-day' => [
    'name' => __( '10 Day Vignette Austria', 'vehicle-form' ),
    'price' => 12.50,
    'info' => __( 'Valid immediately or later if desired.', 'vehicle-form' ),
    'duration' => 'ten-day'
  ],
  'two-month' => [
    'name' => __( '2 Months Vignette Austria', 'vehicle-form'),
    'price' => 23.60,
    'info' => __( 'Valid immediately or later if desired.', 'vehicle-form' ),
    'duration' => 'two-month'
  ],
  'annual' => [
    'name' => __( 'Annual Vignette Austria 2024', 'vehicle-form' ),
    'price' => 49.90,
    'info' => __( 'Valid from December 1st, 2023 - January 31st, 2025' ),
    'duration' => 'annual'
  ],
  'single' => [
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

  $license_plate_fields = GFAPI::get_fields_by_type( $form, 'license_plate' );
  if ( count( $license_plate_fields )){
    wp_enqueue_style( 'montserrat', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap' );
    wp_enqueue_style( 'vehicle-form-all', plugins_url(  'assets/css/all.min.css', __FILE__ ));
    wp_enqueue_script( 'vehicle-form-all', plugins_url(  'assets/js/all.min.js', __FILE__  ), array( 'jquery', 'jquery-ui-datepicker' ));
    wp_localize_script( 'vehicle-form-all', 'countries', VEHICLE_FORM_COUNTRIES );
    wp_localize_script( 'vehicle-form-all', 'vfplans', VEHICLE_FORM_PLANS );
    wp_localize_script( 'vehicle-form-all', 'img', array( 'src' => VEHICLE_FORM_PLATES . 'close.svg' ));
  }
}

add_action( 'gform_enqueue_scripts', 'vehicle_form_enqueue', 10, 2 );

function vehicle_form_enqueue_confirmation_style(){
  wp_enqueue_style( 'vehicle-form-confirmation', plugins_url( 'assets/css/confirmation.css', __FILE__ ));
}

add_action( 'wp_enqueue_scripts', 'vehicle_form_enqueue_confirmation_style' );

function vehicle_form_register_query_vars( $vars ){
  $vehicle_form_vars = array( 'vf-plan', 'vf-firstname', 'vf-lastname', 'vf-invoice', 'vf-company', 'vf-street', 'vf-region', 'vf-city', 'vf-zip' );
  $vars = array_merge( $vars, $vehicle_form_vars );
  return $vars;
}

add_filter( 'query_vars', 'vehicle_form_register_query_vars' );

function vehicle_form_filter_content( $content ){
  global $post;
  if ( 198 != $post->ID ){//change
    return $content;
  } else {

    $plan_param = get_query_var( 'vf-plan', '' );
    if ( '' == $plan_param || !isset( VEHICLE_FORM_PLANS[ $plan_param ] ) ){
      $plan = __( 'Your order', 'vehicle-form' ); 
    } else {
      $plan = esc_html( VEHICLE_FORM_PLANS[ $plan_param ]['name'] );
    }

    $invoice_var = get_query_var( 'vf-invoice', '' );
    $needs_invoice = $invoice_var == "I need an invoice";
    
    $firstname = ucfirst( get_query_var( 'vf-firstname', '' ));
    $h2_string =  empty( $firstname ) ? __('Thank you!', 'vehicle-form') : __( 'Thank you, %1$s!', 'vehicle-form' );
    $h2 = sprintf( $h2_string, esc_html( $firstname ) );
    $intro = esc_html__( 'For your records, please find your order information listed below.', 'vehicle-form' );
    $price = isset( $_GET[$plan_param] ) ? $_GET[$plan_param] : '';
    $price_string = !empty( $price ) ? sprintf( '<p><strong>Total:</strong> %1$s €</p>', esc_html( $price ) ) : '';

    $invoice_info = "";
    if ( $needs_invoice ){
      $issued_to = sprintf( 
        __( '<strong>Issued to</strong>: %1$s' ), 
        esc_html( $firstname . ' ' . get_query_var( 'vf-lastname', '' )),
      );

      $company = get_query_var( 'vf-company', '' );
  
      $company_label = sprintf( '<strong>%1$s:</strong> ', __( 'Company', 'vehice-form' ) );
      $company_text = $company_label . esc_html( ucfirst( $company ));
      
      $street = get_query_var( 'vf-street', '' );
      $city = esc_html( get_query_var( 'vf-city', '' ));
      $region = esc_html( get_query_var( 'vf-region', '' ) );
      $street_address = sprintf( 
        '<strong>%1$s:</strong> %2$s', 
        __( 'Street Address', 'vehicle-form' ),
        esc_html( $street ),
      );
      $postal_code = esc_html( get_query_var( 'vf-zip', '' ));

      $invoice_info = "
        <div class='invoice-info'>
          <div class='issued-to'>{$issued_to}</div>
          <div class='company'>{$company_text}</div>
          <div class='vf-address'>{$street_address}</div>
          <div class='vf-city'><strong>City:</strong> {$city}</div>
          <div class='vf-region'><strong>Region:</strong> {$region}</div>
          <div class='vf-zip'><strong>Postal Code</strong> {$postal_code}</div>
        </div>
      ";
    }


    $content = "
      <article class='vf-confirmation'>
        <header>
          <h2>{$h2}</h2>
        </header>
        <div class='vf-conf-content'>
          <p>{$intro}</p>
          <div class='order-summary-wrap'>
            <div class='order-summary'>
              <h3>{$plan}</h3>
              {$price_string}
              {$invoice_info}
            </div>
          </div>
        </div>
      </article>
    ";
    return $content;
  }
}

add_filter( 'the_content', 'vehicle_form_filter_content' );