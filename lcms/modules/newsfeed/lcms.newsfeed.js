
var newsfeed = new function(){

	this.input = [];
	
	this.init = function(){

	};
		
	this.bind = function(){
		var that = this;
		$('button.lcms-newsfeed-save').live('click', function(){ that.save(); });
		$('button.lcms-newsfeed-save-publish').live('click', function(){ that.savePublish(); });
		$('button.lcms-newsfeed-update').live('click', function(){ that.update(); });
		$('button.lcms-newsfeed-update-publish').live('click', function(){ that.updatePublish(); });

		$('button.lcms-newsfeed-discard').live('click', function(){ that.discard(); });
		$('button.lcms-newsfeed-discard-update').live('click', function(){ that.discardUpdate(); });
		
		this.editForm	= $('div.lcms-newsfeed-edit').clone();
		this.newForm 	= $('div.lcms-newsfeed-new').clone();
	};
	
	this.inputs = function(key){
		
		this.input['class'] 	= $(this.el).find('input.lcms-newsfeed-class').val();
		this.input['content']	= $(this.el).find('select.lcms-newsfeed-select').val();
		this.input['limit']		= $(this.el).find('select.lcms-newsfeed-limit').val();
		this.input['thumbnail'] = $(this.el).find('input.lcms-newsfeed-thumbnail').attr('checked') ? 1 : 0;
		this.input['excerpt']	= $(this.el).find('input.lcms-newsfeed-excerpt').attr('checked') ? 1 : 0;
		
		
		return this.inputs[key];
	};
	
	this.reload = function(){
		var that = this;
	};
	
	this.add = function(el, page, location){
		this.el = el;
		
		var that = this;		
		
		$.ajax({
			url: site_url+"page/ajax/control/newsfeed/news_list",
			success: function (res){
				$(that.el).prepend($(that.newForm).clone());
				$('select.lcms-newsfeed-select').html(res);
			}
		});
		
	};
	
	this.edit = function(el, data){
		this.el = el;
		this.id = data.id;
		
		var that = this;
		
		$.ajax({
			url: site_url+"page/ajax/control/newsfeed/news_list",
			success: function (res){
				$(that.el).prepend($(that.editForm).clone());		
				$('select.lcms-newsfeed-select').html(res);
				
				eval('var opts = '+data.options);
				
				$(that.el).find('input.lcms-newsfeed-class').val(data.class);
				$(that.el).find('input.lcms-newsfeed-select').val(data.content);
				$(that.el).find('select.lcms-newsfeed-limit').val(opts.limit);
				
				$(that.el).find('input.lcms-newsfeed-excerpt').attr('checked',opts.excerpt == '1' ? true : false);
				$(that.el).find('input.lcms-newsfeed-thumbnail').attr('checked',opts.thumbnail == '1' ? true : false);
			}		
		});
		
		
	};
	
	this.save = function(){
	
		var opts = {
			'limit'		: this.input['limit'],
			'excerpt'	: this.input['excerpt'],
			'thumbnail'	: this.input['thumbnail']
		};

		var content 	= this.input['content'];
		var options 	= JSON.stringify(opts);
		var css_class	= this.input['class'];
		var callback	= function() {  };


		lcmsSave(content, options, css_class, callback);	
	};
	
	this.savePublish = function(){
	
		var opts = {
			'limit'		: this.input['limit'],
			'excerpt'	: this.input['excerpt'],
			'thumbnail'	: this.input['thumbnail']
		};

		var content 	= this.input['content'];
		var options 	= JSON.stringify(opts);
		var css_class	= this.input['class'];
		var callback	= function() {  };
		
		
		lcmsSavePublish(content, options, css_class, callback);	
	};
	
	this.update = function(){

		var opts = {
			'limit'		: this.input['limit'],
			'excerpt'	: this.input['excerpt'],
			'thumbnail'	: this.input['thumbnail']
		};

		var content 	= this.input['content'];
		var options 	= JSON.stringify(opts);
		var css_class	= this.input['class'];
		var callback	= function() {  };
		
		alert(options);
				
		lcmsUpdate(this.id, content, options, css_class, callback);
	};
	
	this.updatePublish = function(){
	
		var opts = {
			'limit'		: this.input['limit'],
			'excerpt'	: this.input['excerpt'],
			'thumbnail'	: this.input['thumbnail']
		};

		var content 	= this.input['content'];
		var options 	= JSON.stringify(opts);
		var css_class	= this.input['class'];
		var callback	= function() {  };
				
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