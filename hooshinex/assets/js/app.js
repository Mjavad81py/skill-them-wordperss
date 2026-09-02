/**
 * Hooshinex front-end behaviour.
 *
 * Everything here is a progressive enhancement: the markup works without it, and
 * each module bails out quietly when its hook elements are missing. No jQuery is
 * required, though WooCommerce's own jQuery events are respected when present.
 *
 * @package Hooshinex
 */
( function () {
	'use strict';

	var data = window.hooshinexData || {};
	var i18n = data.i18n || {};

	var PERSIAN = [ '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' ];

	function qs( selector, scope ) {
		return ( scope || document ).querySelector( selector );
	}

	function qsa( selector, scope ) {
		return Array.prototype.slice.call( ( scope || document ).querySelectorAll( selector ) );
	}

	function on( element, type, handler, options ) {
		if ( element ) {
			element.addEventListener( type, handler, options || false );
		}
	}

	function debounce( fn, wait ) {
		var timer = null;

		return function () {
			var context = this;
			var args = arguments;

			window.clearTimeout( timer );
			timer = window.setTimeout( function () {
				fn.apply( context, args );
			}, wait );
		};
	}

	function escapeHtml( value ) {
		return String( value == null ? '' : value ).replace( /[&<>"']/g, function ( character ) {
			return {
				'&': '&amp;',
				'<': '&lt;',
				'>': '&gt;',
				'"': '&quot;',
				"'": '&#39;'
			}[ character ];
		} );
	}

	/* ---------------------------------------------------------------------
	 * Persian digits
	 * ------------------------------------------------------------------ */

	function toPersian( value ) {
		return String( value ).replace( /[0-9]/g, function ( digit ) {
			return PERSIAN[ digit ];
		} );
	}

	var SKIP_DIGITS = /^(SCRIPT|STYLE|TEXTAREA|INPUT|CODE|PRE|KBD|SVG|PATH)$/;

	function convertDigits( root ) {
		if ( ! data.persianDigits || ! root ) {
			return;
		}

		var walker = document.createTreeWalker( root, NodeFilter.SHOW_TEXT, {
			acceptNode: function ( node ) {
				if ( ! /[0-9]/.test( node.nodeValue ) ) {
					return NodeFilter.FILTER_REJECT;
				}

				var parent = node.parentNode;

				while ( parent && parent !== root.parentNode ) {
					if ( parent.nodeType !== 1 ) {
						break;
					}

					if ( SKIP_DIGITS.test( parent.nodeName ) ) {
						return NodeFilter.FILTER_REJECT;
					}

					if ( parent.hasAttribute && ( parent.hasAttribute( 'data-no-fa' ) || 'ltr' === parent.getAttribute( 'dir' ) ) ) {
						return NodeFilter.FILTER_REJECT;
					}

					parent = parent.parentNode;
				}

				return NodeFilter.FILTER_ACCEPT;
			}
		} );

		var nodes = [];
		var current;

		while ( ( current = walker.nextNode() ) ) {
			nodes.push( current );
		}

		nodes.forEach( function ( node ) {
			node.nodeValue = toPersian( node.nodeValue );
		} );
	}

	/* ---------------------------------------------------------------------
	 * Toasts
	 * ------------------------------------------------------------------ */

	function toast( message, kind ) {
		var host = qs( '[data-hx-toasts]' );

		if ( ! host || ! message ) {
			return;
		}

		var node = document.createElement( 'div' );

		node.className = 'hx-toast' + ( kind ? ' hx-toast--' + kind : '' );
		node.setAttribute( 'role', 'status' );
		node.textContent = message;

		host.appendChild( node );

		window.requestAnimationFrame( function () {
			node.classList.add( 'is-visible' );
		} );

		window.setTimeout( function () {
			node.classList.remove( 'is-visible' );
			window.setTimeout( function () {
				if ( node.parentNode ) {
					node.parentNode.removeChild( node );
				}
			}, 350 );
		}, 3600 );
	}

	/* ---------------------------------------------------------------------
	 * Sticky header
	 * ------------------------------------------------------------------ */

	function initHeader() {
		var header = qs( '[data-hx-header]' );

		if ( ! header ) {
			return;
		}

		var ticking = false;

		function update() {
			header.classList.toggle( 'is-scrolled', window.scrollY > 12 );
			ticking = false;
		}

		on( window, 'scroll', function () {
			if ( ! ticking ) {
				ticking = true;
				window.requestAnimationFrame( update );
			}
		}, { passive: true } );

		update();
	}

	/* ---------------------------------------------------------------------
	 * Mobile drawer
	 * ------------------------------------------------------------------ */

	function initDrawer() {
		var drawer = qs( '#hxDrawer' );
		var overlay = qs( '#hxDrawerOverlay' );
		var opener = qs( '[data-hx-drawer-open]' );

		if ( ! drawer ) {
			return;
		}

		function open() {
			drawer.classList.add( 'is-open' );
			drawer.setAttribute( 'aria-hidden', 'false' );
			document.body.classList.add( 'hx-no-scroll' );

			if ( overlay ) {
				overlay.hidden = false;
				window.requestAnimationFrame( function () {
					overlay.classList.add( 'is-visible' );
				} );
			}

			if ( opener ) {
				opener.setAttribute( 'aria-expanded', 'true' );
			}

			var focusable = qs( 'a, button, input', drawer );

			if ( focusable ) {
				focusable.focus();
			}
		}

		function close() {
			drawer.classList.remove( 'is-open' );
			drawer.setAttribute( 'aria-hidden', 'true' );
			document.body.classList.remove( 'hx-no-scroll' );

			if ( overlay ) {
				overlay.classList.remove( 'is-visible' );
				window.setTimeout( function () {
					overlay.hidden = true;
				}, 300 );
			}

			if ( opener ) {
				opener.setAttribute( 'aria-expanded', 'false' );
				opener.focus();
			}
		}

		on( opener, 'click', open );
		on( overlay, 'click', close );

		qsa( '[data-hx-drawer-close]', drawer ).forEach( function ( button ) {
			on( button, 'click', close );
		} );

		on( document, 'keydown', function ( event ) {
			if ( 'Escape' === event.key && drawer.classList.contains( 'is-open' ) ) {
				close();
			}
		} );

		// Turn nested menus into accordions so long menus stay usable on phones.
		qsa( '.menu-item-has-children', drawer ).forEach( function ( item ) {
			var submenu = qs( '.sub-menu', item );

			if ( ! submenu ) {
				return;
			}

			var toggle = document.createElement( 'button' );

			toggle.type = 'button';
			toggle.className = 'hx-submenu-toggle';
			toggle.setAttribute( 'aria-expanded', 'false' );
			toggle.setAttribute( 'aria-label', i18n.submenu || 'زیرمنو' );
			toggle.innerHTML = '<span aria-hidden="true"></span>';

			item.insertBefore( toggle, submenu );

			on( toggle, 'click', function () {
				var expanded = 'true' === toggle.getAttribute( 'aria-expanded' );

				toggle.setAttribute( 'aria-expanded', expanded ? 'false' : 'true' );
				item.classList.toggle( 'is-open', ! expanded );
			} );
		} );
	}

	/* ---------------------------------------------------------------------
	 * Desktop mega menu keyboard support
	 * ------------------------------------------------------------------ */

	function initMegaMenu() {
		var nav = qs( '#site-navigation' );

		if ( ! nav ) {
			return;
		}

		qsa( '.menu-item-has-children', nav ).forEach( function ( item ) {
			var link = qs( 'a', item );

			if ( link ) {
				link.setAttribute( 'aria-haspopup', 'true' );
				link.setAttribute( 'aria-expanded', 'false' );
			}

			function setOpen( state ) {
				item.classList.toggle( 'is-open', state );

				if ( link ) {
					link.setAttribute( 'aria-expanded', state ? 'true' : 'false' );
				}
			}

			on( item, 'mouseenter', function () {
				setOpen( true );
			} );

			on( item, 'mouseleave', function () {
				setOpen( false );
			} );

			on( item, 'focusin', function () {
				setOpen( true );
			} );

			on( item, 'focusout', function ( event ) {
				if ( ! item.contains( event.relatedTarget ) ) {
					setOpen( false );
				}
			} );

			on( item, 'keydown', function ( event ) {
				if ( 'Escape' === event.key ) {
					setOpen( false );
				}
			} );
		} );
	}

	/* ---------------------------------------------------------------------
	 * Live search
	 * ------------------------------------------------------------------ */

	function trendingMarkup() {
		var terms = data.trending || [];

		if ( ! terms.length ) {
			return '';
		}

		var chips = terms.map( function ( term ) {
			return '<a class="hx-search-chip" href="' + escapeHtml( data.searchUrl || '' ) + encodeURIComponent( term ) + '">' + escapeHtml( term ) + '</a>';
		} ).join( '' );

		return '<div class="hx-search-section"><div class="hx-search-heading">' +
			escapeHtml( i18n.trending || 'جستجوهای پرطرفدار' ) +
			'</div><div class="hx-search-chips">' + chips + '</div></div>';
	}

	function resultsMarkup( results, query ) {
		if ( ! results.length ) {
			return '<div class="hx-search-empty">' + escapeHtml( i18n.noResults || 'نتیجه‌ای یافت نشد' ) + '</div>';
		}

		var items = results.map( function ( item ) {
			var thumb = item.thumb ?
				'<img src="' + escapeHtml( item.thumb ) + '" alt="" loading="lazy">' :
				'<span class="hx-search-result-fallback" aria-hidden="true"></span>';

			var price = item.price ? '<span class="hx-search-result-price">' + item.price + '</span>' : '';

			return '<a class="hx-search-result" href="' + escapeHtml( item.url ) + '">' +
				'<span class="hx-search-result-thumb">' + thumb + '</span>' +
				'<span class="hx-search-result-text">' +
				'<span class="hx-search-result-title">' + escapeHtml( item.title ) + '</span>' +
				( item.meta ? '<span class="hx-search-result-meta">' + escapeHtml( item.meta ) + '</span>' : '' ) +
				'</span>' + price + '</a>';
		} ).join( '' );

		var all = '<a class="hx-search-all" href="' + escapeHtml( data.searchUrl || '' ) + encodeURIComponent( query ) + '">' +
			escapeHtml( i18n.results || 'مشاهده همه نتایج' ) + '</a>';

		return '<div class="hx-search-section">' + items + '</div>' + all;
	}

	function initSearch() {
		var boxes = qsa( '[data-hx-search]' );

		if ( ! boxes.length ) {
			return;
		}

		var controllers = [];

		boxes.forEach( function ( box ) {
			var input = qs( '.hx-search-input', box );
			var panel = qs( '[data-hx-search-panel]', box );
			var clear = qs( '[data-hx-search-clear]', box );
			var aborter = null;

			if ( ! input || ! panel ) {
				return;
			}

			function openPanel( html ) {
				panel.innerHTML = html;
				box.classList.add( 'is-open' );
				convertDigits( panel );
			}

			function closePanel() {
				box.classList.remove( 'is-open' );
			}

			function showTrending() {
				var markup = trendingMarkup();

				if ( markup ) {
					openPanel( markup );
				} else {
					closePanel();
				}
			}

			var search = debounce( function ( query ) {
				if ( ! data.restSearch ) {
					return;
				}

				if ( aborter && aborter.abort ) {
					aborter.abort();
				}

				aborter = 'undefined' !== typeof AbortController ? new AbortController() : null;

				openPanel( '<div class="hx-search-loading">' + escapeHtml( i18n.searching || 'در حال جستجو…' ) + '</div>' );

				window.fetch( data.restSearch + '?q=' + encodeURIComponent( query ), {
					credentials: 'same-origin',
					signal: aborter ? aborter.signal : undefined,
					headers: data.nonce ? { 'X-WP-Nonce': data.nonce } : {}
				} )
					.then( function ( response ) {
						return response.ok ? response.json() : Promise.reject( response );
					} )
					.then( function ( payload ) {
						openPanel( resultsMarkup( ( payload && payload.results ) || [], query ) );
					} )
					.catch( function ( error ) {
						if ( error && 'AbortError' === error.name ) {
							return;
						}

						openPanel( '<div class="hx-search-empty">' + escapeHtml( i18n.tryAgain || 'خطا در جستجو، دوباره تلاش کنید' ) + '</div>' );
					} );
			}, 280 );

			on( input, 'input', function () {
				var query = input.value.trim();

				box.classList.toggle( 'has-text', '' !== query );

				if ( query.length < 2 ) {
					showTrending();
					return;
				}

				search( query );
			} );

			on( input, 'focus', function () {
				if ( input.value.trim().length < 2 ) {
					showTrending();
				} else {
					box.classList.add( 'is-open' );
				}
			} );

			on( clear, 'click', function () {
				input.value = '';
				box.classList.remove( 'has-text' );
				input.focus();
				showTrending();
			} );

			on( document, 'click', function ( event ) {
				if ( ! box.contains( event.target ) ) {
					closePanel();
				}
			} );

			on( input, 'keydown', function ( event ) {
				if ( 'Escape' === event.key ) {
					closePanel();
					input.blur();
				}
			} );

			controllers.push( input );
		} );

		// Ctrl/Cmd + K focuses the first visible search field.
		on( document, 'keydown', function ( event ) {
			if ( ! ( event.ctrlKey || event.metaKey ) || 'k' !== event.key.toLowerCase() ) {
				return;
			}

			var target = controllers.filter( function ( input ) {
				return input.offsetParent !== null;
			} )[ 0 ];

			if ( target ) {
				event.preventDefault();
				target.focus();
				target.select();
			}
		} );
	}

	/* ---------------------------------------------------------------------
	 * Carousels
	 * ------------------------------------------------------------------ */

	function initCarousel( root ) {
		var track = qs( '[data-hx-track]', root );

		if ( ! track ) {
			return;
		}

		var prev = qs( '[data-hx-prev]', root );
		var next = qs( '[data-hx-next]', root );
		var dotsHost = qs( '[data-hx-dots]', root );
		var autoplay = parseInt( root.getAttribute( 'data-hx-autoplay' ), 10 ) || 0;
		var rtl = getComputedStyle( track ).direction === 'rtl';
		var timer = null;

		function step() {
			var first = track.firstElementChild;

			if ( ! first ) {
				return track.clientWidth;
			}

			var styles = getComputedStyle( track );
			var gap = parseFloat( styles.columnGap || styles.gap || 0 ) || 0;

			return first.getBoundingClientRect().width + gap;
		}

		function maxScroll() {
			return track.scrollWidth - track.clientWidth;
		}

		function position() {
			return Math.abs( track.scrollLeft );
		}

		function scrollBy( direction ) {
			var delta = step() * direction * ( rtl ? -1 : 1 );

			track.scrollBy( { left: delta, behavior: 'smooth' } );
		}

		function pages() {
			var perView = Math.max( 1, Math.round( track.clientWidth / step() ) );

			return Math.max( 1, Math.ceil( track.children.length / perView ) );
		}

		function buildDots() {
			if ( ! dotsHost ) {
				return;
			}

			var total = pages();

			dotsHost.innerHTML = '';

			if ( total < 2 ) {
				return;
			}

			for ( var index = 0; index < total; index++ ) {
				( function ( page ) {
					var dot = document.createElement( 'button' );

					dot.type = 'button';
					dot.className = 'hx-dot';
					dot.setAttribute( 'aria-label', String( page + 1 ) );

					on( dot, 'click', function () {
						var offset = ( maxScroll() / Math.max( 1, total - 1 ) ) * page;

						track.scrollTo( { left: rtl ? -offset : offset, behavior: 'smooth' } );
					} );

					dotsHost.appendChild( dot );
				}( index ) );
			}

			syncDots();
		}

		function syncDots() {
			if ( ! dotsHost || ! dotsHost.children.length ) {
				return;
			}

			var total = dotsHost.children.length;
			var ratio = maxScroll() > 0 ? position() / maxScroll() : 0;
			var active = Math.round( ratio * ( total - 1 ) );

			qsa( '.hx-dot', dotsHost ).forEach( function ( dot, index ) {
				dot.classList.toggle( 'is-active', index === active );
			} );
		}

		function syncNav() {
			var atStart = position() <= 2;
			var atEnd = position() >= maxScroll() - 2;

			if ( prev ) {
				prev.disabled = atStart;
			}

			if ( next ) {
				next.disabled = atEnd;
			}
		}

		on( prev, 'click', function () {
			scrollBy( -1 );
		} );

		on( next, 'click', function () {
			scrollBy( 1 );
		} );

		on( track, 'scroll', function () {
			window.requestAnimationFrame( function () {
				syncDots();
				syncNav();
			} );
		}, { passive: true } );

		// Pointer dragging, so the rail feels the same on desktop and touch.
		var dragging = false;
		var startX = 0;
		var startScroll = 0;

		on( track, 'pointerdown', function ( event ) {
			if ( 'mouse' === event.pointerType && 0 !== event.button ) {
				return;
			}

			dragging = true;
			startX = event.clientX;
			startScroll = track.scrollLeft;
			track.classList.add( 'is-dragging' );
		} );

		on( track, 'pointermove', function ( event ) {
			if ( ! dragging ) {
				return;
			}

			var delta = event.clientX - startX;

			if ( Math.abs( delta ) > 4 ) {
				track.scrollLeft = startScroll - delta;
			}
		} );

		[ 'pointerup', 'pointercancel', 'pointerleave' ].forEach( function ( type ) {
			on( track, type, function () {
				dragging = false;
				track.classList.remove( 'is-dragging' );
			} );
		} );

		if ( autoplay > 0 ) {
			var play = function () {
				timer = window.setInterval( function () {
					if ( position() >= maxScroll() - 2 ) {
						track.scrollTo( { left: 0, behavior: 'smooth' } );
					} else {
						scrollBy( 1 );
					}
				}, autoplay );
			};

			var pause = function () {
				window.clearInterval( timer );
			};

			on( root, 'mouseenter', pause );
			on( root, 'mouseleave', play );
			on( root, 'focusin', pause );

			if ( ! window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
				play();
			}
		}

		buildDots();
		syncNav();

		on( window, 'resize', debounce( function () {
			buildDots();
			syncNav();
		}, 200 ) );
	}

	/* ---------------------------------------------------------------------
	 * Spotlight (amazing product)
	 * ------------------------------------------------------------------ */

	function initSpotlight( root ) {
		var slides = qsa( '[data-hx-slide]', root );
		var thumbs = qsa( '[data-hx-slide-to]', root );

		if ( slides.length < 2 ) {
			return;
		}

		var index = 0;
		var timer = null;

		function show( target ) {
			index = ( target + slides.length ) % slides.length;

			slides.forEach( function ( slide, position ) {
				var active = position === index;

				slide.classList.toggle( 'is-active', active );

				if ( active ) {
					slide.removeAttribute( 'aria-hidden' );
				} else {
					slide.setAttribute( 'aria-hidden', 'true' );
				}
			} );

			thumbs.forEach( function ( thumb, position ) {
				var active = position === index;

				thumb.classList.toggle( 'is-active', active );
				thumb.setAttribute( 'aria-selected', active ? 'true' : 'false' );
			} );
		}

		thumbs.forEach( function ( thumb, position ) {
			on( thumb, 'click', function () {
				show( position );
				restart();
			} );
		} );

		function restart() {
			window.clearInterval( timer );

			if ( window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
				return;
			}

			timer = window.setInterval( function () {
				show( index + 1 );
			}, 6000 );
		}

		on( root, 'mouseenter', function () {
			window.clearInterval( timer );
		} );

		on( root, 'mouseleave', restart );

		show( 0 );
		restart();
	}

	/* ---------------------------------------------------------------------
	 * Countdown
	 * ------------------------------------------------------------------ */

	function initCountdown( root ) {
		var deadline = parseInt( root.getAttribute( 'data-hx-countdown' ), 10 );

		if ( ! deadline ) {
			return;
		}

		var fields = {
			days: qs( '[data-hx-cd-days]', root ),
			hours: qs( '[data-hx-cd-hours]', root ),
			minutes: qs( '[data-hx-cd-minutes]', root ),
			seconds: qs( '[data-hx-cd-seconds]', root )
		};

		function pad( value ) {
			var padded = value < 10 ? '0' + value : String( value );

			return data.persianDigits ? toPersian( padded ) : padded;
		}

		function tick() {
			var remaining = deadline - Math.floor( Date.now() / 1000 );

			if ( remaining <= 0 ) {
				window.clearInterval( timer );
				root.classList.add( 'is-expired' );
				remaining = 0;
			}

			var days = Math.floor( remaining / 86400 );
			var hours = Math.floor( ( remaining % 86400 ) / 3600 );
			var minutes = Math.floor( ( remaining % 3600 ) / 60 );
			var seconds = remaining % 60;

			if ( fields.days ) {
				fields.days.textContent = pad( days );
			}

			if ( fields.hours ) {
				fields.hours.textContent = pad( hours );
			}

			if ( fields.minutes ) {
				fields.minutes.textContent = pad( minutes );
			}

			if ( fields.seconds ) {
				fields.seconds.textContent = pad( seconds );
			}
		}

		var timer = window.setInterval( tick, 1000 );

		tick();
	}

	/* ---------------------------------------------------------------------
	 * Tabs and accordions
	 * ------------------------------------------------------------------ */

	function initTabs( root ) {
		var tabs = qsa( '[data-hx-tab]', root );
		var panels = qsa( '[data-hx-panel]', root );

		if ( ! tabs.length ) {
			return;
		}

		function activate( name ) {
			tabs.forEach( function ( tab ) {
				var active = tab.getAttribute( 'data-hx-tab' ) === name;

				tab.classList.toggle( 'is-active', active );
				tab.setAttribute( 'aria-selected', active ? 'true' : 'false' );
			} );

			panels.forEach( function ( panel ) {
				var active = panel.getAttribute( 'data-hx-panel' ) === name;

				panel.classList.toggle( 'is-active', active );
				panel.hidden = ! active;
			} );
		}

		tabs.forEach( function ( tab ) {
			on( tab, 'click', function () {
				activate( tab.getAttribute( 'data-hx-tab' ) );
			} );
		} );

		activate( tabs[ 0 ].getAttribute( 'data-hx-tab' ) );
	}

	function initAccordion( root ) {
		qsa( '[data-hx-accordion-item]', root ).forEach( function ( item ) {
			var trigger = qs( '[data-hx-accordion-trigger]', item );
			var panel = qs( '[data-hx-accordion-panel]', item );

			if ( ! trigger || ! panel ) {
				return;
			}

			on( trigger, 'click', function () {
				var expanded = 'true' === trigger.getAttribute( 'aria-expanded' );

				if ( ! root.hasAttribute( 'data-hx-accordion-multi' ) ) {
					qsa( '[data-hx-accordion-item]', root ).forEach( function ( other ) {
						if ( other === item ) {
							return;
						}

						other.classList.remove( 'is-open' );

						var otherTrigger = qs( '[data-hx-accordion-trigger]', other );

						if ( otherTrigger ) {
							otherTrigger.setAttribute( 'aria-expanded', 'false' );
						}
					} );
				}

				trigger.setAttribute( 'aria-expanded', expanded ? 'false' : 'true' );
				item.classList.toggle( 'is-open', ! expanded );
			} );
		} );
	}

	/* ---------------------------------------------------------------------
	 * Scroll to top
	 * ------------------------------------------------------------------ */

	function initScrollTop() {
		var button = qs( '[data-hx-scroll-top]' );

		if ( ! button ) {
			return;
		}

		function update() {
			button.classList.toggle( 'is-visible', window.scrollY > 600 );
		}

		on( window, 'scroll', debounce( update, 120 ), { passive: true } );

		on( button, 'click', function () {
			window.scrollTo( {
				top: 0,
				behavior: window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ? 'auto' : 'smooth'
			} );
		} );

		update();
	}

	/* ---------------------------------------------------------------------
	 * Reveal on scroll
	 * ------------------------------------------------------------------ */

	function initReveal() {
		var targets = qsa( '.hx-section, .hx-product-card, .hx-blog-card' );

		if ( ! targets.length || ! ( 'IntersectionObserver' in window ) ) {
			return;
		}

		if ( window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
			return;
		}

		var observer = new IntersectionObserver( function ( entries ) {
			entries.forEach( function ( entry ) {
				if ( entry.isIntersecting ) {
					entry.target.classList.add( 'is-revealed' );
					observer.unobserve( entry.target );
				}
			} );
		}, { rootMargin: '0px 0px -8% 0px', threshold: 0.05 } );

		targets.forEach( function ( target ) {
			target.classList.add( 'hx-reveal' );
			observer.observe( target );
		} );
	}

	/* ---------------------------------------------------------------------
	 * Cart feedback
	 * ------------------------------------------------------------------ */

	function setCartCount( count ) {
		qsa( '[data-hx-cart-count]' ).forEach( function ( node ) {
			node.textContent = data.persianDigits ? toPersian( count ) : String( count );
			node.classList.toggle( 'is-empty', 0 === parseInt( count, 10 ) );
		} );
	}

	function initCart() {
		var jq = window.jQuery;
		var wooReady = 'undefined' !== typeof window.wc_add_to_cart_params;

		if ( jq ) {
			jq( document.body ).on( 'added_to_cart', function ( event, fragments, hash, button ) {
				toast( i18n.addedToCart || 'به سبد خرید اضافه شد', 'success' );

				if ( button && button.length ) {
					button.addClass( 'is-added' );
				}

				var counter = fragments && ( fragments[ 'span.hx-cart-count' ] || fragments[ '.hx-cart-count' ] );

				if ( counter ) {
					var parsed = jq( counter ).text().replace( /[^0-9۰-۹]/g, '' );

					if ( parsed ) {
						setCartCount( parsed );
					}
				}
			} );

			jq( document.body ).on( 'wc_cart_emptied', function () {
				setCartCount( 0 );
			} );
		}

		if ( wooReady ) {
			// WooCommerce's own add-to-cart script is loaded; let it do the work.
			return;
		}

		// It is not: post to the wc-ajax endpoint ourselves.
		on( document, 'click', function ( event ) {
			var button = event.target.closest ? event.target.closest( '.hx-add-to-cart.ajax_add_to_cart' ) : null;

			if ( ! button ) {
				return;
			}

			var productId = button.getAttribute( 'data-product_id' );

			if ( ! productId || ! data.ajaxUrl ) {
				return;
			}

			event.preventDefault();
			button.classList.add( 'is-loading' );

			var body = new window.FormData();

			body.append( 'product_id', productId );
			body.append( 'quantity', button.getAttribute( 'data-quantity' ) || 1 );

			window.fetch( data.homeUrl + '?wc-ajax=add_to_cart', {
				method: 'POST',
				credentials: 'same-origin',
				body: body
			} )
				.then( function ( response ) {
					return response.ok ? response.json() : Promise.reject( response );
				} )
				.then( function () {
					toast( i18n.addedToCart || 'به سبد خرید اضافه شد', 'success' );
					button.classList.add( 'is-added' );
				} )
				.catch( function () {
					toast( i18n.cartError || 'افزودن به سبد ناموفق بود', 'error' );
				} )
				.then( function () {
					button.classList.remove( 'is-loading' );
				} );
		} );
	}

	/* ---------------------------------------------------------------------
	 * Boot
	 * ------------------------------------------------------------------ */

	function ready( fn ) {
		if ( 'loading' !== document.readyState ) {
			fn();
		} else {
			document.addEventListener( 'DOMContentLoaded', fn );
		}
	}

	ready( function () {
		initHeader();
		initDrawer();
		initMegaMenu();
		initSearch();
		initScrollTop();
		initReveal();
		initCart();

		qsa( '[data-hx-carousel]' ).forEach( initCarousel );
		qsa( '[data-hx-spotlight]' ).forEach( initSpotlight );
		qsa( '[data-hx-countdown]' ).forEach( initCountdown );
		qsa( '[data-hx-tabs]' ).forEach( initTabs );
		qsa( '[data-hx-accordion]' ).forEach( initAccordion );

		convertDigits( document.body );
	} );

	// A tiny public surface, so the companion plugin's widgets can reuse the same
	// behaviour after an Elementor editor refresh.
	window.hooshinex = {
		toast: toast,
		convertDigits: convertDigits,
		initCarousel: initCarousel,
		initSpotlight: initSpotlight,
		initCountdown: initCountdown,
		initTabs: initTabs,
		initAccordion: initAccordion
	};
}() );
