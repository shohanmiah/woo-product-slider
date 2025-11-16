# Custom Product Grid Slider

A powerful WordPress plugin that displays WooCommerce products in a beautiful grid or slider format for Elementor.

## Version
**2.0.0**

## Description
Custom Product Grid Slider is a feature-rich WordPress plugin that seamlessly integrates with Elementor and WooCommerce to create stunning product displays. Whether you want a traditional grid layout or an interactive slider with smooth scrolling, this plugin has you covered.

## Features

### 🎯 Core Features
- **Elementor Integration**: Native Elementor widget with live editing
- **WooCommerce Compatible**: Full integration with WooCommerce products
- **Grid & Slider Modes**: Switch between grid and slider layouts
- **Responsive Design**: Optimized for desktop, tablet, and mobile devices
- **AJAX Add to Cart**: Add products to cart without page reload
- **Wishlist Support**: Built-in wishlist with localStorage persistence

### 🎨 Product Query Options
- Recent Products
- Featured Products
- Sale Products
- Best Selling Products
- Top Rated Products
- Category Filtering
- Custom Order By (Date, Title, Price, Popularity, Rating, Random)
- Ascending/Descending Order

### 📐 Layout Settings
- **Responsive Columns**: Different column counts for desktop, tablet, and mobile
- **Gap Controls**: Adjustable column and row spacing
- **Equal Height**: Products maintain consistent height

### 🎢 Slider Features
- **Desktop/Mobile Toggle**: Enable/disable slider per device
- **Peek Percentage**: Show partial view of next items
- **Mouse Wheel Scrolling**: Horizontal scroll with mouse wheel
- **Drag to Scroll**: Click and drag navigation
- **Navigation Arrows**: Previous/Next buttons with auto-disable at ends
- **Scroll Snap**: Smooth snap-to-item scrolling

### 👁️ Element Visibility
- Product Image
- Product Title
- Product Price
- Star Ratings
- Sale Badge
- Add to Cart Button
- Wishlist Button

### 🎭 Styling Options

#### Item Hover Effects
- None
- Lift
- Scale
- Lift + Scale
- Float (animated)

#### Image Hover Effects
- None
- Zoom In
- Zoom Out
- Rotate
- Blur
- Grayscale to Color
- Opacity

#### Typography & Colors
- **Title**: Typography, colors, word limit, hover color
- **Price**: Typography, regular and sale price colors
- **Rating**: Star color, alignment options
- **Buttons**: Typography, colors (normal/hover), border radius, padding
- **Wishlist**: Position (4 corners), icon colors (normal/active), background
- **Navigation Arrows**: Size, colors (normal/hover), background
- **Sale Badge**: Background, text color, typography

## Requirements

### Minimum Requirements
- WordPress 5.0 or higher
- PHP 7.0 or higher
- Elementor (free version)
- WooCommerce 3.0 or higher

### Recommended
- WordPress 6.0+
- PHP 8.0+
- Elementor Pro (for additional features)
- WooCommerce 7.0+

## Installation

