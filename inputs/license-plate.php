<?php

class GF_Field_License_Plate extends GF_Field {

  public $type = 'license_plate';
  private $patterns = [
    'austria' => ['/[a-zA-Z]{1,2}/', '/^[a-zA-Z0-9]{3,6}$/'],
    'bulgaria' => ['/^[A-Za-z0-9]{7,8}$/'],
    'germany' => [ '/^[A-Za-z]{1,3}$/', '/^[a-zA-Z]{1,2}$/', '/^[0-9]{1,4}$/' ],
    'romania' => [ '/^[A-Za-z]{1,2}$/', '/^[0-9]{1,2}$/', '/^[A-Za-z]{1,3}$/' ],
    'croatia' => [ '/^[A-Za-z0-9-]{7,9}$/'],
  ];
  
  public function get_form_editor_field_title(){
    return esc_attr__( 'License Plate', 'vehicle-form' );
  }
  
  public function country_select( $input_id, $value, $form_id ){
    $field_id = (int) $this->id;
    $select_label = esc_html__( 'State of registration of the License Plate', 'vehicle-form' );
    ?>
      <div class='country-select-container'>
        <label for="<?php echo "input_{$form_id}_{$field_id}" ?>" class='country-label'><?php echo $select_label ?></label>
        <select name="<?php echo "input_{$field_id}.{$input_id}" ?>" id="<?php echo "input_{$form_id}_{$field_id}" ?>" value="<?php echo esc_attr($value) ?>">
          <?php foreach ( $this->get_choices() as $country => $display_name ) : ?>
            <option value="<?php echo $country; ?>" <?php echo $value==$country ? 'selected' : '' ?>><?php echo $display_name; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    <?php
  }
  
  public function date_input( $label, $id, $input_id, $value, $is_start ){
    $input_id_attr = "input_{$id}_{$input_id}";
    $css_class = $is_start ? 'start-date' : 'end-date';
    $disabled_text = $is_start ? '' : 'disabled';
    ?>
    <div class="date-input-container <?php echo $css_class; ?>" id='<?php echo "input_{$id}_{$input_id}_container"?>'>
      <label for="<?php echo $input_id_attr; ?>">
        <?php echo esc_html( $label ) ?>
      </label>
      <input type="text" id="<?php echo $input_id_attr; ?>" name='<?php echo "input_{$id}.$input_id" ?>' value="<?php echo esc_attr( $value ) ?>" <?php echo $disabled_text; ?>>
    </div>
    <?php
  }

  public function license_plate_input( $input, $form_id, $value, $country_value){
    $field_id = (int) $this->id;
    $input_id = "input_{$form_id}_{$field_id}_{$input}";
    $name = "input_{$field_id}.{$input}";
    
    $country_is_set = isset( VEHICLE_FORM_COUNTRIES[$country_value] );
    if ( !$country_is_set ){
      $country = VEHICLE_FORM_COUNTRIES['austria'];
    } else {
      $country = VEHICLE_FORM_COUNTRIES[$country_value];
    }
    
    $attr_index = ( $input - 4 ) % 3;
    $placeholders = $country['placeholders'];
    $placeholder = $placeholders[ $attr_index ];
    $maxlengths = $country['maxlengths'];
    $maxlength = $maxlengths[ $attr_index ];
        
    $disabled = in_array( $input, array(6, 9) ) ? 'disabled' : '';//for default country, Austria
    $disabled = '';//change

    ?>
      <span id="input_<?php echo $field_id; ?>_1_container" class="lp-input <?php echo "lp-input-$input";?>">
        <input type="text" id="<?php echo $input_id; ?>" name="<?php echo $name; ?>" value="<?php echo $value ?>" placeholder="<?php echo $placeholder; ?>" maxlength="<?php echo $maxlength ?>" <?php echo $disabled ?>>
      </span>
    <?php
  }
  
