///////////////////////////////////////////////////////////
// "Live Clock Advanced" script - Version 1.0
// By Mark Plachetta (astroboy@zip.com.au)
//
// Get the latest version at:
// http://www.zip.com.au/~astroboy/liveclock/
//
// Based on the original script: "Upper Corner Live Clock"
// available at:
// - Dynamic Drive (http://www.dynamicdrive.com)
// - Website Abstraction (http://www.wsabstract.com)
// ========================================================
// CHANGES TO ORIGINAL SCRIPT:
// - Gave more flexibility in positioning of clock
// - Added date construct (Advanced version only)
// - User configurable
// ========================================================
// Both "Advanced" and "Lite" versions are available free
// of charge, see the website for more information on the
// two scripts.
///////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////
/////////////// CONFIGURATION /////////////////////////////
	// Set the clock's font face:
	var myfont_face = "Times";

	// Set the clock's font size (in point):
	var myfont_size = "12";

	// Set the clock's font color:
	var myfont_color = "#000000";
	
	// Set the clock's background color:
	var myback_color = "";

	// Set the text to display before the clock:
	var mypre_text = "";

	// Set the width of the clock (in pixels):
	var mywidth = 170;

	// Display the time in 24 or 12 hour time?
	// 0 = 24, 1 = 12
	var my12_hour = 0;

	// How often do you want the clock updated?
	// 0 = Never, 1 = Every Second, 2 = Every Minute
	// If you pick 0 or 2, the seconds will not be displayed
	var myupdate = 1;

	// Display the date?
	// 0 = No, 1 = Yes
	var DisplayDate = 1;

/////////////// END CONFIGURATION /////////////////////////
///////////////////////////////////////////////////////////

// Browser detect code
        var ie4=document.all
        var ns4=document.layers
        var ns6=document.getElementById&&!document.all

// Global varibale definitions:

	var dn = "";
	var mn = "th";
	var old = "";

// The following arrays contain data which is used in the clock's
// date function. Feel free to change values for Days and Months
// if needed (if you wanted abbreviated names for example).
	var DaysOfWeek = new Array(7);
		DaysOfWeek[0] = "Niedziela";
		DaysOfWeek[1] = "Poniedziałek";
		DaysOfWeek[2] = "Wtorek";
		DaysOfWeek[3] = "Środa";
		DaysOfWeek[4] = "Czwartek";
		DaysOfWeek[5] = "Piątek";
		DaysOfWeek[6] = "Sobota";

	var MonthsOfYear = new Array(12);
		MonthsOfYear[0] = "Styczeń";
		MonthsOfYear[1] = "Luty";
		MonthsOfYear[2] = "Marzec";
		MonthsOfYear[3] = "Kwiecień";
		MonthsOfYear[4] = "Maj";
		MonthsOfYear[5] = "Czerwiec";
		MonthsOfYear[6] = "Lipiec";
		MonthsOfYear[7] = "Sierpień";
		MonthsOfYear[8] = "Wrzesień";
		MonthsOfYear[9] = "Październik";
		MonthsOfYear[10] = "Listopad";
		MonthsOfYear[11] = "Grudzień";

// This array controls how often the clock is updated,
// based on your selection in the configuration.
	var ClockUpdate = new Array(3);
		ClockUpdate[0] = 0;
		ClockUpdate[1] = 1000;
		ClockUpdate[2] = 60000;

// For Version 4+ browsers, write the appropriate HTML to the
// page for the clock, otherwise, attempt to write a static
// date to the page.
	if (ie4||ns6) { document.write('<span id="LiveClockIE" style="width:'+mywidth+'px; background-color:'+myback_color+'"></span>'); }
	else if (document.layers) { document.write('<ilayer bgColor="'+myback_color+'" id="ClockPosNS" visibility="hide"><layer width="'+mywidth+'" id="LiveClockNS"></layer></ilayer>'); }
	else { old = "true"; show_clock(); }

// The main part of the script:
	function show_clock() {
		if (old == "die") { return; }
	
	//show clock in NS 4
		if (ns4)
                document.ClockPosNS.visibility="show"
	// Get all our date variables:
		var Digital = new Date();
		var day = Digital.getDay();
		var mday = Digital.getDate();
		var month = Digital.getMonth();
		var year = Digital.getYear();

		var hours = Digital.getHours();
		var minutes = Digital.getMinutes();
		var seconds = Digital.getSeconds();


	// Corrections if Y2K display problem

		if(year < 1900) {year += 1900}; 


	// Fix the "mn" variable if needed:
		if (mday == 1) { mn = "0"; }
		else if (mday == 2) { mn = "0"; }
		else if (mday == 3) { mn = "0"; }
		else if (mday == 4) { mn = "0"; }
		else if (mday == 5) { mn = "0"; }
		else if (mday == 6) { mn = "0"; }
		else if (mday == 7) { mn = "0"; }
		else if (mday == 8) { mn = "0"; }
		else if (mday == 9) { mn = "0"; }
		else if (mday >= 10) { mn = ""; }

	// Set up the hours for either 24 or 12 hour display:
		if (my12_hour) {
			dn = "AM";
			if (hours > 12) { dn = "PM"; hours = hours - 12; }
			if (hours == 0) { hours = 12; }
		} else {
			dn = "";
		}
		if (minutes <= 9) { minutes = "0"+minutes; }
		if (seconds <= 9) { seconds = "0"+seconds; }

	// This is the actual HTML of the clock. If you're going to play around
	// with this, be careful to keep all your quotations in tact.
		myclock = '';
		myclock += '<font style="color:'+myfont_color+'; font-family:'+myfont_face+'; font-size:'+myfont_size+'pt;"> ';
		myclock += mypre_text;
		myclock += hours+':'+minutes;
		if ((myupdate < 2) || (myupdate == 0)) { myclock += ':'+seconds; }
		myclock += ' '+dn;
		if (DisplayDate) { myclock += ' ( '+DaysOfWeek[day]+' )<br /> '+mn+mday+' - '+MonthsOfYear[month]+' - '+year; }
		myclock += ' </font>';

		if (old == "true") {
			document.write(myclock);
			old = "die";
			return;
		}

	// Write the clock to the layer:
		if (ns4) {
			clockpos = document.ClockPosNS;
			liveclock = clockpos.document.LiveClockNS;
			liveclock.document.write(myclock);
			liveclock.document.close();
		} else if (ie4) {
			LiveClockIE.innerHTML = myclock;
		} else if (ns6){
			document.getElementById("LiveClockIE").innerHTML = myclock;
                }            

	if (myupdate != 0) { setTimeout("show_clock()",ClockUpdate[myupdate]); }
}
