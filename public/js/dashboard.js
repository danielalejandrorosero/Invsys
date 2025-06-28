// === INICIO: Código movido desde dashboard.php ===
// Función para alternar la barra lateral
function toggleSidebar() {
    document.querySelector('.sidebar').classList.toggle('show');
}

// Función para verificar el ancho de la pantalla
function checkScreenSize() {
    if (window.innerWidth <= 768) {
        document.querySelector('.sidebar').classList.remove('show');
    } else {
        document.querySelector('.sidebar').classList.add('show');
    }
}

// Inicialización cuando el DOM está listo

document.addEventListener('DOMContentLoaded', function () {
    // Inicializar componentes de Materialize
    if (window.M && M.AutoInit) M.AutoInit();
    
    // Mostrar modal de alertas si hay productos con bajo stock
    if (typeof productosBajoStock !== 'undefined' && productosBajoStock && productosBajoStock.length > 0 && typeof alertaStockMostrada !== 'undefined' && alertaStockMostrada === false) {
        setTimeout(function() {
            showAlertModal();
            fetch('marcar_alerta_stock.php');
        }, 2000);
    }
    
    // Detectar tamaño de pantalla al cargar
    checkScreenSize();
    
    // Detectar cambios en el tamaño de la pantalla
    window.addEventListener('resize', checkScreenSize);
    
    // Inicializar gráfico de resumen
    if (typeof totalProductos !== 'undefined' && typeof stockBajo !== 'undefined' && typeof transferencias !== 'undefined' && document.getElementById('chart-container')) {
        const options = {
            series: [totalProductos - stockBajo, stockBajo, transferencias],
            labels: ['Productos con stock normal', 'Productos con stock bajo', 'Transferencias pendientes'],
            chart: {
                type: 'donut',
                height: 250,
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    animateGradually: {
                        enabled: true,
                        delay: 150
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 350
                    }
                }
            },
            colors: ['#4caf50', '#ff9800', '#2196f3'],
            legend: {
                position: 'bottom',
                formatter: function(seriesName, opts) {
                    return [seriesName, ': ', opts.w.globals.series[opts.seriesIndex]].join('')
                }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        height: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }],
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + " unidades";
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function (val, opts) {
                    return opts.w.globals.series[opts.seriesIndex]
                }
            }
        };
        const chart = new ApexCharts(document.getElementById('chart-container'), options);
        chart.render();
    }
    
    // Verificar si la imagen se actualizó
    if (window.location.search.includes('img_updated')) {
        if (window.M && M.toast) {
            M.toast({
                html: '<i class="fas fa-check-circle"></i> ¡Imagen de perfil actualizada correctamente!',
                displayLength: 3000
            });
        }
        // Limpiar la URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});

// Función para mostrar el modal de alertas
function showAlertModal() {
    document.getElementById('alertModal').style.display = 'flex';
}

// Función para cerrar el modal de alertas
function closeAlertModal() {
    document.getElementById('alertModal').style.display = 'none';
}

// Cerrar modal al hacer clic fuera de él

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('alert-modal')) {
        closeAlertModal();
    }
});

// Cerrar modal con ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAlertModal();
    }
});
