<?php 
class GF_Field_Vehicle extends GF_Field {

  public $type = "vehicle";

  public function get_field_input( $form, $value='', $entry = '' ){
    $id = (int) $this->id;

    $input = new DomDocument();
    $input_container = $input->createElement( 'div' );
    
    // .ginput_container
    $input_container_class = $input->createAttribute( 'class' );
    $input_container_class->value = "ginput_container";
    $input_container->appendChild( $input_container_class );

      // .vehicle-buttons-wrapper
      $vehicle_buttons_wrapper = $input->createElement( 'div' );
        //class="vehicle_buttons_wrapper"
        $vehicle_buttons_wrapper_class = $input->createAttribute( 'class' );
        $vehicle_buttons_wrapper_class->value = 'vehicle-buttons-wrapper';
        $vehicle_buttons_wrapper->appendChild( $vehicle_buttons_wrapper_class );
      $input_container->appendChild( $vehicle_buttons_wrapper );

        // <button>Motorcycle</button>
        $moto_button = $input->createElement('button', __( 'Motorcycle', 'vehicle-form' ) );
        $moto_button->setAttribute( 'class', 'vehicle-field-button btn-motorcycle' );
        $vehicle_buttons_wrapper->appendChild($moto_button);

        // <button>Automobile</button>
        $auto_button = $input->createElement('button', __( 'Automobile', 'vehicle-form' ) );
        $auto_button->setAttribute( 'class', 'vehicle-field-button btn-automobile' );
        $vehicle_buttons_wrapper->appendChild($auto_button);
      
      // <input>
      $input_tag = $input->createElement( 'input' );
        // type="hidden"
        $input_tag_type = $input->createAttribute( 'type' );
        $input_tag_type->value = 'hidden';
        $input_tag->appendChild( $input_tag_type );
        //name = input_{$id}
        $input_tag_name = $input->createAttribute( 'name' );
        $input_tag_name->value = 'input_' . esc_attr( $id );
        $input_tag->appendChild( $input_tag_name );
        //value=""
        $input_tag_value = $input->createAttribute( 'value' );
        $input_tag_value->value = rgpost( 'input_' . esc_attr( $id ));
        $input_tag->appendChild( $input_tag_value );
      $input_container->appendChild( $input_tag );

      $input->appendChild( $input_container );
    
    return $input->saveHTML();
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