<?php

/**
 * The Book Search and Result's Operation Class.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Library_Book_Search
 * @subpackage Library_Book_Search/public
 */

class Library_Book_Search_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action( 'wp_ajax_search_books', array( $this, 'search_books_callback' ) );
		add_action( 'wp_ajax_nopriv_search_books', array( $this, 'search_books_callback' ) );
		add_filter( 'single_template', array( $this, 'load_single_book_template' ) );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( 'jquery-ui', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.min.css', array(), $this->version, 'all' );

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/library-book-search-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'jquery-ui', plugin_dir_url( __FILE__ ) . 'js/jquery-ui.min.js', array( 'jquery' ), $this->version, true );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/library-book-search-public.js', array( 'jquery' ), $this->version, true );

		wp_localize_script( $this->plugin_name, 'book_search_params', array(
                    'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php',
                ) );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function library_books_shortcode() {

		ob_start();

		include BOOK_SEARCH_PLUGIN_DIR.'/public/partials/library-book-search-public-display.php';
		
		$template = ob_get_clean();

		return $template;

	}

	/**
	 * Ajax Pagination.
	 *
	 * @since    1.0.0
	 * @param      int       $pages      Total Number of Pages.
	 * @param      int    	 $paged    	 Current Page.
	 * @param      int    	 $range      Range to devide pagination links
	 * @return     string    Pagination Links HTML
	 */
	public function ajaxpagination( $pages = '', $paged, $range = 4 ) {
	    $morepages = ($range * 2) + 1;
	    $page_html = '';
	    if (empty($paged))
	        $paged = 1;

	    if ($pages >= 1) {
	        $page_html .= ' <div class="holder" id="holder">';
	        $prev_class = 'jp-previous ';
	        if ($paged == 1) {
	            $prev_class .= ' jp-disabled dis_page';
	        }
	        $page_html .= '<a id="top-previous" href="javascript:void(0);" class="' . $prev_class . '" data-page_id="' . ($paged - 1) . '"></a>';
	        if ($paged >= 1 && $paged > $range + 1 && $morepages < $pages)
	            $page_html .= '<a href="javascript:void(0);"  data-page_id="1">1</a><span class="separate">...</span>';
	        for ($i = 1; $i <= $pages; $i++) {
	            if (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $morepages) {
	                $page_html .= ($paged == $i) ? '<a href="javascript:void(0);"  class="jp-current dis_page"  data-page_id="' . $i . '">' . $i . '</a>' : '<a href="javascript:void(0);"  data-page_id="' . $i . '">' . $i . '</a>';
	            }
	        }
	        if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $morepages < $pages)
	            $page_html .= '<span class="separate">...</span><a href="javascript:void(0);"  data-page_id="' . $pages . '">' . $pages . '</a>';
	        $next_class = 'jp-next ';
	        if ($paged == $pages) {
	            $next_class .= ' jp-disabled dis_page';
	        }
	        $page_html .= '<a id="top-next" href="javascript:void(0);" class="' . $next_class . '" data-page_id="' . ($paged + 1) . '"></a>';
	        $page_html .= ' </div';
	    }
	    return $page_html;
	}

	/**
	 * Main AJAX Search Funciton.
	 *
	 * @since    1.0.0
	 * @return     string    $output      Return Search Results
	 */
	public function search_books_callback() {

		$paged = isset( $_POST['paged'] ) ? $_POST['paged'] : 1;
		$args = array(
			'post_type' => 'book',
			'posts_per_page' => 10,
			'paged' => $paged,
		);	

		if( isset( $_POST['book_rating'] ) && $_POST['book_rating'] !="" ) {
			$meta_query[] = array(
					'key' => 'library_book_ratings',
					'value' => $_POST['book_rating'],
					'compare' => '='
				);
		}

		if( isset( $_POST['price_range'] ) && $_POST['price_range'] !="" ) {
			$range_array = explode( '-', $_POST['price_range'] );
			$meta_query[] = array(
					'key' => 'library_book_price',
					'value' => $range_array,
					'type'    => 'numeric',
					'compare' => 'BETWEEN',
				);
		}

		if( isset( $_POST['author_name'] ) && $_POST['author_name'] !="" ) {
			global $wpdb;
	
			$term_ids = array(); 
      		$cat_args = "SELECT * FROM $wpdb->terms WHERE name LIKE '%".$_POST['author_name']."%' ";
      		$cats = $wpdb->get_results($cat_args, OBJECT);
      		array_push($term_ids,$cats[0]->term_id);
			$tax_query[] = array(
	            array(
	                'taxonomy' => 'book_author',
	                'terms' => $term_ids,
	                'field' => 'id',
	            ),
	        );
		}	

		if( isset( $_POST['publisher_name'] ) && $_POST['publisher_name'] !="" ) {
			$tax_query[] = array(
	                'taxonomy' => 'book_publisher',
	                'terms' => $_POST['publisher_name'],
	                'operator' => 'IN'
	            );
		}

		if (!empty($_POST['book_name'])) {
        	$args['post_title'] = trim($_POST['book_name']);
    	}

		$args['meta_query'] = $meta_query;
	    $args['tax_query'] = $tax_query;

	    add_filter('posts_where', array( $this, 'title_filter' ), 10, 2);
		
		$query = new WP_Query( $args );

		remove_filter('posts_where', array( $this, 'title_filter' ), 10, 2);

		$output = '';

		$output .= '<table class="all_books">
		<thead>
			<tr>
				<th>'. __( 'No.', 'library-book-search' ) .'</th>
				<th>'.  __( 'Book Name', 'library-book-search' ) .'</th>
				<th>'. __( 'Price', 'library-book-search' ) .'</th>
				<th>'. __( 'Author', 'library-book-search' ) .'</th>
				<th>'. __( 'Publisher', 'library-book-search' ) .'</th>
				<th>'. __( 'Rating', 'library-book-search' ) .'</th>
			</tr>
		</thead>
		<tbody>';

		if( $query->have_posts() ) {
			$i = 1;
			while($query->have_posts()) : $query->the_post();
				$post_id = get_the_ID();
				$price = get_post_meta( $post_id, 'library_book_price', 'true' );
				$ratings = get_post_meta( $post_id, 'library_book_ratings', 'true' );
				$author = wp_get_post_terms( $post_id, 'book_author', array("fields" => "names") );
				$publisher = wp_get_post_terms( $post_id, 'book_publisher', array("fields" => "names") );
			
		$output .= '<tr>
				<td>'. $i .'</td>
				<td><a href="' . get_the_permalink() . '">'. get_the_title().'</a></td>
				<td>'. (!empty( $price ) ? $price : "-") .'</td>
				<td>'. (!empty( $author ) ? $author[0] : "-") .'</td>
				<td>'. (!empty( $publisher ) ? $publisher[0] : "-") .'</td>
				<td>'. wp_star_rating( array('rating' => $ratings, 'type' => 'rating','number' => $ratings, 'echo' => false) ) .'</td>
			</tr>';

			$i++;
			endwhile;
		} else { 
			$output	.= '<tr><td colspan="6">'. __('No Books Found!', 'library-book-search').'</td></tr>';
		} 
			
		$output .= '</tbody>
		</table>
		<div class="book_pagination">
			'. $this->ajaxpagination( $query->max_num_pages, $paged) .'
		</div>';
		echo $output;
		wp_die();
	}


	/**
	 * LIKE Query Filter for Post Titles.
	 *
	 * @since    1.0.0
	 * @param      string    $where      The $where clause of query.
	 * @param      object    $wp_quey    The wp query object.
	 * @return     string    $where      Modified where clause
	 */
	public function title_filter( $where, &$wp_query ) {
		
		global $wpdb;
	    
	    if ($search_term = $wp_query->get('post_title')) {
	        $where .= ' AND (' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql($wpdb->esc_like($search_term)) . '%\')';
	    }

    	return $where;
	}

	/**
	 * Create Single Books Page from plugin 
	 *
	 * @since    1.0.0
	 * @return   file    $template    Returns Template to be used as a single page for Books
	 */
	public function load_single_book_template( $template ) {

	    global $post;

	    if ($post->post_type == "book" && $template !== locate_template(array("single-book.php"))){
	        /* This is a "movie" post 
	         * AND a 'single movie template' is not found on 
	         * theme or child theme directories, so load it 
	         * from our plugin directory
	         */
	        return dirname( __FILE__) . "/partials/single-book.php";
	    }

	    return $template;

	}
}
