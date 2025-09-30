<?php
// index.php - Login principal
session_start();
if (isset($_SESSION['user'])) {
    header('Location: app/views/dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RMIE - Sistema de Gestión</title>
    <link href="public/css/bootstrap.min.css" rel="stylesheet">
    <link href="public/css/styles.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta name="description" content="Sistema de gestión empresarial RMIE">
</head>
<body class="login-body">
    <!-- Partículas flotantes -->
    <div class="login-particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="login-container">
        <div class="login-card">
            <!-- Header con logo -->
            <div class="login-header">
                <div class="login-logo">
                    <i class="fas fa-briefcase"></i>
                </div>
                <h1 class="login-title">RMIE</h1>
                <p class="login-subtitle">Sistema de Gestión Empresarial</p>
            </div>

            <!-- Formulario de login -->
            <form action="app/controllers/LoginController.php" method="POST" class="login-form">
                <div class="login-form-group">
                    <label for="user" class="login-form-label">
                        <i class="fas fa-user"></i>
                        Usuario
                    </label>
                    <input 
                        type="text" 
                        class="login-form-input" 
                        id="user" 
                        name="user" 
                        placeholder="Ingrese su usuario"
                        required
                        autocomplete="username"
                    >
                </div>

                <div class="login-form-group">
                    <label for="password" class="login-form-label">
                        <i class="fas fa-lock"></i>
                        Contraseña
                    </label>
                    <input 
                        type="password" 
                        class="login-form-input" 
                        id="password" 
                        name="password" 
                        placeholder="Ingrese su contraseña"
                        required
                        autocomplete="current-password"
                    >
                </div>

                <button type="submit" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    Iniciar Sesión
                </button>
            </form>

            <!-- Enlaces adicionales -->
            <div class="login-links">
                <a href="#" class="login-link">
                    <i class="fas fa-question-circle"></i>
                    ¿Olvidaste tu contraseña?
                </a>
                <a href="#" class="login-link">
                    <i class="fas fa-info-circle"></i>
                    Ayuda
                </a>
            </div>
        </div>
    </div>

    <!-- JavaScript para efectos adicionales -->
    <script>
        // Animación de entrada suave
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.querySelector('.login-card');
            const inputs = document.querySelectorAll('.login-form-input');
            const button = document.querySelector('.login-btn');

            // Efecto de focus en inputs
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'translateY(-2px)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'translateY(0)';
                });
            });

            // Efecto de ripple en el botón
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.classList.add('ripple');
                
                ripple.style.position = 'absolute';
                ripple.style.borderRadius = '50%';
                ripple.style.background = 'rgba(255, 255, 255, 0.3)';
                ripple.style.transform = 'scale(0)';
                ripple.style.animation = 'ripple 0.6s linear';
                ripple.style.pointerEvents = 'none';
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });

        // Keyframes para ripple
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>