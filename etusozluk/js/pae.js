/**
 * SU AN OLANLAR
 * Pagination ver custometumade http://www.myphpetc.com/2009/10/easy-pagination-with-jquery-and-ajax.html
 * Maxlength ver 1.0.5
 * ScrollTo version 1.4.2
 *
 *
 *
 *
 *
 */
 

/**
 * jQuery Maxlength plugin
 * @version		$Id: jquery.maxlength.js 18 2009-05-16 15:37:08Z emil@anon-design.se $
 * @package		jQuery maxlength 1.0.5
 * @copyright	Copyright (C) 2009 Emil Stjerneman / http://www.anon-design.se
 * @license		GNU/GPL, see LICENSE.txt
 */

(function($) 
{

	$.fn.maxlength = function(options)
	{
		var settings = jQuery.extend(
		{
			events:				      [], // Array of events to be triggerd
			maxCharacters:		  100, // Characters limit
			status:				      true, // True to show status indicator bewlow the element
			statusClass:		    "status", // The class on the status div
			statusText:			    "Kalan karakter: ", // The status text
			notificationClass:	"notification",	// Will be added to the emement when maxlength is reached
			showAlert: 			    false, // True to show a regular alert message
			alertText:			    "You have typed too many characters.", // Text in the alert message
			slider:				      false // Use counter slider
		}, options );
		
		// Add the default event
		$.merge(settings.events, ['keyup']);

		return this.each(function() 
		{
			var item = $(this);
			var charactersLength = $(this).val().length;
			
      // Update the status text
			function updateStatus()
			{
				var charactersLeft = settings.maxCharacters - charactersLength;
				
				if(charactersLeft < 0) 
				{
					charactersLeft = 0;
				}

				item.next("div").html(settings.statusText + " " + charactersLeft);
			}

			function checkChars() 
			{
				var valid = true;
				
				// Too many chars?
				if(charactersLength >= settings.maxCharacters) 
				{
					// Too may chars, set the valid boolean to false
					valid = false;
					// Add the notifycation class when we have too many chars
					item.addClass(settings.notificationClass);
					// Cut down the string
					item.val(item.val().substr(0,settings.maxCharacters));
					// Show the alert dialog box, if its set to true
					showAlert();
				} 
				else 
				{
					// Remove the notification class
					if(item.hasClass(settings.notificationClass)) 
					{
						item.removeClass(settings.notificationClass);
					}
				}

				if(settings.status)
				{
					updateStatus();
				}
			}
						
			// Shows an alert msg
			function showAlert() 
			{
				if(settings.showAlert)
				{
					alert(settings.alertText);
				}
			}

			// Check if the element is valid.
			function validateElement() 
			{
				var ret = false;
				
				if(item.is('textarea')) {
					ret = true;
				} else if(item.filter("input[type=text]")) {
					ret = true;
				} else if(item.filter("input[type=password]")) {
					ret = true;
				}

				return ret;
			}

			// Validate
			if(!validateElement()) 
			{
				return false;
			}
			
			// Loop through the events and bind them to the element
			$.each(settings.events, function (i, n) {
				item.bind(n, function(e) {
					charactersLength = item.val().length;
					checkChars();
				});
			});

			// Insert the status div
			if(settings.status) 
			{
				item.after($("<div/>").addClass(settings.statusClass).html('-'));
				updateStatus();
			}

			// Remove the status div
			if(!settings.status) 
			{
				var removeThisDiv = item.next("div."+settings.statusClass);
				
				if(removeThisDiv) {
					removeThisDiv.remove();
				}

			}

			// Slide counter
			if(settings.slider) {
				item.next().hide();
				
				item.focus(function(){
					item.next().slideDown('fast');
				});

				item.blur(function(){
					item.next().slideUp('fast');
				}); 
			}

		});
	};
})(jQuery);

/* Maxlength Bitti */

/* Pager */
	
