<!DOCTYPE html>
<html>
	<head>
		<title>Enviar E-mails</title>

		<link rel="stylesheet" type="text/css" href="estilos.css" media="screen" />
	</head>

	<body>
		<div>
			<header>
				<h1>Enviar e-mail a clientes</h1>
				<p><strong>Privado:</strong> Solo para uso privado.<br />
  				Escribir y enviar un email a los miembros de la lista de clientes.</p>
			</header>
		</div>

		<?php

			// Datos de conexion a la base de datos MYSQL
		    // Cambia estos valores por los tuyos: 
			$url_db = 'la_url_de_tu_DB'; // por lo general es localhost
			$usuario_db = 'tu_nombre_de_usuario';
			$password_db = 'tu_contraseña';
			$nombre_db = 'el_nombre_de_tu_DB';
			$nombre_tabla_db = 'el_nombre_de_tu_tabla';


			// Inicilizamos las variables del formulario (por primera vez) 
			// para que se muestren vacias al cargar el formulario por primera vez
			if (!isset($asunto_del_email)) { $asunto_del_email = ""; }
			if (!isset($texto_del_mail)) { $texto_del_mail = ""; }
	
			// Verificamos si el valor de $_POST['submit']
			// ha sido seteado. Osea si se ha hecho el submit del
			// boton enviar. "SI EL FORMULARIO FUE SUBMITTED 
			// (se presiono el boton) entonces:"
			if (isset($_POST['submit'])) {
		
				// Obtenemos los datos del formulario
				// Y seteamos las variables con los datos enviados
				$asunto_del_email = $_POST['subject'];
				$texto_del_mail = $_POST['cumplomail']; // lo obtenemos del formulario
				
				// flag para saber cuando mostrar el formulario
				// El formulario debe mostrarse en la primera carga
				// de pagina y luego debe ser recargado si no se pasa
				// la validación de los campos.
				$output_form = false;

				/* ------------------- VALIDACION ------------------------*/
				
				// verifica si el asunto y el texto del email estan vacios
				if(empty($asunto_del_email) && empty($texto_del_mail)) {
					echo '<span class="warning">Olvidaste el asunto y cuerpo del mensaje.</span><br />';
					echo '<br />';
					$output_form = true; // Debe mostrarse el formulario nuevamente para 
										 // ingresar el asunto y el email faltantes
				}

				// verifica si el asunto esta vacio
				if(empty($asunto_del_email) && (!empty($texto_del_mail))) {
					echo '<span class="warning">Olvidaste indicar el asunto.</span><br />';
					echo '<br />';
					$output_form = true; // Debe mostrarse el formulario nuevamente para 
										 // ingresar el asunto faltante
				}
	
				// verifica si el texto del email esta vacio
				if((!empty($asunto_del_email)) && empty($texto_del_mail)) {
					echo '<span class="warning">Olvidaste escribir el cuerpo del mensaje.</span><br />';
					echo '<br />';
					$output_form = true; // Debe mostrarse el formulario nuevamente para 
										 // ingresar el texto del mensaje faltante
				}

				// Verifica que no esten vacios el asunto y el texto del email 
				// En caso de no estarlo se procede a enviar el email.
				if((!empty($asunto_del_email)) && (!empty($texto_del_mail))) {

					// Hacemos el pulling de todos los datos de la tabla 
					// en nuestra base de datos que contenga la lista de emails 

					// Creamos e iniciamos una conexion a nuestra base de datos
					
					$dbc = mysqli_connect($url_db, $usuario_db, $password_db, $nombre_db) 
						or die('Error al conectar con el servidor MySQL.');

					// Armamos la cosnulta
					$query = "SELECT * FROM $nombre_tabla_db";

					// Realizamos la consulta
					$result = mysqli_query($dbc, $query)
						or die('Error al consultar la base de datos.');

					// Recorremos uno a uno los registros de la tabla 
					// Y en viamos un mail a cada cliente
					while( $row = mysqli_fetch_array($result) ) {
						$nombre = $row['nombre'];
						$apellido = $row['apellido'];
						$para = $row['email']; // obtenemos el email de cada destinatariofila
						$body = "Estimado $nombre $apellido, \n $texto_del_mail";
						
						// eneviamos el email a cada mail de la lista
						echo 'Mensaje enviado a: '. $para . '<br />';

						/* --> En caso de usar PHPMailer el mail se envia de la sigu manera: 
						$mail->AddAddress($para, $nombre . ' ' .$apellido);

						if(!$mail->Send()) {
							echo 'Mailer Error: ' . $para . $mail->ErrorInfo .'<br />';
						} else {
							echo 'Mensaje enviado a: '. $para . '<br />';
						}
						<-- */
						
					} // end While

					// cerramos la conexion a la base de datos
					mysqli_close($dbc); 

		
				} // end If
		
			// si el formulario no fue enviado debe mostrarse
			// este caso se da en la primera carga
			} else { 
				$output_form = true;
			}

			// Muestra el formulario si la bandera para ello esta en true
			if($output_form) {

			?>

			<div>
				<!-- SEND EMAIL FORM -->
				<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					<label for="subject">Asunto del email:</label><br />
					<input type="text" id="subject" name="subject" size="30" value="<?php echo $asunto_del_email; ?>" /><br />
					<br />
				
					<label for="cumplomail">Cuerpo del email:</label><br />
					<textarea id="cumplomail" name="cumplomail" rows="8" cols="40"><?php echo $texto_del_mail; ?></textarea><br />
						
					<input type="submit" id="submit" name="submit" value="Enviar" />
				</form>
			</div>		

		<?php	
			} // end if
		?>

	</body>

</html>