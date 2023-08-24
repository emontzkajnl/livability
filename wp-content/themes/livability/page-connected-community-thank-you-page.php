<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header(); ?>
<script
src="https://connect.transactiongateway.com/token/Collect.js" 
data-tokenization-key="67hgFg-bGM4BN-C5qx8G-7t5Q37"
data-payment-selector="#payButton"
data-field-ccnumber-placeholder = '0000 0000 0000 0000'
data-field-ccexp-placeholder = '00 / 00'
data-field-cvv-placeholder = '123'
data-field-checkaccount-placeholder = '000000000000'
data-field-checkaba-placeholder = '000000000'
data-field-checkname-placeholder = 'Customer Name'
data-variant="inline"
>
</script>
<?php $hero_section = get_field('display_hero');
if ($hero_section) {
	get_template_part( 'template-parts/page-hero-section' );
}

/* Start the Loop */
while ( have_posts() ) :
	the_post();


	get_template_part( 'template-parts/content/content-cc-thank-you' );



endwhile; // End of the loop.
// dynamic_sidebar( 'sidebar-2' );

get_footer();