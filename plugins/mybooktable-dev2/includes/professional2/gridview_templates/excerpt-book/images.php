<div class="mbt-book-images">
	<?php if(mbt_get_setting('enable_gridview_shadowbox')) { ?>
		<div data-href="<?php the_permalink(); ?>" class="mbt-shadowbox-buybutton mbt-shadowbox-iframe"><?php mbt_the_book_image(); ?></div>
	<?php } else { ?>
		<a href="<?php the_permalink(); ?>"><?php mbt_the_book_image(); ?></a>
	<?php } ?>
</div>