function generateRows(selected, opt) {
	var op;
	
	if (opt == "dun")
		op = "dun";
	else if(isFinite(opt) && $.isNumeric(opt) && opt.length == 8)
		 op = opt;
	else 
		op = "bugun";
	var pages = $("#page_count").val();
	
	if (pages <= 5) {
		var pagers="<div id='paginator'>";
		for (i=1; i<=pages;i++) {
		if (i == selected) {
			pagers += "<a href='#' class='pagor selected'>" + i + "</a>";
			} else {
			pagers += "<a href='#' class='pagor'>" + i + "</a>";
			}
		}
		
		$("#paginator").remove();
		$("#basliklar").after(pagers);
		$(".pagor").click(function() {
			var index = $(".pagor").index(this);
			index=index+1;
			gungetir(op,index);
			$(".pagor").removeClass("selected");
			$(this).addClass("selected");
		});		
	} else {
		if (selected < 5) {
			// Draw the first 5 then have ... link to last
			var pagers = "<div id='paginator'>";
			for (i = 1; i <= 5; i++) {
				if (i == selected) {
					pagers += "<a href='#' class='pagor selected'>" + i + "</a>";
				} else {
					pagers += "<a href='#' class='pagor'>" + i + "</a>";
				}				
			}
			pagers += "<div style='float:left;padding-left:6px;padding-right:6px;'>...</div><a href='#' class='pagor'>" + Number(pages) + "</a><div style='clear:both;'></div></div>";
			
			$("#paginator").remove();
			$("#basliklar").after(pagers);
			$(".pagor").click(function() {
				var index = $(".pagor").index(this);
				index=index+1;
				gungetir(op,index);
				$(".pagor").removeClass("selected");
				$(this).addClass("selected");
			});
		} else if (selected > (Number(pages) - 4)) {
			// Draw ... link to first then have the last 5
			var pagers = "<div id='paginator'><a href='#' class='pagor'>1</a><div style='float:left;padding-left:6px;padding-right:6px;'>...</div>";
			for (i = (Number(pages) - 4); i <= Number(pages); i++) {
				if (i == selected) {
					pagers += "<a href='#' class='pagor selected'>" + i + "</a>";
				} else {
					pagers += "<a href='#' class='pagor'>" + i + "</a>";
				}				
			}			
			pagers += "<div style='clear:both;'></div></div>";
			
			$("#paginator").remove();
			$("#basliklar").after(pagers);
			$(".pagor").click(function() {
				var index = $(".pagor").index(this);
				index=index+1;
				gungetir(op,index);
				$(".pagor").removeClass("selected");
				$(this).addClass("selected");
			});		
		} else {
			// Draw the number 1 element, then draw ... 2 before and two after and ... link to last
			var pagers = "<div id='paginator'><a href='#' class='pagor'>1</a><div style='float:left;padding-left:6px;padding-right:6px;'>...</div>";
			for (i = (Number(selected) - 2); i <= (Number(selected) + 2); i++) {
				if (i == selected) {
					pagers += "<a href='#' class='pagor selected'>" + i + "</a>";
				} else {
					pagers += "<a href='#' class='pagor'>" + i + "</a>";
				}
			}
			pagers += "<div style='float:left;padding-left:6px;padding-right:6px;'>...</div><a href='#' class='pagor'>" + pages + "</a><div style='clear:both;'></div></div>";
			
			$("#paginator").remove();
			$("#basliklar").after(pagers);
			$(".pagor").click(function() {
				var index = $(".pagor").index(this);
				index=index+1;
				gungetir(op,index);
				$(".pagor").removeClass("selected");
				$(this).addClass("selected");
			});			
		}
	}
}

/* Pager bitti */

