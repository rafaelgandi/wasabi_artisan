/* 
	Utility module
 */
navigator.define('Util', function ($, undefined) {
	var $root = $(document);
	function hasIssues (_$form, _all) {
		var fail = false,
			t = $.trim;
		_all = _all || false;
		var $fields = _$form.find('input, select, textarea').filter('.required, .number, .required_if_has_value');
		if (_all == false) {
			$fields = $fields.filter(':visible')
		}
		$fields.removeClass('issue').each(function () {
			var $field = $(this),
				v = t($field.val());
			// LM: 02-10-2015	
			// Checking for radio elements. Checkboxes by nature should always be optional. //	
			if ($field.is(':radio')) {
				var name = Uwa.t($field.attr('name')),
					$elem;
				if (name) {
					$elem = $('input[name="'+name+'"]').filter(':checked');
					if (! $elem.length) {
						v = '';						
					}	
				}					
			}
			if (v == '' || parseInt(v, 10) == 0) {
				fail = true;				
				$field.addClass('issue');					
			}
			else {
				if ($field.hasClass('email')) {
					if (! Uwa.isEmail(v)) {
						fail = true;
						$field.addClass('issue');
					}
				}
				else if ($field.hasClass('number')) {
					if (! Uwa.isNumeric(v)) {
						fail = true;
						$field.addClass('issue');
					}
				}
			}	
		});
		return fail;
	}
	
	return {		
		root: $root,		
		hasIssues: hasIssues,
		t: $.trim,	
		Image: {
			isValid: function (_filename) {
				var imgExt = 'png,jpg,jpeg,gif'.split(','),
					ext = Uwa.t(_filename).toLowerCase().split('.').pop();
				if ($.inArray(ext, imgExt) === -1) { return false; }	
				return true;
			}
		},
		
		pint: function (_str) {
			var num = parseInt(_str, 10);
			if (isNaN(num)) { return 0; }
			return num;
		},
		
		isEmail: function (_email) {
			var emailRegExp = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
				e = $.trim(_email);
			if (e.length > 50) { return false }
			return !! emailRegExp.test(e);
		},
		
		isNumeric: function (_num) {
			var n = _num;
			// See: http://stackoverflow.com/a/1830844
			return !isNaN(parseFloat(n)) && isFinite(n);
		},
		
		log: function (_log, _method) {
			// See: http://www.paulirish.com/2009/log-a-lightweight-wrapper-for-consolelog/
			_method = _method || 'log';
			if (self.console) { 
				console[_method](_log);
			}	
		},
		
		sc: (function () {
			// Selector Cacher //
			var elems = {};
			return function (_selector) {
				if (! elems[_selector]) {
					elems[_selector] = $(_selector);
				}
				return elems[_selector];
			};
		})(),
		
		unescapeUnicode: function (_str) {
			// See: http://stackoverflow.com/questions/7885096/how-do-i-decode-a-string-with-escaped-unicode
			var r = /\\u([\d\w]{4})/gi;
			_str = (_str+'').replace(r, function (match, grp) {
				return String.fromCharCode(parseInt(grp, 16)); 
			});
			_str = unescape(_str);
			return _str;
		},
		
		css: function (_src, _callback) {
			$('head').append('<link rel="stylesheet" type="text/css" href="' + _src + '?'+(new Date()).getTime()+'">');
			// Cool technique to know when the stylesheet is loaded.
			// See: http://www.backalleycoder.com/2011/03/20/link-tag-css-stylesheet-load-event/
			var img = document.createElement('img');
			img.onerror = function () {
				if(_callback) { _callback(); }
			}
			img.src = _src;
		},
		
		tpl: function (s,d){
			// See: http://mir.aculo.us/2011/03/09/little-helpers-a-tweet-sized-javascript-templating-engine/	
			for(var p in d) {
				s=s.replace(new RegExp('{'+p+'}','g'), d[p]);
			}	   
			return s;
		}
	};
});