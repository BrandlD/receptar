<?php
/**
 * WP admin modifications
 *
 * @package    Receptar
 * @copyright  2015 WebMan - Oliver Juhas
 *
 * @since    1.0
 * @version  1.0
 *
 * CONTENT:
 * - 10) Actions and filters
 * - 20) Assets
 * - 30) Posts list table
 */





/**
 * 10) Actions and filters
 */

	/**
	 * Actions
	 */

		//Styles and scripts
			add_action( 'admin_enqueue_scripts', 'receptar_assets_admin' );
		//Posts list table
			//Posts
				add_action( 'manage_post_posts_columns',                    'receptar_post_columns_register', 10    );
				add_action( 'manage_post_posts_custom_column',              'receptar_post_columns_render',   10, 2 );
			//Pages
				add_action( 'manage_pages_columns',                         'receptar_post_columns_register', 10    );
				add_action( 'manage_pages_custom_column',                   'receptar_post_columns_render',   10, 2 );
			//Jetpack Portfolio posts
				add_action( 'manage_edit-jetpack-portfolio_columns',        'receptar_post_columns_register', 10    );
				add_action( 'manage_jetpack-portfolio_posts_custom_column', 'receptar_post_columns_render',   10, 2 );





/**
 * 20) Assets
 */

	/**
	 * Admin HTML head assets enqueue
	 *
	 * @since    1.0
	 * @version  1.0
	 */
	if ( ! function_exists( 'receptar_assets_admin' ) ) {
		function receptar_assets_admin() {
			//Helper variables
				global $current_screen;

				$custom_styles = '';

			//Enqueue (only on specific admin pages)
				if ( in_array( $current_screen->base, array( 'edit', 'post' ) ) ) {
					//Styles
						wp_enqueue_style(
								'receptar-admin-styles',
								receptar_get_stylesheet_directory_uri( 'css/admin.css' ),
								false,
								WM_SCRIPTS_VERSION,
								'screen'
							);

					//Styles - inline
						$custom_styles .= '#post-' . get_option( 'page_on_front' ) . ' { background: #d7eef4; }';
						$custom_styles .= '#post-' . get_option( 'page_for_posts' ) . ' { background: #d7f4e3; }';

						wp_add_inline_style(
								'receptar-admin-styles',
								apply_filters( 'wmhook_esc_css', $custom_styles )
							);

				}
		}
	} // /receptar_assets_admin





/**
 * 30) Posts list table
 */

	/**
	 * Register table columns
	 *
	 * @since    1.0
	 * @version  1.0
	 *
	 * @param  array $columns
	 */
	if ( ! function_exists( 'receptar_post_columns_register' ) ) {
		function receptar_post_columns_register( $columns ) {
			//Preparing output
				if ( 'jetpack-portfolio' == get_post_type() ) {
					unset( $columns['thumbnail'] );
				}

				$add             = array_slice( $columns, 0, 1 );
				$add['wm-thumb'] = __( 'Image', 'receptar' );

			//Output
				return apply_filters( 'wmhook_receptar_post_columns_register_output', array_merge( $add, array_slice( $columns, 1 ) ) );
		}
	} // /receptar_post_columns_register



	/**
	 * Admin post list columns content
	 *
	 * @since    1.0
	 * @version  1.0
	 *
	 * @param  string $column
	 * @param  absint $post_id
	 */
	if ( ! function_exists( 'receptar_post_columns_render' ) ) {
		function receptar_post_columns_render( $column, $post_id ) {
			//Thumbnail renderer
				if ( 'wm-thumb' === $column ) {

					$size = ( class_exists( 'Jetpack_Portfolio' ) ) ? ( 'jetpack-portfolio-admin-thumb' ) : ( 'thumbnail' );
					$size = apply_filters( 'wmhook_wm-thumb_size', $size );

					$image = ( has_post_thumbnail() ) ? ( get_the_post_thumbnail( $post_id, $size ) ) : ( '' );

					$thumb_class  = ( $image ) ? ( ' has-thumb' ) : ( ' no-thumb' );
					$thumb_class .= ' size-' . $size;

					echo '<span class="wm-image-container' . $thumb_class . '">';

					if ( get_edit_post_link() ) {
						edit_post_link( $image );
					} else {
						echo '<a href="' . esc_url( get_permalink() ) . '">' . $image . '</a>';
					}

					echo '</span>';

				}
		}
	} // /receptar_post_columns_render

?>