  public function get_field_input( $form, $value = '', $entry = null ){
    $is_entry_detail = $this->is_entry_detail();
    $is_form_editor = $this->is_form_editor();

    $form_id = $form['id'];
    $id = ( int ) $this->id;

    $start_date_value = $end_date_value = $country_value = $license_portion_one_val = $license_portion_two_val = $license_portion_three_val = '';

    if ( is_array( $value )){
      $start_date_value = esc_attr( rgget( $this->id . '.1', $value ));
      $end_date_value = esc_attr( rgget( $this->id . '.2', $value ));
      $country_value = esc_attr( rgget( $this->id . '.3', $value ));
      $license_portion_one_val = esc_attr( rgget( $this->id . '.4', $value ));
      $license_portion_two_val = esc_attr( rgget( $this->id . '.5', $value ));
      $license_portion_three_val = esc_attr( rgget( $this->id . '.6', $value ));
      $license_conf_portion_one_val = esc_attr( rgget( $this->id . '.7', $value ));
      $license_conf_portion_two_val = esc_attr( rgget( $this->id . '.8', $value ));
      $license_conf_portion_three_val = esc_attr( rgget( $this->id . '.9', $value ));
    }

    $disabled_text = $is_form_editor ? 'disabled' : '';
    $class_suffix = $is_entry_detail ? '_admin' : '';
  
    $license_plate_layout = isset( VEHICLE_FORM_COUNTRIES[$country_value] ) ? VEHICLE_FORM_COUNTRIES[$country_value]['cssSuffix'] : 'at';
    $img_url = isset( VEHICLE_FORM_COUNTRIES[$country_value] ) ? VEHICLE_FORM_COUNTRIES[$country_value]['url'] : plugins_url( '../assets/img/generic-at.svg', __FILE__ );

    $img = "<img class='license-backdrop' alt='' src='{$img_url}'>";
    
    ob_start();
    ?>
    <div class='ginput_container'>
      <div class="date-fields-container">
        <?php 
          echo $this->date_input( sprintf( '%1$s:', __( 'Valid From', 'vehicle-form') ), $id, 1, $start_date_value, true ); 
          echo $this->date_input( sprintf( '%1$s:', __( 'Valid Till', 'vehicle-form' ) ), $id, 2, $end_date_value, false );
        ?>
      </div>
      <?php $this->country_select( 3, $country_value, $form_id ); ?>
      <div class="license-plate-fields">
        <fieldset class="license-plate-field">
          <legend><?php esc_html_e( 'Mark', 'vehicle-form' ); ?></legend>
          <div class="license-plate-inputs">
            <img class='license-backdrop' alt='' src="<?php echo esc_url( $img_url ); ?>">
            <div class="ginput-license-inner layout-<?php echo $license_plate_layout; ?>">
              <?php 
                $this->license_plate_input( 4, $form_id, $license_portion_one_val, $country_value ); 
                $this->license_plate_input( 5, $form_id, $license_portion_two_val, $country_value );
                $this->license_plate_input( 6, $form_id, $license_portion_three_val, $country_value );
              ?>
            </div>
          </div>
        </fieldset>

        <fieldset class="license-plate-field">
          <legend><?php esc_html_e( 'Repeat License Plate', 'vehicle-form' ); ?></legend>
          <div class="license-plate-inputs">
            <img class='license-backdrop' alt='' src="<?php echo esc_url( $img_url ); ?>">
            <div class="ginput-license-inner layout-<?php echo $license_plate_layout; ?>">
              <?php 
                $this->license_plate_input( 7, $form_id, $license_conf_portion_one_val, $country_value ); 
                $this->license_plate_input( 8, $form_id, $license_conf_portion_two_val, $country_value );
                $this->license_plate_input( 9, $form_id, $license_conf_portion_three_val, $country_value );
              ?>
            </div>
          </div>
        </fieldset>
      </div>
    </div>
    <?php 
    $markup = ob_get_contents();
    ob_end_clean();
    
    return $markup;
  }
  
  public function get_choices(){
    $choices = [
      'austria' => esc_html__('Austria', 'vehicle-form'),
      'bulgaria' => esc_html__('Bulgaria', 'vehicle-form'),
      'croatia' => esc_html__( 'Croatia', 'vehicle-form' ),
      'germany' => esc_html__('Germany', 'vehicle-form'),
      'romania' => esc_html__('Romania', 'vehicle-form')
    ];
    return $choices;
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

  public function get_form_editor_inline_script_on_page_render() {

    // set the default field label for the field
    $script = sprintf( "function SetDefaultValues_%s(field) {
    field.label = '%s';
    field.inputs = [
      new Input(field.id + '.1', ''), 
      new Input(field.id + '.2', ''), 
      new Input(field.id + '.3', ''),
      new Input(field.id + '.4', ''),
      new Input(field.id + '.5', ''),
      new Input(field.id + '.6', ''),
      new Input(field.id + '.7', ''),
      new Input(field.id + '.8', ''),
      new Input(field.id + '.9', ''),
    ];
    }", $this->type, $this->get_form_editor_field_title() ) . PHP_EOL;

    return $script;
  }

  /* 
  array(9) { 
    ["16.1"]=> string(10) "10/10/2023" 
    ["16.2"]=> string(0) "" 
    ["16.3"]=> string(7) "germany" 
    ["16.4"]=> string(1) "1" 
    ["16.5"]=> string(2) "ab" 
    ["16.6"]=> string(4) "1312" 
    ["16.7"]=> string(1) "1" 
    ["16.8"]=> string(2) "ab" 
    ["16.9"]=> string(4) "1312" }
  */

  public function validate( $value, $form ){
    $id = $this->id;
    $countries = array_keys( $this->get_choices() );
    
    /* Country is in list of allowed countries */
    $country_value = $value[$id.'.3'];
    if ( !in_array( $country_value, $countries ) ){
      $this->failed_validation = true;
      $this->validation_message = __( 'Please choose a valid country', 'vehicle-form' );
    }

    /* Test each license plate segment according to the rules for that country */
    $patterns = $this->patterns[$country_value];
    $submitted_license_plate_portions = [ $id.'.4', $id.'.5', $id.'.6' ];

    for ( $i=0; $i < count($patterns); $i++ ){
      if ( 1 !== preg_match( $patterns[$i], $value[ $submitted_license_plate_portions[$i] ] )){
        $this->failed_validation = true;
        $this->validation_message = sprintf( __( '%1$s is not a valid value for that part of the license plate', 'vehicle-form' ), esc_html( $value[ $submitted_license_plate_portions[$i] ] ) );
        break;
      }
    }

    //Ensure license plate fields match
    $pairs = [ [ $id.'.4', $id.'.7'], [ $id.'.5', $id.'.8'], [ $id.'.6', $id.'.9' ] ];
    foreach ( $pairs as $pair ){
      if ( $value[$pair[0]] !== $value[$pair[1]] ){
        $this->failed_validation = true;
        $this->validation_message = __( 'License plate values must match.', 'vehicle-form' );
        break;
      }
    }
  }

  public function sanitize_entry_value( $value, $form_id ){
    return sanitize_text_field( $value );
  }
}

GF_Fields::register( new GF_Field_License_Plate() );