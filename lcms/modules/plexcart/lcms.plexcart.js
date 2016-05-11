
var plexcart = new function(){

	this.input = [];
	
	this.init = function(){

	};
		
	this.bind = function(){
		var that = this;
		$('button.lcms-plexcart-save').live('click', function(){ that.save(); });
		$('button.lcms-plexcart-save-publish').live('click', function(){ that.savePublish(); });
		$('button.lcms-plexcart-update').live('click', function(){ that.update(); });
		$('button.lcms-plexcart-update-publish').live('click', function(){ that.updatePublish(); });

		$('button.lcms-plexcart-discard').live('click', function(){ that.discard(); });
		$('button.lcms-plexcart-discard-update').live('click', function(){ that.discardUpdate(); });
		
		this.editForm	= $('div.lcms-plexcart-edit').clone();
		this.newForm 	= $('div.lcms-plexcart-new').clone();
	};
	
	this.inputs = function(key){
		this.input['api_url']	= $(this.el).find('input.lcms-plexcart-api-url').val();
		this.input['cdn_url'] = $(this.el).find('input.lcms-plexcart-cdn-url').val();
		this.input['api_key'] = $(this.el).find('input.lcms-plexcart-api-key').val();		
		this.input['ucc'] = $(this.el).find('input.lcms-plexcart-ucc').val();
		this.input['ccc'] = $(this.el).find('input.lcms-plexcart-ccc').val();
		
		return this.inputs[key];
	};
	
	this.reload = function(){
		var that = this;
	};
	
	this.add = function(el, page, location){
		this.el = el;
		$(this.el).prepend($(this.newForm).clone());
	};
	
	this.edit = function(el, data){
		this.el = el;
		this.id = data.id;
		eval('var opt = '+data.options);
		
		$(this.editForm).clone().prependTo(this.el);
		
		$(this.el).find('input.lcms-plexcart-api-url').val(opt.api_url);
		$(this.el).find('input.lcms-plexcart-cdn-url').val(opt.cdn_url);
		$(this.el).find('input.lcms-plexcart-api-key').val(opt.api_key);
		$(this.el).find('input.lcms-plexcart-ucc').val(opt.ucc);
		$(this.el).find('input.lcms-plexcart-ccc').val(opt.ccc);
	};
	
	this.save = function(){
		var opt = {};
		
		opt.api_url = this.input['api_url'];
		opt.cdn_url = this.input['cdn_url'];
		opt.api_key = this.input['api_key'];
		opt.ucc = this.input['ucc'];
		opt.ccc = this.input['ccc'];
	
		var content 	= '';
		var options 	= JSON.stringify(opt);
		var css_class	= '';
		var callback	= function() {};


		lcmsSave(content, options, css_class, callback);	
	};
	
	this.savePublish = function(){
		var opt = {};
		
		opt.api_url = this.input['api_url'];
		opt.cdn_url = this.input['cdn_url'];
		opt.api_key = this.input['api_key'];
		opt.ucc = this.input['ucc'];
		opt.ccc = this.input['ccc'];
	
		var content 	= '';
		var options 	= JSON.stringify(opt);
		var css_class	= '';
		var callback	= function() {};

		lcmsSavePublish(content, options, css_class, callback);	
	};
	
	this.update = function(){
		var opt = {};
		
		opt.api_url = this.input['api_url'];
		opt.cdn_url = this.input['cdn_url'];
		opt.api_key = this.input['api_key'];
		opt.ucc = this.input['ucc'];
		opt.ccc = this.input['ccc'];
	
		var content 	= '';
		var options 	= JSON.stringify(opt);
		var css_class	= '';
		var callback	= function() {};
				
		lcmsUpdate(this.id, content, options, css_class, callback);
	};
	
	this.updatePublish = function(){
		var opt = {};
		
		opt.api_url = this.input['api_url'];
		opt.cdn_url = this.input['cdn_url'];
		opt.api_key = this.input['api_key'];
		opt.ucc = this.input['ucc'];
		opt.ccc = this.input['ccc'];
	
		var content 	= '';
		var options 	= JSON.stringify(opt);
		var css_class	= '';
		var callback	= function() {};
				
		lcmsUpdatePublish(this.id, content, options, css_class, callback);
	}
		
	this.discard = function(){
		$(this.el).remove();
		lcmsDiscard();
	};
	
	this.discardUpdate = function(){
		lcmsDiscard();
	};

	this.init();	

}