/**
 * jQuery.ScrollTo
 * Copyright (c) 2007-2009 Ariel Flesler - aflesler(at)gmail(dot)com | http://flesler.blogspot.com
 * Dual licensed under MIT and GPL.
 * Date: 5/25/2009
 *
 * @projectDescription Easy element scrolling using jQuery.
 * http://flesler.blogspot.com/2007/10/jqueryscrollto.html
 * Works with jQuery +1.2.6. Tested on FF 2/3, IE 6/7/8, Opera 9.5/6, Safari 3, Chrome 1 on WinXP.
 *
 * @author Ariel Flesler
 * @version 1.4.2
 *
 * @id jQuery.scrollTo
 * @id jQuery.fn.scrollTo
 * @param {String, Number, DOMElement, jQuery, Object} target Where to scroll the matched elements.
 *	  The different options for target are:
 *		- A number position (will be applied to all axes).
 *		- A string position ('44', '100px', '+=90', etc ) will be applied to all axes
 *		- A jQuery/DOM element ( logically, child of the element to scroll )
 *		- A string selector, that will be relative to the element to scroll ( 'li:eq(2)', etc )
 *		- A hash { top:x, left:y }, x and y can be any kind of number/string like above.
*		- A percentage of the container's dimension/s, for example: 50% to go to the middle.
 *		- The string 'max' for go-to-end. 
 * @param {Number} duration The OVERALL length of the animation, this argument can be the settings object instead.
 * @param {Object,Function} settings Optional set of settings or the onAfter callback.
 *	 @option {String} axis Which axis must be scrolled, use 'x', 'y', 'xy' or 'yx'.
 *	 @option {Number} duration The OVERALL length of the animation.
 *	 @option {String} easing The easing method for the animation.
 *	 @option {Boolean} margin If true, the margin of the target element will be deducted from the final position.
 *	 @option {Object, Number} offset Add/deduct from the end position. One number for both axes or { top:x, left:y }.
 *	 @option {Object, Number} over Add/deduct the height/width multiplied by 'over', can be { top:x, left:y } when using both axes.
 *	 @option {Boolean} queue If true, and both axis are given, the 2nd axis will only be animated after the first one ends.
 *	 @option {Function} onAfter Function to be called after the scrolling ends. 
 *	 @option {Function} onAfterFirst If queuing is activated, this function will be called after the first scrolling ends.
 * @return {jQuery} Returns the same jQuery object, for chaining.
 *
 * @desc Scroll to a fixed position
 * @example $('div').scrollTo( 340 );
 *
 * @desc Scroll relatively to the actual position
 * @example $('div').scrollTo( '+=340px', { axis:'y' } );
 *
 * @dec Scroll using a selector (relative to the scrolled element)
 * @example $('div').scrollTo( 'p.paragraph:eq(2)', 500, { easing:'swing', queue:true, axis:'xy' } );
 *
 * @ Scroll to a DOM element (same for jQuery object)
 * @example var second_child = document.getElementById('container').firstChild.nextSibling;
 *			$('#container').scrollTo( second_child, { duration:500, axis:'x', onAfter:function(){
 *				alert('scrolled!!');																   
 *			}});
 *
 * @desc Scroll on both axes, to different values
 * @example $('div').scrollTo( { top: 300, left:'+=200' }, { axis:'xy', offset:-20 } );
 */
