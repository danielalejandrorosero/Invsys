
document.addEventListener('DOMContentLoaded', function() {
    // Inicialización de componentes de Materialize
    M.AutoInit();
    
    // Mejorar la experiencia de usuario con animaciones
    const metricCards = document.querySelectorAll('.metric-card');
    metricCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        card.style.animation = 'fadeInUp 0.6s ease forwards';
    });
    
    // Funcionalidad del menú desplegable
    const dropdown = document.querySelector('.dropdown');
    const dropdownContent = document.querySelector('.dropdown-content');
    const dropdownTrigger = document.querySelector('.dropdown-trigger');
    
    // Mostrar/ocultar dropdown al hacer clic
    dropdownTrigger.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Toggle del estado activo
        if (dropdown.classList.contains('active')) {
            dropdown.classList.remove('active');
            dropdownContent.style.display = 'none';
        } else {
            dropdown.classList.add('active');
            dropdownContent.style.display = 'block';
        }
    });
    
    // Ocultar dropdown solo al hacer clic fuera del dropdown completo
    document.addEventListener('click', function(e) {
        if (!dropdown.contains(e.target)) {
            // Pequeño retraso para evitar cierre accidental
            setTimeout(() => {
                dropdown.classList.remove('active');
                dropdownContent.style.display = 'none';
            }, 100);
        }
    });
    
    // Prevenir que se cierre al hacer clic dentro del dropdown
    dropdownContent.addEventListener('click', function(e) {
        e.stopPropagation();
    });
    
    // Cerrar dropdown con tecla Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && dropdown.classList.contains('active')) {
            dropdown.classList.remove('active');
            dropdownContent.style.display = 'none';
        }
    });
    
    // Manejar enlaces de exportación
    const exportLinks = document.querySelectorAll('.export-link');
    exportLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Cerrar el dropdown después de seleccionar
            dropdown.classList.remove('active');
            dropdownContent.style.display = 'none';
            
            // Mostrar indicador de carga
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Exportando...';
            this.style.pointerEvents = 'none';
            
            // Simular descarga
            setTimeout(() => {
                window.location.href = this.href;
                
                // Restaurar texto después de un momento
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.style.pointerEvents = 'auto';
                }, 2000);
            }, 500);
        });
    });
    
    // Agregar tooltips informativos
    exportLinks.forEach(link => {
        const formato = link.href.includes('formato=') ? 
            link.href.split('formato=')[1].split('&')[0] : 'csv';
        
        link.title = `Exportar movimientos en formato ${formato.toUpperCase()}`;
    });
});

// Animación CSS para las tarjetas métricas
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Animación para el dropdown */
    .dropdown-content {
        animation: slideDown 0.3s ease-out;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Efecto de hover mejorado para los enlaces */
    .export-link {
        transition: all 0.3s ease;
    }
    
    .export-link:hover {
        transform: translateX(5px);
        background-color: #e3f2fd !important;
    }
`;
document.head.appendChild(style);
