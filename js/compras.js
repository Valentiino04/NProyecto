document.addEventListener('DOMContentLoaded', () => {
    // Sincronizar el carrito desde localStorage a cookies
    sincronizarCarritoConCookies();

    // Validar el formulario antes de enviarlo
    const compraForm = document.querySelector('#compraForm');
    if (compraForm) {
        compraForm.addEventListener('submit', (e) => {
            if (!validarFormulario()) {
                e.preventDefault(); // Evitar el envío del formulario si hay errores
                alert('Por favor, complete todos los campos correctamente.');
            }
        });
    }
});

/**
 * Sincronizar el carrito desde localStorage a cookies
 */
function sincronizarCarritoConCookies() {
    const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    if (carrito.length > 0) {
        document.cookie = `carrito=${encodeURIComponent(JSON.stringify(carrito))}; path=/`;
    } else {
        // Si el carrito está vacío, eliminar la cookie
        document.cookie = `carrito=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC;`;
    }
}

/**
 * Validar los campos del formulario de compra
 * @returns {boolean} Si el formulario es válido o no
 */
function validarFormulario() {
    const nombre = document.querySelector('#nombre').value.trim();
    const email = document.querySelector('#email').value.trim();
    const direccion = document.querySelector('#direccion').value.trim();
    const telefono = document.querySelector('#telefono').value.trim();
    const numeroTarjeta = document.querySelector('#numero_tarjeta').value.trim();
    const nombreTarjeta = document.querySelector('#nombre_tarjeta').value.trim();
    const fechaVencimiento = document.querySelector('#fecha_vencimiento').value.trim();
    const cvv = document.querySelector('#cvv').value.trim();

    // Validaciones básicas
    if (!nombre || !email || !direccion || !telefono || !numeroTarjeta || !nombreTarjeta || !fechaVencimiento || !cvv) {
        return false; // Faltan campos por llenar
    }

    // Validar formato de email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        return false; // Email inválido
    }

    // Validar número de tarjeta (16 dígitos)
    if (!/^\d{16}$/.test(numeroTarjeta)) {
        return false; // Número de tarjeta inválido
    }

    // Validar CVV (3 dígitos)
    if (!/^\d{3}$/.test(cvv)) {
        return false; // CVV inválido
    }

    return true; // Todo está correcto
}
