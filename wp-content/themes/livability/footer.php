<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

?>
			</main><!-- #main -->
		</div><!-- #primary -->
	</div><!-- #content -->


	<?php get_template_part('template-parts/content/content-newsletter-two'); ?>


	<footer id="colophon" class="site-footer" role="contentinfo">
        <div class="container site-footer-info">
            <div class="footer-about">
            <div class="site-name">
				<?php if ( has_custom_logo() ) : ?>
					<div class="site-logo"><?php the_custom_logo(); ?></div>
				<?php else : ?>
					<?php if ( get_bloginfo( 'name' ) && get_theme_mod( 'display_title_and_tagline', true ) ) : ?>
						<?php if ( is_front_page() && ! is_paged() ) : ?>
							<?php bloginfo( 'name' ); ?>
						<?php else : ?>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
						<?php endif; ?>
					<?php endif; ?>
				<?php endif; ?>
			</div><!-- .site-name -->
			
			<div class="footer-social">
			<?php
			$facebook = get_option('options_facebook');
			$twitter = get_option('options_twitter');
			$instagram = get_option('options_instagram');
			$pinterest = get_option('options_pinterest');
			$linkedin = get_option('options_linkedin');
			?>
			<ul>
				<?php 
				if($facebook) {echo '<li><a href="'.esc_url($facebook).'" target="_blank" ><i class="fab fa-facebook-f"></i></a></li>';} 
				if($twitter) {echo '<li><a href="'.esc_url($twitter).'" target="_blank" ><i class="fab fa-twitter"></i></a></li>';} 
				if($instagram) {echo '<li><a href="'.esc_url($instagram).'" target="_blank" ><i class="fab fa-instagram"></i></a></li>';} 
				if($pinterest) {echo '<li><a href="'.esc_url($pinterest).'" target="_blank" ><i class="fab fa-pinterest-p"></i></a></li>';} 
				if($linkedin) {echo '<li><a href="'.esc_url($linkedin).'" target="_blank" ><i class="fab fa-linkedin-in"></i></a></li>';} 
				?>
			</ul>
			</div>
            <?php $footer_text = get_option('options_footer_text');
            echo $footer_text; ?>
            </div>
            <div class="footer-menu-area">
            <?php if ( has_nav_menu( 'footer' ) ) : ?>
			<nav aria-label="<?php esc_attr_e( 'Secondary menu', 'livablity' ); ?>" class="footer-navigation">
				<ul class="footer-navigation-wrapper">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'items_wrap'     => '%3$s',
							'container'      => false,
							'depth'          => 1,
							'link_before'    => '<span>',
							'link_after'     => '</span>',
							'fallback_cb'    => false,
						)
					);
					?>
				</ul><!-- .footer-navigation-wrapper -->
			</nav><!-- .footer-navigation -->
		<?php endif; ?>
            </div>
        </div>
		<div class="site-info">
			<div class="powered-by">
				<?php
				$year = date("Y");
				echo sprintf('Copyright 2010-%s Livability - Journal Communications, Inc', $year);
				?>
			</div><!-- .powered-by -->
			</div><!-- .site-info -->
			<div class="subfooter-links">
				<ul>
					<li><a href="<?php echo get_site_url(); ?>/privacy-policy">Privacy Policy</a></li>
					<li><a href="<?php echo get_site_url(); ?>/terms-of-use">Terms of Use</a></li>
				</ul>
			</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<div class="search-pop-up">
<div class="close-search"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#fff" d="M376.6 84.5c11.3-13.6 9.5-33.8-4.1-45.1s-33.8-9.5-45.1 4.1L192 206 56.6 43.5C45.3 29.9 25.1 28.1 11.5 39.4S-3.9 70.9 7.4 84.5L150.3 256 7.4 427.5c-11.3 13.6-9.5 33.8 4.1 45.1s33.8 9.5 45.1-4.1L192 306 327.4 468.5c11.3 13.6 31.5 15.4 45.1 4.1s15.4-31.5 4.1-45.1L233.7 256 376.6 84.5z"/></svg></div>
</div>
<?php get_template_part( 'template-parts/mobile-search-form' ); 
if (get_post_type() == 'liv_place') {
	get_template_part( 'template-parts/blocks/popup-top-100-map-24' );
} ?>


<?php wp_footer(); ?>
</body>
</html>