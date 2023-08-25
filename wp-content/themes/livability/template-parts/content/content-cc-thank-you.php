<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>



	<div class="entry-content">

		<?php
		the_content();
        if ($_POST):
            // echo 'post submission stuff here';
            // echo '<pre>';
            // print_r($_POST);
            // echo '</pre>';
        // account security key: j6Q2Rq7aTFh94DEr9VzQ7ZMJ28UT3Z8T
        // test account security key: 6457Thfj624V5r7WUwc5v6a68Zsd6YEm
        $vars = 'security_key=6457Thfj624V5r7WUwc5v6a68Zsd6YEm';
        $vars .= '&firstname='.urlencode($_POST['first_name']);
        $vars .= '&lastname='.urlencode($_POST['last_name']);
        $vars .= '&amount='.urlencode(number_format($_POST['payment'],2,".",""));
        $vars .= '&method='.urlencode($_POST['method']);
        $vars .= '&faddress_1='.urlencode($_POST['address_1']);
        $vars .= '&faddress_2='.urlencode($_POST['address_2']);
        $vars .= '&city='.urlencode($_POST['city']);
        $vars .= '&state='.urlencode($_POST['state']);
        $vars .= '&country=US';
        $vars .= '&postal_code='.urlencode($_POST['postal_code']);
        $vars .= '&email='.urlencode($_POST['email']);
        $vars .= '&phone='.urlencode($_POST['phone']);
        $vars .= '&test_mode=enabled';
        $vars .= '&payment_token='.urlencode($_POST['payment_token']);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://connect.transactiongateway.com/api/transact.php");
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
        curl_setopt($ch, CURLOPT_POST, 1);
        
        if (!($data = curl_exec($ch))) {
            return ERROR;
        }
        curl_close($ch);
        unset($ch);
        // print "\n$data\n";
        $results = [];
        $data = explode('&', $data);
        for ($i=0; $i < count($data); $i++) { 
            $rdata = explode("=", $data[$i]);
            // echo 'rdata is ';
            // print_r($rdata);
            // echo '<br />';
            $results[] = $rdata[1];
        }
        // echo "results: <br />";
        // print_r($results);
        // echo '<br />text is '.$results[1];
        // echo '<br />response is '.$results[0];
        $response_code = $results[0];
        $response_text = $results[1];
        // echo '<br />';
        // var_dump($data);
        // echo 'text is '.$data[1];
        if ($response_code == 1) {
            echo '<p style="text-align: center;">Thank you for your payment!</p>';
            echo '<p style="text-align: center;">Transaction Id: '.$results[3].'</p>';
        } else {
            echo '<p  style="text-align: center;">'.$response_text.'</p>';
        }

        else:
        $Post_ID = $_GET['post_id'];
        $Entry_ID = $_GET['entry_id'];
        // echo 'The post id is '.$Post_ID.' and the entry id is '.$Entry_ID.'.'; 
        ?>
    
    <form action="" method="POST">
    <table style="max-width: 550px; margin: 0 auto;"><tbody>  
    <tr>    
        <td><label for="post-id">Post ID:</label></td>
        <td><input type="text"  id="post-id" value="<?php echo $Post_ID; ?>"></td>
    </tr>
    <tr>
    <td><label for="payment">Payment Amount:</label></td>
        <td><input type="number" name="payment" id="payment" required="" value="10"></td>
    </tr>
    <tr>
    <td><label for="first_name">First name: </label></td>
        <td><input type="text" id="first_name"></td>
    </tr>
    <tr>
    <td><label for="last_name">Last name: </label></td>
        <td><input type="text" id="last_name"></td>
    </tr>
    <tr>
        <td><label for="address_1">Address: </label></td>
        <td><input type="text" name="address_1" id="address_1" required></td>
    </tr>
    <tr>
        <td><label for="address_2"></label></td>
        <td><input type="text" name="address_2" id="address_2" required></td>
    </tr>
    <tr>
        <td><label for="city">City:</label></td>
        <td><input type="text" name="city" id="city" required></td>
    </tr>
    <tr>
        <td><label for="state">State:</label></td>
        <td><input type="text" name="state" id="state" required></td>
    </tr>
    <tr>
        <td><label for="postal_code">Zip:</label></td>
        <td><input type="text" name="postal_code" id="postal_code" required></td>
    </tr>
    <tr>
        <td><label for="phone">Phone:</label></td>
        <td><input type="tel" name="phone" id="phone" required></td>
    </tr>
    <tr>
        <td><label for="email">Email:</label></td>
        <td><input type="email" name="email" id="email" required></td>
    </tr>
    <td><label for="method" >Payment Method:</label></td>
    <td>
        <table>
            <tr>
                <td><label for="card">Card</label><input type="radio" id="card" name="method" value="card" checked></td>
                <td><label for="echeck">eCheck</label><input type="radio" id="echeck" name="method" value="echeck"></td>
            </tr>
        </table>
    </td>
    </tr>
    <tr class="cc-fields">
            <td><label for="ccnumber">Credit Card Number</label></td>
            <td><div id="ccnumber"></div></td>
        </tr>
        <tr class="cc-fields">
            <td><label for="ccexp">Expiration:</label></td>
            <td><div id="ccexp"></div></td>
        </tr>
        <tr class="cc-fields">
            <td><label for="cvv">CVV</label></td>
            <td><div id="cvv"></div></td>
        </tr>
        <tr class="checking-fields" >
            <td><label for="checkname">Checking Account Name:</label></td>
            <td><div id="checkname"></div></td>
        </tr>
        <tr class="checking-fields" >
            <td><label for="checkaccount">Account Number:</label></td>
            <td><div id="checkaccount"></div></td>
        </tr>
        <tr class="checking-fields" >
            <td><label for="checkaba">Routing Number:</label></td>
            <td><div id="checkaba"></div></td>
        </tr>
    <tr>
    <td>
        <input type="hidden" name="merchant_defined_field_7" id="post-id" value="<?php echo $Post_ID; ?>">
        <input type="hidden" name="merchant_defined_field_8" id="entry-id" value="<?php echo $Entry_ID; ?>">
        <button type="button" id="payButton"  >Submit Payment</button>
    </td>
    </tr>
        </tbody>
    </table>    
        </form>
    <?php endif; ?>
	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer default-max-width">
			<?php
			edit_post_link(
				sprintf(
					/* translators: %s: Name of current post. Only visible to screen readers. */
					esc_html__( 'Edit %s', 'twentytwentyone' ),
					'<span class="screen-reader-text">' . get_the_title() . '</span>'
				),
				'<span class="edit-link">',
				'</span>'
			);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
