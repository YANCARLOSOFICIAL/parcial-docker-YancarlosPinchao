# 🚀 Proyecto Docker: PHP + MySQL

## 📋 Descripción del Proyecto

Aplicación web básica desarrollada en PHP que permite gestionar usuarios (listar y agregar) con persistencia en base de datos MySQL. Todo el proyecto está contenerizado usando Docker y Docker Compose.

**Características principales:**
- ✅ Listar usuarios existentes (GET)
- ✅ Agregar nuevos usuarios con nombre y email (POST)
- ✅ Validación de datos
- ✅ Interfaz web responsive y moderna
- ✅ Persistencia de datos con MySQL
- ✅ Contenerización completa con Docker

---

## 🏗️ Estructura del Proyecto

```
docker-php-mysql/
├── app/
│   ├── index.php          # Página principal con formulario y lista
│   ├── users.php          # Procesa el POST para agregar usuarios
│   └── Dockerfile         # Imagen personalizada de PHP
├── db/
│   └── init.sql           # Script de inicialización de BD
├── docker-compose.yml     # Orquestación de servicios
├── .env                   # Variables de entorno (NO subir a GitHub)
├── .env.example           # Ejemplo de variables de entorno
├── .gitignore             # Archivos a ignorar en Git
└── README.md              # Este archivo
```

---

## 🔧 Requisitos Previos

Antes de comenzar, asegúrate de tener instalado:

