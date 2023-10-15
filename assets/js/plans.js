jQuery(document).ready(function(){

  class PlansInput{
    
    selected = null;

    constructor( plans ){
      this.plans = plans
      this.field = document.querySelector( '.gfield--type-pricing-plan' );
      if ( !this.field || !this.plans ) return;
      this.input = this.field.querySelector( 'input' );
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

    setInitialValue(){
      const value = this.input.getAttribute( 'value' ),
      allowed = this.plans.map( plan => plan.duration );
      if ( !value || allowed.indexOf( value ) < 0) return;
      const selectedPlan = this.plans.find( plan => plan.duration == value );
      const selected = Array.from( this.field.getElementsByClassName( 'plans-field-plan' )).find( el => el.dataset.duration == selectedPlan.duration );
      this.selected = selected
      this.selected.classList.add( 'selected' );
    }

    addListeners(){
      this.field.addEventListener('click', this.handleClick.bind( this ) );
    }
  }

  const plans = new PlansInput( vfplans );
});