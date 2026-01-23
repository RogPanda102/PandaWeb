<?php
include 'bd.php';
if (isset($_POST['submit'])) {

    $nombre_completo = $_POST['nombre_completo'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $ciudad = $_POST['ciudad'];
    $empresa = $_POST['empresa'];
    $sector = $_POST['sector'];
    $sitioWeb = $_POST['sitioWeb'];

    $dominio = $_POST['dominio'];
    $modificacion = $_POST['modificacion'];
    $fecha_limite = $_POST['fecha_limite'];
    $presupuesto = $_POST['presupuesto'];
    $posible_fecha = $_POST['posible_fecha'];
    //=====================================================================================================================
    //Recogemos el archivo enviado por el formulario
    $archivo = $_FILES['imagen']['name'];
    //Si el archivo contiene algo y es diferente de vacio
    if (isset($archivo) && $archivo != "") {
        //Obtenemos algunos datos necesarios sobre el archivo
        $tipo = $_FILES['imagen']['type'];
        $tamano = $_FILES['imagen']['size'];
        $temp = $_FILES['imagen']['tmp_name'];
        //Se comprueba si el archivo a cargar es correcto observando su extensión y tamaño
        if (!((strpos($tipo, "gif") || strpos($tipo, "jpeg") || strpos($tipo, "jpg") || strpos($tipo, "png")) && ($tamano < 2000000000))) {
            echo '<div><b>Error. La extensión o el tamaño de los archivos no es correcta.<br/>
					- Se permiten archivos .gif, .jpg, .png. y de 200000 kb como máximo.</b></div>';

        } else {
            //Si la imagen es correcta en tamaño y tipo
            //Se intenta subir al servidor
            if (move_uploaded_file($temp, './galeria-img/' . $archivo)) {
                //Cambiamos los permisos del archivo a 777 para poder modificarlo posteriormente
                chmod('./galeria-img/' . $archivo, 0777);

                $insertar = "INSERT INTO formulario (
                nombre_completo,
                email,
                telefono,
                ciudad,
                empresa,
                sector,
                sitioWeb,
                dominio,
                modificaciones,
                fecha_limite,
                presupuesto,
                posible_fecha,
                imagen)
                VALUES (
                '$nombre_completo',
                '$email',
                '$telefono',
                '$ciudad',
                '$empresa',
                '$sector',
                '$sitioWeb',
                '$dominio',
                '$modificaciones',
                '$fecha_limite',
                '$presupuesto',
                '$posible_fecha',
                'galeria-img/$archivo'
                );";
                mysqli_query($conexion, $insertar);
                mysqli_close($conexion);

                echo '<script>alert("Se ha guardardo correctamente el registro");</script>';
                echo '<script> window.location.href="index.php"; </script>';
            } else {
                //Si no se ha podido subir la imagen, mostramos un mensaje de error
                echo '<div><b>Ocurrió algún error al subir imagen. No pudo guardarse.</b></div>';
            }
        }
    } 
        
}else{
        $insertar = "INSERT INTO formulario (
        nombre_completo,
        email,
        telefono,
        ciudad,
        empresa,
        sector,
        sitioWeb,
        dominio,
        modificaciones,
        fecha_limite,
        presupuesto,
        posible_fecha,
        imagen)
        VALUES (
        '$nombre_completo',
        '$email',
        '$telefono',
        '$ciudad',
        '$empresa',
        '$sector',
        '$sitioWeb',
        '$dominio',
        '$modificaciones',
        '$fecha_limite',
        '$presupuesto',
        '$posible_fecha',
        'galeria-img/$archivo'
        );";
    mysqli_query($conexion, $insertar);
    mysqli_close($conexion);

    echo '<script>alert("Se ha guardardo correctamente el registro");</script>';
    echo '<script> window.location.href="index.php"; </script>';
} 
?>