/*! LAB.js (LABjs :: Loading And Blocking JavaScript)
    v1.2.0 (c) Kyle Simpson
    MIT License
*/
;(function(p){var q="string",w="head",L="body",M="script",u="readyState",j="preloaddone",x="loadtrigger",N="srcuri",E="preload",Z="complete",y="done",z="which",O="preserve",F="onreadystatechange",ba="onload",P="hasOwnProperty",bb="script/cache",Q="[object ",bw=Q+"Function]",bx=Q+"Array]",e=null,h=true,i=false,k=p.document,bc=p.location,bd=p.ActiveXObject,A=p.setTimeout,be=p.clearTimeout,R=function(a){return k.getElementsByTagName(a)},S=Object.prototype.toString,G=function(){},r={},T={},bf=/^[^?#]*\//.exec(bc.href)[0],bg=/^\w+\:\/\/\/?[^\/]+/.exec(bf)[0],by=R(M),bh=p.opera&&S.call(p.opera)==Q+"Opera]",bi=("MozAppearance"in k.documentElement.style),bj=(k.createElement(M).async===true),v={cache:!(bi||bh),order:bi||bh||bj,xhr:h,dupe:h,base:"",which:w};v[O]=i;v[E]=h;r[w]=k.head||R(w);r[L]=R(L);function B(a){return S.call(a)===bw}function U(a,b){var c=/^\w+\:\/\//,d;if(typeof a!=q)a="";if(typeof b!=q)b="";d=((/^\/\//.test(a))?bc.protocol:"")+a;d=(c.test(d)?"":b)+d;return((c.test(d)?"":(d.charAt(0)==="/"?bg:bf))+d)}function bz(a){return(U(a).indexOf(bg)===0)}function bA(a){var b,c=-1;while(b=by[++c]){if(typeof b.src==q&&a===U(b.src)&&b.type!==bb)return h}return i}function H(t,l){t=!(!t);if(l==e)l=v;var bk=i,C=t&&l[E],bl=C&&l.cache,I=C&&l.order,bm=C&&l.xhr,bB=l[O],bC=l.which,bD=l.base,bn=G,J=i,D,s=h,m={},K=[],V=e;C=bl||bm||I;function bo(a,b){if((a[u]&&a[u]!==Z&&a[u]!=="loaded")||b[y]){return i}a[ba]=a[F]=e;return h}function W(a,b,c){c=!(!c);if(!c&&!(bo(a,b)))return;b[y]=h;for(var d in m){if(m[P](d)&&!(m[d][y]))return}bk=h;bn()}function bp(a){if(B(a[x])){a[x]();a[x]=e}}function bE(a,b){if(!bo(a,b))return;b[j]=h;A(function(){r[b[z]].removeChild(a);bp(b)},0)}function bF(a,b){if(a[u]===4){a[F]=G;b[j]=h;A(function(){bp(b)},0)}}function X(b,c,d,g,f,n){var o=b[z];A(function(){if("item"in r[o]){if(!r[o][0]){A(arguments.callee,25);return}r[o]=r[o][0]}var a=k.createElement(M);if(typeof d==q)a.type=d;if(typeof g==q)a.charset=g;if(B(f)){a[ba]=a[F]=function(){f(a,b)};a.src=c;if(bj){a.async=i}}r[o].insertBefore(a,(o===w?r[o].firstChild:e));if(typeof n==q){a.text=n;W(a,b,h)}},0)}function bq(a,b,c,d){T[a[N]]=h;X(a,b,c,d,W)}function br(a,b,c,d){var g=arguments;if(s&&a[j]==e){a[j]=i;X(a,b,bb,d,bE)}else if(!s&&a[j]!=e&&!a[j]){a[x]=function(){br.apply(e,g)}}else if(!s){bq.apply(e,g)}}function bs(a,b,c,d){var g=arguments,f;if(s&&a[j]==e){a[j]=i;f=a.xhr=(bd?new bd("Microsoft.XMLHTTP"):new p.XMLHttpRequest());f[F]=function(){bF(f,a)};f.open("GET",b);f.send("")}else if(!s&&a[j]!=e&&!a[j]){a[x]=function(){bs.apply(e,g)}}else if(!s){T[a[N]]=h;X(a,b,c,d,e,a.xhr.responseText);a.xhr=e}}function bt(a){if(typeof a=="undefined"||!a)return;if(a.allowDup==e)a.allowDup=l.dupe;var b=a.src,c=a.type,d=a.charset,g=a.allowDup,f=U(b,bD),n,o=bz(f);if(typeof d!=q)d=e;g=!(!g);if(!g&&((T[f]!=e)||(s&&m[f])||bA(f))){if(m[f]!=e&&m[f][j]&&!m[f][y]&&o){W(e,m[f],h)}return}if(m[f]==e)m[f]={};n=m[f];if(n[z]==e)n[z]=bC;n[y]=i;n[N]=f;J=h;if(!I&&bm&&o)bs(n,f,c,d);else if(!I&&bl)br(n,f,c,d);else bq(n,f,c,d)}function Y(a){if(t&&!I)K.push(a);if(!t||C)a()}function bu(a){var b=[],c;for(c=-1;++c<a.length;){if(S.call(a[c])===bx)b=b.concat(bu(a[c]));else b[b.length]=a[c]}return b}D={script:function(){be(V);var a=bu(arguments),b=D,c;if(bB){for(c=-1;++c<a.length;){if(B(a[c]))a[c]=a[c]();if(c===0){Y(function(){bt((typeof a[0]==q)?{src:a[0]}:a[0])})}else b=b.script(a[c]);b=b.wait()}}else{for(c=-1;++c<a.length;){if(B(a[c]))a[c]=a[c]()}Y(function(){for(c=-1;++c<a.length;){bt((typeof a[c]==q)?{src:a[c]}:a[c])}})}V=A(function(){s=i},5);return b},wait:function(a){be(V);s=i;if(!B(a))a=G;var b=H(t||J,l),c=b.trigger,d=function(){try{a()}catch(err){}c()};delete b.trigger;var g=function(){if(J&&!bk)bn=d;else d()};if(t&&!J)K.push(g);else Y(g);return b}};if(t){D.trigger=function(){var a,b=-1;while(a=K[++b])a();K=[]}}else D.trigger=G;return D}function bv(a){var b,c={},d={"UseCachePreload":"cache","UseLocalXHR":"xhr","UsePreloading":E,"AlwaysPreserveOrder":O,"AllowDuplicates":"dupe"},g={"AppendTo":z,"BasePath":"base"};for(b in d)g[b]=d[b];c.order=!(!v.order);for(b in g){if(g[P](b)&&v[g[b]]!=e)c[g[b]]=(a[b]!=e)?a[b]:v[g[b]]}for(b in d){if(d[P](b))c[d[b]]=!(!c[d[b]])}if(!c[E])c.cache=c.order=c.xhr=i;c.which=(c.which===w||c.which===L)?c.which:w;return c}p.$LAB={setGlobalDefaults:function(a){v=bv(a)},setOptions:function(a){return H(i,bv(a))},script:function(){return H().script.apply(e,arguments)},wait:function(){return H().wait.apply(e,arguments)}};(function(a,b,c){if(k[u]==e&&k[a]){k[u]="loading";k[a](b,c=function(){k.removeEventListener(b,c,i);k[u]=Z},i)}})("addEventListener","DOMContentLoaded")})(window);
/* 
	Boot Loader
		- inspired by basket.js
		- dependencies: jQuery and LAB.js
		- LM: 10-08-12
	@author: Rafael Gandionco
	@version: 2.0
	
	Links:
		- http://www.stevesouders.com/blog/2011/03/28/storager-case-study-bing-google/
		- http://addyosmani.github.com/basket.js/
*/
function Boot() {
	this.hasLocalStorage = !!localStorage; 
	var key = 'bootjs~',
		date = new Date();	
	
	this._log = function (_msg) {
		if (typeof console === 'undefined') { return; }
		console.log(_msg);
	};
	
	this._basename = function (_jsPath, _alsoQueryString) {
		var p = _jsPath.split('/'),
			qs = _alsoQueryString || false,
			forwardSlash = /\//g,
			tild = /~/g;
		if (qs && _jsPath.indexOf('?') > -1) {
			// Also remove the query string if it has one. //
			return jQuery.trim(p[(p.length-1)]).split('?')[0].replace(forwardSlash, '')
															 .replace(tild, '');
		}	
		return jQuery.trim(p[(p.length-1)]).replace(forwardSlash, '')
										   .replace(tild, '');
	};
	
	this._removeKeys = function (_key) {
		return _key.split('~')[1];
	};
	
	this._makeKey = function (_file) {
		return key + this._basename(_file) + '~' + date.getTime();
	};
	
	this._removeOldVersion = function (_jsPath) {
		var filename = this._basename(_jsPath),
			p, keyRegExp;
		// Only update version if has "boot" query string //	
		if (filename.indexOf('?') > -1 && filename.indexOf('boot=') > -1) {
			p = filename.split('?');
			keyRegExp = new RegExp('^'+key+p[0]);
			for (var prop in localStorage) {
				if (keyRegExp.test(prop)) {
					if (this._removeKeys(prop) === filename) { return true; }
					localStorage.removeItem(prop);
					return false;
				}
			}
		}
		return true;
	};
	
	this._getStoredItem = function (_file) {
		if (! localStorage.length) { return false; }
		var f = this._basename(_file, true),
			keyRegExp = new RegExp('^'+key+f);
		for (var prop in localStorage) {
			if (keyRegExp.test(prop)) {
				// Make sure that it is a  match //
				if (this._basename(this._removeKeys(prop), true) === f) {					
					return localStorage.getItem(prop); 
				}				
			}
		}		
		return false;
	};
	
	this._inCacheThenEval = function (_jsPath) {
		var cache = this._getStoredItem(_jsPath);
		if (cache !== false) { // Must compare to false here...
			if (this._removeOldVersion(_jsPath)) {
				jQuery.globalEval(cache);
				this._log(_jsPath+' has been loaded');
				return true;	
			}
		}
		return false;
	};
	
	this._makeCache = function (_js) {
		var that = this;
		setTimeout(function () {
			jQuery.get(_js, function (res) {
				try { localStorage.setItem(that._makeKey(_js), res); }
				catch(e) {
					// See: http://sunpig.com/martin/archives/2011/05/21/considerations-for-caching-resources-in-localstorage.html
					that._log('Can\'t store resource >>> '+e.message);
				}		
			}, 'text'); // make sure to get a text version
		}, 1e3);					
	};
}

Boot.prototype.load = function (_js, _callback, _useCache) {
	var js = jQuery.trim(_js),
		USE_CACHE = true,
		hollaback = function () {},
		that = this;
	if (jQuery.isFunction(_callback)) {
		USE_CACHE = (typeof _useCache === 'undefined') ? true : false;
		// See: https://developer.mozilla.org/en/JavaScript/Reference/Global_Objects/Function/call
		hollaback = function () {_callback.call(that, that);};
	}
	else {
		if (typeof _callback !== 'undefined') { USE_CACHE = !!_callback; }
	}
	
	if (this.hasLocalStorage && USE_CACHE) {
		if (this._inCacheThenEval(js)) { hollaback(); }
		else {
			$LAB.script(js).wait(hollaback);	
			this._makeCache(js);
		}		
	}
	else { $LAB.script(js).wait(hollaback); } // Load using $LAB js
	return this;
};

// Expire Stale Stored Data //
(function () {
	// Stored scripts expires in one month//
	if (! localStorage) { return; }; // Check if localStorage is available
	// See: http://googlecode.blogspot.com/2009/07/gmail-for-mobile-html5-series-using.html
	setTimeout(function () { // Check after 1 second.
		var date = new Date(),
			getTimestampFromKey = function (_key) {
				var p = _key.split('~');
				return parseInt(p[p.length-1], 10);
			},
			// Number of seconds in a month (31 days). 
			// See: http://wiki.answers.com/Q/How_many_seconds_in_the_month_of_January
			ONE_MONTH = 2678400,
			now = date.getTime(),
			sec;
		for (var prop in localStorage) {
			if (prop.indexOf('bootjs~') > -1) {
				sec = Math.ceil((now - getTimestampFromKey(prop)) / 1e3);
				if (sec >= ONE_MONTH) {
					(function (prop) {
						setTimeout(function () { localStorage.removeItem(prop); }, 0);
					})(prop);				
				}	
			}
		}
	}, 1e3);
})();