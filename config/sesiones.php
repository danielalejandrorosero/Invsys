        <?php
        session_start();

        class Sesiones {
            private $usuarioEstaAutenticado = false;
            private $session_lifetime = 60; 

            public function __construct() {
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $this->verificarExpiracion();
                $this->inicializarSesionUsuario();
            }

            private function verificarExpiracion() {
                if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $this->session_lifetime) {
                    $this->cerrarSesion();
                    header("Location: ../../Views/usuarios/login.php"); // Ajuste de la ruta de redirección
                    exit();
                }
                $_SESSION['LAST_ACTIVITY'] = time(); // Actualiza el tiempo de actividad
            }

            public function usuarioAutenticado() {
                return $this->usuarioEstaAutenticado;
            }
            

            public function iniciarSesion($id_usuario) {
                $_SESSION['id_usuario'] = $id_usuario;
                $_SESSION['LAST_ACTIVITY'] = time();
                $this->usuarioEstaAutenticado = true;
            }

            private function inicializarSesionUsuario() {
                if (isset($_SESSION['id_usuario'])) {
                    $this->usuarioEstaAutenticado = true;
                }
            }

            public function cerrarSesion() {
                session_unset();
                session_destroy();
            }
        }

        $sesion = new Sesiones();
        ?>
