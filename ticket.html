

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de ticket - Tienda de Artesanías</title>    
    <link rel="stylesheet" href="style.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">     
</head>
<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    include 'session_check.php';
    ?>
<body>
    <header>
        <div id="banner">  
            <img src="ima/logon.png" >
            <h1> Bienvenidos <br>"Artesanias alebrije"</h1>
        </div>
    </header>
    <header>
        <header>
            <ul class="menu">
                <li><a href="ARTESANIAS ALEBRIJES.html">Inicio</a></li>
                <li><a href="categoria.php">Categorías</a></li>
                <li><a href="producto.php">Productos</a></li>
                <li><a href="ticket.php">Ticket</a></li>
                <li><a href="logout.php">cerrar sesion</a></li>
                </ul>
        </header>
    </header>

    <div class="container">
        <h2>Formulario de ticket</h2>
        <form id="ticketForm" action="registroticket.php" method="post">
            <div class="form-group">
                <label for="nombre_cliente">Nombre del Cliente:</label>
                <input type="text" id="nombre_cliente" name="nombre_cliente"  
                   pattern="[A-Za-z0-9\s]+"
                title="Solo letras, números y espacios son permitidos"
                oninput="this.value = this.value.replace(/[^A-Za-z0-9\s]/g, '')" required><br>

            </div>
            <div class="form-group">
                <label for="fecha">Fecha:</label>
                <input type="date" id="fecha" name="fecha" required>
            </div>
            <div id="productos">
                <div class="form-group producto">
                    <label for="producto_1">Producto:</label>
                    <select id="producto_1" name="producto[]" onchange="actualizarPrecio(this)" required>
                        <!-- Options will be loaded here dynamically -->
                    </select>
                    <label for="cantidad_1">Cantidad:</label>
                    <input type="number" id="cantidad_1" name="cantidad[]" min="1" required>
                    <label for="precio_unitario_1">Precio Unitario:</label>
                    <input type="number" step="0.01" id="precio_unitario_1" name="precio_unitario[]" required readonly>
                    <button type="button" class="button" onclick="eliminarProducto(this)">Eliminar</button>
                </div>
            </div>
            <button type="button" class="button" onclick="agregarProducto()">Agregar Producto</button>
            <div class="form-group">
                <label for="nombre_personal">Nombre del Personal:</label>
                <input type="text" id="nombre_personal" name="nombre_personal"
                       pattern="[A-Za-z\s]+" title="Solo letras y espacios son permitidos"
                       oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')" required>
            </div>
            <div class="form-group">
                <label for="telefono_empresa">Teléfono de la Empresa:</label>
                <input type="text" id="telefono_empresa" name="telefono_empresa" 
                pattern="\d{10}" title="Debe contener exactamente 10 dígitos"
                oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
            </div>
            <div class="form-group">
                <label for="domicilio_empresa">Domicilio de la Empresa:</label>
                <input type="text" id="domicilio_empresa" name="domicilio_empresa" 
                pattern="[A-Za-z0-9\s]+" title="Solo letras, números y espacios son permitidos" 
                oninput="this.value = this.value.replace(/[^A-Za-z0-9\s]/g, '')" required><br>
            </div>
            <div class="form-group">
                <label for="total">Total:</label>
                <input type="number" step="0.01" id="total" name="total" required readonly>
            </div>
            <div class="form">
                <input type="submit" value="Generar ticket y almacenar">
            </div>
        </form>
    </div>

    <script>
        document.getElementById('ticketForm').addEventListener('input', calcularTotal);

        function calcularTotal() {
            let total = 0;
            document.querySelectorAll('.producto').forEach((producto) => {
                const cantidad = parseFloat(producto.querySelector('[id^="cantidad_"]').value) || 0;
                const precioUnitario = parseFloat(producto.querySelector('[id^="precio_unitario_"]').value) || 0;
                total += cantidad * precioUnitario;
            });
            document.getElementById('total').value = total.toFixed(2);
        }

        function agregarProducto() {
            const productosDiv = document.getElementById('productos');
            const numProductos = productosDiv.querySelectorAll('.producto').length;
            const nuevoProductoDiv = document.createElement('div');
            nuevoProductoDiv.className = 'form-group producto';
            nuevoProductoDiv.innerHTML = `
                <label for="producto_${numProductos + 1}">Producto:</label>
                <select id="producto_${numProductos + 1}" name="producto[]" onchange="actualizarPrecio(this)" required>
                    <!-- Options will be loaded here dynamically -->
                </select>
                <label for="cantidad_${numProductos + 1}">Cantidad:</label>
                <input type="number" id="cantidad_${numProductos + 1}" name="cantidad[]" min="1" required>
                <label for="precio_unitario_${numProductos + 1}">Precio Unitario:</label>
                <input type="number" step="0.01" id="precio_unitario_${numProductos + 1}" name="precio_unitario[]" required readonly>
                <button type="button" class="button" onclick="eliminarProducto(this)">Eliminar</button>
            `;
            productosDiv.appendChild(nuevoProductoDiv);
            cargarProductos(nuevoProductoDiv.querySelector('select'));
        }

        function eliminarProducto(button) {
            button.parentNode.remove();
            calcularTotal();
        }

        function cargarProductos(selectElement) {
            fetch('getProducts.php')
                .then(response => response.json())
                .then(data => {
                    selectElement.innerHTML = '<option value="">Selecciona un producto</option>';
                    data.forEach(producto => {
                        const option = document.createElement('option');
                        option.value = producto.id_producto;
                        option.text = `${producto.nombre} - $${producto.precio}`;
                        selectElement.add(option);
                    });
                });
        }

        function actualizarPrecio(selectElement) {
            const precioInput = selectElement.parentNode.querySelector('[id^="precio_unitario_"]');
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            if (selectedOption && selectedOption.text) {
                const precio = selectedOption.text.split('$')[1];
                precioInput.value = parseFloat(precio).toFixed(2);
            } else {
                precioInput.value = '';
            }
            calcularTotal();
        }

        // Cargar productos en el primer select al cargar la página
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('select[name="producto[]"]').forEach(select => cargarProductos(select));
        });
    </script>
</body>
</html>
