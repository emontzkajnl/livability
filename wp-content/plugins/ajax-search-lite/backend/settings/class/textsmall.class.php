<?php
if (!class_exists("wpdreamsTextSmall")) {
    /**
     * Class wpdreamsTextSmall
     *
     * A 5 characters wide text input field.
     *
     * @package  WPDreams/OptionsFramework/Classes
     * @category Class
     * @author Ernest Marcinko <ernest.marcinko@wp-dreams.com>
     * @link http://wp-dreams.com, http://codecanyon.net/user/anago/portfolio
     * @copyright Copyright (c) 2014, Ernest Marcinko
     */
    class wpdreamsTextSmall extends wpdreamsType {

        private $icon = 'none';
        private $suffix = '';
        private $inputClasses = '';
        private $iconMsg;

        function getType() {
            parent::getType();
            $this->processData();
            echo "<div class='wpdreamsTextSmall'>";
            if ($this->label != "")
                echo "<label for='wpdreamstextsmall_" . self::$_instancenumber . "'>" . $this->label . "</label>";

            if ( $this->icon != 'none' ) {
                ?>
                <span
                    title="<?php echo isset($this->iconMsg[$this->icon]) ? $this->iconMsg[$this->icon] : ''; ?>"
                    class="wpd-txt-small-icon wpd-txt-small-icon-<?php echo $this->icon ?>">
                </span>
                <?php
            }
            echo "<input isparam=1 class='small ".$this->inputClasses."' type='text' id='wpdreamstextsmall_" . self::$_instancenumber . "' name='" . esc_attr($this->name) . "' value=\"" . stripslashes(esc_html($this->value)) . "\" />";
            echo $this->suffix;
            echo "
        <div class='triggerer'></div>
      </div>";
        }

        public function processData() {
            $this->iconMsg = array(
                'phone' => __('Phone devices, on 0px to 640px widths', 'ajax-search-lite'),
                'tablet' => __('Tablet devices, on 641px to 1024px widths', 'ajax-search-lite'),
                'desktop' => __('Desktop devices, 1025px width  and higher', 'ajax-search-lite')
            );

            if ( is_array($this->data) ) {
                $this->icon = isset($this->data['icon']) ? $this->data['icon'] : $this->icon;
                $this->suffix = isset($this->data['suffix']) ? $this->data['suffix'] : $this->suffix;
                $this->inputClasses = isset($this->data['inputClasses']) ? $this->data['inputClasses'] : $this->inputClasses;
            }
        }
    }
}