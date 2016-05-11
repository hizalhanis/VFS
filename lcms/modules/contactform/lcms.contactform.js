
var contactform = new function(){

	this.input = [];
	
	this.init = function(){

		
	};
		
	this.bind = function(){
	
		
	
		var that = this;
		$('button.lcms-contactform-save').live('click', function(e){ e.preventDefault(); that.save(); });
		$('button.lcms-contactform-save-publish').live('click', function(e){ e.preventDefault(); that.savePublish(); });
		$('button.lcms-contactform-update').live('click', function(e){ e.preventDefault(); that.update(); });
		$('button.lcms-contactform-update-publish').live('click', function(e){ e.preventDefault(); that.updatePublish(); });

		$('button.lcms-contactform-discard').live('click', function(e){ e.preventDefault(); that.discard(); });
		$('button.lcms-contactform-discard-update').live('click', function(e){ e.preventDefault(); that.discardUpdate(); });
		
		$('button.lcms-contactform-add-input').live('click',function(e){
			e.preventDefault();
			var x = $(this.row).clone();
			$(x).attr('fieldtype','text');
			$('div.lcms-contactform-table table').append(x);
		});
		
		$('button.lcms-contactform-add-textarea').live('click',function(e){
			e.preventDefault();
			var x = $(this.row).clone();
			$(x).find('td.field').html('<textarea></textarea>').attr('fieldtype','textarea');
			$('div.lcms-contactform-table table').append(x);
		});
		
		$('button.lcms-contactform-delete-row').live('click',function(e){
			e.preventDefault();
			$(this).parents('tr.cf-row').fadeOut(500,function(){
				$(this).remove();
			})
		});
		
		this.editForm	= $('div.lcms-contactform-edit').clone();
		this.newForm 	= $('div.lcms-contactform-new').clone();
	};
	
	this.inputs = function(key){
		this.input['email']	= $(this.el).find('input.lcms-contactform-email').val();
		this.input['class'] = $(this.el).find('input.lcms-contactform-class').val();
		
		return this.inputs[key];
	};
	
	this.reload = function(){
		var that = this;
	};
	
	this.add = function(el, page, location){
		this.el = el;
		$(this.el).prepend($(this.newForm).clone());
		$(this.el).append($('div.lcms-contactform-table'));
		
		
		var x = $('div.lcms-contactform-table tr').first().clone();
		$(x).removeClass('first').find('input.label').val('');
		$(x).find('button').remove();
		$(x).find('span').remove();
		$(x).find('input.mandatory').remove();
		$(x).find('td.label').prepend('<span style="color:red">*</span><input type="checkbox" class="mandatory" value="1" /><button class="lcms-dbtn lcms-contactform-delete-row">x</button>');
		this.row = $(x);

		console.log(this.row);
		
	};
	
	this.edit = function(el, data){
		this.el = el;
		this.id = data.id;
		
		var fields = JSON.parse(data.options);
		
		$(this.editForm).clone().prependTo(this.el).each(function(){
			$(this).find('input.lcms-contactform-email').val(data.content);
			$(this).find('input.lcms-contactform-class').val(data.class);
			
			$('td.label span').hide();
			$('td.label input').show();
			$('div.lcms-contactform-control-buttons').show();
			$('button.lcms-contactform-delete-row').show();
			$('tr.submit').remove();
			
		});
	};
	
	this.save = function(){
	
		var opts = []
		$('div.lcms-contactform-table input.label').each(function(){
			var val = $(this).val();
			var type = $(this).attr('fieldtype');
			var obj = {
				type: type,
				label: val
			};
			
			opts.push(obj);
		})
	
		var content 	= this.input['email'];
		var options 	= JSON.stringify(opts);
		var css_class	= this.input['class'];
		var callback	= function() {};


		lcmsSave(content, options, css_class, callback);	
	};
	
	this.savePublish = function(){
	
		var opts = []
		$('div.lcms-contactform-table input.label').each(function(){
			var val = $(this).val();
			var type = $(this).attr('fieldtype');
			var obj = {
				type: type,
				label: val
			};
			
			opts.push(obj);
		})
	
		var content 	= this.input['email'];
		var options 	= JSON.stringify(opts);
		var css_class	= this.input['class'];
		var callback	= function() {};

		lcmsSavePublish(content, options, css_class, callback);	
	};
	
	this.update = function(){
	
		var opts = []
		$(this.el).find('input.label').each(function(){			
			var val = $(this).val();
			var type = $(this).attr('fieldtype');
			

			var obj = {
				type: type,
				label: val
			};
			
			opts.push(obj);
		})
	
		var content 	= this.input['email'];
		var options 	= JSON.stringify(opts);
		var css_class	= this.input['class'];
		var callback	= function() {};
				
		lcmsUpdate(this.id, content, options, css_class, callback);
	};
	
	this.updatePublish = function(){
	
		var opts = []
		$(this.el).find('input.label').each(function(){			
			var val = $(this).val();
			var type = $(this).attr('fieldtype');
			

			var obj = {
				type: type,
				label: val
			};
			
			opts.push(obj);
		})
	
		var content 	= this.input['email'];
		var options 	= JSON.stringify(opts);
		var css_class	= this.input['class'];
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