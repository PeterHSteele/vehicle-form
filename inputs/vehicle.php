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
        <input type="hidden" name="input_<?php echo esc_attr($id); ?>" value="<?php echo rgpost( 'input_' . esc_attr( $id )) ?>">
      </div>
    <?php
    $markup = ob_get_contents();
    ob_end_clean();
    return $markup;
  }

  public function get_svg(){
    ?>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 750 750">
      <defs>
        <style>
          .cls-1 {
            fill: #5bc89a;
          }

          .cls-1, .cls-2 {
            stroke-width: 0px;
          }

          .cls-2 {
            fill: #fff;
          }
        </style>
      </defs>
      <g id="Layer_1-2" data-name="Layer 1">
        <circle class="cls-1" cx="375" cy="375" r="375"/>
        <g>
          <path class="cls-2" d="m409.69,434c-2.41-9.26-3.69-18.98-3.69-29,0-55.45,39.25-101.73,91.48-112.59l-17.98-46.91h34.16c6.26,0,11.34-5.08,11.34-11.34v-36.68c0-7.45-6.04-13.48-13.48-13.48h-33.76c-4.01,0-7.9,1.31-11.09,3.73l-154.66,117.27-28.66-26.64c-4.4-4.09-10.18-6.36-16.18-6.36h-127.44c-8.13,0-14.72,6.59-14.72,14.72v29.78h116v.5c55.23,0,100,44.77,100,100,0,5.8-.5,11.47-1.45,17h70.14Z"/>
          <path class="cls-2" d="m519.37,414.96l-86.55-206.75c-2.33-5.58-7.79-9.21-13.84-9.21h-72.96c-8.28,0-15-6.72-15-15h0c0-8.28,6.72-15,15-15h92.92c6.05,0,11.5,3.63,13.84,9.21l94.26,225.16c3.2,7.64-.4,16.43-8.04,19.63h0c-7.64,3.2-16.43-.4-19.63-8.04Z"/>
          <path class="cls-2" d="m533,309c-55.23,0-100,44.77-100,100s44.77,100,100,100,100-44.77,100-100-44.77-100-100-100Zm0,150c-27.61,0-50-22.39-50-50s22.39-50,50-50,50,22.39,50,50-22.39,50-50,50Z"/>
          <g>
            <circle class="cls-2" cx="226" cy="424" r="20"/>
            <path class="cls-2" d="m266.32,444c-7.33,14.78-22.47,24.94-39.97,24.94-24.67,0-44.67-20.17-44.67-45.06s20-45.06,44.67-45.06c17.6,0,32.81,10.27,40.09,25.18h47.07c-8.98-40.21-44.59-70.24-87.16-70.24-49.35,0-89.35,40.35-89.35,90.12s40,90.12,89.35,90.12c42.49,0,78.05-29.91,87.11-70h-47.14Z"/>
          </g>
        </g>
      </g>
    </svg>
    <?php
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