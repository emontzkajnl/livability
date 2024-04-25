<?php 
use sgpbsubscriptionplus\EmailIntegrations as EmailIntegrations;
$allIntegrations = EmailIntegrations::allEmailProvidersList(true);
$activeIntegrations = $allIntegrations['active'];
$availableIntegrations = $allIntegrations['available'];
?>
<div class="sgpb-wrapper sgpb-integrations">
	<!-- bunner -->
	<div class="row">
		<div class="col-md-12">
			<div class="sgpb-integrations-bunner">
				<img src="<?php echo SGPB_SUBSCRIPTION_PLUS_IMG_URL.'integration-bunner.png'; ?>">
			</div>
		</div>
	</div>

	<div class="row">
		<!-- Availables Connections-->
		<div class="col-md-6">
			<div class="sgpb-integration-block1">
				<h3 class="sgpb-integration-hundle">
					<span><?php _e('Available connections', SG_POPUP_TEXT_DOMAIN); ?></span>	
				</h3>
				<p class="sgpb-integration-paragraph"><?php _e('Here\'s a list of all the available apps that you can connect to.', SG_POPUP_TEXT_DOMAIN); ?></p>	
				<div class="sgpb-integration-table">
					<table> 
						<tbody>
							<?php if (empty($availableIntegrations)) : ?>
								<tr>
									<td>
										<div>
											<p class="sgpb-integration-paragraph"><?php _e('There are no more available connections', SG_POPUP_TEXT_DOMAIN) ?>.</p>
										</div>
									</td>
								</tr>		
							<?php endif ?> 
							<?php foreach ($availableIntegrations as $key => $value) : ?>
									<tr>
										<td>
											<div>
												<img class="sgpb-app-icon" src="<?php echo $value['logo']; ?>">	
												<span><?php echo $value['name'] ?></span>
												<div class="sgpb-icon sgpb-connect-field sgpb-connect-button" data-app-id="<?php echo $key; ?>"><span class="dashicons dashicons-arrow-right-alt"></span></div>
											</div>
										</td>
									</tr>		
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<!-- Active Connections-->
		<div class="col-md-6">
			<div class="sgpb-integration-block2">
				<h3 class="sgpb-integration-hundle">
					<span><?php _e('Active connections', SG_POPUP_TEXT_DOMAIN); ?></span>	
				</h3>
				<p class="sgpb-integration-paragraph"><?php _e('These are the apps you\'ve connected to using their APIs.', SG_POPUP_TEXT_DOMAIN); ?></p>
				<div class="sgpb-integration-table">
					<table> 
						<tbody>
							<?php foreach ($activeIntegrations as $key => $value) : ?>
									<tr>
										<td>
											<div>
												<img class="sgpb-app-icon" src="<?php echo $value['logo']; ?>">	
												<span><?php echo $value['name']; ?></span>
												<?php 
													if ($key == 'default') {
														continue;
													}
												?>
												<div class="sgpb-icon sgpb-disconnect-field" data-app-id="<?php echo $key; ?>"><span class="dashicons dashicons-no-alt"></span></div>
											</div>
										</td>
									</tr>		
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="sgpb-dialog-wrapper sgpb sgpb-hide">
	<div class="sgpb-integration-dialog-wrapper sgpb-position-relative">
		<span class="sgpb-close-icon sgpb-subscriber-data-popup-close-btn-js"></span>
		<div class="sgpb-wrapper">
			<div class="row ">
				<div class="col-sm-1 sgpb-add-subscriber-header-spinner-column">
					<img src="<?php echo SG_POPUP_IMG_URL.'ajaxSpinner.gif'; ?>" alt="gif" class="sgpb-subscribers-add-spinner js-sg-spinner js-sgpb-add-spinner sg-hide-element js-sg-import-gif" width="20px">
				</div>
			</div>
			<div class="row">
				<form style="margin:0;" method="post" id="dialog-form">			
					<div class="sgpb-dialog-image"><img src=""></div>
					<h3 class="sgpb-dialog-handle"><?php _e('Configure', SG_POPUP_TEXT_DOMAIN); ?></h3>
					<div class="sgpb-dialog-info"></div>
					<div class="sgpb-hide sgpb-error-message alert alert-danger" role="alert"></div> 
					<div class="sgpb-dialog-div"></div>
					<button type="button" class="sgpb-btn sgpb-btn-blue sgpb-connection sgpb-width-100"><?php _e('CONNECT', SG_POPUP_TEXT_DOMAIN)?></button>
					<img src="<?php echo SG_POPUP_IMG_URL.'ajaxSpinner.gif'; ?>" width="20px" class="sgpb-hide sgpb-js-integration-spinner">
				</form>
		</div>
	</div>
</div>
<div class="sgpb-success-message sgpb-hide">
	<div class="sgpb-success-message-text"></div>
	<div class="sgpb-icon sgpb-success-message-icon">
		<span class="dashicons dashicons-yes-alt"></span>
	</div>
</div>
<style type="text/css">
	.select2-container {
		z-index: 9999;
	}
</style>


