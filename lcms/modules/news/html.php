<?php if ($author_mode): ?>

	<script type="text/javascript">
	
	$(document).ready(function(){
		var control = 'news';
		if (!lcmsAddedItems[control]){
			eval(control + '.bind()');
			$('*').live('change paste keydown keyup',function(){
				eval(control + '.inputs()');
			});
			lcmsAddedItems[control] = true;
		}
		
		$('select[name=nstatus]').change(function(){
			var status = $(this).val();
			var newsId = $(this).parents('div.news-entry').attr('id');
	    	$('div.lcms-status').html('Updating Status').show();
			$.ajax({
	    		type: "POST",
	    		url: site_url+"page/ajax/control/news/update_status/",
	    		data: 'status=' + status + '&id=' + newsId,
	    		success: function (html){
	    			$('div.lcms-status').html('News article has been set to ' + status);
	    			setTimeout(function() { 
	    				$('div.lcms-status').fadeOut('slow', function(){
	    					$(this).css('display','none');
	    				}); 
	    			}, 1000);    			
	    		}
	    	});
		});
	
		
	});
	
	</script>

	<a href="<?php echo site_url(); ?>p/<?php echo $content->page; ?>/add_article" style="margin-top: 1px; margin-right: 40px;" class="lcms-dbtn">Add News Article</a> 
	<?php if ($news_id): ?>
	<a href="<?php echo page_url(); ?>" style="margin-top: 1px; margin-right: 2px;" class="lcms-dbtn">Back to Main</a> 
	<?php endif; ?>
	<div class="lcms-news-contents <?php echo $content->class; ?>">
	
<?php else: ?>

	<div class="lcms-news-contents <?php echo $content->class; ?>">
	
<?php endif; ?>



	<div class="lcms-news-entries">
	
	<?php if ($news_id): ?>
		<?php 
			$date = date("h:iA, j F, Y",strtotime($entry->date));
			if ($entry->image != null && $entry->image != 'undefined'){
				$image = "<div class=\"news-entry-image-single\"><img src=\"{$entry->image}\" class=\"news-entry-image-single\" /></div>";
			} else {
				$image = '';
			}
			
			$dropdown = form_dropdown('nstatus', array('Saved'=>'Saved','Published'=>'Published'), $entry->status, 'class="lcms-news-post-status"');
		?>			
	
		<div class="news-entry" id="<?php echo $entry->id; ?>">

			<h2><a href="<?php echo site_url(); ?><?php echo $content->page; ?>/article/<?php echo $entry->id; ?>/<?php echo clean_url($entry->title); ?>"><?php echo $entry->title; ?></a></h2>
			<p class="news-meta">Posted on <?php echo $date; ?>. 
				<?php if ($author_mode): ?>
					<?php echo $dropdown; ?> <a class="lcms-btn" href="<?php echo site_url(); ?>p/<?php echo $content->page; ?>/edit_article/<?php echo $entry->id; ?>">Edit Article</a>
				<?php endif; ?>
			</p>
		    <?php echo $image; ?>
		    <div class="news-text">
		    	<?php echo $entry->content; ?>
		    </div>
			    	<br class="news-clear" />
		</div>


	
	
	<?php else: ?>
		<?php foreach ($entries as $entry): ?>
			<?php 
				$dropdown = form_dropdown('nstatus', array('Saved'=>'Saved','Published'=>'Published'), $entry->status, 'class="lcms-news-post-status"');
					
				$date = date("h:iA, j F, Y",strtotime($entry->date));
				$date_field = date('d/m/Y',strtotime($entry->date));
				
				$url = site_url() . $content->page . '/article/' . $entry->id . '/' . clean_url($entry->title);
					
				if ($entry->image != null && $entry->image != 'undefined'){
					$image = "<div class=\"news-entry-image\"><a href=\"{$url}\"><img src=\"{$entry->image}\" class=\"news-entry-image\" /></a></div>";
				} else {
					$image = '';
				}
			?>
			<div class="news-entry" id="<?php echo $entry->id; ?>">
			    <div class="news-entry-post">
			    	<h2><a href="<?php echo site_url(); ?><?php echo $content->page; ?>/article/<?php echo $entry->id; ?>/<?php echo clean_url($entry->title); ?>"><?php echo $entry->title; ?></a></h2>

			    	<p class="news-meta">Posted on <?php echo $date; ?>. 
			    		<?php if ($author_mode): ?>
			    			<?php echo $dropdown; ?> <a class="lcms-btn" href="<?php echo site_url(); ?>p/<?php echo $content->page; ?>/edit_article/<?php echo $entry->id; ?>">Edit Article</a>
			    		<?php endif; ?>
			    	</p>
			    	<?php echo $image; ?>
			    	<div class="news-text">

			    		<?php echo $excerpt ? $entry->excerpt : $entry->content; ?>
			    		<br />
			    		<a href="<?php echo site_url(); ?><?php echo $content->page; ?>/article/<?php echo $entry->id; ?>/<?php echo clean_url($entry->title); ?>">Full Story</a>
			    	</div>
			    	<br class="news-clear" />
			    </div>
			    
			</div>
			
			<hr />
				
		<?php endforeach; ?>
		<?php 
			$total_pages = ceil($total_entries / $npp);
			$total_pages = $total_pages == 0 ? 1 : $total_pages;

		?>
				
			<div class="news-pagination">
			<?php 		
				$next_url 		= site_url() . '' . $content->page . '/page/' . ($cur_page - 1);
				$previous_url 	= site_url() . '' . $content->page . '/page/' . ($cur_page + 1);
				$page_url 		= site_url() . '' . $content->page;
				
				if ($cur_page - 1 == 0){
					$news_pagination .= $cur_page != 0 ? "<a class=\"news-pagination-previous\" href=\"{$page_url}\">Next</a> &middot; " : '';
				} else {
					$news_pagination .= $cur_page != 0 ? "<a class=\"news-pagination-previous\" href=\"{$next_url}\">Next</a> &middot; " : '';
				}
				
				$news_pagination .= 'Page ' . ($cur_page + 1) . ' of ' . $total_pages;
				$news_pagination .= $cur_page != $total_pages - 1 ? " &middot; <a class=\"news-pagination-next\" href=\"{$previous_url}\">Previous</a>" : '';
				
				echo $news_pagination; 
			?>		
			</div>
			
	<?php endif; ?>
	</div>
</div>