# Tech Alumni

Sistema de gestión integral para cursos online, incluyendo administración de alumnos, contenidos, evaluaciones y más.

## Características Principales

- Gestión de alumnos y egresados
- Administración de cursos y contenidos
- Sistema de evaluaciones y calificaciones
- Generación de diplomas
- Sistema de mensajería interno
- Encuestas y feedback
- Seguimiento de progreso
- Soporte para múltiples empresas/instituciones

## Requisitos del Sistema

- PHP >= 8.0 (recomendado PHP 8.2)
- MySQL 8.0 o superior / MariaDB 10.5 o superior
- Servidor web (Apache/Nginx)
- Composer
- Extensiones PHP requeridas:
  - php-mysql
  - php-gd
  - php-mbstring
  - php-xml
  - php-ssh2
  - php-curl

## Instalación

1. Clonar el repositorio:
   ```bash
   git clone https://github.com/your-username/tech-alumni.git
   cd tech-alumni
   ```

2. Instalar dependencias con Composer:
   ```bash
   composer install
   ```

3. Configurar el entorno:
   ```bash
   cp .env.example .env
   # Editar .env con tus configuraciones
   ```

## Configuración de la Base de Datos

1. Crear la base de datos:
   ```bash
   mysql -u root -p
   CREATE DATABASE alumni CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
   exit;
   ```

2. Importar la estructura de la base de datos:
   ```bash
   mysql -u your_user -p alumni < alumni.sql
   ```

3. Estructura de la base de datos:

   La base de datos contiene las siguientes tablas principales:

   - `clientes`: Almacena información de usuarios/alumnos
     - Campos clave: cli_usuario, cli_password, cli_admin
     - Gestiona autenticación y permisos

   - `cursos`: Gestión de cursos
     - Campos clave: cur_id, cur_designacion, cur_descripcion
     - Relacionada con curso_status para estados

   - `contenidos`: Material de estudio
     - Vinculada a cursos (cur_id)
     - Almacena objetivos y material de clase

   - `calificacion`: Sistema de evaluación
     - Relaciona alumnos con cursos
     - Registra fechas y notas

   - `empresas`: Gestión de empresas/instituciones
     - Permite múltiples organizaciones
     - Almacena información de contacto

4. Notas importantes sobre la base de datos:

   - Usa UTF-8 para soporte multilenguaje
   - Tiene claves foráneas para integridad referencial
   - Incluye índices para optimizar búsquedas
   - Password por defecto: cambiarlo inmediatamente
   - Respetar las relaciones entre tablas al insertar datos

5. Usuario inicial:
   ```sql
   -- Crear usuario administrador inicial
   INSERT INTO clientes (cli_nombre, cli_apellido, cli_usuario, cli_password, cli_admin, cli_habilitado)
   VALUES ('Admin', 'System', 'admin', 'CAMBIAR_ESTE_PASSWORD', 1, 1);
   ```

## Configuración del Servidor Web

1. Configurar el servidor web:
   - Para Apache, asegurar que mod_rewrite esté habilitado
   - Configurar el DocumentRoot al directorio public
   - Asegurar que los permisos de archivos sean correctos

2. Configurar permisos:
   ```bash
   chmod -R 755 .
   chmod -R 777 storage/
   ```

## Configuración

1. Editar el archivo `.env` con:
   - Credenciales de la base de datos
   - Clave de encriptación
   - Configuración de correo (si se necesita)
   - URL de la aplicación

2. Configuraciones adicionales en `config.php`:
   - Zona horaria
   - Límites de memoria y tiempo de ejecución
   - Configuración de sesiones

## Seguridad

- Mantener el archivo `.env` seguro y nunca compartirlo
- Cambiar las claves de encriptación en producción
- Mantener actualizadas las dependencias
- Configurar correctamente los permisos de archivos
- Deshabilitar la depuración en producción
- Cambiar inmediatamente el password del usuario admin inicial

## Mantenimiento

- Realizar backups regulares de la base de datos:
  ```bash
  # Backup de la base de datos
  mysqldump -u your_user -p alumni > backup_$(date +%Y%m%d).sql
  ```
- Mantener actualizado PHP y sus extensiones
- Actualizar las dependencias periódicamente:
  ```bash
  composer update
  ```

## Soporte

Para reportar problemas o solicitar ayuda:
- Abrir un issue en el repositorio
- Contactar al equipo de soporte

## Licencia

Ver archivo [LICENSE](LICENSE) para más detalles.