1. Download the plugin files
2. Upload to `/wp-content/plugins/custom-product-grid-slider/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Ensure Elementor and WooCommerce are installed and activated
5. Find the "Product Grid Slider" widget in Elementor's widget panel

## Usage

### Basic Setup
1. Edit a page with Elementor
2. Search for "Product Grid Slider" widget
3. Drag and drop the widget onto your page
4. Configure product query settings
5. Customize layout and styling options
6. Publish your page

### Creating a Product Slider
1. Add the widget to your page
2. Go to **Content > Slider Settings**
3. Enable "Slider on Desktop" or "Slider on Mobile"
4. Adjust peek percentage (0-50%)
5. Enable mouse wheel and drag scroll as needed
6. Show/hide navigation arrows

### Styling Products
1. Switch to the **Style** tab
2. Choose your preferred item hover effect
3. Select an image hover effect
4. Customize title, price, and button styles
5. Configure wishlist button position and colors
6. Adjust navigation arrow appearance

## File Structure

```
custom-product-grid-slider/
├── assets/
│   ├── css/
│   │   └── style.css          # Complete grid/slider styles
│   └── js/
│       └── script.js          # JavaScript functionality
├── widgets/
│   └── product-grid-widget.php # Elementor widget class
├── custom-product-grid-slider.php # Main plugin file
└── README.md                   # Documentation
```

## Technical Details

### Main Plugin File
- **Version**: 2.0.0
- **Dependency Checks**: Validates Elementor and WooCommerce
- **Admin Notices**: Displays warnings for missing dependencies
- **AJAX Handlers**: `cpg_add_to_cart` and `cpg_toggle_wishlist`
- **Enqueued Assets**: CSS and JS with version control

### Elementor Widget
- **Name**: `custom_product_grid`
- **Category**: General
- **Controls**: 50+ customization options
- **Query System**: WP_Query with WooCommerce integration
- **Helper Functions**: Title limiting, category fetching

### CSS Architecture
- **Grid System**: CSS Grid with flexbox fallbacks
- **Scroll Snap**: Native browser scroll snapping
- **Animations**: Pure CSS hover effects
- **Responsive**: Mobile-first approach with breakpoints
- **Equal Heights**: CSS Grid auto-stretch

### JavaScript Features
- **Object Pattern**: CustomProductGrid singleton
- **jQuery**: Built on jQuery for compatibility
- **AJAX**: WordPress AJAX API integration
- **LocalStorage**: Wishlist persistence
- **Debounce**: Performance optimization for resize
- **Event Triggers**: Custom events for extensibility

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Hooks & Filters

### JavaScript Events
```javascript
// Product added to cart
$(document).on('cpg_product_added_to_cart', function(event, productId, quantity) {
    // Your code here
});

// Wishlist updated
$(document).on('cpg_wishlist_updated', function(event, productId, action) {
    // Your code here
});
```

### AJAX Actions
- `wp_ajax_cpg_add_to_cart` - Add product to cart
- `wp_ajax_nopriv_cpg_add_to_cart` - Add product to cart (non-logged-in)
- `wp_ajax_cpg_toggle_wishlist` - Toggle wishlist item
- `wp_ajax_nopriv_cpg_toggle_wishlist` - Toggle wishlist (non-logged-in)

## Security

- **Nonce Verification**: All AJAX requests use WordPress nonces
- **Data Sanitization**: User input is sanitized using WordPress functions
- **Output Escaping**: All output is escaped appropriately
- **No SQL Injection**: Uses WordPress WP_Query and safe functions
- **CodeQL Verified**: Passed security scanning with 0 alerts

## Performance

- **Optimized CSS**: Minimal, production-ready styles
- **Efficient JavaScript**: Debounced events, optimized DOM manipulation
- **Lazy Loading**: Compatible with image lazy loading
- **Caching**: Works with WordPress caching plugins
- **No External Dependencies**: Pure CSS/JS, no third-party libraries

## Troubleshooting

### Widget Not Appearing
- Ensure Elementor is installed and activated
- Ensure WooCommerce is installed and activated
- Clear Elementor cache (Elementor > Tools > Regenerate CSS)

### Products Not Showing
- Check if products are published
- Verify category selections
- Ensure WooCommerce is properly configured

### Slider Not Working
- Enable slider mode in settings
- Check browser console for JavaScript errors
- Ensure jQuery is loaded

### Add to Cart Not Working
- Verify WooCommerce is active
- Check browser console for AJAX errors
- Ensure products are in stock

## Changelog

### Version 2.0.0 (Current)
- Complete rewrite of plugin architecture
- Added Elementor integration
- Added comprehensive styling options
- Implemented AJAX add to cart
- Added wishlist functionality with localStorage
- Implemented slider with peek percentage
- Added multiple hover effects
- Added responsive controls
- Enhanced security and sanitization
- Production-ready release

### Version 1.0.0
- Initial basic release
- Simple shortcode functionality

## Credits

**Author**: Shohan Miah  
**Author URI**: https://example.com  
**License**: GPL v2 or later  
**License URI**: https://www.gnu.org/licenses/gpl-2.0.html

## Support

For support, feature requests, or bug reports, please create an issue in the repository.

## Contributing

Contributions are welcome! Please follow WordPress coding standards and test thoroughly before submitting pull requests.

---

Made with ❤️ for the WordPress community
