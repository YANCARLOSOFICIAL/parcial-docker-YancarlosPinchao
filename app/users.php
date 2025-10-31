<?php
// users.php - Procesar POST para agregar usuarios

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?error=Método no permitido');
    exit;
}

// Obtener datos del formulario
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
$host = 'db';
$dbname = $_ENV['MYSQL_DATABASE'] ?? 'usuarios_db';
$username = $_ENV['MYSQL_USER'] ?? 'usuario';
$password = $_ENV['MYSQL_PASSWORD'] ?? 'password';

try {
    // Conectar a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Verificar si el email ya existe
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $count = $stmt->fetchColumn();
    
    if ($count > 0) {
        header('Location: index.php?error=El email ya está registrado');
        exit;
    }
    
    // Insertar nuevo usuario
    $stmt = $pdo->prepare("INSERT INTO users (nombre, email) VALUES (?, ?)");
    $stmt->execute([$nombre, $email]);
    
    // Redirigir con mensaje de éxito
    header('Location: index.php?success=1');
    exit;
    
} catch (PDOException $e) {
    // Redirigir con mensaje de error
    header('Location: index.php?error=' . urlencode('Error en la base de datos: ' . $e->getMessage()));
    exit;
}
?>
