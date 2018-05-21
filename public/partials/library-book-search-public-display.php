<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Library_Book_Search
 * @subpackage Library_Book_Search/public/partials
 */

require_once( ABSPATH . 'wp-admin/includes/template.php' );

?>
<div class="load_overlay" id="loading" style=" display: none; ">
    <img src="<?php echo BOOK_SEARCH_PLUGIN_URL; ?>/public/images/loader.gif">
</div>

<div class="book-search-section">
	<form id="book-search">
		<div class="form-group">
			<label for="book_name"><?php echo __('Book Name', 'library-book-search') ?></label>
			<input type="text" name="book_name" id="book_name">
			<label for="author_name"><?php echo __('Author', 'library-book-search') ?></label>
			<input type="text" name="author_name" id="author_name">
		</div>
		<div class="form-group">
			<label for="publisher_name"><?php echo __('Publisher', 'library-book-search') ?></label>
			<select id="publisher_name" name="publisher_name">
				<option value="">Select Publisher</option>
				<?php
					$publishers = get_terms( array(
					    'taxonomy' => 'book_publisher',
					    'hide_empty' => false,
					) );
					foreach ($publishers as $publisher) { ?>
						<option value="<?php echo $publisher->term_id; ?>"><?php echo $publisher->name; ?></option>
				<?php	
					}
				?>
			</select>
			<label for="book_rating"><?php echo __('Rating', 'library-book-search') ?></label>
			<select id="book_rating" name="book_rating">
				<option value="">Select Ratings</option>
				<?php 
				$rate_count = 1;
				for( $rate_count = 1; $rate_count < 6 ; $rate_count++) { ?>
					<option><?php echo $rate_count ?></option>
				<?php
				}
				?>
			</select>
		</div>
		<div class="form-group">
			<label for="book_price"><?php echo __('Price', 'library-book-search') ?></label>
			<div id="slider-range"></div>
			<p id="amount" style="border:0; color:#f6931f; font-weight:bold;"></p>
			<input type="hidden" id="price_range" name="price_range" value="">
		</div>
		<div class="form-group">
			<input type="submit" id="search_book" value="<?php echo __('Search', 'library-book-search') ?>">	
		</div>
	</form>
</div>

<?php 

	//Get All Books
	$args = array(
		'post_type' => 'book',
		'posts_per_page' => 10,
	);	

	$query = new WP_Query( $args );
?>
<div class="books_section">
	<table class="all_books">
		<thead>
			<tr>
				<th><?php echo __( 'No.', 'library-book-search' ) ?></th>
				<th><?php echo __( 'Book Name', 'library-book-search' ) ?></th>
				<th><?php echo __( 'Price', 'library-book-search' ) ?></th>
				<th><?php echo __( 'Author', 'library-book-search' ) ?></th>
				<th><?php echo __( 'Publisher', 'library-book-search' ) ?></th>
				<th><?php echo __( 'Rating', 'library-book-search' ) ?></th>
			</tr>
		</thead>
		<tbody>
		<?php if( $query->have_posts() ) {
			$i = 1;
			while($query->have_posts()) : $query->the_post();
				$post_id = get_the_ID();
				$price = get_post_meta( $post_id, 'library_book_price', 'true' );
				$ratings = get_post_meta( $post_id, 'library_book_ratings', 'true' );
				$author = wp_get_post_terms( $post_id, 'book_author', array("fields" => "names") );
				$publisher = wp_get_post_terms( $post_id, 'book_publisher', array("fields" => "names") );
			?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></td>
				<td><?php echo !empty( $price ) ? $price : "-"; ?></td>
				<td><?php echo !empty( $author ) ? $author[0] : "-"; ?></td>
				<td><?php echo !empty( $publisher ) ? $publisher[0] : "-"; ?></td>
				<td><?php wp_star_rating( array('rating' => $ratings, 'type' => 'rating','number' => $ratings) );  ?></td>
			</tr>

		<?php
			$i++;
			endwhile;
		} else { ?>
			<tr><td colspan="6"><?php echo __('No Books Found!', 'library-book-search'); ?></td></tr>
		<?php
		} ?>
			
		</tbody>
	</table>
	<div class="book_pagination">
		<?php echo $this->ajaxpagination( $query->max_num_pages, 1); ?>
	</div>
</div>

