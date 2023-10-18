jQuery(document).ready( function(){
  
  class VehicleInput{
    constructor(){
      this.field = document.querySelector( '.gfield--type-vehicle' );
      if ( !this.field ) return;
      this.buttons = Array.from( this.field.querySelectorAll( '.vehicle-field-button' ));
      this.input = this.field.querySelector( 'input' ); 
      this.addListeners();
      this.setInitialValue();
    }

    handleClick(e){
      const clicked = e.target.closest( '.vehicle-field-button' );
      if ( clicked ){
        e.preventDefault();
        this.buttons.forEach( button => button.classList.remove( 'selected' ))
        clicked.classList.add( 'selected' );
        if ( clicked.classList.contains( 'btn-motorcycle' ) ){
          this.input.setAttribute( 'value', 'motorcycle' );
        } else {
          this.input.setAttribute( 'value', 'automobile' );
        }
      }
    }

    setInitialValue(){
      const value = this.input.getAttribute( 'value' ),
      allowed = [ 'automobile, motorcycle' ];
      if ( !value || !allowed.indexOf( value )) return;
      const selected = this.buttons.find( button => button.classList.contains( `btn-${value}` ));
      selected.classList.add( 'selected' );
    }

    addListeners(){
      this.field.addEventListener( 'click', this.handleClick.bind( this ));
    }
  }

  const vehicleInput = new VehicleInput();

  class PlansInput{
    
    selected = null;

    constructor( plans ){
      this.form = document.querySelector( '.vehicle-rental-form' );
      if ( !this.form ) return;
      this.plans = plans;
      this.field = this.form.querySelector( '.gfield--type-pricing-plan' );
      if ( !this.field || !this.plans ) return;
      this.input = this.field.querySelector( 'input' );
      this.productFields = this.form.querySelectorAll( '.gfield--input-type-hiddenproduct' );
      this.addListeners();
      this.setInitialValue()
    }

    handleClick( event ){
      if ( 'BUTTON' == event.target.tagName ){  
        if ( this.selected ){
          this.selected.classList.remove('selected');
        }
        const clicked = event.target;
        this.selected = clicked.closest( '.plans-field-plan' )
        this.selected.classList.add( 'selected' );
        const duration = this.plans.find( plan => plan.duration == this.selected.dataset.duration ).duration
        this.updateProductFields( duration );
        this.input.value = duration;
        this.selected.dispatchEvent( 
          new CustomEvent(
            'choosePlan',
            {
              bubbles: true,
              detail: { duration }
            }
        ));
      }
    }

    updateProductFields( chosen ){
      this.productFields.forEach( field => {
        for ( let i=0; i < field.children.length; i++ ){
          let current = field.children.item(i);
          let id = current.getAttribute('id');
          if ( new RegExp('ginput_quantity_').test( id ) ){
            if ( field.classList.contains( chosen )){
              current.setAttribute( 'value', "1" );
            } else {
              current.setAttribute( 'value', "0")
            }
          }
        }
      })
    }

    setInitialValue(){
      const value = this.input.getAttribute( 'value' ),
      allowed = this.plans.map( plan => plan.duration );
      if ( !value || allowed.indexOf( value ) < 0){
        this.updateProductFields( null )
        return;
      } 
      const selectedPlan = this.plans.find( plan => plan.duration == value );
      this.updateProductFields( value );
      const selected = Array.from( this.field.getElementsByClassName( 'plans-field-plan' )).find( el => el.dataset.duration == selectedPlan.duration );
      this.selected = selected
      this.selected.classList.add( 'selected' );
    }

    handleEmptyCart(){
      if ( this.selected ){
        this.selected.classList.remove( 'selected' )
        this.selected=null;
      }
      this.updateProductFields( null );
      this.input.value = '';
    }

    addListeners(){
      this.field.addEventListener('click', this.handleClick.bind( this ) );
      this.form.addEventListener( 'emptyCart', this.handleEmptyCart.bind( this ));
    }
  }

  const plans = new PlansInput( Object.keys( vfplans ).map( key => vfplans[key] ));

  function tenDays( date ){
    date.setDate( date.getDate() + 10 );
    return date;
  }

  function twoMonths( date ){
    date.setMonth( date.getMonth() + 2 );
    return date;
  }

  function oneYear( date ){
    date.setYear( date.getFullYear() + 1);
    return date;
  }
  
  class VehicleForm{

    mapPlansToTimes = {};

    constructor(){
      this.form = document.querySelector( '.vehicle-rental-form' );
      if ( !this.form ) return;
      this.dateInput = jQuery('.start-date input[type=text]');
      this.endDateInput = Array.from( this.form.querySelector('.end-date').children ).find( child => child.tagName == 'INPUT' );
      this.addListeners();
      this.setMapPlansToTimes();
      this.initDatePicker()
      jQuery('#ui-datepicker-div').addClass( 'gravity-theme' );
      this.planInput = this.form.querySelector( '.vehicle-plan input' )
    }

    setMapPlansToTimes(){
      this.mapPlansToTimes["ten-day"] = tenDays
      this.mapPlansToTimes["two-month"] = twoMonths
      this.mapPlansToTimes["single"] = oneYear
      this.mapPlansToTimes['annual'] = () => null
    }

    handleStartDateChange( selected ){
      if ( !selected ) return;
      
      const plan = this.planInput.getAttribute( 'value' )
      if ( !plan || Object.keys( this.mapPlansToTimes ).indexOf(plan) < 0 ) return; 

      const startDate = new Date( selected );
  
      const endDate = this.mapPlansToTimes[plan]( new Date( startDate )),
      endMonth = ('0' + ( endDate.getMonth() + 1 ) ).slice(-2), //add leading "0" to single digit months, i.e. February becomes 02 instead of 2
      endDay = ('0' + endDate.getDate()).slice(-2), //add leading "0" to single digit days, i.e. the 5th becomes 05 instead of 5
      endYear = endDate.getFullYear(),
      endDateText = [ endMonth, endDay, endYear ].join( '/' );
      this.endDateInput.setAttribute( 'value', endDateText )
      
    }

    handleChoosePlan( event ){
      let duration = event?.detail?.duration;

      //make sure datepicker is unlocked and input is writeable
      this.dateInput.attr( 'readonly', false );
      this.initDatePicker();
      
      this.dateInput.datepicker('setDate', duration == 'annual' ? '12/01/2023' : null);
      this.endDateInput.setAttribute('value','');
      
      if ( 'annual' != event?.detail?.duration ) return;

      this.endDateInput.setAttribute( 'value', '01/31/2025' );

      //lock datepicker
      this.dateInput.attr( 'readonly', true );
      this.dateInput.datepicker( "destroy" );
    }

    handleEmptyCart( event ){
      this.handleChoosePlan( event );
    }

    initDatePicker(){
      const now = new Date();
      this.dateInput.datepicker({
        onSelect: this.handleStartDateChange.bind(this),
        yearRange: '-100:+20',
        showOn: 'focus',
        dateFormat: 'mm/dd/yy',
        changeMonth: true,
        changeYear: true,
        suppressDatePicker: false,
        showOtherMonths: true,
        selectOtherMonths: false,
        minDate: now,
      });
    }

    addListeners(){
      this.form.addEventListener( 'choosePlan', this.handleChoosePlan.bind( this ));
      this.form.addEventListener( 'emptyCart', this.handleEmptyCart.bind( this ))
    }
  }

  const form = new VehicleForm();

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
      element.value = '';
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

  class CartPlan {
    node = {
      tagName: 'div',
      children: [
        {
          tagName: 'div',
          classes: [ 'plans-field-plan' ],
          children: [
            {
              tagName: 'div',
              classes: [ 'price-container' ],
              children: [
                {
                  tagName: 'p',
                  text: () => this.price + '€'
                }
              ]
            },{
              tagName: 'div',
              classes: [ 'info-container' ],
              children: [
                { tagName: 'h2', text: () => this.name },
                { tagName: 'p', text: () => this.info }
              ]
            },{
              tagName: 'button',
              classes: [ 'remove-from-cart' ],
              children: [ 
                { tagName: 'span', classes: [ 'screen-reader-text' ], text: () => 'remove from cart' }, 
                { 
                  tagName: 'img', 
                  attrs: [
                    [ 'src', img.src ],
                    [ 'alt', ''],
                    [ 'role', 'presentation' ]
                  ]
                }
              ],
              attrs: [['type','button']]
            }
          ]
        }
      ]
    };

    constructor( props ){
      for ( let prop in props ){
        this[prop] = props[prop];
      }
    }

    renderNode( node ){
      const el = document.createElement( node.tagName );
      if ( node.children ){
        const childNodes = [];
        node.children.forEach( child => childNodes.push( this.renderNode( child )));
        childNodes.forEach( child => el.append( child ));
      }
      if ( node.id ){
        el.setAttribute( 'id', node.id );
      }
      if ( node.classes ){
        node.classes.forEach( className => el.classList.add( className ));
      }
      if ( node.text ){
        const text = document.createTextNode( node.text() );
        el.append(text);
      } 
      if ( node.attrs ){
        node.attrs.forEach( attr => {
          el.setAttribute( attr[0], attr[1] );
        });
      }
      return el;
    }

    render(){
      return this.renderNode( this.node )
    }
  }

  class VehicleFormCart {
    constructor( plans ){
      this.plans = plans;
      this.form = document.querySelector( '.vehicle-rental-form' );
      if ( !this.form ) return;
      this.field = this.form.querySelector( '.gfield--type-cart_and_terms' );
      if ( !this.field ) return;
      this.cart = this.field.querySelector('.vf-cart');
      this.chosenPlan = this.cart.querySelector( '.chosen-plan' );
      this.addListeners();
    }

    addListeners(){
      this.form.addEventListener( 'choosePlan', this.handlePlanChoice.bind( this ));
      this.cart.addEventListener( 'click', this.handleClick.bind( this ))
    }

    handlePlanChoice( event ){
      const plan = this.plans.find( plan => event.detail.duration == plan.duration );
      if ( !plan ) return;
      plan.price = Number(plan.price).toFixed(2);
      this.chosenPlan.children.item(0)?.remove();
      this.chosenPlan.append( new CartPlan( plan ).render() )
    }

    handleClick( event ){
      if ( event.target.closest( '.remove-from-cart' )){
        this.chosenPlan.dispatchEvent(
          new CustomEvent(
            'emptyCart',
            { bubbles: true }
          )
        )
        this.chosenPlan.children.item(0).remove();
      }
    }
  }

  const cart = new VehicleFormCart( Object.keys( vfplans ).map( key => vfplans[key] ))

})