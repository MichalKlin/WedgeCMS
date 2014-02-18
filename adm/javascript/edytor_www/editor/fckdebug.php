<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<!--
 * FCKeditor - The text editor for Internet - http://www.fckeditor.net
 * Copyright (C) 2003-2008 Frederico Caldeira Knabben
 *
 * == BEGIN LICENSE ==
 *
 * Licensed under the terms of any of the following licenses at your
 * choice:
 *
 *  - GNU General Public License Version 2 or later (the "GPL")
 *    http://www.gnu.org/licenses/gpl.html
 *
 *  - GNU Lesser General Public License Version 2.1 or later (the "LGPL")
 *    http://www.gnu.org/licenses/lgpl.html
 *
 *  - Mozilla Public License Version 1.1 or later (the "MPL")
 *    http://www.mozilla.org/MPL/MPL-1.1.html
 *
 * == END LICENSE ==
 *
 * This is the Debug window.
 * It automatically popups if the Debug = true in the configuration file.
-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>FCKeditor Debug Window</title>
	<meta name="robots" content="noindex, nofollow" />
	<script type="text/javascript">

var oWindow ;
var oDiv ;

if ( !window.FCKMessages )
	window.FCKMessages = new Array() ;

window.onload = function()
{
	oWindow = document.getElementById('xOutput').contentWindow ;
	oWindow.document.open() ;
	oWindow.document.write( '<div id="divMsg"><\/div>' ) ;
	oWindow.document.close() ;
	oDiv	= oWindow.document.getElementById('divMsg') ;
}

function Output( message, color, noParse )
{
	if ( !noParse && message != null && isNaN( message ) )
		message = message.replace(/</g, "&lt;") ;

	if ( color )
		message = '<font color="' + color + '">' + message + '<\/font>' ;

	window.FCKMessages[ window.FCKMessages.length ] = message ;
	StartTimer() ;
}

function OutputObject( anyObject, color )
{
	var message ;

	if ( anyObject != null )
	{
		message = 'Properties of: ' + anyObject + '</b><blockquote>' ;

		for (var prop in anyObject)
		{
			try
			{
				var sVal = anyObject[ prop ] != null ? anyObject[ prop ] + '' : '[null]' ;
				message += '<b>' + prop + '</b> : ' + sVal.replace(/</g, '&lt;') + '<br>' ;
			}
			catch (e)
			{
				try
				{
					message += '<b>' + prop + '</b> : [' + typeof( anyObject[ prop ] ) + ']<br>' ;
				}
				catch (e)
				{
					message += '<b>' + prop + '</b> : [-error-]<br>' ;
				}
			}
		}

		message += '</blockquote><b>' ;
	} else
		message = 'OutputObject : Object is "null".' ;

	Output( message, color, true ) ;
}

function StartTimer()
{
	window.setTimeout( 'CheckMessages()', 100 ) ;
}

function CheckMessages()
{
	if ( window.FCKMessages.length > 0 )
	{
		// Get the first item in the queue
		var sMessage = window.FCKMessages[0] ;

		// Removes the first item from the queue
		var oTempArray = new Array() ;
		for ( i = 1 ; i < window.FCKMessages.length ; i++ )
			oTempArray[ i - 1 ] = window.FCKMessages[ i ] ;
		window.FCKMessages = oTempArray ;

		var d = new Date() ;
		var sTime =
			( d.getHours() + 100 + '' ).substr( 1,2 ) + ':' +
			( d.getMinutes() + 100 + '' ).substr( 1,2 ) + ':' +
			( d.getSeconds() + 100 + '' ).substr( 1,2 ) + ':' +
			( d.getMilliseconds() + 1000 + '' ).substr( 1,3 ) ;

		var oMsgDiv = oWindow.document.createElement( 'div' ) ;
		oMsgDiv.innerHTML = sTime + ': <b>' + sMessage + '<\/b>' ;
		oDiv.appendChild( oMsgDiv ) ;
		oMsgDiv.scrollIntoView() ;
	}
}

function Clear()
{
	oDiv.innerHTML = '' ;
}
	</script>
</head>
<body style="margin: 10px">
	<table style="height: 100%" cellspacing="5" cellpadding="0" width="100%" border="0">
		<tr>
			<td>
				<table cellspacing="0" cellpadding="0" width="100%" border="0">
					<tr>
						<td style="font-weight: bold; font-size: 1.2em;">
							FCKeditor Debug Window</td>
						<td align="right">
							<input type="button" value="Clear" onclick="Clear();" /></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr style="height: 100%">
			<td style="border: #696969 1px solid">
				<iframe id="xOutput" width="100%" height="100%" scrolling="auto" src="javascript:void(0)"
					frameborder="0"></iframe>
			</td>
		</tr>
	</table>
</body>
</html>
