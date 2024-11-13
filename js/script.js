document.addEventListener('DOMContentLoaded', () => {
    const searchIcon = document.querySelector('.search-icon');
    const searchBar = document.getElementById('searchBar');
    const cartIcon = document.querySelector('.cart-icon');
    const cartContainer = document.getElementById('cartContainer');
    const closeCart = document.getElementById('closeCart');
    const filterHeaders = document.querySelectorAll('.filter-header');
    const genderFilters = document.querySelectorAll('.gender-filter');
    const brandFilters = document.querySelectorAll('.brand-filter');
    const categoryFilters = document.querySelectorAll('.category-filter');
    const colorFilters = document.querySelectorAll('.color-filter');
    const productCards = document.querySelectorAll('.product-card');
    const addToCartButtons = document.querySelectorAll('.add-to-cart'); // Botones de agregar al carrito
    const clearCartButton = document.getElementById('clearCart'); // Botón de limpiar carrito
    const cartItemsList = document.getElementById('cartItemsList');
    const emptyCartMessage = document.getElementById('emptyCartMessage');
    const continuePurchaseButton = document.getElementById('continuePurchase');

    // Recuperar el carrito de localStorage
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];

    // Mostrar el carrito al hacer clic en el ícono de carrito
    cartIcon.addEventListener('click', () => {
        cartContainer.classList.add('show');
        actualizarCarrito();
    });

    // Cerrar el carrito
    closeCart.addEventListener('click', () => {
        cartContainer.classList.remove('show');
    });

    // Mostrar/ocultar filtros al hacer clic en los encabezados
    filterHeaders.forEach(header => {
        header.addEventListener('click', () => {
            const arrow = header.querySelector('.arrow');
            const options = header.nextElementSibling;

            if (options.style.display === 'block') {
                options.style.display = 'none';
                arrow.style.transform = 'rotate(0deg)';
            } else {
                options.style.display = 'block';
                arrow.style.transform = 'rotate(90deg)';
            }
        });
    });

    // Filtrar productos según los filtros seleccionados
    genderFilters.forEach(filter => {
        filter.addEventListener('change', () => {
            filterProducts();
        });
    });

    brandFilters.forEach(filter => {
        filter.addEventListener('change', () => {
            filterProducts();
        });
    });

    categoryFilters.forEach(filter => {
        filter.addEventListener('change', () => {
            filterProducts();
        });
    });

    colorFilters.forEach(filter => {
        filter.addEventListener('change', () => {
            filterProducts();
        });
    });

    // Función para filtrar productos según los filtros seleccionados
    function filterProducts() {
        const selectedGender = getSelectedValues(genderFilters);
        const selectedBrands = getSelectedValues(brandFilters);
        const selectedCategories = getSelectedValues(categoryFilters);
        const selectedColors = getSelectedValues(colorFilters);

        productCards.forEach(card => {
            const productGender = card.getAttribute('data-gender');
            const productBrand = card.getAttribute('data-brand');
            const productCategory = card.getAttribute('data-category');
            const productColor = card.getAttribute('data-color'); // Asegúrate de tener este atributo

            let genderMatch = !selectedGender.length || selectedGender.includes(productGender);
            let brandMatch = !selectedBrands.length || selectedBrands.includes(productBrand);
            let categoryMatch = !selectedCategories.length || selectedCategories.includes(productCategory);
            let colorMatch = !selectedColors.length || selectedColors.includes(productColor);

            // Mostrar u ocultar el producto según si cumple con los filtros
            if (genderMatch && brandMatch && categoryMatch && colorMatch) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Función para obtener los valores seleccionados de los filtros
    function getSelectedValues(filters) {
        const selectedValues = [];
        filters.forEach(filter => {
            if (filter.checked) {
                selectedValues.push(filter.value);
            }
        });
        return selectedValues;
    }

    // Agregar producto al carrito
    addToCartButtons.forEach((button) => {
        button.addEventListener('click', (e) => {
            const productCard = e.target.closest('.product-card');
            const productName = productCard.querySelector('.product-name').textContent;
            const productPrice = productCard.querySelector('.product-price').textContent.replace('$', '').replace('.', '').trim();
            const productImage = productCard.querySelector('img').src;
            const productId = productCard.getAttribute('data-id');

            const product = {
                id: productId,
                name: productName,
                price: parseFloat(productPrice),
                image: productImage,
                quantity: 1
            };

            const existingProduct = carrito.find(item => item.id === product.id);
            if (existingProduct) {
                existingProduct.quantity += 1;
            } else {
                carrito.push(product);
            }

            // Guardar el carrito en localStorage
            localStorage.setItem('carrito', JSON.stringify(carrito));
            actualizarCarrito();
        });
    });

    // Función para actualizar el carrito
    function actualizarCarrito() {
        if (carrito.length > 0) {
            cartItemsList.innerHTML = ''; // Limpiar la lista antes de agregar los productos
            carrito.forEach(item => {
                const itemElement = document.createElement('li');
                itemElement.classList.add('cart-item');
                itemElement.innerHTML = `
                    <img src="${item.image}" alt="${item.name}" />
                    <div>
                        <p>${item.name}</p>
                        <p>Cantidad: ${item.quantity}</p>
                        <p>$${(item.price * item.quantity).toFixed(2)}</p>
                    </div>
                `;
                cartItemsList.appendChild(itemElement);
            });

            // Mostrar el botón de "Continuar con la compra" si hay productos
            continuePurchaseButton.disabled = false;
            continuePurchaseButton.style.display = 'block';
            emptyCartMessage.style.display = 'none';
        } else {
            cartItemsList.innerHTML = ''; // Limpiar la lista
            emptyCartMessage.style.display = 'block'; // Mostrar mensaje de carrito vacío
            continuePurchaseButton.style.display = 'none'; // Ocultar el botón de continuar compra
        }
    }

    // Limpiar el carrito
    clearCartButton.addEventListener('click', () => {
        carrito = [];
        localStorage.setItem('carrito', JSON.stringify(carrito));
        actualizarCarrito(); // Actualizar la interfaz del carrito
    });
});