1. **Docker Desktop** (Windows/Mac) o **Docker Engine** (Linux)
   - [Descargar Docker](https://www.docker.com/get-started)
   - Verificar: `docker --version`

2. **Docker Compose**
   - Incluido en Docker Desktop
   - Verificar: `docker-compose --version`

3. **Cuenta en Docker Hub**
   - [Crear cuenta en Docker Hub](https://hub.docker.com/)

4. **Git**
   - [Descargar Git](https://git-scm.com/)
   - Verificar: `git --version`

---

## 📦 Paso 1: Construir y Subir la Imagen a Docker Hub

### 1.1 Iniciar sesión en Docker Hub

```bash
docker login
```

Ingresa tu nombre de usuario y contraseña de Docker Hub.

### 1.2 Construir la imagen personalizada

```bash
# Navegar a la carpeta del proyecto
cd docker-php-mysql

# Construir la imagen (reemplaza 'usuario_dockerhub' con tu usuario)
docker build -t usuario_dockerhub/php-app:1.0 ./app
```

**Ejemplo:**
```bash
docker build -t juanperez/php-app:1.0 ./app
```

### 1.3 Subir la imagen a Docker Hub

```bash
docker push usuario_dockerhub/php-app:1.0
```

**Ejemplo:**
```bash
docker push juanperez/php-app:1.0
```

### 1.4 Actualizar docker-compose.yml

Abre el archivo `docker-compose.yml` y reemplaza `usuario_dockerhub` con tu nombre de usuario:

```yaml
services:
  app:
    image: juanperez/php-app:1.0  # ← Cambiar aquí
    # ... resto de la configuración
```

---

## 🚀 Paso 2: Ejecutar el Proyecto con Docker Compose

Nota: este proyecto puede ejecutarse sin subir la imagen a Docker Hub. El archivo `docker-compose.yml` está preparado para construir la imagen localmente desde la carpeta `./app` (esto evita tener que usar un repositorio en Docker Hub).

Para reconstruir y levantar los servicios en un solo paso usa:

```bash
docker compose up --build -d
```

### 2.1 Configurar variables de entorno

Crea el archivo `.env` basándote en `.env.example`:

```bash
cp .env.example .env
```

Puedes editar el archivo `.env` si deseas cambiar las credenciales:

```env
MYSQL_ROOT_PASSWORD=rootpassword123
MYSQL_DATABASE=usuarios_db
MYSQL_USER=usuario
MYSQL_PASSWORD=password123
```

### 2.2 Iniciar los contenedores

```bash
docker-compose up -d
```

Este comando:
- Descarga la imagen de MySQL si no existe
- Descarga tu imagen personalizada de Docker Hub
- Crea y ejecuta los contenedores en segundo plano (-d)
- Crea la red y los volúmenes necesarios

### 2.3 Verificar que los contenedores estén corriendo

```bash
docker-compose ps
```

Deberías ver algo como:

```
NAME        IMAGE                    STATUS
php-app     usuario_dockerhub/php-app:1.0   Up
mysql-db    mysql:8                  Up (healthy)
```

---

## 🌐 Paso 3: Acceder a la Aplicación

Abre tu navegador web y visita:

```
http://localhost:8080
```

Deberías ver:
- 📝 Un formulario para agregar usuarios
- 👥 Una lista con 3 usuarios de prueba pre-cargados

---

## 🧪 Paso 4: Probar la Aplicación

### Agregar un nuevo usuario

1. Completa el formulario con:
   - Nombre: `Pedro Sánchez`
   - Email: `pedro.sanchez@example.com`
2. Haz clic en "Agregar Usuario"
3. El nuevo usuario aparecerá en la lista

### Ver los usuarios en la base de datos (opcional)

```bash
# Conectar al contenedor de MySQL
docker exec -it mysql-db mysql -u usuario -ppassword123 usuarios_db

# Dentro de MySQL, ejecutar:
SELECT * FROM users;

# Salir de MySQL
EXIT;
```

---

## 📂 Paso 5: Subir el Proyecto a GitHub

### 5.1 Inicializar repositorio Git

```bash
cd docker-php-mysql
git init
```

### 5.2 Agregar archivos al staging

```bash
git add .
```

### 5.3 Hacer el primer commit

```bash
git commit -m "Initial commit: Proyecto Docker PHP + MySQL"
```

### 5.4 Crear repositorio en GitHub

1. Ve a [GitHub](https://github.com/) e inicia sesión
2. Haz clic en el botón `+` → `New repository`
3. Nombre del repositorio: `docker-php-mysql`
4. Descripción: `Aplicación PHP + MySQL contenerizada con Docker`
5. Elige `Public` o `Private`
6. **NO** marques "Initialize this repository with a README"
7. Haz clic en `Create repository`

### 5.5 Conectar y subir al repositorio remoto

```bash
# Agregar el repositorio remoto (reemplaza TU_USUARIO)
git remote add origin https://github.com/TU_USUARIO/docker-php-mysql.git

# Subir los archivos
git branch -M main
git push -u origin main
```

---

## 🛠️ Comandos Útiles

### Ver logs de los contenedores

```bash
# Logs de todos los servicios
docker-compose logs

# Logs de un servicio específico
docker-compose logs app
docker-compose logs db

# Seguir los logs en tiempo real
docker-compose logs -f
```

### Detener los contenedores

```bash
docker-compose down
```

### Detener y eliminar volúmenes (⚠️ elimina los datos)

```bash
docker-compose down -v
```

### Reconstruir la imagen después de cambios

```bash
# Si modificaste el código fuente:
docker build -t usuario_dockerhub/php-app:1.0 ./app
docker push usuario_dockerhub/php-app:1.0
docker-compose down
docker-compose up -d
```

### Acceder al contenedor de la aplicación

```bash
docker exec -it php-app bash
```

### Reiniciar un servicio específico

```bash
docker-compose restart app
```

---

## 🗃️ Base de Datos

### Esquema de la tabla `users`

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Datos de prueba iniciales

El script `db/init.sql` inserta automáticamente 3 usuarios:
1. Juan Pérez - juan.perez@example.com
2. María García - maria.garcia@example.com
3. Carlos López - carlos.lopez@example.com

---

## 🔒 Seguridad

- ✅ Las credenciales están en archivo `.env` (no se sube a GitHub)
- ✅ Validación de email en el servidor
- ✅ Uso de prepared statements (PDO) para prevenir SQL Injection
- ✅ Sanitización de datos con `htmlspecialchars()`

---

## 🐛 Solución de Problemas

### Error: "Cannot connect to the Docker daemon"

```bash
# Asegúrate de que Docker Desktop esté ejecutándose
# En Windows/Mac: Abre Docker Desktop
# En Linux: sudo systemctl start docker
```

### Error: "Port 8080 is already in use"

```bash
# Opción 1: Cambiar el puerto en docker-compose.yml
ports:
  - "8081:80"  # Cambiar 8080 por 8081

# Opción 2: Liberar el puerto 8080
# Windows: netstat -ano | findstr :8080
# Linux/Mac: lsof -i :8080
```

### La aplicación muestra "Error de conexión"

```bash
# Verificar que el contenedor de MySQL esté corriendo y saludable
docker-compose ps

# Ver logs de MySQL
docker-compose logs db

# Esperar a que MySQL esté completamente iniciado (puede tomar 30-60 segundos)
```

### Los datos no persisten al reiniciar

```bash
# Verificar que el volumen existe
docker volume ls

# Debería aparecer: docker-php-mysql_mysql-data
```

---

## 📚 Tecnologías Utilizadas

- **PHP 8.2** - Lenguaje de programación
- **Apache 2.4** - Servidor web
- **MySQL 8** - Base de datos
- **Docker** - Contenerización
- **Docker Compose** - Orquestación de contenedores
- **PDO** - Capa de abstracción de base de datos

---

## 👨‍💻 Autor

**Proyecto de Parcial Práctico Avanzado**

- Curso: Docker & Contenerización
- Fecha: 2025

---

## 📄 Licencia

Este proyecto es de uso educativo y libre.

---

## 🎯 Objetivos de Aprendizaje Cumplidos

✅ Creación de Dockerfile personalizado  
✅ Uso de docker-compose.yml multi-servicio  
✅ Gestión de variables de entorno con .env  
✅ Persistencia de datos con volúmenes  
✅ Networking entre contenedores  
✅ Subida de imagen a Docker Hub  
✅ Integración con GitHub  
✅ Buenas prácticas de seguridad  

---

## 📞 Soporte

Si encuentras algún problema:
1. Revisa la sección de "Solución de Problemas"
2. Verifica los logs: `docker-compose logs`
3. Asegúrate de que Docker Desktop esté ejecutándose
4. Confirma que los puertos no estén ocupados

---

## 🔄 Actualizaciones Futuras

Posibles mejoras para el proyecto:
- [ ] Agregar funcionalidad de editar usuarios
- [ ] Implementar eliminación de usuarios
- [ ] Agregar paginación a la lista
- [ ] Implementar búsqueda de usuarios
- [ ] Agregar tests unitarios
- [ ] Configurar CI/CD con GitHub Actions
- [ ] Implementar autenticación de usuarios

---

**¡Gracias por usar este proyecto! 🎉**
