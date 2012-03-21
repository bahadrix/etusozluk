/**
 * SU AN OLANLAR
 * Pagination ver custometumade http://www.myphpetc.com/2009/10/easy-pagination-with-jquery-and-ajax.html
 * Maxlength ver 1.0.5
 *
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

/* ETU SOZLUK KISMI */

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
		
		function isURL(a) {
			return /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(a)
		}
		
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
		}
		
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
		}
		
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
		}
		
		function sharebox(eid,posX,posY) {
			$("#yazarminiinfo").remove();
			$("#sharebox").remove();
			$("#fbtw").remove();
			var bilgi="";
			bilgi += '<div id="sharebox"><input type="text" name="eadres" id="eadres" value="http://www.etusozluk.com/goster.php?eid='+eid+'"/><br /><div style="position:relative; left:50px; top:2px; text-align:left; background-color:#000; width:50px;"><a type="button" name="fb_share" share_url="http://www.etusozluk.com/goster.php?eid='+eid+'">Paylaş</a></div><div style="position:relative; left:50px; top:5px; text-align:left; background-color:#000; width:50px;"><a href="https://twitter.com/share" class="twitter-share-button" data-url="http://www.etusozluk.com/goster.php?eid='+eid+'" data-via="etusozluk" data-lang="tr" data-count="none"></a><br /></div><div class="kapat"></div></div>';
			$('body').append('<div id="fbtw"><script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div>');
			$("body").append(bilgi);
			$("#sharebox").css({top: posY+10+"px", left: posX+10+"px"});
			$("#sharebox").show();
			$(".kapat").click(function() { $("#sharebox").fadeOut(300, function() { $("#sharebox").remove(); $("#fbtw").remove(); }) });
			$(document).one("click", function() { $("#sharebox").fadeOut(300, function() { $("#sharebox").remove(); $("#fbtw").remove(); }); });
			$("#sharebox").click(function(e){ e.stopPropagation(); });
			
		}
		
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
			
			$("#ben").click(function() {
				$("#loginboxx").toggle(0).focus();
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
				$("#titlea").focus().val("");
			});
			
			$("#titlea").blur(function() {
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
	});

/* ETU SOZLUK KISMI BITIS */
