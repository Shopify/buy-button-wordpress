/**
 * Shopify eCommerce Plugin - Shopping Cart - Admin Customize Page
 * https://www.shopify.com/buy-button
 *
 * Licensed under the GPLv2+ license.
 */

import $ from 'jquery';
import queryString from 'query-string';

$( function() {
	let $iframe = $( '.secp-customize-preview' ),
		addArgument = function( key, val ) {
			let loc = $iframe.attr( 'src' ),
				split = loc.split( '?' ),
				parsed = queryString.parse( split[1] );

			if ( '#' === val[0] ) {
				val = val.slice( 1 );
			}

			if ( parsed[ key ] !== val ) {
				if ( val ) {
					parsed[ key ] = val;
				} else {
					delete parsed[ key ];
				}
				loc = split[0] + '?' + queryString.stringify( parsed );

				$iframe.attr( 'src', loc );
			}
		};

	$( document.body ).on( 'change', 'input,select', function() {
		if ( 'background' === this.id ) {
			addArgument( this.name, this.checked );
			$( '.cmb2-id-background-color' ).toggleClass( 'disabled', ! this.checked );
		} else {
			addArgument( this.name, this.value );
		}
	} );

	// Add color picker change event
	$( '.cmb2-colorpicker' ).wpColorPicker( {
		change: function( event, ui ) {
			let name = event.target.name,
				color = ui.color.toString();

			addArgument( name, color );
		}
	} );

	// Adjust color picker styling to have field title in button.
	setTimeout( ()=>{
		$( '.wp-color-result' ).each( function() {
			let $this = $( this ), newTitle;

			newTitle = $this.closest( '.cmb-row' )
							.find( '.cmb-th label' )
							.text();

			$this.attr( 'title', newTitle ).attr( 'data-current', newTitle );
		} );
	}, 1 );

	$( '.cmb2-id-background-color' ).toggleClass( 'disabled', $( '.cmb2-id-background input:checked' ).length === 0 );
} );
