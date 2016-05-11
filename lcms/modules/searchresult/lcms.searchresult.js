
var searchresult = new function(){

	this.input = [];
	
	this.init = function(){

	};
		
	this.bind = function(){
		var that = this;
		$('button.lcms-searchresult-save').live('click', function(){ that.save(); });
		$('button.lcms-searchresult-save-publish').live('click', function(){ that.savePublish(); });
		$('button.lcms-searchresult-update').live('click', function(){ that.update(); });
		$('button.lcms-searchresult-update-publish').live('click', function(){ that.updatePublish(); });

		$('button.lcms-searchresult-discard').live('click', function(){ that.discard(); });
		$('button.lcms-searchresult-discard-update').live('click', function(){ that.discardUpdate(); });
		
		this.editForm	= $('div.lcms-searchresult-edit').clone();
		this.newForm 	= $('div.lcms-searchresult-new').clone();
	};
	
	this.inputs = function(key){
		
		this.input['class'] 	= $(this.el).find('input.lcms-searchresult-class').val();
		this.input['content']	= $(this.el).find('select.lcms-searchresult-limit').val();
		
		return this.inputs[key];
	};
	
	this.reload = function(){
		var that = this;
	};
	
	this.add = function(el, page, location){
		this.el = el;
		$(this.el).prepend($(this.newForm).clone());
		
		$.ajax({
			url: base_url+"page/ajax/control/searchresult/results_list",
			success: function (res){
				$('select.lcms-searchresult-select').html(res);
			}		
		})
	};
	
	this.edit = function(el, data){
		this.el = el;
		this.id = data.id;

		$(this.el).prepend($(this.editForm).clone());
		
		$(this.el).find('input.lcms-searchresult-limit').val(data.content);
		$(this.el).find('input.lcms-searchresult-class').val(data.class);

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