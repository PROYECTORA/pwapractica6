<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Hotel pwapractica6</title>
    
    <!-- Invocación de estilos locales (Rutas relativas al directorio superior) -->
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/componentes/formularios.css">
    <link rel="stylesheet" href="../assets/css/componentes/botones.css">
</head>
<body style="background-color: var(--color-primario); display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0;">

    <div class="login-card">
        <div style="text-align: center; margin-bottom: 25px;">
            <h1 style="color: var(--color-primario); margin: 0; font-size: 1.8rem;">Hotel PWA</h1>
            <p style="color: #64748b; font-size: 0.9rem;">Gestión de Reservas</p>
        </div>

        <form action="procesar_login.php" method="POST">
            <div class="form-group">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="ejemplo@hotel.com" required autofocus>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primario btn-block">
                Ingresar al Sistema
            </button>
        </form>

        <div style="margin-top: 20px; text-align: center; border-top: 1px solid var(--color-borde); padding-top: 15px;">
            <a href="../index.php" style="font-size: 0.85rem; color: var(--color-secundario); font-weight: 600;">
                &larr; Volver al Portal Principal
            </a>
        </div>
    </div>

</body>
</html>
