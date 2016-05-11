
var survey = new function(){

	this.input = [];
	
	this.init = function(){
		lcmsAuthorMode = false;
		$('ul.lcms-content li.lcms-empty-author').hide().css('display','none !important');
		$('div.lcms-content-toolbar').hide();
		$.cookie("lcmsAuthorMode", 0);

	};
		
	this.bind = function(){
		var that = this;
		$('button.lcms-survey-save').live('click', function(){ that.save(); });
		$('button.lcms-survey-save-publish').live('click', function(){ that.savePublish(); });
		$('button.lcms-survey-update').live('click', function(){ that.update(); });
		$('button.lcms-survey-update-publish').live('click', function(){ that.updatePublish(); });

		$('button.lcms-survey-discard').live('click', function(){ that.discard(); });
		$('button.lcms-survey-discard-update').live('click', function(){ that.discardUpdate(); });
		
		$('input.lcms-survey-password-protected').live('click', function(){
			if ($(this).attr('checked')){
				$(this).parents('table.lcms-control-form').find('tr.password-protected').show();
			} else {
				$(this).parents('table.lcms-control-form').find('tr.password-protected').hide();
			}
		});
		
		this.editForm	= $('div.lcms-survey-edit').clone();
		this.newForm 	= $('div.lcms-survey-new').clone();
		
	};
	
	this.inputs = function(key){
		this.input['name']					= $(this.el).find('input.lcms-survey-title').val();
		this.input['password_protected']	= $(this.el).find('input.lcms-survey-password-protected').attr('checked') ? 1 : 0;
		this.input['logins']				= $(this.el).find('textarea.lcms-survey-logins').val();
		
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
		
		console.log(data);
		
		eval('var opt = '+data.options);
		
		$(this.editForm).clone().prependTo(this.el);
		
		$('input.lcms-survey-password-protected').removeAttr('checked');
		
		$('input.lcms-survey-name').val(opt.name);
		if (opt.password_protected){
			$('input.lcms-survey-password-protected').attr('checked','checked');
			$('tr.password-protected').show();
		} else {
			$('input.lcms-survey-password-protected').removeAttr('checked');
			$('tr.password-protected').hide();
		}

		$('textarea.lcms-survey-logins').val(opt.logins);
		
	};
	
	this.save = function(){
		var opt = {};
		
		opt.password_protected 	= this.input['password_protected'];
		opt.name 				= this.input['name'];
		opt.logins				= this.input['logins'];
	
		var content 	= this.inputs['logins'];
		var options 	= JSON.stringify(opt);
		var css_class	= '';
		var callback	= function() {};


		lcmsSave(content, options, css_class, callback);	
	};
	
	this.savePublish = function(){
		var opt = {};
		
		opt.password_protected 	= this.input['password_protected'];
		opt.name 				= this.input['name'];
		opt.logins				= this.input['logins'];
	
		var content 	= this.inputs['logins'];
		var options 	= JSON.stringify(opt);
		var css_class	= '';
		var callback	= function() {};

		lcmsSavePublish(content, options, css_class, callback);	
	};
	
	this.update = function(){
		var opt = {};
		
		opt.password_protected 	= this.input['password_protected'];
		opt.name 				= this.input['name'];
		opt.logins				= this.input['logins'];
	
		var content 	= this.inputs['logins'];
		var options 	= JSON.stringify(opt);
		var css_class	= '';
		var callback	= function() {};
				
		lcmsUpdate(this.id, content, options, css_class, callback);
	};
	
	this.updatePublish = function(){
		var opt = {};
		
		opt.password_protected 	= this.input['password_protected'];
		opt.name 				= this.input['name'];
		opt.logins				= this.input['logins'];
	
		var content 	= this.inputs['logins'];
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