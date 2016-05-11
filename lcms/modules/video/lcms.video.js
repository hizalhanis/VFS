
var video = new function(){

	this.input = [];
	
	this.init = function(){

	};
		
	this.bind = function(){
		var that = this;
		$('button.lcms-video-save').live('click', function(){ that.save(); });
		$('button.lcms-video-save-publish').live('click', function(){ that.savePublish(); });
		$('button.lcms-video-update').live('click', function(){ that.update(); });
		$('button.lcms-video-update-publish').live('click', function(){ that.updatePublish(); });

		$('button.lcms-video-discard').live('click', function(){ that.discard(); });
		$('button.lcms-video-discard-update').live('click', function(){ that.discardUpdate(); });
		
		this.editForm	= $('div.lcms-video-edit').clone();
		this.newForm 	= $('div.lcms-video-new').clone();
	};
	
	this.inputs = function(key){
		
		this.input['class'] 	= $(this.el).find('input.lcms-video-class').val();
		this.input['content']	= $(this.el).find('input.lcms-video-embed').val();
		this.input['option']	= $(this.el).find('select.lcms-video-option').val();
		
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
		
		$(this.el).find('input.lcms-video-class').val(data.class);
		$(this.el).find('select.lcms-video-option').val(data.options);
		$(this.el).find('input.lcms-video-embed').val(data.content);
		
	};
	
	this.save = function(){
	

		var content 	= this.input['content'];
		var options 	= this.input['option'];
		var css_class	= this.input['class'];
		var callback	= function() { lcmsClearPane() };


		lcmsSave(content, options, css_class, callback);	
	};
	
	this.savePublish = function(){
	
		var content 	= this.input['content'];
		var options 	= this.input['option'];
		var css_class	= this.input['class'];
		var callback	= function() { lcmsClearPane() };
		
		lcmsSavePublish(content, options, css_class, callback);	
	};
	
	this.update = function(){

		var content 	= this.input['content'];
		var options 	= this.input['option'];
		var css_class	= this.input['class'];
		var callback	= function() { lcmsClearPane() };
				
		lcmsUpdate(this.id, content, options, css_class, callback);
	};
	
	this.updatePublish = function(){
	
		var content 	= this.input['content'];
		var options 	= this.input['option'];
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