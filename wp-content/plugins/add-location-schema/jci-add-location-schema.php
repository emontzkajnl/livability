<?php
// use \Yoast\WP\SEO\Generators\Schema\Abstract_Schema_Piece;
/**
 * Plugin Name:       Add Location Schema
 * Description:       Adds location schema via Yoast plugin
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            Journal Communications
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       add-ls
 *
 * @package           create-block
 */


//  If post or best place, get place data from place relationship. If place, from title or parent title. 


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Main JCI_Place_Schema class
 *
 * @since 1.0.0
 * @package JCI_Place_Schema
 */
class JCI_Place_Schema {

    	/**
	 * Primary constructor.
	 *
	 * @since 1.0.0
	 */
	function __construct() {
        add_filter( 'wpseo_schema_graph_pieces', array( $this, 'add_schema_piece' ) , 20, 2 );
    }

    public function add_schema_piece($pieces, $context ) {
        require_once( plugin_dir_path( __FILE__ ) . '/class-jci-place.php' );
        $this->context = $context;
        $pieces[] = new JCI_Place($context); 
        return $pieces;
    }
}

new JCI_Place_Schema; 