<?php 
class GF_Field_Vehicle extends GF_Field {

  public $type = "vehicle";

  public function get_field_input( $form, $value='', $entry = '' ){
    $id = (int) $this->id;
    ob_start();
    ?>
      <div class="ginput_container">
        <div class='vehicle-buttons-wrapper'>
          <button type="button" class='vehicle-field-button btn-motorcycle'>
            <div class='icon-wrap'>
              <?php include VEHICLE_FORM_ASSETS . 'inline-svg/motorcycle.php' ?>
            </div>
            <div class="description">
              <?php esc_html_e( 'Motorcycle', 'vehicle-input' ); ?>
            </div>
          </button>
          <button type="button" class='vehicle-field-button btn-automobile'>
            <div class='icon-wrap'>
              <?php include VEHICLE_FORM_ASSETS . 'inline-svg/automobile.php' ?>
            </div>
            <div class="description">
              <?php esc_html_e( 'Automobile', 'vehicle-input' ); ?>
            </div>
          </button>
        </div>
        <input type="hidden" id="<?php echo "input_{$form['id']}_{$id}" ?>" name="input_<?php echo esc_attr($id); ?>" value="<?php echo rgpost( 'input_' . esc_attr( $id )) ?>">
      </div>
    <?php
    $markup = ob_get_contents();
    ob_end_clean();
    return $markup;
  }

  public function get_form_editor_field_title(){
    return esc_attr__( 'Vehicle', 'vehicle-form' );
  }

  public function validate( $value, $form ){
    $allowed = [ 'automobile', 'motorcycle' ];
    if ( !in_array( $value, $allowed )){
      $this->failed_validation = true;
      $this->validation_message = __( "Please select either automobile or motorcycle.", 'vehicle-form' );
    }
  }

  public function sanitize_entry_value( $value, $form_id ){
    return sanitize_text_field( $value );
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

GF_Fields::register( new GF_Field_Vehicle() );