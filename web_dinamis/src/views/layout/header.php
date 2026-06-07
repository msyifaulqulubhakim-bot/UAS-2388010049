<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Hakim's News — Info Pendakian & Vulkanologi</title>
  <!-- Google Fonts: Playfair Display (Editorial) & Outfit (Modern Sans) -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
  <style>
    :root {
      --bg: #0b0f17;
      --surface: #121824;
      --card: #182232;
      --border: #233147;
      --accent: #ff6f3c; /* Volcanic Orange */
      --accent-hover: #ff8c61;
      --green: #2ecc71;
      --red: #ff5252;
      --text: #e2e8f0;
      --muted: #8a9cb4;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      background: var(--bg);
      color: var(--text);
      font-family: 'Outfit', sans-serif;
      line-height: 1.6;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    a {
      color: inherit;
      text-decoration: none;
      transition: color 0.2s, border-color 0.2s;
    }

    /* HEADER / NAVIGATION */
    header {
      background: var(--surface);
      border-bottom: 1px solid var(--border);
      position: sticky;
      top: 0;
      z-index: 100;
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      background: rgba(18, 24, 36, 0.85);
    }
    .nav-container {
      max-width: 1100px;
      margin: 0 auto;
      padding: 0.8rem 1.5rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .logo {
      font-family: 'Playfair Display', serif;
      font-size: 1.6rem;
      font-weight: 800;
      letter-spacing: -0.02em;
    }
    .logo span {
      color: var(--accent);
      font-style: italic;
    }
    .nav-menu {
      display: flex;
      align-items: center;
      gap: 1.5rem;
      font-size: 0.9rem;
      font-weight: 500;
    }
    .nav-link:hover {
      color: var(--accent);
    }
    .nav-link.active {
      color: var(--accent);
      border-bottom: 2px solid var(--accent);
      padding-bottom: 2px;
    }
    .user-info {
      display: flex;
      align-items: center;
      gap: 1rem;
      border-left: 1px solid var(--border);
      padding-left: 1.5rem;
    }
    .badge {
      background: rgba(255, 111, 60, 0.15);
      color: var(--accent);
      padding: 0.15rem 0.5rem;
      font-size: 0.75rem;
      border-radius: 4px;
      border: 1px solid rgba(255, 111, 60, 0.3);
      font-weight: 600;
      text-transform: uppercase;
    }
    .btn-login {
      background: var(--accent);
      color: #fff;
      padding: 0.4rem 1.2rem;
      border-radius: 4px;
      font-weight: 600;
      transition: background 0.2s;
    }
    .btn-login:hover {
      background: var(--accent-hover);
      color: #fff;
    }
    .btn-logout {
      color: var(--muted);
      font-size: 0.85rem;
      border: 1px solid var(--border);
      padding: 0.3rem 0.8rem;
      border-radius: 4px;
    }
    .btn-logout:hover {
      color: var(--red);
      border-color: var(--red);
    }

    /* HERO */
    .hero-banner {
      background: linear-gradient(180deg, rgba(11,15,23,0) 0%, rgba(11,15,23,0.9) 100%), 
                  url('https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=1200') no-repeat center center/cover;
      height: 250px;
      display: flex;
      align-items: flex-end;
      padding: 2.5rem 1.5rem;
      border-bottom: 1px solid var(--border);
    }
    .hero-content {
      max-width: 1100px;
      margin: 0 auto;
      width: 100%;
    }
    .hero-title {
      font-family: 'Playfair Display', serif;
      font-size: 2.5rem;
      font-weight: 800;
      line-height: 1.1;
      margin-bottom: 0.5rem;
    }
    .hero-subtitle {
      color: var(--muted);
      font-size: 1rem;
      font-weight: 400;
      letter-spacing: 0.05em;
    }

    /* MAIN CONTAINER */
    .main-content {
      flex: 1;
      max-width: 1100px;
      margin: 0 auto;
      padding: 2rem 1.5rem;
      width: 100%;
    }
  </style>
</head>
<body>

<header>
  <div class="nav-container">
    <a href="/" class="logo">Gunung<span>News</span></a>
    <div class="nav-menu">
      <a href="/" class="nav-link <?= !isset($_GET['route']) || $_GET['route'] === 'home' ? 'active' : '' ?>">Beranda</a>
      <?php if (isset($_SESSION['user']) && $_SESSION['role'] === 'admin'): ?>
        <a href="/?route=admin" class="nav-link <?= $_GET['route'] === 'admin' ? 'active' : '' ?>">Dashboard Admin</a>
      <?php endif; ?>

      <?php if (isset($_SESSION['user'])): ?>
        <div class="user-info">
          <span><?= htmlspecialchars($_SESSION['user']) ?></span>
          <span class="badge"><?= htmlspecialchars($_SESSION['role']) ?></span>
          <a href="/?route=logout" class="btn-logout">Logout</a>
        </div>
      <?php else: ?>
        <a href="/?route=login" class="btn-login">Login</a>
      <?php endif; ?>
    </div>
  </div>
</header>

<div class="hero-banner">
  <div class="hero-content">
    <h1 class="hero-title">Gunung Hakim</h1>
    <p class="hero-subtitle">Portal Informasi Terpercaya Seputar Pendakian, Eksplorasi Alam, dan Vulkanologi Indonesia</p>
  </div>
</div>

<main class="main-content">
