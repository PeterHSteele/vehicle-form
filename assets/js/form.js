jQuery(document).ready( function(){

  function oneDay ( date ) { 
    date.setDate( date.getDate() + 1 )
    return date;
  }

  function tenDays( date ){
    date.setDate( date.getDate() + 10 )
    return date;
  }

  function twoMonths( date ){
    date.setMonth( date.getMonth() + 2 )
    return date;
  }

  var mapPlansToTimes = {};
  mapPlansToTimes["One Day"] = oneDay
  mapPlansToTimes["Ten Days"] = tenDays
  mapPlansToTimes["Two Months"] = twoMonths
  
  class VehicleForm{

    constructor(){
      this.form = document.querySelector( '.vehicle-rental-form' );
      if ( !this.form ) return;
      this.dateRange = this.form.querySelector( '.vehicle-date-range' );
      this.dateInput = jQuery('.start-date input[type=text]');
      this.addListeners();
      this.addFilters();
      this.planInput = this.form.querySelector( '.vehicle-plan input' )
    }

    handleStartDateChange( selected ){
      if ( !selected ) return;
      
      const plan = this.planInput.getAttribute( 'value' )

      if ( !plan || Object.keys( mapPlansToTimes ).indexOf(plan) < 0 ) return; 

      const startDate = new Date( selected ),
      month = startDate.getMonth() + 1,
      day = startDate.getDate(),
      year = startDate.getFullYear(),
      time = startDate.getTime(),
      startDateText = [ month, day, year ].join( '.' );

      const endDate = mapPlansToTimes[plan]( new Date( startDate )),
      endMonth = endDate.getMonth() + 1,
      endDay = endDate.getDate(),
      endYear = endDate.getFullYear(),
      endDateText = [ endMonth, endDay, endYear ].join( '.' );

      const rangeDiv = document.createElement( 'div' );
      const text = document.createTextNode( 'Valid from ' + startDateText + ' - ' + endDateText );
      rangeDiv.append(text)
      Array.from( this.dateRange.children ).forEach( child => child.remove() );
      this.dateRange.append( rangeDiv );
    }

    addFilters(){
      gform.addFilter( 'gform_datepicker_options_pre_init', ( options ) => {
        options.onSelect = this.handleStartDateChange.bind(this);
        return options
      })
    }
  
    addListeners(){
      
    }
  }

  const form = new VehicleForm();

})