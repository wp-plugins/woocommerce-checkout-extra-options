<?php
/**
 * Plugin Name: WooCommerce Checkout Extra options 
 * Plugin URI: 
 * Description: Customization of woocommerce plugins using hooks like remove shipping address from checkout 	* rename add to cart button different -2 based on products etc.
 * Author: kantsverma
 * Author URI: http://kantsverma.tumblr.com
 * Version: 1.0
 *
 */
 ob_start();
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/* 
 *  Remove the shipping address section from eheckout page 
 **/
 
add_action('woocommerce_checkout_init','ecpt_disable_billing_shipping');
 
function ecpt_disable_billing_shipping($checkout){
 
 $checkout->checkout_fields['shipping']=array();
 $checkout->checkout_fields['order']=array();
 unset($checkout->checkout_fields['billing']['billing_postcode']);
 unset($checkout->checkout_fields['billing']['billing_phone']);
 
 return $checkout;
}
/* ###################
 *  Remove the shipping and billing text from checkout page 
 * ####*/
function ecpt_custom_addresses_labels( $translated_text, $text, $domain )
{
    switch ( $translated_text )
    {
        case 'Billing Address' : /* Front-end */
            $translated_text = __( '', 'woocommerce' );
            break;
        case 'Add to cart' : // Back-end 
            $translated_text = __( 'yes let me in', 'woocommerce' );
            break;
		case 'Additional Information' : // Back-end 
            $translated_text = __( '', 'woocommerce' );
            break;
        case 'Billing Address' : /* Front-end */
            $translated_text = __( '', 'woocommerce' );
            break;                                      
    }
    return $translated_text;
}
add_filter( 'gettext', 'ecpt_custom_addresses_labels', 20, 3 );

// redirect to custom page if cart is empty 
add_action("template_redirect", 'ecpt_redirection_function');
function ecpt_redirection_function(){
    global $woocommerce;
    if( is_cart() && sizeof($woocommerce->cart->cart_contents) == 0){
        wp_safe_redirect( get_home_url().'/join-sa' );
    }
}

/* Custom login redirect for whoocommerce checkout */
add_filter('woocommerce_login_redirect', 'ecpt_wc_login_redirect');
 
function ecpt_wc_login_redirect( $redirect_to ) {
     $redirect_to = site_url('elite-mentorship-home');
     return $redirect_to;
}

/**
 * adding styles and js files to wp head
 */
function ecpt_drish_extra_scripts() {	
	wp_enqueue_style( 'style-name', plugins_url( 'css/hs_style.css', __FILE__ ) );
	//wp_enqueue_script( 'script-name', get_template_directory_uri() . '/js/example.js', array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'ecpt_drish_extra_scripts' );


/**
 * Add a content block after all notices, such as the login and coupon notices.
 *
 */
add_action( 'woocommerce_before_checkout_form', 'ecpt__add_checkout_content', 12 );
function ecpt__add_checkout_content() {
	global $woocommerce;
	
	// check if cart is empty
	if ( sizeof( $woocommerce->cart->cart_contents) > 0 ) {
	
		foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $cart_item ) {
			
			echo $cart_item['data']->post->post_content;
		}
	}	
}
 // change the whoocommerce  single product add to cart text
 
add_filter( 'woocommerce_product_add_to_cart_text', 'ecpt_archive_custom_cart_button_text' ); // 2.1 +
function ecpt_archive_custom_cart_button_text() {
	global $product;
	
	if($product->id =='1440'){
		return __( 'No thanks, I’ll just have the ebook', 'woocommerce' );
	}else{
		return __( 'YES, I WANT TO JOIN', 'woocommerce' );	
	}
} 
?>