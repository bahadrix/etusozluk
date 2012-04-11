/**
 * SU AN OLANLAR
 * Pagination version custometumade http://www.myphpetc.com/2009/10/easy-pagination-with-jquery-and-ajax.html
 * Maxlength version 1.0.5 http://www.stjerneman.com/demo/maxlength-with-jquery
 * ScrollTo version 1.4.2 http://flesler.blogspot.com/2007/10/jqueryscrollto.html
 * ajaxQueue version 1.0 http://stackoverflow.com/questions/3034874/sequencing-ajax-requests/3035268#3035268
 * History https://github.com/balupton/history.js
 * SimpleModal 1.4.2 http://simplemodal.com/
 * Daha sonra bak https://gist.github.com/854622
 * back/forward daha sonra
 */
 

/* jQuery Maxlength plugin */
(function(A){A.fn.maxlength=function(B){var C=jQuery.extend({events:[],maxCharacters:10,status:true,statusClass:"status",statusText:"character left",notificationClass:"notification",showAlert:false,alertText:"You have typed too many characters.",slider:false},B);A.merge(C.events,["keyup"]);return this.each(function(){var G=A(this);var J=A(this).val().length;function D(){var K=C.maxCharacters-J;if(K<0){K=0}G.next("div").html(K+" "+C.statusText)}function E(){var K=true;if(J>=C.maxCharacters){K=false;G.addClass(C.notificationClass);G.val(G.val().substr(0,C.maxCharacters));I()}else{if(G.hasClass(C.notificationClass)){G.removeClass(C.notificationClass)}}if(C.status){D()}}function I(){if(C.showAlert){alert(C.alertText)}}function F(){var K=false;if(G.is("textarea")){K=true}else{if(G.filter("input[type=text]")){K=true}else{if(G.filter("input[type=password]")){K=true}}}return K}if(!F()){return false}A.each(C.events,function(K,L){G.bind(L,function(M){J=G.val().length;E()})});if(C.status){G.after(A("<div/>").addClass(C.statusClass).html("-"));D()}if(!C.status){var H=G.next("div."+C.statusClass);if(H){H.remove()}}if(C.slider){G.next().hide();G.focus(function(){G.next().slideDown("fast")});G.blur(function(){G.next().slideUp("fast")})}})}})(jQuery);
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

/* jQuery.ScrollTo - Easy element scrolling using jQuery. */
(function(d){var k=d.scrollTo=function(a,i,e){d(window).scrollTo(a,i,e)};k.defaults={axis:'xy',duration:parseFloat(d.fn.jquery)>=1.3?0:1};k.window=function(a){return d(window)._scrollable()};d.fn._scrollable=function(){return this.map(function(){var a=this,i=!a.nodeName||d.inArray(a.nodeName.toLowerCase(),['iframe','#document','html','body'])!=-1;if(!i)return a;var e=(a.contentWindow||a).document||a.ownerDocument||a;return d.browser.safari||e.compatMode=='BackCompat'?e.body:e.documentElement})};d.fn.scrollTo=function(n,j,b){if(typeof j=='object'){b=j;j=0}if(typeof b=='function')b={onAfter:b};if(n=='max')n=9e9;b=d.extend({},k.defaults,b);j=j||b.speed||b.duration;b.queue=b.queue&&b.axis.length>1;if(b.queue)j/=2;b.offset=p(b.offset);b.over=p(b.over);return this._scrollable().each(function(){var q=this,r=d(q),f=n,s,g={},u=r.is('html,body');switch(typeof f){case'number':case'string':if(/^([+-]=)?\d+(\.\d+)?(px|%)?$/.test(f)){f=p(f);break}f=d(f,this);case'object':if(f.is||f.style)s=(f=d(f)).offset()}d.each(b.axis.split(''),function(a,i){var e=i=='x'?'Left':'Top',h=e.toLowerCase(),c='scroll'+e,l=q[c],m=k.max(q,i);if(s){g[c]=s[h]+(u?0:l-r.offset()[h]);if(b.margin){g[c]-=parseInt(f.css('margin'+e))||0;g[c]-=parseInt(f.css('border'+e+'Width'))||0}g[c]+=b.offset[h]||0;if(b.over[h])g[c]+=f[i=='x'?'width':'height']()*b.over[h]}else{var o=f[h];g[c]=o.slice&&o.slice(-1)=='%'?parseFloat(o)/100*m:o}if(/^\d+$/.test(g[c]))g[c]=g[c]<=0?0:Math.min(g[c],m);if(!a&&b.queue){if(l!=g[c])t(b.onAfterFirst);delete g[c]}});t(b.onAfter);function t(a){r.animate(g,j,b.easing,a&&function(){a.call(this,n,b)})}}).end()};k.max=function(a,i){var e=i=='x'?'Width':'Height',h='scroll'+e;if(!d(a).is('html,body'))return a[h]-d(a)[e.toLowerCase()]();var c='client'+e,l=a.ownerDocument.documentElement,m=a.ownerDocument.body;return Math.max(l[h],m[h])-Math.min(l[c],m[c])};function p(a){return typeof a=='object'?a:{top:a,left:a}}})(jQuery);
/* ScrollTo Bitti*/

/* jQuery.ajaxQueue - A queue for ajax requests */
(function($){var ajaxQueue = $({});$.ajaxQueue = function( ajaxOpts ) {var jqXHR,dfd = $.Deferred(),promise = dfd.promise();ajaxQueue.queue( doRequest );promise.abort = function( statusText ) {if ( jqXHR ) {return jqXHR.abort( statusText );}var queue = ajaxQueue.queue(),index = $.inArray( doRequest, queue );if ( index > -1 ) {queue.splice( index, 1 );}dfd.rejectWith( ajaxOpts.context || ajaxOpts,[ promise, statusText, "" ] );return promise;};function doRequest( next ) {jqXHR = $.ajax( ajaxOpts ).then( next, next ).done( dfd.resolve ).fail( dfd.reject );}return promise;};})(jQuery);
/* ajaxQueue bitti */

