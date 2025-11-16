/**
 * Custom Product Grid Slider Script
 * Version: 2.0.0
 */

(function($) {
	'use strict';

	/**
	 * Custom Product Grid Object
	 */
	const CustomProductGrid = {
		/**
		 * Initialize
		 */
		init: function() {
			this.bindEvents();
			this.initializeGrids();
		},

		/**
		 * Bind events
		 */
		bindEvents: function() {
			const self = this;

			// Initialize on document ready
			$(document).ready(function() {
				self.initializeGrids();
			});

			// Re-initialize on window resize with debounce
			let resizeTimer;
			$(window).on('resize', function() {
				clearTimeout(resizeTimer);
				resizeTimer = setTimeout(function() {
					self.initializeGrids();
				}, 250);
			});

			// Elementor frontend init
			$(window).on('elementor/frontend/init', function() {
				elementorFrontend.hooks.addAction('frontend/element_ready/custom_product_grid.default', function($scope) {
					self.initializeGrid($scope.find('.cpg-products-wrapper'));
				});
			});
		},

		/**
		 * Initialize all grids on page
		 */
		initializeGrids: function() {
			const self = this;
			$('.cpg-products-wrapper').each(function() {
				self.initializeGrid($(this));
			});
		},

		/**
		 * Initialize single grid
		 */
		initializeGrid: function($wrapper) {
			if ($wrapper.length === 0) return;

			this.handleSlider($wrapper);
			this.handleNavigationArrows($wrapper);
			this.handleMouseWheel($wrapper);
			this.handleDragScroll($wrapper);
			this.handleAddToCart($wrapper);
			this.handleWishlist($wrapper);
			this.loadWishlistFromStorage($wrapper);
		},

		/**
		 * Handle slider functionality
		 */
		handleSlider: function($wrapper) {
			const $grid = $wrapper.find('.cpg-products-grid');
			if ($grid.length === 0) return;

			const isDesktopSlider = $wrapper.data('slider-desktop') === 'yes';
			const isMobileSlider = $wrapper.data('slider-mobile') === 'yes';
			const peekPercentage = parseInt($wrapper.data('peek-percentage')) || 10;
			const columns = parseInt($wrapper.data('columns')) || 4;
			const columnsTablet = parseInt($wrapper.data('columns-tablet')) || 3;
			const columnsMobile = parseInt($wrapper.data('columns-mobile')) || 2;

			const windowWidth = $(window).width();
			let isSlider = false;
			let activeColumns = columns;

			// Determine if slider should be active based on viewport
			if (windowWidth >= 1025) {
				isSlider = isDesktopSlider;
				activeColumns = columns;
			} else if (windowWidth >= 768) {
				isSlider = isMobileSlider;
				activeColumns = columnsTablet;
			} else {
				isSlider = isMobileSlider;
				activeColumns = columnsMobile;
			}

			if (isSlider) {
				const $items = $grid.find('.cpg-product-item');
				if ($items.length === 0) return;

				const containerWidth = $grid.width();
				const gap = parseFloat($grid.css('column-gap')) || 20;
				const peekAmount = (containerWidth * peekPercentage) / 100;
				const itemWidth = (containerWidth - (gap * (activeColumns - 1)) - peekAmount) / activeColumns;

				$items.css('min-width', itemWidth + 'px');
				$grid.css('grid-auto-columns', itemWidth + 'px');
			} else {
				// Reset for grid mode
				$grid.find('.cpg-product-item').css('min-width', '');
				$grid.css('grid-auto-columns', '');
			}
		},

		/**
		 * Handle navigation arrows
		 */
		handleNavigationArrows: function($wrapper) {
			const $grid = $wrapper.find('.cpg-products-grid');
			const $prevArrow = $wrapper.find('.cpg-nav-prev');
			const $nextArrow = $wrapper.find('.cpg-nav-next');

			if ($grid.length === 0 || $prevArrow.length === 0 || $nextArrow.length === 0) return;

			const self = this;

			// Update arrow states
			const updateArrowStates = function() {
				const scrollLeft = $grid.scrollLeft();
				const maxScroll = $grid[0].scrollWidth - $grid[0].clientWidth;

				$prevArrow.prop('disabled', scrollLeft <= 0);
				$nextArrow.prop('disabled', scrollLeft >= maxScroll - 1);
			};

			// Initial state
			updateArrowStates();

			// Update on scroll
			$grid.on('scroll', self.debounce(updateArrowStates, 50));

			// Previous arrow click
			$prevArrow.off('click').on('click', function() {
				const scrollAmount = $grid.width();
				$grid.animate({
					scrollLeft: $grid.scrollLeft() - scrollAmount
				}, 400, 'swing', updateArrowStates);
			});

			// Next arrow click
			$nextArrow.off('click').on('click', function() {
				const scrollAmount = $grid.width();
				$grid.animate({
					scrollLeft: $grid.scrollLeft() + scrollAmount
				}, 400, 'swing', updateArrowStates);
			});
		},

		/**
		 * Handle mouse wheel scrolling
		 */
		handleMouseWheel: function($wrapper) {
			const $grid = $wrapper.find('.cpg-products-grid');
			const enableMouseWheel = $wrapper.data('mouse-wheel') === 'yes';

			if (!enableMouseWheel || $grid.length === 0) return;

			$grid.off('wheel').on('wheel', function(e) {
				// Only handle horizontal scrolling if slider is active
				if ($grid.hasClass('cpg-slider-desktop') || $grid.hasClass('cpg-slider-mobile')) {
					if (e.originalEvent.deltaY !== 0) {
						e.preventDefault();
						this.scrollLeft += e.originalEvent.deltaY;
					}
				}
			});
		},

		/**
		 * Handle drag scrolling
		 */
		handleDragScroll: function($wrapper) {
			const $grid = $wrapper.find('.cpg-products-grid');
			const enableDragScroll = $wrapper.data('drag-scroll') === 'yes';

			if (!enableDragScroll || $grid.length === 0) return;

			let isDown = false;
			let startX;
			let scrollLeft;

			$grid.off('mousedown mouseleave mouseup mousemove');

			$grid.on('mousedown', function(e) {
				// Only enable drag if slider is active
				if (!$grid.hasClass('cpg-slider-desktop') && !$grid.hasClass('cpg-slider-mobile')) return;

				isDown = true;
				$grid.addClass('dragging');
				startX = e.pageX - $grid.offset().left;
				scrollLeft = $grid.scrollLeft();
			});

			$grid.on('mouseleave', function() {
				isDown = false;
				$grid.removeClass('dragging');
			});

			$grid.on('mouseup', function() {
				isDown = false;
				$grid.removeClass('dragging');
			});

			$grid.on('mousemove', function(e) {
				if (!isDown) return;
				e.preventDefault();
				const x = e.pageX - $grid.offset().left;
				const walk = (x - startX) * 2; // Scroll speed multiplier
				$grid.scrollLeft(scrollLeft - walk);
			});
		},

		/**
		 * Handle add to cart
		 */
		handleAddToCart: function($wrapper) {
			const self = this;

			$wrapper.off('click', '.cpg-add-to-cart').on('click', '.cpg-add-to-cart', function(e) {
				e.preventDefault();

				const $button = $(this);
				const productId = $button.data('product-id');
				const quantity = $button.data('quantity') || 1;

				// Prevent double clicks
				if ($button.hasClass('loading') || $button.hasClass('added')) return;

				// Set loading state
				$button.addClass('loading');

				// AJAX request
				$.ajax({
					url: cpgAjax.ajaxurl,
					type: 'POST',
					data: {
						action: 'cpg_add_to_cart',
						nonce: cpgAjax.nonce,
						product_id: productId,
						quantity: quantity
					},
					success: function(response) {
						$button.removeClass('loading');

						if (response.success) {
							// Set added state
							$button.addClass('added');
							$button.find('.button-text').text('Added!');

							// Trigger WooCommerce events
							$(document.body).trigger('added_to_cart', [response.data.fragments, response.data.cart_hash, $button]);

							// Custom event
							$(document).trigger('cpg_product_added_to_cart', [productId, quantity]);

							// Reset after 2 seconds
							setTimeout(function() {
								$button.removeClass('added');
								$button.find('.button-text').text('Add to Cart');
							}, 2000);
						} else {
							console.error('Add to cart failed:', response.data.message);
						}
					},
					error: function(xhr, status, error) {
						$button.removeClass('loading');
						console.error('AJAX error:', error);
					}
				});
			});
		},

		/**
		 * Handle wishlist
		 */
		handleWishlist: function($wrapper) {
			const self = this;

			$wrapper.off('click', '.cpg-wishlist-btn').on('click', '.cpg-wishlist-btn', function(e) {
				e.preventDefault();
				e.stopPropagation();

				const $button = $(this);
				const productId = $button.data('product-id');
				const isActive = $button.hasClass('active');
				const action = isActive ? 'remove' : 'add';

				// Toggle active state immediately for better UX
				$button.toggleClass('active');

				// Update localStorage
				self.updateWishlistStorage(productId, action);

				// AJAX request
				$.ajax({
					url: cpgAjax.ajaxurl,
					type: 'POST',
					data: {
						action: 'cpg_toggle_wishlist',
						nonce: cpgAjax.nonce,
						product_id: productId,
						wishlist_action: action
					},
					success: function(response) {
						if (response.success) {
							// Custom event
							$(document).trigger('cpg_wishlist_updated', [productId, action]);
						} else {
							// Revert on error
							$button.toggleClass('active');
							self.updateWishlistStorage(productId, isActive ? 'add' : 'remove');
							console.error('Wishlist toggle failed:', response.data.message);
						}
					},
					error: function(xhr, status, error) {
						// Revert on error
						$button.toggleClass('active');
						self.updateWishlistStorage(productId, isActive ? 'add' : 'remove');
						console.error('AJAX error:', error);
					}
				});
			});
		},

		/**
		 * Update wishlist in localStorage
		 */
		updateWishlistStorage: function(productId, action) {
			let wishlist = this.getWishlistFromStorage();

			if (action === 'add') {
				if (!wishlist.includes(productId)) {
					wishlist.push(productId);
				}
			} else {
				wishlist = wishlist.filter(id => id !== productId);
			}

			localStorage.setItem('cpg_wishlist', JSON.stringify(wishlist));
		},

		/**
		 * Get wishlist from localStorage
		 */
		getWishlistFromStorage: function() {
			const wishlist = localStorage.getItem('cpg_wishlist');
			return wishlist ? JSON.parse(wishlist) : [];
		},

		/**
		 * Load wishlist state from localStorage
		 */
		loadWishlistFromStorage: function($wrapper) {
			const wishlist = this.getWishlistFromStorage();

			$wrapper.find('.cpg-wishlist-btn').each(function() {
				const $button = $(this);
				const productId = $button.data('product-id');

				if (wishlist.includes(productId)) {
					$button.addClass('active');
				}
			});
		},

		/**
		 * Debounce utility
		 */
		debounce: function(func, wait) {
			let timeout;
			return function executedFunction(...args) {
				const later = () => {
					clearTimeout(timeout);
					func(...args);
				};
				clearTimeout(timeout);
				timeout = setTimeout(later, wait);
			};
		}
	};

	// Initialize
	CustomProductGrid.init();

	// Expose to global scope for external access
	window.CustomProductGrid = CustomProductGrid;

})(jQuery);