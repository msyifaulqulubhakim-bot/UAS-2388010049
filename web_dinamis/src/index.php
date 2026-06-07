<?php
session_start();

$host = getenv('DB_HOST') ?: 'db';
$dbname = getenv('DB_NAME') ?: 'app_db';
$user = getenv('DB_USER') ?: 'appuser';
$pass = getenv('DB_PASS') ?: 'apppassword';

$pdo = null;
$db_error = null;

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    $db_error = $e->getMessage();
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: /');
    exit;
}

// Handle login
$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($pdo) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user_row = $stmt->fetch();

        if ($user_row && md5($password) === $user_row['password']) {
            $_SESSION['user'] = $user_row['username'];
            $_SESSION['role'] = $user_row['role'];
            header('Location: /');
            exit;
        } else {
            $login_error = 'Username atau password salah.';
        }
    } else {
        $login_error = 'Database tidak tersedia.';
    }
}

$logged_in = isset($_SESSION['user']);
$users_data = [];
$stats = ['users' => 0, 'admins' => 0];

if ($logged_in && $pdo) {
    $users_data = $pdo->query("SELECT id, username, role, created_at FROM users ORDER BY id")->fetchAll();
    $stats['users'] = count(array_filter($users_data, fn($u) => $u['role'] === 'user'));
    $stats['admins'] = count(array_filter($users_data, fn($u) => $u['role'] === 'admin'));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= $logged_in ? 'Dashboard' : 'Login' ?> — Web Dinamis PHP</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet"/>
  <style>
    :root {
      --bg: #0b0f1a;
      --surface: #111827;
      --card: #1a2234;
      --border: #1e293b;
      --accent: #38bdf8;
      --accent2: #818cf8;
      --green: #4ade80;
      --red: #f87171;
      --text: #e2e8f0;
      --muted: #64748b;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      background: var(--bg);
      color: var(--text);
      font-family: 'DM Mono', monospace;
      min-height: 100vh;
    }

    /* ===== LOGIN ===== */
    .login-wrap {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background:
        radial-gradient(ellipse at 20% 50%, rgba(56,189,248,0.07) 0%, transparent 60%),
        radial-gradient(ellipse at 80% 20%, rgba(129,140,248,0.07) 0%, transparent 60%),
        var(--bg);
    }
    .login-box {
      width: 100%;
      max-width: 380px;
      padding: 2.5rem;
      background: var(--surface);
      border: 1px solid var(--border);
      position: relative;
    }
    .login-box::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 2px;
      background: linear-gradient(90deg, var(--accent), var(--accent2));
    }
    .login-logo { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.5rem; margin-bottom: 0.25rem; }
    .login-logo span { color: var(--accent); }
    .login-sub { color: var(--muted); font-size: 0.75rem; margin-bottom: 2rem; }
    .form-group { margin-bottom: 1rem; }
    label { display: block; font-size: 0.7rem; letter-spacing: 0.1em; text-transform: uppercase; color: var(--muted); margin-bottom: 0.4rem; }
    input[type=text], input[type=password] {
      width: 100%; background: var(--bg); border: 1px solid var(--border);
      color: var(--text); padding: 0.65rem 0.85rem;
      font-family: 'DM Mono', monospace; font-size: 0.85rem; outline: none; transition: border-color 0.2s;
    }
    input:focus { border-color: var(--accent); }
    .btn {
      display: block; width: 100%; padding: 0.75rem;
      background: var(--accent); color: #000; border: none;
      font-family: 'Syne', sans-serif; font-weight: 700; font-size: 0.85rem;
      letter-spacing: 0.05em; cursor: pointer; transition: opacity 0.2s;
      margin-top: 1.5rem; text-transform: uppercase;
    }
    .btn:hover { opacity: 0.85; }
    .error-msg {
      background: rgba(248,113,113,0.1); border: 1px solid rgba(248,113,113,0.3);
      color: var(--red); padding: 0.6rem 0.85rem; font-size: 0.75rem; margin-top: 1rem;
    }
    .hint { color: var(--muted); font-size: 0.7rem; margin-top: 1.5rem; text-align: center; }
    .hint strong { color: var(--accent2); }

    /* ===== DASHBOARD ===== */
    .navbar {
      background: var(--surface); border-bottom: 1px solid var(--border);
      padding: 0 2rem; height: 56px; display: flex; align-items: center;
      justify-content: space-between; position: sticky; top: 0; z-index: 10;
    }
    .nav-brand { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1rem; }
    .nav-brand span { color: var(--accent); }
    .nav-right { display: flex; align-items: center; gap: 1.5rem; font-size: 0.75rem; }
    .badge { background: var(--accent2); color: #000; padding: 0.15rem 0.5rem; font-size: 0.6rem; font-weight: bold; letter-spacing: 0.1em; text-transform: uppercase; }
    .logout-btn { color: var(--muted); text-decoration: none; font-size: 0.75rem; border: 1px solid var(--border); padding: 0.35rem 0.75rem; transition: all 0.2s; }
    .logout-btn:hover { color: var(--red); border-color: var(--red); }
    .main { max-width: 960px; margin: 0 auto; padding: 2.5rem 2rem; }
    .page-title { font-family: 'Syne', sans-serif; font-size: 1.75rem; font-weight: 800; margin-bottom: 0.25rem; }
    .page-sub { color: var(--muted); font-size: 0.8rem; margin-bottom: 2rem; }
    .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
    .stat-card { background: var(--card); border: 1px solid var(--border); padding: 1.25rem; position: relative; overflow: hidden; }
    .stat-card::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 2px; }
    .stat-card.blue::after { background: var(--accent); }
    .stat-card.purple::after { background: var(--accent2); }
    .stat-card.green::after { background: var(--green); }
    .stat-num { font-family: 'Syne', sans-serif; font-size: 2rem; font-weight: 800; line-height: 1; margin-bottom: 0.25rem; }
    .stat-label { color: var(--muted); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em; }
    .section-title { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--muted); margin-bottom: 0.75rem; }
    .table-wrap { background: var(--card); border: 1px solid var(--border); overflow: hidden; }
    table { width: 100%; border-collapse: collapse; font-size: 0.8rem; }
    thead { background: var(--surface); }
    th { text-align: left; padding: 0.75rem 1rem; color: var(--muted); font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 500; }
    td { padding: 0.75rem 1rem; border-top: 1px solid var(--border); }
    tr:hover td { background: rgba(255,255,255,0.02); }
    .role-badge { display: inline-block; padding: 0.15rem 0.5rem; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.1em; }
    .role-admin { background: rgba(129,140,248,0.15); color: var(--accent2); border: 1px solid rgba(129,140,248,0.3); }
    .role-user  { background: rgba(56,189,248,0.1);  color: var(--accent);  border: 1px solid rgba(56,189,248,0.3); }
    .db-error { background: rgba(248,113,113,0.08); border: 1px solid rgba(248,113,113,0.25); padding: 1rem 1.25rem; color: var(--red); font-size: 0.78rem; margin-bottom: 1.5rem; }
    .server-info { margin-top: 2rem; background: var(--card); border: 1px solid var(--border); padding: 1.25rem; font-size: 0.75rem; color: var(--muted); display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.5rem; }
    .server-info span { color: var(--text); }
  </style>
