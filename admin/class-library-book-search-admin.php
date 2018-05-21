<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Library_Book_Search
 * @subpackage Library_Book_Search/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Library_Book_Search
 * @subpackage Library_Book_Search/admin
 * @author     Hardik Thakkar <thakkarhardik12@gmail.com>
 */
class Library_Book_Search_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action( 'save_post', array( $this, 'save_book_data' ) );

	}

    /**
     * Show Admin Menus
     *
     * @since    1.0.0
     */
    public function show_admin_menu() {
        add_menu_page( 'Library Book Search', 'Library Book Search', 'manage_options', 'library-book-search', array( $this, 'show_dashboard_screen' ), '', 98 );
    }

	/**
     * Data Import Admin Page.
     *
     * @since    1.0.0
     */
    public function show_dashboard_screen() {
        include_once ( plugin_dir_path(__FILE__) . 'partials/library-book-search-admin-display.php' );
    }    	

	/**
	 * Register Custom Post Type Book.
	 *
	 * @since    1.0.0
	 */
	public function register_book_post_type() {

		if(post_type_exists( 'book' ))
			return;

		$labels = array(
			'name'               => _x( 'Books', 'post type general name', 'library-book-search' ),
			'singular_name'      => _x( 'Book', 'post type singular name', 'library-book-search' ),
			'menu_name'          => _x( 'Books', 'admin menu', 'library-book-search' ),
			'name_admin_bar'     => _x( 'Book', 'add new on admin bar', 'library-book-search' ),
			'add_new'            => _x( 'Add New', 'book', 'library-book-search' ),
			'add_new_item'       => __( 'Add New Book', 'library-book-search' ),
			'new_item'           => __( 'New Book', 'library-book-search' ),
			'edit_item'          => __( 'Edit Book', 'library-book-search' ),
			'view_item'          => __( 'View Book', 'library-book-search' ),
			'all_items'          => __( 'All Books', 'library-book-search' ),
			'search_items'       => __( 'Search Books', 'library-book-search' ),
			'parent_item_colon'  => __( 'Parent Books:', 'library-book-search' ),
			'not_found'          => __( 'No books found.', 'library-book-search' ),
			'not_found_in_trash' => __( 'No books found in Trash.', 'library-book-search' )
		);

		$args = array(
			'labels'             => $labels,
	        'description'        => __( 'Books are the core post type for the plugin operations.', 'library-book-search' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'book' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' )
		);

		register_post_type( 'book', $args );

	}

	/**
	 * Register taxonomies for Books.
	 *
	 * @since    1.0.0
	 */
	public function register_book_taxonomies() {

		// Book Author Taxonomy
		$author_labels = array(
            'name'              => _x("Author", 'taxonomy general name', 'library-book-search'),
            'singular_name'     => _x("Author", 'taxonomy singular name', 'library-book-search'),
            'search_items'      => __('Search Author', 'library-book-search'),
            'all_items'         => __('All Authors', 'library-book-search'),
            'parent_item'       => __('Parent Author', 'library-book-search'),
            'parent_item_colon' => __('Parent Author', 'library-book-search'),
            'edit_item'         => __('Edit Author', 'library-book-search'),
            'update_item'       => __('Update Author', 'library-book-search'),
            'add_new_item'      => __('Add New Author', 'library-book-search'),
            'new_item_name'     => __('New Author', 'library-book-search'),
            'menu_name'         => __('Author', 'library-book-search'),
        );

        $author_args = array(
            'hierarchical'      => true,
            'labels'            => $author_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
        );

        register_taxonomy('book_author', array( 'book' ), $author_args);

		// Book Publisher Taxonomy
		$publisher_labels = array(
            'name'              => _x("Publisher", 'taxonomy general name', 'library-book-search'),
            'singular_name'     => _x("Publisher", 'taxonomy singular name', 'library-book-search'),
            'search_items'      => __('Search Publisher', 'library-book-search'),
            'all_items'         => __('All Publishers', 'library-book-search'),
            'parent_item'       => __('Parent Publisher', 'library-book-search'),
            'parent_item_colon' => __('Parent Publisher', 'library-book-search'),
            'edit_item'         => __('Edit Publisher', 'library-book-search'),
            'update_item'       => __('Update Publisher', 'library-book-search'),
            'add_new_item'      => __('Add New Publisher', 'library-book-search'),
            'new_item_name'     => __('New Publisher', 'library-book-search'),
            'menu_name'         => __('Publisher', 'library-book-search'),
        );

        $publisher_args = array(
            'hierarchical'      => true,
            'labels'            => $publisher_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
        );

        register_taxonomy('book_publisher', array( 'book' ), $publisher_args);

	}

	/**
	 * Register Meta Fields for Books.
	 *
	 * @since    1.0.0
	 */
	public function register_book_meta_fields() {

		add_meta_box(
		        'book_details',
		        'Books Details',
		        array( $this, 'render_library_meta' ),
		        'book',
		        'normal',
		        'high'
		);

	}

	/**
	 * Render Meta Fields for Books.
	 *
	 * @since    1.0.0
	 */
	public function render_library_meta() {

		global $post;

	    // Use nonce for verification to secure data sending
	    wp_nonce_field( 'library_book_data_save', 'library_book_details' );
	    $book_price = get_post_meta( get_the_id(), 'library_book_price', true );
	    $book_ratings = get_post_meta( get_the_id(), 'library_book_ratings', true );
	    ?>

	    <div id="library-book-meta-manager">
			<table id="library-book-meta-manager-data">
				<tr>
					<td class="key"><?php echo __('Book Price', 'library-book-search') ?></td>
					<td class="value">
						<input type="text" class="" name="library_book_price" value="<?php echo $book_price ?>" onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57"> ( $ )
					</td>
				</tr>
				<tr>
					<td class="key"><?php echo __('Book Ratings', 'library-book-search') ?></td>
					<td class="value">
						<select name="library_book_ratings">
							<?php 
							$i = 1;
							for( $i = 1; $i < 6; $i++ ) {
								echo '<option value="' . $i . '"'. selected( $book_ratings, $i, false ) . ' >' . $i . '</option>';
							}
						?>
						</select>
					</td>
				</tr>
			</table>
		</div>
	    <?php

	}

	/**
	 * Save Book Meta Fields.
	 *
	 * @since    1.0.0
	 */
	public function save_book_data( $post_id ) {

 		// verify nonce
  		if ( !isset( $_POST['library_book_details'] ) || !wp_verify_nonce( $_POST['library_book_details'], 'library_book_data_save') ) {
  			return;
  		}

  		$book_price = $_POST['library_book_price'];
  		$book_ratings = $_POST['library_book_ratings'];
      	update_post_meta( $post_id, 'library_book_price', $book_price );
      	update_post_meta( $post_id, 'library_book_ratings', $book_ratings );

	}

		
}
	