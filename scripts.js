
let cart = [];

function filterProducts(category) {
    const products = document.querySelectorAll('.product');

    products.forEach(product => {
        if (category === 'todos') {
            product.style.display = 'block';
        } else if (product.getAttribute('data-category') === category) {
            product.style.display = 'block';
        } else {
            product.style.display = 'none';
        }
    });
}

function addToCart(productName, productPrice) {
    const cartItem = cart.find(item => item.name === productName);

    if (cartItem) {
        cartItem.quantity += 1;
    } else {
        cart.push({ name: productName, price: productPrice, quantity: 1 });
    }

    updateCart();
}

function updateCart() {
    const cartItemsContainer = document.getElementById('cart-items');
    cartItemsContainer.innerHTML = '';

    let total = 0;

    cart.forEach(item => {
        const cartItemElement = document.createElement('li');
        cartItemElement.textContent = `${item.name} - $${item.price.toFixed(2)} x ${item.quantity}`;
        cartItemsContainer.appendChild(cartItemElement);

        total += item.price * item.quantity;
    });

    document.getElementById('cart-total').textContent = total.toFixed(2);
}
