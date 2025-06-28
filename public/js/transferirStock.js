document.addEventListener('DOMContentLoaded', function() {
    // Inicializar select
    var elems = document.querySelectorAll('select');
    M.FormSelect.init(elems);
    
    // Manejar cambio en el select de productos
    document.getElementById('id_producto').addEventListener('change', function() {
        const productoId = this.value;
        if (productoId) {
            obtenerAlmacenOrigen(productoId);
        }
    });
    
    // Validar que no se elija el mismo almacén de origen y destino
    document.getElementById('transferencia-form').addEventListener('submit', function(e) {
        const idAlmacenOrigen = document.getElementById('id_almacen_origen').value;
        const idAlmacenDestino = document.getElementById('id_almacen_destino').value;
        
        if (idAlmacenOrigen === idAlmacenDestino) {
            e.preventDefault();
            M.toast({html: 'El almacén de origen y destino no pueden ser el mismo', classes: 'red'});
        }
    });

    const warehouseCards = document.querySelectorAll('.warehouse-card');
    const warehouseRadios = document.querySelectorAll('.warehouse-radio');

    if (warehouseCards.length === 0) {
        console.warn('No se encontraron tarjetas de almacén en el DOM.');
    }

    // Restaurar selección previa si existe
    const lastSelected = localStorage.getItem('selectedWarehouse');
    if (lastSelected) {
        warehouseRadios.forEach((radio, idx) => {
            if (radio.value === lastSelected) {
                radio.checked = true;
                warehouseCards[idx].classList.add('selected');
            } else {
                warehouseCards[idx].classList.remove('selected');
            }
        });
    } else {
        // Si no hay selección previa, resalta el que está checked por defecto
        warehouseRadios.forEach((radio, idx) => {
            if (radio.checked) {
                warehouseCards[idx].classList.add('selected');
            } else {
                warehouseCards[idx].classList.remove('selected');
            }
        });
    }

    warehouseCards.forEach((card, idx) => {
        card.addEventListener('click', function() {
            warehouseCards.forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            warehouseRadios[idx].checked = true;
            // Guardar selección en localStorage
            localStorage.setItem('selectedWarehouse', warehouseRadios[idx].value);
        });
    });
});

// Función para obtener el almacén de origen mediante AJAX
function obtenerAlmacenOrigen(productoId) {
    // Crear objeto FormData para enviar datos
    const formData = new FormData();
    formData.append('obtenerAlmacenOrigen', '1');
    formData.append('id_producto', productoId);
    
    // Realizar petición AJAX
    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            // Mostrar mensaje de error
            document.getElementById('error-mensaje').style.display = 'block';
            document.getElementById('almacen-origen-info').style.display = 'none';
            document.getElementById('id_almacen_origen').value = '';
        } else {
            // Mostrar información del almacén de origen
            document.getElementById('error-mensaje').style.display = 'none';
            document.getElementById('almacen-origen-info').style.display = 'block';
            document.getElementById('almacen-origen-info').classList.add('fade-in');
            setTimeout(() => {
                document.getElementById('almacen-origen-info').classList.remove('fade-in');
            }, 700);
            document.getElementById('nombre-almacen-origen').textContent = data.nombre;
            document.getElementById('id_almacen_origen').value = data.id_almacen;
            document.getElementById('stock-disponible').textContent = data.cantidad_disponible;
            
            // Actualizar el máximo del campo cantidad
            document.getElementById('cantidad').max = data.cantidad_disponible;
            document.getElementById('cantidad').placeholder = `Máximo: ${data.cantidad_disponible}`;
            
            // Actualizar opciones del almacén de destino para evitar seleccionar el mismo
            const selectDestino = document.getElementById('id_almacen_destino');
            for (let i = 0; i < selectDestino.options.length; i++) {
                if (selectDestino.options[i].value == data.id_almacen) {
                    selectDestino.options[i].disabled = true;
                } else {
                    selectDestino.options[i].disabled = false;
                }
            }
            // Reinicializar el select de Materialize
            M.FormSelect.init(selectDestino);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        M.toast({html: 'Error al obtener información del almacén', classes: 'red'});
    });
}
