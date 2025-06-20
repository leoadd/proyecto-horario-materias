// Funciones JavaScript para el sistema de materias

// Función para toggle de selección de materias
function toggleMateria(checkbox) {
    const card = checkbox.parentElement;
    if (checkbox.checked) {
        card.classList.add('selected');
        // Animación de selección
        card.style.transform = 'scale(1.02)';
        setTimeout(() => {
            card.style.transform = '';
        }, 200);
    } else {
        card.classList.remove('selected');
    }
}

// Función para confirmar eliminación
function confirmarEliminacion(nombre) {
    return confirm(`¿Estás seguro de que deseas eliminar al estudiante "${nombre}"?\n\nEsta acción no se puede deshacer y eliminará todas sus inscripciones.`);
}

// Función para validar formulario
function validarFormulario() {
    const nombre = document.getElementById('nombre').value.trim();
    const semestre = document.getElementById('semestre').value;
    const materiasSeleccionadas = document.querySelectorAll('input[name="materias[]"]:checked');
    
    let errores = [];
    
    if (!nombre) {
        errores.push('El nombre es obligatorio');
    }
    
    if (!semestre) {
        errores.push('Debes seleccionar un semestre');
    }
    
    if (materiasSeleccionadas.length === 0) {
        errores.push('Debes seleccionar al menos una materia');
    }
    
    if (materiasSeleccionadas.length > 6) {
        errores.push('No puedes seleccionar más de 6 materias');
    }
    
    if (errores.length > 0) {
        alert('Por favor corrige los siguientes errores:\n\n' + errores.join('\n'));
        return false;
    }
    
    return true;
}

