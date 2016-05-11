	<ul class="lcms-newsfeed-feeds <?php echo $content->class; ?>">
	<?php foreach ($feeds as $feed): ?>
			<?php 
				$url = site_url() .'' . $news_content->page . '/article/' . $feed->id . '/' . clean_url($feed->title); 
				$img = $feed->image ? "<div class=\"lcms-newsfeed-feature\"><a href=\"{$url}\"><img class=\"lcms-newsfeed-feature\" src=\"{$feed->image}\" \></a></div>"  : '';
				$date = date('j F, Y', strtotime($feed->date));
			?>
			<li>
				<a class="title" href="<?php echo $url; ?>"><?php echo $feed->title; ?></a>
				<p class="lcms-newsfeed-date"><?php echo $date; ?></p>
				<?php echo $img; ?>
				<div class="lcms-newsfeed-excerpt"><?php echo $feed->excerpt; ?> <a href="<?php echo $url; ?>">Full Story</a></div>
			</li>
				
	<?php endforeach; ?>
	</ul>
