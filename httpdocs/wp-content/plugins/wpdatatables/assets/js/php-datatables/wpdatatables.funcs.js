jQuery(document).ready(function($) {


	if( typeof wpdatatables_frontend_strings !== 'undefined' ){
		$.fn.DataTable.defaults.oLanguage.sInfo = wpdatatables_frontend_strings.sInfo;
		$.fn.DataTable.defaults.oLanguage.sSearch = wpdatatables_frontend_strings.sSearch;
		$.fn.DataTable.defaults.oLanguage.lengthMenu = wpdatatables_frontend_strings.lengthMenu;
		$.fn.DataTable.defaults.oLanguage.sEmptyTable = wpdatatables_frontend_strings.sEmptyTable;
		$.fn.DataTable.defaults.oLanguage.sInfoEmpty = wpdatatables_frontend_strings.sInfoEmpty;
		$.fn.DataTable.defaults.oLanguage.sInfoFiltered = wpdatatables_frontend_strings.sInfoFiltered;
		$.fn.DataTable.defaults.oLanguage.sInfoPostFix = wpdatatables_frontend_strings.sInfoPostFix;
		$.fn.DataTable.defaults.oLanguage.sInfoThousands = wpdatatables_frontend_strings.sInfoThousands;
		$.fn.DataTable.defaults.oLanguage.sLengthMenu = wpdatatables_frontend_strings.sLengthMenu;
		$.fn.DataTable.defaults.oLanguage.sProcessing = wpdatatables_frontend_strings.sProcessing;
		$.fn.DataTable.defaults.oLanguage.sZeroRecords = wpdatatables_frontend_strings.sZeroRecords;
		$.fn.DataTable.defaults.oLanguage.oPaginate = wpdatatables_frontend_strings.oPaginate;
		$.fn.DataTable.defaults.oLanguage.oAria = wpdatatables_frontend_strings.oAria;
	}


	/* Clear filters */
	$.fn.dataTableExt.oApi.fnFilterClear  = function ( oSettings )
	{
		/* Remove global filter */
		oSettings.oPreviousSearch.sSearch = "";

		/* Remove the text of the global filter in the input boxes */
		if ( typeof oSettings.aanFeatures.f != 'undefined' )
		{
			var n = oSettings.aanFeatures.f;
			for ( var i=0, iLen=n.length ; i<iLen ; i++ )
			{
				$('input', n[i]).val( '' );
			}
		}

		/* Remove the search text for the column filters - NOTE - if you have input boxes for these
		* filters, these will need to be reset
		*/
		for ( var i=0, iLen=oSettings.aoPreSearchCols.length ; i<iLen ; i++ )
		{
			oSettings.aoPreSearchCols[i].sSearch = "";
		}

		/* Redraw */
		oSettings.oApi._fnReDraw( oSettings );
	};	

	jQuery.extend( jQuery.fn.dataTableExt.oSort, {
		"formatted-num-pre": function ( a ) {
			if($(a).text()!=''){
				a = $(a).text();
			}
			a = (a==="-") ? -1 : a.replace( /[^\d\-\.]/g, "" );

			if(a!=-1){
				while(a.indexOf('.')!=-1){
					a = a.replace(".","");
				}

				a = a.replace(',','.');

			}

			return parseFloat( a );
		},

		"formatted-num-asc": function ( a, b ) {
			return a - b;
		},

		"formatted-num-desc": function ( a, b ) {
			return b - a;
		},

		"statuscol-pre": function ( a ) {

			a = $(a).find('div.percents').text();
			return parseFloat( a );
		},

		"statuscol-asc": function ( a, b ) {
			return a - b;
		},

		"statuscol-desc": function ( a, b ) {
			return b - a;
		},

		'datetime-pre': function( datetime ){
			var dateTimeArr = datetime.split(' ');
			return jQuery.fn.dataTableExt.oSort['date-pre'](dateTimeArr[0])+wdtPrepareTime( dateTimeArr[1] );
		},

		'datetime-asc':  function ( a, b ) {
			return a - b;
		},

		"datetime-desc": function ( a, b ) {
			return b - a;
		},

		'datetime-am-pre': function( datetime ){
			// First divide date and time
			var dateTimeArr = datetime.split(' ');
			return jQuery.fn.dataTableExt.oSort['date-pre'](dateTimeArr[0])+wdtPrepareAmTime(dateTimeArr[1]+' '+dateTimeArr[2]);;
		},

		'datetime-am-asc':  function ( a, b ) {
			return a - b;
		},

		"datetime-am-desc": function ( a, b ) {
			return b - a;
		},

		'datetime-eu-pre': function( datetime ){
			// First divide date and time
			var dateTimeArr = datetime.split(' ');
			return (wdtDateEuPre( dateTimeArr[0] )*100000)+wdtPrepareTime(dateTimeArr[1]);
		},

		'datetime-eu-asc':  function ( a, b ) {
			return a - b;
		},

		"datetime-eu-desc": function ( a, b ) {
			return b - a;
		},

		'datetime-eu-am-pre': function( datetime ){
			// First divide date and time
			var dateTimeArr = datetime.split(' ');
			return ( wdtDateEuPre( dateTimeArr[0] )*100000 )+wdtPrepareAmTime(dateTimeArr[1]+' '+dateTimeArr[2]);
		},

		'datetime-eu-am-asc':  function ( a, b ) {
			return a - b;
		},

		"datetime-eu-am-desc": function ( a, b ) {
			return b - a;
		},

		'datetime-dd-mmm-yyyy-pre': function( datetime ){
			// First divide date and time
			var dateTimeArr = datetime.split(' ');
			return wdtCustomDateDDMMMYYYYToOrd(dateTimeArr[0]+'-'+dateTimeArr[1]+'-'+dateTimeArr[2])+wdtPrepareTime(dateTimeArr[3]);
		},

		'datetime-dd-mmm-yyyy-asc':  function ( a, b ) {
			return a - b;
		},

		"datetime-dd-mmm-yyyy-desc": function ( a, b ) {
			return b - a;
		},

		'datetime-dd-mmm-yyyy-am-pre': function( datetime ){
			// First divide date and time
			var dateTimeArr = datetime.split(' ');
			return wdtCustomDateDDMMMYYYYToOrd(dateTimeArr[0]+'-'+dateTimeArr[1]+'-'+dateTimeArr[2])+wdtPrepareAmTime(dateTimeArr[3]+' '+dateTimeArr[4]);
		},

		'datetime-dd-mmm-yyyy-am-asc':  function ( a, b ) {
			return a - b;
		},

		"datetime-dd-mmm-yyyy-am-desc": function ( a, b ) {
			return b - a;
		},

		'time-pre': function( time ){
			return wdtPrepareTime( time )
		},

		'time-am-pre': function( time ){
			return wdtPrepareAmTime( time )
		},

	} );
	
	$.fn.dataTableExt.oApi.fnGetColumnIndex = function ( oSettings, sCol ) 
	{
		var cols = oSettings.aoColumns;
		for ( var x=0, xLen=cols.length ; x<xLen ; x++ )
		{
			if ( (typeof(cols[x].sTitle) == 'string') && ( cols[x].sTitle.toLowerCase() == sCol.toLowerCase() ) )
			{
				return x;
			};
		}
		return -1;
	};	

	// This will help DataTables magic detect the "dd-MMM-yyyy" format; Unshift so that it's the first data type (so it takes priority over existing)
	jQuery.fn.dataTableExt.aTypes.unshift(
	    function (sData) {
	        "use strict"; //let's avoid tom-foolery in this function
	        if (/^([0-2]?\d|3[0-1])-(jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec)-\d{4}/i.test(sData)) {
	            return 'date-dd-mmm-yyyy';
	        }
	        return null;
	    }
	);
	 
	// define the sorts
	jQuery.fn.dataTableExt.oSort['date-dd-mmm-yyyy-asc'] = function (a, b) {
	    "use strict"; //let's avoid tom-foolery in this function
	    var ordA = wdtCustomDateDDMMMYYYYToOrd(a),
	        ordB = wdtCustomDateDDMMMYYYYToOrd(b);
	    return (ordA < ordB) ? -1 : ((ordA > ordB) ? 1 : 0);
	};
	 
	jQuery.fn.dataTableExt.oSort['date-dd-mmm-yyyy-desc'] = function (a, b) {
	    "use strict"; //let's avoid tom-foolery in this function
	    var ordA = wdtCustomDateDDMMMYYYYToOrd(a),
	        ordB = wdtCustomDateDDMMMYYYYToOrd(b);
	    return (ordA < ordB) ? 1 : ((ordA > ordB) ? -1 : 0);
	};
	jQuery.extend( jQuery.fn.dataTableExt.oSort, {
	    "date-eu-pre": function ( date ) {
			return wdtDateEuPre( date );
	    },
	 
	    "date-eu-asc": function ( a, b ) {
	        return ((a < b) ? -1 : ((a > b) ? 1 : 0));
	    },
	 
	    "date-eu-desc": function ( a, b ) {
	        return ((a < b) ? 1 : ((a > b) ? -1 : 0));
	    }
	} );
	
});


