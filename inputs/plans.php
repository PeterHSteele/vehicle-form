<?php

class GF_Field_Plans extends GF_Field {

  public $type = 'pricing-plan';

  public function get_form_editor_field_title(){
    return __( 'Pricing Plan', 'vehicle-form' );
  }

  public function plan_card_markup( $plandata ){
    ?>
    <div class="plans-field-plan" data-duration="<?php echo $plandata['duration'] ?>">
      <div class="price-container">
        <p><?php echo esc_html( number_format( $plandata['price'], 2 )); ?> €</p>
      </div>
      <div class="info-container">
        <h2><?php echo esc_html( $plandata['name'] ); ?></h2>
        <p><?php echo esc_html( $plandata['info'] ); ?></p>
        <div>
          <button type="button"><?php esc_html_e( 'Add To Cart', 'vehicle-form' ); ?></button>
        </div>
      </div>
    </div>
    <?php
  }

  public function plan_data(){
    /*return [
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
    ];*/
    return VEHICLE_FORM_PLANS;
  }

  public function get_field_input( $form, $value = '', $entry = '' ){
    $id = (int) $this->id;
    $input_name = 'input_' . $id;
    $input_value= empty( rgpost( $input_name ) ) ? '' : rgpost( $input_name );
    $plan_data = $this->plan_data();

    ob_start();
    ?>
      <div class="ginput_container">
        <div class="plans-field-cards">
          <?php 
          foreach ( $plan_data as $plan ){
            $this->plan_card_markup( $plan );
          }
          ?>
        </div>
        <input type="hidden" name="<?php echo esc_attr( 'input_' . $id ) ?>" value="<?php echo esc_attr( $input_value ) ?>">
      </div>
    <?php
    $markup = ob_get_contents();
    ob_end_clean();
    return $markup;
  }

  function get_form_editor_field_settings() {
    return array(
        'label_setting',
        'label_placement_setting',
        'rules_setting',
        'visibility_setting',
        'description_setting',
        'css_class_setting',
    );
  }

  public function validate( $value, $form ){
    //$allowed = [ '', 'Ten Days', 'Two Months', 'One Year', 'Single Ticket' ];
    $allowed = array_map( function( $plan ){
      return $plan['duration'];
    } , VEHICLE_FORM_PLANS );
    
    if ( !in_array( $value, $allowed )){
      $this->failed_validation = true;
      $this->validation_message = __( "Please Select an Allowed Value", 'vehicle-form' );
    }
  }

  public function sanitize_entry_value( $value, $form_id ){
    return sanitize_text_field( $value );
  }

}

GF_Fields::register( new GF_Field_Plans() );