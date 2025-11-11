<?php
/**
 * The New/Edit filter view.
 *
 * @see admin.php?page=ajax-load-more-filters&action=new
 * @package ALMFilters
 */

// Pass current filter attributes as JS variable.
?>
<script>
	var alm_filters = <?php echo $filter_vue ? wp_json_encode( $filter_vue ) : '""'; ?>;
	var alm_filter_id = <?php echo $filter_vue ? '"' . $filter_id . '"' : '""'; ?>;
</script>
<?php

// Does this filter contain a facets array.
$has_facets = isset( $filter['facets'] ) && $filter['facets'] === true;

// Set a default filter status if it doesn't exist.
if ( $has_facets && $filter_id ) {
	ALM_Facets::set_default_facet_status( $filter_id );
}
?>

<!-- Start app -->
<div class="ajax-load-more-inner-wrapper" id="app">
	<!-- MAIN COLUMN -->
	<div class="cnkt-main stylefree">
		<div class="alm-filters">
			<?php require ALM_FILTERS_PATH . 'admin/views/includes/navigation.php'; ?>
			<div class="alm-content-wrap">
				<header class="alm-filter--intro">
					<?php if ( ! $editing ) { ?>
					<h2><?php esc_attr_e( 'Add New Filter', 'ajax-load-more-filters' ); ?></h2>
					<p><?php esc_attr_e( 'Create an Ajax Load More filter by customizing the options below.', 'ajax-load-more-filters' ); ?></p>
					<?php } else { ?>
					<div>
						<h2><?php esc_attr_e( 'Filter:', 'ajax-load-more-filters' ); ?> <strong v-cloak>{{ data[0].id }}</strong></h2>
						<p>
							<?php esc_attr_e( 'Edit this filter by adjusting the options below.', 'ajax-load-more-filters' ); ?>
						</p>
					</div>
					<a href="<?php echo esc_attr( get_home_url() ); ?>?alm_filters_preview=<?php echo esc_attr( $filter_id ); ?>" target="_blank" class="button"><?php esc_attr_e( 'Preview Filter', 'ajax-load-more-filters' ); ?></a>
					<?php } ?>
				</header>

				<header class="alm-filter--header">
					<div class="title-area">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M480 544C515.3 544 544 515.3 544 480L544 160C544 124.7 515.3 96 480 96L160 96C124.7 96 96 124.7 96 160L96 480C96 515.3 124.7 544 160 544L480 544zM480 400C480 413.3 469.3 424 456 424L416 424L416 456C416 469.3 405.3 480 392 480C378.7 480 368 469.3 368 456L368 424L184 424C170.7 424 160 413.3 160 400C160 386.7 170.7 376 184 376L368 376L368 344C368 330.7 378.7 320 392 320C405.3 320 416 330.7 416 344L416 376L456 376C469.3 376 480 386.7 480 400zM456 216C469.3 216 480 226.7 480 240C480 253.3 469.3 264 456 264L272 264L272 296C272 309.3 261.3 320 248 320C234.7 320 224 309.3 224 296L224 264L184 264C170.7 264 160 253.3 160 240C160 226.7 170.7 216 184 216L224 216L224 184C224 170.7 234.7 160 248 160C261.3 160 272 170.7 272 184L272 216L456 216z"/></svg>
						<div>
							<h3><?php esc_html_e( 'Settings', 'ajax-load-more-filters' ); ?></h3>
							<p><?php esc_html_e( 'Configure this filter by entering a unique filter ID and selecting an interaction style.', 'ajax-load-more-filters' ); ?></p>
						</div>
					</div>
				</header>

				<section class="alm-filter--options">

					<!-- Filter ID -->
					<div class="alm-filter--row" id="row-id" v-bind:class="{ done: data[0].id !=='' }">
						<label data-id="id">
							<div class="label">
								<?php esc_attr_e( 'ID', 'ajax-load-more-filters' ); ?>
								<a
									title="<?php esc_attr_e( 'The unique ID for this filter instance. The ID must be lowercase and be without spaces or hyphens.', 'ajax-load-more-filters' ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
							</div>
							<div class="item">
								<input
									type="text"
									id="filter-id"
									class="filter-element"
									:value="data[0].id"
									data-id="id"
									v-on:keyup="optionsChange($event)"
									v-on:keypress="restrictIDChars($event)"
									style="text-transform: lowercase;"
									<?php
									if ( $editing ) {
										echo ' readonly="readonly"'; }
									?>
									>
							</div>
						</label>
					</div>

					<!-- Style -->
					<div class="alm-filter--row" id="row-style" v-bind:class="{ done: data[0].style !=='' }">
						<label data-id="style">
							<div class="label">
								<?php esc_attr_e( 'Style', 'ajax-load-more-filters' ); ?>
								<a title="<?php esc_attr_e( 'Select a user interaction for this filter.', 'ajax-load-more-filters' ); ?>" href="javascript:void(0)" class="fa fa-question-circle tooltip" tabindex="-1"></a>
							</div>
							<div class="item">
								<div class="select-wrapper">
									<select
										id="filter-style"
										class="alm-filter-select"
										data-id="style"
										v-on:change="optionsChange($event)"
									>
										<option value=""<?php echo ! isset( $filter['style'] ) ? esc_attr( $selected ) : ''; ?>>-- <?php esc_attr_e( 'Select Style', 'ajax-load-more-filters' ); ?> --</option>
										<option value="change"<?php echo isset( $filter['style'] ) && $filter['style'] === 'change' ? esc_attr( $selected ) : ''; ?>><?php esc_attr_e( 'Change', 'ajax-load-more-filters' ); ?></option>
										<option value="button"<?php echo isset( $filter['style'] ) && $filter['style'] === 'button' ? esc_attr( $selected ) : ''; ?>><?php esc_attr_e( 'Submit Button', 'ajax-load-more-filters' ); ?></option>
									</select>
								</div>
							</div>
						</label>
					</div>

					<!-- Button Label -->
					<div class="related-filters--wrap" v-if="data[0].style === 'button'">
						<div class="alm-filter--row not-required" id="row-button-text" data-id="button-text">
							<label data-id="button_text">
								<div class="label">
									<?php esc_attr_e( 'Button Label', 'ajax-load-more-filters' ); ?>
									<a title="<?php esc_attr_e( 'Enter a label for the filter submit button.', 'ajax-load-more-filters' ); ?>" href="javascript:void(0)" class="fa fa-question-circle tooltip" tabindex="-1"></a>
								</div>
								<div class="item">
									<input type="text" id="filter-button-text" data-id="button_text" :value="data[0].button_text" v-on:change="optionsChange($event)" placeholder="<?php echo wp_kses_post( apply_filters( 'alm_filters_button_text', esc_attr__( 'Submit', 'ajax-load-more-filters' ) ) ); ?>">
								</div>
							</label>
						</div>
					</div>

					<!-- Facets -->
					<div class="alm-filter--row not-required" id="row-facets">
						<div class="fake-label">
							<div class="label">
								<?php esc_attr_e( 'Facets', 'ajax-load-more-filters' ); ?>
								<a
									title="<?php esc_attr_e( 'Facet filtering refines the available filter options with each user selection.', 'ajax-load-more-filters' ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
							</div>
							<div class="item">
								<div class="checkbox-wrapper">
									<input type="checkbox" id="filter-facets" data-id="facets" value="true" :checked="data[0].facets === true" v-on:change="optionsChange($event)">
									<label class="checkbox" for="filter-facets">
										<span><?php esc_attr_e( 'Enable Facet Filtering', 'ajax-load-more-filters' ); ?></span>
									</label>
								</div>
							</div>
						</div>
					</div>
					<?php
					$pt_args    = [
						'public' => true,
					];
					$post_types = get_post_types( $pt_args );
					if ( $post_types ) {
						?>
					<!-- Facet Post Types -->
					<div class="related-filters--wrap" v-show="data[0].facets === true">
						<div class="alm-filter--row" id="row-facets_post_types" v-bind:class="{ done: data[0].facets_post_types && data[0].facets_post_types.length > 0 }">
							<label data-id="facets_post_types">
								<div class="label align-top">
									<?php esc_attr_e( 'Post Types', 'ajax-load-more-filters' ); ?>
									<a
										title="<?php esc_attr_e( 'The selected Post Types must exactly match the Post Types included in your core Ajax Load More setup.', 'ajax-load-more-filters' ); ?>"
										href="javascript:void(0)"
										class="fa fa-question-circle tooltip" tabindex="-1"
									></a>
								</div>
								<div class="item">
									<div class="select-wrapper">
										<p><?php esc_attr_e( 'Select the Post Types to be include in your facets.', 'ajax-load-more-filters' ); ?></p>
										<select
											id="filter-facets_post_types"
											class="alm-filter-select"
											data-id="facets_post_types"
											v-on:change="optionsChange($event)"
											size="<?php echo esc_attr( count( $post_types ) ); ?>"
											multiple
										>
											<?php
											foreach ( $post_types as $type ) {
												$typeobj = get_post_type_object( $type );
												?>
												<option value="<?php echo esc_attr( $typeobj->name ); ?>" :selected="data[0].facets_post_types && data[0].facets_post_types.indexOf('<?php echo $typeobj->name; ?>') !== -1"><?php echo esc_attr( $typeobj->labels->singular_name ); ?> (<?php echo esc_attr( $typeobj->name ); ?>)</option>
											<?php	} ?>
										</select>
										<p class="meta-instructions"><?php echo wp_kses_post( __( '<strong>Tip</strong>: Hold the CMD (Mac) or CTRL (PC) key to select multiple Post Types.', 'ajax-load-more-filters' ) ); ?></p>
									</div>
								</div>
							</label>
						</div>

						<!-- Hide Facets -->
						<div class="alm-filter--row not-required" id="row-facets_hide_inactive">
							<div class="fake-label">
								<label data-id="facets_hide_inactive">
									<div class="label">
										<?php esc_attr_e( 'Inactive Filters', 'ajax-load-more-filters' ); ?>
										<a
											title="<?php esc_attr_e( 'By default, inactive filters are disabled (not hidden) on the frontend.', 'ajax-load-more-filters' ); ?>"
											href="javascript:void(0)"
											class="fa fa-question-circle tooltip" tabindex="-1"
										></a>
									</div>
									<div class="item">
										<div class="checkbox-wrapper">
											<input type="checkbox" id="filter-facets_hide_inactive" data-id="facets_hide_inactive" value="true" :checked="data[0].facets_hide_inactive === true" v-on:change="optionsChange($event)">
											<label class="checkbox" for="filter-facets_hide_inactive">
												<span><?php esc_attr_e( 'Hide Inactive Filter Options', 'ajax-load-more-filters' ); ?></span>
											</label>
										</div>
									</div>
								</label>
							</div>
						</div>
					</div>
					<?php } ?>

					<!-- Filter Reset -->
					<div class="alm-filter--row not-required" id="row-reset_button">
						<div class="fake-label">
							<div class="label">
								<?php esc_attr_e( 'Reset Filters', 'ajax-load-more-filters' ); ?>
								<a
									title="<?php esc_attr_e( 'Add a `Reset Filters` button that clears all currently active filters.', 'ajax-load-more-filters' ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
							</div>
							<div class="item">
								<div class="checkbox-wrapper">
									<input type="checkbox" id="filter-reset_button" data-id="reset_button" value="true" :checked="data[0].reset_button === true" v-on:change="optionsChange($event)">
									<label class="checkbox" for="filter-reset_button">
										<span><?php echo wp_kses_post( __( 'Display <strong>Reset Filters</strong> Button', 'ajax-load-more-filters' ) ); ?></span>
									</label>
								</div>
							</div>
						</div>
					</div>

					<!-- Filter Reset Text -->
					<div class="related-filters--wrap" v-show="data[0].reset_button === true">
						<div class="alm-filter--row not-required" id="row-reset_button_label">
							<label data-id="reset_button_label">
								<div class="label">
									<?php esc_attr_e( 'Reset Button Label', 'ajax-load-more-filters' ); ?>
									<a
										title="<?php esc_attr_e( 'Enter a label for the `Reset Filters` button.', 'ajax-load-more-filters' ); ?>"
										href="javascript:void(0)"
										class="fa fa-question-circle tooltip" tabindex="-1"
									></a>
								</div>
								<div class="item">
									<input type="text" id="filter-reset_button_label" data-id="reset_button_label" :value="data[0].reset_button_label" v-on:change="optionsChange($event)" placeholder="<?php echo apply_filters( 'alm_filters_reset_button_label', __( 'Reset Filters', 'ajax-load-more-filters' ) ); ?>">
								</div>
							</label>
						</div>
					</div>
				</section>

				<!-- Header -->
				<header class="alm-filter--header">
					<div class="title-area">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M33.8 142.8C37.5 133.8 46.3 128 56 128L392 128C401.7 128 410.5 133.8 414.2 142.8C417.9 151.8 415.9 162.1 409 169L280 298L280 488.1C280 497.8 274.2 506.6 265.2 510.3C256.2 514 245.9 512 239 505.1L175 441.1C170.5 436.6 168 430.5 168 424.1L168 297.9L39 169C32.2 162.1 30.1 151.8 33.8 142.8zM480 160C480 142.3 494.3 128 512 128L544 128C561.7 128 576 142.3 576 160C576 177.7 561.7 192 544 192L512 192C494.3 192 480 177.7 480 160zM352 320C352 302.3 366.3 288 384 288L544 288C561.7 288 576 302.3 576 320C576 337.7 561.7 352 544 352L384 352C366.3 352 352 337.7 352 320zM352 480C352 462.3 366.3 448 384 448L544 448C561.7 448 576 462.3 576 480C576 497.7 561.7 512 544 512L384 512C366.3 512 352 497.7 352 480z"/></svg>
						<div>
							<h3><?php esc_html_e( 'Filters', 'ajax-load-more-filters' ); ?></h3>
							<p><?php esc_html_e( 'Create custom filters by adding, removing, and reordering filter blocks.', 'ajax-load-more-filters' ); ?></p>
						</div>
					</div>
					<div class="toggle-controls">
						<button v-on:click="collapseFilters($event)" title="<?php esc_attr_e( 'Collapse Filters', 'ajax-load-more-filters' ); ?>" aria-label="<?php esc_attr_e( 'Collapse Filters', 'ajax-load-more-filters' ); ?>">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M300.3 440.8C312.9 451 331.4 450.3 343.1 438.6L471.1 310.6C480.3 301.4 483 287.7 478 275.7C473 263.7 461.4 256 448.5 256L192.5 256C179.6 256 167.9 263.8 162.9 275.8C157.9 287.8 160.7 301.5 169.9 310.6L297.9 438.6L300.3 440.8z"/></svg>
						</button>
						<button v-on:click="expandFilters($event)"  title="<?php esc_attr_e( 'Expand Filters', 'ajax-load-more-filters' ); ?>" aria-label="<?php esc_attr_e( 'Expand Filters', 'ajax-load-more-filters' ); ?>">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M300.3 440.8C312.9 451 331.4 450.3 343.1 438.6L471.1 310.6C480.3 301.4 483 287.7 478 275.7C473 263.7 461.4 256 448.5 256L192.5 256C179.6 256 167.9 263.8 162.9 275.8C157.9 287.8 160.7 301.5 169.9 310.6L297.9 438.6L300.3 440.8z"/></svg>
						</button>
					</div>
				</header>

				<!-- Vue Template -->
				<section class="alm-filter--filters">
					<draggable v-model="filters" @end="onEnd" :options="{animation:250, handle:'.alm-drag'}">
						<filter-template v-for="(filter, index) in filters" :key="filter.uniqueid" :data-filter="index" :keys="keys" :field_types="field_types" :taxonomy_operators="taxonomy_operators" :taxonomy_include_children="taxonomy_include_children" :meta_types="meta_types" :meta_operators="meta_operators" :filters="filters" :filter="filter" :index="index"></filter-template>
					</draggable>
				</section>

				<!-- Add Filter -->
				<section class="alm-filter--add">
					<button type="button" v-on:click="addFilter($event)" class="button add-filter">
						<?php esc_html_e( 'Add Filter', 'ajax-load-more-filters' ); ?>
					</button>
				</section>

				<!-- Filter Actions -->
				<section class="alm-filter--actions">
					<button type="button" v-on:click="saveFilter($event)" data-baseurl="<?php echo ALM_FILTERS_BASE_URL; ?>" class="buttom button-large button-primary save-filter" :disabled="isEmpty()">
					<?php
					if ( ! $editing ) {
						?>
						{{ create_btn_text }}
						<?php
					} else {
						?>
						{{ update_btn_text }}<?php } ?>
					</button>
					<div class="saving-filter"></div>
				</section>

			<?php
			if ( isset( $filter['date_created'] ) || isset( $filter['date_created'] ) ) {
				echo '<section class="alm-filter--meta">';
				if ( isset( $filter['date_created'] ) ) {
					echo '<div class="col">';
						echo esc_attr__( 'Published', 'ajax-load-more-filters' ) . ': <span>' . wp_kses_post( gmdate( 'Y/m/d h:i:s a', $filter['date_created'] ) ) . '</span>';
					echo '</div>';
				}
				if ( isset( $filter['date_created'] ) ) {
					echo '<div class="col">';
						echo esc_attr__( 'Modified', 'ajax-load-more-filters' ) . ': <span>' . wp_kses_post( gmdate( 'Y/m/d h:i:s a', $filter['date_modified'] ) ) . '</span>';
					echo '</div>';
				}
				echo '</section>';
			}
			?>
			</div>

		</div>

	</div>
	<!-- END MAIN COLUMN -->
	<?php require ALM_FILTERS_PATH . 'admin/views/includes/sidebar.php'; ?>
	<?php
	if ( $editing ) {
		include ALM_FILTERS_PATH . 'admin/views/includes/output-php.php';
	}
	?>
