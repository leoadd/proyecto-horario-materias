-- Base de datos para Sistema de Selección de Materias
-- Ejecutar este script en phpMyAdmin de InfinityFree
-- Tabla de estudiantes
CREATE TABLE estudiantes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    semestre INT NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de materias
CREATE TABLE materias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    horario VARCHAR(50) NOT NULL,
    profesor VARCHAR(100) NOT NULL,
    creditos INT DEFAULT 3
);

-- Tabla de inscripciones (relación estudiante-materia)
CREATE TABLE inscripciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    estudiante_id INT NOT NULL,
    materia_id INT NOT NULL,
    fecha_inscripcion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (estudiante_id) REFERENCES estudiantes(id) ON DELETE CASCADE,
    FOREIGN KEY (materia_id) REFERENCES materias(id) ON DELETE CASCADE
);

-- Insertar las materias predefinidas
INSERT INTO materias (nombre, horario, profesor) VALUES
('CMMI', 'Lunes 8:00-10:00', 'Dr. García'),
('Admin Redes', 'Martes 10:00-12:00', 'Ing. López'),
('Programación Web', 'Miércoles 8:00-10:00', 'Lic. Martínez'),
('Programación Lógica', 'Jueves 10:00-12:00', 'Dr. Rodríguez'),
('Arquitectura de Computadoras', 'Viernes 8:00-10:00', 'Ing. Hernández'),
('Taller de Investigación', 'Viernes 10:00-12:00', 'Dra. Pérez');