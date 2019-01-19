jQuery(document).ready(function() {
	jQuery(".wrap").on("change", ".mbt_featured_book_selectmode", function() {
		var element = jQuery(this);
		if(element.val() == "manual_select") {
			element.parents('.widget-content').find('.mbt-featured-book-manual-selector').show();
			element.parents('.widget-content').find('.mbt-featured-book-options').hide();
		} else {
			element.parents('.widget-content').find('.mbt-featured-book-manual-selector').hide();
			element.parents('.widget-content').find('.mbt-featured-book-options').show();
		}
	});

});

function mbt_update_featured_books(parent) {
	var data = [];
	parent.find('.mbt-featured-book-list .mbt-book').each(function(i, e) {
		data.push(parseInt(jQuery(e).attr('data-id'), 10));
	});
	parent.find('.mbt-featured-books').val(JSON.stringify(data));
	parent.find('.widget-control-save').click();
	return true;
}

function mbt_init_book_remover(i, elem) {
	var elem = jQuery(elem)
	elem.click(function(e) {
		parent = elem.parents('.mbt-featured-book-manual-selector');
		elem.parent().remove();
		mbt_update_featured_books(parent.parents('.widget-inside'));
		return false;
	});
}

function mbt_initialize_featured_book_widget_editor(elem) {
	var thiselement = jQuery(elem);
	if(thiselement.attr('data-initialized') === 'true') { return false; }
	thiselement.attr('data-initialized', 'true');

	var parent = thiselement.parents('.widget-inside');

	parent.find('.mbt-featured-book-adder').click(function(e) {
		selector = parent.find('.mbt-featured-book-selector');
		if(!selector.val()){return false;}
		var element = jQuery('<li data-id="'+selector.val()+'" class="mbt-book">'+selector.find(":selected").text()+'<a class="mbt-book-remover">X</a></li>');
		mbt_init_book_remover(0, element.find('.mbt-book-remover'));
		parent.find('.mbt-featured-book-list').prepend(element);
		mbt_update_featured_books(parent);
		selector.val('');
		return false;
	});

	parent.find('.mbt-book-remover').each(mbt_init_book_remover);

	parent.find('.mbt-featured-book-list').sortable({stop: function(){mbt_update_featured_books(parent);}});
}
