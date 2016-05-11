
var searchbox = new function(){

	this.input = [];
	
	this.init = function(){

	};
		
	this.bind = function(){
		var that = this;
		$('button.lcms-searchbox-save').live('click', function(){ that.save(); });
		$('button.lcms-searchbox-save-publish').live('click', function(){ that.savePublish(); });
		$('button.lcms-searchbox-update').live('click', function(){ that.update(); });
		$('button.lcms-searchbox-update-publish').live('click', function(){ that.updatePublish(); });

		$('button.lcms-searchbox-discard').live('click', function(){ that.discard(); });
		$('button.lcms-searchbox-discard-update').live('click', function(){ that.discardUpdate(); });
		
		this.editForm	= $('div.lcms-searchbox-edit').clone();
		this.newForm 	= $('div.lcms-searchbox-new').clone();
	};
	
	this.inputs = function(key){
		
		this.input['class'] 	= $(this.el).find('input.lcms-searchbox-class').val();
		this.input['content']	= $(this.el).find('select.lcms-searchresult-select').val();
		
		return this.inputs[key];
	};
	
	this.reload = function(){
		var that = this;
	};
	
	this.add = function(el, page, location){
		this.el = el;

		var that = this;
		$.ajax({
			url: site_url+"page/ajax/control/searchbox/results_list",
			success: function (res){
				$(that.el).prepend($(that.newForm).clone());
				$('select.lcms-searchresult-select').html(res);
			}		
		});
		
		
	};
	
	this.edit = function(el, data){
		this.el = el;
		this.id = data.id;
		
		var that = this;
		
		$.ajax({
			url: site_url+"page/ajax/control/searchbox/results_list",
			success: function (res){
				$(that.el).prepend($(this.editForm).clone());		
				$('select.lcms-searchresult-select').html(res);
				$(that.el).find('input.lcms-searchbox-class').val(data.class);
				$(that.el).find('input.lcms-searchresult-select').val(data.content);

			}		
		});
		
		
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