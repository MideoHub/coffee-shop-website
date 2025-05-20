function toggleMenu() {
  const menu = document.querySelector(".menu");
  menu.style.display = menu.style.display === "block" ? "none" : "block";
}

window.onclick = function (event) {
  if (!event.target.matches(".icon")) {
    const menu = document.querySelector(".menu");
    if (menu.style.display === "block") {
      menu.style.display = "none";
    }
  }
}

document.addEventListener("DOMContentLoaded", function () {
  // Booking Form
  const bookingForm = document.getElementById("bookingForm");
  if (bookingForm) {
    bookingForm.addEventListener("submit", async function (e) {
      e.preventDefault();

      const token = localStorage.getItem("token");
      if (!token) {
        alert("Please login to book a table");
        window.location.href = "../Login/index.html";
        return;
      }

      const name = bookingForm.querySelector('input[name="name"]').value;
      const email = bookingForm.querySelector('input[name="email"]').value;
      const date = bookingForm.querySelector('input[name="date"]').value;
      const time = bookingForm.querySelector('input[name="time"]').value;
      const persons = bookingForm.querySelector('select[name="persons"]').value;

      try {
        const response = await fetch("http://localhost:8000/api/bookings", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${token}`,
          },
          body: JSON.stringify({ name, email, date, time, persons }),
        });

        const data = await response.json();
        if (response.ok) {
          alert("Booking successful!");
          bookingForm.reset();
        } else {
          alert(data.message || "Booking failed");
        }
      } catch (error) {
        alert("Error: " + error.message);
      }
    });
  }

  // Login Form
  const loginForm = document.getElementById("loginForm");
  if (loginForm) {
    loginForm.addEventListener("submit", async function (e) {
      e.preventDefault();

      const email = loginForm.querySelector('input[name="email"]').value;
      const password = loginForm.querySelector('input[name="password"]').value;

      try {
        const response = await fetch("http://localhost:8000/api/auth/login", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ email, password }),
        });

        const data = await response.json();
        if (response.ok) {
          localStorage.setItem("token", data.token);
          alert("Login successful!");
          window.location.href = "../HOME/index.html";
        } else {
          alert(data.message || "Login failed");
        }
      } catch (error) {
        alert("Error: " + error.message);
      }
    });
  }

  // Signup Form
  const signupForm = document.getElementById("signupForm");
  if (signupForm) {
    signupForm.addEventListener("submit", async function (e) {
      e.preventDefault();

      const name = signupForm.querySelector('input[name="name"]').value;
      const email = signupForm.querySelector('input[name="email"]').value;
      const password = signupForm.querySelector('input[name="password"]').value;

      try {
        const response = await fetch("http://localhost:8000/api/auth/signup", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ name, email, password }),
        });

        const data = await response.json();
        if (response.ok) {
          localStorage.setItem("token", data.token);
          alert("Signup successful!");
          window.location.href = "../HOME/index.html";
        } else {
          alert(data.message || "Signup failed");
        }
      } catch (error) {
        alert("Error: " + error.message);
      }
    });
  }

  // Menu and Products Modal
  const overlay = document.getElementById("overlay");
  const closeBtn = document.getElementById("closeBtn");
  const viewBtns = document.querySelectorAll(".box-btn.view-btn");

  if (overlay && closeBtn && viewBtns) {
    viewBtns.forEach((btn) => {
      btn.addEventListener("click", function (e) {
        e.preventDefault();

        const name = this.getAttribute("data-name");
        const description = this.getAttribute("data-description");
        const image = this.getAttribute("data-image");
        const nutritionData = this.getAttribute("data-nutrition").split("|");

        document.getElementById("modalTitle").innerText = name;
        document.getElementById("modalDescription").innerText = description;
        document.getElementById("modalImage").src = image;

        const modalNutrition = document.getElementById("modalNutrition");
        modalNutrition.innerHTML = "";
        nutritionData.forEach((info) => {
          const p = document.createElement("p");
          p.innerText = info;
          modalNutrition.appendChild(p);
        });

        overlay.style.display = "flex";
      });
    });

    closeBtn.addEventListener("click", function () {
      overlay.style.display = "none";
    });

    window.addEventListener("click", function (e) {
      if (e.target == overlay) {
        overlay.style.display = "none";
      }
    });
  }

  // Cart Functionality
  let cart = JSON.parse(localStorage.getItem("cart")) || [];

  const cartBtns = document.querySelectorAll(".cart-btn");
  cartBtns.forEach((btn) => {
    btn.addEventListener("click", async function (e) {
      e.preventDefault();
      const token = localStorage.getItem("token");
      if (!token) {
        alert("Please login to add items to cart");
        window.location.href = "../Login/index.html";
        return;
      }

      const name = this.getAttribute("data-name");
      const price = parseFloat(
        this.parentElement.nextElementSibling.querySelector("p").textContent.replace("$", "")
      );
      const image = this.getAttribute("data-image");

      const item = { name, price, image, quantity: 1 };
      const existingItem = cart.find((cartItem) => cartItem.name === name);

      if (existingItem) {
        existingItem.quantity += 1;
      } else {
        cart.push(item);
      }

      localStorage.setItem("cart", JSON.stringify(cart));

      try {
        await fetch("http://localhost:8000/api/cart", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${token}`,
          },
          body: JSON.stringify(item),
        });
      } catch (error) {
        console.error("Error syncing cart with server:", error);
      }

      alert(`${name} added to cart!`);
    });
  });

  // Cart Page
  const cartContainer = document.querySelector(".cart-container");
  if (cartContainer) {
    function renderCart() {
      cartContainer.innerHTML = "";
      let total = 0;

      cart.forEach((item, index) => {
        total += item.price * item.quantity;
        const cartItem = document.createElement("div");
        cartItem.classList.add("cart-item");
        cartItem.innerHTML = `
          <img src="${item.image}" alt="${item.name}" />
          <div class="cart-item-details">
            <h3>${item.name}</h3>
            <p>$${item.price.toFixed(2)} x ${item.quantity}</p>
          </div>
          <button onclick="removeFromCart(${index})">Remove</button>
        `;
        cartContainer.appendChild(cartItem);
      });

      const totalDiv = document.createElement("div");
      totalDiv.classList.add("cart-total");
      totalDiv.innerHTML = `
        <p>Total: $${total.toFixed(2)}</p>
        <a href="#" class="checkout-btn">Checkout</a>
      `;
      cartContainer.appendChild(totalDiv);
    }

    window.removeFromCart = function (index) {
      cart.splice(index, 1);
      localStorage.setItem("cart", JSON.stringify(cart));
      renderCart();

      const token = localStorage.getItem("token");
      if (token) {
        fetch("http://localhost:8000/api/cart", {
          method: "DELETE",
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }).catch((error) => console.error("Error syncing cart with server:", error));
      }
    };

    renderCart();

    const checkoutBtn = document.querySelector(".checkout-btn");
    if (checkoutBtn) {
      checkoutBtn.addEventListener("click", async function (e) {
        e.preventDefault();
        const token = localStorage.getItem("token");
        if (!token) {
          alert("Please login to checkout");
          window.location.href = "../Login/index.html";
          return;
        }

        try {
          const response = await fetch("http://localhost:8000/api/cart/checkout", {
            method: "POST",
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });

          const data = await response.json();
          if (response.ok) {
            alert("Checkout successful!");
            cart = [];
            localStorage.setItem("cart", JSON.stringify(cart));
            renderCart();
          } else {
            alert(data.message || "Checkout failed");
          }
        } catch (error) {
          alert("Error: " + error.message);
        }
      });
    }
  }
});