jQuery(document).ready(function(){

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

  const plans = new PlansInput( vfplans );
});