var wdtCustomDateDDMMMYYYYToOrd = function (date) {
	"use strict"; //let's avoid tom-foolery in this function
	// Convert to a number YYYYMMDD which we can use to order
	var dateParts = date.split(/-/);
	return (dateParts[2] * 10000) + (jQuery.inArray(dateParts[1].toUpperCase(), ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"]) * 100) + dateParts[0];
};

function wdtDateEuPre( date ){
	var date = date.replace(" ", "");
	if (date.indexOf('.') > 0) {
		/*date a, format dd.mn.(yyyy) ; (year is optional)*/
		var eu_date = date.split('.');
	} else if (date.indexOf('-') > 0) {
		/*date a, format dd.mn.(yyyy) ; (year is optional)*/
		var eu_date = date.split('-');
	} else {
		/*date a, format dd/mn/(yyyy) ; (year is optional)*/
		var eu_date = date.split('/');
	}

	var year = 0;
	var month = 0;
	var day = 0;

	/*year (optional)*/
	if (eu_date[2]) {
		year = eu_date[2];
	}

	/*month*/
	if(eu_date[1]){
		month = eu_date[1];
		if( month.length < 2 ){ month = 0+month; }
	}

	/*day*/
	if(eu_date[0]) {
		day = eu_date[0];
		if( day.length < 2 ){ day = 0+day; }
	}

	return (year + month + day) * 1;
}

function wdtValidateURL(textval) {
  var regex = /^([a-z]([a-z]|\d|\+|-|\.)*):(\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?((\[(|(v[\da-f]{1,}\.(([a-z]|\d|-|\.|_|~)|[!\$&'\(\)\*\+,;=]|:)+))\])|((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=])*)(:\d*)?)(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*|(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)|((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)|((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)){0})(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?(||.*)?$/i;
  return regex.test(textval);
}

function wdtPrepareTime( time ){
	return moment.duration( time, 'HH:mm').asSeconds()
}

function wdtPrepareAmTime( time ){
	return moment.duration( moment( time, 'hh:mm A').format("HH:mm"), 'HH:mm' ).asSeconds()
}

function wdtValidateEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+(||.*)?$/;
  return regex.test(email);
}

function wdtRandString(n){
    if(!n){
        n = 5;
    }

    var text = '';
    var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    for(var i=0; i < n; i++){
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    }

    return text;
}

function wdtFormatNumber(n, c, d, t){
	c = isNaN(c = Math.abs(c)) ? 2 : c,
		d = d == undefined ? "." : d,
		t = t == undefined ? "," : t,
		s = n < 0 ? "-" : "",
		i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
		j = (j = i.length) > 3 ? j % 3 : 0;
	return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};

function wdtUnformatNumber( number, thousandsSeparator, decimalsSeparator, isFloat ){
	if( typeof isFloat == 'undefined' ) { isFloat = false; }

	var return_string = number.toString().replace( new RegExp( '\\'+thousandsSeparator, 'g'), '' );

	if( isFloat && decimalsSeparator == ',' ){
		return_string = return_string.replace( new RegExp( '\\'+decimalsSeparator ), '.' );
	}
	return return_string;
}