/* History */
window.JSON||(window.JSON={}),function(){function f(a){return a<10?"0"+a:a}function quote(a){return escapable.lastIndex=0,escapable.test(a)?'"'+a.replace(escapable,function(a){var b=meta[a];return typeof b=="string"?b:"\\u"+("0000"+a.charCodeAt(0).toString(16)).slice(-4)})+'"':'"'+a+'"'}function str(a,b){var c,d,e,f,g=gap,h,i=b[a];i&&typeof i=="object"&&typeof i.toJSON=="function"&&(i=i.toJSON(a)),typeof rep=="function"&&(i=rep.call(b,a,i));switch(typeof i){case"string":return quote(i);case"number":return isFinite(i)?String(i):"null";case"boolean":case"null":return String(i);case"object":if(!i)return"null";gap+=indent,h=[];if(Object.prototype.toString.apply(i)==="[object Array]"){f=i.length;for(c=0;c<f;c+=1)h[c]=str(c,i)||"null";return e=h.length===0?"[]":gap?"[\n"+gap+h.join(",\n"+gap)+"\n"+g+"]":"["+h.join(",")+"]",gap=g,e}if(rep&&typeof rep=="object"){f=rep.length;for(c=0;c<f;c+=1)d=rep[c],typeof d=="string"&&(e=str(d,i),e&&h.push(quote(d)+(gap?": ":":")+e))}else for(d in i)Object.hasOwnProperty.call(i,d)&&(e=str(d,i),e&&h.push(quote(d)+(gap?": ":":")+e));return e=h.length===0?"{}":gap?"{\n"+gap+h.join(",\n"+gap)+"\n"+g+"}":"{"+h.join(",")+"}",gap=g,e}}"use strict",typeof Date.prototype.toJSON!="function"&&(Date.prototype.toJSON=function(a){return isFinite(this.valueOf())?this.getUTCFullYear()+"-"+f(this.getUTCMonth()+1)+"-"+f(this.getUTCDate())+"T"+f(this.getUTCHours())+":"+f(this.getUTCMinutes())+":"+f(this.getUTCSeconds())+"Z":null},String.prototype.toJSON=Number.prototype.toJSON=Boolean.prototype.toJSON=function(a){return this.valueOf()});var JSON=window.JSON,cx=/[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,escapable=/[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,gap,indent,meta={"\b":"\\b","\t":"\\t","\n":"\\n","\f":"\\f","\r":"\\r",'"':'\\"',"\\":"\\\\"},rep;typeof JSON.stringify!="function"&&(JSON.stringify=function(a,b,c){var d;gap="",indent="";if(typeof c=="number")for(d=0;d<c;d+=1)indent+=" ";else typeof c=="string"&&(indent=c);rep=b;if(!b||typeof b=="function"||typeof b=="object"&&typeof b.length=="number")return str("",{"":a});throw new Error("JSON.stringify")}),typeof JSON.parse!="function"&&(JSON.parse=function(text,reviver){function walk(a,b){var c,d,e=a[b];if(e&&typeof e=="object")for(c in e)Object.hasOwnProperty.call(e,c)&&(d=walk(e,c),d!==undefined?e[c]=d:delete e[c]);return reviver.call(a,b,e)}var j;text=String(text),cx.lastIndex=0,cx.test(text)&&(text=text.replace(cx,function(a){return"\\u"+("0000"+a.charCodeAt(0).toString(16)).slice(-4)}));if(/^[\],:{}\s]*$/.test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g,"@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,"]").replace(/(?:^|:|,)(?:\s*\[)+/g,"")))return j=eval("("+text+")"),typeof reviver=="function"?walk({"":j},""):j;throw new SyntaxError("JSON.parse")})}(),function(a,b){"use strict";var c=a.History=a.History||{},d=a.jQuery;if(typeof c.Adapter!="undefined")throw new Error("History.js Adapter has already been loaded...");c.Adapter={bind:function(a,b,c){d(a).bind(b,c)},trigger:function(a,b,c){d(a).trigger(b,c)},extractEventData:function(a,c,d){var e=c&&c.originalEvent&&c.originalEvent[a]||d&&d[a]||b;return e},onDomLoad:function(a){d(a)}},typeof c.init!="undefined"&&c.init()}(window),function(a,b){"use strict";var c=a.document,d=a.setTimeout||d,e=a.clearTimeout||e,f=a.setInterval||f,g=a.History=a.History||{};if(typeof g.initHtml4!="undefined")throw new Error("History.js HTML4 Support has already been loaded...");g.initHtml4=function(){if(typeof g.initHtml4.initialized!="undefined")return!1;g.initHtml4.initialized=!0,g.enabled=!0,g.savedHashes=[],g.isLastHash=function(a){var b=g.getHashByIndex(),c;return c=a===b,c},g.saveHash=function(a){return g.isLastHash(a)?!1:(g.savedHashes.push(a),!0)},g.getHashByIndex=function(a){var b=null;return typeof a=="undefined"?b=g.savedHashes[g.savedHashes.length-1]:a<0?b=g.savedHashes[g.savedHashes.length+a]:b=g.savedHashes[a],b},g.discardedHashes={},g.discardedStates={},g.discardState=function(a,b,c){var d=g.getHashByState(a),e;return e={discardedState:a,backState:c,forwardState:b},g.discardedStates[d]=e,!0},g.discardHash=function(a,b,c){var d={discardedHash:a,backState:c,forwardState:b};return g.discardedHashes[a]=d,!0},g.discardedState=function(a){var b=g.getHashByState(a),c;return c=g.discardedStates[b]||!1,c},g.discardedHash=function(a){var b=g.discardedHashes[a]||!1;return b},g.recycleState=function(a){var b=g.getHashByState(a);return g.discardedState(a)&&delete g.discardedStates[b],!0},g.emulated.hashChange&&(g.hashChangeInit=function(){g.checkerFunction=null;var b="",d,e,h,i;return g.isInternetExplorer()?(d="historyjs-iframe",e=c.createElement("iframe"),e.setAttribute("id",d),e.style.display="none",c.body.appendChild(e),e.contentWindow.document.open(),e.contentWindow.document.close(),h="",i=!1,g.checkerFunction=function(){if(i)return!1;i=!0;var c=g.getHash()||"",d=g.unescapeHash(e.contentWindow.document.location.hash)||"";return c!==b?(b=c,d!==c&&(h=d=c,e.contentWindow.document.open(),e.contentWindow.document.close(),e.contentWindow.document.location.hash=g.escapeHash(c)),g.Adapter.trigger(a,"hashchange")):d!==h&&(h=d,g.setHash(d,!1)),i=!1,!0}):g.checkerFunction=function(){var c=g.getHash();return c!==b&&(b=c,g.Adapter.trigger(a,"hashchange")),!0},g.intervalList.push(f(g.checkerFunction,g.options.hashChangeInterval)),!0},g.Adapter.onDomLoad(g.hashChangeInit)),g.emulated.pushState&&(g.onHashChange=function(b){var d=b&&b.newURL||c.location.href,e=g.getHashByUrl(d),f=null,h=null,i=null,j;return g.isLastHash(e)?(g.busy(!1),!1):(g.doubleCheckComplete(),g.saveHash(e),e&&g.isTraditionalAnchor(e)?(g.Adapter.trigger(a,"anchorchange"),g.busy(!1),!1):(f=g.extractState(g.getFullUrl(e||c.location.href,!1),!0),g.isLastSavedState(f)?(g.busy(!1),!1):(h=g.getHashByState(f),j=g.discardedState(f),j?(g.getHashByIndex(-2)===g.getHashByState(j.forwardState)?g.back(!1):g.forward(!1),!1):(g.pushState(f.data,f.title,f.url,!1),!0))))},g.Adapter.bind(a,"hashchange",g.onHashChange),g.pushState=function(b,d,e,f){if(g.getHashByUrl(e))throw new Error("History.js does not support states with fragement-identifiers (hashes/anchors).");if(f!==!1&&g.busy())return g.pushQueue({scope:g,callback:g.pushState,args:arguments,queue:f}),!1;g.busy(!0);var h=g.createStateObject(b,d,e),i=g.getHashByState(h),j=g.getState(!1),k=g.getHashByState(j),l=g.getHash();return g.storeState(h),g.expectedStateId=h.id,g.recycleState(h),g.setTitle(h),i===k?(g.busy(!1),!1):i!==l&&i!==g.getShortUrl(c.location.href)?(g.setHash(i,!1),!1):(g.saveState(h),g.Adapter.trigger(a,"statechange"),g.busy(!1),!0)},g.replaceState=function(a,b,c,d){if(g.getHashByUrl(c))throw new Error("History.js does not support states with fragement-identifiers (hashes/anchors).");if(d!==!1&&g.busy())return g.pushQueue({scope:g,callback:g.replaceState,args:arguments,queue:d}),!1;g.busy(!0);var e=g.createStateObject(a,b,c),f=g.getState(!1),h=g.getStateByIndex(-2);return g.discardState(f,e,h),g.pushState(e.data,e.title,e.url,!1),!0}),g.emulated.pushState&&g.getHash()&&!g.emulated.hashChange&&g.Adapter.onDomLoad(function(){g.Adapter.trigger(a,"hashchange")})},typeof g.init!="undefined"&&g.init()}(window),function(a,b){"use strict";var c=a.console||b,d=a.document,e=a.navigator,f=a.sessionStorage||!1,g=a.setTimeout,h=a.clearTimeout,i=a.setInterval,j=a.clearInterval,k=a.JSON,l=a.alert,m=a.History=a.History||{},n=a.history;k.stringify=k.stringify||k.encode,k.parse=k.parse||k.decode;if(typeof m.init!="undefined")throw new Error("History.js Core has already been loaded...");m.init=function(){return typeof m.Adapter=="undefined"?!1:(typeof m.initCore!="undefined"&&m.initCore(),typeof m.initHtml4!="undefined"&&m.initHtml4(),!0)},m.initCore=function(){if(typeof m.initCore.initialized!="undefined")return!1;m.initCore.initialized=!0,m.options=m.options||{},m.options.hashChangeInterval=m.options.hashChangeInterval||100,m.options.safariPollInterval=m.options.safariPollInterval||500,m.options.doubleCheckInterval=m.options.doubleCheckInterval||500,m.options.storeInterval=m.options.storeInterval||1e3,m.options.busyDelay=m.options.busyDelay||250,m.options.debug=m.options.debug||!1,m.options.initialTitle=m.options.initialTitle||d.title,m.intervalList=[],m.clearAllIntervals=function(){var a,b=m.intervalList;if(typeof b!="undefined"&&b!==null){for(a=0;a<b.length;a++)j(b[a]);m.intervalList=null}},m.debug=function(){(m.options.debug||!1)&&m.log.apply(m,arguments)},m.log=function(){var a=typeof c!="undefined"&&typeof c.log!="undefined"&&typeof c.log.apply!="undefined",b=d.getElementById("log"),e,f,g,h,i;a?(h=Array.prototype.slice.call(arguments),e=h.shift(),typeof c.debug!="undefined"?c.debug.apply(c,[e,h]):c.log.apply(c,[e,h])):e="\n"+arguments[0]+"\n";for(f=1,g=arguments.length;f<g;++f){i=arguments[f];if(typeof i=="object"&&typeof k!="undefined")try{i=k.stringify(i)}catch(j){}e+="\n"+i+"\n"}return b?(b.value+=e+"\n-----\n",b.scrollTop=b.scrollHeight-b.clientHeight):a||l(e),!0},m.getInternetExplorerMajorVersion=function(){var a=m.getInternetExplorerMajorVersion.cached=typeof m.getInternetExplorerMajorVersion.cached!="undefined"?m.getInternetExplorerMajorVersion.cached:function(){var a=3,b=d.createElement("div"),c=b.getElementsByTagName("i");while((b.innerHTML="<!--[if gt IE "+ ++a+"]><i></i><![endif]-->")&&c[0]);return a>4?a:!1}();return a},m.isInternetExplorer=function(){var a=m.isInternetExplorer.cached=typeof m.isInternetExplorer.cached!="undefined"?m.isInternetExplorer.cached:Boolean(m.getInternetExplorerMajorVersion());return a},m.emulated={pushState:!Boolean(a.history&&a.history.pushState&&a.history.replaceState&&!/ Mobile\/([1-7][a-z]|(8([abcde]|f(1[0-8]))))/i.test(e.userAgent)&&!/AppleWebKit\/5([0-2]|3[0-2])/i.test(e.userAgent)),hashChange:Boolean(!("onhashchange"in a||"onhashchange"in d)||m.isInternetExplorer()&&m.getInternetExplorerMajorVersion()<8)},m.enabled=!m.emulated.pushState,m.bugs={setHash:Boolean(!m.emulated.pushState&&e.vendor==="Apple Computer, Inc."&&/AppleWebKit\/5([0-2]|3[0-3])/.test(e.userAgent)),safariPoll:Boolean(!m.emulated.pushState&&e.vendor==="Apple Computer, Inc."&&/AppleWebKit\/5([0-2]|3[0-3])/.test(e.userAgent)),ieDoubleCheck:Boolean(m.isInternetExplorer()&&m.getInternetExplorerMajorVersion()<8),hashEscape:Boolean(m.isInternetExplorer()&&m.getInternetExplorerMajorVersion()<7)},m.isEmptyObject=function(a){for(var b in a)return!1;return!0},m.cloneObject=function(a){var b,c;return a?(b=k.stringify(a),c=k.parse(b)):c={},c},m.getRootUrl=function(){var a=d.location.protocol+"//"+(d.location.hostname||d.location.host);if(d.location.port||!1)a+=":"+d.location.port;return a+="/",a},m.getBaseHref=function(){var a=d.getElementsByTagName("base"),b=null,c="";return a.length===1&&(b=a[0],c=b.href.replace(/[^\/]+$/,"")),c=c.replace(/\/+$/,""),c&&(c+="/"),c},m.getBaseUrl=function(){var a=m.getBaseHref()||m.getBasePageUrl()||m.getRootUrl();return a},m.getPageUrl=function(){var a=m.getState(!1,!1),b=(a||{}).url||d.location.href,c;return c=b.replace(/\/+$/,"").replace(/[^\/]+$/,function(a,b,c){return/\./.test(a)?a:a+"/"}),c},m.getBasePageUrl=function(){var a=d.location.href.replace(/[#\?].*/,"").replace(/[^\/]+$/,function(a,b,c){return/[^\/]$/.test(a)?"":a}).replace(/\/+$/,"")+"/";return a},m.getFullUrl=function(a,b){var c=a,d=a.substring(0,1);return b=typeof b=="undefined"?!0:b,/[a-z]+\:\/\//.test(a)||(d==="/"?c=m.getRootUrl()+a.replace(/^\/+/,""):d==="#"?c=m.getPageUrl().replace(/#.*/,"")+a:d==="?"?c=m.getPageUrl().replace(/[\?#].*/,"")+a:b?c=m.getBaseUrl()+a.replace(/^(\.\/)+/,""):c=m.getBasePageUrl()+a.replace(/^(\.\/)+/,"")),c.replace(/\#$/,"")},m.getShortUrl=function(a){var b=a,c=m.getBaseUrl(),d=m.getRootUrl();return m.emulated.pushState&&(b=b.replace(c,"")),b=b.replace(d,"/"),m.isTraditionalAnchor(b)&&(b="./"+b),b=b.replace(/^(\.\/)+/g,"./").replace(/\#$/,""),b},m.store={},m.idToState=m.idToState||{},m.stateToId=m.stateToId||{},m.urlToId=m.urlToId||{},m.storedStates=m.storedStates||[],m.savedStates=m.savedStates||[],m.normalizeStore=function(){m.store.idToState=m.store.idToState||{},m.store.urlToId=m.store.urlToId||{},m.store.stateToId=m.store.stateToId||{}},m.getState=function(a,b){typeof a=="undefined"&&(a=!0),typeof b=="undefined"&&(b=!0);var c=m.getLastSavedState();return!c&&b&&(c=m.createStateObject()),a&&(c=m.cloneObject(c),c.url=c.cleanUrl||c.url),c},m.getIdByState=function(a){var b=m.extractId(a.url),c;if(!b){c=m.getStateString(a);if(typeof m.stateToId[c]!="undefined")b=m.stateToId[c];else if(typeof m.store.stateToId[c]!="undefined")b=m.store.stateToId[c];else{for(;;){b=(new Date).getTime()+String(Math.random()).replace(/\D/g,"");if(typeof m.idToState[b]=="undefined"&&typeof m.store.idToState[b]=="undefined")break}m.stateToId[c]=b,m.idToState[b]=a}}return b},m.normalizeState=function(a){var b,c;if(!a||typeof a!="object")a={};if(typeof a.normalized!="undefined")return a;if(!a.data||typeof a.data!="object")a.data={};b={},b.normalized=!0,b.title=a.title||"",b.url=m.getFullUrl(m.unescapeString(a.url||d.location.href)),b.hash=m.getShortUrl(b.url),b.data=m.cloneObject(a.data),b.id=m.getIdByState(b),b.cleanUrl=b.url.replace(/\??\&_suid.*/,""),b.url=b.cleanUrl,c=!m.isEmptyObject(b.data);if(b.title||c)b.hash=m.getShortUrl(b.url).replace(/\??\&_suid.*/,""),/\?/.test(b.hash)||(b.hash+="?"),b.hash+="&_suid="+b.id;return b.hashedUrl=m.getFullUrl(b.hash),(m.emulated.pushState||m.bugs.safariPoll)&&m.hasUrlDuplicate(b)&&(b.url=b.hashedUrl),b},m.createStateObject=function(a,b,c){var d={data:a,title:b,url:c};return d=m.normalizeState(d),d},m.getStateById=function(a){a=String(a);var c=m.idToState[a]||m.store.idToState[a]||b;return c},m.getStateString=function(a){var b,c,d;return b=m.normalizeState(a),c={data:b.data,title:a.title,url:a.url},d=k.stringify(c),d},m.getStateId=function(a){var b,c;return b=m.normalizeState(a),c=b.id,c},m.getHashByState=function(a){var b,c;return b=m.normalizeState(a),c=b.hash,c},m.extractId=function(a){var b,c,d;return c=/(.*)\&_suid=([0-9]+)$/.exec(a),d=c?c[1]||a:a,b=c?String(c[2]||""):"",b||!1},m.isTraditionalAnchor=function(a){var b=!/[\/\?\.]/.test(a);return b},m.extractState=function(a,b){var c=null,d,e;return b=b||!1,d=m.extractId(a),d&&(c=m.getStateById(d)),c||(e=m.getFullUrl(a),d=m.getIdByUrl(e)||!1,d&&(c=m.getStateById(d)),!c&&b&&!m.isTraditionalAnchor(a)&&(c=m.createStateObject(null,null,e))),c},m.getIdByUrl=function(a){var c=m.urlToId[a]||m.store.urlToId[a]||b;return c},m.getLastSavedState=function(){return m.savedStates[m.savedStates.length-1]||b},m.getLastStoredState=function(){return m.storedStates[m.storedStates.length-1]||b},m.hasUrlDuplicate=function(a){var b=!1,c;return c=m.extractState(a.url),b=c&&c.id!==a.id,b},m.storeState=function(a){return m.urlToId[a.url]=a.id,m.storedStates.push(m.cloneObject(a)),a},m.isLastSavedState=function(a){var b=!1,c,d,e;return m.savedStates.length&&(c=a.id,d=m.getLastSavedState(),e=d.id,b=c===e),b},m.saveState=function(a){return m.isLastSavedState(a)?!1:(m.savedStates.push(m.cloneObject(a)),!0)},m.getStateByIndex=function(a){var b=null;return typeof a=="undefined"?b=m.savedStates[m.savedStates.length-1]:a<0?b=m.savedStates[m.savedStates.length+a]:b=m.savedStates[a],b},m.getHash=function(){var a=m.unescapeHash(d.location.hash);return a},m.unescapeString=function(b){var c=b,d;for(;;){d=a.unescape(c);if(d===c)break;c=d}return c},m.unescapeHash=function(a){var b=m.normalizeHash(a);return b=m.unescapeString(b),b},m.normalizeHash=function(a){var b=a.replace(/[^#]*#/,"").replace(/#.*/,"");return b},m.setHash=function(a,b){var c,e,f;return b!==!1&&m.busy()?(m.pushQueue({scope:m,callback:m.setHash,args:arguments,queue:b}),!1):(c=m.escapeHash(a),m.busy(!0),e=m.extractState(a,!0),e&&!m.emulated.pushState?m.pushState(e.data,e.title,e.url,!1):d.location.hash!==c&&(m.bugs.setHash?(f=m.getPageUrl(),m.pushState(null,null,f+"#"+c,!1)):d.location.hash=c),m)},m.escapeHash=function(b){var c=m.normalizeHash(b);return c=a.escape(c),m.bugs.hashEscape||(c=c.replace(/\%21/g,"!").replace(/\%26/g,"&").replace(/\%3D/g,"=").replace(/\%3F/g,"?")),c},m.getHashByUrl=function(a){var b=String(a).replace(/([^#]*)#?([^#]*)#?(.*)/,"$2");return b=m.unescapeHash(b),b},m.setTitle=function(a){var b=a.title,c;b||(c=m.getStateByIndex(0),c&&c.url===a.url&&(b=c.title||m.options.initialTitle));try{d.getElementsByTagName("title")[0].innerHTML=b.replace("<","&lt;").replace(">","&gt;").replace(" & "," &amp; ")}catch(e){}return d.title=b,m},m.queues=[],m.busy=function(a){typeof a!="undefined"?m.busy.flag=a:typeof m.busy.flag=="undefined"&&(m.busy.flag=!1);if(!m.busy.flag){h(m.busy.timeout);var b=function(){var a,c,d;if(m.busy.flag)return;for(a=m.queues.length-1;a>=0;--a){c=m.queues[a];if(c.length===0)continue;d=c.shift(),m.fireQueueItem(d),m.busy.timeout=g(b,m.options.busyDelay)}};m.busy.timeout=g(b,m.options.busyDelay)}return m.busy.flag},m.busy.flag=!1,m.fireQueueItem=function(a){return a.callback.apply(a.scope||m,a.args||[])},m.pushQueue=function(a){return m.queues[a.queue||0]=m.queues[a.queue||0]||[],m.queues[a.queue||0].push(a),m},m.queue=function(a,b){return typeof a=="function"&&(a={callback:a}),typeof b!="undefined"&&(a.queue=b),m.busy()?m.pushQueue(a):m.fireQueueItem(a),m},m.clearQueue=function(){return m.busy.flag=!1,m.queues=[],m},m.stateChanged=!1,m.doubleChecker=!1,m.doubleCheckComplete=function(){return m.stateChanged=!0,m.doubleCheckClear(),m},m.doubleCheckClear=function(){return m.doubleChecker&&(h(m.doubleChecker),m.doubleChecker=!1),m},m.doubleCheck=function(a){return m.stateChanged=!1,m.doubleCheckClear(),m.bugs.ieDoubleCheck&&(m.doubleChecker=g(function(){return m.doubleCheckClear(),m.stateChanged||a(),!0},m.options.doubleCheckInterval)),m},m.safariStatePoll=function(){var b=m.extractState(d.location.href),c;if(!m.isLastSavedState(b))c=b;else return;return c||(c=m.createStateObject()),m.Adapter.trigger(a,"popstate"),m},m.back=function(a){return a!==!1&&m.busy()?(m.pushQueue({scope:m,callback:m.back,args:arguments,queue:a}),!1):(m.busy(!0),m.doubleCheck(function(){m.back(!1)}),n.go(-1),!0)},m.forward=function(a){return a!==!1&&m.busy()?(m.pushQueue({scope:m,callback:m.forward,args:arguments,queue:a}),!1):(m.busy(!0),m.doubleCheck(function(){m.forward(!1)}),n.go(1),!0)},m.go=function(a,b){var c;if(a>0)for(c=1;c<=a;++c)m.forward(b);else{if(!(a<0))throw new Error("History.go: History.go requires a positive or negative integer passed.");for(c=-1;c>=a;--c)m.back(b)}return m};if(m.emulated.pushState){var o=function(){};m.pushState=m.pushState||o,m.replaceState=m.replaceState||o}else m.onPopState=function(b,c){var e=!1,f=!1,g,h;return m.doubleCheckComplete(),g=m.getHash(),g?(h=m.extractState(g||d.location.href,!0),h?m.replaceState(h.data,h.title,h.url,!1):(m.Adapter.trigger(a,"anchorchange"),m.busy(!1)),m.expectedStateId=!1,!1):(e=m.Adapter.extractEventData("state",b,c)||!1,e?f=m.getStateById(e):m.expectedStateId?f=m.getStateById(m.expectedStateId):f=m.extractState(d.location.href),f||(f=m.createStateObject(null,null,d.location.href)),m.expectedStateId=!1,m.isLastSavedState(f)?(m.busy(!1),!1):(m.storeState(f),m.saveState(f),m.setTitle(f),m.Adapter.trigger(a,"statechange"),m.busy(!1),!0))},m.Adapter.bind(a,"popstate",m.onPopState),m.pushState=function(b,c,d,e){if(m.getHashByUrl(d)&&m.emulated.pushState)throw new Error("History.js does not support states with fragement-identifiers (hashes/anchors).");if(e!==!1&&m.busy())return m.pushQueue({scope:m,callback:m.pushState,args:arguments,queue:e}),!1;m.busy(!0);var f=m.createStateObject(b,c,d);return m.isLastSavedState(f)?m.busy(!1):(m.storeState(f),m.expectedStateId=f.id,n.pushState(f.id,f.title,f.url),m.Adapter.trigger(a,"popstate")),!0},m.replaceState=function(b,c,d,e){if(m.getHashByUrl(d)&&m.emulated.pushState)throw new Error("History.js does not support states with fragement-identifiers (hashes/anchors).");if(e!==!1&&m.busy())return m.pushQueue({scope:m,callback:m.replaceState,args:arguments,queue:e}),!1;m.busy(!0);var f=m.createStateObject(b,c,d);return m.isLastSavedState(f)?m.busy(!1):(m.storeState(f),m.expectedStateId=f.id,n.replaceState(f.id,f.title,f.url),m.Adapter.trigger(a,"popstate")),!0};if(f){try{m.store=k.parse(f.getItem("History.store"))||{}}catch(p){m.store={}}m.normalizeStore()}else m.store={},m.normalizeStore();m.Adapter.bind(a,"beforeunload",m.clearAllIntervals),m.Adapter.bind(a,"unload",m.clearAllIntervals),m.saveState(m.storeState(m.extractState(d.location.href,!0))),f&&(m.onUnload=function(){var a,b;try{a=k.parse(f.getItem("History.store"))||{}}catch(c){a={}}a.idToState=a.idToState||{},a.urlToId=a.urlToId||{},a.stateToId=a.stateToId||{};for(b in m.idToState){if(!m.idToState.hasOwnProperty(b))continue;a.idToState[b]=m.idToState[b]}for(b in m.urlToId){if(!m.urlToId.hasOwnProperty(b))continue;a.urlToId[b]=m.urlToId[b]}for(b in m.stateToId){if(!m.stateToId.hasOwnProperty(b))continue;a.stateToId[b]=m.stateToId[b]}m.store=a,m.normalizeStore(),f.setItem("History.store",k.stringify(a))},m.intervalList.push(i(m.onUnload,m.options.storeInterval)),m.Adapter.bind(a,"beforeunload",m.onUnload),m.Adapter.bind(a,"unload",m.onUnload));if(!m.emulated.pushState){m.bugs.safariPoll&&m.intervalList.push(i(m.safariStatePoll,m.options.safariPollInterval));if(e.vendor==="Apple Computer, Inc."||(e.appCodeName||"")==="Mozilla")m.Adapter.bind(a,"hashchange",function(){m.Adapter.trigger(a,"popstate")}),m.getHash()&&m.Adapter.onDomLoad(function(){m.Adapter.trigger(a,"hashchange")})}},m.init()}(window);
/* /History */

/* ETU SOZLUK KISMI
* @version 0.5
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
		
		function fS() {
			$("#sharebox").remove();
			$("#yazarminiinfo").remove();
			$("#fbtw").remove();
			$("#yi").remove();
			$("textarea").css({resize: "none"});	
		};
		
		function boslukSil(a) {
			var baslik = a.replace(/ /g,"+");
			return baslik;
		};
		
		function ejf(o) {
			var a = o.replace(/\\\//g,"/");
			return a;
		};
		
		function ep( s,w,h ) {
		if (!h) h = 200;
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
		
		function eS(link) {
			var loading = 'Başlık yükleniyor... <br/><img src="img/1.gif">';
			$e = $('#entries');
			$e.empty().append(loading);
			$.ajaxQueue({
				url: link,
				dataType: "json",
				success: function(data) {
					History.pushState(null,"etüsözlük - " + data.baslik,unescape(decodeURIComponent(link)));
					$e.empty();
					if (data.code) { //hata döndüyse
						$e.append("<i>"+data.message+"</i>");
					}
					else if (data.log) { //hata yoksa
						$e.append('<h3 style="text-align:left; margin-left:40px;">'+data.baslik+'</h3><input type="hidden" value="'+data.baslik+'" id="baslikd" />');
						
						/* üst kısım sayfalama */
						if (Number(data.ts)>1) {
							$e.append('<div id="sayfalar" style="position:absolute;right:0;top:0;font-size:8pt;"></div>');
							if (Number(data.p)>1) {
								syf = Number(data.p)-1;
								$e.find('#sayfalar:first').append('<a href="'+data.url+'&p='+syf+'">&lt;&lt</a>');
							}
							$e.find('#sayfalar:first').append('<select class="sayfa" style="font-size:8pt;" onChange="location.href=\''+data.url+'&p=\'+(this.selectedIndex+1)" name="p">'); 
							for (i=1;i<=Number(data.ts);i++) {
								if (i==Number(data.p))
									ekle = " selected";
								else
									ekle = "";
								$e.find('#sayfalar:first .sayfa:last').append('<option value="'+i+'"'+ekle+'>'+i+'</option>');
							}
							$e.find('#sayfalar:first').append('/<a href="'+data.url+'&p='+data.ts+'">'+data.ts+'</a>');
							if (data.p != data.ts) {
								syf = Number(data.p)+1;
								$e.find('#sayfalar:first').append('<a href="'+data.url+'&p='+syf+'">&gt;&gt</a>');
							}
						}
						
						/* /üst kısım sayfalama*/
						
						$e.append('<ol class="girdiler">');
						/* Entry Göster */
							$.each(data.girdiler, function(i,g) {
								$e.find('ol:first').append('<li class="girdi" value="'+g.listnumber+'">');
								$el = $e.find('li:last');
								
								$el.append(ejf(g.girdi));
								$el.append('<div class="yazarinfo">(<a href="goster.php?t='+boslukSil(g.nick)+'" id="yazar" rel="'+g.id+'">'+g.nick+'</a>, '+g.tarih.substr(0,16)+''+g.duzen+')</div><div class="ymore"><a href="#" id="entryid">#'+g.eid+'</a></div>');
								
								$ely = $el.find('.ymore');
								/* butonlar */
									if (g.iyuf==1 && g.iih==1) {
										$ely.append('&nbsp;<button type="button" onClick="ep(\'vote.php?id='+g.eid+'&o=1\')" class="minib" title="olmuş bu" id="+1">iyuf</button>&nbsp;<button type="button" onClick="ep(\'vote.php?id='+g.eid+'&o=-1\')" class="minib" title="böyle olmaz hacı" id="-1">ı ıh</button>');
									}
									if (g.duzenle==1) {
										$ely.append('&nbsp;<button type="button" onClick="ep(\'edit.php?e='+g.eid+'\',\'820\',\'400\')" class="minib" id="eduz">düzelt</button>');
									}
									if (g.sil==1) {
										$ely.append('&nbsp;<button type="button" onClick="ep(\'del.php?e='+g.eid+'\')" class="minib" title="sil" id="esil">X</button>');
									}
									if (g.favori==1) {
										$ely.append('&nbsp;<button type="button" onClick="ep(\'fav.php?e='+g.eid+'\')" class="minib" title="favorilere ekle" id="efav">:D</button>');
									}
									if (g.mesaj==1) {
										$ely.append('&nbsp;<button type="button" onClick="ep(\'mesaj.php?y='+boslukSil(g.nick)+'\',\'600\',\'400\')" class="minib" title="yazara mesaj atiyim" id="eymesaj">msj</button>');
									}
									$ely.append('&nbsp;<button type="button" onClick="location.href=\'yazar.php?y='+boslukSil(g.nick)+'\'" id="eyh" class="minib" title="yazar hakkında">?</button>&nbsp;<button type="button" onClick="location.href=\'sikayet.php?e='+g.eid+'\'" id="esb" class="minib" title="şikayet et">!</button>');
									$el.append('&nbsp;<div id="yazarmini"></div>');
								/* /butonlar */
								$el.append('<br />');	
					
							});
						/* /Entry Göster */
						$e.append('<br />');
						
						/* alt kısım sayfalama*/
						if (Number(data.ts)>1) {
							$e.append('<div id="sayfalar" style="position:absolute;right:0;font-size:8pt;"></div>');
							if (Number(data.p)>1) {
								syf = Number(data.p)-1;
								$e.find('#sayfalar:last').append('<a href="'+data.url+'&p='+syf+'">&lt;&lt</a>');
							}
							$e.find('#sayfalar:last').append('<select class="sayfa" style="font-size:8pt;" onChange="location.href=\''+data.url+'&p=\'+(this.selectedIndex+1)" name="p">');
							for (i=1;i<=Number(data.ts);i++) {
								if (i==Number(data.p))
									ekle = " selected";
								else
									ekle = "";
								$e.find('.sayfa:last').append('<option value="'+i+'"'+ekle+'>'+i+'</option>');
							}
							$e.find('#sayfalar:last').append('/<a href="'+data.url+'&p='+data.ts+'">'+data.ts+'</a>');
							if (data.p != data.ts) {
								syf = Number(data.p)+1;
								$e.find('#sayfalar:last').append('<a href="'+data.url+'&p='+syf+'">&gt;&gt</a>');
							}
						}
						/* /alt kısım sayfalama */
					}
					$e.append('<div style="text-align:center;" id="hg"></div>');
					
					if (Number(data.hgj)==1 && data.baslik!="") {
						$('#hg').append('<button type="button" rel="goster.php?t='+boslukSil(data.baslik)+'" id="ehg">Hepsi Gelsin</button>');
					}
					if (Number(data.log)==1 && (data.baslik!="" || $('#titlea').val()!="")) {
						baslik = data.baslik?data.baslik:$('#titlea').val();
						$("#hg").append('<div style="text-align:left; padding-top:10px; padding-left:25px;">"'+baslik+'" hakkında söylemek istediklerim var diyorsan hadi durma:	<form action="ekle.php" method="post" id="yenigirdi" name="yenigirdi"><input type="hidden" name="t" value="'+baslik+'" /><div id="butonlar" style="text-align:left; width:100%; padding-top:10px;"><input type="button" id="bkz" value="(bkz: )" class="ebut" /><input type="button" id="gizlibkz" value="``" class="ebut"/><input type="button" id="spoiler" value="spoiler" class="ebut"/><input type="button" value="link" onclick="var a=prompt(\'link: (başında http:// olmalı)\', \'http://\');if(isURL(a))$(\'#entrytextarea\').tae(\'url\',a);" class="ebut"/></div><textarea id="entrytextarea" rows="10" cols="105" class="ygirdi" name="ygirdi"></textarea><input type="submit" value="böyle olur" class="ebut" /><input type="submit" value="bunu sonra gönderirim" class="ebut" name="kaydet" /></form></div>');
					}
					fS();
				},
				error: function (request, status, error) {
					//alert(request.responseText);
					$("#entries").empty().append('Hata oluştu lütfen tekrar deneyin.');
				}
			});
		};

		function gungetir(gun, p,rel) {
			var loading = 'Başlıklar yükleniyor... <br /><img src="img/1.gif">';
			var uza='';
			var durum='';
			var yazi='';
			
			if (gun == "dun") {
			  uza = "g=dun";
			  durum = "&g=d";
			  yazi = "dün yazılanlar:";
			}
			else if(isFinite(gun) && $.isNumeric(gun) && gun.length == 8) {
			  uza= "g=gun&gun="+gun.substr(0,2)+"&ay="+gun.substr(2,2)+"&yil="+gun.substr(4);
			  durum="&g="+gun.substr(4)+""+gun.substr(2,2)+""+gun.substr(0,2); //yıl-ay-gün
			  yazi = gun.substr(0,2)+"."+gun.substr(2,2)+"."+gun.substr(4)+" yazılanlar:";
			}
			else if(gun == "yazar") {
			  uza ="y="+rel;
			  durum = "/@"+boslukSil(rel);
			  var t = rel.replace(/\+/g," ");
			  t = unescape(decodeURIComponent(t));
			  yazi = t + "'in son yazdıkları";
			}
			else {
			  uza = "g=bugun";
			  durum ="&g=bg";
			  yazi = "bugün yazılanlar:";
			}
			  
			$("#basliklar").empty().append(loading);
			$.ajaxQueue({
				url: "data/baslik.php",
				data: uza + "&page=" + p,
				dataType: "json",
				success: function(JSON) {
					if (!JSON.items[0]) {
						$("#basliklar").empty().append('Bir şey yazılmamış');
					} else {
					$("#basliklar").empty();
					$("#basliklar").append('<font style="font-size:8pt;">&nbsp;&nbsp;&nbsp;'+yazi+'</font>');
					$("#basliklar").append('<ul class="b">');
					$s = $("#basliklar").find('ul');
					$.each(JSON.items, function(i,item) {
						if (item.hata)
							$("#basliklar").empty().append(item.hata);
						else {
							var sayi='';
							if (item.count>1)
							sayi='('+item.count+')';
							$s.append('<li class="b">-&nbsp;<a href=goster.php?t='+boslukSil(item.baslik)+''+durum+'>'+item.baslik+'</a> '+sayi+'</li>');
						}
					});
					if (rel!=null)
						var c = getcount(gun,rel);
					else
						var c = getcount(gun);
					$("#page_count").val(Math.ceil(c / 50));
					generateRows(i,gun);
					}
				},

					error: function (request, status, error) {
						//alert(request.responseText);
						$("#basliklar").empty().append('Hata oluştu lütfen tekrar deneyin.');
					}
				});
		};
		
		function rastgetir() {
			var loading = 'Başlıklar yükleniyor... <br /><img src="img/1.gif">';
			var uza='';
			
			$("#paginator").remove();
			$("#basliklar").empty().append(loading);
			$.ajaxQueue({
				url: "data/baslik.php",
				data: "rast=1",
				dataType: "json",
				success: function(JSON) {
					if (!JSON.items[0]) {
						$("#basliklar").empty().append('Bir şey yazılmamış');
					} else {
					$("#basliklar").empty();
					$("#basliklar").append('<font style="font-size:8pt;">&nbsp;&nbsp;&nbsp;Rastgele 50 başlık:</font>');
					$("#basliklar").append('<ul class="b">');
					$s = $("#basliklar").find('ul');
					$.each(JSON.items, function(i,item) {
						if (item.hata)
							$("#basliklar").empty().append(item.hata);
						else {
							var sayi='';
							if (item.count>1)
							sayi='('+item.count+')';
							$("#basliklar").find('ul').append('<li class="b">-&nbsp;<a href=goster.php?t='+boslukSil(item.baslik)+'>'+item.baslik+'</a> '+sayi+'</li>');
						}
					});
					}
				},

					error: function (request, status, error) {
						//alert(request.responseText);
						$("#basliklar").empty().append('Hata oluştu lütfen tekrar deneyin.');
					}
				});
		};
		
		function getcount(opt,rel) {
			var count = 0;
			var uza = null;
			if (opt == "dun")
			  uza = "?say=dun";
			else if(isFinite(opt) && $.isNumeric(opt) && opt.length == 8)
			  uza= "?say=gun&gun="+opt.substr(0,2)+"&ay="+opt.substr(2,2)+"&yil="+opt.substr(4);
			else if(opt == "yazar")
			  uza ="?say=yazar&y="+rel;
			else 
			  uza = "?say=bugun";
			$.ajaxQueue({
			  url: "count.php" +uza,
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
			fS();
			var bilgi="";
			$.post("yazarmini.php", {yid:id}, function(data) {
				bilgi += '<div id="yazarminiinfo">' + data + '<div class="kapat"></div></div>';
				$("body").append(bilgi);
				$('body').append('<div id="yi"><script type=text/javascript>$("#sonyaz").click(function() { $cur = $(this);	gungetir("yazar",1,$cur.attr("rel"));});</script></div>');
				$("#yazarminiinfo").css({top: posY-30+"px", left: posX-260+"px"});
				$("#yazarminiinfo").delay(200).fadeIn(300);
				$(".kapat").click(function() { $("#yazarminiinfo").fadeOut(300, function() { $("#yazarminiinfo").remove() ;}) });
				$("#yazarminiinfo").click(function(e){ e.stopPropagation(); });
				$(document).one("click", function() { $("#yazarminiinfo").fadeOut(300, function() { $("#yazarminiinfo").remove() ;}); });
			});
		};
		
		function sharebox(eid,posX,posY) {
			fS();
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
		
		var ac_config = {
				minLength:2,
				delay:750,
				appendTo: '#basara',
				source: function(request,response) {
					$.post("ara.php",{t:request.term}, function(data) {
						var x = false;
						$('#basara').addClass('basara').empty();
						$.each(data, function(i,item) {
							if (item.Baslik!=null) {
								if (!x) x = true;
								$('#basara').append('<a href="goster.php?t='+boslukSil(item.Baslik)+'" id="baslikaraid">'+item.Baslik+'</a><br />');
							}
						});
						if (x)
							$('#basara').show();
						$('#baslikaraid').click(function(e) {
							e.preventDefault();
							$('#basara').fadeOut(250);
							$('#titlea').val($(this).text());
							sayfa = $(this).attr("href");
							eS(sayfa);														
						});
						if ($('#basara').css('display')!='none') {
							$(document).one("click", function() { $("#basara").fadeOut(250); });
							$("#basara").click(function(e){ e.stopPropagation(); });
						}
					}, "json");
				}
		};
	
	function getGubi() {
		if ($(".sikayetmenu").length) return;
		if ($("#uyeol").text()!=="Ben") return;
		$.ajaxQueue({
			url : "gubidik.php",
			cache : true,
			success : function(data) {
				if (!data) return;
				$("body").append('<div class="sikayetmenupanel"></div><a class="sikayetmenu" href="#">Gubidik</a>');
				$(".sikayetmenupanel").html(data);
			}
		});
	};
		
	$(document).on("click",'a[href*="goster.php?"]', function(e) {
			e.preventDefault();
			eS($(this).attr("href"));
	});
	$(document).on("mouseenter",'.girdi',function() {
		$(this).find(".ymore").show();
	});
	$(document).on("mouseleave",'.girdi',function() {
		$(this).find(".ymore").hide();
	});
	$(document).on("click","li #entryid",function(e) {
		e.preventDefault();
		e.stopPropagation();
		sharebox($(this).text().substr(1),$(this).offset().left,$(this).offset().top);
	});
	var ytimer;
	$(document).on("mouseenter","li #yazar",function() {
		$cur = $(this);
		ytimer = setTimeout(function() {yazarinfo($cur.attr("rel"),$cur.offset().left,$cur.offset().top);},500);
	});
	$(document).on("mouseleave","li #yazar",function() { clearTimeout(ytimer); });
	$(document).on("click","#ehg",function() {
			sayfa = $(this).attr("rel");
			eS(sayfa);
	});	
	$(document).on("click","#bkz",function() {
		$("#entrytextarea").tae("bkz");
	});
	$(document).on("click","#gizlibkz",function() {
		$("#entrytextarea").tae("gizli");
	});
	$(document).on("click","#spoiler",function() {
		$("#entrytextarea").tae("spoiler");
	});
	$(document).on("click", ".sikayetmenu",function() {
		if ($(".aramenupanel:visible").length>0) {
			$(".aramenupanel").hide();
			$(".aramenu").toggleClass("active");
		}
			
		$(".sikayetmenupanel").toggle(0);
		$(this).toggleClass("active");
		return false;
	});
	
	$(document).on("click",".spoyl",function() {
		$o = $(this).parent();
		$o.toggleClass("spylr").find("#spoyler").toggle(0);
	});
		
	$(document).ready(function() {
		if ($("#uyeol").text()==="Ben" && $(".sikayetmenu").length === 0) getGubi(); //ne olur ne olmaz kontrol ediyorum.
		var c = getcount(0);
		gungetir(0,1);
		$("#page_count").val(Math.ceil(c / 50));
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
			$("#page_count").val(Math.ceil(c / 50));
			gungetir(0,1);
			generateRows(1,0);
		});
			
		$("#dun").click(function() {
			$("#page_count").val(Math.ceil(c / 50));
			gungetir("dun",1);
			generateRows(1,"dun");
		});
			
		$("#rastgele").click(function() {
			rastgetir();
		});
			
		$("#titlea").click(function() {
			if ($("#titlea").val() === "" || $("#titlea").val() === "Başlık Getir") 
				$("#titlea").focus().val("");
		});
			
		$("#titlea").blur(function() {
			if ($("#titlea").val() === "" || $("#titlea").val() === "Başlık Getir") 
				$(this).val("Başlık Getir");
		});
			
		$('#top-link').btt({ min:300, fadeSpeed:500 });
		$('#top-link').click(function(e) {
			e.preventDefault();
			$.scrollTo(0,300);
		});
			
		$('form[name="loginform"]').submit(function(e) {
			e.preventDefault();
			$('input[name="login"]').attr("disabled","disabled");
			$('input[name="login"]').addClass('disabled');
			setTimeout('enBu()', 5000);
			$.ajaxQueue({
				url: "login.php",
				dataType: "json",
				data: "login&"+$(this).serialize(),
				success: function(data) {
					if (data.durum) {
						$("#uyeol").find("span").text('Ben');
						$("#loginbox").empty();
						$("#loginbox").append('<p class="lgbaslik">' + data.nick + '</p><hr class="lg"/><p style="text-align:left; padding-left:50px; margin:0;"><a href="hq.php">HQ</a><br /><a href="mesaj.php">Mesajlar</a><br /><a href="getir.php?mode=ark">Arkadaşlar</a><br /><a href="getir.php?mode=kenar">Kenarda Duranlar</a><br /><a href="getir.php?mode=yeni">Yeni</a><br /><a href="login.php?logout">Çıkış</a></p>');
						getGubi();
						//if (location.pathname.toString().substr(14).match("^goster.php")=="goster.php") //bu daha güvenli gibi şu an localhost ve klasör ismi olduğunda kullanamıyorum.
						if (location.pathname.toString().indexOf("/goster.php")!=-1)
							eS(location.pathname+location.search);
						if (location.pathname.toString().indexOf("/index.php")!=-1 && $('li #entryid').text()!="" )
							eS("goster.php?e="+$('li #entryid').text().substr(1));
					}
					else if(data.code) {
						$('#hata').remove();
						$('#loginbox').append('<p id="hata" style="color:#fecc00;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><strong>Hata: </strong>'+data.message+'</p>');
					}
				}
			});
		});
		
		$('#titlea').autocomplete(ac_config);
	});

/* ETU SOZLUK KISMI BITIS */
