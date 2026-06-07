<?php require_once 'views/layout/header.php'; ?>

<style>
  .login-container {
    max-width: 400px;
    margin: 3rem auto;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 2.5rem;
    position: relative;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
  }
  .login-container::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: var(--accent);
    border-radius: 8px 8px 0 0;
  }
  .login-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-align: center;
  }
  .login-subtitle {
    color: var(--muted);
    font-size: 0.85rem;
    margin-bottom: 2rem;
    text-align: center;
  }
  .form-group {
    margin-bottom: 1.25rem;
  }
  .form-group label {
    display: block;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--muted);
    margin-bottom: 0.5rem;
  }
  .form-group input {
    width: 100%;
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 4px;
    padding: 0.75rem 1rem;
    color: var(--text);
    font-family: inherit;
    font-size: 0.9rem;
    outline: none;
    transition: border-color 0.2s;
  }
  .form-group input:focus {
    border-color: var(--accent);
  }
  .btn-submit {
    display: block;
    width: 100%;
    background: var(--accent);
    color: #fff;
    border: none;
    border-radius: 4px;
    padding: 0.8rem;
    font-size: 0.95rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-top: 1.5rem;
  }
  .btn-submit:hover {
    background: var(--accent-hover);
  }
  .error-box {
    background: rgba(255, 82, 82, 0.15);
    border: 1px solid rgba(255, 82, 82, 0.3);
    color: var(--red);
    padding: 0.75rem 1rem;
    border-radius: 4px;
    font-size: 0.85rem;
    margin-top: 1rem;
    text-align: center;
  }
  .login-hint {
    margin-top: 1.5rem;
    font-size: 0.75rem;
    color: var(--muted);
    text-align: center;
    border-top: 1px solid var(--border);
    padding-top: 1rem;
  }
  .login-hint strong {
    color: var(--accent);
  }
</style>

<div class="login-container">
  <h2 class="login-title">Masuk</h2>
  <p class="login-subtitle">Akses Dashboard Gunung News</p>
  
  <form method="POST">
    <div class="form-group">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" placeholder="Masukkan username" required autocomplete="username"/>
    </div>
    
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="Masukkan password" required autocomplete="current-password"/>
    </div>
    
    <button type="submit" class="btn-submit">Masuk →</button>
    
    <?php if ($error): ?>
      <div class="error-box"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
  </form>
  
  <div class="login-hint">
    Demo Admin: <strong>admin</strong> / <strong>admin123</strong><br/>
    Demo User: <strong>user1</strong> / <strong>user123</strong>
  </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>
