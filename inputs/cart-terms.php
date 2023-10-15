<?php

class GF_Field_Cart_And_Terms extends GF_Field {

  public $type = 'cart_and_terms';

  public function get_form_editor_field_title(){
    return esc_attr__( 'Cart And Terms', 'vehicle-form' );
  }

  public function get_field_input( $form, $value = '', $entry = '' ){
    $id = $this->id;
    $form_id = $form['id'];

    $checkbox_one_val = $checkbox_two_val = '';
    if ( is_array( $value )){
      $checkbox_one_val = esc_attr( rgget( $id.'.1', $value ));
      $checkbox_two_val = esc_attr( rgget( $id.'.2', $value ));
    }

    $checkbox_one_val = esc_attr__( 'I agree to the terms and conditions, the cancellation policy and the consumer information. I agree and expressly request that you begin carrying out the ordered service before the end of the cancellation period. I am aware that if you fulfill the contract in full, I will lose my right of cancellation.', 'vehicle-form' );
    $checkbox_two_text = '<span>' . esc_html__( 'Sign up for our newsletter. This way you will regularly find out everything about our new products and offers.', 'vehicle-form' ) . '</span><span>' . esc_html__( 'As a result of your purchase, we will send you advertising about similar products. You can object to its use here and at any time.', 'vehicle-form' ) . '</span>';
    $checkbox_two_val = esc_attr( $checkbox_two_text );

    $html = "
    <div class='ginput_container'>
      <div class='vh-terms-checkbox'>
        <input type='checkbox' name='input_{$id}.1' id='input_{$form_id}_{$id}_1' value='{$checkbox_one_val}'>
        <label for='input_{$form_id}_{$id}_1'>
          <span class='vh-custom-box-wrap'>
            <span class='vh-custom-box'></span>
          </span>
          <span class='label-text'>$checkbox_one_val</span>
        </label>
      </div>
      <div class='vh-terms-checkbox'>
        <input type='checkbox' name='input_{$id}.2' id='input_{$form_id}_{$id}_2' value='{$checkbox_two_val}'>
        <label for='input_{$form_id}_{$id}_2'>
          <span class='vh-custom-box-wrap'>
            <span class='vh-custom-box'></span>
          </span>
          <span class='label-text'>$checkbox_two_text</span>
        </label>
      </div>
    </div>
    ";

    return $html;
  }

  public function get_field_content( $value, $force_frontend_label, $form ) {
    ob_start();
    $field_label = $this->get_field_label( $force_frontend_label, $value );
    ?>
    <label class='gfield_label gform-field-label' ><?php echo esc_html( $field_label ); ?></label>
    <div class="vf-cart">
      <h2><?php esc_html_e( 'My Cart', 'vehicle-form' ) ?></h2>
      <div class="chosen-plan">
        <!--<div>
          <div class="plans-field-plan">
            <div class="price-container">
              <p><?php echo esc_html( number_format( 0, 2 )); ?> €</p>
            </div>
            <div class="info-container">
              <h2>Test heading</h2>
              <p>test desc</p>
            </div>
            <button type="button" class="remove-from-cart">
              <span class="screen-reader-text">
                <?php esc_html_e( 'Remove item from cart', 'vehicle-form' ); ?>
              </span>
              <?php //include VEHICLE_FORM_ASSETS . 'inline-svg/close.php'; ?>
              <img alt='' role="presentation" src="<?php echo esc_url( VEHICLE_FORM_PLATES . 'close.svg' ); ?>">
            </button>
          </div>
          <div class="vf-cart-total">
            <p><?php echo esc_html__( 'Total', 'vehicle-form' ) . ':'; ?></p>
            <p><?php echo esc_html( number_format( 0, 2 )); ?></p>
          </div>
        </div>-->
      </div>
    </div>
    {FIELD}
    <?php
    $markup = ob_get_contents();
    ob_end_clean();
    return $markup;
  }

  public function get_form_editor_inline_script_on_page_render() {
    $script = sprintf( "function SetDefaultValues_%s(field) {
    field.label = '%s';
    field.inputs = [
      new Input(field.id + '.1', ''), 
      new Input(field.id + '.2', ''), 
      new Input(field.id + '.3', ''),
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