jQuery(document).ready( function(){

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
      console.log(this.dateInput);
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
      console.log( `setting datepicker to ${duration == 'annual' ? '12/01/2023' : null}`);
      this.dateInput.datepicker('setDate', duration == 'annual' ? '12/01/2023' : null);
      this.endDateInput.setAttribute('value','');
      
      if ( 'annual' != event?.detail?.duration ) return;

      this.endDateInput.setAttribute( 'value', '01/31/2025' );

      //lock datepicker
      console.log('suppressing');
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

})