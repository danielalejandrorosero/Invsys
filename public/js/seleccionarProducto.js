
document.addEventListener('DOMContentLoaded', function() {
    // Filter products based on search input
    const searchInput = document.getElementById('searchProduct');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const productOptions = document.querySelectorAll('.product-option');

            productOptions.forEach(option => {
                const productName = option.querySelector('.product-name').textContent.toLowerCase();
                const productInfo = option.querySelector('.product-info').textContent.toLowerCase();

                if (productName.includes(searchTerm) || productInfo.includes(searchTerm)) {
                    option.style.display = 'flex';
                } else {
                    option.style.display = 'none';
                }
            });
        });
    }

    // Handle product selection
    const productOptions = document.querySelectorAll('.product-option');
    const productSelect = document.getElementById('id_producto');

    productOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove selected class from all options
            productOptions.forEach(opt => opt.classList.remove('selected'));

            // Add selected class to clicked option
            this.classList.add('selected');

            // Update hidden select value
            const productId = this.getAttribute('data-id');
            productSelect.value = productId;
        });
    });
});