// Función para mostrar mensajes de estado
function mostrarMensaje(mensaje, tipo = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${tipo}`;
    alertDiv.textContent = mensaje;
    
    const content = document.querySelector('.content');
    content.insertBefore(alertDiv, content.firstChild);
    
    // Remover el mensaje después de 5 segundos
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.parentNode.removeChild(alertDiv);
        }
    }, 5000);
}

// Función para formatear fechas
function formatearFecha(fecha) {
    const opciones = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    return new Date(fecha).toLocaleDateString('es-ES', opciones);
}

// Función para imprimir horario
function imprimirHorario() {
    // Ocultar elementos no necesarios para la impresión
    const noImprimir = document.querySelectorAll('.no-print');
    noImprimir.forEach(elemento => {
        elemento.style.display = 'none';
    });
    
    // Imprimir
    window.print();
    
    // Restaurar elementos después de imprimir
    setTimeout(() => {
        noImprimir.forEach(elemento => {
            elemento.style.display = '';
        });
    }, 1000);
}

// Función para buscar estudiantes (funcionalidad adicional)
function buscarEstudiante() {
    const input = document.getElementById('buscarInput');
    const filtro = input.value.toLowerCase();
    const cards = document.querySelectorAll('.estudiante-card');
    
    cards.forEach(card => {
        const nombre = card.querySelector('h3').textContent.toLowerCase();
        if (nombre.includes(filtro)) {
            card.style.display = 'block';
            card.style.animation = 'fadeIn 0.3s ease-in';
        } else {
            card.style.display = 'none';
        }
    });
}

// Función para ordenar estudiantes
function ordenarEstudiantes(criterio) {
    const container = document.querySelector('.estudiantes-grid');
    const cards = Array.from(container.querySelectorAll('.estudiante-card'));
    
    cards.sort((a, b) => {
        let valorA, valorB;
        
        switch (criterio) {
            case 'nombre':
                valorA = a.querySelector('h3').textContent.toLowerCase();
                valorB = b.querySelector('h3').textContent.toLowerCase();
                break;
            case 'semestre':
                valorA = parseInt(a.querySelector('p').textContent.match(/\d+/)[0]);
                valorB = parseInt(b.querySelector('p').textContent.match(/\d+/)[0]);
                break;
            case 'fecha':
                valorA = new Date(a.querySelector('p:last-of-type').textContent.split(': ')[1]);
                valorB = new Date(b.querySelector('p:last-of-type').textContent.split(': ')[1]);
                break;
            default:
                return 0;
        }
        
        return valorA > valorB ? 1 : -1;
    });
    
    // Limpiar contenedor y agregar cards ordenadas
    container.innerHTML = '';
    cards.forEach(card => container.appendChild(card));
}

// Función para contar materias por día
function contarMateriasPorDia() {
    const dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
    const conteo = {};
    
    dias.forEach(dia => {
        const materiasDia = document.querySelectorAll(`.dia-card:contains("${dia}") .clase-info`);
        conteo[dia] = materiasDia.length;
    });
    
    return conteo;
}

// Función para validar conflictos de horario
function validarConflictosHorario(materiasSeleccionadas) {
    const horarios = materiasSeleccionadas.map(materia => {
        const card = document.querySelector(`input[value="${materia}"]`).parentElement;
        const horarioTexto = card.querySelector('small').textContent;
        return {
            id: materia,
            horario: horarioTexto
        };
    });
    
    // Verificar si hay conflictos de horario
    const conflictos = [];
    for (let i = 0; i < horarios.length; i++) {
        for (let j = i + 1; j < horarios.length; j++) {
            if (horarios[i].horario === horarios[j].horario) {
                conflictos.push({
                    materia1: horarios[i].id,
                    materia2: horarios[j].id,
                    horario: horarios[i].horario
                });
            }
        }
    }
    
    return conflictos;
}

// Función para calcular carga académica
function calcularCargaAcademica(numeroMaterias) {
    const creditosPorMateria = 3;
    const horasPorCredito = 16;
    
    return {
        totalCreditos: numeroMaterias * creditosPorMateria,
        totalHoras: numeroMaterias * creditosPorMateria * horasPorCredito,
        cargaSemanal: numeroMaterias * 4 // 4 horas por materia por semana
    };
}

// Event listeners cuando se carga el DOM
document.addEventListener('DOMContentLoaded', function() {
    // Agregar validación al formulario si existe
    const formulario = document.querySelector('form');
    if (formulario) {
        formulario.addEventListener('submit', function(e) {
            if (!validarFormulario()) {
                e.preventDefault();
            }
        });
    }
    
    // Agregar efectos hover a las cards
    const cards = document.querySelectorAll('.materia-card, .estudiante-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    });
    
    // Animaciones de entrada
    const elementos = document.querySelectorAll('.materia-card, .estudiante-card, .btn');
    elementos.forEach((elemento, index) => {
        elemento.style.opacity = '0';
        elemento.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            elemento.style.transition = 'all 0.5s ease';
            elemento.style.opacity = '1';
            elemento.style.transform = 'translateY(0)';
        }, index * 100);
    });
});

// Función para modo oscuro (funcionalidad adicional)
function toggleModoOscuro() {
    document.body.classList.toggle('modo-oscuro');
    const esModoOscuro = document.body.classList.contains('modo-oscuro');
    localStorage.setItem('modoOscuro', esModoOscuro);
}

// Cargar preferencia de modo oscuro
if (localStorage.getItem('modoOscuro') === 'true') {
    document.body.classList.add('modo-oscuro');
}

// Función para exportar datos (funcionalidad adicional)
function exportarDatos() {
    const estudiantes = document.querySelectorAll('.estudiante-card');
    const datos = [];
    
    estudiantes.forEach(card => {
        const nombre = card.querySelector('h3').textContent;
        const semestre = card.querySelector('p').textContent.match(/\d+/)[0];
        const materias = Array.from(card.querySelectorAll('.materias-inscritas li')).map(li => li.textContent.trim());
        
        datos.push({
            nombre,
            semestre,
            materias
        });
    });
    
    const blob = new Blob([JSON.stringify(datos, null, 2)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'estudiantes_' + new Date().toISOString().split('T')[0] + '.json';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}