<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - PHP + MySQL</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="brand">
                <div class="logo">YS</div>
                <div>
                    <h1>Gestión de Usuarios</h1>
                    <p class="muted">Aplicación PHP + MySQL en Docker</p>
                </div>
            </div>
            <div class="actions">
                <a class="btn" href="#">Ir al repositorio</a>
            </div>
        </div>
        
        <div class="content">
            <div>
                <!-- Formulario para agregar usuario -->
                <div class="card form-section">
                    <h2>➕ Agregar Nuevo Usuario</h2>
                    <form id="user-form" action="users.php" method="POST">
                        <input type="hidden" id="user-id" name="id" value="">
                        <div class="form-group">
                            <label for="nombre">Nombre completo:</label>
                            <input type="text" id="nombre" name="nombre" required placeholder="Ej: Juan Pérez">
                        </div>
                        <div class="form-group">
                            <label for="email">Correo electrónico:</label>
                            <input type="email" id="email" name="email" required placeholder="Ej: juan@example.com">
                        </div>

                        <!-- Botón full-width visible siempre -->
                        <button type="submit" id="primary-submit" class="btn fullwidth">Guardar Usuario</button>

                        <div class="form-actions" style="margin-top:8px">
                            <button type="submit" id="submit-btn" class="btn" style="display:none">Guardar Usuario</button>
                            <button type="button" id="cancel-btn" class="btn secondary" style="display:none;">Cancelar</button>
                        </div>
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
                <div class="card users-section" style="margin-top:18px;">
                    <h2>👥 Lista de Usuarios</h2>
                    <div class="users-list">
                    <?php
                    // Configuración de conexión a la base de datos
                    // Intentar varios hosts automáticamente para soportar entornos sin .env
                    $preferred = getenv('MYSQL_HOST') ?: (getenv('DB_HOST') ?: 'db');
                    $dbname = $_ENV['MYSQL_DATABASE'] ?? getenv('MYSQL_DATABASE') ?: 'usuarios_db';
                    $username = $_ENV['MYSQL_USER'] ?? getenv('MYSQL_USER') ?: 'usuario';
                    $password = $_ENV['MYSQL_PASSWORD'] ?? getenv('MYSQL_PASSWORD') ?: 'password';

                    try {
                    $hostsToTry = array_unique([$preferred, 'host.docker.internal', '127.0.0.1']);
                    $lastException = null;
                    $pdo = null;
                    foreach ($hostsToTry as $h) {
                        try {
                            $pdo = new PDO("mysql:host=$h;dbname=$dbname;charset=utf8mb4", $username, $password);
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            // Si conecta, salimos del loop
                            break;
                        } catch (PDOException $e) {
                            $lastException = $e;
                            // intentar siguiente host
                        }
                    }

                    if (!$pdo) {
                        throw $lastException ?: new Exception('No se pudo establecer conexión con la base de datos');
                    }

                    $stmt = $pdo->query("SELECT id, nombre, email FROM users ORDER BY id DESC");
                    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        if (count($users) > 0) {
                            foreach ($users as $user) {
                                // Añadimos botón de editar con data-attributes para JS
                                echo '<div class="user-card">';
                                echo '<div class="user-meta"><div><span class="user-name">' . htmlspecialchars($user['nombre']) . '</span><div class="user-email">' . htmlspecialchars($user['email']) . '</div></div><div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px"><div class="muted">ID: ' . htmlspecialchars($user['id']) . '</div><div><button type="button" class="btn secondary edit-btn" data-id="' . htmlspecialchars($user['id']) . '" data-nombre="' . htmlspecialchars($user['nombre'], ENT_QUOTES) . '" data-email="' . htmlspecialchars($user['email'], ENT_QUOTES) . '">Editar</button></div></div></div>';
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
            </div>

        </div>

        <div class="footer">
            <p>Proyecto Docker - PHP 8.2 + MySQL 8 | Parcial Práctico Avanzado</p>
        </div>
    </div>
    <script>
        // JavaScript mínimo para habilitar edición inline del formulario
        (function(){
            const form = document.getElementById('user-form');
            const idInput = document.getElementById('user-id');
            const nombreInput = document.getElementById('nombre');
            const emailInput = document.getElementById('email');
            const submitBtn = document.getElementById('submit-btn');
            const cancelBtn = document.getElementById('cancel-btn');

            function resetForm(){
                idInput.value = '';
                nombreInput.value = '';
                emailInput.value = '';
                submitBtn.textContent = 'Guardar Usuario';
                cancelBtn.style.display = 'none';
                hideFloating();
            }

            // Delegación de eventos para botones editar
            document.addEventListener('click', function(e){
                const el = e.target;
                if (el && el.classList && el.classList.contains('edit-btn')){
                    const uid = el.getAttribute('data-id');
                    const uname = el.getAttribute('data-nombre');
                    const uemail = el.getAttribute('data-email');
                    idInput.value = uid || '';
                    nombreInput.value = uname || '';
                    emailInput.value = uemail || '';
                    submitBtn.textContent = 'Guardar cambios';
                    cancelBtn.style.display = 'inline-flex';
                    // scroll to form
                    nombreInput.scrollIntoView({behavior:'smooth',block:'center'});
                    showFloating();
                }
            });

            cancelBtn.addEventListener('click', function(){
                resetForm();
            });

            // Floating save button
            const floatBtn = document.createElement('button');
            floatBtn.id = 'floating-save-btn';
            floatBtn.className = 'btn';
            floatBtn.type = 'button';
            floatBtn.textContent = 'Guardar Usuario';
            floatBtn.style.display = 'inline-flex';
            floatBtn.style.zIndex = 9999;
            floatBtn.addEventListener('click', function(){
                // trigger submit
                submitBtn.click();
            });
            floatBtn.setAttribute('aria-hidden','false');
            floatBtn.setAttribute('id','floating-save');
            document.body.appendChild(floatBtn);

            function showFloating(){
                floatBtn.style.display = 'inline-flex';
            }
            function hideFloating(){
                floatBtn.style.display = 'inline-flex';
            }

            // Show floating when form inputs are focused
            [nombreInput, emailInput].forEach(function(input){
                input.addEventListener('focus', function(){ showFloating(); });
                input.addEventListener('blur', function(){
                    // small timeout to allow click
                    setTimeout(function(){
                        if (!document.activeElement || (document.activeElement !== nombreInput && document.activeElement !== emailInput)){
                            // keep visible briefly
                            hideFloating();
                        }
                    }, 200);
                });
            });

            // Optional: reset form on successful submission when URL has success param
            if (window.location.search.indexOf('success') !== -1){
                // clear form after action
                resetForm();
            }
        })();
    </script>
</body>
</html>
