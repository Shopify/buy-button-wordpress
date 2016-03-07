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

			if ( '#' === val[0] ) {
				val = val.slice( 1 );
			}

			if ( parsed[ key ] !== val ) {
				parsed[ key ] = val;
				loc = split[0] + '?' + queryString.stringify( parsed );

				$iframe.attr( 'src', loc );
			}
		};

	$( document.body ).on( 'change', 'input,select', function() {
		addArgument( this.name, this.value );
	} );

	$( '.cmb2-colorpicker' ).wpColorPicker( {
		change: function( event, ui ) {
			let name = event.target.name,
				color = ui.color.toString();

			addArgument( name, color );
		}
	} );
} );
