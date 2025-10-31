<?php
// users.php - Procesar POST para agregar usuarios

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?error=Método no permitido');
    exit;
}

// Obtener datos del formulario
$id = isset($_POST['id']) ? intval($_POST['id']) : null;
$nombre = trim($_POST['nombre'] ?? '');
$email = trim($_POST['email'] ?? '');

// Validar datos
if (empty($nombre) || empty($email)) {
    header('Location: index.php?error=Todos los campos son obligatorios');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: index.php?error=El email no es válido');
    exit;
}

// Configuración de conexión a la base de datos
// Permitir sobrescribir el host desde .env (MYSQL_HOST or DB_HOST).
$host = getenv('MYSQL_HOST') ?: (getenv('DB_HOST') ?: 'db');
$dbname = $_ENV['MYSQL_DATABASE'] ?? getenv('MYSQL_DATABASE') ?: 'usuarios_db';
$username = $_ENV['MYSQL_USER'] ?? getenv('MYSQL_USER') ?: 'usuario';
$password = $_ENV['MYSQL_PASSWORD'] ?? getenv('MYSQL_PASSWORD') ?: 'password';

try {
    // Conectar a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if ($id) {
        // Si llega un id, actualizamos el usuario
        // Verificar si el email ya existe en otro registro
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $id]);
        $count = $stmt->fetchColumn();
        if ($count > 0) {
            header('Location: index.php?error=El email ya está registrado');
            exit;
        }

        $stmt = $pdo->prepare("UPDATE users SET nombre = ?, email = ? WHERE id = ?");
        $stmt->execute([$nombre, $email, $id]);
        header('Location: index.php?success=updated');
        exit;
    } else {
        // Insertar nuevo usuario
        // Verificar si el email ya existe
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            header('Location: index.php?error=El email ya está registrado');
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO users (nombre, email) VALUES (?, ?)");
        $stmt->execute([$nombre, $email]);

        // Redirigir con mensaje de éxito
        header('Location: index.php?success=1');
        exit;
    }
    
} catch (PDOException $e) {
    // Redirigir con mensaje de error
    header('Location: index.php?error=' . urlencode('Error en la base de datos: ' . $e->getMessage()));
    exit;
}
?>
