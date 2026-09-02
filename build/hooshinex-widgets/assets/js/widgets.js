/**
 * Frontend handlers for Hooshinex widgets.
 *
 * Elementor fires `frontend/element_ready/<widget-name>.default` on initial load AND
 * after every edit in the editor, so handlers registered here stay live while
 * designing. The behaviour itself lives in the theme's app.js and is exposed on
 * `window.hooshinex`; this file re-runs it per widget instance, and provides a small
 * standalone fallback so the widgets still work under a different theme.
 */
( function ( $ ) {
	'use strict';

	/**
	 * Minimal carousel used only when the Hooshinex theme (and its app.js) is absent.
	 *
	 * @param {HTMLElement} root Carousel root.
	 */
	function fallbackCarousel( root ) {
		var track = root.querySelector( '[data-hx-track]' );

		if ( ! track || root.dataset.hxReady ) {
			return;
		}

		root.dataset.hxReady = '1';

		var prev = root.querySelector( '[data-hx-prev]' );
		var next = root.querySelector( '[data-hx-next]' );
		var rtl = getComputedStyle( track ).direction === 'rtl';

		function step() {
			var first = track.firstElementChild;

			if ( ! first ) {
				return track.clientWidth;
			}

			var styles = getComputedStyle( track );

			return first.getBoundingClientRect().width + ( parseFloat( styles.columnGap || styles.gap || 0 ) || 0 );
		}

		function go( direction ) {
			track.scrollBy( { left: step() * direction * ( rtl ? -1 : 1 ), behavior: 'smooth' } );
		}

		if ( prev ) {
			prev.addEventListener( 'click', function () {
				go( -1 );
			} );
		}

		if ( next ) {
			next.addEventListener( 'click', function () {
				go( 1 );
			} );
		}
	}

	/**
	 * Minimal spotlight fallback.
	 *
	 * @param {HTMLElement} root Spotlight root.
	 */
	function fallbackSpotlight( root ) {
		if ( root.dataset.hxReady ) {
			return;
		}

		root.dataset.hxReady = '1';

		var slides = Array.prototype.slice.call( root.querySelectorAll( '[data-hx-slide]' ) );
		var thumbs = Array.prototype.slice.call( root.querySelectorAll( '[data-hx-slide-to]' ) );

		thumbs.forEach( function ( thumb, index ) {
			thumb.addEventListener( 'click', function () {
				slides.forEach( function ( slide, position ) {
					slide.classList.toggle( 'is-active', position === index );
				} );

				thumbs.forEach( function ( other, position ) {
					other.classList.toggle( 'is-active', position === index );
					other.setAttribute( 'aria-selected', position === index ? 'true' : 'false' );
				} );
			} );
		} );
	}

	/**
	 * Minimal countdown fallback.
	 *
	 * @param {HTMLElement} root Countdown root.
	 */
	function fallbackCountdown( root ) {
		var deadline = parseInt( root.getAttribute( 'data-hx-countdown' ), 10 );

		if ( ! deadline || root.dataset.hxReady ) {
			return;
		}

		root.dataset.hxReady = '1';

		var fields = {
			days: root.querySelector( '[data-hx-cd-days]' ),
			hours: root.querySelector( '[data-hx-cd-hours]' ),
			minutes: root.querySelector( '[data-hx-cd-minutes]' ),
			seconds: root.querySelector( '[data-hx-cd-seconds]' )
		};

		function pad( value ) {
			return value < 10 ? '0' + value : String( value );
		}

		function tick() {
			var remaining = Math.max( 0, deadline - Math.floor( Date.now() / 1000 ) );

			if ( fields.days ) {
				fields.days.textContent = pad( Math.floor( remaining / 86400 ) );
			}

			if ( fields.hours ) {
				fields.hours.textContent = pad( Math.floor( ( remaining % 86400 ) / 3600 ) );
			}

			if ( fields.minutes ) {
				fields.minutes.textContent = pad( Math.floor( ( remaining % 3600 ) / 60 ) );
			}

			if ( fields.seconds ) {
				fields.seconds.textContent = pad( remaining % 60 );
			}
		}

		window.setInterval( tick, 1000 );
		tick();
	}

	/**
	 * Initialise everything inside a widget scope.
	 *
	 * @param {jQuery} $scope Widget wrapper.
	 */
	function initScope( $scope ) {
		var theme = window.hooshinex || {};
		var root = $scope[ 0 ];

		if ( ! root ) {
			return;
		}

		Array.prototype.forEach.call( root.querySelectorAll( '[data-hx-carousel]' ), function ( node ) {
			if ( theme.initCarousel ) {
				delete node.dataset.hxReady;
				theme.initCarousel( node );
			} else {
				fallbackCarousel( node );
			}
		} );

		Array.prototype.forEach.call( root.querySelectorAll( '[data-hx-spotlight]' ), function ( node ) {
			if ( theme.initSpotlight ) {
				theme.initSpotlight( node );
			} else {
				fallbackSpotlight( node );
			}
		} );

		Array.prototype.forEach.call( root.querySelectorAll( '[data-hx-countdown]' ), function ( node ) {
			if ( theme.initCountdown ) {
				theme.initCountdown( node );
			} else {
				fallbackCountdown( node );
			}
		} );

		if ( theme.convertDigits ) {
			theme.convertDigits( root );
		}
	}

	$( window ).on( 'elementor/frontend/init', function () {

		if ( 'undefined' === typeof elementorFrontend ) {
			return;
		}

		var widgets = [
			'hooshinex-feature-cards',
			'hooshinex-post-loop',
			'hooshinex-product-grid',
			'hooshinex-product-carousel',
			'hooshinex-category-grid',
			'hooshinex-amazing-product',
			'hooshinex-offer-banner',
			'hooshinex-questions',
			'hooshinex-promo-banners',
			'hooshinex-seller-cta',
			'hooshinex-hero',
			'hooshinex-section-heading'
		];

		widgets.forEach( function ( name ) {
			elementorFrontend.hooks.addAction(
				'frontend/element_ready/' + name + '.default',
				initScope
			);
		} );
	} );

} )( jQuery );
