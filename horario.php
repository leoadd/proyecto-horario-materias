<?php
require_once 'config.php';

if (!isset($_GET['id'])) {
    header('Location: estudiantes.php');
    exit;
}

$estudiante_id = $_GET['id'];

// Obtener datos del estudiante
$stmt = $pdo->prepare("SELECT * FROM estudiantes WHERE id = ?");
$stmt->execute([$estudiante_id]);
$estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$estudiante) {
    header('Location: estudiantes.php');
    exit;
}

$inscripciones = obtenerInscripcionesEstudiante($pdo, $estudiante_id);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horario de <?php echo htmlspecialchars($estudiante['nombre']); ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="header no-print">
            <h1>📅 Horario de Clases</h1>
            <p>Horario personalizado del estudiante</p>
        </div>

        <div class="content">
            <div class="nav-buttons no-print">
                <a href="index.php" class="btn">📝 Registrar</a>
                <a href="estudiantes.php" class="btn">👥 Ver Estudiantes</a>
                <button onclick="window.print()" class="btn btn-print">🖨️ Imprimir</button>
            </div>

            <div class="horario-completo">
                <div class="horario-header">
                    <h2>📋 Horario de Clases</h2>
                    <div class="estudiante-datos">
                        <p><strong>Estudiante:</strong> <?php echo htmlspecialchars($estudiante['nombre']); ?></p>
                        <p><strong>Semestre:</strong> <?php echo $estudiante['semestre']; ?>°</p>
                        <p><strong>Fecha de impresión:</strong> <?php echo date('d/m/Y'); ?></p>
                    </div>
                </div>

                <?php if (empty($inscripciones)): ?>
                    <div class="alert alert-info">
                        Este estudiante no tiene materias inscritas.
                    </div>
                <?php else: ?>
                    <table class="horario-table">
                        <thead>
                            <tr>
                                <th>Materia</th>
                                <th>Horario</th>
                                <th>Profesor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($inscripciones as $materia): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($materia['nombre']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($materia['horario']); ?></td>
                                    <td><?php echo htmlspecialchars($materia['profesor']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="horario-resumen">
                        <p><strong>Total de materias inscritas:</strong> <?php echo count($inscripciones); ?></p>
                        <p><strong>Total de créditos:</strong> <?php echo count($inscripciones) * 3; ?></p>
                    </div>

                    <div class="horario-semanal">
                        <h3>📅 Vista Semanal</h3>
                        <div class="semana-grid">
                            <?php
                            $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
                            foreach($dias as $dia):
                                $materias_dia = array_filter($inscripciones, function($m) use ($dia) {
                                    return strpos($m['horario'], $dia) !== false;
                                });
                            ?>
                                <div class="dia-card">
                                    <h4><?php echo $dia; ?></h4>
                                    <?php if (empty($materias_dia)): ?>
                                        <p><em>Sin clases</em></p>
                                    <?php else: ?>
                                        <?php foreach($materias_dia as $materia): ?>
                                            <div class="clase-info">
                                                <strong><?php echo htmlspecialchars($materia['nombre']); ?></strong><br>
                                                <small><?php echo str_replace($dia . ' ', '', $materia['horario']); ?></small>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>