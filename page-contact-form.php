<?php
/*
 * Template Name: Formulario Fijo para Adaia
 */

// Incluir ficherod e configuración y librerías
require 'cfg.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Incluye las dependencias de Google Cloud con Composer
use Google\Cloud\RecaptchaEnterprise\V1\RecaptchaEnterpriseServiceClient;
use Google\Cloud\RecaptchaEnterprise\V1\Event;
use Google\Cloud\RecaptchaEnterprise\V1\Assessment;
use Google\Cloud\RecaptchaEnterprise\V1\TokenProperties\InvalidReason;


/**
 * Guarda contenido en un archivo dentro de un directorio estructurado por año y mes.
 *
 * Esta función crea un directorio con la estructura "DEFINE/año/mes" si no existe
 * y guarda el contenido en un archivo cuyo nombre está basado en la fecha y hora
 * actual en formato ISO 8601. En caso de fallo, retorna false sin generar errores visibles.
 *
 * Notas:
 * - Los nombres de archivo reemplazan caracteres no válidos como ':' y '/' para evitar conflictos.
 * - El directorio base es BASE_STORAGE_PATH, y la estructura sigue el formato "DEFINE/año/mes".
 *
 * Ejemplo de uso:
 * $path = saveToFile("Contenido de prueba.");
 * if ($path) {
 * echo "Archivo guardado en: $path";
 * } else {
 * echo "Error al guardar el archivo.";
 * }
 *
 * @param string $content
 *        	El contenido que se desea guardar en el archivo.
 *        	
 * @return string|false La ruta completa del archivo creado en caso de éxito, o false si falla.
 *        
 *         Funcionalidad:
 *         - Verifica si el directorio especificado existe; si no, lo crea.
 *         - Genera un nombre único de archivo basado en la fecha y hora actual.
 *         - Guarda el contenido en el archivo dentro del directorio correspondiente.
 *         - Maneja errores de manera silenciosa (sin excepciones ni advertencias).
 *        
 *        
 */
function saveToFile ($content)
{

	// Obtén el año y el mes actual
	$year = date ("Y");
	$month = date ("m");

	// Construye la ruta completa del directorio
	$directory = BASE_STORAGE_PATH . "/$year/$month";

	// Crea el directorio si no existe, con permisos 0755
	if (! is_dir ($directory))
	{
		if (! mkdir ($directory, 0755, true))
		{
			return false; // Fallo al crear el directorio
		}
	}

	// Genera el nombre del archivo basado en la fecha y hora actual
	$fileName = date ("c") . ".txt"; // ISO 8601 (incluye fecha, hora y zona horaria)
	$fileName = str_replace ([ ':', '/'], '-', $fileName); // Sustituir caracteres no válidos en nombres de archivo

	// Construye la ruta completa del archivo
	$filePath = "$directory/$fileName";

	// Guarda el contenido en el archivo
	if (file_put_contents ($filePath, $content) === false)
	{
		return false; // Fallo al escribir en el archivo
	}

	// Retorna la ruta del archivo creado
	return $filePath;
}


/**
 * Crea una evaluación para analizar el riesgo de una acción de la IU.
 *
 * @param string $recaptchaKey
 *        	La clave reCAPTCHA asociada con el sitio o la aplicación
 * @param string $token
 *        	El token generado obtenido del cliente.
 * @param string $project
 *        	El ID del proyecto de Google Cloud.
 * @param string $action
 *        	El nombre de la acción que corresponde al token.
 * @return float with the spam score or mensaje de error
 */
function checkRecaptcha ()
{
	$recaptchaKey = RECAPTCHA_SITE_KEY;
	$project = RECAPTCHA_PROJECT;
	$token = $_POST ['g-recaptcha-response'];
	$action = 'SUBMIT_CONTACT_FORM';

	putenv ('GOOGLE_APPLICATION_CREDENTIALS=' . GOOGLE_APPLICATION_CREDENTIALS);

	$retVal = 'Fallo';

	try
	{
		// TODO: almacena en caché el código de generación de clientes (recomendado) o llama a client.close() antes de salir del método.
		// Crea el cliente de reCAPTCHA.
		$client = new RecaptchaEnterpriseServiceClient ();
		$projectName = $client->projectName ($project);

		// Establece las propiedades del evento para realizar un seguimiento.
		$event = (new Event ())->setSiteKey ($recaptchaKey)->setToken ($token);

		// Crea la solicitud de evaluación.
		$assessment = (new Assessment ())->setEvent ($event);

		$response = $client->createAssessment ($projectName, $assessment);

		// Verifica si el token es válido.
		if ($response->getTokenProperties ()->getValid () == false)
		{
			// The CreateAssessment() call failed because the token was invalid for the following reason:
			$retVal = InvalidReason::name ($response->getTokenProperties ()->getInvalidReason ());
		}

		// Verifica si se ejecutó la acción esperada.
		if ($response->getTokenProperties ()->getAction () == $action)
		{
			// Obtén la puntuación de riesgo y los motivos.
			// Para obtener más información sobre cómo interpretar la evaluación, consulta:
			// https://cloud.google.com/recaptcha-enterprise/docs/interpret-assessment
			// The score for the protection action is:
			$retVal = $response->getRiskAnalysis ()->getScore ();
		}
		else
		{
			$retVal = 'The action attribute in your reCAPTCHA tag does not match the action you are expecting to score';
		}

		$client->close ();
	}
	catch (exception $e)
	{
		$retVal = 'CreateAssessment() call failed with the following error: ' . $e;
	}

	return $retVal;
}


