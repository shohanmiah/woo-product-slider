// script.js

// This script is for managing product slider functionality.

// Initialize an array for products
const products = [];

// Function to add a product to the slider
function addProduct(product) {
    products.push(product);
    updateSlider();
}

// Function to update the slider DOM
function updateSlider() {
    const sliderContainer = document.getElementById('product-slider');
    sliderContainer.innerHTML = '';
    products.forEach(product => {
        const productEl = document.createElement('div');
        productEl.className = 'product-item';
        productEl.innerText = product.name;
        sliderContainer.appendChild(productEl);
    });
}

// Example usage
addProduct({ name: 'Product 1' });
addProduct({ name: 'Product 2' });
addProduct({ name: 'Product 3' });