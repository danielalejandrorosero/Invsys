document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('select');
    var instances = M.FormSelect.init(elems);
});
document.addEventListener('DOMContentLoaded', function() {
    // Handle warehouse card selection
    const warehouseCards = document.querySelectorAll('.warehouse-card');
    const warehouseRadios = document.querySelectorAll('.warehouse-radio');

    warehouseCards.forEach(card => {
        card.addEventListener('click', function() {
            // Find the radio input inside this card
            const radio = this.querySelector('.warehouse-radio');

            // Check the radio
            radio.checked = true;

            // Remove selected class from all cards
            warehouseCards.forEach(c => c.classList.remove('selected'));

            // Add selected class to clicked card
            this.classList.add('selected');
        });
    });
});
