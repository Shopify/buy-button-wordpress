/**
 * Shopify Buy Button - Admin Widget
 * https://www.shopify.com/buy-button
 *
 * Licensed under the GPLv2+ license.
 */

import $ from 'jquery';
import modal from './add-button-modal';

$( function() {
	$( document.body ).on( 'click', '#sbb-add-widget', function( e ) {
		var $widgetContent = $( this ).closest( '.widget-content' ),
			$c = {
				inputType:   $widgetContent.find( '.sbb-hidden-embed_type' ),
				inputShop:   $widgetContent.find( '.sbb-hidden-shop' ),
				inputHandle: $widgetContent.find( '.sbb-hidden-product_handle' )
			};

		e.preventDefault();

		modal( ( data ) => {
			$c.inputType.val( data.resourceType );
			$c.inputShop.val( data.shop );
			$c.inputHandle.val( data.resourceHandles.join( ', ' ) );

			console.log( data );
		} );
	} );
} );
