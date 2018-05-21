<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Library_Book_Search
 * @subpackage Library_Book_Search/admin/partials
 */
?>

<h2><?php echo __("Library Book Search", "library-book-search"); ?></h2>

<label for="library-book-search-shortcode">Copy this shortcode and paste it into your post, page, or text widget content:</label>

<span class="shortcode wp-ui-highlight">
	<input type="text" id="library-book-search-shortcode" onfocus="this.select();" readonly="readonly" class="large-text code" value="[library-books]">
</span>