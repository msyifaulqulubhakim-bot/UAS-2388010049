<?php require_once 'views/layout/header.php'; ?>

<style>
  .admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    border-bottom: 2px solid var(--border);
    padding-bottom: 1rem;
  }
  .admin-title {
    font-family: 'Playfair Display', serif;
    font-size: 2rem;
    font-weight: 800;
  }
  .btn-add {
    background: var(--accent);
    color: #fff;
    padding: 0.5rem 1.25rem;
    border-radius: 4px;
    font-weight: 600;
    font-size: 0.9rem;
  }
  .btn-add:hover {
    background: var(--accent-hover);
    color: #fff;
  }
  
  /* TABLE */
  .table-container {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 6px;
    overflow-x: auto;
  }
  table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.9rem;
    text-align: left;
  }
  th {
    background: rgba(255, 255, 255, 0.02);
    padding: 1rem;
    color: var(--muted);
    font-weight: 600;
    border-bottom: 1px solid var(--border);
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }
  td {
    padding: 1rem;
    border-bottom: 1px solid var(--border);
  }
  tr:last-child td {
    border-bottom: none;
  }
  tr:hover td {
    background: rgba(255, 255, 255, 0.01);
  }
  
  .td-title {
    font-weight: 600;
    color: #fff;
  }
  .td-badge {
    background: rgba(255, 111, 60, 0.1);
    color: var(--accent);
    padding: 0.15rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
  }
  .actions-cell {
    display: flex;
    gap: 0.75rem;
    justify-content: center;
  }
  .action-btn {
    font-size: 0.8rem;
    font-weight: 600;
    padding: 0.25rem 0.6rem;
    border-radius: 4px;
    border: 1px solid transparent;
  }
  .btn-edit {
    background: rgba(46, 204, 113, 0.15);
    color: var(--green);
    border-color: rgba(46, 204, 113, 0.3);
  }
  .btn-edit:hover {
    background: var(--green);
    color: #000;
  }
  .btn-delete {
    background: rgba(255, 82, 82, 0.15);
    color: var(--red);
    border-color: rgba(255, 82, 82, 0.3);
  }
  .btn-delete:hover {
    background: var(--red);
    color: #fff;
  }
</style>

<div class="admin-header">
  <h2 class="admin-title">Kelola Berita Gunung</h2>
  <a href="/?route=create" class="btn-add">+ Tambah Berita</a>
</div>

<div class="table-container">
  <table>
    <thead>
      <tr>
        <th style="width: 50px;">#</th>
        <th>Judul Berita</th>
        <th>Gunung / Kategori</th>
        <th>Tanggal Rilis</th>
        <th style="width: 150px; text-align: center;">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($articles)): ?>
        <tr>
          <td colspan="5" style="text-align: center; color: var(--muted); padding: 2rem;">
            Tidak ada data artikel.
          </td>
        </tr>
      <?php else: ?>
        <?php foreach ($articles as $index => $art): ?>
          <tr>
            <td style="color: var(--muted);"><?= $index + 1 ?></td>
            <td class="td-title"><?= htmlspecialchars($art['title']) ?></td>
            <td><span class="td-badge"><?= htmlspecialchars($art['mountain']) ?></span></td>
            <td style="color: var(--muted);"><?= date('d M Y, H:i', strtotime($art['created_at'])) ?></td>
            <td style="text-align: center;">
              <div class="actions-cell">
                <a href="/?route=edit&id=<?= $art['id'] ?>" class="action-btn btn-edit">Edit</a>
                <a href="/?route=delete&id=<?= $art['id'] ?>" class="action-btn btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus artikel ini?')">Hapus</a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php require_once 'views/layout/footer.php'; ?>
