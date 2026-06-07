<?php require_once 'views/layout/header.php'; ?>

<style>
  .article-container {
    max-width: 800px;
    margin: 0 auto 3rem;
  }
  .btn-back {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--muted);
    font-size: 0.9rem;
    margin-bottom: 1.5rem;
    font-weight: 500;
  }
  .btn-back:hover {
    color: var(--accent);
  }
  .single-badge {
    display: inline-block;
    background: rgba(255, 111, 60, 0.15);
    color: var(--accent);
    padding: 0.25rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 700;
    border-radius: 4px;
    margin-bottom: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }
  .single-title {
    font-family: 'Playfair Display', serif;
    font-size: 2.5rem;
    font-weight: 800;
    line-height: 1.15;
    margin-bottom: 1rem;
    color: #fff;
  }
  .single-meta {
    font-size: 0.85rem;
    color: var(--muted);
    margin-bottom: 2rem;
    border-bottom: 1px solid var(--border);
    padding-bottom: 1rem;
    display: flex;
    gap: 1.5rem;
  }
  .single-content {
    font-size: 1.05rem;
    line-height: 1.8;
    color: #cbd5e1;
    text-align: justify;
  }
</style>

<div class="article-container">
  <a href="/" class="btn-back">← Kembali ke Berita</a>
  
  <div>
    <span class="single-badge"><?= htmlspecialchars($article['mountain']) ?></span>
  </div>
  
  <h1 class="single-title"><?= htmlspecialchars($article['title']) ?></h1>
  
  <div class="single-meta">
    <span>Diterbitkan: <strong><?= date('d M Y, H:i', strtotime($article['created_at'])) ?></strong></span>
    <span>Topik: <strong><?= htmlspecialchars($article['mountain']) ?></strong></span>
  </div>
  
  <div class="single-content">
    <?= nl2br(htmlspecialchars($article['content'])) ?>
  </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>
