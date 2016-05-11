
var categories = new function(){

	this.input = [];
	
	this.init = function(){

	};
		
	this.bind = function(){
		var that = this;
		$('button.lcms-categories-save').live('click', function(){ that.save(); });
		$('button.lcms-categories-save-publish').live('click', function(){ that.savePublish(); });
		$('button.lcms-categories-update').live('click', function(){ that.update(); });
		$('button.lcms-categories-update-publish').live('click', function(){ that.updatePublish(); });

		$('button.lcms-categories-discard').live('click', function(){ that.discard(); });
		$('button.lcms-categories-discard-update').live('click', function(){ that.discardUpdate(); });
		
		this.editForm	= $('div.lcms-categories-edit').clone();
		this.newForm 	= $('div.lcms-categories-new').clone();
	};
	
	this.inputs = function(key){
		
		this.input['class'] 	= $(this.el).find('input.lcms-categories-class').val();
		
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

		$(this.el).prepend($(this.editForm).clone());		
		
		$(this.el).find('input.lcms-categories-class').val(data.class);
	};
	
	this.save = function(){
	

		var content 	= this.input['content'];
		var options 	= '';
		var css_class	= this.input['class'];
		var callback	= function() { lcmsClearPane() };


		lcmsSave(content, options, css_class, callback);	
	};
	
	this.savePublish = function(){
	
		var content 	= this.input['content'];
		var options 	= '';
		var css_class	= this.input['class'];
		var callback	= function() { lcmsClearPane() };
		


		lcmsSavePublish(content, options, css_class, callback);	
	};
	
	this.update = function(){

		var content 	= this.input['content'];
		var options 	= '';
		var css_class	= this.input['class'];
		var callback	= function() { lcmsClearPane() };
				
		lcmsUpdate(this.id, content, options, css_class, callback);
	};
	
	this.updatePublish = function(){
	
		var content 	= this.input['content'];
		var options 	= '';
		var css_class	= this.input['class'];
		var callback	= function() { lcmsClearPane() };
				
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