<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Concretera Huancayo - Acceso</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Montserrat', sans-serif; }

        body {
            height: 100vh;
            background: linear-gradient(rgba(0,0,0,0.88), rgba(0,0,0,0.88)),
                        url('/images/fondo-concreteras.jpg') no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            overflow: hidden;
            position: relative;
        }

        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 1;
        }
        .particle {
            position: absolute;
            background: rgba(0, 255, 255, 0.7);
            border-radius: 50%;
            box-shadow: 0 0 12px rgba(0, 255, 255, 0.9);
            animation: float 18s infinite linear;
        }
        @keyframes float {
            0% { transform: translateY(100vh) translateX(0); opacity: 0; }
            10% { opacity: 0.8; }
            90% { opacity: 0.8; }
            100% { transform: translateY(-100px) translateX(150px); opacity: 0; }
        }

        .login-container {
            background: rgba(20, 20, 40, 0.45);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            padding: 60px 50px;
            width: 420px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.7);
            border: 1px solid rgba(255, 102, 0, 0.2);
            text-align: center;
            position: relative;
            z-index: 2;
            animation: fadeIn 1.5s ease-out;
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: translateY(0); } }

        h1 {
            font-size: 36px;
            margin-bottom: 50px;
            color: #ff6600;
            text-shadow: 0 0 25px rgba(255, 102, 0, 0.7);
            font-weight: 700;
        }

        .field-label {
            display: block;
            text-align: left;
            margin-bottom: 8px;
            font-size: 16px;
            color: #ff6600;
            font-weight: 600;
        }

        .input-group {
            position: relative;
            margin-bottom: 30px;
        }

        input[type="text"], input[type="email"], input[type="password"] {
    width: 100%;
    padding: 16px 50px 16px 20px;
    border: none;
    border-radius: 15px;
    background: rgba(255, 255, 255, 0.15);
    color: #fff;
    font-size: 16px;
    outline: none;
    backdrop-filter: blur(5px);
    transition: all 0.3s ease;
}

        input::placeholder {
            color: #ccc;
        }

        input:focus {
            background: rgba(255, 255, 255, 0.25);
            box-shadow: 0 0 20px rgba(255, 102, 0, 0.4);
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 16px;
            cursor: pointer;
            color: #ff6600;
            font-size: 20px;
            transition: color 0.3s;
        }
        .password-toggle:hover {
            color: #fff;
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(45deg, #ff6600, #ff3300);
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.4s ease;
            box-shadow: 0 10px 25px rgba(255, 102, 0, 0.5);
            margin-top: 20px;
        }
        .btn-login:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(255, 102, 0, 0.7);
        }

        .error {
            color: #ff3366;
            margin-top: 15px;
            font-size: 14px;
            background: rgba(255, 0, 0, 0.2);
            padding: 10px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="particles" id="particles"></div>

    <div class="login-container">
        <h1>Concretera Huancayo</h1>
    
        @if ($errors->any())
            <p class="error">Usuario o contraseña incorrectos</p>
        @endif
    
        <form method="POST" action="{{ route('login') }}">
            @csrf
    
            <label class="field-label">Email</label>
            <div class="input-group">
                <input type="email" name="email" placeholder="andreas@gmail.com" value="{{ old('email') }}" required autofocus>
            </div>
    
            <label class="field-label">Contraseña</label>
            <div class="input-group">
                <input type="password" name="password" id="contrasena" placeholder="Ingresa tu contraseña" required>
                <i class="fas fa-eye password-toggle" id="togglePassword"></i>
            </div>
    
            <button type="submit" class="btn-login">Ingresar</button>
        </form>
    </div>

    <script>
        // Partículas
        const particlesContainer = document.getElementById('particles');
        for (let i = 0; i < 80; i++) {
            const p = document.createElement('div');
            p.classList.add('particle');
            const size = Math.random() * 7 + 3;
            p.style.width = p.style.height = size + 'px';
            p.style.left = Math.random() * 100 + 'vw';
            p.style.animationDelay = Math.random() * 18 + 's';
            p.style.animationDuration = 12 + Math.random() * 12 + 's';
            particlesContainer.appendChild(p);
        }

        // Toggle contraseña
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('contrasena');
        togglePassword.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            togglePassword.classList.toggle('fa-eye');
            togglePassword.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>