<?php require_once 'views/layout/header.php'; ?>

<style>
  .form-container {
    max-width: 700px;
    margin: 0 auto 3rem;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 2.5rem;
  }
  .form-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    border-bottom: 1px solid var(--border);
    padding-bottom: 0.75rem;
  }
  .btn-back {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--muted);
    font-size: 0.9rem;
    margin-bottom: 1.5rem;
  }
  .btn-back:hover {
    color: var(--accent);
  }
  .form-group {
    margin-bottom: 1.5rem;
  }
  .form-group label {
    display: block;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--muted);
    margin-bottom: 0.5rem;
  }
  .form-group input, .form-group textarea {
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
  .form-group input:focus, .form-group textarea:focus {
    border-color: var(--accent);
  }
  .form-group textarea {
    resize: vertical;
    min-height: 150px;
  }
  .btn-submit {
    background: var(--accent);
    color: #fff;
    border: none;
    border-radius: 4px;
    padding: 0.75rem 1.5rem;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
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
    margin-bottom: 1.5rem;
  }
</style>

<div class="form-container">
  <a href="/?route=admin" class="btn-back">← Kembali ke Dashboard</a>
  
  <h2 class="form-title">Tulis Berita Gunung Baru</h2>
  
  <?php if ($error): ?>
    <div class="error-box"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  
  <form method="POST">
    <div class="form-group">
      <label for="title">Judul Berita</label>
      <input type="text" id="title" name="title" placeholder="Contoh: Pendakian Gunung Gede Ditutup Sementara" required/>
    </div>
    
    <div class="form-group">
      <label for="mountain">Nama Gunung</label>
      <input type="text" id="mountain" name="mountain" placeholder="Contoh: Gunung Gede" required/>
    </div>
    
    <div class="form-group">
      <label for="excerpt">Kutipan Singkat (Excerpt)</label>
      <input type="text" id="excerpt" name="excerpt" placeholder="Deskripsi singkat yang tampil di halaman depan" required/>
    </div>
    
    <div class="form-group">
      <label for="content">Konten Berita Lengkap</label>
      <textarea id="content" name="content" placeholder="Ketik konten berita selengkapnya di sini..." required></textarea>
    </div>
    
    <button type="submit" class="btn-submit">Simpan &amp; Rilis Berita</button>
  </form>
</div>

<?php require_once 'views/layout/footer.php'; ?>
