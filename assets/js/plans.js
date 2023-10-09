jQuery(document).ready(function(){
  
  class PlansInput{
    plans = [
      { data: 'one-day', pretty: "One Day" },
      { data: 'ten-day', pretty: 'Ten Days' },
      { data: 'two-month', pretty: "Two Months" },
      { data: 'annual', pretty: "One Year" },
      { data: 'single', pretty: "Single Ticket" }
    ]
    selected = null;

    constructor(){
      this.field = document.querySelector( '.gfield--type-pricing-plan' );
      if ( !this.field ) return;
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
        this.input.value = this.plans.find( plan => plan.data == this.selected.dataset.duration ).pretty
      }
    }

    setInitialValue(){
      const value = this.input.getAttribute( 'value' ),
      allowed = this.plans.map( plan => plan.pretty );
      if ( !value || allowed.indexOf( value ) < 0) return;
      const selectedPlan = this.plans.find( plan => plan.pretty == value );
      const selected = Array.from( this.field.getElementsByClassName( 'plans-field-plan' )).find( el => el.dataset.duration == selectedPlan.data );
      this.selected = selected
      this.selected.classList.add( 'selected' );
    }

    addListeners(){
      this.field.addEventListener('click', this.handleClick.bind( this ) );
    }
  }

  const plans = new PlansInput();
});