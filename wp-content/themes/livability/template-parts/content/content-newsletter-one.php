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