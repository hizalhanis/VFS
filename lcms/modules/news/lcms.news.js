
var news = new function(){

	this.bound = false;
	this.input = [];
	
	this.init = function(){

	};
		
	this.bind = function(){	
		
		var that = this;
		$('button.lcms-news-save').live('click', function(){ that.save(); });
		$('button.lcms-news-save-publish').live('click', function(){ that.savePublish(); });
		$('button.lcms-news-update').live('click', function(){ that.update(); });
		$('button.lcms-news-update-publish').live('click', function(){ that.updatePublish(); });

		$('button.lcms-news-image-select').live('click',function(e){ e.preventDefault(); lcmsFileObject = $('input.lcms-news-image-url'); selectFile('images') });
		$('button.lcms-news-discard').live('click', function(){ that.discard(); });
		$('button.lcms-news-discard-update').live('click', function(){ that.discardUpdate(); });

		
		this.editForm	= $('div.lcms-news-edit').clone();
		this.newForm 	= $('div.lcms-news-new').clone();
	};
	
	this.inputs = function(key){
		
		this.input['class'] 	= $(this.el).find('input.lcms-news-class').val();
		this.input['content']	= $(this.el).find('input.lcms-news-name').val();
		this.input['npp']		= $(this.el).find('input.lcms-news-entries').val();
		this.input['excerpt']	= $(this.el).find('input.lcms-news-excerpt').attr('checked') ? 1 : 0;
		
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
		
		this.editForm	= $('div.lcms-news-edit').clone();
		$(this.el).prepend($(this.editForm).clone());
		
		eval('var opts = ' + data.options);
		
		$(this.el).find('input.lcms-news-class').val(data.class);
		$(this.el).find('input.lcms-news-name').val(data.content);
		$(this.el).find('input.lcms-news-entries').val(opts.npp);
		$(this.el).find('input.lcms-news-excerpt').attr('checked',opts.excerpt);
	};
	
	this.save = function(){
	
		var opts = {
			'npp'		: this.input['npp'],
			'excerpt'	: this.input['excerpt']
		};

		var content 	= this.input['content'];
		var options 	= JSON.stringify(opts);
		var css_class	= this.input['class'];
		var callback	= function() { };
		
		var ok = true;
		
		if (!content) ok = false;

		if (!ok){
			createDialog('ok','Please make sure you have filled the form correctly.');
		}

		if (ok) lcmsSave(content, options, css_class, callback);	
	};
	
	this.savePublish = function(){
	
		var opts = {
			'npp'		: this.input['npp'],
			'excerpt'	: this.input['excerpt']
		};

		var content 	= this.input['content'];
		var options 	= JSON.stringify(opts);
		var css_class	= this.input['class'];
		var callback	= function() { };
		
		var ok = true;
		
		if (!content) ok = false;

		if (!ok){
			createDialog('ok','Please make sure you have filled the form correctly.');
		}
		

		lcmsSavePublish(content, options, css_class, callback);	
	};
	
	this.update = function(){

		var opts = {
			'npp'		: this.input['npp'],
			'excerpt'	: this.input['excerpt']
		};

		var content 	= this.input['content'];
		var options 	= JSON.stringify(opts);
		var css_class	= this.input['class'];
		var callback	= function() { };
		
		var ok = true;
		
		
		if (!content) ok = false;

		if (!ok){
			createDialog('ok','Please make sure you have filled the form correctly.');
		}
	
				
		lcmsUpdate(this.id, content, options, css_class, callback);
	};
	
	this.updatePublish = function(){
	
		var opts = {
			'npp'		: this.input['npp'],
			'excerpt'	: this.input['excerpt']
		};

		var content 	= this.input['content'];
		var options 	= JSON.stringify(opts);
		var css_class	= this.input['class'];
		var callback	= function() { };
		
		var ok = true;
		
		if (!content) ok = false;

		if (!ok){
			createDialog('ok','Please make sure you have filled the form correctly.');
		}

				
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