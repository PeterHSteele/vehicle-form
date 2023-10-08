jQuery(document).ready( function(){
  
  class VehicleInput{
    constructor(){
      this.field = document.querySelector( '.gfield--type-vehicle' );
      if ( !this.field ){ return; }
      this.buttons = this.field.querySelectorAll( '.vehicle-field-button' );
      this.input = this.field.querySelector( 'input' ); 
      this.addListeners();
    }

    handleClick(e){
      if ( e.target.classList.contains( 'vehicle-field-button' )){
        e.preventDefault();
        this.buttons.forEach( button => button.classList.remove( 'selected' ))
        const clicked = e.target;
        clicked.classList.add( 'selected' );
        if ( clicked.classList.contains( 'btn-motorcycle' ) ){
          this.input.setAttribute( 'value', 'motorcycle' )
        } else {
          this.input.setAttribute( 'value', 'automobile' )
        }
      }
    }

    addListeners(){
      this.field.addEventListener( 'click', this.handleClick.bind( this ));
    }
  }

  const vehicleInput = new VehicleInput();

})