/**
 * Envía un correo electrónico utilizando PHPMailer.
 *
 * @param string $contents
 *        	El contenido del correo electrónico.
 * @return bool|string Retorna true si el correo se envió correctamente, o un mensaje de error en caso contrario.
 */
function enviarCorreo ($contents): bool
{
	$mail = new PHPMailer (true);

	try
	{
		// Configuración del servidor SMTP
		$mail->isSMTP ();

		// SMTP::DEBUG_OFF = off (for production use)
		// SMTP::DEBUG_CLIENT = client messages
		// SMTP::DEBUG_SERVER = client and server messages
		$mail->SMTPDebug = SMTP::DEBUG_OFF;

		$mail->Host = SMTP_HOST;
		$mail->Port = SMTP_PORT;
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
		$mail->SMTPAuth = true;

		$mail->Username = SMTP_USERNAME;
		$mail->Password = SMTP_PASSWORD;

		// Configuración del correo
		$mail->setFrom (SMTP_FROM_EMAIL, SMTP_FROM_NAME);
		$destinatarios = explode (";", TO_EMAIL);
		foreach ($destinatarios as $sendTo)
		{
			$mail->addAddress ($sendTo);
		}
		$mail->Subject = 'Nuevo mensaje del formulario de contacto';
		$mail->msgHTML ($contents);

		// Enviamos el correo
		if (! $mail->send ())
		{
			return 'Mailer Error: ' . $mail->ErrorInfo;
		}
		else
		{
			return true;
		}
	}
	catch (Exception $e)
	{
		error_log ('Error al enviar el correo: ' . $mail->ErrorInfo);
		return false;
	}
}


/**
 * Primera Fase: Muestra el formulario de contacto sin CAPTCHA.
 *
 * @return string HTML del formulario de contacto.
 */
function showFormWithoutCaptcha ($actionUrl)
{

	// Formulario sin recaptcha en primera fase
	return '<form id="adaiaContactForm" action="' . $actionUrl . '" method="post">
        <label for="name">Tu nombre:</label>
        <input type="text" name="adaiaName" id="name" required><br><br>
			
        <label for="email">Tu Email:</label>
        <input type="email" name="adaiaEmail" id="email" required><br><br>
			
        <label for="message">Tu mensaje:</label>
        <textarea name="adaiaMessage" id="message" rows="5" required></textarea><br><br>
			
        <label for="emailBC" id="lbl_emailBC">Repite el Email:</label>
        <input type="email" name="emailBC" id="emailBC"><br><br>
			
        <label>
            <input type="checkbox" name="privacy_policy" required>
            Acepto la <a href="/legal/politica-de-privacidad/" target="_blank">política de privacidad</a>.
        </label><br><br>
			
        <button id="submitContactForm">Enviar</button>
    </form>';
}


/**
 * Segunda Fase: Muestra el formulario con CAPTCHA.
 * Autoenvia el formulario una vez haya obtenido el cptcha
 *
 * @param string $actionUrl
 *        	URL de acción del formulario.
 * @return string HTML del formulario con CAPTCHA.
 */
function showFormWithCaptcha ($actionUrl)
{
	$retVal = '<p>Comprobando robots…</p>
				<form id="captchaForm" action="' . $actionUrl . '" method="post">';
	foreach ($_POST as $key => $value)
	{
		$val = htmlspecialchars ($value, ENT_QUOTES);
		$retVal .= "<input type='hidden' name='{$key}' value='{$val}'>" . PHP_EOL;
	}
	$retVal .= '<input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response" value="">
                </form>';
	$retVal .= '<script src="https://www.google.com/recaptcha/enterprise.js?render=' . RECAPTCHA_SITE_KEY . '" async defer></script>';

	$retVal .= '<script>
grecaptcha.enterprise.ready(function() {
            grecaptcha.enterprise.execute("' . RECAPTCHA_SITE_KEY . '", {action: "SUBMIT_CONTACT_FORM"})
                .then(function(token) {
                    document.getElementById("g-recaptcha-response").value = token;
                    document.getElementById("captchaForm").submit();
                });
        });
</script>';

	return $retVal;
}

