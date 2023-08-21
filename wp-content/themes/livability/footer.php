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
	<div class="newsletter">
		<div class="container">
			<div class="nl-inner-container">
			<div class="nl-text-area">
				<?php if (get_option( 'options_newsletter_heading')) {echo '<h3>'.get_option( 'options_newsletter_heading').'</h3>';}
				if (get_option('options_newsletter_text')) {echo get_field('newsletter_text','options');} ?>
			</div>
		<?php echo do_shortcode( '[gravityform id="1" title="false" description="false" ajax="true"]'); ?>
		</div>
		</div>
	</div>

	


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
<?php wp_footer(); ?>
</body>
</html>