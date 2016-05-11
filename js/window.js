var contentHeight = 0;

$(document).ready(function(){
	
	
	$(window).resize(function(){
		
		contentHeight = $(window).height() - ($('#header').outerHeight() + $('#nav').outerHeight() + $('#footer').outerHeight());		
		$('#main').css('height', contentHeight + 'px');
		
		var th = $('div.toolbar').outerHeight();
		$('div.content-scroll').height(contentHeight - th);
		
		$('div.questions-container').height(contentHeight - th - 39);
		
	});
	
	$('input.autocomplete-road').textboxlist({unique: true, max: 1, plugins: {autocomplete: {
		minLength: 1,
		queryRemote: true,
		onlyFromValues: true,
		remote: {url: base_url + 'cases/search_roads'}
	}}});
	
	$('input.autocomplete-user-single').textboxlist({unique: true, max: 1, plugins: {autocomplete: {
		minLength: 1,
		queryRemote: true,
		onlyFromValues: true,
		remote: {url: base_url + 'users/search'}
	}}});

	$('input.autocomplete-user-multiple').textboxlist({unique: true, max: 100, plugins: {autocomplete: {
		minLength: 1,
		queryRemote: true,
		onlyFromValues: true,
		remote: {url: base_url + 'users/search'}
	}}});

	
	setTimeout(function(){
		$(window).resize();
	}, 10);
	
	
	$('button.submit-btn').click(function(){
		var rel = $(this).attr('rel');
		
		
		$('#'+rel).submit();
	})
	
	$('input.time').timeEntry({spinnerImage: base_url+'images/spinnerDefault.png'});
	$('input.date').dateEntry({dateFormat: 'dmy/',spinnerImage: base_url+'images/spinnerDefault.png'});

	$('#dialog button').click(function(){
		$('#dialog-overlay').fadeOut(300);
	})
	
	
	
	$.widget( "custom.combobox", {
		_create: function() {
    		this.wrapper = $( "<span>" )
    			.addClass( "custom-combobox" )
    			.insertAfter( this.element );

    		this.element.hide();
    		this._createAutocomplete();
    		this._createShowAllButton();
    	},

    	_createAutocomplete: function() {
    		var selected = this.element.children( ":selected" ),
    			value = selected.val() ? selected.text() : "";

    		this.input = $( "<input>" )
    			.appendTo( this.wrapper )
    			.val( value )
    			.attr( "title", "" )
    			.addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
    			.autocomplete({
    				delay: 0,
    				minLength: 0,
    				source: $.proxy( this, "_source" )
    			})
    			.tooltip({
    				tooltipClass: "ui-state-highlight"
    			});

    		this._on( this.input, {
    			autocompleteselect: function( event, ui ) {
    				ui.item.option.selected = true;
    				this._trigger( "select", event, {
    					item: ui.item.option
    				});
    			},

    			autocompletechange: "_removeIfInvalid"
    		});
    	},

    	_createShowAllButton: function() {
    		var input = this.input,
    			wasOpen = false;

    		$( "<a>" )
    			.attr( "tabIndex", -1 )
    			.attr( "title", "Show All Items" )
    			.tooltip()
    			.appendTo( this.wrapper )
    			.button({
    				icons: {
    					primary: "ui-icon-triangle-1-s"
    				},
    				text: false
    			})
    			.removeClass( "ui-corner-all" )
    			.addClass( "custom-combobox-toggle ui-corner-right" )
    			.mousedown(function() {
    				wasOpen = input.autocomplete( "widget" ).is( ":visible" );
    			})
    			.click(function() {
    				input.focus();

    				// Close if already visible
    				if ( wasOpen ) {
    					return;
    				}

    				// Pass empty string as value to search for, displaying all results
    				input.autocomplete( "search", "" );
    			});
    	},

    	_source: function( request, response ) {
    		var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
    		response( this.element.children( "option" ).map(function() {
    			var text = $( this ).text();
    			if ( this.value && ( !request.term || matcher.test(text) ) )
    				return {
    					label: text,
    					value: text,
    					option: this
    				};
    		}) );
    	},

    	_removeIfInvalid: function( event, ui ) {

    		// Selected an item, nothing to do
    		if ( ui.item ) {
    			return;
    		}

    		// Search for a match (case-insensitive)
    		var value = this.input.val(),
    			valueLowerCase = value.toLowerCase(),
    			valid = false;
    		this.element.children( "option" ).each(function() {
    			if ( $( this ).text().toLowerCase() === valueLowerCase ) {
    				this.selected = valid = true;
    				return false;
    			}
    		});

    		// Found a match, nothing to do
    		if ( valid ) {
    			return;
    		}

    		// Remove invalid value
    		this.input
    			.val( "" )
    			.attr( "title", value + " didn't match any item" )
    			.tooltip( "open" );
    		this.element.val( "" );
    		this._delay(function() {
    			this.input.tooltip( "close" ).attr( "title", "" );
    		}, 2500 );
    		this.input.autocomplete( "instance" ).term = "";
    	},

    	_destroy: function() {
    		this.wrapper.remove();
    		this.element.show();
    	}
    });
    
    $('select.combobox').combobox();
	
})



function showDialog(title, text){
	$('#dialog .title').html(title);
	$('#dialog .text').html(text);
	$('#dialog-overlay').fadeIn(300);
}

function show(el){
	$(el).show();
	console.log($(el));
}

function hide(el){
	$(el).fadeOut();
}


function isArray(what) {
    return Object.prototype.toString.call(what) === '[object Array]';
}

function isObject(what) {
    return Object.prototype.toString.call(what) === '[object Object]';
}

function isString(what) {
    return Object.prototype.toString.call(what) === '[object String]';
}

function isJSON(what){
	try {
		eval('var testJSON = ' + what);
	} catch(e){
		return false;
	}
	return true;
}


function nl2br (str, is_xhtml) {
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}