;(function( $ ){
	
	var $scrollTo = $.scrollTo = function( target, duration, settings ){
		$(window).scrollTo( target, duration, settings );
	};

	$scrollTo.defaults = {
		axis:'xy',
		duration: parseFloat($.fn.jquery) >= 1.3 ? 0 : 1
	};

	// Returns the element that needs to be animated to scroll the window.
	// Kept for backwards compatibility (specially for localScroll & serialScroll)
	$scrollTo.window = function( scope ){
		return $(window)._scrollable();
	};

	// Hack, hack, hack :)
	// Returns the real elements to scroll (supports window/iframes, documents and regular nodes)
	$.fn._scrollable = function(){
		return this.map(function(){
			var elem = this,
				isWin = !elem.nodeName || $.inArray( elem.nodeName.toLowerCase(), ['iframe','#document','html','body'] ) != -1;

				if( !isWin )
					return elem;

			var doc = (elem.contentWindow || elem).document || elem.ownerDocument || elem;
			
			return $.browser.safari || doc.compatMode == 'BackCompat' ?
				doc.body : 
				doc.documentElement;
		});
	};

	$.fn.scrollTo = function( target, duration, settings ){
		if( typeof duration == 'object' ){
			settings = duration;
			duration = 0;
		}
		if( typeof settings == 'function' )
			settings = { onAfter:settings };
			
		if( target == 'max' )
			target = 9e9;
			
		settings = $.extend( {}, $scrollTo.defaults, settings );
		// Speed is still recognized for backwards compatibility
		duration = duration || settings.speed || settings.duration;
		// Make sure the settings are given right
		settings.queue = settings.queue && settings.axis.length > 1;
		
		if( settings.queue )
			// Let's keep the overall duration
			duration /= 2;
		settings.offset = both( settings.offset );
		settings.over = both( settings.over );

		return this._scrollable().each(function(){
			var elem = this,
				$elem = $(elem),
				targ = target, toff, attr = {},
				win = $elem.is('html,body');

			switch( typeof targ ){
				// A number will pass the regex
				case 'number':
				case 'string':
					if( /^([+-]=)?\d+(\.\d+)?(px|%)?$/.test(targ) ){
						targ = both( targ );
						// We are done
						break;
					}
					// Relative selector, no break!
					targ = $(targ,this);
				case 'object':
					// DOMElement / jQuery
					if( targ.is || targ.style )
						// Get the real position of the target 
						toff = (targ = $(targ)).offset();
			}
			$.each( settings.axis.split(''), function( i, axis ){
				var Pos	= axis == 'x' ? 'Left' : 'Top',
					pos = Pos.toLowerCase(),
					key = 'scroll' + Pos,
					old = elem[key],
					max = $scrollTo.max(elem, axis);

				if( toff ){// jQuery / DOMElement
					attr[key] = toff[pos] + ( win ? 0 : old - $elem.offset()[pos] );

					// If it's a dom element, reduce the margin
					if( settings.margin ){
						attr[key] -= parseInt(targ.css('margin'+Pos)) || 0;
						attr[key] -= parseInt(targ.css('border'+Pos+'Width')) || 0;
					}
					
					attr[key] += settings.offset[pos] || 0;
					
					if( settings.over[pos] )
						// Scroll to a fraction of its width/height
						attr[key] += targ[axis=='x'?'width':'height']() * settings.over[pos];
				}else{ 
					var val = targ[pos];
					// Handle percentage values
					attr[key] = val.slice && val.slice(-1) == '%' ? 
						parseFloat(val) / 100 * max
						: val;
				}

				// Number or 'number'
				if( /^\d+$/.test(attr[key]) )
					// Check the limits
					attr[key] = attr[key] <= 0 ? 0 : Math.min( attr[key], max );

				// Queueing axes
				if( !i && settings.queue ){
					// Don't waste time animating, if there's no need.
					if( old != attr[key] )
						// Intermediate animation
						animate( settings.onAfterFirst );
					// Don't animate this axis again in the next iteration.
					delete attr[key];
				}
			});

			animate( settings.onAfter );			

			function animate( callback ){
				$elem.animate( attr, duration, settings.easing, callback && function(){
					callback.call(this, target, settings);
				});
			};

		}).end();
	};
	
	// Max scrolling position, works on quirks mode
	// It only fails (not too badly) on IE, quirks mode.
	$scrollTo.max = function( elem, axis ){
		var Dim = axis == 'x' ? 'Width' : 'Height',
			scroll = 'scroll'+Dim;
		
		if( !$(elem).is('html,body') )
			return elem[scroll] - $(elem)[Dim.toLowerCase()]();
		
		var size = 'client' + Dim,
			html = elem.ownerDocument.documentElement,
			body = elem.ownerDocument.body;

		return Math.max( html[scroll], body[scroll] ) 
			 - Math.min( html[size]  , body[size]   );
			
	};

	function both( val ){
		return typeof val == 'object' ? val : { top:val, left:val };
	};

})( jQuery );

/* ScrollTo Bitti*/

