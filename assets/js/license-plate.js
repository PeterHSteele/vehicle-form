jQuery(document).ready( function(){
  
  class LicensePlateInput {

    constructor( countries ){
      this.countries = countries;
      this.field = document.querySelector( '.gfield--type-license_plate' );
      
      if (!this.field) return;
      this.countrySelect = this.field.querySelector( '.country-select-container select' );
      this.plateImages = this.field.querySelectorAll( '.license-backdrop' );
      this.plateFields = this.field.querySelectorAll( '.ginput-license-inner' );
      //this.currentLayout = Array.from(this.plateFields[0].classList).find
      this.currentLayout = Array.from(this.plateFields[0].classList)
        .find( token => {
          const exp = new RegExp('layout-[a-z]{2}');
          return exp.test( token );
        })
        .slice(7)
      this.addListeners();
    }

    updatePlateFields( event ){
      const country = event.target.value;
      if ( !country ) return;
      this.plateFields.forEach( field  => this.updatePlateField( field, country ));
      this.plateImages.forEach( image => image.setAttribute( 'src', this.countries[country].url ) )
      this.currentLayout = this.countries[country].cssSuffix;
    }

    updatePlateField( field, country ){
      field.classList.remove( 'layout-' + this.currentLayout );
      field.classList.add( 'layout-' + this.countries[country].cssSuffix );
      field.querySelectorAll( 'input' ).forEach( input => this.updatePlateInput( input, country ))
    }

    updatePlateInput( element, country ){
      const inputId = element.getAttribute( 'id' ),
      inputNum = inputId[inputId.length-1],
      { placeholders, maxlengths } = this.countries[country];
      let placeholder = '', maxlength = '';
      switch ( inputNum ){
        case '4':
        case '7': 
          placeholder = placeholders[0];
          maxlength = maxlengths[0]
          break;
        case '5':
        case '8':
          placeholder = placeholders[1];
          maxlength = maxlengths[1];
          break;
        case '6':
        case '9':
          placeholder = placeholders[2];
          maxlength = maxlengths[2];
          break;
        default:
          placeholder ='';
      }
      element.setAttribute( 'value', '' );
      element.setAttribute( 'placeholder', placeholder );
      element.setAttribute( 'maxlength', maxlength );
      if ( element.hasAttribute( 'disabled' ) && maxlength !== "0" ){
        element.disabled = false;
      } else if ( !element.hasAttribute('disabled') && maxlength == "0" ){
        element.disabled = true;
      }
    }

    addListeners(){
      this.countrySelect.addEventListener( 'change', this.updatePlateFields.bind(this))
    }
  }

  const input = new LicensePlateInput( countries );
})