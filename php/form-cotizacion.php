<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario cotización</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
        }

        .container {
            background-color: white;
            padding: 50px;
            border-radius: 8px;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 0 50px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .checkbox-group,
        .radio-group {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        button {
            background-color: #333;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button[type="reset"] {
            background-color: #555;
        }

        button:hover {
            background-color: #555;
        }

        button[type="reset"]:hover {
            background-color: #777;
        }
    </style>
</head>

<body>
    <div class="container">

        <h2>Solicitud de cotización</h2>
        <?php
        include 'bd.php';
        ?>
        <form enctype="multipart/form-data" action="insert.php" method="POST">
            <label>Nombre completo:</label>
            <input type="text" id="nombre_completo" name="nombre_completo">

            <label>Correo electrónico:</label>
            <input type="email" id="email" name="email">

            <label>Teléfono:</label>
            <input type="tel" id="telefono" name="telefono" minlength="10" maxlength="10">

            <label>Ciudad:</label>
            <input type="text" id="ciudad" name="ciudad">

            <label>Empresa:</label>
            <input type="text" id="empresa" name="empresa">

            <label>¿Cuál es tu sector comercial?:</label>
            <textarea id="sector" name="sector" rows="5" cols="50"
                placeholder="Por ejemplo: venta de electrodomésticos, gestoría administrativa, diseño de interiores, productos etc...."></textarea>

            <label>¿Existe un sitio web?</label>
            <label>Sí</label>
            <input type="radio" id="si_web" name="sitioWeb" value="SI" onclick="sitio_web()">

            <label>No</label>
            <input type="radio" id="no_web" name="sitioWeb" value="NO" onclick="sitio_web()">

            <div id="dominio" style="display:none">
                <label>Dominio:</label>
                <input type="url" id="dominio" name="dominio" placeholder="https://">
            </div>
            <div id="modificaciones" style="display:none">
                <label>Escribe a detalle las modificaciones de la página:</label>
                <textarea id="modificacion" name="modificacion" rows="4"
                    placeholder="Detalles de modificaciones"></textarea>
            </div>

            <div id="fecha_limite" style="display:none">
                <label>Fecha limite de entrega:</label>
                <input type="date" id="fecha_limite" name="fecha_limite">
            </div>

            <div id="presupuesto" style="display:none">
                <label>Presupuesto:</label>
                <input type="number" id="presupuesto" name="presupuesto">
            </div>
            <div id="posible_fecha" style="display:none">
                <label>Definir fecha de entrega:</label>
                <input type="date" id="posible_fecha" name="posible_fecha">
            </div>
            <div id="imagen" style="display:none">
                <label>Imagen:</label>
                <input type="file" id="imagen" name="imagen">
            </div>

            <script type="text/javascript">
                function sitio_web() {
                    if (document.getElementById('si_web').checked) {
                        document.getElementById('dominio').style.display = 'block';
                        document.getElementById('modificaciones').style.display = 'block';
                        document.getElementById('fecha_limite').style.display = 'block';
                        document.getElementById('presupuesto').style.display = 'block';

                        document.getElementById('posible_fecha').style.display = 'none';
                        document.getElementById('imagen').style.display = 'none';
                    } else {
                        document.getElementById('dominio').style.display = 'none';
                        document.getElementById('modificaciones').style.display = 'none';
                        document.getElementById('fecha_limite').style.display = 'none';
                        document.getElementById('presupuesto').style.display = 'none';

                        document.getElementById('posible_fecha').style.display = 'block';
                        document.getElementById('imagen').style.display = 'block';
                    }
                }
            </script>
            <button type="submit" name="submit">Enviar cotización</button>
            <button type="reset">Restablecer</button>
        </form>
    </div>

</html>