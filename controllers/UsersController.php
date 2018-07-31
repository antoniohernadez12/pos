<?php
    class UsersController
    {
        /**
         * CONTROLADOR LOGIN
        */
		public function ctrlLoginUser()
		{
            if (isset($_POST['ingUsuario'])) {
                if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['ingUsuario']) &&
                    preg_match('/^[a-zA-Z0-9]+$/', $_POST['ingPassword'])) {
					
					// ENCRIPTAR LA CONTRASEÑA
					$encriptar = crypt($_POST['ingPassword'], '$2a$07$usesomesillystringforsalt$');

                    // Entidad de la tabla 'users'
                    $item = 'user';
                    $value = $_POST['ingUsuario'];

                    $respuesta = Users::findUser($item, $value);
					
                    if ($respuesta['password'] == $_POST['ingPassword']) {
						if ($respuesta['status'] == 1) {
							$_SESSION['iniciarSesion'] = 'ok';
							$_SESSION['id'] = $respuesta['id'];
							$_SESSION['name'] = $respuesta['name'];
							$_SESSION['user'] = $respuesta['user'];
							$_SESSION['avatar'] = $respuesta['avatar'];
							$_SESSION['profile'] = $respuesta['profile'];

							/** 
							 * REGISTRAR FECHA PARA SABER EL ULTIMO LOGIN
							*/

							// ZONA HORARIO
							date_default_timezone_set('America/Monterrey');

							// CAPTURAR FECHA Y HORA
							$fecha = date('Y-m-d');
							$hora = date('H:i:s');
							$fechaActual = $fecha.' '.$hora;

							$item1 = 'last_login';
							$value1 = $fechaActual;
							$item2 = 'id';
							$value2 = $respuesta['id'];

							//ACTUALIZAR ULTIMO LOGIN
							$ultimoLogin = Users::ActUser($item1, $value1, $item2, $value2);
							
							if ($ultimoLogin) {
								echo '<script> window.location = "home"; </script>';
							}
						} else {
							echo '<br><div class="alert alert-danger">Alerta el usuario no esta Activado</div>';	
						}
                    } else {
                        echo '<br><div class="alert alert-danger">Error al ingresar, intente de nuevo</div>';
                    }
                }
            }
        }

		/** 
		 * CONTROLADOR CREAR USUARIO
		*/
        public function ctrCreateUser()
    	{
    		if (isset($_POST['nuevoUsuario'])) {
    			if (preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST['nuevoNombre']) &&
    					preg_match('/^[a-zA-Z0-9]+$/', $_POST['nuevoUsuario']) &&
    					preg_match('/^[a-zA-Z0-9]+$/', $_POST['nuevoPassword'])) {
					
					$ruta = '';
					/** 
					 * VALIDAR LA IMAGEN
					*/
					if (isset($_FILES['nuevaFoto']['tmp_name'])) {
						list($ancho, $alto) = getimagesize($_FILES['nuevaFoto']['tmp_name']);
						
						$nuevoAncho = 500;
						$nuevoAlto = 500;

						/** 
						 * CREAMOS EL DIRECTORIO DONDE VAMOS A GUARDAR LA FOTO DEL USUARIO
						*/

						$directorio = 'views/img/users/'.$_POST['nuevoUsuario'];

						mkdir($directorio, 0755);

						/**
						 * DE ACUERDO AL TIPO DE IMAGEN APLICAMOS LAS FUNCIONES POR DEFECTO DE PHP
						*/

						if ($_FILES['nuevaFoto']['type'] == 'image/jpeg') {
							/** 
							 * GUARDAMOS LA IMAGEN EN EL DIRECTORIO
							*/
							$aleatorio = mt_rand(100, 999);
							$ruta = 'views/img/users/'.$_POST['nuevoUsuario'].'/'.$aleatorio.'.jpg';

							$origen = imagecreatefromjpeg($_FILES['nuevaFoto']['tmp_name']);
							$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

							imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

							imagejpeg($destino, $ruta);
						}

						if ($_FILES['nuevaFoto']['type'] == 'image/png') {
							/** 
							 * GUARDAMOS LA IMAGEN EN EL DIRECTORIO
							*/
							$aleatorio = mt_rand(100, 999);
							$ruta = 'views/img/users/'.$_POST['nuevoUsuario'].'/'.$aleatorio.'.png';

							$origen = imagecreatefrompng($_FILES['nuevaFoto']['tmp_name']);
							$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

							imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

							imagepng($destino, $ruta);
						}
					}

					// ENCRIPTAR LA CONTRASEÑA
					$encriptar = crypt($_POST['nuevoPassword'], '$2a$07$usesomesillystringforsalt$');

    				$datos = [
    					'nombre' => $_POST['nuevoNombre'],
    					'usuario' => $_POST['nuevoUsuario'],
    					'password' => $encriptar,
						'perfil' => $_POST['nuevoPerfil'],
						'ruta' => $ruta
    				];
                
    				$respuesta = Users::addUser($datos);
                
    				if ($respuesta) {
    					echo '<script>
    						swal({
    							type: "success",
    							title: "¡El usuario ha sido guardado correctamente!",
    							showConfirmButton: true,
    							confirmButtonText: "Cerrar",
    							closeOnConfirm: false
    						}).then((result)=> {
    							if (result.value) {
    								window.location = "users";
    							}
    						});
    					</script>';
    				} else {
    					echo '<script>
    						swal({
    							type: "error",
    							title: "¡El usuario no se pudo guardar!",
    							showConfirmButton: true,
    							confirmButtonText: "Cerrar",
    							closeOnConfirm: false
    						}).then((result)=> {
    							if (result.value) {
    								window.location = "users";
    							}
    						});
    					</script>';
    				}
                
    			} else {
    				echo '<script>
    					swal({
    						type: "error",
    						title: "¡El usuario no puede ir vacío o llevar caracteres especiales!",
    						showConfirmButton: true,
    						confirmButtonText: "Cerrar",
    						closeOnConfirm: false
    					}).then((result)=> {
    						if (result.value) {
    							window.location = "users";
    						}
    					});
    				</script>';
    			}
    		}
		}

		/** 
		 * CONTROLADOR MOSTRAR USUARIOS
		*/
		public function ctrViewsUsers($item, $value)
		{
			$respuesta = Users::findUser($item, $value);

			return $respuesta;
		}

		/** 
		 * CONTROLADOR EDITAR USUARIO
		*/
		public function ctrEditUser()
		{
			if (isset($_POST['editarUsuario'])) {
				if (preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST['editarNombre'])) {
					/** 
					 * VALIDAR IMAGEN
					*/
					$ruta = $_POST['fotoActual'];

					if (isset($_FILES['editarFoto']['tmp_name']) && !empty($_FILES['editarFoto']['tmp_name'])) {
						list($ancho, $alto) = getimagesize($_FILES['editarFoto']['tmp_name']);
						
						$nuevoAncho = 500;
						$nuevoAlto = 500;

						/** 
						 * CREAMOS EL DIRECTORIO DONDE VAMOS A GUARDAR LA FOTO DEL USUARIO
						*/

						$directorio = 'views/img/users/'.$_POST['editarUsuario'];

						/** 
						 * PREGUNTAMOS SI EXISTE OTRA IMAGEN EN LA BASE DE DATOS
						*/

						if (!empty($_POST['fotoActual'])) {
							unlink($_POST['fotoActual']);
						} else {
							mkdir($directorio, 0755);
						}

						/**
						 * DE ACUERDO AL TIPO DE IMAGEN APLICAMOS LAS FUNCIONES POR DEFECTO DE PHP
						*/

						if ($_FILES['editarFoto']['type'] == 'image/jpeg') {
							/** 
							 * GUARDAMOS LA IMAGEN EN EL DIRECTORIO
							*/
							$aleatorio = mt_rand(100, 999);
							$ruta = 'views/img/users/'.$_POST['editarUsuario'].'/'.$aleatorio.'.jpg';

							$origen = imagecreatefromjpeg($_FILES['editarFoto']['tmp_name']);
							$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

							imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

							imagejpeg($destino, $ruta);
						}

						if ($_FILES['editarFoto']['type'] == 'image/png') {
							/** 
							 * GUARDAMOS LA IMAGEN EN EL DIRECTORIO
							*/
							$aleatorio = mt_rand(100, 999);
							$ruta = 'views/img/users/'.$_POST['editarUsuario'].'/'.$aleatorio.'.png';

							$origen = imagecreatefrompng($_FILES['editarFoto']['tmp_name']);
							$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

							imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

							imagepng($destino, $ruta);
						}
					}

					if ($_POST['editarPassword'] != '') {
						if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['editarPassword'])) {	
							// ENCRIPTAR LA CONTRASEÑA
							$encriptar = crypt($_POST['editarPassword'], '$2a$07$usesomesillystringforsalt$');
						} else {
							echo '<script>
    								swal({
    									type: "error",
    									title: "¡La contraseña no puede ir vacía o llevar caracteres especiales!",
    									showConfirmButton: true,
    									confirmButtonText: "Cerrar",
    									closeOnConfirm: false
    								}).then((result)=> {
    									if (result.value) {
    										window.location = "users";
    									}
    								});
    							</script>';
						}
					} else {
						$encriptar = $_POST['passwordActual'];
					}

					$datos = [
    					'name' => $_POST['editarNombre'],
    					'user' => $_POST['editarUsuario'],
    					'password' => $encriptar,
						'profile' => $_POST['editarPerfil'],
						'ruta' => $ruta
					];
					
					$respuesta = Users::EditUser($datos);

					if ($respuesta) {
    					echo '<script>
    						swal({
    							type: "success",
    							title: "¡El usuario ha sido guardado correctamente!",
    							showConfirmButton: true,
    							confirmButtonText: "Cerrar",
    							closeOnConfirm: false
    						}).then((result)=> {
    							if (result.value) {
    								window.location = "users";
    							}
    						});
    					</script>';
    				} else {
    					echo '<script>
    						swal({
    							type: "error",
    							title: "¡El usuario no se pudo guardar!",
    							showConfirmButton: true,
    							confirmButtonText: "Cerrar",
    							closeOnConfirm: false
    						}).then((result)=> {
    							if (result.value) {
    								window.location = "users";
    							}
    						});
    					</script>';
    				}					
				} else {
					echo '<script>
    					swal({
    						type: "error",
    						title: "¡El usuario no puede ir vacío o llevar caracteres especiales!",
    						showConfirmButton: true,
    						confirmButtonText: "Cerrar",
    						closeOnConfirm: false
    					}).then((result)=> {
    						if (result.value) {
    							window.location = "users";
    						}
    					});
    				</script>';
				}
			}
		}

		/** 
		 * ELIMINAR USUARIO
		*/
		public function ctrDeleteUser()
		{
			if (isset($_GET['idUser'])) {
				$datos = $_GET['idUser'];
				
				/** 
				 * ELIMINAR LA FOTO SI EXISTE EN LA BASE DE DATOS
				*/
				if ($_GET['fotoUser'] != '') {
					unlink($_GET['fotoUser']);
					rmdir('views/img/users/'.$_GET['user']);
				}

				$respuesta = Users::deleteUser($datos);

				if ($respuesta) {
					echo ' <script>
						swal({
    						type: "success",
    						title: "¡El usuario ha sido borrado correctamente!",
    						showConfirmButton: true,
    						confirmButtonText: "Cerrar",
    						closeOnConfirm: false
    					}).then((result)=> {
    						if (result.value) {
    							window.location = "users";
    						}
    					});
					</script>';
				}
			}
		}
    }