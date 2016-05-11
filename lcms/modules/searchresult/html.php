<div class="lcms-searchresult-contents <?php echo $content->class; ?>">
	<form method="post" action="<?php echo $url; ?>">
		<label for="q">Search</label> <input id="q" type="search" name="q" /> <input type="submit" value="Search" />
	</form>

	<div class="lcms-searchresult-container">
	<?php if ($do_search): ?>
		<h2>Search Results for <strong><?php echo $search_term; ?></strong></h2>
		
		<?php foreach ($results as $result): ?>
			<?php 
				$text = search_excerpt(strip_tags($result->content),$search_term,50);
				$title = $page_title[$result->page];
				$url = base_url() . $this->uri->segment(1) . '/' . $result->page;
			?>
			<?php if ($title): ?>
			<div class="lcms-searchresult-item">
			    <h4 class="lcms-searchresult-title"><a href="<?php echo $url; ?>"><?php echo $title; ?></a></h4>
			    <p class="lcms-searchresult-excerpt"><?php echo $text; ?></p>
			    <p class="lcms-searchresult-url"><?php echo $url; ?></p>
			</div>
			<?php endif; ?>
		<?php endforeach; ?>
	
		<?php if (!count($results)): ?>
			<div class="lcms-searchresult-item"><p class="lcms-searchresult-empty">No results found.</p></div>
		<?php endif; ?>
		
	<?php else: ?>
		<h2>Search this site</h2>
		<p>Use the search box above to search from the website contents.</p>
	<?php endif; ?>
	
	</div>
</div>