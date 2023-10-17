<?php

class GF_Field_Cart_And_Terms extends GF_Field {

  public $type = 'cart_and_terms';

  public function get_form_editor_field_title(){
    return esc_attr__( 'Cart And Terms', 'vehicle-form' );
  }

  public $checkbox_one_val = 'I agree to the terms and conditions, the cancellation policy and the consumer information. I agree and expressly request that you begin carrying out the ordered service before the end of the cancellation period. I am aware that if you fulfill the contract in full, I will lose my right of cancellation.';
  public $checkbox_two_val = 'Sign up for our newsletter. This way you will regularly find out everything about our new products and offers. As a result of your purchase, we will send you advertising about similar products. You can object to its use here and at any time.';

  public function get_field_input( $form, $value = '', $entry = '' ){
    $id = $this->id;
    $form_id = $form['id'];

    if ( is_array( $value )){
      $current_box_one_val = esc_attr( rgget( $id.'.1', $value ));
      $current_box_two_val = esc_attr( rgget( $id.'.2', $value ));
    }
    
    $checkbox_one_label = esc_html( $this->checkbox_one_val );
    $checkbox_two_label = '<span>' . esc_html__( 'Sign up for our newsletter. This way you will regularly find out everything about our new products and offers.', 'vehicle-form' ) . '</span><span>' . esc_html__( 'As a result of your purchase, we will send you advertising about similar products. You can object to its use here and at any time.', 'vehicle-form' ) . '</span>';
    
    $one_is_checked = $current_box_one_val == $this->checkbox_one_val ? 'checked' : '';
    $two_is_checked = $current_box_two_val == $this->checkbox_two_val ? 'checked' : '';

    $html = "
    <div class='ginput_container'>
      <div class='vh-terms-checkbox'>
        <input type='checkbox' name='input_{$id}.1' id='input_{$form_id}_{$id}_1' value='{$this->checkbox_one_val}' {$one_is_checked}>
        <label for='input_{$form_id}_{$id}_1'>
          <span class='vh-custom-box-wrap'>
            <span class='vh-custom-box'></span>
          </span>
          <span class='label-text'>$checkbox_one_label</span>
        </label>
      </div>
      <div class='vh-terms-checkbox'>
        <input type='checkbox' name='input_{$id}.2' id='input_{$form_id}_{$id}_2' value='{$this->checkbox_two_val}' {$two_is_checked}>
        <label for='input_{$form_id}_{$id}_2'>
          <span class='vh-custom-box-wrap'>
            <span class='vh-custom-box'></span>
          </span>
          <span class='label-text'>$checkbox_two_label</span>
        </label>
      </div>
    </div>
    ";

    return $html;
  }
  
  public function get_field_content( $value, $force_frontend_label, $form ) {
    $field_label = esc_html( $this->get_field_label( $force_frontend_label, $value ));
    $h2 = esc_html__( 'My Cart', 'vehicle-form' );

    $validation_message_id = 'validation_message_' . $form['id'] . '_' . $this->id;
		$validation_message = ( $this->failed_validation && ! empty( $this->validation_message ) ) ? sprintf( "<div id='%s' class='gfield_description validation_message gfield_validation_message'>%s</div>", $validation_message_id, $this->validation_message ) : '';

    $html = "
      <label class='gfield_label gform-field-label'>{$field_label}</label>
      <div class='vf-cart'>
        <h2>{$h2}</h2>
        <div class='chosen-plan'></div>
      </div>
      {FIELD}
      {$validation_message}
    ";
    //var_dump($this->get_admin_buttons());
    if ( $this->is_form_editor() ){
      $html = $this->get_admin_buttons() . $html;
    }
    
    return $html;
  }

  /*
  array(24) { 
    ["input_12"]=> string(10) "motorcycle" 
    ["input_14"]=> string(7) "ten-day" 
    ["input_16_1"]=> string(10) "10/09/2023" 
    ["input_16_2"]=> string(10) "10/19/2023" 
    ["input_16_3"]=> string(8) "bulgaria" 
    ["input_16_4"]=> string(8) "12345678" 
    ["input_16_5"]=> string(0) "" 
    ["input_16_6"]=> string(0) "" 
    ["input_16_7"]=> string(8) "12345678" 
    ["input_16_8"]=> string(0) "" 
    ["input_16_9"]=> string(0) "" 
    ["input_18_3"]=> string(0) "" 
    ["input_18_6"]=> string(0) "" 
    ["input_19"]=> string(0) "" 
    ["input_21_1"]=> string(15) "termsconditions" 
    ["input_21_2"]=> string(10) "newsletter" 
    ["is_submit_2"]=> string(1) "1" 
    ["gform_submit"]=> string(1) "2" 
    ["gform_unique_id"]=> string(0) "" 
    ["state_2"]=> string(176) "WyJ7XCIyMC4xXCI6XCI5MDYyNmEzYWRlZmQ2OGM2MTQ1MjI0YzA0YmExODY1ZFwiLFwiMjAuMlwiOlwiYjAzMzljNjVjYTA2Yjk3ZWI3YWU2NmQwZmYwN2E4NjZcIn0iLCIzZDdiZGQ4NjkwNjUwMGQ5MTU0NTkyNDcxYTc5YWJjNCJd" 
    ["gform_target_page_number_2"]=> string(1) "0" 
    ["gform_source_page_number_2"]=> string(1) "1" 
    ["gform_field_values"]=> string(0) "" 
    ["version_hash"]=> string(32) "6329e3791b5e32a32dde8c65f14c7836" 
  } */

  public function validate( $value, $form ){

    if ( empty( $value[$this->id.'.1'] ) ){
      $this->failed_validation = true;
      $this->validation_message = esc_html__( 'Please agree to the terms and conditions.', 'vehicle-form' );
      return;
    }
    
    if ( $value[$this->id.'.1'] != $this->checkbox_one_val ){
      $this->failed_validation = true;
      $this->validation_message = esc_html__( 'Error validating terms checkbox.', 'vehicle-form' );
    }

    if ( !empty( $value[$this->id.'.2'] ) && $value[$this->id.'.2'] != $this->checkbox_two_val ){
      $this->failed_validation = true;
      $this->validation_message = esc_html__( 'Error validating subscription checkbox.', 'vehicle-form' );
    }
  }

  public function sanitize_entry_value( $value, $form_id ){
    return sanitize_text_field( $value );
  }

  public function get_value_entry_detail( $value, $currency = '', $use_text = false, $format = 'html', $media = 'screen' ){
    if ( !is_array( $value )){
      echo esc_html($value);
    }

    $html = '<ul class="bulleted">';
    foreach( $value as $field ){
      if ( $field ){
        $html .= '<li>' . $field . '</li>';
      }
    }
    $html .= '</ul>';
    return $html;
  }


  public function get_form_editor_inline_script_on_page_render() {
    $script = sprintf( "function SetDefaultValues_%s(field) {
    field.label = '%s';
    field.inputs = [
      new Input(field.id + '.1', ''), 
      new Input(field.id + '.2', ''),
    ];
    }", $this->type, $this->get_form_editor_field_title() ) . PHP_EOL;

    return $script;
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

}

GF_Fields::register( new GF_Field_Cart_And_Terms() );