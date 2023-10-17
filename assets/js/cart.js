document.addEventListener( 'DOMContentLoaded', function(){
  
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
    constructor(){
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
      const plan = vfplans.find( plan => event.detail.duration == plan.duration );
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

  const cart = new VehicleFormCart()

});