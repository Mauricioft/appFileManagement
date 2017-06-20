<?php
	$servername = "localhost";
	$username = "username";
	$password = "password";

	// Create connection
	$conn = mysql_connect($servername, $username, $password);

	// Check connection
	if (!$conn) {
	    die("Connection failed: " . mysql_connect_error());
	}
	echo "Connected successfully";

	// Lo siguiente podría ser proporcionado por un usuario, como por ejemplo
	$nombre = 'fred';
	$apellido  = 'fox';

	// Formular la consulta
	// Este es el mejor método para formular una consulta SQL
	// Para más ejemplos, consulte mysql_real_escape_string()
	$consulta = sprintf("SELECT nombre, apellido, direccion, edad FROM amigos 
	    WHERE nombre='%s' AND apellido='%s'",
	    mysql_real_escape_string($nombre),
	    mysql_real_escape_string($apellido));

	// Ejecutar la consulta
	$resultado =  mysqli_query( $conn
, $consulta);
	// Comprobar el resultado
	// Lo siguiente muestra la consulta real enviada a MySQL, y el error ocurrido. Útil para depuración.
	if (!$resultado) {
	    $mensaje  = 'Consulta no válida: ' . mysql_error() . "\n";
	    $mensaje .= 'Consulta completa: ' . $consulta;
	    die($mensaje);
	}

	// Usar el resultado
	// Si se intenta imprimir $resultado no será posible acceder a la información del recurso
	// Se debe usar una de las funciones de resultados de mysql
	// Consulte también mysql_result(), mysql_fetch_array(), mysql_fetch_row(), etc.
	while ($fila = mysql_fetch_assoc($resultado)) {
	    echo $fila['nombre'];
	    echo $fila['apellido'];
	    echo $fila['direccion'];
	    echo $fila['edad'];
	}

	// Liberar los recursos asociados con el conjunto de resultados
	// Esto se ejecutado automáticamente al finalizar el script.
	mysql_free_result($resultado);
?>