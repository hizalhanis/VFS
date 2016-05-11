
var slideshow = new function(){

	this.input = [];
	
	this.init = function(){

		
		$('div.lcms-slideshow-image-container img').live('mousedown',function(e){
			e.preventDefault();
		});
		
		$('div.lcms-slideshow-image-container ul li').live('click',function(){

			$('div.lcms-slideshow-image-container ul li').removeClass('current');
			$(this).addClass('current');
			
			$('input.lcms-slideshow-image-url').val($(this).attr('src'));
			$('input.lcms-slideshow-link-url').val($(this).attr('href'));
			
			$('button.lcms-slideshow-add').text('Update');
			
		});
		
		$('button.lcms-slideshow-select').live('click',function(){
			$('input.lcms-slideshow-image-url').val('');
			$('input.lcms-slideshow-link-url').val('');
			
			$('button.lcms-slideshow-add').text('Add');
		});
		
	};
		
	this.bind = function(){
		var that = this;
		$('button.lcms-slideshow-save').live('click', function(){ that.save(); });
		$('button.lcms-slideshow-save-publish').live('click', function(){ that.savePublish(); });
		$('button.lcms-slideshow-update').live('click', function(){ that.update(); });
		$('button.lcms-slideshow-update-publish').live('click', function(){ that.updatePublish(); });

		$('button.lcms-slideshow-discard').live('click', function(){ that.discard(); });
		$('button.lcms-slideshow-discard-update').live('click', function(){ that.discardUpdate(); });
		
		$('button.lcms-slideshow-select').live('click',function(){ lcmsFileObject = $('input.lcms-slideshow-image-url'); selectFile('images') });
		$('button.lcms-slideshow-add').live('click',function(){ that.addImage(); })
		$('a.lcms-slideshow-image-delete').live('click',function(){ $(this).parents('li.lcms-slideshow-item').remove(); })
		
		this.editForm	= $('div.lcms-slideshow-edit').clone();
		this.newForm 	= $('div.lcms-slideshow-new').clone();
		this.container	= $('div.lcms-slideshow-image-container ul');
	};
	
	
	this.inputs = function(key){
		
		this.input['class'] 	= $(this.el).find('input.lcms-slideshow-class').val();
		this.input['imageURL']	= $(this.el).find('input.lcms-slideshow-image-url').val();
		this.input['linkURL']	= $(this.el).find('input.lcms-slideshow-link-url').val();
		
		this.input['photoOption']	= $(this.el).find('button.lcms-slideshow-add').text();
		
		this.input['effect']	= $(this.el).find('select.lcms-slideshow-effect').val();
		
		this.input['show_markers']	= $(this.el).find('input.lcms-slideshow-show-markers').attr('checked') ? 1 : 0;
		this.input['show_controls']	= $(this.el).find('input.lcms-slideshow-show-controls').attr('checked') ? 1 : 0;
		this.input['center_markers']= $(this.el).find('input.lcms-slideshow-center-markers').attr('checked') ? 1 : 0;	
		
		return this.inputs[key];
	};
	
	this.addImage = function(){
		this.inputs();
		
		if (this.input['photoOption'] == 'Update'){
			$('div.lcms-slideshow-image-container li.current').attr('src',this.input['imageURL']).attr('href',this.input['linkURL']);
			$('div.lcms-slideshow-image-container li.current img').attr('src',this.input['imageURL']);
			$('div.lcms-slideshow-image-container li.current').removeClass('current');
			
			$('input.lcms-slideshow-image-url').val('');
			$('input.lcms-slideshow-link-url').val('');
			
			$('button.lcms-slideshow-add').text('Add');
		} else {
		
			var item = $('<li class="lcms-slideshow-item" style="float: left; width: 50px; height: 65px; margin: 2px;" src="'+this.input['imageURL']+'" href="'+this.input['linkURL']+'"><img style="width: 50px; height: 50px;" src="'+this.input['imageURL']+'" /><a class="lcms-btn lcms-slideshow-image-delete">Delete</a></li>');
			$(this.el).find('div.lcms-slideshow-image-container ul').append(item);
		
			$('div.lcms-slideshow-image-container ul').sortable({scroll:true, scrollSensitivity: 5, tolerance: 'intersect', helper: 'clone'});
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
			var item = $('<li class="lcms-slideshow-item" style="float: left; width: 50px; height: 65px; margin: 2px;" src="'+image.src+'" href="'+image.href+'"><img style="width: 50px; height: 50px; " src="'+image.src+'" /><a class="lcms-btn lcms-slideshow-image-delete">Delete</a></li>');
			$(this.el).find('div.lcms-slideshow-image-container ul').append(item);
			
		}
		
		$(this.el).find('select.lcms-slideshow-effect').val(opt.effect);
		$(this.el).find('input.lcms-slideshow-show-markers').attr('checked',opt.show_markers == 1 ? true : false);
		$(this.el).find('input.lcms-slideshow-show-controls').attr('checked',opt.show_controls == 1 ? true : false);
		$(this.el).find('input.lcms-slideshow-center-markers').attr('checked',opt.center_markers == 1 ? true : false);
		
		$(this.el).find('input.lcms-slideshow-class').val(data.class);

		$('div.lcms-slideshow-image-container ul').sortable({scroll:true, scrollSensitivity: 5, tolerance: 'intersect', helper: 'clone'});
		$('div.lcms-slideshow-image-container ul').disableSelection();
		
	};
	
	this.save = function(){
	
		this.inputs();
	
		var opt = {};
		opt.effect 			= this.input['effect'];
		opt.show_markers 	= this.input['show_markers'];
		opt.show_controls	= this.input['show_controls'];
		opt.center_markers	= this.input['center_markers'];
	
		var images = [];
		
		$(this.el).find('div.lcms-slideshow-image-container li').each(function(){
			var img = {};
			img.src = $(this).attr('src');
			img.href = $(this).attr('href');
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
		opt.show_markers 	= this.input['show_markers'];
		opt.show_controls	= this.input['show_controls'];
		opt.center_markers	= this.input['center_markers'];
	
		var images = [];
		$(this.el).find('div.lcms-slideshow-image-container li').each(function(){
			var img = {};
			img.src = $(this).attr('src');
			img.href = $(this).attr('href');
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
		opt.show_markers 	= this.input['show_markers'];
		opt.show_controls	= this.input['show_controls'];
		opt.center_markers	= this.input['center_markers'];

		var images = [];
		$(this.el).find('div.lcms-slideshow-image-container li').each(function(){
			var img = {};
			img.src = $(this).attr('src');
			img.href = $(this).attr('href');
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
		opt.show_markers 	= this.input['show_markers'];
		opt.show_controls	= this.input['show_controls'];
		opt.center_markers	= this.input['center_markers'];
	
		var images = [];
		$(this.el).find('div.lcms-slideshow-image-container li').each(function(){
			var img = {};
			img.src = $(this).attr('src');
			img.href = $(this).attr('href');
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