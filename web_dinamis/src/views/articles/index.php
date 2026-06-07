<?php require_once 'views/layout/header.php'; ?>

<style>
  .section-headline {
    font-family: 'Playfair Display', serif;
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 1.5rem;
    border-bottom: 2px solid var(--border);
    padding-bottom: 0.5rem;
    position: relative;
  }
  .section-headline::after {
    content: '';
    position: absolute;
    bottom: -2px; left: 0;
    width: 60px;
    height: 2px;
    background: var(--accent);
  }
  
  /* ARTICLES GRID */
  .articles-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
  }
  .article-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: transform 0.2s, box-shadow 0.2s;
  }
  .article-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
    border-color: var(--accent);
  }
  .article-badge {
    display: inline-block;
    background: rgba(255, 111, 60, 0.15);
    color: var(--accent);
    padding: 0.2rem 0.6rem;
    font-size: 0.7rem;
    font-weight: 700;
    border-radius: 4px;
    margin-bottom: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }
  .article-body {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    flex: 1;
  }
  .article-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.35rem;
    font-weight: 700;
    line-height: 1.25;
    margin-bottom: 0.75rem;
    color: #fff;
  }
  .article-title a:hover {
    color: var(--accent);
  }
  .article-excerpt {
    color: var(--muted);
    font-size: 0.88rem;
    margin-bottom: 1.5rem;
    flex: 1;
  }
  .article-meta {
    font-size: 0.75rem;
    color: var(--muted);
    border-top: 1px solid var(--border);
    padding-top: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .read-more {
    color: var(--accent);
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.85rem;
  }
  .read-more:hover {
    color: var(--accent-hover);
  }
</style>

<h2 class="section-headline">Berita Terkini</h2>

<div class="articles-grid">
  <?php if (empty($articles)): ?>
    <div style="grid-column: 1/-1; text-align: center; padding: 3rem; color: var(--muted)">
      Belum ada artikel berita yang dipublikasikan.
    </div>
  <?php else: ?>
    <?php foreach ($articles as $art): ?>
      <article class="article-card">
        <div class="article-body">
          <div>
            <span class="article-badge"><?= htmlspecialchars($art['mountain']) ?></span>
          </div>
          <h3 class="article-title">
            <a href="/?route=article&id=<?= $art['id'] ?>"><?= htmlspecialchars($art['title']) ?></a>
          </h3>
          <p class="article-excerpt"><?= htmlspecialchars($art['excerpt']) ?></p>
          <div class="article-meta">
            <span><?= date('d M Y', strtotime($art['created_at'])) ?></span>
            <a href="/?route=article&id=<?= $art['id'] ?>" class="read-more">Baca Selengkapnya →</a>
          </div>
        </div>
      </article>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<?php require_once 'views/layout/footer.php'; ?>
