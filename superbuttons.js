/* We have to use window load because document ready won't work with liquid canvas */
var mozborderAvailable = false;
var webkitborderAvailable = false;
var khtmlborderAvailable = false;
var borderAvailable = false;
var superCSS = '';

function getInternetExplorerVersion()
// Returns the version of Internet Explorer or a -1
// (indicating the use of another browser).
{
  var rv = -1; // Return value assumes failure.
  if (navigator.appName == 'Microsoft Internet Explorer')
  {
    var ua = navigator.userAgent;
    var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
    if (re.exec(ua) != null)
      rv = parseFloat( RegExp.$1 );
  }
  return rv;
}

var ieversion = getInternetExplorerVersion();

/**
 * Concatenates the values of a variable into an easily readable string
 * by Matt Hackett [scriptnode.com]
 * @param {Object} x The variable to debug
 * @param {Number} max The maximum number of recursions allowed (keep low, around 5 for HTML elements to prevent errors) [default: 10]
 * @param {String} sep The separator to use between [default: a single space ' ']
 * @param {Number} l The current level deep (amount of recursion). Do not use this parameter: it's for the function's own use
 */
function print_r(x, max, sep, l) {

	l = l || 0;
	max = max || 10;
	sep = sep || ' ';

	if (l > max) {
		return "[WARNING: Too much recursion]\n";
	}

	var
		i,
		r = '',
		t = typeof x,
		tab = '';

	if (x === null) {
		r += "(null)\n";
	} else if (t == 'object') {

		l++;

		for (i = 0; i < l; i++) {
			tab += sep;
		}

		if (x && x.length) {
			t = 'array';
		}

		r += '(' + t + ") :\n";

		for (i in x) {
			try {
				r += tab + '[' + i + '] : ' + print_r(x[i], max, sep, (l + 1));
			} catch(e) {
				return "[ERROR: " + e + "]\n";
			}
		}

	} else {

		if (t == 'string') {
			if (x == '') {
				x = '(empty)';
			}
		}

		r += '(' + t + ') ' + x + "\n";

	}

	return r;

};
var_dump = print_r;

function strstr (haystack, needle, bool) {
    // Finds first occurrence of a string within another  
    // 
    // version: 1003.2411
    // discuss at: http://phpjs.org/functions/strstr
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: Onno Marsman
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // *     example 1: strstr('Kevin van Zonneveld', 'van');
    // *     returns 1: 'van Zonneveld'
    // *     example 2: strstr('Kevin van Zonneveld', 'van', true);
    // *     returns 2: 'Kevin '
    // *     example 3: strstr('name@example.com', '@');
    // *     returns 3: '@example.com'
    // *     example 4: strstr('name@example.com', '@', true);
    // *     returns 4: 'name'
    var pos = 0;
    
    haystack += '';
    pos = haystack.indexOf( needle );
    if (pos == -1) {
        return false;
    } else{
        if (bool){
            return haystack.substr( 0, pos );
        } else{
            return haystack.slice( pos );
        }
    }
}

	hasNativeGradientSupport = false;
	hasNativeRadiusSupport = false;

	//Check to see if CSS Gradients are natively supported
	var div = document.createElement('div');
	div.style.cssText = [
		"background-image:-webkit-gradient(linear, 0% 0%, 0% 100%, from(red), to(blue));",
		"background-image:-moz-linear-gradient(top left, bottom right, from(red), to(blue));", /*Firefox 3.6 Alpha*/
		"background-image:-moz-linear-gradient(left, red, blue);" /*Firefox 3.6*/
	].join('');

	if(div.style.backgroundImage){
		hasNativeGradientSupport = true;
	}

	// Check for CSS border-radius support
	try {
		if (div.style.MozBorderRadius !== undefined)
			mozborderAvailable = true;
			hasNativeRadiusSupport = true;
	} catch(err) {}

	try {
		if (div.style.WebkitBorderRadius !== undefined)
			webkitborderAvailable = true;
			hasNativeRadiusSupport = true;
	} catch(err) {}

	try {
		if (div.style.KhtmlBorderRadius !== undefined)
			khtmlborderAvailable = true;
			hasNativeRadiusSupport = true;
	} catch(err) {}

	try {
		if (div.style.BorderRadius !== undefined)
			borderAvailable = true;
			hasNativeRadiusSupport = true;
	} catch(err) {}

	/*alert('ng: '+hasNativeGradientSupport+' nr:'+hasNativeRadiusSupport);*/

jQuery(window).load(function() {
	if (!hasNativeGradientSupport || !hasNativeRadiusSupport)
	{
		var i = 1;
		if (ieversion != -1 && ieversion < 8)
		{
			var max = 2;
		} else if (ieversion == 8)
		{
			var max = 5;
		} else {
			var max = 20;
		}

		jQuery('.superbutton').each(function() {
			if (i <= max)
			{
				var classList = [];
				var classList = jQuery(this).attr('class').split(' ');
				key = 0;
				bgCSS = [];
				jQuery.each( classList, function(index, cssColor){
					if ( strstr( cssColor, '#' ) ) {
						bgCSS[key] = cssColor;
						key = key +1;
					}
				});
				if (key < 2)
				{
					bgCSS[2] = bgCSS[0];
					bgCSS[3] = bgCSS[1]
				}
				if (key < 5)
				{
					bgCSS[4] = bgCSS[0];
					bgCSS[5] = bgCSS[1];
				}
				jQuery(this).bg(6, [[bgCSS[0], bgCSS[1]], [bgCSS[2], bgCSS[3]], [bgCSS[4], bgCSS[5]]]);
				jQuery(this).css('filter',''); // Remove IE filter (doesn't play well)
				i = i + 1;
			}	
		});
	}
});