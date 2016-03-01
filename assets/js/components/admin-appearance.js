/**
 * Shopify Buy Button - Admin Appearance Page
 * https://www.shopify.com/buy-button
 *
 * Licensed under the GPLv2+ license.
 */

import $ from 'jquery';
import queryString from 'query-string';

$( function() {
	let $iframe = $( '.sbb-appearance-preview' ),
		addArgument = function( key, val ) {
			let loc = $iframe.attr( 'src' ),
				split = loc.split( '?' ),
				parsed = queryString.parse( split[1] );

			parsed[ key ] = val;
			loc = split[0] + '?' + queryString.stringify( parsed );

			$iframe.attr( 'src', loc );
		};

	$( document.body ).on( 'change', 'input', function() {
		if ( 'background' === this.name ) {
			if ( this.checked ) {
				addArgument( 'background_color', $( '#background_color' ).val().slice( 1 ) );
			} else {
				addArgument( 'background_color', 'transparent' );
			}
		} else {
			addArgument( this.name, this.value );
		}
	} );

	$( '.cmb2-colorpicker' ).wpColorPicker( {
		change: function( event, ui ) {
			let name = event.target.name,
				color = ui.color.toString().slice( 1 );

			if ( 'background_color' === name && 0 === $( '#background:checked' ).length ) {
				return;
			}

			addArgument( name, color );
		}
	} );
} );
