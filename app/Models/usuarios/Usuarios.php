    <?php

    class Usuario {
        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }


        public function agregarUsuario($nombre, $nombreUsuario, $email, $password, $nivel_usuario) {
            $status = 1;
            $last_login = date('Y-m-d H:i:s');
        
            try {
                $checkGrupo = $this->conn->prepare("SELECT nivel_grupo FROM grupos WHERE nivel_grupo = ?");
                $checkGrupo->bind_param("i", $nivel_usuario);
                $checkGrupo->execute();
                $checkGrupo->store_result();
        
                if ($checkGrupo->num_rows === 0) {
                    return "El nivel de usuario seleccionado no existe.";
                }
                $checkGrupo->close();
        
                $checkUsuario = $this->conn->prepare("SELECT nombreUsuario, email FROM usuarios WHERE nombreUsuario = ? OR email = ?");
                $checkUsuario->bind_param("ss", $nombreUsuario, $email);
                $checkUsuario->execute();
                $checkUsuario->store_result();
        
                if ($checkUsuario->num_rows > 0) {
                    return "El nombre de usuario o email ya están en uso.";
                }
                $checkUsuario->close();
        
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
                $sql = "INSERT INTO usuarios (nombre, nombreUsuario, email, password, status, nivel_usuario, last_login) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $this->conn->prepare($sql);
        
                if (!$stmt) {
                    throw new Exception("Error en la consulta: " . $this->conn->error);
                }
        
                $stmt->bind_param("ssssiis", $nombre, $nombreUsuario, $email, $hashedPassword, $status, $nivel_usuario, $last_login);
                $stmt->execute();
        
                if ($stmt->affected_rows > 0) {
                    return true;
                } else {
                    return "Error al agregar usuario: " . $stmt->error;
                }
            } catch (Exception $e) {
                return $e->getMessage();
            } finally {
                if (isset($stmt) && $stmt !== false) {
                    $stmt->close();
                }
            }
        }
        


    public function editarUsuario($id_usuario, $nombre, $nombreUsuario, $id_usuario_sesion, $nivel_sesion) {
        if (empty($nombre) || empty($nombreUsuario)) {
            return "El nombre y el nombre de usuario no pueden estar vacíos.";
        }

        if ($id_usuario != $id_usuario_sesion && $nivel_sesion != 1) {
            return "No tienes permisos para actualizar este usuario.";
        }

        // Verificar si el nombre de usuario ya existe
        if ($this->nombreUsuarioExiste($nombreUsuario, $id_usuario)) {
            return "El nombre de usuario ya existe.";
        }

        try {
            $sql = "UPDATE usuarios SET nombre = ?, nombreUsuario = ? WHERE id_usuario = ?";
            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Error en la consulta: " . $this->conn->error);
            }

            $stmt->bind_param("ssi", $nombre, $nombreUsuario, $id_usuario);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                return true;
            } else {
                return "No se realizaron cambios o hubo un error: " . $stmt->error;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }

    public function obtenerUsuarioPorId($id_usuario) {
        try {
            $sql = "SELECT * FROM usuarios WHERE id_usuario = ?";
            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Error en la consulta: " . $this->conn->error);
            }

            $stmt->bind_param("i", $id_usuario);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows > 0) {
                return $resultado->fetch_assoc();
            } else {
                return null;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }

    public function nombreUsuarioExiste($nombreUsuario, $id_usuario = null) {
        try {
            $sql = "SELECT id_usuario FROM usuarios WHERE nombreUsuario = ?";
            if ($id_usuario !== null) {
                $sql .= " AND id_usuario != ?";
            }

            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Error en la consulta: " . $this->conn->error);
            }

            if ($id_usuario !== null) {
                $stmt->bind_param("si", $nombreUsuario, $id_usuario);
            } else {
                $stmt->bind_param("s", $nombreUsuario);
            }

            $stmt->execute();
            $resultado = $stmt->get_result();

            return $resultado->num_rows > 0;
        } catch (Exception $e) {
            return false;
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }
    
        public function actualizarPassword($id_usuario, $password, $id_usuario_sesion, $nivel_sesion) {
            if (empty($password)) {
                return "La contraseña no puede estar vacía.";
            }
    
            if ($id_usuario != $id_usuario_sesion && $nivel_sesion != 1) {
                return "No tienes permisos para actualizar la contraseña de este usuario.";
            }
    
            try {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $sql = "UPDATE usuarios SET password = ? WHERE id_usuario = ?";
                $stmt = $this->conn->prepare($sql);
    
                if (!$stmt) {
                    throw new Exception("Error en la consulta: " . $this->conn->error);
                }
    
                $stmt->bind_param("si", $hashedPassword, $id_usuario);
                $stmt->execute();
    
                if ($stmt->affected_rows > 0) {
                    return true;
                } else {
                    return "No se realizaron cambios o hubo un error: " . $stmt->error;
                }
            } catch (Exception $e) {
                return $e->getMessage();
            } finally {
                if (isset($stmt) && $stmt !== false) {
                    $stmt->close();
                }
            }
        }
        


        // iniciar sesión
        public function verificarCredenciales($nombreUsuario, $password) {
            try {
                $stmt = $this->conn->prepare("SELECT id_usuario, nombreUsuario, password, nivel_usuario FROM usuarios WHERE nombreUsuario = ?");
                $stmt->bind_param("s", $nombreUsuario);
                $stmt->execute();
                $resultado = $stmt->get_result();
                $usuario = $resultado->fetch_assoc();
                $stmt->close();
        
                if (!$usuario) {
                    return false; // Usuario no encontrado
                }
        
                if (!password_verify($password, $usuario['password'])) {
                    return false; // Contraseña incorrecta
                }
        
                return $usuario; // Usuario autenticado
        
            } catch (Exception $e) {
                return false; // Error en la base de datos
            }
        }

        // solicitar recuperación de contra
        public function generarToken($correo) {
            try {
                $stmt = $this->conn->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
                $stmt->bind_param("s", $correo);
                $stmt->execute();
                $resultado = $stmt->get_result();
    
                if ($resultado->num_rows > 0) {
                    $usuario = $resultado->fetch_assoc();
    
                    // Limpiar el token anterior
                    $stmt = $this->conn->prepare("UPDATE usuarios SET token_recuperacion = NULL, expira_token = NULL WHERE id_usuario = ?");
                    $stmt->bind_param("i", $usuario['id_usuario']);
                    $stmt->execute();
    
                    // Generar nuevo token y expiración
                    $token = bin2hex(random_bytes(32));
                    $expira = date('Y-m-d H:i:s', strtotime('+15 minutes'));
    
                    $stmt = $this->conn->prepare("UPDATE usuarios SET token_recuperacion = ?, expira_token = ? WHERE id_usuario = ?");
                    $stmt->bind_param("ssi", $token, $expira, $usuario['id_usuario']);
                    $stmt->execute();
    
                    return [
                        'token' => $token,
                        'expira' => $expira
                    ];
                } else {
                    return "Correo no registrado.";
                }
            } catch (Exception $e) {
                return "Error inesperado: " . $e->getMessage();
            } finally {
                if (isset($stmt) && $stmt !== false) {
                    $stmt->close();
                }
            }
        }


        public function actualizarPasswordConToken($token, $nuevaPassword) {
            try {
                // buscamos el usuarios por el token y verificamos si el token an es validto
                $stmt = $this->conn->prepare("SELECT id_usuario FROM usuarios WHERE token_recuperacion = ? AND expira_token > NOW()");
                $stmt->bind_param("s",$token);
                $stmt->execute();
                $resultado = $stmt->get_result();
                

                if ($resultado->num_rows > 0) {
                    $usuario = $resultado->fetch_assoc();   

                    // Actualizamos la contrapseña y elimiamos el token 
                    $stmt = $this->conn->prepare("UPDATE usuarios SET password = ?, token_recuperacion = NULL, expira_token = NULL WHERE id_usuario = ?");
                    $stmt->bind_param("si", $nuevaPassword, $usuario['id_usuario']);
                    $stmt->execute();

                    if ($stmt->affected_rows > 0) {
                        return true;
                    } else {
                       return false;
                    }
                } else {
                    return "Token inválido o expirado.";
                }
            } catch (Exception $e) {
                return "Error inesperado: " . $e->getMessage();
            } finally {
                if (isset($stmt) && $stmt !== false) {
                    $stmt->close();
                }
            }
        }


        // este metodo lo voy a usar para poder listar los usuarios
        public function obtenerUsuarios() {
            $sql = "SELECT u.id_usuario, u.nombre, u.email, u.nivel_usuario, g.nombre_grupo AS grupo
                    FROM usuarios u
                    JOIN grupos g ON u.nivel_usuario = g.id_grupo";
            $result = $this->conn->query($sql);
    
            if (!$result) {
                throw new Exception("Error al obtener los usuarios.");
            }
    
            if ($result->num_rows === 0) {
                throw new Exception("No se encontraron usuarios.");
            }
    
            $usuarios = [];
            while ($row = $result->fetch_assoc()) {
                $usuarios[] = $row;
            }
    
            return $usuarios;
        }



        // este metodo lo voy a usar para podeer tener el nombre de los usuario en eliminar usuario
        public function obtenerNombreUsuario() {
            $sql = "SELECT id_usuario, nombreUsuario FROM usuarios";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            $usuarios = [];

            while ($row = $result->fetch_assoc()) {
                $usuarios[] = $row;
            }
            return $usuarios;
        }


        public function eliminarUsuario($id_usuario) {
            try {
                // Verificar si hay más de un administrador en la base de datos
                $sql = "SELECT COUNT(*) AS total FROM usuarios WHERE nivel_usuario = 1";
                $result = $this->conn->query($sql);
                $row = $result->fetch_assoc();
                $total_admins = $row['total'];
    
                // Obtener el nivel del usuario a eliminar
                $sql = "SELECT nivel_usuario FROM usuarios WHERE id_usuario = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("i", $id_usuario);
                $stmt->execute();
                $nivel_usuario_eliminar = null;
                $stmt->bind_result($nivel_usuario_eliminar);
                $stmt->fetch();
                $stmt->close();
    
                if ($nivel_usuario_eliminar == 1 && $total_admins <= 1) {
                    return "No puedes eliminar el último administrador.";
                } else {
                    $sql = "DELETE FROM usuarios WHERE id_usuario = ?";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bind_param("i", $id_usuario);
                    $stmt->execute();
    
                    if ($stmt->affected_rows > 0) {
                        return true;
                    } else {
                        return "No se encontró el usuario o error al eliminar.";
                    }
                }
            } catch (Exception $e) {
                return "Error interno, intenta más tarde.";
            }
        }
    }
    
?>