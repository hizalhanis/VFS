
var richtext = new function(){

	this.input = [];
	
	this.init = function(){
	
		/*
		$('div.lcms-richtext-edit img').live('mousemove',function(){
			if (!$(this).hasClass('lcms-resizable')){
				$(this).addClass('lcms-resizable').resizable();
			}
		});
		
		$('div.lcms-richtext-edit img').live('mouseleave',function(){
			if (!$(this).hasClass('lcms-resizable')){
				$(this).addClass('lcms-resizable').resizable();
			}
			
		})
		
		$('div.lcms-richtext-new img').live('mousemove',function(){
			if (!$(this).hasClass('lcms-resizable')){
				$(this).addClass('lcms-resizable').resizable();
			}
		});
		*/
	};
		
	this.bind = function(){
		var that = this;
		$('button.lcms-richtext-save').live('click', function(){ that.save(); });
		$('button.lcms-richtext-save-publish').live('click', function(){ that.savePublish(); });
		$('button.lcms-richtext-update').live('click', function(){ that.update(); });
		$('button.lcms-richtext-update-publish').live('click', function(){ that.updatePublish(); });

		$('button.lcms-richtext-discard').live('click', function(){ that.discard(); });
		$('button.lcms-richtext-discard-update').live('click', function(){ that.discardUpdate(); });
		
		this.editForm	= $('div.lcms-richtext-edit').clone();
		this.newForm 	= $('div.lcms-richtext-new').clone();
	};
	
	this.inputs = function(key){
		
		// no inputs
		
		return this.inputs[key];
	};
	
	this.reload = function(){
		var that = this;
	};
	
	this.add = function(el, page, location){
		this.el = el;
		$(this.el).prepend($(this.newForm).clone());
		$(this.el).find('textarea').attr('id','lcms-richtext-textarea-input');
		
		var myNicEditor = new nicEditor({fullPanel : true});
		myNicEditor.addInstance('lcms-richtext-textarea-input');
		myNicEditor.setPanel('lcms-editor-pane');
		
		lcmsContentSpotlight();
	};
	
	this.edit = function(el, data){
		this.el = el;
		this.id = data.id;
		
		$(this.editForm).clone().prependTo(this.el).each(function(){
			$(this).find('#lcms-richtext-textarea').val(data.content).attr('id','lcms-richtext-textarea-input');
		});
		
		$(this.el).find('div.lcms-richtext-contents').hide();
		
		var myNicEditor = new nicEditor({fullPanel : true});
		myNicEditor.addInstance('lcms-richtext-textarea-input');
		myNicEditor.setPanel('lcms-editor-pane');
		
		lcmsContentSpotlight();
		
	};
	
	this.save = function(){
	
		var myEditor 	= nicEditors.findEditor('lcms-richtext-textarea-input').getElm();
		var content 	= $(myEditor).html();
		var options 	= '';
		var css_class	= '';
		var callback	= function() { lcmsClearPane() };


		lcmsSave(content, options, css_class, callback);	
	};
	
	this.savePublish = function(){
	
		var myEditor 	= nicEditors.findEditor('lcms-richtext-textarea-input').getElm();
		var content 	= $(myEditor).html();
		var options 	= '';
		var css_class	= '';
		var callback	= function() { lcmsClearPane() };
		


		lcmsSavePublish(content, options, css_class, callback);	
	};
	
	this.update = function(){

		var myEditor 	= nicEditors.findEditor('lcms-richtext-textarea-input').getElm();
		var content 	= $(myEditor).html();
		var options 	= '';
		var css_class	= '';
		var callback	= function() { lcmsClearPane() };
		
		
		
				
		lcmsUpdate(this.id, content, options, css_class, callback);
	};
	
	this.updatePublish = function(){
	
		var myEditor 	= nicEditors.findEditor('lcms-richtext-textarea-input').getElm();
		var content 	= $(myEditor).html();
		var options 	= '';
		var css_class	= '';
		var callback	= function() { lcmsClearPane() };
				
		lcmsUpdatePublish(this.id, content, options, css_class, callback);
	}
		
	this.discard = function(){
		$(this.el).remove();
		lcmsDiscard();
		lcmsClearPane();
		lcmsOffSpotlight();
		$(this.el).find('div.lcms-richtext-contents').show();
	};
	
	this.discardUpdate = function(){
		lcmsDiscard();
		lcmsClearPane();
		lcmsOffSpotlight();
		$(this.el).find('div.lcms-richtext-contents').show();
	};

	this.init();	

}