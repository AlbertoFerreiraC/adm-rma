<style>
  body.login-page {
    margin: 0;
    height: 100vh;
    background: radial-gradient(circle at top, #0b1220, #0f1a2b, #060a12);
    overflow: hidden;
    font-family: 'Segoe UI', sans-serif;
  }

  body.login-page::before {
    content: "";
    position: absolute;
    width: 100%;
    height: 100%;
    background:
      radial-gradient(circle at 20% 20%, rgba(0, 140, 255, 0.15), transparent 40%),
      radial-gradient(circle at 80% 80%, rgba(0, 255, 200, 0.08), transparent 40%);
    animation: floatBg 10s ease-in-out infinite alternate;
  }

  @keyframes floatBg {
    from {
      transform: scale(1);
    }

    to {
      transform: scale(1.05);
    }
  }

  .login-wrapper {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    padding: 20px;
  }

  /* Glass card */
  .login-container {
    width: 100%;
    max-width: 380px;
    padding: 40px;
    border-radius: 16px;

    background: rgba(255, 255, 255, 0.06);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);

    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 0 40px rgba(0, 140, 255, 0.10);

    text-align: center;
    position: relative;
    z-index: 2;
  }

  /* LOGO */
  .logo {
    width: 100%;
    display: flex;
    justify-content: center;
    margin-bottom: 22px;
  }

  .logo img {
    width: 100%;
    max-width: 260px;
    height: auto;
    object-fit: contain;

    filter: brightness(1.1) contrast(0.95) drop-shadow(0 0 18px rgba(0, 140, 255, 0.25));

    opacity: 0.92;

    mix-blend-mode: screen;

    transition: all 0.4s ease;
  }

  .logo img:hover {
    transform: scale(1.04);
    filter: brightness(1.2) contrast(1) drop-shadow(0 0 25px rgba(0, 140, 255, 0.4));
  }

  /* TEXTOS */
  .login-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #ffffff;
    letter-spacing: 1px;
  }

  .login-subtitle {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.6);
    margin-bottom: 25px;
  }

  /* INPUTS */
  .login-container input {
    width: 100%;
    padding: 12px 14px;
    margin-bottom: 12px;

    border-radius: 10px;
    border: 1px solid rgba(255, 255, 255, 0.1);

    background: rgba(255, 255, 255, 0.05);
    color: white;

    outline: none;
    transition: 0.3s;
  }

  .login-container input:focus {
    border-color: rgba(0, 140, 255, 0.6);
    box-shadow: 0 0 10px rgba(0, 140, 255, 0.3);
  }

  .login-container label {
    display: block;
    text-align: left;
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.6);
    margin-bottom: 4px;
  }

  /* BOTÓN */
  .login-container button {
    width: 100%;
    padding: 12px;

    border: none;
    border-radius: 10px;

    background: linear-gradient(135deg, #008cff, #00ffd0);
    color: #0b1220;

    font-weight: bold;
    letter-spacing: 1px;

    cursor: pointer;
    transition: 0.3s;
  }

  .login-container button:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 140, 255, 0.25);
  }

  /* ERROR */
  .alert {
    background: rgba(255, 0, 0, 0.15);
    color: #ffb3b3;
    border: 1px solid rgba(255, 0, 0, 0.3);
    font-size: 0.85rem;
  }
</style>

<div class="login-wrapper">

  <div class="login-container">

    <div class="logo">
      <img src="vistas/img/micrologo3.png">
    </div>

    <div class="login-title">SISTEMA RMA</div>

    <div class="login-subtitle">
      Servicio técnico y gestión inteligente
    </div>

    <?php if (!empty($error)): ?>
      <div class="alert">
        <?= $error ?>
      </div>
    <?php endif; ?>

    <form method="POST">

      <label>Usuario</label>
      <input type="text" id="usuario" name="usuario" required>

      <label>Contraseña</label>
      <input type="password" id="contrasena" name="contrasena" required>
      <button type="button" id="ingresarLogin">
        INGRESAR
      </button>
    </form>

  </div>

</div>

<script src="vistas/js/login.js"></script>