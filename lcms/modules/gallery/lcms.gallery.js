
var gallery = new function(){

	this.input = [];
	
	this.init = function(){
	
		$('div.lcms-gallery-image-container img').live('mousedown',function(e){
			e.preventDefault();
		});
		
		$('div.lcms-gallery-image-container ul li').live('click',function(){

			$('div.lcms-gallery-image-container ul li').removeClass('current');
			$(this).addClass('current');
			
			$('input.lcms-gallery-image-url').val($(this).attr('src'));
			$('input.lcms-gallery-image-title').val($(this).attr('title'));
			
			$('button.lcms-gallery-add').text('Update');
			
		});
		
		$('button.lcms-gallery-select').live('click',function(){
			$('input.lcms-gallery-image-url').val('');
			$('input.lcms-gallery-image-title').val('');
			
			$('button.lcms-gallery-add').text('Add');
		});

	};
		
	this.bind = function(){
		var that = this;
		$('button.lcms-gallery-save').live('click', function(){ that.save(); });
		$('button.lcms-gallery-save-publish').live('click', function(){ that.savePublish(); });
		$('button.lcms-gallery-update').live('click', function(){ that.update(); });
		$('button.lcms-gallery-update-publish').live('click', function(){ that.updatePublish(); });

		$('button.lcms-gallery-discard').live('click', function(){ that.discard(); });
		$('button.lcms-gallery-discard-update').live('click', function(){ that.discardUpdate(); });
		
		$('button.lcms-gallery-select').live('click',function(){ lcmsFileObject = $('input.lcms-gallery-image-url'); selectFile('images') });
		$('button.lcms-gallery-add').live('click',function(){ that.addImage(); })
		$('a.lcms-gallery-image-delete').live('click',function(){ $(this).parents('li.lcms-gallery-item').remove(); })
		
		
		this.editForm	= $('div.lcms-gallery-edit').clone();
		this.newForm 	= $('div.lcms-gallery-new').clone();
		this.container	= $('div.lcms-gallery-image-container ul');
	};
	
	
	this.inputs = function(key){
		
		this.input['class'] 	= $(this.el).find('input.lcms-gallery-class').val();
		this.input['imageURL']	= $(this.el).find('input.lcms-gallery-image-url').val();
		this.input['title']		= $(this.el).find('input.lcms-gallery-image-title').val();
		
		this.input['photoOption']	= $(this.el).find('button.lcms-gallery-add').text();
		
		this.input['effect']	= $(this.el).find('select.lcms-gallery-effect').val();
		this.input['title_position']	= $(this.el).find('select.lcms-gallery-title-position').val();
	
		
		return this.inputs[key];
	};
	
	this.addImage = function(){
		this.inputs();
		
		if (this.input['photoOption'] == 'Update'){
			$('div.lcms-gallery-image-container li.current').attr('src',this.input['imageURL']).attr('title',this.input['title']);
			$('div.lcms-gallery-image-container li.current img').attr('src',this.input['imageURL']);
			$('div.lcms-gallery-image-container li.current').removeClass('current');
			
			$('input.lcms-gallery-image-url').val('');
			$('input.lcms-gallery-image-title').val('');
			
			$('button.lcms-gallery-add').text('Add');
		} else {
		
			var item = $('<li class="lcms-gallery-item" style="float: left; width: 50px; height: 65px; margin: 2px;" src="'+this.input['imageURL']+'" title="'+this.input['title']+'"><img style="width: 50px; height: 50px;" src="'+this.input['imageURL']+'" /><a class="lcms-btn lcms-gallery-image-delete">Delete</a></li>');
			$(this.el).find('div.lcms-gallery-image-container ul').append(item);
		}
		
		
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
		
		eval('var images = ' + data.content);
		eval('var opt = ' + data.options);
		
		for (var i = 0; i < images.length; i++){
			var image = images[i];
			var item = $('<li class="lcms-gallery-item" style="float: left; width: 50px; height: 65px; margin: 2px;" src="'+image.src+'" title="'+image.title+'"><img style="width: 50px; height: 50px;" src="'+image.src+'" /><a class="lcms-btn lcms-gallery-image-delete">Delete</a></li>');
			$(this.el).find('div.lcms-gallery-image-container ul').append(item);
		}
		
		$(this.el).find('select.lcms-gallery-effect').val(opt.effect);
		$(this.el).find('select.lcms-gallery-title-position').val(opt.title_position);
		
		$(this.el).find('input.lcms-gallery-class').val(data.class);
		
		$('div.lcms-gallery-image-container ul').sortable({scroll:true, scrollSensitivity: 5, tolerance: 'intersect', helper: 'clone'});
		$('div.lcms-gallery-image-container ul').disableSelection();
	};
	
	this.save = function(){
	
		this.inputs();
		
		alert(this.input['title_position']);
	
		var opt = {};
		opt.effect 			= this.input['effect'];
		opt.title_position 	= this.input['title_position'];
	
		var images = [];
		
		$(this.el).find('div.lcms-gallery-image-container li').each(function(){
			var img = {};
			img.src = $(this).attr('src');
			img.title = $(this).attr('title');
			images.push(img);
		});

		var content 	= JSON.stringify(images);
		var options 	= JSON.stringify(opt);
		var css_class	= this.input['class'];
		var callback	= function() { lcmsClearPane() };


		lcmsSave(content, options, css_class, callback);	
	};
	
	this.savePublish = function(){
	
		this.inputs();
	
		var opt = {};
		opt.effect 			= this.input['effect'];
		opt.title_position 	= this.input['title_position'];
	
		var images = [];
		$(this.el).find('div.lcms-gallery-image-container li').each(function(){
			var img = {};
			img.src = $(this).attr('src');
			img.title = $(this).attr('title');
			images.push(img);
		});

		var content 	= JSON.stringify(images);
		var options 	= JSON.stringify(opt);
		var css_class	= this.input['class'];
		var callback	= function() { lcmsClearPane() };
		


		lcmsSavePublish(content, options, css_class, callback);	
	};
	
	this.update = function(){
	
		this.inputs();
	
		var opt = {};
		opt.effect 			= this.input['effect'];
		opt.title_position 	= this.input['title_position'];

		var images = [];
		$(this.el).find('div.lcms-gallery-image-container li').each(function(){
			var img = {};
			img.src = $(this).attr('src');
			img.title = $(this).attr('title');
			images.push(img);
		});

		var content 	= JSON.stringify(images);
		var options 	= JSON.stringify(opt);
		var css_class	= this.input['class'];
		var callback	= function() { lcmsClearPane() };
				
		lcmsUpdate(this.id, content, options, css_class, callback);
	};
	
	this.updatePublish = function(){
	
		this.inputs();
	
		var opt = {};
		opt.effect 			= this.input['effect'];
		opt.title_position 	= this.input['title_position'];	
	
		var images = [];
		$(this.el).find('div.lcms-gallery-image-container li').each(function(){
			var img = {};
			img.src = $(this).attr('src');
			img.title = $(this).attr('title');
			images.push(img);
		});

		var content 	= JSON.stringify(images);
		var options 	= JSON.stringify(opt);
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