</div>
<!-- End #app -->

<script type="text/x-template" id="filterTemplate">
	<div class="alm-filter--wrapper" tabindex="-1">
		<div>
			<div class="alm-counter alm-drag" :title="'<?php esc_attr_e( 'Filter Block', 'ajax-load-more-filters' ); ?> #' + (index+1)" v-on:dblclick="toggleFilterGroup($event)">
				<div class="drag"><span></span><span></span><span></span></div>
				<input type="hidden"
					class="filter-order"
					v-bind:value="index + 1"
					:data-index="index"
					:data-oldindex="filter.order - 1"
					data-id="order"
					readonly="readonly"
				>
			</div>

			<!-- Title Bar -->
			<div class="alm-filter--title">
				<div role="button" tabindex="0" v-on:click="toggleFilterGroup($event)" v-on:keyup.enter="toggleFilterGroup($event)">
					<div class="alm-filter--title-selected">
						<span><?php esc_html_e( 'Filter:', 'ajax-load-more-filters' ); ?></span>
						<span class="alm-filter-type">{{ getKeyLabel(filter) }}</span>
					</div>
					<div class="alm-filter--title-icon" :title="getFieldTypeLabel(filter.field_type)" v-html="getIcon(filter.field_type)">
					</div>
				</div>
			</div>
			<!-- End Title Bar -->

			<div class="collapsible">
				<!-- Filter Options -->
				<h4 class="alm-filter--section-toggle" role="button" tabindex="0" v-on:click="toggleSection($event)" v-on:keyup.enter="toggleSection($event)" aria-expanded="true">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M544 269.8C529.2 279.6 512.2 287.5 494.5 293.8C447.5 310.6 385.8 320 320 320C254.2 320 192.4 310.5 145.5 293.8C127.9 287.5 110.8 279.6 96 269.8L96 352C96 396.2 196.3 432 320 432C443.7 432 544 396.2 544 352L544 269.8zM544 192L544 144C544 99.8 443.7 64 320 64C196.3 64 96 99.8 96 144L96 192C96 236.2 196.3 272 320 272C443.7 272 544 236.2 544 192zM494.5 453.8C447.6 470.5 385.9 480 320 480C254.1 480 192.4 470.5 145.5 453.8C127.9 447.5 110.8 439.6 96 429.8L96 496C96 540.2 196.3 576 320 576C443.7 576 544 540.2 544 496L544 429.8C529.2 439.6 512.2 447.5 494.5 453.8z"/></svg>
					<?php esc_attr_e( 'Query Options', 'ajax-load-more-filters' ); ?>
					<span>
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M300.3 440.8C312.9 451 331.4 450.3 343.1 438.6L471.1 310.6C480.3 301.4 483 287.7 478 275.7C473 263.7 461.4 256 448.5 256L192.5 256C179.6 256 167.9 263.8 162.9 275.8C157.9 287.8 160.7 301.5 169.9 310.6L297.9 438.6L300.3 440.8z"/></svg>
					</span>
				</h4>

				<section class="alm-filter--section" aria-hidden="false">
					<!-- Key / Query Param -->
					<div class="alm-filter--row" id="row-key" :class="{ done: filter.key !== '' }">
						<label>
							<div class="label">
								<?php esc_attr_e( 'Key', 'ajax-load-more-filters' ); ?>
								<a
									title="<?php esc_attr_e( 'Select a query parameter (required).', 'ajax-load-more-filters' ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
							</div>
							<div class="item">
								<div class="select-wrapper">
									<select id="filter-key"
										class="alm-filter-select"
										:data-index="index"
										data-id="key"
										v-on:change="filterChange($event)"
									>
										<option value="">-- <?php esc_attr_e( 'Select Query Parameter', 'ajax-load-more-filters' ); ?> --</option>
										<option v-for="key in keys" :value="key.value" :selected="key.value === filter.key ? 'selected' : ''" :disabled="key.value === '#'">{{ key.text }}</option>
									</select>
								</div>
							</div>
						</label>
					</div>
					<?php require ALM_FILTERS_PATH . 'admin/views/includes/instructions.php'; ?>

					<!-- Dynamic Query Options -->
					<div v-show="checkQueryOptions(filter.key)" v-bind:class="filter.key">
						<!-- Taxonomy -->
						<?php
						$public_tax = apply_filters( 'alm_filters_public_taxonomies', true );
						$tax_args   = [
							'_builtin' => apply_filters( 'alm_filters_builtin_taxonomies', false ),
						];

						// Add `public` argument if not sepecifically set to false.
						if ( $public_tax ) {
							$tax_args['public'] = true;
						}
						$tax_output = 'objects';
						$taxonomies = get_taxonomies( $tax_args, $tax_output );

						if ( $taxonomies ) {
							?>
						<div v-show="filter.key === 'taxonomy'" id="taxonomies">
							<div class="alm-filter--row" id="row-taxonomy" v-bind:class="{ done: filter.taxonomy !== '' }">
								<label>
									<div class="label">
										<?php esc_attr_e( 'Taxonomy', 'ajax-load-more-filters' ); ?>
										<a
											title="<?php esc_attr_e( 'Select a taxonomy.', 'ajax-load-more-filters' ); ?>"
											href="javascript:void(0)"
											class="fa fa-question-circle tooltip" tabindex="-1"
										></a>
									</div>
									<div class="item">
										<div class="select-wrapper">
											<select
													id="filter-taxonomy"
													class="alm-filter-select"
													:data-index="index"
													data-id="taxonomy"
													v-on:change="filterChange($event)"
												>
												<option value="" :selected="filter.taxonomy === '' ? 'selected' : ''">-- <?php esc_attr_e( 'Select Taxonomy', 'ajax-load-more-filters' ); ?>--</option>
												<?php
												$cats = get_categories();
												if ( $cats ) {
													?>
												<option value="category" :selected="filter.taxonomy === 'category' ? 'selected' : ''"><?php esc_attr_e( 'Categories', 'ajax-load-more-filters' ); ?></option>
													<?php
												}

												$tags = get_tags();
												if ( $tags ) {
													?>
												<option value="post_tag" :selected="filter.taxonomy === 'post_tag' ? 'selected' : ''"><?php esc_attr_e( 'Tags', 'ajax-load-more-filters' ); ?></option>
												<?php } ?>

												<?php foreach ( $taxonomies as $taxonomy ) { ?>
													<option value="<?php echo $taxonomy->query_var; ?>" :selected="filter.taxonomy === '<?php echo $taxonomy->query_var; ?>' ? 'selected' : ''"><?php echo $taxonomy->label; ?></option>
												<?php } ?>

											</select>
										</div>
									</div>
								</label>
							</div>
							<div class="alm-filter--row done" id="row-taxonomy-operator">
								<label>
									<div class="label">
										<?php esc_attr_e( 'Operator', 'ajax-load-more-filters' ); ?>
										<a
											title="<?php esc_attr_e( 'Operator to test.', 'ajax-load-more-filters' ); ?>"
											href="javascript:void(0)"
											class="fa fa-question-circle tooltip" tabindex="-1"
										></a>
									</div>
									<div class="item">
										<div class="select-wrapper">
											<select
													id="filter-taxonomy-operator"
													class="alm-filter-select"
													:data-index="index"
													data-id="taxonomy_operator"
													v-on:change="filterChange($event)"
												>
													<option
													v-for="tax_operator in taxonomy_operators"
													:disabled="tax_operator.disabled === 'facets' && facetsActive() === true"
													:value="tax_operator.value"
													:selected="isActive(tax_operator.value, filter.taxonomy_operator, 'IN' )">{{ tax_operator.text }}</option>
											</select>
										</div>
									</div>
								</label>
							</div>

							<div class="alm-filter--row not-required" id="row-taxonomy-include-children">
								<label>
									<div class="label">
										<?php esc_attr_e( 'Include Children', 'ajax-load-more-filters' ); ?>
										<a
											title="<?php esc_attr_e( 'Whether or not to include children in the query results for hierarchical taxonomies only', 'ajax-load-more-filters' ); ?>"
											href="javascript:void(0)"
											class="fa fa-question-circle tooltip" tabindex="-1"
										></a>
									</div>
									<div class="item">
										<div class="select-wrapper">
											<select
													id="filter-taxonomy-include-children"
													class="alm-filter-select"
													:data-index="index"
													data-id="taxonomy_include_children"
													v-on:change="filterChange($event)"
												>
													<option
													v-for="tax_include_children in taxonomy_include_children"
													:value="tax_include_children.value"
													:selected="isActive(tax_include_children.value, filter.taxonomy_include_children, 'true' )">{{ tax_include_children.text }}</option>
											</select>
										</div>
									</div>
								</label>
							</div>

						</div>
						<?php } ?>
						<!-- End Taxonomy -->

						<!-- Meta Query -->
						<div v-show="filter.key === 'meta'" id="meta">

							<div class="alm-filter--row" id="row-meta-key" v-bind:class="{ done: filter.meta_key !== '' }">
								<label>
									<div class="label"><?php esc_attr_e( 'Meta Key', 'ajax-load-more-filters' ); ?></div>
									<div class="item">
										<input type="text" id="filter-meta-key" placeholder="<?php esc_attr_e( 'Enter custom field name', 'ajax-load-more-filters' ); ?>" data-id="meta_key" :data-index="index" :value="filter.meta_key" v-on:change="filterChange($event)">
									</div>
								</label>
							</div>

							<div class="alm-filter--row" id="row-meta-operator" v-bind:class="{ done: filter.meta_operator !== '' }">
								<label>
									<div class="label"><?php esc_attr_e( 'Compare', 'ajax-load-more-filters' ); ?></div>
									<div class="item">
										<div class="select-wrapper">
											<select
													id="filter-meta-operator"
													class="alm-filter-select"
													:data-index="index"
													data-id="meta_operator"
													v-on:change="filterChange($event)"
												>
												<option value="">-- <?php esc_attr_e( 'Select Meta Operator', 'ajax-load-more-filters' ); ?> --</option>
												<option
												:disabled="meta_operator.disabled === 'facets' && facetsActive() === true"
												v-for="meta_operator in meta_operators"
												:value="meta_operator.value"
												:selected="isActive(meta_operator.value, filter.meta_operator, 'IN' )">{{ meta_operator.text }}</option>
											</select>
										</div>
									</div>
								</label>
							</div>

							<div class="alm-filter--row" id="row-meta-type" v-bind:class="{ done: filter.meta_type !== '' }">
								<label>
									<div class="label"><?php esc_attr_e( 'Type', 'ajax-load-more-filters' ); ?></div>
									<div class="item">
										<div class="select-wrapper">
											<select
													id="filter-meta-type"
													class="alm-filter-select"
													:data-index="index"
													data-id="meta_type"
													v-on:change="filterChange($event)"
												>
												<option value="">-- <?php esc_attr_e( 'Select Meta Type', 'ajax-load-more-filters' ); ?> --</option>
												<option
												v-for="meta_type in meta_types"
												:value="meta_type.value"
												:selected="isActive(meta_type.value, filter.meta_type, 'CHAR' )">{{ meta_type.text }}</option>
											</select>
										</div>
									</div>
								</label>
							</div>
						</div>
						<!-- End Meta Query -->

						<!-- Author -->
						<div v-show="filter.key === 'author'" id="role">
							<div class="alm-filter--row not-required" id="row-author-role">
								<label>
									<div class="label"><?php esc_attr_e( 'Role', 'ajax-load-more-filters' ); ?></div>
									<div class="item">
										<div class="select-wrapper">
											<?php
											global $wp_roles;
											$author_role = '';
											if ( $wp_roles ) {
												$roles       = $wp_roles->get_names();
												$author_role = '';
												$decode_f    = json_decode( $filter_vue );

												// Get selected author role.
												if ( isset( $decode_f->filters ) && isset( $decode_f->filters ) ) {
													foreach ( $decode_f->filters as $filter ) {
														if ( isset( $filter->author_role ) && $filter->author_role !== '' ) {
															$author_role = $filter->author_role;
															break;
														}
													}
												}
												?>
											<select
												id="filter-author-role"
												class="alm-filter-select"
												:data-index="index"
												data-id="author_role"
												v-on:change="filterChange($event)"
											>
												<option value="">-- <?php esc_attr_e( 'Select an Author Role', 'ajax-load-more-filters' ); ?> --</option>
												<?php
												foreach ( $roles as $role ) {
													?>
												<option value="<?php echo esc_attr( $role ); ?>"
													<?php
													if ( $author_role === $role ) {
														echo ' selected="selected"';
													}
													?>
												><?php echo esc_attr( $role ); ?></option>
											<?php } ?>
											</select>
											<?php } ?>
										</div>
									</div>
								</label>
							</div>
						</div>
						<!-- End Author -->

						<!-- Exclude (Category, Tag, Taxonomy) -->
						<div v-show="checkExclude(filter.key, index)">
							<div class="alm-filter--row not-required" id="row-exclude">
								<label>
									<div class="label">
										<?php esc_attr_e( 'Exclude', 'ajax-load-more-filters' ); ?>
										<a
											title="<?php esc_attr_e( 'Comma separated list of IDs to exclude from being listed in this filter.', 'ajax-load-more-filters' ); ?>"
											href="javascript:void(0)"
											class="fa fa-question-circle tooltip" tabindex="-1"
										></a>
									</div>
									<div class="item">
										<input type="text" placeholder="55, 85, 115" id="filter-exclude" data-id="exclude" :data-index="index" :value="filter.exclude" v-on:keyup="filterChange($event)">
									</div>
								</label>
							</div>
						</div>
						<!-- End Exclude (Category, Tag, taxonomy) -->

					</div>
				</section>
				<!-- End Filters Query Config -->

				<!-- Field Type -->
				<h4 class="alm-filter--section-toggle" role="button" tabindex="0" v-on:click="toggleSection($event)" v-on:keyup.enter="toggleSection($event)" aria-expanded="true">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M259.1 73.5C262.1 58.7 275.2 48 290.4 48L350.2 48C365.4 48 378.5 58.7 381.5 73.5L396 143.5C410.1 149.5 423.3 157.2 435.3 166.3L503.1 143.8C517.5 139 533.3 145 540.9 158.2L570.8 210C578.4 223.2 575.7 239.8 564.3 249.9L511 297.3C511.9 304.7 512.3 312.3 512.3 320C512.3 327.7 511.8 335.3 511 342.7L564.4 390.2C575.8 400.3 578.4 417 570.9 430.1L541 481.9C533.4 495 517.6 501.1 503.2 496.3L435.4 473.8C423.3 482.9 410.1 490.5 396.1 496.6L381.7 566.5C378.6 581.4 365.5 592 350.4 592L290.6 592C275.4 592 262.3 581.3 259.3 566.5L244.9 496.6C230.8 490.6 217.7 482.9 205.6 473.8L137.5 496.3C123.1 501.1 107.3 495.1 99.7 481.9L69.8 430.1C62.2 416.9 64.9 400.3 76.3 390.2L129.7 342.7C128.8 335.3 128.4 327.7 128.4 320C128.4 312.3 128.9 304.7 129.7 297.3L76.3 249.8C64.9 239.7 62.3 223 69.8 209.9L99.7 158.1C107.3 144.9 123.1 138.9 137.5 143.7L205.3 166.2C217.4 157.1 230.6 149.5 244.6 143.4L259.1 73.5zM320.3 400C364.5 399.8 400.2 363.9 400 319.7C399.8 275.5 363.9 239.8 319.7 240C275.5 240.2 239.8 276.1 240 320.3C240.2 364.5 276.1 400.2 320.3 400z"/></svg>
					<?php esc_html_e( 'Form Field Options', 'ajax-load-more-filters' ); ?>
					<span>
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M300.3 440.8C312.9 451 331.4 450.3 343.1 438.6L471.1 310.6C480.3 301.4 483 287.7 478 275.7C473 263.7 461.4 256 448.5 256L192.5 256C179.6 256 167.9 263.8 162.9 275.8C157.9 287.8 160.7 301.5 169.9 310.6L297.9 438.6L300.3 440.8z"/></svg>
					</span>
				</h4>

				<section class="alm-filter--section" aria-hidden="false">
					<div class="alm-filter--row" id="row-field-type" v-bind:class="{ done: filter.field_type !== '' }">
						<label>
							<div class="label">
								<?php esc_html_e( 'Field Type', 'ajax-load-more-filters' ); ?>
								<a
									title="<?php esc_attr_e( 'Select a form element for this filter (required).', 'ajax-load-more-filters' ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
							</div>
							<div class="item">
								<div class="select-wrapper">
									<select
										id="filter-field-type"
										class="alm-filter-select"
										:data-index="index"
										data-id="field_type"
										v-on:change="filterChange($event)"
									>
										<option value="">-- <?php esc_attr_e( 'Select Field Type', 'ajax-load-more-filters' ); ?> --</option>
										<option v-for="field_type in field_types" :value="field_type.value" :selected="field_type.value === filter.field_type ? 'selected' : ''" :disabled="field_type.value === '#' || field_type.disabled === true">{{ field_type.text }}</option>
									</select>
								</div>
							</div>
						</label>
					</div>

					<!-- Checkbox: Limit and Toggle All Settings -->
					<div class="related-filters--wrap" v-show="filter.field_type === 'checkbox'">

						<!-- Limit Checkbox Header -->
						<div class="alm-filters-inline-desc no-margin">
							<h3><?php esc_attr_e( 'Checkbox Display Limit', 'ajax-load-more-filters' ); ?> <a
									title="<?php echo wp_kses_post( __( 'Limit the amount of checkboxes that display on page load. Remaining checkboxes will be loaded with a `Show More` button.', 'ajax-load-more-filters' ) ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a></h3>
						</div>

						<!-- Limit Checkbox Count -->
						<div class="alm-filter--row not-required">
							<label>
								<div class="label">
									<?php esc_attr_e( 'Limit', 'ajax-load-more-filters' ); ?>
									<a
										title="<?php esc_attr_e( 'Set the amount of checkboxes to display on initial page load. Leave empty to display all terms.', 'ajax-load-more-filters' ); ?>"
										href="javascript:void(0)"
										class="fa fa-question-circle tooltip" tabindex="-1"
									></a>
								</div>
								<div class="item">
									<input type="number" min="1" steps="1" id="filter-checkbox_limit" placeholder="-1" data-id="checkbox_limit" :data-index="index" :value="filter.checkbox_limit" v-on:keyup="filterChange($event)">
								</div>
							</label>
						</div>

						<!-- Limit Open Checkbox Label -->
						<div class="alm-filter--row not-required" id="row-checkbox_limit_label_open" v-show="filter.checkbox_limit !== ''">
							<label>
								<div class="label">
									<?php esc_attr_e( 'Button Label (Open)', 'ajax-load-more-filters' ); ?>
									<a
									title="<?php esc_attr_e( 'The text to be rendered on the `Show More` button. Use the %total% placeholder to display the total items remaining to be shown.', 'ajax-load-more-filters' ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
								</div>
								<div class="item">
									<input type="text" id="filter-checkbox_limit_label_open" data-id="checkbox_limit_label_open" :data-index="index" :value="filter.checkbox_limit_label_open" v-on:keyup="filterChange($event)" placeholder="<?php echo wp_kses_post( apply_filters( 'alm_filters_checkbox_limit_label_open', __( 'Show More', 'ajax-load-more-filters' ) ) ); ?>">
								</div>
							</label>
						</div>

						<!-- Limit Close Checkbox Label -->
						<div class="alm-filter--row not-required" id="row-checkbox_limit_label_close" v-show="filter.checkbox_limit !== ''">
							<label>
								<div class="label">
									<?php esc_attr_e( 'Button Label (Close)', 'ajax-load-more-filters' ); ?>
									<a
									title="<?php esc_attr_e( 'The text to be rendered on the `Show Less` button.', 'ajax-load-more-filters' ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
								</div>
								<div class="item">
									<input type="text" id="filter-checkbox_limit_label_close" data-id="checkbox_limit_label_close" :data-index="index" :value="filter.checkbox_limit_label_close" v-on:keyup="filterChange($event)" placeholder="<?php echo wp_kses_post( apply_filters( 'alm_filters_checkbox_limit_label_close', __( 'Show Less', 'ajax-load-more-filters' ) ) ); ?>">
								</div>
							</label>
						</div>

						<hr v-show="!facetsActive()"/>

						<!-- Toggle Checkbox Header -->
						<div v-show="!facetsActive()">
							<div class="alm-filters-inline-desc no-margin">
								<h3>
									<?php esc_attr_e( 'Checkbox Select All Toggle', 'ajax-load-more-filters' ); ?>
									<a
									title="<?php echo wp_kses_post( __( 'Display a `Select All` checkbox that allows users to select/unselect all filters with a single click. This feature is not available when using Facets.', 'ajax-load-more-filters' ) ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
								</h3>
							</div>

							<!-- Toggle Checkbox Location -->
							<div class="alm-filter--row not-required" id="row-checkbox_toggle">
								<label>
									<div class="label">
										<?php esc_attr_e( 'Location', 'ajax-load-more-filters' ); ?>
										<a
										title="<?php esc_attr_e( 'Select the placement of the toggle checkbox.', 'ajax-load-more-filters' ); ?>"
										href="javascript:void(0)"
										class="fa fa-question-circle tooltip" tabindex="-1"
									></a>
									</div>
									<div class="item">
										<div class="select-wrapper">
											<select
												id="filter-checkbox_toggle"
												class="alm-filter-select"
												data-id="checkbox_toggle"
												:data-index="index"
												v-on:change="filterChange($event)"
											>
												<option value="" :selected="filter.checkbox_toggle === '' ? 'selected' : ''"><?php esc_attr_e( 'None (Do not display)', 'ajax-load-more-filters' ); ?></option>
												<option value="before" :selected="filter.checkbox_toggle === 'before' ? 'selected' : ''"><?php esc_attr_e( 'Before (Displayed before filters)', 'ajax-load-more-filters' ); ?></option>
												<option value="after" :selected="filter.checkbox_toggle === 'after' ? 'selected' : ''"><?php esc_attr_e( 'After (Displayed after filters)', 'ajax-load-more-filters' ); ?></option>
											</select>
										</div>
									</div>
								</label>
							</div>

							<!-- Toggle Checkbox Label -->
							<div class="alm-filter--row not-required" id="row-checkbox_toggle_label" v-show="filter.checkbox_toggle !== ''">
								<label>
									<div class="label">
										<?php esc_attr_e( 'Checkbox Label', 'ajax-load-more-filters' ); ?>
										<a
										title="<?php esc_attr_e( 'The text to be rendered as the toggle checkbox label.', 'ajax-load-more-filters' ); ?>"
										href="javascript:void(0)"
										class="fa fa-question-circle tooltip" tabindex="-1"
									></a>
									</div>
									<div class="item">
										<input type="text" id="filter-checkbox_toggle_label" data-id="checkbox_toggle_label" :data-index="index" :value="filter.checkbox_toggle_label" v-on:keyup="filterChange($event)" placeholder="<?php echo apply_filters( 'alm_filters_toggle_label', __( 'Select All', 'ajax-load-more-filters' ) ); ?>">
									</div>
								</label>
							</div>
						</div>

					</div>
					<!-- End Toggle All -->

					<!-- Datepicker -->
					<div class="related-filters--wrap" v-show="filter.field_type === 'date_picker'" id="datepicker">
						<div class="alm-filter--row_instructions">
							<div class="alm-instructions alm-instructions--intro">
								<p><?php _e( 'The <strong>Date Picker</strong> field type uses the <a href="https://flatpickr.js.org/" target="_blank">Flatpickr JS</a> library to display a calendar select input field.', 'ajax-load-more-filters' ); ?></p>
							</div>
						</div>

						<div class="alm-filters-inline-desc">
							<h3><?php esc_attr_e( 'Date Picker Options', 'ajax-load-more-filters' ); ?></h3>
							<p><a href="https://flatpickr.js.org/formatting/" target="_blank"><?php esc_attr_e( 'Date Formatting Options', 'ajax-load-more-filters' ); ?></a> | <a href="https://github.com/flatpickr/flatpickr/tree/master/src/l10n" target="_blank"><?php esc_attr_e( 'Available Locales', 'ajax-load-more-filters' ); ?></a><a href="javascript:void(0)" class="fa fa-question-circle tooltip" title="<?php esc_attr_e( 'Select a 2 digit locale from the list', 'ajax-load-more-filters' ); ?>"></a></p>
						</div>

						<!-- Mode -->
						<div class="alm-filter--row not-required" id="row-datemode">
							<label>
								<div class="label">
									<?php esc_attr_e( 'Mode', 'ajax-load-more-filters' ); ?>
									<a
									title="<?php esc_attr_e( 'Select the type of date picker. Choose between single, multiple or a date range.', 'ajax-load-more-filters' ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
								</div>
								<div class="item">
									<div class="select-wrapper">
										<select
										id="filter-datepicker-mode"
										class="alm-filter-select"
										data-id="datepicker_mode"
										:data-index="index"
										v-on:change="filterChange($event)"
									>
										<option value="">-- <?php esc_attr_e( 'Select Mode', 'ajax-load-more-filters' ); ?> --</option>
										<option value="single" :selected="filter.datepicker_mode === 'single' ? 'selected' : ''"><?php esc_attr_e( 'Single Date', 'ajax-load-more-filters' ); ?></option>
										<option value="multiple" :selected="filter.datepicker_mode === 'multiple' ? 'selected' : ''"><?php esc_attr_e( 'Multiple Dates', 'ajax-load-more-filters' ); ?></option>
										<option value="range" :selected="filter.datepicker_mode === 'range' ? 'selected' : ''"><?php esc_attr_e( 'Range', 'ajax-load-more-filters' ); ?></option>
									</select>
									</div>
								</div>
							</label>
						</div>
						<!-- Date Display Format -->
						<div class="alm-filter--row not-required" id="row-dateformat">
							<label>
								<div class="label">
									<?php esc_attr_e( 'Date Format', 'ajax-load-more-filters' ); ?>
									<a
									title="<?php esc_attr_e( 'The date format displayed to the user when a date is selected. Default = Y-m-d.', 'ajax-load-more-filters' ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
								</div>
								<div class="item">
									<input type="text" placeholder="Y-m-d" id="filter-datepicker-format" data-id="datepicker_format" :data-index="index" :value="filter.datepicker_format" v-on:keyup="filterChange($event)">
								</div>
							</label>
						</div>
						<!-- End Date Display Format -->

						<!-- Locale -->
						<div class="alm-filter--row not-required" id="row-locale">
							<label>
								<div class="label">
									<?php esc_attr_e( 'Localization', 'ajax-load-more-filters' ); ?>
									<a
									title="<?php esc_attr_e( 'Choose a locale (language) for the date picker. Default = en', 'ajax-load-more-filters' ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
								</div>
								<div class="item">
									<input type="text" placeholder="en" id="filter-datepicker-locale" data-id="datepicker_locale" :data-index="index" :value="filter.datepicker_locale" v-on:keyup="filterChange($event)">
								</div>
							</label>
						</div>
						<!-- End Locale -->
					</div>
					<!-- End Datepicker -->

					<!-- Range Slider -->
					<div class="related-filters--wrap" v-show="filter.field_type === 'range_slider'" id="rangeslider">
						<div class="alm-filter--row_instructions">
							<div class="alm-instructions alm-instructions--intro">
								<p><?php echo wp_kses_post( __( 'The <strong>Range Slider</strong> field type uses the <a href="https://refreshless.com/nouislider/" target="_blank">noUiSlider</a> library to display a draggable range selector.', 'ajax-load-more-filters' ) ); ?></p>
								<p><?php esc_attr_e( 'Range Sliders are most commonly used when querying for custom field values such as product price (WooCommerce) or width/height measurements.', 'ajax-load-more-filters' ); ?></p>
							</div>
						</div>

						<div class="alm-filters-inline-desc">
							<h3><?php esc_attr_e( 'Range Slider Options', 'ajax-load-more-filters' ); ?></h3>
							<p><?php esc_attr_e( 'Set the min/max value and other noUiSlider configuration options.', 'ajax-load-more-filters' ); ?></p>
						</div>

						<!-- Range Min -->
						<div class="alm-filter--row not-required" id="row-rangeslider-start">
							<label>
								<div class="label">
									<?php esc_attr_e( 'Min Value', 'ajax-load-more-filters' ); ?>
									<a
									title="<?php esc_attr_e( 'Set the minimum value of the slider.', 'ajax-load-more-filters' ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
								</div>
								<div class="item">
									<input type="number" placeholder="0" id="filter-rangeslider-min" data-id="rangeslider_min" :data-index="index" :value="filter.rangeslider_min" v-on:keyup="filterChange($event)">
								</div>
							</label>
						</div>
						<!-- Range Max -->
						<div class="alm-filter--row not-required" id="row-rangeslider-end">
							<label>
								<div class="label">
									<?php esc_attr_e( 'Max Value', 'ajax-load-more-filters' ); ?>
									<a
									title="<?php esc_attr_e( 'Set the maximum value of the slider.', 'ajax-load-more-filters' ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
								</div>
								<div class="item">
									<input type="number" placeholder="100" id="filter-rangeslider-max" data-id="rangeslider_max" :data-index="index" :value="filter.rangeslider_max" v-on:keyup="filterChange($event)">
								</div>
							</label>
						</div>
						<!-- Range Steps -->
						<div class="alm-filter--row not-required" id="row-rangeslider-end">
							<label>
								<div class="label">
									<?php esc_attr_e( 'Steps', 'ajax-load-more-filters' ); ?>
									<a
									title="<?php esc_attr_e( 'Make the slide handles jump between intervals on drag - default is 1.', 'ajax-load-more-filters' ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
								</div>
								<div class="item">
									<input type="number" min="0" step="<?php echo esc_attr( apply_filters( 'alm_filters_range_slider_steps', 1 ) ); ?>" placeholder="<?php echo esc_attr( apply_filters( 'alm_filters_range_slider_steps', 1 ) ); ?>" id="filter-rangeslider-steps" data-id="rangeslider_steps" :data-index="index" :value="filter.rangeslider_steps" v-on:keyup="filterChange($event)">
								</div>
							</label>
						</div>
						<!-- Range Label -->
						<div class="alm-filter--row not-required" id="row-rangeslider-label">
							<label>
								<div class="label">
									<?php esc_attr_e( 'Display Label', 'ajax-load-more-filters' ); ?>
									<a
									title="<?php esc_attr_e( 'The label renders the current start & end values of the slider to the user. The {start} parameter displays the low value, while the {end} parameter displays the high value.', 'ajax-load-more-filters' ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
								</div>
								<div class="item">
									<input type="text" placeholder="${start} - ${end}" id="filter-rangeslider-label" data-id="rangeslider_label" :data-index="index" :value="filter.rangeslider_label" v-on:keyup="filterChange($event)">
								</div>
							</label>
						</div>
						<!-- Range Orientation -->
						<div class="alm-filter--row not-required" id="row-rangeslider-orientation">
							<label>
								<div class="label">
									<?php esc_attr_e( 'Orientation', 'ajax-load-more-filters' ); ?>
									<a
									title="<?php esc_attr_e( 'Vertical sliders default to 200px in height. You can adjust this using custom CSS.', 'ajax-load-more-filters' ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
								</div>
								<div class="item">
									<div class="select-wrapper">
										<select
											id="filter-rangeslider-orientation"
											class="alm-filter-select"
											data-id="rangeslider_orientation"
											:data-index="index"
											v-on:change="filterChange($event)"
											>
											<option value="horizontal" :selected="isActive( 'horizontal', filter.rangeslider_orientation, 'horizontal' )"><?php esc_attr_e( 'Horizontal', 'ajax-load-more-filters' ); ?> &nbsp; ↔</option>
											<option value="vertical" :selected="isActive( 'vertical', filter.rangeslider_orientation, 'horizontal' )"><?php esc_attr_e( 'Vertical', 'ajax-load-more-filters' ); ?> &nbsp; ↕</option>
										</select>
									</div>
								</div>
							</label>
						</div>
						<!-- Range Decimal -->
						<div class="alm-filter--row not-required" id="row-rangeslider-decimal">
							<label>
								<div class="label">
									<?php esc_attr_e( 'Show Decimals', 'ajax-load-more-filters' ); ?>
									<a
									title="<?php esc_attr_e( 'Render decimals in the range slider display.', 'ajax-load-more-filters' ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
								</div>
								<div class="item">
									<div class="select-wrapper">
										<select
											id="filter-rangeslider-decimals"
											class="alm-filter-select"
											data-id="rangeslider_decimals"
											:data-index="index"
											v-on:change="filterChange($event)"
											>
											<option value="true" :selected="isActive( 'true', filter.rangeslider_decimals, 'true' )"><?php esc_attr_e( 'True', 'ajax-load-more-filters' ); ?></option>
											<option value="false" :selected="isActive( 'false', filter.rangeslider_decimals, 'false' )"><?php esc_attr_e( 'False', 'ajax-load-more-filters' ); ?></option>
										</select>
									</div>
								</div>
							</label>
						</div>
						<!-- Range Reset -->
						<div class="alm-filter--row not-required" id="row-rangeslider-reset">
							<label>
								<div class="label">
									<?php esc_attr_e( 'Reset Button', 'ajax-load-more-filters' ); ?>
									<a
									title="<?php esc_attr_e( 'Display a reset button to clear range slider values.', 'ajax-load-more-filters' ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
								</div>
								<div class="item">
									<div class="select-wrapper">
										<select
											id="filter-rangeslider-reset"
											class="alm-filter-select"
											data-id="rangeslider_reset"
											:data-index="index"
											v-on:change="filterChange($event)"
											>
											<option value="true" :selected="isActive( 'true', filter.rangeslider_reset, 'true' )"><?php esc_attr_e( 'True', 'ajax-load-more-filters' ); ?></option>
											<option value="false" :selected="isActive( 'false', filter.rangeslider_reset, 'false' )"><?php esc_attr_e( 'False', 'ajax-load-more-filters' ); ?></option>
										</select>
									</div>
								</div>
							</label>
							<div class="alm-filter--row_info">
								<?php
									$format = __( 'Use the %s hook to adjust the `Reset` button label.', 'ajax-load-more-filters' );
									printf( $format, '<a href="https://connekthq.com/plugins/ajax-load-more/docs/add-ons/filters/hooks/#alm_filters_range_slider_reset_label" target="_blank">alm_filters_range_slider_reset_label</a>' );
								?>
							</div>
						</div>

						<hr/>

						<div class="alm-filters-inline-desc">
							<h3><?php esc_attr_e( 'Range Slider Defaults', 'ajax-load-more-filters' ); ?></h3>
							<p><?php esc_attr_e( 'Set the default start and end position of the drag handles.', 'ajax-load-more-filters' ); ?></p>
						</div>

						<!-- Range Start -->
						<div class="alm-filter--row not-required" id="row-rangeslider-start">
							<label>
								<div class="label">
									<?php esc_attr_e( 'Start Value', 'ajax-load-more-filters' ); ?>
									<a
									title="<?php esc_attr_e( 'If no value is set, handle will start at the Min value.', 'ajax-load-more-filters' ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
								</div>
								<div class="item">
									<input type="number" placeholder="10" id="filter-rangeslider-start" data-id="rangeslider_start" :data-index="index" :value="filter.rangeslider_start" v-on:keyup="filterChange($event)">
								</div>
							</label>
						</div>
						<!-- Range End -->
						<div class="alm-filter--row not-required" id="row-rangeslider-end">
							<label>
								<div class="label">
									<?php esc_attr_e( 'End Value', 'ajax-load-more-filters' ); ?>
									<a
									title="<?php esc_attr_e( 'If no value is set, handle will start at the Max value', 'ajax-load-more-filters' ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
								</div>
								<div class="item">
									<input type="number" placeholder="175" id="filter-rangeslider-end" data-id="rangeslider_end" :data-index="index" :value="filter.rangeslider_end" v-on:keyup="filterChange($event)">
								</div>
							</label>
						</div>
					</div>
					<!-- End Range Slider -->

					<!-- Star Rating -->
					<div class="related-filters--wrap" v-show="filter.field_type === 'star_rating'" id="star_rating">
						<div class="alm-filter--row_instructions">
							<div class="alm-instructions alm-instructions--intro">
								<p><?php echo wp_kses_post( __( 'The <strong>Star Rating</strong> field type uses a <strong>Custom Field</strong> query for a numeric value.', 'ajax-load-more-filters' ) ); ?></p>
								<p><?php esc_attr_e( 'You must have an existing custom field that stores an average rating value. This rating field must contain a numeric value up to a max of 10.', 'ajax-load-more-filters' ); ?></p>
							</div>
						</div>

						<div class="alm-filters-inline-desc">
							<h3><?php esc_attr_e( 'Star Rating Options', 'ajax-load-more-filters' ); ?></h3>
						</div>

						<!-- Min Value -->
						<div class="alm-filter--row not-required" id="row-star_rating_min">
							<label>
								<div class="label">
									<?php esc_attr_e( 'Min Value', 'ajax-load-more-filters' ); ?>
									<a
									title="<?php esc_attr_e( 'Select the minimum star rating value.', 'ajax-load-more-filters' ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
								</div>
								<div class="item">
									<input type="number" min="1" max="1" placeholder="1" id="filter-star_rating_min" data-id="star_rating_min" :data-index="index" :value="filter.star_rating_min" v-on:keyup="filterChange($event)" v-on:change="filterChange($event)">
								</div>
							</label>
						</div>

						<!-- Max Value -->
						<div class="alm-filter--row not-required" id="row-star_rating_max">
							<label>
								<div class="label">
									<?php esc_attr_e( 'Max Value', 'ajax-load-more-filters' ); ?>
									<a
									title="<?php esc_attr_e( 'Select the maximum star rating value.', 'ajax-load-more-filters' ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
								</div>
								<div class="item">
									<input type="number" min="1" max="10" placeholder="5" id="filter-star_rating_max" data-id="star_rating_max" :data-index="index" :value="filter.star_rating_max" v-on:keyup="filterChange($event)" v-on:change="filterChange($event)">
								</div>
							</label>
						</div>
					</div>
					<!-- End Star Rating -->

					<!-- Default Select Option -->
					<div class="alm-filter--row not-required" id="row-default-select-option" v-show="filter.field_type === 'select'">
						<label>
							<div class="label">
								<?php esc_attr_e( 'Default Select Option', 'ajax-load-more-filters' ); ?>
								<a
									title="<?php esc_attr_e( 'Will be displayed as the first (default) option in the select dropdown list.', 'ajax-load-more-filters' ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
							</div>
							<div class="item">
								<input type="text" id="filter-default-select-option" data-id="default_select_option" :data-index="index" :value="filter.default_select_option" placeholder="<?php esc_attr_e( '-- Make a Selection --', 'ajax-load-more-filters' ); ?>" v-on:change="filterChange($event)">
							</div>
						</label>
					</div>

					<!-- Custom Values -->
					<div class="alm-filter--row not-required" id="row-values" v-show="filter.field_type !== 'star_rating' && filter.key !== 'search' && filter.field_type !== 'text' && filter.field_type !== 'range_slider' && filter.field_type !== 'date_picker'">
						<div class="fake-label">
							<div class="label align-top">
							<?php esc_attr_e( 'Custom Values', 'ajax-load-more-filters' ); ?>
								<a
									title="<?php esc_attr_e( 'Create a list of custom filters by adding an array of label/value pairs.', 'ajax-load-more-filters' ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
							</div>
							<div class="item should-wrap">
								<div class="value-fields-wrap" :data-index="index" data-id="values" :data-key="filter.key">
									<div class="value-fields-wrap--field" v-for="(cv, index) in filter.values" :key="index">
										<label>
											<span><?php esc_attr_e( 'Label', 'ajax-load-more-filters' ); ?></span>
											<input
												type="text"
												class="values-label"
												placeholder="<?php esc_attr_e( 'Label', 'ajax-load-more-filters' ); ?>"
												v-on:change="customValueChange($event)"
												:value="cv.label"
											>
										</label>
										<label>
											<span><?php esc_attr_e( 'Value', 'ajax-load-more-filters' ); ?></span>
											<input
												type="text"
												class="values-value"
												placeholder="<?php esc_attr_e( 'Value', 'ajax-load-more-filters' ); ?>"
												v-on:change="customValueChange($event)"
												:value="cv.value"
												v-show="filter.key !== 'sort'"
											>
											<input
												type="text"
												class="values-sortvalue"
												placeholder="<?php esc_attr_e( 'order:orderby', 'ajax-load-more-filters' ); ?>"
												v-on:change="customValueChange($event)"
												:value="cv.value"
												v-show="filter.key === 'sort'"
											>
										</label>
									<div class="custom-value-controls">
									<button
										:class="{ disabled: index == filter.values.length-1}"
										class="alm-cv-down"
											v-on:click="customValueMove($event)"
											data-direction="down"
											data-id="values"
											:data-index="index"
											title="<?php esc_attr_e( 'Move Down', 'ajax-load-more-filters' ); ?>"
									>
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M169.4 374.6c12.5 12.5 32.8 12.5 45.3 0l160-160c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 306.7 54.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l160 160z"/></svg>
										<span><?php esc_html_e( 'Move Down', 'ajax-load-more-filters' ); ?></span>
									</button>
									<button
										:class="{ disabled: index == 0 }"
										class="alm-cv-up"
											v-on:click="customValueMove($event)"
											data-direction="up"
											data-id="values"
											:data-index="index"
											title="<?php esc_attr_e( 'Move Up', 'ajax-load-more-filters' ); ?>"
									>
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M169.4 137.4c12.5-12.5 32.8-12.5 45.3 0l160 160c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L192 205.3 54.6 342.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l160-160z"/></svg>
										<span><?php esc_html_e( 'Move Up', 'ajax-load-more-filters' ); ?></span>
									</button>

									<button
										v-show="filter.values.length > 0"
										class="alm-remove-btn"
											v-on:click="removeCustomValue($event)"
											data-id="values"
											:data-index="index"
											title="<?php esc_attr_e( 'Remove', 'ajax-load-more-filters' ); ?>"
									>
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M55.1 73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L147.2 256 9.9 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192.5 301.3 329.9 438.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.8 256 375.1 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192.5 210.7 55.1 73.4z"/></svg>
										<span><?php esc_html_e( 'Remove', 'ajax-load-more-filters' ); ?></span></button>
									</div>

									</div>
								</div>
								<div class="value-fields-wrap--controls">
									<button
									class="button add-value"
									v-on:click="addCustomValue($event)"
									data-id="values"
									:data-index="index"
									><?php esc_attr_e( 'Add Value', 'ajax-load-more-filters' ); ?></button>
								</div>
							</div>
						</div>
					</div>

					<!-- Result Count -->
					<div class="alm-filter--row not-required" id="row-show-count" v-show="checkShowCount(filter.key, index) && filter.field_type !== 'text'">
						<div class="fake-label">
							<div class="label">
								<?php esc_attr_e( 'Result Count', 'ajax-load-more-filters' ); ?>
								<a
									title="<?php esc_attr_e( 'Display the total number of results beside each filter item.', 'ajax-load-more-filters' ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
							</div>
							<div class="item">
								<div class="checkbox-wrapper">
									<input type="checkbox" :id="`show_count_${index}`" data-id="show_count" :data-index="index" value="true" :checked="filter.show_count === true" v-on:change="filterChange($event)">
									<label class="checkbox" :for="`show_count_${index}`">
										<span><?php esc_attr_e( 'Yes', 'ajax-load-more-filters' ); ?></span>
									</label>
								</div>
							</div>
						</div>
					</div>

					<!-- Pre-Selected Value -->
					<div class="alm-filter--row not-required" id="row-selected-value" v-show="filter.field_type === 'select' || filter.field_type === 'radio' || filter.field_type === 'checkbox'">
						<label>
							<div class="label">
								<?php esc_attr_e( 'Preselected Value', 'ajax-load-more-filters' ); ?>
								<a
									title="<?php esc_attr_e( 'Set a preselected value (slug) for this filter block. The value set here will be the element selected on initial page load. Separate multiple values with a + symbol. e.g. design+development+app.', 'ajax-load-more-filters' ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
							</div>
							<div class="item">
								<input type="text" id="filter-selected-value" data-id="selected_value" :data-index="index" :value="filter.selected_value" v-on:change="filterChange($event)">
							</div>
						</label>
					</div>

					<!-- Default Value -->
					<div class="alm-filter--row not-required" id="row-default-value" v-show="filter.field_type !== 'range_slider'">
						<label>
							<div class="label">
								<?php esc_attr_e( 'Default Value (Fallback)', 'ajax-load-more-filters' ); ?>
								<a
									title="<?php esc_attr_e( 'Default values allow for setting initial Ajax Load More parameters and provide a fallback for when no filters are selected. Separate multiple values with a comma. e.g. design,development,app.', 'ajax-load-more-filters' ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
							</div>
							<div class="item">
								<input type="text" id="filter-default-value" data-id="default_value" :data-index="index" :value="filter.default_value" v-on:change="filterChange($event)">
							</div>
						</label>
					</div>

					<!-- Label -->
					<div class="alm-filter--row not-required" id="row-label" v-show="checkLabel(filter.field_type, index)">
						<label>
							<div class="label">
								<?php esc_attr_e( 'Field Label', 'ajax-load-more-filters' ); ?>
								<a
									title="<?php esc_attr_e( 'The HTML input label for the form element.', 'ajax-load-more-filters' ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
							</div>
							<div class="item">
								<input type="text" id="filter-label" data-id="label" :data-index="index" :value="filter.label" v-on:change="filterChange($event)">
							</div>
						</label>
					</div>

					<!-- Placeholder -->
					<div class="alm-filter--row not-required" id="row-placeholder" v-show="filter.field_type === 'text' || filter.field_type === 'date_picker'">
						<label>
							<div class="label">
							<?php esc_attr_e( 'Placeholder Text', 'ajax-load-more-filters' ); ?>
								<a
									title="<?php esc_attr_e( 'Placeholder text displayed on the <input> element.', 'ajax-load-more-filters' ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
						</div>
							<div class="item">
								<input type="text" id="filter-placeholder" data-id="placeholder" placeholder="<?php echo wp_kses_post( apply_filters( 'alm_filters_textfield_placeholder', __( 'Submit', 'ajax-load-more-filters' ) ) ); ?>" :data-index="index" :value="filter.placeholder" v-on:change="filterChange($event)">
							</div>
						</label>
					</div>

					<!-- Button Label -->
					<div class="alm-filter--row not-required" id="row-button-label" v-show="filter.field_type === 'text' || filter.field_type === 'date_picker'">
						<label>
							<div class="label">
								<?php esc_attr_e( 'Submit Button Label', 'ajax-load-more-filters' ); ?>
								<a
									title="<?php esc_attr_e( 'The label of the submit button.', 'ajax-load-more-filters' ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
							</div>
							<div class="item">
								<input type="text" id="filter-button-label" data-id="button_label" :data-index="index" :value="filter.button_label" v-on:change="filterChange($event)" placeholder="<?php echo wp_kses_post( apply_filters( 'alm_filters_textfield_submit_label', __( 'Submit', 'ajax-load-more-filters' ) ) ); ?>">
							</div>
						</label>
					</div>
				</section>

				<h4 class="alm-filter--section-toggle" role="button" tabindex="0" v-on:click="toggleSection($event)" v-on:keyup.enter="toggleSection($event)" aria-expanded="true">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M576 320C576 320.9 576 321.8 576 322.7C575.6 359.2 542.4 384 505.9 384L408 384C381.5 384 360 405.5 360 432C360 435.4 360.4 438.7 361 441.9C363.1 452.1 367.5 461.9 371.8 471.8C377.9 485.6 383.9 499.3 383.9 513.8C383.9 545.6 362.3 574.5 330.5 575.8C327 575.9 323.5 576 319.9 576C178.5 576 63.9 461.4 63.9 320C63.9 178.6 178.6 64 320 64C461.4 64 576 178.6 576 320zM192 352C192 334.3 177.7 320 160 320C142.3 320 128 334.3 128 352C128 369.7 142.3 384 160 384C177.7 384 192 369.7 192 352zM192 256C209.7 256 224 241.7 224 224C224 206.3 209.7 192 192 192C174.3 192 160 206.3 160 224C160 241.7 174.3 256 192 256zM352 160C352 142.3 337.7 128 320 128C302.3 128 288 142.3 288 160C288 177.7 302.3 192 320 192C337.7 192 352 177.7 352 160zM448 256C465.7 256 480 241.7 480 224C480 206.3 465.7 192 448 192C430.3 192 416 206.3 416 224C416 241.7 430.3 256 448 256z"/></svg>
					<?php esc_attr_e( 'Display Options', 'ajax-load-more-filters' ); ?>
					<span>
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M300.3 440.8C312.9 451 331.4 450.3 343.1 438.6L471.1 310.6C480.3 301.4 483 287.7 478 275.7C473 263.7 461.4 256 448.5 256L192.5 256C179.6 256 167.9 263.8 162.9 275.8C157.9 287.8 160.7 301.5 169.9 310.6L297.9 438.6L300.3 440.8z"/></svg>
					</span>
				</h4>

				<section class="alm-filter--section" aria-hidden="false">
					<!-- Title -->
					<div class="alm-filter--row not-required" id="row-title">
						<label>
							<div class="label">
								<?php esc_attr_e( 'Title', 'ajax-load-more-filters' ); ?>
								<a
									title="<?php echo wp_kses_post( __( 'Add an optional title for this filter block. Titles are displayed as <h3> elements above the form element.', 'ajax-load-more-filters' ) ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
							</div>
							<div class="item">
								<input type="text" id="filter-title" data-id="title" :data-index="index" :value="filter.title" v-on:keyup="filterChange($event)">
							</div>
						</label>
					</div>

					<!-- Desc -->
					<div class="alm-filter--row not-required" id="row-description">
						<label>
							<div class="label align-top">
								<?php esc_attr_e( 'Description', 'ajax-load-more-filters' ); ?>
								<a
									title="<?php esc_attr_e( 'Add an optional short description for this filter block. Descriptions are displayed as paragraphs below the Title. HTML markup is allowed.', 'ajax-load-more-filters' ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
							</div>
							<div class="item">
								<textarea id="filter-description" rows="#" data-id="description" :data-index="index" :value="filter.description" v-on:keyup="filterChange($event)"></textarea>
							</div>
						</label>
					</div>

					<!-- Toggle -->
					<div class="alm-filter--row not-required" id="row-section_toggle" v-show="filter.title !== ''">
						<div class="fake-label">
							<div class="label">
								<?php esc_attr_e( 'Expand/Collapse Toggle', 'ajax-load-more-filters' ); ?>
								<a
									title="<?php esc_attr_e( 'Allow users to expand and collapse this filter block.', 'ajax-load-more-filters' ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
							</div>
							<div class="item">
								<div class="checkbox-wrapper">
									<input type="checkbox" :id="`section_toggle_${index}`" data-id="section_toggle" :data-index="index" value="true" :checked="filter.section_toggle !== '' && filter.section_toggle === true" v-on:change="filterChange($event)">
									<label class="checkbox" :for="`section_toggle_${index}`">
										<span><?php esc_attr_e( 'Display Toggle', 'ajax-load-more-filters' ); ?></span>
									</label>
								</div>
							</div>
						</div>
					</div>

					<!-- Toggle Options -->
					<div class="related-filters--wrap" v-show="filter.title !== '' && filter.section_toggle === true">

						<div class="alm-filters-inline-desc no-margin">
							<h3><?php esc_attr_e( 'Toggle Options', 'ajax-load-more-filters' ); ?></h3>
						</div>

						<!-- Toggle Status -->
						<div class="alm-filter--row not-required">
							<label>
								<div class="label">
									<?php esc_attr_e( 'Initial Toggle State', 'ajax-load-more-filters' ); ?>
									<a
										title="<?php esc_attr_e( 'Set the initial open/closed state for this filter block on page load.', 'ajax-load-more-filters' ); ?>"
										href="javascript:void(0)"
										class="fa fa-question-circle tooltip" tabindex="-1"
									></a>
								</div>
								<div class="item">
									<div class="select-wrapper">
										<select
											id="filter-section_toggle_status"
											class="alm-filter-select"
											data-id="section_toggle_status"
											:data-index="index"
											v-on:change="filterChange($event)"
										>
											<option value="expanded" :selected="filter.section_toggle_status === 'expanded' ? 'selected' : ''"><?php esc_attr_e( 'Expanded', 'ajax-load-more-filters' ); ?></option>
											<option value="collapsed" :selected="filter.section_toggle_status === 'collapsed' ? 'selected' : ''"><?php esc_attr_e( 'Collapsed', 'ajax-load-more-filters' ); ?></option>
										</select>
									</div>
								</div>
							</label>
						</div>
					</div>

					<!-- Classes -->
					<div class="alm-filter--row not-required" id="row-classes">
						<label>
							<div class="label">
								<?php esc_attr_e( 'CSS Classes', 'ajax-load-more-filters' ); ?>
								<a
									title="<?php esc_attr_e( 'Add custom CSS classnames to the container of this filter block.', 'ajax-load-more-filters' ); ?>"
									href="javascript:void(0)"
									class="fa fa-question-circle tooltip" tabindex="-1"
								></a>
							</div>
							<div class="item no-">
								<input type="text" id="filter-classes" data-id="classes" :data-index="index" :value="filter.classes" placeholder="<?php echo esc_attr( apply_filters( 'alm_filters_css_classes', 'row container' ) ); ?>" v-on:keyup="filterChange($event)">
							</div>
						</label>
					</div>

				</section>

			</div>
		</div>
		<div class="alm-filter--controls">
			<div class="count" :title="'<?php esc_attr_e( 'Filter Block', 'ajax-load-more-filters' ); ?> #' + (index+1)">{{ index + 1 }}</div>
			<button class="button" v-on:click="removeFilter($event)" :data-index="index" :class="{ disabled: filters.length == 1 }">
				<span><?php esc_attr_e( 'Remove Filter', 'ajax-load-more-filters' ); ?></span>
			</button>
		</div>
	</div>
</script>
