<?php

/*---------------------------------------------------------*/
/* UIEE File Import                                        */
/*---------------------------------------------------------*/

function mbtdev2_uiee_import_init() {
	add_filter('mbt_importers', 'mbtdev2_add_uiee_importer');
}
add_action('mbtdev2_init', 'mbtdev2_uiee_import_init');

function mbtdev2_add_uiee_importer($importers) {
	$importers['uiee'] = array(
		'name' => __('UIEE File', 'mybooktable'),
		'desc' => __('Import your books from a UIEE (Universal Information Exchange Environment) File.', 'mybooktable'),
		'page_title' => __('UIEE File Import', 'mybooktable'),
		'get_book_list' => array(
			'render_import_form' => 'mbtdev2_render_uiee_books_import_form',
			'parse_import_form' => 'mbtdev2_parse_uiee_books_import_form',
		),
	);
	return $importers;
}

function mbtdev2_render_uiee_books_import_form() {
	?>
		<label for="mbt_import_file">Choose File:</label>
		<input type="file" name="mbt_import_file" id="mbt_import_file">
	<?php
}

function mbtdev2_parse_uiee_books_import_form() {
	if($_FILES['mbt_import_file']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['mbt_import_file']['tmp_name'])) {
		$file = file_get_contents($_FILES['mbt_import_file']['tmp_name']);
		return mbtdev2_uiee_import_get_books($file);
	} else {
		return __('Error with file upload!', 'mybooktable');
	}
}

function mbtdev2_uiee_import_get_books($file) {
	$books = array();

	$lines = array_map('trim', explode("\n", $file));
	$current_line = 0;

	//parsing based on the UIEE specification at http://www.uiee.com/page2.htm

	//if it exists, skip past the header
	if(!preg_match('/[A-Z][A-Z]\|/', $lines[$current_line])) {
		while($current_line < count($lines) and !empty($lines[$current_line])) { $current_line++; }
		while($current_line < count($lines) and empty($lines[$current_line])) { $current_line++; }
	}

	//if there are still lines left, parse a record
	while($current_line < count($lines)) {
		$record = array();

		//parse record lines until we hit an empty line
		$previous_prefix = '';
		while($current_line < count($lines) and !empty($lines[$current_line])) {
			if(!preg_match('/[A-Z][A-Z]\|/', $lines[$current_line])) {
				return __('Error parsing file: invalid record on line ', 'mybooktable').$current_line;
			}
			list($prefix, $rest) = explode('|', $lines[$current_line], 2);
			if($prefix == $previous_prefix) {
				$record[$prefix] .= ' '.$rest;
			} else {
				$record[$prefix] = $rest;
			}
			$previous_prefix = $prefix;
			$current_line++;
		}

		//eat any empty lines
		while($current_line < count($lines) and empty($lines[$current_line])) { $current_line++; }

		//use record to make new book
		$new_book = array();
		if(!empty($record['TI'])) { $new_book['title'] = $record['TI']; }
		if(!empty($record['AA'])) { $new_book['authors'] = array_map('trim', explode(',', $record['AA'])); }
		if(!empty($record['MT'])) { $new_book['genres'] = array($record['MT']); }
		if(!empty($record['KE'])) { $new_book['tags'] = array_map('trim', explode(',', $record['KE'])); }
		if(!empty($record['BN'])) { $new_book['unique_id_isbn'] = $record['BN']; }
		if(!empty($record['PU'])) { $new_book['publisher_name'] = $record['PU']; }
		if(!empty($record['DP'])) { $new_book['publication_year'] = $record['DP']; }
		if(!empty($record['PR'])) { $new_book['price'] = $record['PR']; }
		if(!empty($record['NT']) and empty($new_book['unique_id_isbn'])) {
			$matches = array();
			preg_match('/\b([0-9]{13})\b/', $record['NT'], $matches);
			if(!empty($matches) and !empty($matches[1])) { $new_book['unique_id_isbn'] = $matches[1]; }
		}
		if(!empty($record['NT'])) {
			$excerptlen = 300;
			$stripped_content = wp_strip_all_tags($record['NT']);
			$new_book['excerpt'] = (strlen($stripped_content) > $excerptlen) ? substr($stripped_content, 0, $excerptlen).'...' : $stripped_content;
			$new_book['content'] = $record['NT'];
		}
		$books[] = $new_book;
	}

	return $books;
}
