<?php
/**
 * The template for displaying all single books and details
 */

get_header();

require_once( ABSPATH . 'wp-admin/includes/template.php' );

?>
<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php
			// Start the loop.
			while ( have_posts() ) :
				the_post();
					$post_id = get_the_ID();
					$price = get_post_meta( $post_id, 'library_book_price', 'true' );
					$ratings = get_post_meta( $post_id, 'library_book_ratings', 'true' );
					$author = wp_get_post_terms( $post_id, 'book_author' );
					$publisher = wp_get_post_terms( $post_id, 'book_publisher' );

			?>	
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					</header><!-- .entry-header -->
					<div class="entry-content">
						<div class="author_sec book_detail">
							<label for="author"><?php echo __('Author', 'library-book-search'); ?></label>
							<a href="<?php echo get_term_link($author[0], 'book_auhtor') ?>"><?php echo $author[0]->name; ?></a>	
						</div>
						<div class="publisher_sec book_detail">
							<label for="publisher"><?php echo __('Publisher', 'library-book-search'); ?></label>
							<a href="<?php echo get_term_link($publisher[0], 'book_publisher') ?>"><?php echo $publisher[0]->name; ?></a>	
						</div>
						<div class="desc_sec book_detail">
							<label for="desc"><?php echo __('Description', 'library-book-search'); ?></label>
							<p><?php echo !empty(get_the_content()) ? get_the_content() : "No Description found!"; ?></p>
						</div>
						<div class="rating_sec book_detail">
							<label for="rating"><?php echo __('Ratings', 'library-book-search'); ?></label>
							<?php wp_star_rating( array('rating' => $ratings, 'type' => 'rating','number' => $ratings) );  ?>
						</div>
						<div class="price_sec book_detail">
							<label for="author"><?php echo __('Price', 'library-book-search'); ?></label>
							<p><?php echo !empty($price) ? $price . " $" : " - "; ?></p>
						</div>
					</div>
				</article><!-- #post-## -->	

			<?php
				// End of the loop.
			endwhile;
			?>

		</main><!-- .site-main -->

	<?php get_sidebar( 'content-bottom' ); ?>

	</div><!-- .content-area -->
</div>

<?php 
get_sidebar(); 
get_footer(); 
