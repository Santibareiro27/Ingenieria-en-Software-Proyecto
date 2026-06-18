-- Base de datos: sgso
-- Migracion: 002_auth
-- Objetivo: dejar la tabla `usuario` lista para el login seguro (autenticacion).
--           Es la base para RF19 (roles y permisos).
--
-- REQUISITO: ejecutar primero 001_planificacion.sql (crea la tabla `usuario`).
--
-- INSTRUCCIONES DBEAVER:
--   1. Abrir un SQL Editor sobre la base "sgso".
--   2. Abrir este archivo y ejecutar con Ctrl+A luego Ctrl+Enter.
--
-- NOTA DE SEGURIDAD (importante):
--   La columna `contrasena` (VARCHAR(255)) almacena SIEMPRE el HASH bcrypt,
--   nunca la contrasena en texto plano. Un hash bcrypt ocupa 60 caracteres,
--   por eso 255 es mas que suficiente.
--   El usuario administrador de prueba NO se inserta aqui con su contrasena,
--   porque el hash debe generarlo bcrypt en tiempo de ejecucion. Para crearlo
--   de forma segura, ejecutar el seed del backend:  npm run seed   (carpeta BACK)

-- 1) Auditoria minima: registrar cuando se creo cada usuario.
--    "IF NOT EXISTS" hace que la migracion se pueda correr mas de una vez sin error
--    (sintaxis soportada por MariaDB).
ALTER TABLE usuario
  ADD COLUMN IF NOT EXISTS fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;

-- 2) Limpieza de seguridad: el archivo 001 dejaba un admin con la contrasena en
--    TEXTO PLANO ('admin123'). Lo borramos para que el unico admin valido sea el
--    que crea el seed con hash bcrypt. Si ya lo habias hasheado, esta linea no
--    borra nada (la condicion exige que la contrasena siga siendo el texto plano).
DELETE FROM usuario
  WHERE email = 'admin@sgso.com' AND contrasena = 'admin123';
