<?php
namespace sgpbgamification;

class Tabs
{
    private $config;

    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public static function create($config, $selectedCol = '', $popupTypeObj = '')
    {
        $obj = new self();
        $obj->setConfig($config);
        $obj->selectedCol = strtolower($selectedCol);
        $obj->popupTypeObj = $popupTypeObj;
        return $obj;
    }

    public function render()
    {
        $settings = $this->getConfig();

        $selectedCol = $this->selectedCol;
        $content = '';
        $activeClassName = '';
        ob_start();
        ?>
        <div class=" sgpb-tabs">
            <?php foreach($settings as $key => $label): ?>
                <?php if ($selectedCol == $key): ?>
                    <?php $activeClassName = 'sgpb-tab-active'; ?>
                <?php endif; ?>
                <span data-key="<?php echo $key; ?>" class="sgpb-tab-link sgpb-flex-auto sgpb-padding-10 sgpb-text-center sgpb-option-tab-<?php echo $key; ?> <?php echo $activeClassName; ?>">
	                <?php echo esc_attr($label); ?>
                </span>
                <?php $activeClassName = ''; ?>
            <?php endforeach; ?>
        </div>
        <?php
        $content .= ob_get_contents();
        ob_end_clean();
        $content .= '<input type="hidden" class="sgpb-active-tab-name" value="'.esc_attr($selectedCol).'">';
        $content .= $this->renderContents();

        return $content;
    }

    public function renderContents()
    {
        $settings = $this->getConfig();
        $tabName = $this->selectedCol;
        $popupTypeObj = $this->popupTypeObj;
        ob_start();
        foreach($settings as $key => $label) { ?>
             <div id="sgpb-tab-content-wrapper-<?php echo $key; ?>" class="sgpb-tab-content-wrapper sgpb-padding-10" <?php echo ($tabName == $key) ? 'style="display: block;"': 'style="display: none"'; ?>>
                 <?php @include_once(SGPB_GAMIFICATION_VIEWS_PATH.$key.'.php');?>
             </div>
        <?php }
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

}
