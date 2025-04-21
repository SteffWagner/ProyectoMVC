document.addEventListener('DOMContentLoaded', function() {
    var periodForm = document.getElementById('periodForm');
    var reportResults = document.getElementById('reportResults');

    periodForm.addEventListener('submit', function(event) {
        event.preventDefault();
    
        var start = document.getElementById('start').value;
        var end = document.getElementById('end').value;
    
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'generar_reporte.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var proyectos = JSON.parse(xhr.responseText);
                mostrarResultados(proyectos);
            }
        };
        xhr.send('start=' + start + '&end=' + end); // Enviar datos como cadena de consulta
    });

    // Función para mostrar los resultados del reporte en la página
    function mostrarResultados(proyectos) {
        var html = '<h3>Proyectos finalizados por periodo</h3><br>';
        html += '<ul>';
        proyectos.forEach(function(proyecto) {
            html += '<li>' + '<strong> Nombre de Proyecto: </strong> ' + proyecto.Nombre + '<strong>  -  Fecha de finalización: </strong>' + proyecto.Fecha_entrega + '</li><br>';
        });
        html += '</ul>';
        reportResults.innerHTML = html;
    }
});
