-- Script para agregar control de sesiones únicas por usuario
-- Agregar campo session_id a la tabla usuarios

ALTER TABLE `usuarios` 
ADD COLUMN `session_id` VARCHAR(255) NULL DEFAULT NULL AFTER `expira_token`;

-- Agregar índice para mejorar el rendimiento de las consultas por session_id
ALTER TABLE `usuarios` 
ADD INDEX `idx_session_id` (`session_id`);

-- Comentario explicativo
-- Este campo almacenará el ID de sesión activo del usuario
-- Si es NULL, el usuario no tiene sesión activa
-- Si tiene un valor, ese es el único ID de sesión válido para ese usuario 