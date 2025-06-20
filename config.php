<?php
// Configuración de base de datos para InfinityFree
// Cambia estos valores por los que te proporcione InfinityFree

$servername = "sql202.infinityfree.com"; // Cambiar por tu servidor
$username = "if0_39275534";  // Cambiar por tu usuario de BD
$password = "FsuDSEDS0Kz"; // Cambiar por tu contraseña de BD  
$dbname = "if0_39275534_se"; // Cambiar por el nombre de tu BD

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Función para obtener todas las materias
function obtenerMaterias($pdo) {
    $stmt = $pdo->query("SELECT * FROM materias ORDER BY nombre");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para obtener todos los estudiantes
function obtenerEstudiantes($pdo) {
    $stmt = $pdo->query("SELECT * FROM estudiantes ORDER BY nombre");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para obtener inscripciones de un estudiante
function obtenerInscripcionesEstudiante($pdo, $estudiante_id) {
    $stmt = $pdo->prepare("
        SELECT m.nombre, m.horario, m.profesor 
        FROM inscripciones i 
        JOIN materias m ON i.materia_id = m.id 
        WHERE i.estudiante_id = ?
    ");
    $stmt->execute([$estudiante_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para insertar estudiante
function insertarEstudiante($pdo, $nombre, $semestre) {
    $stmt = $pdo->prepare("INSERT INTO estudiantes (nombre, semestre) VALUES (?, ?)");
    $stmt->execute([$nombre, $semestre]);
    return $pdo->lastInsertId();
}

// Función para insertar inscripción
function insertarInscripcion($pdo, $estudiante_id, $materia_id) {
    $stmt = $pdo->prepare("INSERT INTO inscripciones (estudiante_id, materia_id) VALUES (?, ?)");
    return $stmt->execute([$estudiante_id, $materia_id]);
}

// Función para eliminar estudiante y sus inscripciones
function eliminarEstudiante($pdo, $estudiante_id) {
    $stmt = $pdo->prepare("DELETE FROM estudiantes WHERE id = ?");
    return $stmt->execute([$estudiante_id]);
}
?>