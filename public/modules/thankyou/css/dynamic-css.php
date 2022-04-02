<?php

 if ($order_overview == 'off') {
     $output .= '
 		.woocommerce-order ul.woocommerce-order-overview{
 			display: none;
 		}
 	';
 }

 if ($order_details == 'off') {
     $output .= '
 		.woocommerce-order section.woocommerce-order-details{
 			display: none;
 		}
 	';
 }

 if ($billing_details == 'off') {
     $output .= '
 		.woocommerce-order .woocommerce-customer-details .woocommerce-column--billing-address{
 			display: none;
 		}
 		.woocommerce-order .woocommerce-customer-details .woocommerce-column--shipping-address{
 			float:left;
 		}
 	';
 }

 if ($shipping_details == 'off') {
     $output .= '
 		.woocommerce-order .woocommerce-customer-details .woocommerce-column--shipping-address{
 			display: none;
 		}
 	';
 }

 if ('off' == $billing_details && 'off' == $shipping_details) {
     $output .= '
 		.woocommerce-order .woocommerce-customer-details{
 			display: none;
 		}
 		';
 }
