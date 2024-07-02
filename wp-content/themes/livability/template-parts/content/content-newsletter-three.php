<div class="newsletter">
		<div class="container">
           
			<div class="nl-inner-container">
            <!-- <div class="nl-img-container"> -->
                <!-- <img class="nl-phone-image" src="<?php //echo get_stylesheet_directory_uri(  ); ?>/assets/images/hand-and-phone.svg" /> -->
            <!-- </div> -->
                <div class="nl-text-area">
                    <?php if (get_option( 'options_newsletter_heading')) {echo '<h3>'.get_option( 'options_newsletter_heading').'</h3>';}
                    if (get_option('options_newsletter_text')) {echo get_field('newsletter_text','options');} ?>
                </div>
		        <form action="/newsletter" method="GET" class="html-newsletter">
                    <div class="html-newsletter__email">
                        <label for="html-email">Email Address</label>
                        <input name="html-email" type="email" placeholder="Email Address...">
                    </div>
                    <div class="html-newsletter__submit">
                        <label for="">Subscribe</label>
                        <input type="submit" value="Subscribe">
                    </div>
                </form>
                
		    </div>
		</div>
	</div>