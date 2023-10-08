<?php

class GF_Field_Plans extends GF_Field {

  public $type = 'pricing-plan';

  public function get_form_editor_field_title(){
    return __( 'Pricing Plan', 'vehicle-form' );
  }

  public function plan_card_markup( $plandata ){
    ?>
    <div class="plans-field-plan" data-duration="<?php echo $plandata['duration'] ?>">
      <div>
        <h2><?php echo esc_html( $plandata['name'] ); ?></h2>
      </div>
      <div>
        <p><?php echo esc_html( $plandata['price'] ); ?> €</p>
      </div>
      <div>
        <p><?php echo esc_html( $plandata['info'] ); ?></p>
      </div>
      <div>
        <button type="button"><?php esc_html_e( 'Select', 'vehicle-form' ); ?></button>
      </div>
    </div>
    <?php
  }

  public function plan_data(){
    return [
      [
        'name' => __( '1 Day Vignette Austria', 'vehicle-form' ),
        'price' => 14.90,
        'info' => __( 'Valid immediately or later if desired.', 'vehicle-form' ),
        'duration' => 'one-day'
      ],
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
        'name' => __( 'One Year Vignette Austria', 'vehicle-form' ),
        'price' => 49.90,
        'info' => __( 'Valid from December 1st, 2023 - January 31st, 2025' ),
        'duration' => 'annual'
      ],
      [
        'name' => __( 'Single Ticket', 'vehicle-form' ),
        'price' => 11.50,
        'info' => '',
        'duration' => 'single'
      ]
    ];
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

}

GF_Fields::register( new GF_Field_Plans() );