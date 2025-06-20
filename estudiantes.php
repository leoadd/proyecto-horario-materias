<?php
require_once 'config.php';

$mensaje = '';

// Eliminar estudiante
if (isset($_GET['eliminar'])) {
    $estudiante_id = $_GET['eliminar'];
    try {
        eliminarEstudiante($pdo, $estudiante_id);
        $mensaje = '<div class="alert alert-success">Estudiante eliminado exitosamente</div>';
    } catch (Exception $e) {
        $mensaje = '<div class="alert alert-error">Error al eliminar: ' . $e->getMessage() . '</div>';
    }
}

$estudiantes = obtenerEstudiantes($pdo);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudiantes Inscritos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👥 Estudiantes Inscritos</h1>
            <p>Lista de todos los estudiantes registrados</p>
        </div>

        <div class="content">
            <div class="nav-buttons">
                <a href="index.php" class="btn">📝 Registrar</a>
                <a href="estudiantes.php" class="btn btn-active">👥 Ver Estudiantes</a>
            </div>

            <?php echo $mensaje; ?>

            <?php if (empty($estudiantes)): ?>
                <div class="alert alert-info">
                    No hay estudiantes registrados aún.
                </div>
            <?php else: ?>
                <div class="estudiantes-grid">
                    <?php foreach($estudiantes as $estudiante): ?>
                        <div class="estudiante-card">
                            <div class="estudiante-info">
                                <h3><?php echo htmlspecialchars($estudiante['nombre']); ?></h3>
                                <p><strong>Semestre:</strong> <?php echo $estudiante['semestre']; ?>°</p>
                                <p><strong>Fecha de registro:</strong> <?php echo date('d/m/Y', strtotime($estudiante['fecha_registro'])); ?></p>
                                
                                <div class="materias-inscritas">
                                    <h4>Materias inscritas:</h4>
                                    <?php 
                                    $inscripciones = obtenerInscripcionesEstudiante($pdo, $estudiante['id']);
                                    if (empty($inscripciones)): ?>
                                        <p><em>Sin materias inscritas</em></p>
                                    <?php else: ?>
                                        <ul>
                                            <?php foreach($inscripciones as $materia): ?>
                                                <li>
                                                    <strong><?php echo htmlspecialchars($materia['nombre']); ?></strong><br>
                                                    <small><?php echo htmlspecialchars($materia['horario']); ?> - <?php echo htmlspecialchars($materia['profesor']); ?></small>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="estudiante-actions">
                                <a href="horario.php?id=<?php echo $estudiante['id']; ?>" class="btn btn-small">
                                    📋 Ver Horario
                                </a>
                                <a href="?eliminar=<?php echo $estudiante['id']; ?>" 
                                   class="btn btn-small btn-danger"
                                   onclick="return confirm('¿Estás seguro de eliminar este estudiante?')">
                                    🗑️ Eliminar
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>