/* ETU SOZLUK KISMI
* @version 0.2
*/

			/*$.fn.insertAtCaret = function (tagName) {
			return this.each(function(){
				if (document.selection) {
					//IE support
					this.focus();
					sel = document.selection.createRange();
					sel.text = tagName;
					this.focus();
				}else if (this.selectionStart || this.selectionStart == '0') {
					//MOZILLA/NETSCAPE support
					startPos = this.selectionStart;
					endPos = this.selectionEnd;
					scrollTop = this.scrollTop;
					this.value = this.value.substring(0, startPos) + tagName + this.value.substring(endPos,this.value.length);
					this.focus();
					this.selectionStart = startPos + tagName.length;
					this.selectionEnd = startPos + tagName.length;
					this.scrollTop = scrollTop;
				} else {
					this.value += tagName;
					this.focus();
				}
			});
		};*/
		
		$.fn.btt = function(settings) {
			settings = $.extend({
				min: 1,
				fadeSpeed: 200
			}, settings);
			return this.each(function() {
				var el = $(this);
				$(window).scroll(function() {
				if($(window).scrollTop() >= settings.min)
					el.fadeIn(settings.fadeSpeed);
				else
					el.fadeOut(settings.fadeSpeed);
				});
			});
		};
		
		function isURL(a) {
			return /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(a)
		};
		
		function ep( s,w,h ) {
		if (!h) h = 400;
		if (!w) w = 320;
        var p = window.open(s,"_blank","toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,width=" + w.toString() + ",height=" + h.toString() +"resizeable=0");
		p.focus();
		};
		
		$.fn.tae = function (tagName, tag) {
			return this.each(function(){
				if (tagName === "bkz") {
					strStart = '(bkz: ';
					strEnd = ')';
				} else if (tagName === "spoiler") {
					strStart = '\n\r [spoiler] \n\r';
					strEnd = '\n\r [/spoiler] \n\r';
				} else if (tagName === "gizli") {
					strStart = '`';
					strEnd = '`';
				} else if (tagName === "url") {
					strStart = '['+tag+' ';
					strEnd = ']';
				}else {
					strStart = '['+tagName+']';
					strEnd = '[/'+tagName+']';
				}
				if (document.selection) {
					//IE support
					stringBefore = this.value;
					this.focus();
					sel = document.selection.createRange();
					insertstring = sel.text;
					fullinsertstring = strStart + sel.text + strEnd;
					sel.text = fullinsertstring;
					document.selection.empty();
					this.focus();
					stringAfter = this.value;
					i = stringAfter.lastIndexOf(fullinsertstring);
					range = this.createTextRange();
					numlines = stringBefore.substring(0,i).split("\n").length;
					i = i+3-numlines+tagName.length;
					j = insertstring.length;
					range.move("character",i);
					range.moveEnd("character",j);
					range.select();
				}else if (this.selectionStart || this.selectionStart == '0') {
					//rest
					startPos = this.selectionStart;
					endPos = this.selectionEnd;
					scrollTop = this.scrollTop;
					this.value = this.value.substring(0, startPos) + strStart + this.value.substring(startPos,endPos) + strEnd + this.value.substring(endPos,this.value.length);
					this.focus();
					this.selectionStart = startPos + strStart.length ;
					this.selectionEnd = endPos + strStart.length;
					this.scrollTop = scrollTop;
				} else {
					this.value += strStart + strEnd;
					this.focus();
				}
			});
		};

		function gungetir(gun, i) {
			var loading = 'Başlıklar yükleniyor... <br /><img src="img/1.gif">';
			var uza='';
			
			if (gun == "dun")
			  uza = "g=dun";
			else if(isFinite(gun) && $.isNumeric(gun) && gun.length == 8)
			  uza= "g=gun&gun="+gun.substr(0,2)+"&ay="+gun.substr(2,2)+"&yil="+gun.substr(4);
			else 
			  uza = "g=bugun";
			  
			$("#basliklar").empty().append(loading);
			$.ajax({
				url: "data/baslik.php",
				data: uza + "&page=" + i,
				dataType: "json",
				success: function(JSON) {

					$("#basliklar").empty();
					$("#basliklar").append('<ul class="b">');
					$.each(JSON.items, function(i,item) {
						var sayi='';
						if (item.count>1)
						  sayi='('+item.count+')';
						$("#basliklar").append('<li class="b">-&nbsp;<a href='+item.id+'>'+item.baslik+'</a> '+sayi+'</li>');
					});
					$("#basliklar").append('</ul>');
					var c = getcount(gun);
					$("#page_count").val(Math.ceil(c / 3));
					generateRows(i,gun);
						
				},

					error: function (request, status, error) {
						//alert(request.responseText);
						$("#basliklar").empty().append('Hata oluştu lütfen tekrar deneyin.');
					}
				});
		};
		
		function getcount(opt) {
			var count = 0;
			var uza = null;
			if (opt == "dun")
			  uza = "?say=dun";
			else if(isFinite(opt) && $.isNumeric(opt) && opt.length == 8)
			  uza= "?say=gun&gun="+opt.substr(0,2)+"&ay="+opt.substr(2,2)+"&yil="+opt.substr(4);
			else 
			  uza = "?say=bugun";
			$.ajax({
			  url: "count.php" +uza,
			  async: false,
			  success: function(data) {
			    count=data;
			  },
			  error: function() {
			  	$("#pagingControls").empty();
			  	$("#basliklar").empty().append('Hata oluştu!');
			  }
			});
			return count;
		};
		
		function yazarinfo(id,posX,posY) {
			$("#sharebox").remove();
			$("#yazarminiinfo").remove();
			$("#fbtw").remove();
			var bilgi="";
			$.post("yazarmini.php", {yid:id}, function(data) {
				bilgi += '<div id="yazarminiinfo">' + data + '<div class="kapat"></div></div>';
				$("body").append(bilgi);
				$("#yazarminiinfo").css({top: posY-30+"px", left: posX-260+"px"});
				$("#yazarminiinfo").delay(200).fadeIn(300);
				$(".kapat").click(function() { $("#yazarminiinfo").fadeOut(300, function() { $("#yazarminiinfo").remove() ;}) });
				$("#yazarminiinfo").click(function(e){ e.stopPropagation(); });
				$(document).one("click", function() { $("#yazarminiinfo").fadeOut(300, function() { $("#yazarminiinfo").remove() ;}); });
			});
		};
		
		function sharebox(eid,posX,posY) {
			$("#yazarminiinfo").remove();
			$("#sharebox").remove();
			$("#fbtw").remove();
			var bilgi="";
			bilgi += '<div id="sharebox"><input type="text" name="eadres" id="eadres" value="http://www.etusozluk.com/goster.php?e='+eid+'"/><br /><div style="position:relative; left:50px; top:2px; text-align:left; background-color:#000; width:50px;"><a type="button" name="fb_share" share_url="http://www.etusozluk.com/goster.php?eid='+eid+'">Paylaş</a></div><div style="position:relative; left:50px; top:5px; text-align:left; background-color:#000; width:50px;"><a href="https://twitter.com/share" class="twitter-share-button" data-url="http://www.etusozluk.com/goster.php?eid='+eid+'" data-via="etusozluk" data-lang="tr" data-count="none"></a><br /></div><div class="kapat"></div></div>';
			$('body').append('<div id="fbtw"><script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div>');
			$("body").append(bilgi);
			$("#sharebox").css({top: posY+10+"px", left: posX+10+"px"});
			$("#sharebox").show();
			$(".kapat").click(function() { $("#sharebox").fadeOut(300, function() { $("#sharebox").remove(); $("#fbtw").remove(); }) });
			$(document).one("click", function() { $("#sharebox").fadeOut(300, function() { $("#sharebox").remove(); $("#fbtw").remove(); }); });
			$("#sharebox").click(function(e){ e.stopPropagation(); });
			
		};
		
		function enBu(){
			$('input[name="login"]').removeAttr('disabled');
			$('input[name="login"]').removeClass('disabled');
		};
		
	$(document).ready(function() {
		gungetir(0,1);
		var c = getcount(0);
		$("#page_count").val(Math.ceil(c / 3));
		generateRows(1,0);
		
		$("#txt1").maxlength({slider: true, maxCharacters: 255} );
		
		$(".aramenu").click(function() {
			if ($(".sikayetmenupanel:visible").length>0) {
				$(".sikayetmenupanel").hide();
				$(".sikayetmenu").toggleClass("active");
			}

			$(".aramenupanel").toggle(0);
			$(this).toggleClass("active");
			return false;

			});
			
		$("textarea").css({resize: "none"});
		
		$(".sikayetmenu").click(function() {

			if ($(".aramenupanel:visible").length>0) {

				$(".aramenupanel").hide();
				$(".aramenu").toggleClass("active");
			}

			$(".sikayetmenupanel").toggle(0);
			$(this).toggleClass("active");
			return false;
			});
			
			$("#uyeol").click(function() {
				$("#loginbox").toggle(0).focus();
				$("#gizlimenu").hide();
			});

			$("#bugun, #dun, #rastgele, #hot, #yeni, #ark, #iyuf,").hover(function(){
				if ($("#loginbox:visible").length>0) {
					$("#gizlimenu").hide();
				}
				else {
					$(this).find("div#gizlimenu").show();
				}
			}, function() {
				$(this).find("div#gizlimenu").hide();
			});
			
			$("#bugun").click(function() {
				var c = getcount(0);
				$("#page_count").val(Math.ceil(c / 3));
				gungetir(0,1);
				generateRows(1,0);
			});
			
			$("#dun").click(function() {
				var c = getcount("dun");
				$("#page_count").val(Math.ceil(c / 3));
				gungetir("dun",1);
				generateRows(1,"dun");
			});
			
			$("li #entryid").click(function(e) {
				e.preventDefault();
				e.stopPropagation();
				sharebox($(this).text().substr(1),$(this).offset().left,$(this).offset().top);
			});
			
			$(".girdi").hover(function() {
				$(this).find(".ymore").show();				
			}, function() {
				$(this).find(".ymore").hide();
			});
			
			var ytimer;
			$("li #yazar").mouseenter(function() {
				$cur = $(this);
				ytimer = setTimeout(function() {yazarinfo($cur.attr("rel"),$cur.offset().left,$cur.offset().top);},500);
			}).mouseleave(function() { clearTimeout(ytimer); });
			
			$("#titlea").click(function() {
				if ($("#titlea").val() === "" || $("#titlea").val() === "Başlık Getir") 
					$("#titlea").focus().val("");
			});
			
			$("#titlea").blur(function() {
				if ($("#titlea").val() === "" || $("#titlea").val() === "Başlık Getir") 
					$(this).val("Başlık Getir");
			});
			
			$("#bkz").click(function() {
				$("#entrytextarea").tae("bkz");
			});
			
			$("#gizlibkz").click(function() {
				$("#entrytextarea").tae("gizli");
			});
			
			$("#spoiler").click(function() {
				$("#entrytextarea").tae("spoiler");
			});
			
			$('#top-link').btt({ min:400, fadeSpeed:500 });
			$('#top-link').click(function(e) {
				e.preventDefault();
				$.scrollTo(0,300);
			});
			
			$('form[name="loginform"]').submit(function(e) {
				e.preventDefault();
				$('input[name="login"]').attr("disabled","disabled");
				$('input[name="login"]').addClass('disabled');
				setTimeout('enBu()', 5000);
				$.ajax({
				url: "login.php",
				dataType: "json",
				data: "login&"+$(this).serialize(),
				success: function(data) {
				if (data.durum) {
					$("#uyeol").find("span").text('Ben');
					$("#loginbox").empty();
					$("#loginbox").append('<p class="lgbaslik">' + data.nick + '</p><hr class="lg"/><p style="text-align:left; padding-left:50px; margin:0;"><a href="hq.php">HQ</a><br /><a href="mesaj.php">Mesajlar</a><br /><a href="getir.php?mode=ark">Arkadaşlar</a><br /><a href="getir.php?mode=kenar">Kenarda Duranlar</a><br /><a href="getir.php?mode=yeni">Yeni</a><br /><a href="login.php?mode=cikis">Çıkış</a></p>');
					if ($("#baslikd").val()) { //tekrar çağırmak kirlilik, ilerde burasını direkt kaldırabilirim.
						$("#hg").append('<div style="text-align:left; padding-top:10px; padding-left:25px;">"'+$("#baslikd").val()+'" hakkında söylemek istediklerim var diyorsan hadi durma:	<form action="ekle.php" method="post" id="yenigirdi" name="yenigirdi"><input type="hidden" name="t" value="'+$("#baslikd").val()+'" /><div id="butonlar" style="text-align:left; width:100%; padding-top:10px;"><input type="button" id="bkz" value="(bkz: )" class="ebut" /><input type="button" id="gizlibkz" value="``" class="ebut"/><input type="button" id="spoiler" value="spoiler" class="ebut"/><input type="button" value="link" onclick="var a=prompt(\'link: (başında http:// olmalı)\', \'http://\');if(isURL(a))$(\'#entrytextarea\').tae(\'url\',a);" class="ebut"/></div><textarea id="entrytextarea" rows="10" cols="105" class="ygirdi" name="ygirdi"></textarea><input type="submit" value="böyle olur" class="ebut" /><input type="submit" value="bunu sonra gönderirim" class="ebut" name="kaydet" /></form></div>');
						$("#bkz").click(function() {
							$("#entrytextarea").tae("bkz");
						});
			
						$("#gizlibkz").click(function() {
							$("#entrytextarea").tae("gizli");
						});
			
						$("#spoiler").click(function() {
							$("#entrytextarea").tae("spoiler");
						});
					}
				}
				else if(data.code) {
					$('#hata').remove();
					$('#loginbox').append('<p id="hata" style="color:#fecc00;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><strong>Hata: </strong>'+data.message+'</p>');
				}
			}
			});
			});
	});

/* ETU SOZLUK KISMI BITIS */