</head>
<body>

<?php if (!$logged_in): ?>
<div class="login-wrap">
  <div class="login-box">
    <div class="login-logo">Web<span>Dinamis</span></div>
    <div class="login-sub">PHP + MariaDB · Docker · CI/CD</div>
    <form method="POST">
      <input type="hidden" name="action" value="login"/>
      <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" placeholder="admin" required autocomplete="username"/>
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="••••••••" required autocomplete="current-password"/>
      </div>
      <button type="submit" class="btn">Login →</button>
      <?php if ($login_error): ?>
        <div class="error-msg"><?= htmlspecialchars($login_error) ?></div>
      <?php endif; ?>
    </form>
    <div class="hint">Demo: <strong>admin</strong> / <strong>admin123</strong> &nbsp;|&nbsp; <strong>user1</strong> / <strong>user123</strong></div>
  </div>
</div>

<?php else: ?>
<nav class="navbar">
  <div class="nav-brand">Web<span>Dinamis</span></div>
  <div class="nav-right">
    <div><?= htmlspecialchars($_SESSION['user']) ?> <span class="badge"><?= htmlspecialchars($_SESSION['role']) ?></span></div>
    <a href="/?logout=1" class="logout-btn">Logout</a>
  </div>
</nav>

<main class="main">
  <div class="page-title">Dashboard</div>
  <div class="page-sub">Selamat datang, <?= htmlspecialchars($_SESSION['user']) ?>. Sistem berjalan normal.</div>

  <?php if ($db_error): ?>
    <div class="db-error">⚠ Database Error: <?= htmlspecialchars($db_error) ?></div>
  <?php endif; ?>

  <div class="stats-row">
    <div class="stat-card blue"><div class="stat-num"><?= count($users_data) ?></div><div class="stat-label">Total Users</div></div>
    <div class="stat-card purple"><div class="stat-num"><?= $stats['admins'] ?></div><div class="stat-label">Admins</div></div>
    <div class="stat-card green"><div class="stat-num"><?= $stats['users'] ?></div><div class="stat-label">Regular Users</div></div>
  </div>

  <div class="section-title">Daftar User</div>
  <div class="table-wrap">
    <table>
      <thead><tr><th>#</th><th>Username</th><th>Role</th><th>Dibuat</th></tr></thead>
      <tbody>
        <?php if (empty($users_data)): ?>
          <tr><td colspan="4" style="color:var(--muted);text-align:center;padding:2rem">Tidak ada data.</td></tr>
        <?php else: ?>
          <?php foreach ($users_data as $u): ?>
          <tr>
            <td style="color:var(--muted)"><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['username']) ?></td>
            <td><span class="role-badge role-<?= $u['role'] ?>"><?= $u['role'] ?></span></td>
            <td style="color:var(--muted)"><?= $u['created_at'] ?></td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="server-info">
    <div>PHP Version: <span><?= phpversion() ?></span></div>
    <div>Server: <span><?= $_SERVER['SERVER_SOFTWARE'] ?? 'Apache' ?></span></div>
    <div>DB Host: <span><?= htmlspecialchars($host) ?></span></div>
    <div>DB Status: <span style="color:<?= $pdo ? 'var(--green)' : 'var(--red)' ?>"><?= $pdo ? 'Connected ✓' : 'Error ✗' ?></span></div>
    <div>Session: <span><?= session_id() ?></span></div>
    <div>Time: <span><?= date('Y-m-d H:i:s') ?></span></div>
  </div>
</main>
<?php endif; ?>

</body>
</html>