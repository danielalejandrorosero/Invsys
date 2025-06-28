document.addEventListener('DOMContentLoaded', function() {
    // Initialize Materialize components
    initializeMaterializeComponents();
    
    // Toggle search panel
    initializeSearchToggle();
    
    // Clear search form
    initializeClearButton();
    
    // Table search filter
    initializeTableFilter();
});

/**
 * Initialize Materialize CSS components
 */
function initializeMaterializeComponents() {
    // Initialize select elements
    var elems = document.querySelectorAll('select');
    M.FormSelect.init(elems);
}

/**
 * Initialize search panel toggle functionality
 */
function initializeSearchToggle() {
    const searchHeader = document.querySelector('.search-header');
    const searchCard = document.querySelector('.search-card');

    if (searchHeader && searchCard) {
        searchHeader.addEventListener('click', function() {
            searchCard.classList.toggle('expanded');
            
            // Toggle chevron icon
            const chevronIcon = searchHeader.querySelector('.toggle-icon i');
            if (chevronIcon) {
                chevronIcon.classList.toggle('fa-chevron-up');
                chevronIcon.classList.toggle('fa-chevron-down');
            }
        });
    }
}

/**
 * Initialize clear search button functionality
 */
function initializeClearButton() {
    const clearBtn = document.getElementById('clearSearch');
    
    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            // Clear all form inputs and selects
            document.querySelectorAll('form input, form select').forEach(input => {
                input.value = '';
                
                // For Materialize selects, trigger change event and reinitialize
                if (input.tagName === 'SELECT') {
                    M.FormSelect.init(input);
                }
                
                // For inputs with labels, remove active class
                if (input.tagName === 'INPUT') {
                    const label = input.nextElementSibling;
                    if (label && label.tagName === 'LABEL') {
                        label.classList.remove('active');
                    }
                }
            });
            
            // Focus on first input
            const firstInput = document.querySelector('form input[type="text"]');
            if (firstInput) {
                firstInput.focus();
            }
        });
    }
}

/**
 * Initialize table filter functionality
 */
function initializeTableFilter() {
    const tableSearch = document.getElementById('tableSearch');
    
    if (tableSearch) {
        tableSearch.addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('table tbody tr');
            let visibleCount = 0;

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const isVisible = text.includes(searchValue);
                
                row.style.display = isVisible ? '' : 'none';
                
                if (isVisible) {
                    visibleCount++;
                }
            });
            
            // Update visible count
            updateVisibleCount(visibleCount);
        });
    }
}

/**
 * Update the visible products count
 * @param {number} count - Number of visible products
 */
function updateVisibleCount(count) {
    const countElement = document.querySelector('.col.s12.m6 strong');
    if (countElement) {
        countElement.textContent = count;
    }
}

/**
 * Handle form submission with validation
 */
function handleFormSubmission() {
    const form = document.querySelector('form');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            // Check if at least one field has a value
            const inputs = form.querySelectorAll('input[type="text"], select');
            let hasValue = false;
            
            inputs.forEach(input => {
                if (input.value.trim() !== '') {
                    hasValue = true;
                }
            });
            
            if (!hasValue) {
                e.preventDefault();
                M.toast({html: 'Por favor, ingrese al menos un criterio de búsqueda', classes: 'orange'});
                return false;
            }
        });
    }
}

/**
 * Add confirmation to delete buttons
 */
function initializeDeleteConfirmation() {
    const deleteButtons = document.querySelectorAll('a[title="Eliminar producto"]');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('¿Está seguro que desea eliminar este producto?')) {
                e.preventDefault();
                return false;
            }
        });
    });
}

/**
 * Initialize tooltips for action buttons
 */
function initializeTooltips() {
    const tooltipElements = document.querySelectorAll('[title]');
    
    tooltipElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            // Add hover effect or show custom tooltip if needed
        });
    });
}