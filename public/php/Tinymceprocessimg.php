<?php
	// TODO
	// Este archivo debería estar dentro de alguna carpeta dentro de /app/, quizá en controllers. El problema es que CI manda error 403 cuando lo llama TinyMCE. Para no atorarme en esto, lo saco de /app/ hasta nuevo aviso.
    /**
     * Este archivo se llama cuando se sube una imagen al servidor desde el editor de texto TinyMCE. Se encarga de subir las imágenes al servidor.
     *
     * @return mixed
     */

	require_once('config.php');
	require_once('helpers.php');

	$DuranHelpers = new DuranHelpers();
	$respuesta = array(
		'title' => '¡Ooops!',
		'msgtype' => 'error',
		'msg' => 'Unknown',
	);

    try {
		$f_file_name=$f_file_type=$f_file_tmp_name=$f_file_error=$f_file_errormsg=$f_file_size=NULL;

		if (isset($_FILES)) {
			foreach ($_FILES as $inputName => $dataFile) {
				if ($inputName === 'file') { // "file" se lo pone automáticamente TinyMCE

					$f_file_name = (strlen($_FILES['file']['name'])>0) ?$_FILES['file']['name']:NULL;
					$f_file_type = (strlen($_FILES['file']['type'])>0) ?$_FILES['file']['type']:NULL;
					$f_file_tmp_name = (strlen($_FILES['file']['tmp_name'])>0) ?$_FILES['file']['tmp_name']:NULL;
					$f_file_size = ($_FILES['file']['size']>0) ?$_FILES['file']['size']:NULL;
					// El elemento 'error' siempre viene, y aquí sus posibles valores:
					// 		0  ->  No hay error, fichero subido con éxito
					// 		1  ->  El fichero subido excede la directiva upload_max_filesize de php.ini
					// 		2  ->  El fichero subido excede la directiva MAX_FILE_SIZE especificada en el formulario HTML
					// 		3  ->  El fichero fue sólo parcialmente subido
					// 		4  ->  No se subió ningún fichero
					// 		6  ->  Falta la carpeta temporal
					// 		7  ->  No se pudo escribir el fichero en el disco
					// 		8  ->  Una extensión de PHP detuvo la subida de ficheros
					$f_file_error = $_FILES['file']['error'];
					switch ($f_file_error) {
						case 1:
							$f_file_errormsg = 'El fichero subido excede la directiva upload_max_filesize de php.ini';
							break;
						case 2:
							$f_file_errormsg = 'El fichero subido excede la directiva MAX_FILE_SIZE especificada en el formulario HTML';
							break;
						case 3:
							$f_file_errormsg = 'El fichero fue sólo parcialmente subido';
							break;
						case 4:
							$f_file_errormsg = 'No se subió ningún fichero';
							break;
						case 6:
							$f_file_errormsg = 'Falta la carpeta temporal';
							break;
						case 7:
							$f_file_errormsg = 'No se pudo escribir el fichero en el disco';
							break;
						case 8:
							$f_file_errormsg = 'Una extensión de PHP detuvo la subida de ficheros';
							break;
					}

					// Si el archivo se subió ok
					if ($f_file_error === 0) {
						$file = NULL;
						$finfo = finfo_open(FILEINFO_MIME_TYPE);
						$mimetypesImg = explode(',', MIMETYPES_IMG);
						$file = finfo_file($finfo, $f_file_tmp_name);

						if (!is_null($file)) {
							if (in_array($file, $mimetypesImg)) {
								$fileExtension = $DuranHelpers->getFileExtension($f_file_name);
								$fileName = $DuranHelpers->getFileName($f_file_name);
								$fileFullName = date('YmdHis') . '-' . $fileName . '.' . $fileExtension;
								// Todas las imágenes se guardan en la carpeta que CI4 tiene destinada para eso (writable/uploads/). Para que estén disponibles en la carpeta /public/ simplemente se hace un link simbólico que vincule ambas carpetas.
								$filePathFinal = ROOT_APP . 'writable/uploads/' . $fileFullName;

								if (move_uploaded_file($f_file_tmp_name,$filePathFinal)) {
									$respuesta = array(
										'location' => $filePathFinal,
										'title' => '¡Yeeeha!',
										'msgtype' => 'success',
										'msg' => 'La imagen se cargó correctamente',
									);
								}
								else {
									$DuranHelpers->log(FOLDER_LOGS, 'Tinymceprocessimg.php', __LINE__, 'Imposible mover archivo');
									$respuesta = array(
										'title' => '¡Ooops!',
										'msgtype' => 'error',
										'msg' => 'Imposible mover archivo',
									);
								}
							}
							else {
								$respuesta = array(
									'title' => '¡Ooops!',
									'msgtype' => 'error',
									'msg' => 'El mimetype del archivo NO es permitido',
								);
							}
						}
						else {
							$respuesta = array(
								'title' => '¡Ooops!',
								'msgtype' => 'error',
								'msg' => 'El archivo viene mal',
							);
						}
					}
					// Si el archivo se subió con errores
					elseif ($f_file_error !== 0 && $f_file_error !== 4) {
						$respuesta = array(
							'title' => '¡Ooops!',
							'msgtype' => 'error',
							'msg' => 'El archivo viene mal',
						);
					}
				}
			}
		}
    }
    catch (PDOException $e) {
        $error_msgF = $e->getMessage().'. '.$e->getFile().' ('.$e->getLine().')';
        $DuranHelpers->log(FOLDER_LOGS, 'Tinymceprocessimg.php', __LINE__, $error_msgF);
    }
	catch (ErrorException $e) {
		$error_msgF = $e->getMessage().'. '.$e->getFile().' ('.$e->getLine().'). Trace: '.$e->getTraceAsString();
		$DuranHelpers->log(FOLDER_LOGS, 'Tinymceprocessimg.php', __LINE__,$error_msgF);
	}
	catch (DivisionByZeroError $e) {
		$error_msgF = $e->getMessage().'. '.$e->getFile().' ('.$e->getLine().'). Trace: '.$e->getTraceAsString();
		$DuranHelpers->log(FOLDER_LOGS, 'Tinymceprocessimg.php', __LINE__,$error_msgF);
	}
	catch (ArithmeticError $e) {
		$error_msgF = $e->getMessage().'. '.$e->getFile().' ('.$e->getLine().'). Trace: '.$e->getTraceAsString();
		$DuranHelpers->log(FOLDER_LOGS, 'Tinymceprocessimg.php', __LINE__,$error_msgF);
	}
	catch(ArgumentCountError $e) {
		$error_msgF = $e->getMessage().'. '.$e->getFile().' ('.$e->getLine().'). Trace: '.$e->getTraceAsString();
		$DuranHelpers->log(FOLDER_LOGS, 'Tinymceprocessimg.php', __LINE__,$error_msgF);
	}
	catch(ParseError $e) {
		$error_msgF = $e->getMessage().'. '.$e->getFile().' ('.$e->getLine().'). Trace: '.$e->getTraceAsString();
		$DuranHelpers->log(FOLDER_LOGS, 'Tinymceprocessimg.php', __LINE__,$error_msgF);
	}
	catch(TypeError $e) {
		$error_msgF = $e->getMessage().'. '.$e->getFile().' ('.$e->getLine().'). Trace: '.$e->getTraceAsString();
		$DuranHelpers->log(FOLDER_LOGS, 'Tinymceprocessimg.php', __LINE__,$error_msgF);
	}
	catch(JsonException $e) {
		$error_msgF = $e->getMessage().'. '.$e->getFile().' ('.$e->getLine().'). Trace: '.$e->getTraceAsString();
		$DuranHelpers->log(FOLDER_LOGS, 'Tinymceprocessimg.php', __LINE__,$error_msgF);
	}
	catch(Throwable $e) {
		$error_msgF = $e->getMessage().'. '.$e->getFile().' ('.$e->getLine().'). Trace: '.$e->getTraceAsString();
		$DuranHelpers->log(FOLDER_LOGS, 'Tinymceprocessimg.php', __LINE__,$error_msgF);
	}
	catch(Exception $e) {
		$error_msgF = $e->getMessage().'. '.$e->getFile().' ('.$e->getLine().'). Trace: '.$e->getTraceAsString();
		$DuranHelpers->log(FOLDER_LOGS, 'Tinymceprocessimg.php', __LINE__,$error_msgF);
	}
	catch(Error $e) {
		$error_msgF = $e->getMessage().'. '.$e->getFile().' ('.$e->getLine().'). Trace: '.$e->getTraceAsString();
		$DuranHelpers->log(FOLDER_LOGS, 'Tinymceprocessimg.php', __LINE__,$error_msgF);
	}

	echo json_encode($respuesta);
