<?php
require_once 'config.php';

$materias = obtenerMaterias($pdo);
$mensaje = '';

if ($_POST) {
    if (isset($_POST['registrar'])) {
        $nombre = $_POST['nombre'];
        $semestre = $_POST['semestre'];
        $materias_seleccionadas = $_POST['materias'] ?? [];
        
        if (empty($nombre) || empty($semestre) || empty($materias_seleccionadas)) {
            $mensaje = '<div class="alert alert-error">Por favor completa todos los campos</div>';
        } else {
            try {
                $estudiante_id = insertarEstudiante($pdo, $nombre, $semestre);
                
                foreach ($materias_seleccionadas as $materia_id) {
                    insertarInscripcion($pdo, $estudiante_id, $materia_id);
                }
                
                $mensaje = '<div class="alert alert-success">¡Estudiante registrado exitosamente!</div>';
            } catch (Exception $e) {
                $mensaje = '<div class="alert alert-error">Error al registrar: ' . $e->getMessage() . '</div>';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Selección de Materias</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📚 Sistema de Selección de Materias</h1>
            <p>Selecciona tus materias y genera tu horario</p>
        </div>

        <div class="content">
            <div class="nav-buttons">
                <a href="index.php" class="btn btn-active">📝 Registrar</a>
                <a href="estudiantes.php" class="btn">👥 Ver Estudiantes</a>
            </div>

            <?php echo $mensaje; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="nombre">Nombre del Estudiante:</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>

                <div class="form-group">
                    <label for="semestre">Semestre:</label>
                    <select id="semestre" name="semestre" required>
                        <option value="">Selecciona un semestre</option>
                        <?php for($i = 1; $i <= 8; $i++): ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?>° Semestre</option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Selecciona tus materias:</label>
                    <div class="materias-grid">
                        <?php foreach($materias as $materia): ?>
                            <div class="materia-card">
                                <input type="checkbox" id="materia_<?php echo $materia['id']; ?>" 
                                       name="materias[]" value="<?php echo $materia['id']; ?>"
                                       onchange="toggleMateria(this)">
                                <label for="materia_<?php echo $materia['id']; ?>">
                                    <strong><?php echo htmlspecialchars($materia['nombre']); ?></strong><br>
                                    <small><?php echo htmlspecialchars($materia['horario']); ?></small><br>
                                    <small>Prof. <?php echo htmlspecialchars($materia['profesor']); ?></small>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <button type="submit" name="registrar" class="btn btn-primary">
                    📋 Registrar Estudiante
                </button>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>