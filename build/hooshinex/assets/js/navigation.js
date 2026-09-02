/**
 * Accessible mobile navigation toggle for the fallback header.
 * Dependency-free; only runs when the fallback header is present.
 */
( function () {
	'use strict';

	var nav = document.getElementById( 'site-navigation' );

	if ( ! nav ) {
		return;
	}

	var toggle = nav.querySelector( '.menu-toggle' );
	var menu = nav.querySelector( 'ul' );

	if ( ! toggle || ! menu ) {
		return;
	}

	toggle.addEventListener( 'click', function () {
		var isOpen = nav.classList.toggle( 'is-open' );
		toggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( 'Escape' === event.key && nav.classList.contains( 'is-open' ) ) {
			nav.classList.remove( 'is-open' );
			toggle.setAttribute( 'aria-expanded', 'false' );
			toggle.focus();
		}
	} );

	document.addEventListener( 'click', function ( event ) {
		if ( nav.classList.contains( 'is-open' ) && ! nav.contains( event.target ) ) {
			nav.classList.remove( 'is-open' );
			toggle.setAttribute( 'aria-expanded', 'false' );
		}
	} );
} )();
