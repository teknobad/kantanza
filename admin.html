<?php
// Oturum başlatma
session_start();

// Admin giriş kontrolü
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: index.php');
    exit;
}

// Veritabanı bağlantısı
try {
    $db = new PDO('sqlite:forum.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Veritabanı hatası: " . $e->getMessage());
}

// POST isteklerini işleme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Film verilerini güncelleme
    if (isset($_POST['action']) && $_POST['action'] === 'save_movie') {
        $stmt = $db->prepare("
            INSERT OR REPLACE INTO media_recommendations 
            (type, title, image, imdb, rotten_tomatoes, age_limit, genres, actors, description, updated_at) 
            VALUES ('movie', ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)
        ");
        $stmt->execute([
            $_POST['title'],
            $_POST['image'],
            $_POST['imdb'],
            $_POST['rotten_tomatoes'],
            $_POST['age_limit'],
            $_POST['genres'],
            $_POST['actors'],
            $_POST['description']
        ]);
        $message = "Film bilgileri başarıyla kaydedildi.";
    }
    
    // Dizi verilerini güncelleme
    if (isset($_POST['action']) && $_POST['action'] === 'save_series') {
        $stmt = $db->prepare("
            INSERT OR REPLACE INTO media_recommendations 
            (type, title, image, imdb, subject, seasons, best_episode, updated_at) 
            VALUES ('series', ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)
        ");
        $stmt->execute([
            $_POST['title'],
            $_POST['image'],
            $_POST['imdb'],
            $_POST['subject'],
            $_POST['seasons'],
            $_POST['best_episode']
        ]);
        $message = "Dizi bilgileri başarıyla kaydedildi.";
    }
    
    // Kombin verilerini güncelleme
    if (isset($_POST['action']) && $_POST['action'] === 'save_outfit') {
        // Görsel yükleme işlemi
        $imagePath = null;
        if (isset($_FILES['outfit_image']) && $_FILES['outfit_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/outfits/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileExtension = pathinfo($_FILES['outfit_image']['name'], PATHINFO_EXTENSION);
            $fileName = 'outfit_' . time() . '.' . $fileExtension;
            $imagePath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['outfit_image']['tmp_name'], $imagePath)) {
                // Başarılı
            } else {
                $error = "Görsel yüklenirken hata oluştu.";
            }
        }
        
        $stmt = $db->prepare("
            INSERT OR REPLACE INTO daily_outfits 
            (image, hairstyle, hairclip, earrings, necklace, top, pants, shoes, nail_polish, ring, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)
        ");
        $stmt->execute([
            $imagePath,
            $_POST['hairstyle'],
            $_POST['hairclip'],
            $_POST['earrings'],
            $_POST['necklace'],
            $_POST['top'],
            $_POST['pants'],
            $_POST['shoes'],
            $_POST['nail_polish'],
            $_POST['ring']
        ]);
        $message = "Kombin bilgileri başarıyla kaydedildi.";
    }
    
    // Tarif ekleme/güncelleme
    if (isset($_POST['action']) && $_POST['action'] === 'save_recipe') {
        $recipeId = $_POST['recipe_id'] ?: null;
        
        if ($recipeId) {
            // Güncelleme
            $stmt = $db->prepare("
                UPDATE recipes SET 
                title = ?, ingredients = ?, description = ?, full_recipe = ?, updated_at = CURRENT_TIMESTAMP 
                WHERE id = ?
            ");
            $stmt->execute([
                $_POST['title'],
                $_POST['ingredients'],
                $_POST['description'],
                $_POST['full_recipe'],
                $recipeId
            ]);
        } else {
            // Yeni tarif
            $stmt = $db->prepare("
                INSERT INTO recipes (title, ingredients, description, full_recipe, created_at, updated_at) 
                VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
            ");
            $stmt->execute([
                $_POST['title'],
                $_POST['ingredients'],
                $_POST['description'],
                $_POST['full_recipe']
            ]);
        }
        $message = "Tarif başarıyla kaydedildi.";
    }
    
    // Tarif silme
    if (isset($_POST['action']) && $_POST['action'] === 'delete_recipe') {
        $stmt = $db->prepare("DELETE FROM recipes WHERE id = ?");
        $stmt->execute([$_POST['recipe_id']]);
        $message = "Tarif başarıyla silindi.";
    }
    
    // Konu silme
    if (isset($_POST['action']) && $_POST['action'] === 'delete_topic') {
        // Önce konuya ait yorumları sil
        $stmt = $db->prepare("DELETE FROM comments WHERE topic_id = ?");
        $stmt->execute([$_POST['topic_id']]);
        
        // Sonra konuyu sil
        $stmt = $db->prepare("DELETE FROM topics WHERE id = ?");
        $stmt->execute([$_POST['topic_id']]);
        $message = "Konu başarıyla silindi.";
    }
    
    // Yorum silme
    if (isset($_POST['action']) && $_POST['action'] === 'delete_comment') {
        $stmt = $db->prepare("DELETE FROM comments WHERE id = ?");
        $stmt->execute([$_POST['comment_id']]);
        $message = "Yorum başarıyla silindi.";
    }
}

// Verileri veritabanından alma

// İstatistikler
$stats = [
    'topics' => $db->query("SELECT COUNT(*) FROM topics")->fetchColumn(),
    'comments' => $db->query("SELECT COUNT(*) FROM comments")->fetchColumn(),
    'recipes' => $db->query("SELECT COUNT(*) FROM recipes")->fetchColumn(),
    'users' => $db->query("SELECT COUNT(*) FROM users")->fetchColumn()
];

// Konular
$topics = $db->query("
    SELECT t.*, u.username 
    FROM topics t 
    JOIN users u ON t.user_id = u.id 
    ORDER BY t.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Yorumlar
$comments = $db->query("
    SELECT c.*, u.username, t.title as topic_title 
    FROM comments c 
    JOIN users u ON c.user_id = u.id 
    JOIN topics t ON c.topic_id = t.id 
    ORDER BY c.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Tarifler
$recipes = $db->query("SELECT * FROM recipes ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

// Film verileri
$movie = $db->query("SELECT * FROM media_recommendations WHERE type = 'movie' ORDER BY updated_at DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);

// Dizi verileri
$series = $db->query("SELECT * FROM media_recommendations WHERE type = 'series' ORDER BY updated_at DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);

// Kombin verileri
$outfit = $db->query("SELECT * FROM daily_outfits ORDER BY updated_at DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);

// Varsayılan değerler
$defaultMovie = [
    'title' => 'Karanlığın Ormanı',
    'actors' => 'A. Kaya, B. Demir, C. Öz',
    'description' => 'Issız bir dağ kasabasında kaybolan kampçılar, eski bir efsanenin peşinden sürüklenir.',
    'imdb' => '8.6',
    'rotten_tomatoes' => '94%',
    'age_limit' => '+18 değil',
    'genres' => 'Macera • Korku • Gizem',
    'image' => ''
];

$defaultSeries = [
    'title' => 'Zamanın Ötesinde',
    'imdb' => '8.8/10',
    'subject' => 'Gelecekten gelen bir zaman yolcusunun, tarihi olayları değiştirmeye çalışan bir organizasyonla mücadelesi.',
    'seasons' => '3 sezon',
    'best_episode' => 'Sonsuzluk Yolculuğu (3. Sezon 8. Bölüm)',
    'image' => ''
];

$defaultOutfit = [
    'hairstyle' => 'Topuz',
    'hairclip' => 'Good Hair Days Magic-grip Saç Topuz Tokası',
    'earrings' => 'Halka küpe',
    'necklace' => 'Mini taşlı kolye',
    'top' => 'Yaka detaylı crop ekru gömlek',
    'pants' => 'Indigo mavisi jean gömlek',
    'shoes' => 'Lumberjack ROMINA 5FX siyah koşu ayakkabısı',
    'nail_polish' => 'Pastel oje',
    'ring' => 'Eklem yüzüğü'
];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>KANTANZA - Admin Paneli</title>
  <style>
    :root{
      --bg:#0f1115;
      --card:#151922;
      --ink:#e8ebf1;
      --muted:#a9b0bf;
      --accent:#69bffc;
      --line:#2b3240;
      --danger:#ff6b6b;
      --ok:#7fdc82;
    }
    *{box-sizing:border-box}
    body{margin:0;background:var(--bg);color:var(--ink);font-family:Calibri, Arial, Helvetica, sans-serif;}

    /* Genel iskelet */
    .container{max-width:1200px;margin:0 auto;padding:16px;display:grid;grid-template-columns:1fr;gap:16px}
    .card{background:var(--card);border:1px solid var(--line);border-radius:16px;box-shadow:0 8px 24px rgba(0,0,0,.25)}
    .pad{padding:16px}
    .row{display:flex;align-items:center;gap:10px}
    .between{display:flex;align-items:center;justify-content:space-between}
    .line{height:1px;background:var(--line);}
    .thick-line{height:4px;background:var(--line);border-radius:2px}
    a{color:var(--ink);text-decoration:none}
    button{background:var(--accent);color:#0b0d12;border:none;border-radius:12px;padding:10px 14px;font-weight:700;cursor:pointer}
    button.ghost{background:transparent;color:var(--ink);border:1px solid var(--line)}
    button.danger{background:var(--danger);color:white}
    button:disabled{opacity:.6;cursor:not-allowed}
    input, textarea, select{width:100%;background:#0f131a;border:1px solid var(--line);border-radius:10px;color:var(--ink);padding:10px}
    label{display:block;margin:10px 0 6px}

    /* Başlık alanı */
    header.card{grid-column:1 / -1;}
    .brand{font-family:'Arial Black', Arial, Helvetica, sans-serif;font-size:18pt;font-weight:900;letter-spacing:.5px;cursor:pointer}
    .brand-underline{height:3px;background:var(--ink);width:max(140px, 14ch);border-radius:3px;margin-top:6px}
    .auth-actions{gap:10px}

    /* Başlık alanı - ORTALAMA İÇİN */
    .header-content {
      display: flex;
      align-items: center;
      justify-content: space-between;
      width: 100%;
    }

    .brand-container {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    /* Admin Grid */
    .admin-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }

    /* Tablo Stilleri */
    .admin-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 12px;
    }
    
    .admin-table th, .admin-table td {
      padding: 10px;
      text-align: left;
      border-bottom: 1px solid var(--line);
    }
    
    .admin-table th {
      background: rgba(255,255,255,0.05);
      font-weight: bold;
    }
    
    .admin-table tr:hover {
      background: rgba(255,255,255,0.03);
    }

    /* Form Stilleri */
    .admin-form {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }
    
    .form-group {
      margin-bottom: 12px;
    }
    
    .form-actions {
      display: flex;
      gap: 10px;
      justify-content: flex-end;
      margin-top: 16px;
    }

    /* Bölümler */
    .section {
      margin-bottom: 24px;
    }
    
    .section-title {
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 12px;
      padding-bottom: 8px;
      border-bottom: 1px solid var(--line);
    }

    /* İstatistikler */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px;
      margin-bottom: 24px;
    }
    
    .stat-card {
      background: var(--card);
      border-radius: 12px;
      padding: 16px;
      text-align: center;
      border: 1px solid var(--line);
    }
    
    .stat-number {
      font-size: 28px;
      font-weight: bold;
      color: var(--accent);
      margin-bottom: 4px;
    }
    
    .stat-label {
      font-size: 14px;
      color: var(--muted);
    }

    /* Küçük butonlar */
    .small-button {
      padding: 6px 10px;
      font-size: 12px;
    }

    /* Görsel Yükleme Alanı */
    .image-upload-area {
      width: 100%;
      height: 200px;
      border: 2px dashed var(--line);
      border-radius: 12px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-bottom: 16px;
      position: relative;
      overflow: hidden;
    }
    
    .image-upload-area:hover {
      border-color: var(--accent);
      background: rgba(105, 191, 252, 0.05);
    }
    
    .image-upload-area i {
      font-size: 48px;
      color: var(--muted);
      margin-bottom: 12px;
    }
    
    .image-upload-area .upload-text {
      color: var(--muted);
      text-align: center;
      padding: 0 16px;
    }
    
    .image-preview {
      width: 100%;
      height: 100%;
      display: none;
      position: relative;
    }
    
    .image-preview img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 8px;
    }
    
    .image-actions {
      position: absolute;
      bottom: 12px;
      right: 12px;
      display: flex;
      gap: 8px;
    }
    
    .image-action-btn {
      background: rgba(15, 17, 21, 0.8);
      color: white;
      border: none;
      border-radius: 6px;
      padding: 6px 12px;
      font-size: 12px;
      cursor: pointer;
      backdrop-filter: blur(4px);
    }

    /* Mesaj bildirimleri */
    .message {
      padding: 12px 16px;
      border-radius: 8px;
      margin-bottom: 16px;
    }
    
    .message.success {
      background: rgba(127, 220, 130, 0.2);
      border: 1px solid var(--ok);
      color: var(--ok);
    }
    
    .message.error {
      background: rgba(255, 107, 107, 0.2);
      border: 1px solid var(--danger);
      color: var(--danger);
    }

    /* Mobil için responsive */
    @media (max-width: 768px) {
      .header-content {
        flex-direction: column;
        gap: 12px;
      }
      
      .auth-actions {
        width: 100%;
        justify-content: center;
      }
      
      .admin-grid {
        grid-template-columns: 1fr;
      }
      
      .stats-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }
  </style>
</head>
<body>
  <!-- ÜST BÖLÜM: Marka ve Auth -->
  <header class="card pad">
    <div class="header-content">
      <div class="brand-container">
        <div class="brand">KANTANZA - Admin Paneli</div>
        <div class="brand-underline"></div>
      </div>
      <div class="auth-actions">
        <a href="index.php" class="ghost button">Foruma Dön</a>
        <form method="post" style="display: inline;">
          <input type="hidden" name="action" value="logout">
          <button type="submit" class="danger">Çıkış</button>
        </form>
      </div>
    </div>
    <div class="line" style="margin-top:12px"></div>
  </header>

  <main class="container">
    <!-- Mesaj Bildirimleri -->
    <?php if (isset($message)): ?>
      <div class="message success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
      <div class="message error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- İstatistikler -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-number"><?php echo $stats['topics']; ?></div>
        <div class="stat-label">Toplam Konu</div>
      </div>
      <div class="stat-card">
        <div class="stat-number"><?php echo $stats['comments']; ?></div>
        <div class="stat-label">Toplam Yorum</div>
      </div>
      <div class="stat-card">
        <div class="stat-number"><?php echo $stats['recipes']; ?></div>
        <div class="stat-label">Yemek Tarifi</div>
      </div>
      <div class="stat-card">
        <div class="stat-number"><?php echo $stats['users']; ?></div>
        <div class="stat-label">Kayıtlı Kullanıcı</div>
      </div>
    </div>

    <div class="admin-grid">
      <!-- Sol Kolon -->
      <div>
        <!-- Konu Yönetimi -->
        <div class="card pad section">
          <div class="section-title">Konu Yönetimi</div>
          <div class="admin-table-container">
            <table class="admin-table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Başlık</th>
                  <th>Yazar</th>
                  <th>Tarih</th>
                  <th>İşlem</th>
                </tr>
              </thead>
              <tbody id="topicsTable">
                <?php if (empty($topics)): ?>
                  <tr>
                    <td colspan="5" style="text-align: center; color: var(--muted);">Henüz konu bulunmuyor</td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($topics as $topic): ?>
                    <tr>
                      <td>#<?php echo $topic['id']; ?></td>
                      <td><?php echo htmlspecialchars($topic['title']); ?></td>
                      <td><?php echo htmlspecialchars($topic['username']); ?></td>
                      <td><?php echo date('d.m.Y', strtotime($topic['created_at'])); ?></td>
                      <td>
                        <form method="post" style="display: inline;">
                          <input type="hidden" name="action" value="delete_topic">
                          <input type="hidden" name="topic_id" value="<?php echo $topic['id']; ?>">
                          <button type="submit" class="danger small-button" onclick="return confirm('Bu konuyu silmek istediğinizden emin misiniz?')">Sil</button>
                        </form>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Yorum Yönetimi -->
        <div class="card pad section">
          <div class="section-title">Yorum Yönetimi</div>
          <div class="admin-table-container">
            <table class="admin-table">
              <thead>
                <tr>
                  <th>Konu</th>
                  <th>Yorum</th>
                  <th>Yazar</th>
                  <th>Tarih</th>
                  <th>İşlem</th>
                </tr>
              </thead>
              <tbody id="commentsTable">
                <?php if (empty($comments)): ?>
                  <tr>
                    <td colspan="5" style="text-align: center; color: var(--muted);">Henüz yorum bulunmuyor</td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($comments as $comment): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($comment['topic_title']); ?></td>
                      <td><?php echo htmlspecialchars(mb_substr($comment['text'], 0, 50)); ?><?php echo mb_strlen($comment['text']) > 50 ? '...' : ''; ?></td>
                      <td><?php echo htmlspecialchars($comment['username']); ?></td>
                      <td><?php echo date('d.m.Y', strtotime($comment['created_at'])); ?></td>
                      <td>
                        <form method="post" style="display: inline;">
                          <input type="hidden" name="action" value="delete_comment">
                          <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                          <button type="submit" class="danger small-button" onclick="return confirm('Bu yorumu silmek istediğinizden emin misiniz?')">Sil</button>
                        </form>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Sağ Kolon -->
      <div>
        <!-- Film Tavsiyesi Düzenleme -->
        <div class="card pad section">
          <div class="section-title">Film Tavsiyesi Düzenleme</div>
          <form method="post" class="admin-form">
            <input type="hidden" name="action" value="save_movie">
            <div class="form-group">
              <label for="movieTitle">Film Adı</label>
              <input type="text" id="movieTitle" name="title" placeholder="Film adı" 
                     value="<?php echo htmlspecialchars($movie ? $movie['title'] : $defaultMovie['title']); ?>">
            </div>
            <div class="form-group">
              <label for="movieActors">Oyuncular</label>
              <input type="text" id="movieActors" name="actors" placeholder="Oyuncu isimleri"
                     value="<?php echo htmlspecialchars($movie ? $movie['actors'] : $defaultMovie['actors']); ?>">
            </div>
            <div class="form-group">
              <label for="movieDescription">Açıklama</label>
              <textarea id="movieDescription" name="description" rows="3" placeholder="Film açıklaması"><?php echo htmlspecialchars($movie ? $movie['description'] : $defaultMovie['description']); ?></textarea>
            </div>
            <div class="form-group">
              <label for="movieImdb">IMDb Puanı</label>
              <input type="text" id="movieImdb" name="imdb" placeholder="8.6"
                     value="<?php echo htmlspecialchars($movie ? $movie['imdb'] : $defaultMovie['imdb']); ?>">
            </div>
            <div class="form-group">
              <label for="movieRottenTomatoes">Rotten Tomatoes</label>
              <input type="text" id="movieRottenTomatoes" name="rotten_tomatoes" placeholder="94%"
                     value="<?php echo htmlspecialchars($movie ? $movie['rotten_tomatoes'] : $defaultMovie['rotten_tomatoes']); ?>">
            </div>
            <div class="form-group">
              <label for="movieAgeLimit">Yaş Sınırı</label>
              <input type="text" id="movieAgeLimit" name="age_limit" placeholder="+18 değil"
                     value="<?php echo htmlspecialchars($movie ? $movie['age_limit'] : $defaultMovie['age_limit']); ?>">
            </div>
            <div class="form-group">
              <label for="movieGenres">Türler</label>
              <input type="text" id="movieGenres" name="genres" placeholder="Macera • Korku • Gizem"
                     value="<?php echo htmlspecialchars($movie ? $movie['genres'] : $defaultMovie['genres']); ?>">
            </div>
            <div class="form-group">
              <label for="movieImage">Görsel URL</label>
              <input type="url" id="movieImage" name="image" placeholder="Film görseli URL"
                     value="<?php echo htmlspecialchars($movie ? $movie['image'] : $defaultMovie['image']); ?>">
            </div>
            <div class="form-actions">
              <button type="button" class="ghost" onclick="resetMovieForm()">Sıfırla</button>
              <button type="submit">Film Bilgisini Kaydet</button>
            </div>
          </form>
        </div>

        <!-- Dizi Tavsiyesi Düzenleme -->
        <div class="card pad section">
          <div class="section-title">Dizi Tavsiyesi Düzenleme</div>
          <form method="post" class="admin-form">
            <input type="hidden" name="action" value="save_series">
            <div class="form-group">
              <label for="seriesTitle">Dizi Adı</label>
              <input type="text" id="seriesTitle" name="title" placeholder="Dizi adı"
                     value="<?php echo htmlspecialchars($series ? $series['title'] : $defaultSeries['title']); ?>">
            </div>
            <div class="form-group">
              <label for="seriesImdb">IMDb Puanı</label>
              <input type="text" id="seriesImdb" name="imdb" placeholder="8.8/10"
                     value="<?php echo htmlspecialchars($series ? $series['imdb'] : $defaultSeries['imdb']); ?>">
            </div>
            <div class="form-group">
              <label for="seriesSubject">Konu</label>
              <textarea id="seriesSubject" name="subject" rows="3" placeholder="Dizi konusu"><?php echo htmlspecialchars($series ? $series['subject'] : $defaultSeries['subject']); ?></textarea>
            </div>
            <div class="form-group">
              <label for="seriesSeasons">Sezon Sayısı</label>
              <input type="text" id="seriesSeasons" name="seasons" placeholder="3 sezon"
                     value="<?php echo htmlspecialchars($series ? $series['seasons'] : $defaultSeries['seasons']); ?>">
            </div>
            <div class="form-group">
              <label for="seriesBestEpisode">En Sevilen Bölüm</label>
              <input type="text" id="seriesBestEpisode" name="best_episode" placeholder="Sonsuzluk Yolculuğu (3. Sezon 8. Bölüm)"
                     value="<?php echo htmlspecialchars($series ? $series['best_episode'] : $defaultSeries['best_episode']); ?>">
            </div>
            <div class="form-group">
              <label for="seriesImage">Görsel URL</label>
              <input type="url" id="seriesImage" name="image" placeholder="Dizi görseli URL"
                     value="<?php echo htmlspecialchars($series ? $series['image'] : $defaultSeries['image']); ?>">
            </div>
            <div class="form-actions">
              <button type="button" class="ghost" onclick="resetSeriesForm()">Sıfırla</button>
              <button type="submit">Dizi Bilgisini Kaydet</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Kombin Düzenleme -->
    <div class="card pad section">
      <div class="section-title">Kombin Düzenleme</div>
      <form method="post" class="admin-form" enctype="multipart/form-data">
        <input type="hidden" name="action" value="save_outfit">
        
        <!-- Kombin Görsel Yükleme -->
        <div class="form-group">
          <label>Kombin Görseli</label>
          <div class="image-upload-area" id="outfitImageUploadArea" onclick="document.getElementById('outfitImageInput').click()">
            <?php if ($outfit && !empty($outfit['image'])): ?>
              <div class="image-preview" id="outfitImagePreview" style="display: block;">
                <img id="outfitPreviewImage" src="<?php echo htmlspecialchars($outfit['image']); ?>" alt="Kombin görseli">
                <div class="image-actions">
                  <button type="button" class="image-action-btn" onclick="event.stopPropagation(); document.getElementById('outfitImageInput').click()">Değiştir</button>
                  <button type="button" class="image-action-btn danger" onclick="event.stopPropagation(); resetOutfitImage()">Kaldır</button>
                </div>
              </div>
              <div class="upload-placeholder" id="outfitUploadPlaceholder" style="display: none;">
                <i>📷</i>
                <div class="upload-text">Kombin görseli yüklemek için tıklayın</div>
                <small style="margin-top: 8px; color: var(--muted);">PNG, JPG, GIF - Maks. 5MB</small>
              </div>
            <?php else: ?>
              <div class="upload-placeholder" id="outfitUploadPlaceholder">
                <i>📷</i>
                <div class="upload-text">Kombin görseli yüklemek için tıklayın</div>
                <small style="margin-top: 8px; color: var(--muted);">PNG, JPG, GIF - Maks. 5MB</small>
              </div>
              <div class="image-preview" id="outfitImagePreview">
                <img id="outfitPreviewImage" src="" alt="Kombin görseli">
                <div class="image-actions">
                  <button type="button" class="image-action-btn" onclick="event.stopPropagation(); document.getElementById('outfitImageInput').click()">Değiştir</button>
                  <button type="button" class="image-action-btn danger" onclick="event.stopPropagation(); resetOutfitImage()">Kaldır</button>
                </div>
              </div>
            <?php endif; ?>
            <input type="file" id="outfitImageInput" name="outfit_image" accept="image/*" style="display: none;" onchange="handleOutfitImageUpload(this)">
          </div>
        </div>

        <div class="form-group">
          <label for="outfitHairstyle">Saç Şekli</label>
          <input type="text" id="outfitHairstyle" name="hairstyle" placeholder="Saç şekli"
                 value="<?php echo htmlspecialchars($outfit ? $outfit['hairstyle'] : $defaultOutfit['hairstyle']); ?>">
        </div>
        <div class="form-group">
          <label for="outfitHairclip">Toka</label>
          <input type="text" id="outfitHairclip" name="hairclip" placeholder="Toka modeli"
                 value="<?php echo htmlspecialchars($outfit ? $outfit['hairclip'] : $defaultOutfit['hairclip']); ?>">
        </div>
        <div class="form-group">
          <label for="outfitEarrings">Küpe</label>
          <input type="text" id="outfitEarrings" name="earrings" placeholder="Küpe modeli"
                 value="<?php echo htmlspecialchars($outfit ? $outfit['earrings'] : $defaultOutfit['earrings']); ?>">
        </div>
        <div class="form-group">
          <label for="outfitNecklace">Kolye</label>
          <input type="text" id="outfitNecklace" name="necklace" placeholder="Kolye modeli"
                 value="<?php echo htmlspecialchars($outfit ? $outfit['necklace'] : $defaultOutfit['necklace']); ?>">
        </div>
        <div class="form-group">
          <label for="outfitTop">Üst Giyim</label>
          <input type="text" id="outfitTop" name="top" placeholder="Üst giyim"
                 value="<?php echo htmlspecialchars($outfit ? $outfit['top'] : $defaultOutfit['top']); ?>">
        </div>
        <div class="form-group">
          <label for="outfitPants">Pantolon</label>
          <input type="text" id="outfitPants" name="pants" placeholder="Pantolon modeli"
                 value="<?php echo htmlspecialchars($outfit ? $outfit['pants'] : $defaultOutfit['pants']); ?>">
        </div>
        <div class="form-group">
          <label for="outfitShoes">Ayakkabı</label>
          <input type="text" id="outfitShoes" name="shoes" placeholder="Ayakkabı modeli"
                 value="<?php echo htmlspecialchars($outfit ? $outfit['shoes'] : $defaultOutfit['shoes']); ?>">
        </div>
        <div class="form-group">
          <label for="outfitNailPolish">Oje</label>
          <input type="text" id="outfitNailPolish" name="nail_polish" placeholder="Oje rengi/tipi"
                 value="<?php echo htmlspecialchars($outfit ? $outfit['nail_polish'] : $defaultOutfit['nail_polish']); ?>">
        </div>
        <div class="form-group">
          <label for="outfitRing">Yüzük</label>
          <input type="text" id="outfitRing" name="ring" placeholder="Yüzük modeli"
                 value="<?php echo htmlspecialchars($outfit ? $outfit['ring'] : $defaultOutfit['ring']); ?>">
        </div>
        <div class="form-actions">
          <button type="button" class="ghost" onclick="resetOutfitForm()">Sıfırla</button>
          <button type="submit">Kombini Kaydet</button>
        </div>
      </form>
    </div>

    <!-- Yemek Tarifleri Yönetimi -->
    <div class="card pad section">
      <div class="section-title">Yemek Tarifleri Yönetimi</div>
      
      <!-- Tarif Formu -->
      <form method="post" class="admin-form" id="recipeForm">
        <input type="hidden" name="action" value="save_recipe">
        <input type="hidden" name="recipe_id" id="recipeId" value="">
        
        <div class="form-group">
          <label for="recipeTitle">Tarif Adı</label>
          <input type="text" id="recipeTitle" name="title" placeholder="Tarif adı">
        </div>
        <div class="form-group">
          <label for="recipeIngredients">Malzemeler (virgülle ayırın)</label>
          <textarea id="recipeIngredients" name="ingredients" rows="3" placeholder="Malzemeler"></textarea>
        </div>
        <div class="form-group">
          <label for="recipeDescription">Kısa Açıklama</label>
          <input type="text" id="recipeDescription" name="description" placeholder="Kısa açıklama">
        </div>
        <div class="form-group">
          <label for="recipeFull">Tam Tarif</label>
          <textarea id="recipeFull" name="full_recipe" rows="4" placeholder="Tam tarif"></textarea>
        </div>
        <div class="form-actions">
          <button type="button" class="ghost" onclick="resetRecipeForm()">Temizle</button>
          <button type="submit" id="btnSaveRecipe">Tarifi Kaydet</button>
        </div>
      </form>
      
      <div class="section-title" style="margin-top: 24px;">Mevcut Tarifler</div>
      <div class="admin-table-container">
        <table class="admin-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Tarif Adı</th>
              <th>Malzeme Sayısı</th>
              <th>Tarih</th>
              <th>İşlem</th>
            </tr>
          </thead>
          <tbody id="recipesTable">
            <?php if (empty($recipes)): ?>
              <tr>
                <td colspan="5" style="text-align: center; color: var(--muted);">Henüz tarif bulunmuyor</td>
              </tr>
            <?php else: ?>
              <?php foreach ($recipes as $recipe): ?>
                <tr>
                  <td>#<?php echo $recipe['id']; ?></td>
                  <td><?php echo htmlspecialchars($recipe['title']); ?></td>
                  <td><?php echo $recipe['ingredients'] ? count(explode(',', $recipe['ingredients'])) : 0; ?></td>
                  <td><?php echo date('d.m.Y', strtotime($recipe['created_at'])); ?></td>
                  <td>
                    <button type="button" class="ghost small-button" onclick="editRecipe(<?php echo $recipe['id']; ?>, '<?php echo htmlspecialchars($recipe['title']); ?>', '<?php echo htmlspecialchars($recipe['ingredients']); ?>', '<?php echo htmlspecialchars($recipe['description']); ?>', '<?php echo htmlspecialchars($recipe['full_recipe']); ?>')">Düzenle</button>
                    <form method="post" style="display: inline;">
                      <input type="hidden" name="action" value="delete_recipe">
                      <input type="hidden" name="recipe_id" value="<?php echo $recipe['id']; ?>">
                      <button type="submit" class="danger small-button" onclick="return confirm('Bu tarifi silmek istediğinizden emin misiniz?')">Sil</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <script>
    // Film formunu sıfırla
    function resetMovieForm() {
      document.getElementById('movieTitle').value = '<?php echo $defaultMovie['title']; ?>';
      document.getElementById('movieActors').value = '<?php echo $defaultMovie['actors']; ?>';
      document.getElementById('movieDescription').value = '<?php echo $defaultMovie['description']; ?>';
      document.getElementById('movieImdb').value = '<?php echo $defaultMovie['imdb']; ?>';
      document.getElementById('movieRottenTomatoes').value = '<?php echo $defaultMovie['rotten_tomatoes']; ?>';
      document.getElementById('movieAgeLimit').value = '<?php echo $defaultMovie['age_limit']; ?>';
      document.getElementById('movieGenres').value = '<?php echo $defaultMovie['genres']; ?>';
      document.getElementById('movieImage').value = '<?php echo $defaultMovie['image']; ?>';
    }

    // Dizi formunu sıfırla
    function resetSeriesForm() {
      document.getElementById('seriesTitle').value = '<?php echo $defaultSeries['title']; ?>';
      document.getElementById('seriesImdb').value = '<?php echo $defaultSeries['imdb']; ?>';
      document.getElementById('seriesSubject').value = '<?php echo $defaultSeries['subject']; ?>';
      document.getElementById('seriesSeasons').value = '<?php echo $defaultSeries['seasons']; ?>';
      document.getElementById('seriesBestEpisode').value = '<?php echo $defaultSeries['best_episode']; ?>';
      document.getElementById('seriesImage').value = '<?php echo $defaultSeries['image']; ?>';
    }

    // Kombin formunu sıfırla
    function resetOutfitForm() {
      document.getElementById('outfitHairstyle').value = '<?php echo $defaultOutfit['hairstyle']; ?>';
      document.getElementById('outfitHairclip').value = '<?php echo $defaultOutfit['hairclip']; ?>';
      document.getElementById('outfitEarrings').value = '<?php echo $defaultOutfit['earrings']; ?>';
      document.getElementById('outfitNecklace').value = '<?php echo $defaultOutfit['necklace']; ?>';
      document.getElementById('outfitTop').value = '<?php echo $defaultOutfit['top']; ?>';
      document.getElementById('outfitPants').value = '<?php echo $defaultOutfit['pants']; ?>';
      document.getElementById('outfitShoes').value = '<?php echo $defaultOutfit['shoes']; ?>';
      document.getElementById('outfitNailPolish').value = '<?php echo $defaultOutfit['nail_polish']; ?>';
      document.getElementById('outfitRing').value = '<?php echo $defaultOutfit['ring']; ?>';
      resetOutfitImage();
    }

    // Kombin görselini sıfırla
    function resetOutfitImage() {
      document.getElementById('outfitUploadPlaceholder').style.display = 'flex';
      document.getElementById('outfitImagePreview').style.display = 'none';
      document.getElementById('outfitImageInput').value = '';
    }

    // Kombin görseli yükleme
    function handleOutfitImageUpload(input) {
      const file = input.files[0];
      if (!file) return;
      
      // Dosya türü kontrolü
      if (!file.type.match('image.*')) {
        alert('Sadece resim dosyaları yükleyebilirsiniz!');
        return;
      }
      
      // Dosya boyutu kontrolü (max 5MB)
      if (file.size > 5 * 1024 * 1024) {
        alert('Resim boyutu 5MB\'dan küçük olmalıdır!');
        return;
      }
      
      const reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById('outfitUploadPlaceholder').style.display = 'none';
        document.getElementById('outfitImagePreview').style.display = 'block';
        document.getElementById('outfitPreviewImage').src = e.target.result;
      };
      reader.readAsDataURL(file);
    }

    // Tarif formunu sıfırla
    function resetRecipeForm() {
      document.getElementById('recipeForm').reset();
      document.getElementById('recipeId').value = '';
      document.getElementById('btnSaveRecipe').textContent = 'Tarifi Kaydet';
    }

    // Tarif düzenle
    function editRecipe(id, title, ingredients, description, fullRecipe) {
      document.getElementById('recipeId').value = id;
      document.getElementById('recipeTitle').value = title;
      document.getElementById('recipeIngredients').value = ingredients;
      document.getElementById('recipeDescription').value = description;
      document.getElementById('recipeFull').value = fullRecipe;
      document.getElementById('btnSaveRecipe').textContent = 'Tarifi Güncelle';
      
      // Forma scroll yap
      document.getElementById('recipeForm').scrollIntoView({ behavior: 'smooth' });
    }
  </script>
</body>
</html>
