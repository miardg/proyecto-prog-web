<?php


require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require __DIR__ . '/../../includes/PHPMailer/Exception.php';
require __DIR__ . '/../../includes/PHPMailer/PHPMailer.php';
require __DIR__ . '/../../includes/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_login();

$action = $_POST['action'] ?? $_GET['action'] ?? null;
$idClase = $_POST['id_clase'] ?? null;
$motivo = $_POST['motivo'] ?? 'Cancelada por el profesor';
$fecha = $_POST['fecha'] ?? date('Y-m-d');

switch ($action) {
    case 'cancelar':
        //insertamos en la tabla de auditoria de la DB la cancelación de la clase
        $stmt = $conn->prepare("
        INSERT INTO clase_cancelada (id_clase, fecha, motivo)
        VALUES (:id_clase, :fecha, :motivo)
    ");
        $stmt->bindParam(':id_clase', $idClase, PDO::PARAM_INT);
        $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $stmt->bindParam(':motivo', $motivo, PDO::PARAM_STR);
        $stmt->execute();

        //query para traer los inscriptos activos de esa clase en particular 
        $stmtInscriptos = $conn->prepare("
        SELECT u.email, CONCAT(u.nombre, ' ', u.apellido) AS nombre, c.nombre_clase, c.tipo_actividad
        FROM inscripcionclase i
        INNER JOIN socio s ON s.id_socio = i.id_socio
        INNER JOIN usuario u ON u.id_usuario = s.id_usuario
        INNER JOIN clase c ON c.id_clase = i.id_clase
        WHERE i.id_clase = :id_clase
          AND DATE(i.fecha_inscripcion) <= :fecha
          AND u.estado = 'activo'
          AND s.estado_membresia = 'activa'
    ");
        $stmtInscriptos->bindParam(':id_clase', $idClase, PDO::PARAM_INT);
        $stmtInscriptos->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $stmtInscriptos->execute();
        $inscriptos = $stmtInscriptos->fetchAll(PDO::FETCH_ASSOC);

        //configuración del mail para enviar a cada uno de los inscriptos avisando la cancelación
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'kynetikgym@gmail.com';
            $mail->Password = 'qguu eqjo xdon sjus';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('kynetikgym@gmail.com', 'KynetikGym');
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            foreach ($inscriptos as $inscripto) {
                $mail->clearAddresses();
                $mail->addAddress($inscripto['email'], $inscripto['nombre']);
                $mail->Subject = "Clase cancelada: {$inscripto['nombre_clase']} ({$inscripto['tipo_actividad']})";
                $mail->Body = "Hola {$inscripto['nombre']},\n\n"
                    . "La clase \"{$inscripto['nombre_clase']} ({$inscripto['tipo_actividad']})\" "
                    . "programada para {$fecha} ha sido cancelada.\n\n"
                    . "Motivo: {$motivo}\n\n"
                    . "Gracias por tu comprensión.\n\n"
                    . "Equipo KynetikGym";
                $mail->send();
            }

            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $mail->ErrorInfo]);
        }
        break;


    case 'asistencia':
        $idClase = intval($_POST['id_clase']);
        $fecha = $_POST['fecha'];
        $asistencias = json_decode($_POST['asistencias'], true);

        foreach ($asistencias as $a) {
            $stmt = $conn->prepare("
        INSERT INTO asistenciaclase (id_clase, id_socio, fecha, asistio)
        VALUES (:id_clase, :id_socio, :fecha, :asistio)
        ON DUPLICATE KEY UPDATE asistio = :asistio
    ");
            $stmt->bindParam(':id_clase', $idClase, PDO::PARAM_INT);
            $stmt->bindParam(':id_socio', $a['id_socio'], PDO::PARAM_INT);
            $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
            $stmt->bindParam(':asistio', $a['asistio'], PDO::PARAM_INT);
            $stmt->execute();
        }

        echo json_encode(['success' => true]);
        break;


    case 'inscribir':
        $idClase = intval($_POST['id_clase']);
        $idSocio = intval($_POST['id_socio']);

        //chequeamos si el socio ya esta inscripto
        $stmtCheck = $conn->prepare("
        SELECT COUNT(*) 
        FROM inscripcionclase 
        WHERE id_clase = :id_clase AND id_socio = :id_socio
    ");
        $stmtCheck->bindParam(':id_clase', $idClase, PDO::PARAM_INT);
        $stmtCheck->bindParam(':id_socio', $idSocio, PDO::PARAM_INT);
        $stmtCheck->execute();

        $yaInscripto = $stmtCheck->fetchColumn();

        if ($yaInscripto) {
            echo json_encode(['success' => false, 'error' => 'El alumno ya está inscripto en esta clase']);
            break;
        }

        //si no esta inscripto, lo inscribimos
        $stmt = $conn->prepare("
        INSERT INTO inscripcionclase (id_clase, id_socio, fecha_inscripcion)
        VALUES (:id_clase, :id_socio, CURDATE())
    ");
        $stmt->bindParam(':id_clase', $idClase, PDO::PARAM_INT);
        $stmt->bindParam(':id_socio', $idSocio, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(['success' => true]);
        break;

    case 'correo_inscriptos':
        $idClase = intval($_POST['id_clase']);
        $fecha = $_POST['fecha'];
        $asunto = trim($_POST['asunto'] ?? '');
        $mensaje = trim($_POST['mensaje'] ?? '');

        if ($asunto === '' || $mensaje === '') {
            echo json_encode(['success' => false, 'error' => 'Faltan asunto o mensaje']);
            break;
        }

        //traemos los inscriptos a la clase desde la que se envio el correo
        $stmt = $conn->prepare("
        SELECT u.email, CONCAT(u.nombre, ' ', u.apellido) AS nombre
        FROM inscripcionclase i
        INNER JOIN socio s ON s.id_socio = i.id_socio
        INNER JOIN usuario u ON u.id_usuario = s.id_usuario
        WHERE i.id_clase = :id_clase
          AND DATE(i.fecha_inscripcion) <= :fecha
          AND u.estado = 'activo'
          AND s.estado_membresia = 'activa'
    ");
        $stmt->bindParam(':id_clase', $idClase, PDO::PARAM_INT);
        $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $stmt->execute();
        $inscriptos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //traemos la info de esa clase para mostrar en el correo
        $stmtClase = $conn->prepare("
        SELECT c.nombre_clase, c.tipo_actividad, c.lugar, c.hora_inicio, c.duracion_min,
               CONCAT(u.nombre, ' ', u.apellido) AS profesor
        FROM clase c
        INNER JOIN usuario u ON u.id_usuario = c.profesor_id
        WHERE c.id_clase = :id_clase
    ");
        $stmtClase->bindParam(':id_clase', $idClase, PDO::PARAM_INT);
        $stmtClase->execute();
        $claseInfo = $stmtClase->fetch(PDO::FETCH_ASSOC);

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'kynetikgym@gmail.com';
            $mail->Password = 'qguu eqjo xdon sjus';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('kynetikgym@gmail.com', 'KynetikGym');
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            foreach ($inscriptos as $inscripto) {
                $mail->clearAddresses();
                $mail->addAddress($inscripto['email'], $inscripto['nombre']);
                $mail->Subject = $asunto;
                $mail->Body = "Hola {$inscripto['nombre']},\n\n"
                    . "{$mensaje}\n\n"
                    . "Detalles de la clase:\n"
                    . "- Nombre: {$claseInfo['nombre_clase']} ({$claseInfo['tipo_actividad']})\n"
                    . "- Fecha: {$fecha}\n"
                    . "- Hora: {$claseInfo['hora_inicio']}\n"
                    . "- Lugar: {$claseInfo['lugar']}\n"
                    . "- Duración: {$claseInfo['duracion_min']} minutos\n"
                    . "- Profesor: {$claseInfo['profesor']}\n\n"
                    . "Equipo KynetikGym";
                $mail->send();
            }

            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $mail->ErrorInfo]);
        }
        break;

    case 'ver_asistencia':
        $idClase = intval($_POST['id_clase'] ?? 0);
        $fecha = $_POST['fecha'] ?? null;

        if (!$idClase || !$fecha) {
            echo json_encode(['error' => 'Faltan parámetros']);
            break;
        }

        //chequeamos si ya existe registro de asistencia para esa clase en particular
        $stmt = $conn->prepare("
        SELECT id_socio, asistio
        FROM asistenciaclase
        WHERE id_clase = :id_clase AND fecha = :fecha
    ");
        $stmt->bindParam(':id_clase', $idClase, PDO::PARAM_INT);
        $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $map = [];
        foreach ($rows as $r) {
            $map[$r['id_socio']] = $r['asistio'];
        }

        //devolvemos el estado depende de la fecha y si se encontro registro
        $hoy = date('Y-m-d');
        if ($fecha < $hoy) {
            $estado = 'pasada';      
        } elseif ($fecha > $hoy) {
            $estado = 'futura';      
        } else {
            $estado = count($map) > 0 ? 'registrada' : 'pendiente';
        }

        echo json_encode([
            'estado' => $estado,
            'asistencias' => $map
        ]);
        break;

    default:
        echo json_encode(['error' => 'Acción no reconocida']);
}