/*
 * ------------------------------------------------------------------------------------------------------------------------------------------------
 * Aqui va el flujo de la página
 * ------------------------------------------------------------------------------------------------------------------------------------------------
 */

// Variable para almacenar mensajes de estado
$statusMessage = '';
$statusClass = '';
$showForm = true;
$showCaptchaFrom = false;

$actionUrl = esc_url (home_url ('/contacto/'));
if (isset ($GLOBALS ['currentPage']))
{
	$actionUrl = esc_url (home_url ('/' . $GLOBALS ['currentPage']->post_name . '/'));
}

// Procesamiento del formulario
if ($_SERVER ['REQUEST_METHOD'] === 'POST')
{
	if (! isset ($_POST ['g-recaptcha-response']))
	{
		// Si estamos en POST y no hay campo g-recaptcha-response, pasamos a segunda fase
		$showCaptchaFrom = true;
	}
	else if (! empty ($_POST ['emailBC']))
	{
		// Ha caido en el Honeypot
		$statusMessage = 'Mensaje enviado correctamente';
		$statusClass = 'success';
		$showForm = false;
	}
	else if (empty ($_POST ['g-recaptcha-response']))
	{
		// Si hay campo g-recaptcha-response pero está vacío, no hemos pasado el captcha
		$statusMessage = 'Por favor, verifica el CAPTCHA.';
		$statusClass = 'sendError';
	}
	else if (! isset ($_POST ['privacy_policy']))
	{ // Validación del checkbox
		$statusMessage = 'Debe aceptar la política de privacidad para enviar el formulario.';
		$statusClass = 'sendError';
	}
	else
	{
		// AQui Tenemos un captcha y hemos aceptado la política de privacidad... procedemos a verificar el cptcha
		$score = checkRecaptcha ();

		$body = '<table>';
		$body .= '<tr><th>Centro</th><td>' . ADAIA_CENTRO . '</td></tr>';
		$body .= '<tr><th>Nombre</th><td>' . $_POST ['adaiaName'] . '</td></tr>';
		$body .= '<tr><th>Mensaje</th><td>' . nl2br ($_POST ['adaiaMessage']) . '</td></tr>';
		$body .= '<tr><th>Email</th><td>' . $_POST ['adaiaEmail'] . '</td></tr>';
		$body .= '<tr><th>SPAM score</th><td>' . $score . '</td></tr>';
		$body .= '</table>';

		// Si ha fallado el captcha recibiremos una cadena
		if (! is_float ($score))
		{
			// $response = $score;
			$statusClass = 'sendError';
		}
		else if ($score >= 0.6)
		{
			// Podemos recibir 0.1, 0.3, 0.7, 0.9: vamos a considerar en la primera versión que 0.7 y 0.9 son válidos
			saveToFile ($body);
			// $result = enviarCorreo ($body);
			enviarCorreo ($body);

			// TODO: analizar el resultado de envioi de correo

			$statusMessage = '¡Mensaje enviado correctamente!';
			$statusClass = 'success';
			$showForm = false;
		}
		else
		{
			$statusMessage = 'Error de verificación del CAPTCHA.';
			$statusClass = 'sendError';
			$showForm = false;
			saveToFile ($body);
		}
	}
}

get_header ();

// MAYBE: Mostrar imagen destacada
// var_dump ($showForm);
echo '<div class="container" id="contacto"><div class="content"><h2>' . get_the_title () . '</h2>';

// Mostrar el contenido de la página desde el editor de WordPress
if (isset ($GLOBALS ['currentPage']))
{
	echo apply_filters ('the_content', $GLOBALS ['currentPage']->post_content);
}
else
{
	while (have_posts ())
	{
		the_post ();
		the_content ();
	}
}

echo '<span class="lnk-form">';

if ($statusMessage !== '')
{
	echo "<p class=\"$statusClass\">$statusMessage</p>";
}

if ($showCaptchaFrom)
{
	echo showFormWithCaptcha ($actionUrl);
}
else if ($showForm)
{
	echo showFormWithoutCaptcha ($actionUrl);
}

echo '</span></div></div>';
get_footer ();
