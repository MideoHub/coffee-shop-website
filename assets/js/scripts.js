function toggleMenu() {
    const menu = document.querySelector('.menu');
    menu.classList.toggle('active');
}

// Booking Form Submission
document.getElementById('bookingForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('index.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error('Network response was not ok: ' + text);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Booking submitted successfully!');
            this.reset();
        } else {
            alert(data.message || 'Booking failed. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Booking failed. Please try again. Check the console for details.');
    });
});

// Login Form Submission
document.getElementById('loginForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('index.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error('Network response was not ok: ' + text);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            if (data.role === 'admin') {
                window.location.href = '/coffee-shop-website/Admin/admin.php';
            } else {
                window.location.href = '/coffee-shop-website/User/user.php';
            }
        } else {
            alert(data.message || 'Login failed. Please try again.');
            console.log('Login response:', data);
        }
    })
    .catch(error => {
        console.error('Error during login:', error);
        alert('Login failed. Please try again. Check the console for details.');
    });
});

// Signup Form Submission
document.getElementById('signupForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('index.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = '../Login/index.php';
        } else {
            alert(data.message || 'Signup failed. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Signup failed. Please try again.');
    });
});

// Cart Functionality - Add to Cart
const cartButtons = document.querySelectorAll('.cart-btn');
cartButtons.forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const name = this.dataset.name;
        const image = this.dataset.image;
        let price = 0;

        const box = this.closest('.box');
        const coffeeItem = box?.querySelector('.coffee-item');
        const priceElement = coffeeItem?.querySelector('p');

        if (priceElement) {
            price = parseFloat(priceElement.textContent.replace('$', '')) || 0;
            console.log(`Price extracted for ${name}: ${price}`);
        } else {
            console.warn(`Price element not found for ${name}`);
        }

        fetch('/coffee-shop-website/Cart/add_to_cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name, image, price })
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error('Network response was not ok: ' + text);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(`${name} added to cart!`);
            } else {
                alert(data.message || 'Failed to add to cart.');
            }
        })
        .catch(error => {
            console.error('Error adding to cart:', error);
            alert('Failed to add to cart. Check the console for details.');
        });
    });
});

// Cart Functionality - Delete from Cart
const deleteButtons = document.querySelectorAll('.delete-btn');
deleteButtons.forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const row = this.closest('tr');
        const cartItemId = row.dataset.id;

        fetch('/coffee-shop-website/Cart/remove_from_cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: cartItemId })
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error('Network response was not ok: ' + text);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                row.remove(); // Remove the row from the table
                if (!document.querySelector('.delete-btn')) {
                    // If no items left, display empty cart message
                    const cartContainer = document.querySelector('.cart-container');
                    cartContainer.innerHTML = '<h1>Your Cart</h1><p>Your cart is empty.</p>';
                }
            } else {
                alert(data.message || 'Failed to delete item.');
            }
        })
        .catch(error => {
            console.error('Error deleting from cart:', error);
            alert('Failed to delete item. Check the console for details.');
        });
    });
});

// Modal Functionality
const viewButtons = document.querySelectorAll('.view-btn');
const overlay = document.getElementById('overlay');
const modalTitle = document.getElementById('modalTitle');
const modalDescription = document.getElementById('modalDescription');
const modalImage = document.getElementById('modalImage');
const modalNutrition = document.getElementById('modalNutrition');
const closeBtn = document.getElementById('closeBtn');

viewButtons.forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        modalTitle.textContent = this.dataset.name;
        modalDescription.textContent = this.dataset.description;
        modalImage.src = this.dataset.image;
        modalNutrition.textContent = this.dataset.nutrition;
        overlay.style.display = 'flex';
    });
});

closeBtn?.addEventListener('click', () => {
    overlay.style.display = 'none';
});