$(document).ready(function(){

	$('.numeric').keypress(function(e) {
		if (!(e.which == 8 || e.which == 37 ||e.which == 39 || e.which == 45 || e.which == 46 || (e.which > 47 && e.which < 58))) return false;
	});

});

function checkemail(value){
	reg = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;
	if (value!='' && !value.match(reg)) {return false;} else {return true;}
}

function setCookie(name, value, expires, path, domain, secure) {
	document.cookie = name + '=' + escape(value) +
		((expires) ? '; expires=' + expires : '') +
		((path) ? '; path=' + path : '') +
		((domain) ? '; domain=' + domain : '') +
		((secure) ? '; secure' : '');
}

function getCookie(name) {
	var matches = document.cookie.match(new RegExp('(?:^|; )' + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + '=([^;]*)'));
	return matches ? decodeURIComponent(matches[1]) : undefined;
}

function elasticArea(elaEle) {
	$(elaEle).each(function(index, element) {
		var elasticElement = element,
		$elasticElement = $(element),
		initialHeight = initialHeight || $elasticElement.height(),
		delta = parseInt( $elasticElement.css('paddingBottom') ) + parseInt( $elasticElement.css('paddingTop') ) || 0,
		resize = function() {
			$elasticElement.height(initialHeight);
			$elasticElement.height( elasticElement.scrollHeight - delta );
		};
		$elasticElement.on('input change keyup', resize);
		resize();
	});
}

function showError(errorText) {
	swal({
		title: 'Erreur!',
		text: errorText,
		type: 'error',
		confirmButtonColor: '#348fe2',
		confirmButtonText: 'Fermer'
	});
}

// Временные функции
function dump(obj) {
	var out = "";
	if(obj && typeof(obj) == "object"){
		for (var i in obj) {
			out += i + ": " + obj[i] + "n";
		}
	} else {
		out = obj;
	}
	alert(out);
}