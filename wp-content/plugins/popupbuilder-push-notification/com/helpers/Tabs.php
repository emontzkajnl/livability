<?php
namespace sgpbpush;

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

    public static function create($config, $selectedCol = '')
    {
        $obj = new self();
        $obj->setConfig($config);
        $obj->selectedCol = strtolower($selectedCol);
        return $obj;
    }

    public function render()
    {
        $settings = $this->getConfig();
        $selectedCol = $this->selectedCol;
        $settingsCount = sizeof($settings);
        $colSize = 3;
        if ($settingsCount <= 6) {
            $colSize = 12/$settingsCount;
        }
        $selectedCol = $this->selectedCol;
        $content = '';
        $activeClassName = '';
        ob_start();
        ?>
        <div class="sgpb-tabs">
            <?php foreach($settings as $key => $label): ?>
                <?php if ($selectedCol == $key): ?>
                    <?php $activeClassName = 'sgpb-tab-active'; ?>
                <?php endif; ?>
	            <span type="button" id="<?php echo esc_attr($label); ?>" data-key="<?php echo $key; ?>" class="sgpb-tab-link sgpb-option-tab-<?php echo $key; ?> <?php echo $activeClassName; ?>"><?php echo esc_attr($label); ?></span>
            <?php endforeach; ?>
        </div>
        <?php
        $content .= ob_get_contents();
        ob_end_clean();
        $content .= '<input type="hidden" class="sgpb-active-tab-name" value="'.esc_attr($selectedCol).'">';

        return $content;
    }
}
