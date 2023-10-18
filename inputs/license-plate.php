<?php

class GF_Field_License_Plate extends GF_Field {

  public $type = 'license_plate';
  private $patterns = [
    'austria' => ['/^[a-zA-Z]{1,2}$/', '/^[a-zA-Z0-9]{3,6}$/'],
    'belgium' => [ '/^[A-Za-z0-9]{5,10}$/' ],
    'bulgaria' => ['/^[A-Za-z0-9]{7,8}$/'],
    'germany' => [ '/^[A-Za-z]{1,3}$/', '/^[a-zA-Z]{1,2}$/', '/^[0-9]{1,4}$/' ],
    'romania' => [ '/^[A-Za-z]{1,2}$/', '/^[0-9]{1,2}$/', '/^[A-Za-z]{1,3}$/' ],
    'croatia' => [ '/^[A-Za-z0-9-]{7,9}$/'],
    'cyprus' => [ '/^[A-Z]{3}$/', '/^[0-9]{3}$/' ],
    'czechrepublic' => [ '/^[A-Za-z0-9]{3}$/', '/^[A-Za-z0-9]{4}$/' ],
    'denmark' => [ '/^[A-Za-z]{2}$/', '/^[0-9]{2}$/', '/^[0-9]{3}$/' ],
    'estonia' => [ '/^[A-Za-z0-9]{4,10}$/' ],
    'finland' => [ '/^[A-Za-z]{1,3}$/', '/^[0-9]{1,3}/' ],
    'france' => [ '/^[A-Za-z0-9]{2,3}$/', '/^[A-Za-z0-9]{3}$/', '/^[A-Za-z0-9]{2}$/' ],
    'greece' => [ '/^[A-Za-z]{3}$/', '/^[0-9]{4}$/' ],
    'hungary' => [ '/^[A-Za-z0-9]{2,4}$/', '/^[A-Za-z0-9]{2,4}$/' ],
    'ireland' => [ '/^[0-9]{2,3}$/', '/^[A-Za-z0-9]{1,2}$/', '/^[0-9]{1,6}$/'],
    'italy' => [ '/^[A-Za-z]{1,2}$/', '/^[0-9]{3}$/', '/^[A-Za-z]{1,2}$/' ],
    'lithuania' => [ '/^[A-Za-z]{3}$/', '/^[0-9]{3}$/' ],
    'luxembourg' => [ '/^[A-Za-z0-9]{4,10}$/' ],
    'malta' => [ '/^[A-Za-z]{2}[A-Za-z0-9]{1}$/', '/^[0-9]{3}$/' ],
    'netherlands' => [ '/^[A-Za-z0-9]{1,3}$/', '/^[A-Za-z0-9]{1,3}$/', '/^[A-Za-z0-9]{1,3}$/' ],
    'poland' => [ '/^[A-Za-z0-9]{1,3}$/', '/^[A-Za-z0-9]{2,6}$/'],
    'portugal' => [ '/^[A-Za-z0-9]{6}$/' ],
    'slovakia' => ['/^[A-Za-z]{2}$/', '/^[A-Za-z0-9]{5}$/' ], 
    'slovenia' => [ '/^[A-Za-z]{2}$/', '/^[A-Za-z0-9]{1,3}$/', '/^[A-Za-z0-9]{1,3}$/' ],
    'spain' => [ '/^[0-9A-Za-z]{5,10}$/' ],
    'sweden' => [ '/^[A-Za-z]{3}$/', '/^[0-9]{2}[A-Za-z0-9]{1}$/'],
    'switzerland' => [ '/^[A-Za-z]{2}$/', '/^[0-9]{1,6}$/' ],
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
    $disabled_text = $is_start ? '' : 'readonly';
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
        
    //$disabled = in_array( $input, array(6, 9) ) ? 'disabled' : '';//for default country, Austria
    $disabled = $maxlengths[$attr_index] == '0' ? 'disabled' : '';//change

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
      'belgium' => esc_html__( 'Belgium', 'vehicle-form' ),
      'bulgaria' => esc_html__('Bulgaria', 'vehicle-form'),
      'croatia' => esc_html__( 'Croatia', 'vehicle-form' ),
      'germany' => esc_html__('Germany', 'vehicle-form'),
      'cyprus' => esc_html__( 'Cyprus', 'vehicle-form' ),
      'czechrepublic' => esc_html__( 'Czech Republic', 'vehicle-form' ),
      'denmark' => esc_html__( 'Denmark', 'vehicle-form' ),
      'estonia' => esc_html__( 'Estonia', 'vehicle-form' ),
      'finland' => esc_html__( 'Finland', 'vehicle-form' ),
      'france' => esc_html__( 'France', 'vehicle-form' ),
      'greece' => esc_html__( 'Greece', 'vehicle-form' ),
      'hungary' => esc_html__( 'Hungary', 'vehicle-form' ),
      'ireland' => esc_html__( 'Ireland', 'vehicle-form' ),
      'italy' => esc_html__( 'Italy', 'vehicle-form' ),
      'lithuania' => esc_html__( 'Lithuania', 'vehicle-form' ),
      'luxembourg' => esc_html__( 'Luxembourg', 'vehicle-form' ),
      'malta' => esc_html__( 'Malta', 'vehicle-form' ),
      'netherlands' => esc_html__( 'Netherlands', 'vehicle-form' ),
      'poland' => esc_html__( 'Poland', 'vehicle-form' ),
      'portugal' => esc_html__( 'Portugal', 'vehicle-form' ),
      'romania' => esc_html__('Romania', 'vehicle-form'),
      'slovakia' => esc_html__('Slovakia', 'vehicle-form' ),
      'slovenia' => esc_html__( 'Slovenia', 'vehicle-form' ),
      'spain' => esc_html__( 'Spain', 'vehicle-form' ),
      'sweden' => esc_html__( 'Sweden', 'vehicle-form' ),
      'switzerland' => esc_html__( 'Switzerland', 'vehicle-form' ),
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

  public function validate_date( $date, $is_start = true ){ 

    if ( $is_start ){
      $error_message = __( 'Invalid start date.', 'vehicle-form' );
    } else {
      $error_message = __( 'Invalid end date.', 'vehicle-form' );
    }
    
    $error = array(
      'is_valid' => false,
      'message' => $error_message
    );

    if ( ! is_string( $date ) || empty( $date ) ){
      return $error;
    }
  
    $parts = explode('/', $date );
    if ( count( $parts ) != 3 ){
      return $error;
    }
    
    $checked = checkdate( (int) $parts[0], (int) $parts[1], (int) $parts[2] );
    if (false == $checked ){
      return $error;
    }
  
    $recombined = implode( '-', $parts );
  
    $test = DateTime::createFromFormat( 'm-d-Y', $recombined );
    if ( !$test ){
      return $error;
    }

  
    $date_string = $test->format( 'm-d-Y' );
    if ( $date_string !== $recombined ){
      return $error;
    }
    
    return array(
      'is_valid' => true,
      'message' => ''
    );
  }

  public function validate( $value, $form ){
    $id = $this->id;
    $countries = array_keys( $this->get_choices() );
    
    $start_date = $this->validate_date( $value[$id.'.1'], true );
    //$start_date = $this->validate_date( 'xinso', true );
    if ( !$start_date['is_valid'] ){
      $this->failed_validation = true;
      $this->validation_message = $start_date['message'];
    }

    $end_date = $this->validate_date( $value[$id.'.2'], false );
    if ( !$end_date['is_valid'] ){
      $this->failed_validation = true;
      $this->validation_message = $end_date['message'];
    }
    
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
        $message = empty( $value[ $submitted_license_plate_portions[$i] ] ) ? 
          __( 'Every part of the license plate must have a value.', 'vehicle-form' ) :
          sprintf( __( '%1$s is not a valid value for that part of the license plate', 'vehicle-form' ), esc_html( $value[ $submitted_license_plate_portions[$i] ] ) );
        $this->validation_message = $message;
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

  public function get_value_entry_detail( $value, $currency = '', $use_text = false, $format = 'html', $media = 'screen'){
    //$start_date_te
    $id = $this->id;
    $start_date = esc_html( $value[$id.'.1'] );
    $end_date = esc_html( $value[$id.'.2'] );
    $country = esc_html( $value[$id.'.3']);

    switch ( $country ){
      case 'finland':
      case 'greece':
        $license_plate = esc_html( $value[$id.'.4'] . '-' . $value[$id.'.5']); 
        $license_plate_conf = esc_html( $value[$id.'.7'] . '-' . $value[$id.'.8'] );
        break;
      case 'france': 
      case 'netherlands':
        $license_plate = esc_html( implode( '-', [ $value[$id.'.4'], $value[$id.'.5'], $value[$id.'.6'] ] ) );
        $license_plate_conf = esc_html( implode( '-', [ $value[$id.'.7'], $value[$id.'.8'], $value[$id.'.9'] ] ) );
        break;
      default: 
        $license_plate = esc_html( $value[$id.'.4'] . $value[$id.'.5'] . $value[$id.'.6'] );
        $license_plate_conf = esc_html( $value[$id.'.7'] . $value[$id.'.8'] . $value[$id.'.9'] );
    }

    $html = "
      <table>
        <thead>
          <tr>
            <th>Input</th>
            <th>Response</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Valid From</td>
            <td>$start_date</td>
          </tr>
          <tr>
            <td>Valid Until</td>
            <td>$end_date</td>
          </tr>
          <tr>
            <td>Country</td>
            <td>$country</td>
          </tr>
          <tr>
            <td>License Plate</td>
            <td>$license_plate</td>
          </tr>
          <tr>
            <td>License Plate Confirmation</td>
            <td>$license_plate_conf</td>
          </tr>
        </tbody>
      </table>
    ";
    return $html;
  }
}

GF_Fields::register( new GF_Field_License_Plate() );