<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - PHP + MySQL</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            font-size: 2em;
            margin-bottom: 10px;
        }
        .content {
            padding: 30px;
        }
        .form-section {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .form-section h2 {
            color: #667eea;
            margin-bottom: 20px;
            font-size: 1.5em;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 1em;
            transition: border-color 0.3s;
        }
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            font-size: 1em;
            cursor: pointer;
            transition: transform 0.2s;
            font-weight: 600;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .users-section h2 {
            color: #667eea;
            margin-bottom: 20px;
            font-size: 1.5em;
        }
        .user-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 15px;
            border-left: 4px solid #667eea;
            transition: transform 0.2s;
        }
        .user-card:hover {
            transform: translateX(5px);
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        .user-card strong {
            color: #667eea;
            font-size: 1.1em;
        }
        .user-card p {
            margin: 8px 0;
            color: #666;
        }
        .message {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 Sistema de Gestión de Usuarios</h1>
            <p>Aplicación PHP + MySQL en Docker</p>
        </div>
        
        <div class="content">
            <!-- Formulario para agregar usuario -->
            <div class="form-section">
                <h2>➕ Agregar Nuevo Usuario</h2>
                <form action="users.php" method="POST">
                    <div class="form-group">
                        <label for="nombre">Nombre completo:</label>
                        <input type="text" id="nombre" name="nombre" required placeholder="Ej: Juan Pérez">
                    </div>
                    <div class="form-group">
                        <label for="email">Correo electrónico:</label>
                        <input type="email" id="email" name="email" required placeholder="Ej: juan@example.com">
                    </div>
                    <button type="submit" class="btn">Agregar Usuario</button>
                </form>
            </div>

            <!-- Mostrar mensajes -->
            <?php
            if (isset($_GET['success'])) {
                echo '<div class="message success">✅ Usuario agregado exitosamente</div>';
            }
            if (isset($_GET['error'])) {
                echo '<div class="message error">❌ Error: ' . htmlspecialchars($_GET['error']) . '</div>';
            }
            ?>

            <!-- Lista de usuarios -->
            <div class="users-section">
                <h2>👥 Lista de Usuarios</h2>
                <?php
                // Configuración de conexión a la base de datos
                $host = 'db';
                $dbname = $_ENV['MYSQL_DATABASE'] ?? 'usuarios_db';
                $username = $_ENV['MYSQL_USER'] ?? 'usuario';
                $password = $_ENV['MYSQL_PASSWORD'] ?? 'password';

                try {
                    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                    $stmt = $pdo->query("SELECT id, nombre, email FROM users ORDER BY id DESC");
                    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    if (count($users) > 0) {
                        foreach ($users as $user) {
                            echo '<div class="user-card">';
                            echo '<strong>ID: ' . htmlspecialchars($user['id']) . '</strong>';
                            echo '<p><strong>Nombre:</strong> ' . htmlspecialchars($user['nombre']) . '</p>';
                            echo '<p><strong>Email:</strong> ' . htmlspecialchars($user['email']) . '</p>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p style="text-align: center; color: #999; padding: 20px;">No hay usuarios registrados aún.</p>';
                    }
                } catch (PDOException $e) {
                    echo '<div class="message error">Error de conexión: ' . htmlspecialchars($e->getMessage()) . '</div>';
                }
                ?>
            </div>
        </div>

        <div class="footer">
            <p>Proyecto Docker - PHP 8.2 + MySQL 8 | Parcial Práctico Avanzado</p>
        </div>
    </div>
</body>
</html>
