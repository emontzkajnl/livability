<?php
/**
 * Ajax Load More: Custom Repeaters *
 *
 * @package ALMTemplates
 */

if ( ! class_exists( 'ALMRepeaters' ) ) :

	/**
	 * Initiate the Class.
	 */
	class ALMRepeaters {

		/**
		 * Construct the class.
		 */
		public function __construct() {
			add_action( 'alm_unlimited_repeaters', [ $this, 'alm_unlimited_add_ons' ] );
			add_action( 'alm_get_unlimited_repeaters', [ $this, 'alm_get_unlimited_add_ons' ] );
			add_action( 'alm_unlimited_installed', [ $this, 'alm_is_unlimited_installed' ] );

			// Ajax actions.
			add_action( 'wp_ajax_alm_unlimited_create', [ $this, 'alm_unlimited_create' ] );
			add_action( 'wp_ajax_alm_unlimited_delete', [ $this, 'alm_unlimited_delete' ] );
		}

		/**
		 * Get absolute path to repeater directory base.
		 * Multisite installs directories will be `uploads/sites/{id}/alm_templates`
		 *
		 * @return string The absolute path to the repeater directory.
		 */
		public static function alm_templates_get_repeater_path() {
			$upload_dir = wp_upload_dir();
			$path       = apply_filters( 'alm_repeater_path', $upload_dir['basedir'] . '/alm_templates' );
			return $path;
		}

		/**
		 * Create repeater template directory.
		 *
		 * @param string $dir The directory path.
		 * @return void
		 */
		public static function alm_templates_mkdir( $dir ) {
			if ( ! is_dir( $dir ) ) {
				wp_mkdir_p( $dir ); // Make dir with permissions.

				// Check again after creating it (permission checker).
				if ( ! is_dir( $dir ) ) {
					echo esc_html_e( 'Error creating repeater template directory', 'ajax-load-more-templates' );
					echo ' - ' . esc_html( $dir );
				}
			}
		}

		/**
		 * List repeaters for selection on shortcode builder page.
		 *
		 * @return void
		 */
		public function alm_get_unlimited_add_ons() {
			global $wpdb;
			$table_name = $wpdb->prefix . ALM_TEMPLATES_TABLE_NAME;
			$rows       = $wpdb->get_results( "SELECT * FROM $table_name" ); // phpcs:ignore
			$i          = 0;
			foreach ( $rows as $repeater ) {
				// Get repeater alias, if avaialble.
				++$i;
				$name           = $repeater->name;
				$repeater_alias = $repeater->alias;
				if ( empty( $repeater_alias ) ) {
					echo '<option name="' . esc_attr( $name ) . '" id="chk-' . esc_attr( $name ) . '" value="' . esc_attr( $name ) . '">Template #' . esc_attr( $i ) . '</option>';
				} else {
					echo '<option name="' . esc_attr( $name ) . '" id="chk-' . esc_attr( $name ) . '" value="' . esc_attr( $name ) . '">' . esc_attr( $repeater_alias ) . '</option>';
				}
			}
		}

		/**
		 * An empty function to determine if custom repeater is true.
		 *
		 * @return void
		 */
		public function alm_is_unlimited_installed() {
			// Empty return
			// Function called from /ajax-load-more/admin/admin.php.
		}

		/**
		 * The frontend for the repeaters.
		 *
		 * @return void
		 */
		public function alm_unlimited_add_ons() {
			alm_templates_check_table(); // Create table if it doesn't exist.

			// Repeater loop.
			global $wpdb;
			$table_name = $wpdb->prefix . ALM_TEMPLATES_TABLE_NAME;
			$rowcount   = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" ); // phpcs:ignore
			$rows       = $wpdb->get_results( "SELECT * FROM $table_name" ); // phpcs:ignore
			?>
			<div id="unlmited-container">
				<?php
				if ( $rowcount > 0 ) :
					$i        = 0;
					$base_dir = self::alm_templates_get_repeater_path();
					self::alm_templates_mkdir( $base_dir );

					foreach ( $rows as $repeater ) :
						++$i;
						$repeater_file    = $repeater->name;
						$repeater_name    = 'Template #' . $i;
						$repeater_alias   = $repeater->alias;
						$repeater_content = $repeater->repeaterDefault; // phpcs:ignore
						if ( ! empty( $repeater_alias ) ) { // Set alias.
							$heading = $repeater_alias;
						} else {
							$heading = $repeater_name;
						}
						?>
				<div class="row template unlimited">
					<div>
						<h3 class="heading" data-default="<?php echo esc_html( $repeater_name ); ?>"><?php echo wp_kses_post( $heading ); ?></h3>
						<div class="expand-wrap">
							<div class="wrap repeater-wrap" data-name="<?php echo wp_kses_post( $repeater_file ); ?>" data-type="unlimited">
								<div class="alm-row">
									<div class="column column-6">
										<label for="alias-<?php echo esc_html( $repeater_file ); ?>">
											<?php esc_html_e( 'Template Alias', 'ajax-load-more-templates' ); ?>
											<span><?php esc_html_e( 'The unique name for this template.', 'ajax-load-more-templates' ); ?></span>
										</label>
										<?php
											$alias = ( empty( $repeater_alias ) ) ? $repeater_name : $repeater_alias;
											echo '<input type="text" id="alias-' . esc_html( $repeater_file ) . '" class="_alm_repeater_alias" value="' . esc_html( $alias ) . '" maxlength="55" placeholder="' . esc_html__( 'Blog Listing', 'ajax-load-more-templates' ) . '">';
										?>
									</div>
									<div class="column column-6">
										<label for="id-<?php echo esc_html( $repeater_file ); ?>">
											<?php esc_html_e( 'Template ID', 'ajax-load-more-templates' ); ?>
											<span><?php esc_html_e( 'The unique ID assigned to this template.', 'ajax-load-more-templates' ); ?></span>
										</label>
										<input type="text" class="disabled-input" id="id-<?php echo esc_html( $repeater_file ); ?>" value="<?php echo esc_html( $repeater_file ); ?>" readonly="readonly">
									</div>
								</div>

								<div class="alm-row no-padding-btm">
									<div class="column column-9">
										<label class="trigger-codemirror" data-id="<?php echo esc_html( $repeater_file ); ?>" for="template-<?php echo esc_html( $repeater_file ); ?>">
											<?php esc_html_e( 'Template Code', 'ajax-load-more-templates' ); ?>
											<span><?php esc_html_e( 'The PHP and HTML markup for this template.', 'ajax-load-more-templates' ); ?></span>
										</label>
										</div>
										<div class="column column-3">
											<?php do_action( 'alm_get_layouts' ); ?>
										</div>
									</div>

									<div class="alm-row">
										<div class="column textarea-wrap">
											<?php
											$filename = $base_dir . '/' . $repeater_file . '.php';
											$content  = '';
											if ( file_exists( $filename ) ) {
												// phpcs:ignore
												$handle   = fopen( $filename, 'r' );
												// phpcs:ignore
												$content = filesize( $filename ) !== 0 ? fread( $handle, filesize( $filename ) ) : '';
												// phpcs:ignore
												fclose( $handle );
											}
											?>
											<?php
											if ( ! $content ) { // Add warning if template doesn't exist in filesystem.
												?>
											<p class="warning-callout notify missing-template" style="margin: 10px 0 20px;">
												<?php esc_attr_e( 'This template is missing from the filesystem! Click the "Save Template" button to save the template.', 'ajax-load-more-templates' ); ?>
											</p>
											<?php } ?>
											<textarea rows="10" id="<?php echo esc_html( $repeater_file ); ?>" class="_alm_repeater"><?php echo $content ? $content : $repeater_content; //phpcs:ignore ?></textarea>
											<script>
											var editor_<?php echo esc_html( $repeater_file ); ?> = CodeMirror.fromTextArea(document.getElementById("<?php echo esc_html( $repeater_file ); ?>"),
											{
												mode:  "application/x-httpd-php",
												lineNumbers: true,
												styleActiveLine: true,
												lineWrapping: true,
												matchBrackets: true,
												viewportMargin: Infinity,
												foldGutter: true,
												viewportMargin: Infinity,
												gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
											});
											</script>
										</div>
									</div>

									<div class="alm-row">
										<div class="column">
											<input type="submit" value="<?php esc_html_e( 'Save Template', 'ajax-load-more-templates' ); ?>" class="button button-primary save-repeater" data-editor-id="<?php echo esc_html( $repeater_file ); ?>">
											<div class="saved-response">&nbsp;</div>
											<button type="button" class="alm-delete">
												<?php esc_html_e( 'Delete', 'ajax-load-more-templates' ); ?>
											</button>
											<?php
												$repeater_options = [
													'path' => $filename,
													'name' => $repeater_file,
													'type' => 'standard',
												];
												include ALM_PATH . 'admin/includes/components/repeater-options.php';
												unset( $repeater_options );
												?>
										</div>
									</div>

								</div>
							</div>
							<div class="clear"></div>
						</div>
					</div>
						<?php
					endforeach;
				endif;
				?>
			</div>
			<p class="alm-add-template" id="alm-add-template" style="margin-top: 20px">
				<a href="javascript:void(0);">
					<i class="fa fa-plus-square"></i> <?php esc_html_e( 'Add New Template', 'ajax-load-more-templates' ); ?>
				</a>
			</p>
			<script>
				jQuery(document).ready(function($) {
					// Check alias.
					$(document).on('keyup', '._alm_repeater_alias', function(){
						var el = $(this),
						heading = el.parent().parent().parent().parent().find('h3.heading');
						var val = el.val(),
						defaultVal = heading.data('default');
						if(val === ''){
							heading.text(defaultVal);
						}else{
							heading.text(val);
						}
					});

					// ADD template.
					$('#alm-add-template a').on('click', function(){
						var el = $(this);
						if(!el.hasClass('active')){
							el.addClass('active');

							// Create div
							var container = $('#unlmited-container'),
							div = $('<div class="row unlimited new" />');
							div.appendTo(container);
							div.fadeIn(250);

							// Run ajax.
							$.ajax({
								type: 'POST',
								url: alm_admin_localize.ajax_admin_url,
								data: {
								action: 'alm_unlimited_create',
								nonce: alm_admin_localize.alm_admin_nonce,
							},
							dataType: "JSON",
							success: function(data) {
								div.load("<?php echo esc_html( ALM_TEMPLATES_URL ); ?>/custom-repeaters/includes/template.php", {
									id: data.id,
									alias: data.alias,
									defaultVal: data.defaultVal
								}, function(){ // .load() complete.
									div.addClass('done');
									$('.unlimited-wrap', div).slideDown(350, 'alm_unlimited_ease', function(){
										div.removeClass('new');
										div.removeClass('done');
										el.removeClass('active');
										$('.CodeMirror').each(function(i, el){
											el.CodeMirror.refresh();
										});
										var input = $('input._alm_repeater_alias', div);
										if (input.length > 0) {
											input.focus();
											input.select();
										}

									});
								});
							},
							error: function(xhr, status, error) {
								responseText.html('<p><?php esc_attr_e( 'Error - Something went wrong and the template could not be created.', 'ajax-load-more-templates' ); ?></p>');
								div.remove();
								el.removeClass('active');
							}
							});
						}
					});

					// DELETE template.
					$(document).on('click', '.alm-delete', function(){
						var r = confirm("<?php esc_attr_e( 'Are you sure you want to delete this template?', 'ajax-load-more-templates' ); ?>");
						if (r == true && !$(this).hasClass('deleting')) {
							var el = $(this);
							var container = el.closest('.repeater-wrap');
							var item = container.parent().parent().parent('.row.unlimited');
							var repeater = container.data('name');

							el.addClass('deleting');
							item.addClass('deleting');
							$.ajax({
								type: 'POST',
								url: alm_admin_localize.ajax_admin_url,
								data: {
									action: 'alm_unlimited_delete',
									repeater: repeater,
									nonce: alm_admin_localize.alm_admin_nonce
								},
								dataType: "html",
								success: function(data) {
									setTimeout(function() {
										item.addClass('deleted');
										item.slideUp(350, 'alm_unlimited_ease', function(){
											item.remove();
										})
									}, 350);
								},
								error: function(xhr, status, error) {
									item.removeClass('deleting');
									el.removeClass('deleting');
									responseText.html('<p><?php esc_attr_e( 'Error - Something went wrong and the template could not be deleted.', 'ajax-load-more-templates' ); ?></p>');
								}
							});
						}
					});
					$.easing.alm_unlimited_ease = function (x, t, b, c, d) {
						if ((t /= d / 2) < 1) {
							return c / 2 * t * t + b;
						}
						return -c / 2 * ((--t) * (t - 2) - 1) + b;
					};
				});
				</script>
			<?php
		}

		/**
		 * Create a new Repeater Template.
		 *
		 * @return void
		 */
		public function alm_unlimited_create() {
			$form_data = filter_input_array( INPUT_POST );
			if ( ! current_user_can( 'edit_theme_options' ) || ! isset( $form_data['nonce'] ) ) {
				wp_die( esc_attr__( 'You don\'t belong here.', 'ajax-load-more-templates' ) ); // Bail early if missing WP capabilities or nonce.
			}

			if ( ! wp_verify_nonce( $form_data['nonce'], 'alm_repeater_nonce' ) ) {
				wp_die( esc_attr__( 'Error - unable to verify nonce, please try again.', 'ajax-load-more-templates' ) ); // Verify nonce.
			}

			// Create table if it doesn't exist.
			alm_templates_check_table();

			// Get values from DB.
			global $wpdb;
			$table_name = $wpdb->prefix . ALM_TEMPLATES_TABLE_NAME;

			$count = floatval( $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" ) ); // phpcs:ignore
			$count = ++$count;

			$default_value = '<?php // ' . __( 'Enter your template code here', 'ajax-load-more-templates' ) . '.  ?>';

			// Insert into DB.
			$wpdb->insert(
				$table_name,
				[
					'name'            => 'temp',
					'repeaterDefault' => $default_value,
					'alias'           => '',
				]
			);

			$id            = $wpdb->insert_id; // Get new primary key value (id).
			$data_new      = [ 'name' => 'template_' . $id ];
			$data_previous = [ 'name' => 'temp' ];
			$wpdb->update( $table_name, $data_new, $data_previous );

			// Set new template name.
			$template = 'template_' . $id;

			$base_dir = self::alm_templates_get_repeater_path();
			self::alm_templates_mkdir( $base_dir );

			$f    = $base_dir . '/' . $template . '.php';
			$file = fopen( $f, 'w' ) or die( 'Error opening file' ); // phpcs:ignore
			$w    = fwrite( $file, $default_value ) or die( 'Error writing file' ); // phpcs:ignore

			$return               = [];
			$return['id']         = $template;
			$return['alias']      = __( 'Template #', 'ajax-load-more-templates' ) . '' . $count;
			$return['defaultVal'] = $default_value;

			wp_send_json( $return );
		}

		/**
		 * Delete a Repeater Template.
		 *
		 * @return void
		 */
		public function alm_unlimited_delete() {
			$form_data = filter_input_array( INPUT_POST );
			if ( ! current_user_can( 'edit_theme_options' ) || ! isset( $form_data['nonce'] ) ) {
				wp_die( esc_attr__( 'You don\'t belong here.', 'ajax-load-more-templates' ) ); // Bail early if missing WP capabilities or nonce.
			}

			if ( ! wp_verify_nonce( $form_data['nonce'], 'alm_repeater_nonce' ) ) {
				wp_die( esc_attr__( 'Error - unable to verify nonce, please try again.', 'ajax-load-more-templates' ) ); // Verify nonce.
			}

			// Create table if it doesn't exist.
			alm_templates_check_table();

			global $wpdb;
			$table_name = $wpdb->prefix . ALM_TEMPLATES_TABLE_NAME;

			$template = Trim( stripslashes( $form_data['repeater'] ) ); // Repeater name for deletion.

			$wpdb->delete( $table_name, [ 'name' => $template ] ); // Delete from db.

			// Get base directory.
			$base_dir = self::alm_templates_get_repeater_path();

			// Delete file from server.
			$file_delete = $base_dir . '/' . $template . '.php';
			if ( file_exists( $file_delete ) ) {
				unlink( $file_delete );
			}

			// See if repeater exists again to be sure it was removed.
			if ( file_exists( $file_delete ) ) {
				esc_html_e( 'Template could not be deleted.', 'ajax-load-more-templates' );
			} else {
				esc_html_e( 'Template deleted successfully.', 'ajax-load-more-templates' );
			}

			wp_die();
		}
	}

	// Init the class.
	global $alm_custom_repeaters;
	$alm_custom_repeaters = new ALMRepeaters();

endif;
