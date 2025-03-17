<link rel="stylesheet" href="assets/css/stock-alerts.css">
<script>
    // Variable global para determinar el nivel de usuario
    var userLevel = <?php echo isset($_SESSION['nivel_usuario']) ? $_SESSION['nivel_usuario'] : '0'; ?>;
</script>
<script src="assets/js/stock-alerts.js"></script>