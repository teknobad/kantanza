<?php
// Oturum başlatma
session_start();

// Veritabanı bağlantısı (Örnek olarak SQLite kullanıyoruz)
try {
    $db = new PDO('sqlite:forum.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Tabloları oluştur (eğer yoksa)
    $db->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            email TEXT UNIQUE NOT NULL,
            username TEXT NOT NULL,
            password TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            is_admin INTEGER DEFAULT 0
        )
    ");
    
    $db->exec("
        CREATE TABLE IF NOT EXISTS topics (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            body TEXT NOT NULL,
            user_id INTEGER NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        )
    ");
    
    $db->exec("
        CREATE TABLE IF NOT EXISTS comments (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            topic_id INTEGER NOT NULL,
            user_id INTEGER NOT NULL,
            text TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (topic_id) REFERENCES topics(id),
            FOREIGN KEY (user_id) REFERENCES users(id)
        )
    ");
    
    $db->exec("
        CREATE TABLE IF NOT EXISTS media_recommendations (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            type TEXT NOT NULL,
            title TEXT NOT NULL,
            image TEXT,
            imdb TEXT,
            rotten_tomatoes TEXT,
            age_limit TEXT,
            genres TEXT,
            actors TEXT,
            description TEXT,
            seasons TEXT,
            best_episode TEXT,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    $db->exec("
        CREATE TABLE IF NOT EXISTS daily_outfits (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            image TEXT,
            hairstyle TEXT,
            hairclip TEXT,
            earrings TEXT,
            necklace TEXT,
            top TEXT,
            pants TEXT,
            shoes TEXT,
            nail_polish TEXT,
            ring TEXT,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    // Varsayılan admin kullanıcısını oluştur
    $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = 'admin'");
    $stmt->execute();
    $adminExists = $stmt->fetchColumn();
    
    if (!$adminExists) {
        $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO users (email, username, password, is_admin) VALUES (?, ?, ?, ?)");
        $stmt->execute(['admin@kantanza.com', 'admin', $hashedPassword, 1]);
    }
    
} catch(PDOException $e) {
    die("Veritabanı hatası: " . $e->getMessage());
}

// POST isteklerini işleme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kullanıcı girişi
    if (isset($_POST['action']) && $_POST['action'] === 'login') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = $user['is_admin'];
            header('Location: index.php');
            exit;
        } else {
            $error = "Kullanıcı adı veya şifre hatalı!";
        }
    }
    
    // Kullanıcı kaydı
    if (isset($_POST['action']) && $_POST['action'] === 'signup') {
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
        
        // Validasyon
        if (empty($email) || empty($username) || empty($password) || empty($password2)) {
            $error = "Tüm alanlar gereklidir!";
        } elseif ($password !== $password2) {
            $error = "Şifreler eşleşmiyor!";
        } elseif (strlen($password) < 5) {
            $error = "Şifre en az 5 karakter olmalıdır!";
        } elseif (!preg_match('/[A-Z]/', $password)) {
            $error = "Şifre en az bir büyük harf içermelidir!";
        } elseif (!preg_match('/\d/', $password)) {
            $error = "Şifre en az bir rakam içermelidir!";
        } else {
            // E-posta kontrolü
            $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetchColumn() > 0) {
                $error = "Bu e-posta adresi zaten kayıtlı!";
            } else {
                // Kullanıcı adı kontrolü
                $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
                $stmt->execute([$username]);
                if ($stmt->fetchColumn() > 0) {
                    $error = "Bu kullanıcı adı zaten alınmış!";
                } else {
                    // Kullanıcıyı kaydet
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $db->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
                    $stmt->execute([$email, $username, $hashedPassword]);
                    
                    // Oturum aç
                    $_SESSION['user_id'] = $db->lastInsertId();
                    $_SESSION['username'] = $username;
                    $_SESSION['is_admin'] = 0;
                    header('Location: index.php');
                    exit;
                }
            }
        }
    }
    
    // Konu oluşturma
    if (isset($_POST['action']) && $_POST['action'] === 'create_topic') {
        if (!isset($_SESSION['user_id'])) {
            $error = "Konu oluşturmak için giriş yapmalısınız!";
        } else {
            $title = $_POST['title'];
            $body = $_POST['body'];
            
            if (empty($title) || empty($body)) {
                $error = "Başlık ve içerik gereklidir!";
            } elseif (strlen($title) > 15) {
                $error = "Başlık 15 karakterden uzun olamaz!";
            } elseif (strlen($body) > 250) {
                $error = "İçerik 250 karakterden uzun olamaz!";
            } else {
                $stmt = $db->prepare("INSERT INTO topics (title, body, user_id) VALUES (?, ?, ?)");
                $stmt->execute([$title, $body, $_SESSION['user_id']]);
                header('Location: index.php');
                exit;
            }
        }
    }
    
    // Yorum ekleme
    if (isset($_POST['action']) && $_POST['action'] === 'add_comment') {
        if (!isset($_SESSION['user_id'])) {
            $error = "Yorum yapmak için giriş yapmalısınız!";
        } else {
            $topic_id = $_POST['topic_id'];
            $text = $_POST['text'];
            
            if (empty($text)) {
                $error = "Yorum boş olamaz!";
            } elseif (strlen($text) > 250) {
                $error = "Yorum 250 karakterden uzun olamaz!";
            } else {
                $stmt = $db->prepare("INSERT INTO comments (topic_id, user_id, text) VALUES (?, ?, ?)");
                $stmt->execute([$topic_id, $_SESSION['user_id'], $text]);
                header('Location: index.php?topic=' . $topic_id);
                exit;
            }
        }
    }
    
    // Çıkış yapma
    if (isset($_POST['action']) && $_POST['action'] === 'logout') {
        session_destroy();
        header('Location: index.php');
        exit;
    }
}

// Verileri veritabanından alma
$topics = $db->query("
    SELECT t.*, u.username 
    FROM topics t 
    JOIN users u ON t.user_id = u.id 
    ORDER BY t.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Film ve dizi önerilerini al
$movie = $db->query("SELECT * FROM media_recommendations WHERE type = 'movie' ORDER BY updated_at DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$series = $db->query("SELECT * FROM media_recommendations WHERE type = 'series' ORDER BY updated_at DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);

// Günlük kombin verisini al
$outfit = $db->query("SELECT * FROM daily_outfits ORDER BY updated_at DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);

// Eğer URL'de topic parametresi varsa, konu detayını göster
$current_topic = null;
$comments = [];
if (isset($_GET['topic'])) {
    $topic_id = intval($_GET['topic']);
    $stmt = $db->prepare("
        SELECT t.*, u.username 
        FROM topics t 
        JOIN users u ON t.user_id = u.id 
        WHERE t.id = ?
    ");
    $stmt->execute([$topic_id]);
    $current_topic = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($current_topic) {
        $stmt = $db->prepare("
            SELECT c.*, u.username 
            FROM comments c 
            JOIN users u ON c.user_id = u.id 
            WHERE c.topic_id = ? 
            ORDER BY c.created_at ASC
        ");
        $stmt->execute([$topic_id]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Yemek tarifleri
$recipes = [
    [
        'id' => 1,
        'title' => "Fırında Baharatlı Tavuk",
        'ingredientCount' => 7,
        'ingredients' => "Tavuk baget, zeytinyağı, sarımsak, kekik, kırmızı biber, tuz, karabiber",
        'description' => "Tavuk bagetler zeytinyağı ve baharatla harmanlanıp fırında kızartılır.",
        'fullRecipe' => "Malzemeleri bir kapta karıştırın. Tavukları marine edip 30 dakika bekletin. 200°C önceden ısıtılmış fırında 35-40 dakika pişirin."
    ],
    [
        'id' => 2,
        'title' => "Mercimek Çorbası",
        'ingredientCount' => 8,
        'ingredients' => "Kırmızı mercimek, soğan, havuç, patates, tereyağı, un, limon, tuz",
        'description' => "Klasik Türk mutfağından doyurucu bir çorba.",
        'fullRecipe' => "Soğanı ve havucu küp küp doğrayın. Tereyağında kavurun. Mercimekleri ekleyip su ilave edin. Patatesleri ekleyip pişirin. Blendırdan geçirip servis yapın."
    ]
];

// Günün İngilizce Kelimesi
$englishWords = [
    [
        'word' => "Serendipity",
        'pronunciation' => "/ˌser.ənˈdɪp.ə.ti/",
        'meaning' => "Mutlu tesadüf; beklenmedik ve hoş sürprizlerle karşılaşma",
        'example' => "\"Finding this beautiful cafe was a real serendipity.\" (Bu güzel kafeyi bulmak gerçek bir mutlu tesadüftü.)"
    ],
    [
        'word' => "Ephemeral",
        'pronunciation' => "/ɪˈfem.ər.əl/",
        'meaning' => "Kısa ömürlü, geçici",
        'example' => "\"The beauty of cherry blossoms is ephemeral.\" (Kiraz çiçeklerinin güzelliği geçicidir.)"
    ],
    [
        'word' => "Resilience",
        'pronunciation' => "/rɪˈzɪl.i.əns/",
        'meaning' => "Dayanıklılık, zorluklardan sonra toparlanabilme yeteneği",
        'example' => "\"Her resilience helped her overcome many challenges.\" (Dayanıklılığı, birçok zorluğun üstesinden gelmesine yardımcı oldu.)"
    ]
];

// Günün kelimesini seç
$today = new DateTime();
$dayOfYear = (int)$today->format('z');
$wordIndex = $dayOfYear % count($englishWords);
$wordOfTheDay = $englishWords[$wordIndex];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>KANTANZA Forum</title>
  <style>
    /* CSS kodları aynı kalacak */
    <?php include 'style.css'; ?>
  </style>
</head>
<body>
  <!-- ÜST BÖLÜM: Marka ve Auth -->
  <header class="card pad">
    <div class="header-content">
      <div class="brand-container">
        <div class="brand">KANTANZA</div>
        <div class="brand-underline"></div>
      </div>
      <div class="auth-actions">
        <!-- Admin Panel Butonu -->
        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
          <div id="adminButton">
            <a href="admin.php" class="ghost button">Admin Paneli</a>
          </div>
        <?php endif; ?>
        
        <!-- Giriş yapmamış kullanıcı butonları -->
        <?php if (!isset($_SESSION['user_id'])): ?>
          <div id="guestButtons">
            <button id="btnLogin">Giriş yap</button>
            <button class="ghost" id="btnSignup">Üye ol</button>
          </div>
        <?php else: ?>
          <!-- Giriş yapmış kullanıcı butonu -->
          <div id="userButton">
            <span>Hoş geldin, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <form method="post" style="display: inline;">
              <input type="hidden" name="action" value="logout">
              <button type="submit" class="danger">Çıkış yap</button>
            </form>
          </div>
        <?php endif; ?>
      </div>
    </div>
    <div class="line" style="margin-top:12px"></div>
  </header>

  <main class="container">
    <!-- Sol: Film & Dizi & Yemek -->
    <aside class="card pad sidebar">
      <!-- FİLM ÖNERİSİ -->
      <h3>Bugünkü Film Tavsiyemiz</h3>
      <div class="line" style="margin:8px 0"></div>
      
      <!-- Film Görseli -->
      <div class="media-image-container" id="movieImageContainer">
        <?php if ($movie && !empty($movie['image'])): ?>
          <img class="media-image" id="movieImage" src="<?php echo htmlspecialchars($movie['image']); ?>" alt="Film">
        <?php else: ?>
          <div class="media-placeholder" id="moviePlaceholder">
            <i>🎬</i>
            <div>Film Görseli</div>
          </div>
        <?php endif; ?>
      </div>
      
      <div class="row" id="movieBadges" style="flex-wrap:wrap;gap:6px">
        <?php if ($movie): ?>
          <?php if (!empty($movie['imdb'])): ?>
            <span class="badge">IMDb: <?php echo htmlspecialchars($movie['imdb']); ?></span>
          <?php endif; ?>
          <?php if (!empty($movie['rotten_tomatoes'])): ?>
            <span class="badge">Rotten Tomatoes: <?php echo htmlspecialchars($movie['rotten_tomatoes']); ?></span>
          <?php endif; ?>
          <?php if (!empty($movie['age_limit'])): ?>
            <span class="badge"><?php echo htmlspecialchars($movie['age_limit']); ?></span>
          <?php endif; ?>
          <?php if (!empty($movie['genres'])): ?>
            <span class="badge"><?php echo htmlspecialchars($movie['genres']); ?></span>
          <?php endif; ?>
        <?php else: ?>
          <span class="badge">IMDb: 8.6</span>
          <span class="badge">Rotten Tomatoes: 94%</span>
          <span class="badge">+18 değil</span>
          <span class="badge">Macera • Korku • Gizem</span>
        <?php endif; ?>
      </div>
      <p><strong>Film:</strong> 
        <span id="movieTitle">
          <?php echo $movie ? '"' . htmlspecialchars($movie['title']) . '"' : '"Karanlığın Ormanı"'; ?>
        </span>
      </p>
      <p><strong>Oyuncular:</strong> 
        <span id="movieActors">
          <?php echo $movie ? htmlspecialchars($movie['actors']) : 'A. Kaya, B. Demir, C. Öz'; ?>
        </span>
      </p>
      <p><strong>Kısaca:</strong> 
        <span id="movieDescription">
          <?php echo $movie ? htmlspecialchars($movie['description']) : 'Issız bir dağ kasabasında kaybolan kampçılar, eski bir efsanenin peşinden sürüklenir.'; ?>
        </span>
      </p>
      
      <div class="line" style="margin:16px 0"></div>
      
      <!-- DİZİ ÖNERİLERİ -->
      <h3>Dizi Önerileri</h3>
      <div class="line" style="margin:8px 0"></div>
      
      <!-- Dizi Görseli -->
      <div class="media-image-container" id="seriesImageContainer">
        <?php if ($series && !empty($series['image'])): ?>
          <img class="media-image" id="seriesImage" src="<?php echo htmlspecialchars($series['image']); ?>" alt="Dizi">
        <?php else: ?>
          <div class="media-placeholder" id="seriesPlaceholder">
            <i>📺</i>
            <div>Dizi Görseli</div>
          </div>
        <?php endif; ?>
      </div>
      
      <div class="series-info" id="seriesInfo">
        <p><strong>Dizi:</strong> 
          <span id="seriesTitle">
            <?php echo $series ? '"' . htmlspecialchars($series['title']) . '"' : '"Zamanın Ötesinde"'; ?>
          </span>
        </p>
        <?php if ($series && !empty($series['imdb'])): ?>
          <p><strong class="series-rating">IMDb: <span id="seriesImdb"><?php echo htmlspecialchars($series['imdb']); ?></span></strong></p>
        <?php endif; ?>
        <p><strong>Konu:</strong> 
          <span id="seriesSubject">
            <?php echo $series ? htmlspecialchars($series['description']) : 'Gelecekten gelen bir zaman yolcusunun, tarihi olayları değiştirmeye çalışan bir organizasyonla mücadelesi.'; ?>
          </span>
        </p>
        <?php if ($series && !empty($series['seasons'])): ?>
          <p><strong class="series-seasons">Sezon Sayısı:</strong> <span id="seriesSeasons"><?php echo htmlspecialchars($series['seasons']); ?></span></p>
        <?php endif; ?>
        <?php if ($series && !empty($series['best_episode'])): ?>
          <p><strong class="series-best-episode">En Sevilen Bölüm:</strong> <span id="seriesBestEpisode">"<?php echo htmlspecialchars($series['best_episode']); ?>"</span></p>
        <?php endif; ?>
      </div>
      
      <div class="line" style="margin:16px 0"></div>
      
      <!-- YEMEK TARİFLERİ BÖLÜMÜ -->
      <h3>Basit Yemek Tarifleri</h3>
      <div id="recipesList">
        <?php foreach ($recipes as $recipe): ?>
          <div class="recipe-item">
            <div class="recipe-title"><?php echo htmlspecialchars($recipe['title']); ?></div>
            <div class="recipe-meta">
              <span><?php echo $recipe['ingredientCount']; ?> malzeme</span>
            </div>
            <div class="recipe-ingredients"><?php echo htmlspecialchars($recipe['ingredients']); ?></div>
            <button class="recipe-button" onclick="showRecipeModal(<?php echo $recipe['id']; ?>)">Tarif Göster</button>
          </div>
        <?php endforeach; ?>
      </div>
    </aside>
    
    <!-- Orta: Konular -->
    <?php if (!$current_topic): ?>
      <section class="card pad topics" id="view-home">
        <div class="between">
          <div class="title">KONULAR</div>
          <?php if (isset($_SESSION['user_id'])): ?>
            <button id="btnNewTopic">Konu Aç</button>
          <?php else: ?>
            <button id="btnNewTopic" onclick="alert('Konu açmak için giriş yapmalısınız!'); document.getElementById('btnLogin').click();">Konu Aç</button>
          <?php endif; ?>
        </div>
        <div class="line" style="margin-top:8px"></div>
        <div id="topicsList" style="margin-top:8px">
          <?php if (empty($topics)): ?>
            <p style="text-align: center; color: var(--muted);">Henüz hiç konu açılmamış.</p>
          <?php else: ?>
            <?php foreach ($topics as $topic): ?>
              <div class="topic-item" onclick="window.location.href='?topic=<?php echo $topic['id']; ?>'">
                <div>
                  <span class="topic-id">#<?php echo $topic['id']; ?></span>
                  <span class="topic-head"><?php echo htmlspecialchars($topic['title']); ?></span>
                </div>
                <div class="topic-desc"><?php echo mb_substr($topic['body'], 0, 20) . (mb_strlen($topic['body']) > 20 ? '...' : ''); ?></div>
                <div class="topic-user"><?php echo htmlspecialchars($topic['username']); ?></div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </section>
    <?php else: ?>
      <!-- Konu Detay Görünümü -->
      <section class="card pad" style="grid-column:1 / -1">
        <a class="link" href="index.php">← Geri dön</a>
      </section>
      <section class="card pad" style="grid-column:1 / -1">
        <div id="topicDetail">
          <div class="topic-view-title"><?php echo htmlspecialchars($current_topic['title']); ?></div>
          <div class="topic-user"><?php echo htmlspecialchars($current_topic['username']); ?></div>
          <div class="thick-line" style="margin:8px 0"></div>
          <p><?php echo nl2br(htmlspecialchars($current_topic['body'])); ?></p>
          <div class="thick-line" style="margin:12px 0"></div>
          
          <?php if (isset($_SESSION['user_id'])): ?>
            <div class="row" style="margin:12px 0">
              <button onclick="document.getElementById('commentModal').style.display='flex'">bir şeyler yazmak istiyorum</button>
            </div>
          <?php else: ?>
            <p style="color: var(--muted);">Yorum yapmak için <a href="#" onclick="document.getElementById('btnLogin').click()">giriş yapmalısınız</a>.</p>
          <?php endif; ?>
          
          <div id="commentsWrap">
            <?php if (empty($comments)): ?>
              <p style="color: var(--muted); font-style: italic;">Henüz yorum yapılmamış.</p>
            <?php else: ?>
              <?php foreach ($comments as $comment): ?>
                <div class="comment-item">
                  <div><strong><?php echo htmlspecialchars($comment['username']); ?></strong>: <?php echo htmlspecialchars($comment['text']); ?></div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </section>
    <?php endif; ?>
    
    <!-- Sağ: Günlük Kombin ve Günün İngilizce Kelimesi -->
    <div>
      <!-- GÜNLÜK KOMBİN ALANI -->
      <aside class="card pad daily-outfit">
        <h3>Günlük Kombin</h3>
        <div class="line" style="margin:8px 0"></div>
        
        <div class="outfit-image-container" id="outfitImageContainer">
          <?php if ($outfit && !empty($outfit['image'])): ?>
            <img class="outfit-image" id="outfitImage" src="<?php echo htmlspecialchars($outfit['image']); ?>" alt="Günlük Kombin">
          <?php else: ?>
            <div class="outfit-placeholder" id="outfitPlaceholder">
              <i>👗</i>
              <div>Admin panelinden kombin görseli yüklenmemiş</div>
              <small style="margin-top: 8px; display: block;">Görsel yüklemek için admin paneline gidin</small>
            </div>
          <?php endif; ?>
        </div>
        
        <div class="outfit-info" id="outfitDetails">
          <h4>Bugünkü Kombin Detayları</h4>
          <ul>
            <?php if ($outfit): ?>
              <li><strong>Saç:</strong> <?php echo !empty($outfit['hairstyle']) ? htmlspecialchars($outfit['hairstyle']) : '-'; ?></li>
              <li><strong>Toka:</strong> <?php echo !empty($outfit['hairclip']) ? htmlspecialchars($outfit['hairclip']) : '-'; ?></li>
              <li><strong>Küpe:</strong> <?php echo !empty($outfit['earrings']) ? htmlspecialchars($outfit['earrings']) : '-'; ?></li>
              <li><strong>Kolye:</strong> <?php echo !empty($outfit['necklace']) ? htmlspecialchars($outfit['necklace']) : '-'; ?></li>
              <li><strong>Üst:</strong> <?php echo !empty($outfit['top']) ? htmlspecialchars($outfit['top']) : '-'; ?></li>
              <li><strong>Alt:</strong> <?php echo !empty($outfit['pants']) ? htmlspecialchars($outfit['pants']) : '-'; ?></li>
              <li><strong>Ayakkabı:</strong> <?php echo !empty($outfit['shoes']) ? htmlspecialchars($outfit['shoes']) : '-'; ?></li>
              <li><strong>Oje:</strong> <?php echo !empty($outfit['nail_polish']) ? htmlspecialchars($outfit['nail_polish']) : '-'; ?></li>
              <li><strong>Yüzük:</strong> <?php echo !empty($outfit['ring']) ? htmlspecialchars($outfit['ring']) : '-'; ?></li>
            <?php else: ?>
              <li><strong>Üst:</strong> Yaka detaylı crop ekru gömlek</li>
              <li><strong>Alt:</strong> Indigo mavisi jean gömlek</li>
              <li><strong>Ayakkabı:</strong> Lumberjack ROMINA 5FX siyah koşu ayakkabısı</li>
              <li><strong>Aksesuar:</strong> Mini taşlı kolye</li>
            <?php endif; ?>
          </ul>
        </div>
        
        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
          <a href="admin.php" class="ghost button" style="margin-top: 12px; width: 100%; display: block; text-align: center;">
            Kombinleri Yönet (Admin)
          </a>
        <?php endif; ?>
      </aside>
      
      <!-- GÜNÜN İNGİLİZCE KELİMESİ BÖLÜMÜ -->
      <aside class="card pad word-of-the-day">
        <h3>Günün İngilizce Kelimesi</h3>
        <div class="line" style="margin:8px 0"></div>
        <div class="english-word" id="englishWord"><?php echo htmlspecialchars($wordOfTheDay['word']); ?></div>
        <div class="word-pronunciation" id="wordPronunciation"><?php echo htmlspecialchars($wordOfTheDay['pronunciation']); ?></div>
        <div class="word-meaning" id="wordMeaning"><?php echo htmlspecialchars($wordOfTheDay['meaning']); ?></div>
        <div class="word-example" id="wordExample"><?php echo htmlspecialchars($wordOfTheDay['example']); ?></div>
      </aside>
    </div>
  </main>

  <!-- Modals -->
  <!-- Konu Açma Modalı -->
  <div class="modal" id="modalTopic">
    <div class="sheet">
      <form method="post">
        <input type="hidden" name="action" value="create_topic">
        <div class="head"><div class="label-12-calibri">KONU BAŞLIĞI</div></div>
        <div class="body">
          <div class="line" style="margin:6px 0 12px"></div>
          <input name="title" id="inpTopicTitle" maxlength="15" placeholder="Başlık (max 15)" required />
          <label class="label-12-calibri-normal" style="margin-top:14px">KONU</label>
          <textarea name="body" id="inpTopicBody" rows="5" maxlength="250" placeholder="Açıklama (max 250)" required></textarea>
          <small style="color:var(--muted)">* Özel karakterlere izin verilmez. Harf ve rakamlar metin olarak kabul edilir.</small>
        </div>
        <div class="foot">
          <button type="button" class="ghost" onclick="closeModal('#modalTopic')">İptal</button>
          <button type="submit">Konuyu Kaydet</button>
        </div>
      </form>
    </div>
  </div>
  
  <!-- Yorum Modalı -->
  <div class="modal" id="commentModal">
    <div class="sheet">
      <form method="post">
        <input type="hidden" name="action" value="add_comment">
        <input type="hidden" name="topic_id" value="<?php echo $current_topic ? $current_topic['id'] : ''; ?>">
        <div class="head"><div class="label-12-calibri">yorumum</div></div>
        <div class="body">
          <textarea name="text" id="inpComment" rows="5" maxlength="250" placeholder="Yorum (max 250)" required></textarea>
        </div>
        <div class="foot">
          <button type="button" class="ghost" onclick="closeModal('#commentModal')">İptal</button>
          <button type="submit">Gönder</button>
        </div>
      </form>
    </div>
  </div>
  
  <!-- Giriş Modalı -->
  <div class="modal" id="modalLogin">
    <div class="sheet">
      <form method="post">
        <input type="hidden" name="action" value="login">
        <div class="head"><div class="label-12-calibri">Kullanıcı adı</div></div>
        <div class="body">
          <input name="username" id="loginUser" placeholder="Kullanıcı adı" required />
          <div class="line" style="margin:8px 0"></div>
          <label class="label-12-calibri-normal">Şifre</label>
          <input name="password" id="loginPass" type="password" placeholder="Şifre" required />
          <div class="line" style="margin:8px 0"></div>
          <?php if (isset($error) && $_POST['action'] === 'login'): ?>
            <div style="color: var(--danger); margin-top: 10px;"><?php echo $error; ?></div>
          <?php endif; ?>
        </div>
        <div class="foot">
          <button type="button" class="ghost" onclick="closeModal('#modalLogin')">Kapat</button>
          <button type="submit">Giriş yap</button>
        </div>
      </form>
    </div>
  </div>
  
  <!-- Üyelik Modalı -->
  <div class="modal" id="modalSignup">
    <div class="sheet">
      <form method="post">
        <input type="hidden" name="action" value="signup">
        <div class="head"><div class="label-12-calibri">Üyelik</div></div>
        <div class="body">
          <label class="label-12-calibri-normal">Mail</label>
          <input name="email" id="suMail" type="email" placeholder="ornek@site.com" required />
          <div class="line" style="margin:8px 0"></div>
          <label class="label-12-calibri-normal">Kullanıcı adı</label>
          <input name="username" id="suUser" placeholder="Kullanıcı adı" required />
          <div class="line" style="margin:8px 0"></div>
          <label class="label-12-calibri-normal">Şifre</label>
          <input name="password" id="suPass" type="password" placeholder="Şifre (min 5 karakter, 1 büyük harf, 1 sayı)" required />
          <label class="label-12-calibri-normal" style="margin-top:10px">Şifre onay</label>
          <input name="password2" id="suPass2" type="password" placeholder="Şifre tekrar" required />
          <div class="line" style="margin:8px 0"></div>
          <small style="color:var(--muted)">* Şifre en az 5 karakter, 1 büyük harf ve 1 sayı içermelidir.</small>
          <?php if (isset($error) && $_POST['action'] === 'signup'): ?>
            <div style="color: var(--danger); margin-top: 10px;"><?php echo $error; ?></div>
          <?php endif; ?>
        </div>
        <div class="foot">
          <button type="button" class="ghost" onclick="closeModal('#modalSignup')">Kapat</button>
          <button type="submit">Üye ol</button>
        </div>
      </form>
    </div>
  </div>
  
  <!-- YEMEK TARİFİ MODALI -->
  <div class="modal" id="modalRecipe">
    <div class="sheet">
      <div class="head"><div class="label-12-calibri" id="modalRecipeTitle">Yemek Tarifi</div></div>
      <div class="body" id="modalRecipeBody">
        <!-- İçerik JavaScript ile doldurulacak -->
      </div>
      <div class="foot">
        <button class="ghost" onclick="closeModal('#modalRecipe')">Kapat</button>
      </div>
    </div>
  </div>

  <script>
    // Modal işlevleri
    function openModal(id) { 
      document.querySelector(id).style.display = 'flex'; 
    }
    
    function closeModal(id) { 
      document.querySelector(id).style.display = 'none'; 
    }
    
    // Tarif modalını göster
    function showRecipeModal(recipeId) {
      const recipes = <?php echo json_encode($recipes); ?>;
      const recipe = recipes.find(r => r.id === recipeId);
      
      if (recipe) {
        document.getElementById('modalRecipeTitle').textContent = recipe.title;
        document.getElementById('modalRecipeBody').innerHTML = `
          <p><strong>Malzemeler:</strong> ${recipe.ingredients}</p>
          <p><strong>Hazırlanışı:</strong> ${recipe.fullRecipe}</p>
        `;
        openModal('#modalRecipe');
      }
    }
    
    // Modal dışına tıklama ile kapatma
    document.querySelectorAll('.modal').forEach(m => {
      m.addEventListener('click', (e) => { 
        if(e.target === m) m.style.display = 'none'; 
      });
    });
    
    // Modal açma butonları
    document.getElementById('btnLogin')?.addEventListener('click', () => openModal('#modalLogin'));
    document.getElementById('btnSignup')?.addEventListener('click', () => openModal('#modalSignup'));
    document.getElementById('btnNewTopic')?.addEventListener('click', () => openModal('#modalTopic'));
  </script>
</body>
</html>
