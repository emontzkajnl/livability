<?php
/**
 * WP Ultimate CSV Importer plugin file.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

namespace Smackcoders\FCSV;

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

class ProductMetaExtension extends ExtensionHandler{
	private static $instance = null;

	public static function getInstance() {

		if (ProductMetaExtension::$instance == null) {
			ProductMetaExtension::$instance = new ProductMetaExtension;
		}
		return ProductMetaExtension::$instance;
	}

	/**
	 * Provides Product Meta fields for specific post type
	 * @param string $data - selected import type
	 * @return array - mapping fields
	 */
	public function processExtension($data){
		$import_type = $data;
		$response = [];
		$import_type = $this->import_type_as($import_type);
		if(is_plugin_active('woocommerce/woocommerce.php')){    
			if($import_type == 'WooCommerce'){
				$pro_meta_fields = array(
					'Product Shipping Class' => 'product_shipping_class',
					'Visibility' => 'visibility',
					'Tax Status' => 'tax_status',
					'Product Type' => 'product_type',
					'Featured Product' => 'featured_product',
					'Tax Class' => 'tax_class',
					'File Paths' => 'file_paths',
					'Edit Last' => 'edit_last',
					'Edit Lock' => 'edit_lock',
					'Thumbnail Id' => 'thumbnail_id',
					'Manage Stock' => 'manage_stock',
					'Stock' => 'stock',
					'Stock Status' => 'stock_status',
					'Low Stock Threshold' => 'low_stock_threshold',
					'Stock Quantity' => 'stock_qty',
					'Total Sales' => 'total_sales',
					'Downloadable' => 'downloadable',
					'Virtual' => 'virtual',
					'Regular Price' => 'regular_price',
					'Sale Price' => 'sale_price',
					'Purchase Note' => 'purchase_note',
					'Menu Order' => 'menu_order',
					'Enable Reviews' => 'comment_status',
					'Weight' => 'weight',
					'Length' => 'length',
					'Width' => 'width',
					'Height' => 'height',
					'SKU' => 'sku',
					'UpSells ID' => 'upsell_ids',
					'CrossSells ID' => 'crosssell_ids',
					'Grouping ID' => 'grouping_product',
					'Sales Price Date From' => 'sale_price_dates_from',
					'Sales Price Date To' => 'sale_price_dates_to',
					'Price' => 'price',
					'Sold Individually' => 'sold_individually',
					'Backorders' => 'backorders',
					'Product Image Gallery' => 'product_image_gallery',
					'Product URL' => 'product_url',
					'Button Text' => 'button_text',
					'Featured' => 'featured',
					'Downloadable Files' => 'downloadable_files',
					'Download Limit' => 'download_limit',
					'Download Expiry' => 'download_expiry',
					'Download Type' => 'download_type',
					'parent' => 'parent',
					'_global_unique_id' => '_global_unique_id',
					'_subscription_period' => '_subscription_period',
					'_subscription_period_interval' => '_subscription_period_interval',
					'_subscription_length' => '_subscription_length',
					'_subscription_trial_period' => '_subscription_trial_period',
					'_subscription_trial_length' => '_subscription_trial_length',
					'_subscription_price' => '_subscription_price',
                );
				if(is_plugin_active('yith-cost-of-goods-for-woocommerce-premium/init.php') && ($import_type == 'WooCommerce')){
					$pro_meta_fields['yith_cog_cost'] = 'yith_cog_cost';
				}
				if(is_plugin_active('custom-woocommerce-extensions/custom-woocommerce-extensions.php') && ($import_type == 'WooCommerce')){
					$pro_meta_fields['pdf_download_url'] = 'pdf_download_url';
				}
	
				if(is_plugin_active('woocommerce-min-max-quantities/woocommerce-min-max-quantities.php')){
					$pro_meta_fields['minimum_allowed_quantity'] = 'minimum_allowed_quantity';
					$pro_meta_fields['maximum_allowed_quantity'] = 'maximum_allowed_quantity';
				}
				if ( is_plugin_active( 'yith-woocommerce-barcodes-premium/init.php' ) && $import_type == 'WooCommerce') {
					$pro_meta_fields['Barcode Protocol'] = '_ywbc_barcode_protocol';
					$pro_meta_fields['Barcode Value'] = '_ywbc_barcode_value';
					$pro_meta_fields['Barcode Display Value'] = '_ywbc_barcode_display_value';
				}
                if (is_plugin_active('variation-swatches-for-woocommerce/variation-swatches-for-woocommerce.php')) {
                        $pro_meta_fields['Product Attribute Type'] = 'product_attribute_type';
                }
			}
			// if($import_type == 'WooCommerceVariations'){            
			// 	$pro_meta_fields = array(
			// 		'Featured' => 'featured',
			// 		'Downloadable Files' => 'downloadable_files',
			// 		'Download Limit' => 'download_limit',
			// 		'Download Expiry' => 'download_expiry',
			// 		'Price' => 'price',
			// 		'Sales Price Date From' => 'sale_price_dates_from',
			// 		'Sales Price Date To' => 'sale_price_dates_to',
			// 		'Regular Price' => 'regular_price',
			// 		'Sale Price' => 'sale_price',
			// 		'Purchase Note' => 'purchase_note',
			// 		'Default Attributes' => 'default_attributes',
			// 		'Custom Attributes' => 'custom_attributes',
			// 		'Enable Reviews' => 'comment_status',
			// 		'Tax Status' => 'tax_status',
			// 		'Tax Class' => 'tax_class',
			// 		'Weight' => 'weight',
			// 		'Length' => 'length',
			// 		'Width' => 'width',
			// 		'Height' => 'height',
			// 		'Downloadable' => 'downloadable',
			// 		'Virtual' => 'virtual',
			// 		'Stock' => 'stock',
			// 		'Stock Status' => 'stock_status',
			// 		'Low Stock Threshold' => 'low_stock_threshold',
			// 		'Stock Quantity' => 'stock_qty',
			// 		'Sold Individually' => 'sold_individually',
			// 		'Manage Stock' => 'manage_stock',
			// 		'Backorders' => 'backorders',
			// 		'Thumbnail Id' => 'thumbnail_id',
			// 		'_subscription_period' => '_subscription_period',
			// 		'_subscription_period_interval' => '_subscription_period_interval',
			// 		'_subscription_length' => '_subscription_length',
			// 		'_subscription_trial_period' => '_subscription_trial_period',
			// 		'_subscription_trial_length' => '_subscription_trial_length',
			// 		'_subscription_price' => '_subscription_price',
			// 		'_subscription_sign_up_fee' => '_subscription_sign_up_fee',
			// 		'Variation Description' => 'variation_description',
			// 		'Variation Shipping Class' => 'variation_shipping_class'
			// 	);
			// }
			if($import_type == 'WooCommerceOrders'){            
				$pro_meta_fields = array(
					'Recorded Sales'          => 'recorded_sales',
					'Payment Method Title'    => 'payment_method_title',
					'Payment Method'          => 'payment_method',
					'Transaction Id'          => 'transaction_id',
					'Billing First Name'      => 'billing_first_name',
					'Billing Last Name'       => 'billing_last_name',
					'Billing Company'         => 'billing_company',
					'Billing Address1'        => 'billing_address_1',
					'Billing Address2'        => 'billing_address_2',
					'Billing City'            => 'billing_city',
					'Billing PostCode'        => 'billing_postcode',
					'Billing State'           => 'billing_state',
					'Billing Country'         => 'billing_country',
					'Billing Phone'           => 'billing_phone',
					'Billing Email'           => 'billing_email',
					'Shipping First Name'     => 'shipping_first_name',
					'Shipping Last Name'      => 'shipping_last_name',
					'Shipping Company'        => 'shipping_company',
					'Shipping Address1'       => 'shipping_address_1',
					'Shipping Address2'       => 'shipping_address_2',
					'Shipping City'           => 'shipping_city',
					'Shipping PostCode'       => 'shipping_postcode',
					'Shipping State'          => 'shipping_state',
					'Shipping Country'        => 'shipping_country',
					'Customer User'           => 'customer_user',
					'Order Currency'          => 'order_currency',
					'Order Shipping Tax'      => 'order_shipping_tax',
					'Order Tax'               => 'order_tax',
					'Order Total'             => 'order_total',
					'Cart Discount Tax'       => 'cart_discount_tax',
					'Cart Discount'           => 'cart_discount',
					'Order Shipping'          => 'order_shipping',
					'ITEM: name'              => 'item_name',
					'ITEM: type'              => 'item_type',
					'ITEM: variation_id'      => 'item_variation_id',
					'ITEM: product_id'        => 'item_product_id',
					'ITEM: line_subtotal'     => 'item_line_subtotal',
					'ITEM: line_subtotal_tax' => 'item_line_subtotal_tax',
					'ITEM: line_total'        => 'item_line_total',
					'ITEM: line_tax'          => 'item_line_tax',
					'ITEM: line_tax_data'     => 'item_line_tax_data',
					'ITEM: tax_class'         => 'item_tax_class',
					'ITEM: qty'               => 'item_qty',
					'FEE: name'               => 'fee_name',
					'FEE: type'               => 'fee_type',
					'FEE: tax_class'          => 'fee_tax_class',
					'FEE: line_total'         => 'fee_line_total',
					'FEE: line_tax'           => 'fee_line_tax',
					'FEE: line_tax_data'      => 'fee_line_tax_data',
					'FEE: line_subtotal'      => 'fee_line_subtotal',
					'FEE: line_subtotal_tax'  => 'fee_line_subtotal_tax',
					'SHIPMENT: name'          => 'shipment_name',
					'SHIPMENT: method_id'     => 'shipment_method_id',
					'SHIPMENT: cost'          => 'shipment_cost',
					'SHIPMENT: taxes'         => 'shipment_taxes',
				);
				if ( is_plugin_active( 'yith-woocommerce-order-tracking-premium/init.php' ) && $import_type == 'WooCommerceOrders') {
					$pro_meta_fields['Tracking Coe'] = 'ywot_tracking_code';
					$pro_meta_fields['Tracking PostCode'] = 'ywot_tracking_postcode';
					$pro_meta_fields['Tracking Carrier ID'] = 'ywot_carrier_id';
					$pro_meta_fields['Pick Up Date'] = 'ywot_pick_up_date';
					$pro_meta_fields['Estimated Devliery Date'] = 'ywot_estimated_delivery_date';
					$pro_meta_fields['Pick Up Status '] = 'ywot_picked_up';
				}
	
			}
			
			if($import_type == 'WooCommerceCoupons'){           
				$pro_meta_fields = array(
					'Discount Type' => 'discount_type',
					'Coupon Amount' => 'coupon_amount',
					'Individual Use' => 'individual_use',
					'Product Ids' => 'product_ids',
					'Exclude Product Ids' => 'exclude_product_ids',
					'Usage Limit' => 'usage_limit',
					'Usage Limit Per User' => 'usage_limit_per_user',
					'Limit Usage' => 'limit_usage_to_x_items',
					'Expiry Date' => 'expiry_date',
					'Free Shipping' => 'free_shipping',
					'Exclude Sale Items' => 'exclude_sale_items',
					'Product Categories' => 'product_categories',
					'Exclude Product Categories' => 'exclude_product_categories',
					'Minimum Amount' => 'minimum_amount',
					'Maximum Amount' => 'maximum_amount',
					'Customer Email' => 'customer_email',
					'Wildcard Value'  => 'wildcard_value',
					'Wildcard Type'   => 'wildcard_type',
				);
			}
			if($import_type == 'WooCommerceRefunds' ){            
				$pro_meta_fields = array(
					'Recorded Sales' => 'recorded_sales',
					'Refund Amount' => 'refund_amount',
					'Order Shipping Tax' => 'order_shipping_tax',
					'Order Tax' => 'order_tax',
					'Order Shipping' => 'order_shipping',
					'Cart Discount' => 'cart_discount',
					'Cart Discount Tax' => 'cart_discount_tax',
					'Order Total' => 'order_total',
					'Customer User' =>'customer_user'
				);
			}
			if(is_plugin_active('woocommerce-chained-products/woocommerce-chained-products.php') && $import_type == 'WooCommerce') {
				$chain_product = array(
					'Chained Product Detail' => 'chained_product_detail',
					'Chained Product Manage Stock' => 'chained_product_manage_stock',
				);
				foreach($chain_product as $key => $value){
					$pro_meta_fields[$key] = $value;
				}
			}
			if(is_plugin_active('woocommerce-product-retailers/woocommerce-product-retailers.php') && $import_type == 'WooCommerce') {
				$retailers = array(
					'Retailers Only Purchase' => 'wc_product_retailers_retailer_only_purchase',
					'Retailers Use Buttons' => 'wc_product_retailers_use_buttons',
					'Retailers Product Button Text' => 'wc_product_retailers_product_button_text',
					'Retailers Catalog Button Text' => 'wc_product_retailers_catalog_button_text',
					'Retailers Id' => 'wc_product_retailers_id',
					'Retailers Price' => 'wc_product_retailers_price',
					'Retailers URL' => 'wc_product_retailers_url',
				);
				foreach($retailers as $key => $value){
					$pro_meta_fields[$key] = $value;
				}
			}
			if(is_plugin_active('woocommerce-product-addons/woocommerce-product-addons.php') && $import_type == 'WooCommerce') {
				$product_Addons = array(
					'Product Addons Exclude Global' => 'product_addons_exclude_global',
					'Product Addons Group Name' => 'product_addons_group_name',
					'Product Addons Group Description' => 'product_addons_group_description',
					'Product Addons Type' => 'product_addons_type',
					'Product Addons Position' => 'product_addons_position',
					'Product Addons Required' => 'product_addons_required',
					'Product Addons Label Name' => 'product_addons_label_name',
					'Product Addons Price' => 'product_addons_price',
					'Product Addons Minimum' => 'product_addons_minimum',
					'Product Addons Maximum' => 'product_addons_maximum',
				);
				foreach($product_Addons as $key => $value){
					$pro_meta_fields[$key] = $value;
				}
			}
			if(is_plugin_active('woocommerce-warranty/woocommerce-warranty.php') && $import_type == 'WooCommerce' ) {
				$warranty = array(
					'Warranty Label' => 'warranty_label',
					'Warranty Type' => 'warranty_type',
					'Warranty Length' => 'warranty_length',
					'Warranty Value' => 'warranty_value',
					'Warranty Duration' => 'warranty_duration',
					'Warranty Addons Amount' => 'warranty_addons_amount',
					'Warranty Addons Value' => 'warranty_addons_value',
					'Warranty Addons Duration' => 'warranty_addons_duration',
					'No Warranty Option' => 'no_warranty_option',
				);
				foreach($warranty as $key => $value){
					$pro_meta_fields[$key] = $value;
				}
			}
			if(is_plugin_active('woocommerce-pre-orders/woocommerce-pre-orders.php') && $import_type == 'WooCommerce' ) {
				$pre_orders = array(
					'Pre-Orders Enabled' => 'preorders_enabled',
					'Pre-Orders Fee' => 'preorders_fee',
					'Pre-Orders When to Charge' => 'preorders_when_to_charge',
					'Pre-Orders Availabilty Datetime' => 'preorders_availability_datetime'
				);
				foreach($pre_orders as $key => $value){
					$pro_meta_fields[$key] = $value;
				}
			}   
		}

		$pro_meta_fields_line = $this->convert_static_fields_to_array($pro_meta_fields);
		
		if($data == 'WooCommerce Orders'){
			$response['order_meta_fields'] = $pro_meta_fields_line; 
		}
		if($data == 'WooCommerce Coupons'){
			$response['coupon_meta_fields'] = $pro_meta_fields_line; 
		}
		if($data == 'WooCommerce Refunds'){
			$response['refund_meta_fields'] = $pro_meta_fields_line; 
		}
		if($data !== 'WooCommerce Orders' && $data !== 'WooCommerce Coupons' && $data !== 'WooCommerce Refunds'){
			$response['product_meta_fields'] = $pro_meta_fields_line; 
		}    
		return $response;		
	}

	/**
	 * Product Meta extension supported import types
	 * @param string $import_type - selected import type
	 * @return boolean
	 */
	public function extensionSupportedImportType($import_type ){
		if(is_plugin_active('woocommerce/woocommerce.php')){
			if($import_type == 'nav_menu_item'){
				return false;
			}

			$import_type = $this->import_name_as($import_type);
		
				if($import_type == 'WooCommerce' || $import_type == 'WPeCommerce' || $import_type == 'WooCommerceOrders' || $import_type == 'WooCommerceCoupons' || $import_type == 'WooCommerceRefunds') {
				return true;
			}else{
				return false;
			}
		}
	}

}
