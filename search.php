<?php
/**
 * Search results template
 *
 * @package    Receptar
 * @copyright  2015 WebMan - Oliver Juhas
 *
 * @since    1.0
 * @version  1.0
 */



get_header();

	?>

	<section id="search-results" class="search-results">

		<header class="page-header">

			<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'receptar' ), '<span>' . get_search_query() . '</span>' ); ?></h1>

		</header>

		<?php get_template_part( 'loop', 'search' ); ?>

	</section>

	<?php

get_footer();

?>