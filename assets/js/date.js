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
  
  class VehicleForm{

    mapPlansToTimes = {};

    constructor(){
      this.form = document.querySelector( '.vehicle-rental-form' );
      if ( !this.form ) return;
      this.dateInput = jQuery('.start-date input[type=text]');
      this.endDateInput = Array.from( this.form.querySelector('.end-date').children).find( child => child.tagName == 'INPUT' );
      //console.log( this.dateInput );
      this.addListeners();
      //this.addFilters();
      this.setMapPlansToTimes();
      this.initDatePicker()
      this.planInput = this.form.querySelector( '.vehicle-plan input' )
    }

    setMapPlansToTimes(){
      this.mapPlansToTimes["One Day"] = oneDay
      this.mapPlansToTimes["Ten Days"] = tenDays
      this.mapPlansToTimes["Two Months"] = twoMonths
    }

    handleStartDateChange( selected ){
      if ( !selected ) return;
      
      const plan = this.planInput.getAttribute( 'value' )
      if ( !plan || Object.keys( this.mapPlansToTimes ).indexOf(plan) < 0 ) return; 

      const startDate = new Date( selected );
      /*month = startDate.getMonth() + 1,
      day = startDate.getDate(),
      year = startDate.getFullYear(),
      time = startDate.getTime(),
      startDateText = [ month, day, year ].join( '.' );*/

      const endDate = this.mapPlansToTimes[plan]( new Date( startDate )),
      endMonth = endDate.getMonth() + 1,
      endDay = endDate.getDate(),
      endYear = endDate.getFullYear(),
      endDateText = [ endMonth, endDay, endYear ].join( '/' );
      this.endDateInput.setAttribute( 'value', endDateText)
      
    }

    addFilters(){
      /*gform.addFilter( 'gform_datepicker_options_pre_init', ( options ) => {
        options.onSelect = this.handleStartDateChange.bind(this);
        return options
      })*/
    }

    initDatePicker( ){
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
        beforeShow: function (input, inst) {
            jQuery('#ui-datepicker-div').addClass( 'gravity-theme' );
        }
      });
    }

    addListeners(){
      
    }
  }

  const form = new VehicleForm();

})