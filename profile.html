<?php
// Oturum başlatma
session_start();

// Kullanıcı giriş kontrolü
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Veritabanı bağlantısı
try {
    $db = new PDO('sqlite:forum.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Profil tablosunu oluştur (eğer yoksa)
    $db->exec("
        CREATE TABLE IF NOT EXISTS user_profiles (
            user_id INTEGER PRIMARY KEY,
            pfp TEXT,
            saved_loc TEXT,
            parking_no TEXT,
            shopping_list TEXT,
            habits TEXT,
            special_days TEXT,
            period_start DATE,
            cycle_length INTEGER DEFAULT 28,
            music_link TEXT,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        )
    ");
} catch(PDOException $e) {
    die("Veritabanı hatası: " . $e->getMessage());
}

// Kullanıcı bilgilerini al
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$is_admin = $_SESSION['is_admin'];

// Profil bilgilerini al
$stmt = $db->prepare("SELECT * FROM user_profiles WHERE user_id = ?");
$stmt->execute([$user_id]);
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

// Eğer profil yoksa, oluştur
if (!$profile) {
    $stmt = $db->prepare("INSERT INTO user_profiles (user_id) VALUES (?)");
    $stmt->execute([$user_id]);
    $profile = ['user_id' => $user_id];
}

// JSON verilerini decode et
$shopping_list = $profile['shopping_list'] ? json_decode($profile['shopping_list'], true) : [];
$habits = $profile['habits'] ? json_decode($profile['habits'], true) : [];
$special_days = $profile['special_days'] ? json_decode($profile['special_days'], true) : [];
$saved_loc = $profile['saved_loc'] ? json_decode($profile['saved_loc'], true) : null;

// POST isteklerini işleme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Profil resmi güncelleme
    if (isset($_POST['action']) && $_POST['action'] === 'update_pfp') {
        if (isset($_FILES['pfp']) && $_FILES['pfp']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/pfp/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileExtension = pathinfo($_FILES['pfp']['name'], PATHINFO_EXTENSION);
            $fileName = 'pfp_' . $user_id . '_' . time() . '.' . $fileExtension;
            $filePath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['pfp']['tmp_name'], $filePath)) {
                $stmt = $db->prepare("UPDATE user_profiles SET pfp = ? WHERE user_id = ?");
                $stmt->execute([$filePath, $user_id]);
                $message = "Profil resmi başarıyla güncellendi.";
            } else {
                $error = "Profil resmi yüklenirken hata oluştu.";
            }
        }
    }
    
    // Konum kaydetme
    if (isset($_POST['action']) && $_POST['action'] === 'save_location') {
        $location_data = json_encode([
            'lat' => $_POST['lat'],
            'lng' => $_POST['lng'],
            'addr' => $_POST['addr']
        ]);
        
        $stmt = $db->prepare("UPDATE user_profiles SET saved_loc = ? WHERE user_id = ?");
        $stmt->execute([$location_data, $user_id]);
        $message = "Konum başarıyla kaydedildi.";
    }
    
    // Müzik linki kaydetme
    if (isset($_POST['action']) && $_POST['action'] === 'save_music') {
        $stmt = $db->prepare("UPDATE user_profiles SET music_link = ? WHERE user_id = ?");
        $stmt->execute([$_POST['music_link'], $user_id]);
        $message = "Müzik linki başarıyla kaydedildi.";
    }
    
    // Otopark numarası kaydetme
    if (isset($_POST['action']) && $_POST['action'] === 'save_parking') {
        $stmt = $db->prepare("UPDATE user_profiles SET parking_no = ? WHERE user_id = ?");
        $stmt->execute([$_POST['parking_no'], $user_id]);
        $message = "Otopark numarası başarıyla kaydedildi.";
    }
    
    // Regli takvimi kaydetme
    if (isset($_POST['action']) && $_POST['action'] === 'save_period') {
        $stmt = $db->prepare("UPDATE user_profiles SET period_start = ?, cycle_length = ? WHERE user_id = ?");
        $stmt->execute([$_POST['period_start'], $_POST['cycle_length'], $user_id]);
        $message = "Regli takvimi başarıyla kaydedildi.";
    }
    
    // Alışveriş listesi güncelleme
    if (isset($_POST['action']) && $_POST['action'] === 'update_shopping_list') {
        $shopping_list = json_decode($_POST['shopping_list'], true);
        $stmt = $db->prepare("UPDATE user_profiles SET shopping_list = ? WHERE user_id = ?");
        $stmt->execute([$_POST['shopping_list'], $user_id]);
        $message = "Alışveriş listesi başarıyla güncellendi.";
    }
    
    // Alışkanlıklar güncelleme
    if (isset($_POST['action']) && $_POST['action'] === 'update_habits') {
        $stmt = $db->prepare("UPDATE user_profiles SET habits = ? WHERE user_id = ?");
        $stmt->execute([$_POST['habits'], $user_id]);
        $message = "Alışkanlıklar başarıyla güncellendi.";
    }
    
    // Özel günler güncelleme
    if (isset($_POST['action']) && $_POST['action'] === 'update_special_days') {
        $stmt = $db->prepare("UPDATE user_profiles SET special_days = ? WHERE user_id = ?");
        $stmt->execute([$_POST['special_days'], $user_id]);
        $message = "Özel günler başarıyla güncellendi.";
    }
    
    // Profil ayarları güncelleme
    if (isset($_POST['action']) && $_POST['action'] === 'update_profile_settings') {
        $new_username = $_POST['username'];
        $new_password = $_POST['password'];
        
        // Kullanıcı adı güncelleme
        if (!empty($new_username) && $new_username !== $username) {
            // Kullanıcı adı kontrolü
            $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = ? AND id != ?");
            $stmt->execute([$new_username, $user_id]);
            if ($stmt->fetchColumn() > 0) {
                $error = "Bu kullanıcı adı zaten alınmış!";
            } else {
                $stmt = $db->prepare("UPDATE users SET username = ? WHERE id = ?");
                $stmt->execute([$new_username, $user_id]);
                $_SESSION['username'] = $new_username;
                $username = $new_username;
                $message = "Profil ayarları başarıyla güncellendi.";
            }
        }
        
        // Şifre güncelleme
        if (!empty($new_password)) {
            if (strlen($new_password) < 5) {
                $error = "Şifre en az 5 karakter olmalıdır!";
            } elseif (!preg_match('/[A-Z]/', $new_password)) {
                $error = "Şifre en az bir büyük harf içermelidir!";
            } elseif (!preg_match('/\d/', $new_password)) {
                $error = "Şifre en az bir rakam içermelidir!";
            } else {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$hashed_password, $user_id]);
                $message = "Profil ayarları başarıyla güncellendi.";
            }
        }
        
        if (empty($new_username) && empty($new_password)) {
            $message = "Değişiklik yapılmadı.";
        }
    }
    
    // Çıkış yapma
    if (isset($_POST['action']) && $_POST['action'] === 'logout') {
        session_destroy();
        header('Location: index.php');
        exit;
    }
    
    // Profil bilgilerini tekrar yükle
    $stmt = $db->prepare("SELECT * FROM user_profiles WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // JSON verilerini tekrar decode et
    $shopping_list = $profile['shopping_list'] ? json_decode($profile['shopping_list'], true) : [];
    $habits = $profile['habits'] ? json_decode($profile['habits'], true) : [];
    $special_days = $profile['special_days'] ? json_decode($profile['special_days'], true) : [];
    $saved_loc = $profile['saved_loc'] ? json_decode($profile['saved_loc'], true) : null;
}

// Takvim oluşturma
function buildCalendar($period_start, $cycle_length, $special_days) {
    $today = new DateTime();
    $monthStart = new DateTime($today->format('Y-m-01'));
    $monthEnd = new DateTime($today->format('Y-m-t'));
    
    $calendar_html = '';
    
    // Ay başlığı
    $calendar_html .= '<div id="calendarMeta" class="muted" style="margin:8px 0;color:var(--muted)">';
    $calendar_html .= 'Gösterilen ay: ' . $today->format('F Y') . ' | Döngü: ' . $cycle_length . ' gün';
    $calendar_html .= '</div>';
    
    // Takvim grid
    $calendar_html .= '<div id="calendar" class="calendar">';
    
    // Period başlangıç tarihi
    $period_start_date = $period_start ? new DateTime($period_start) : null;
    $periods = [];
    
    if ($period_start_date) {
        // Önceki ve sonraki periodları hesapla
        for ($i = -6; $i <= 6; $i++) {
            $period = clone $period_start_date;
            $period->modify(($i * $cycle_length) . ' days');
            $periods[] = $period;
        }
    }
    
    // Ayın günlerini oluştur
    $current_date = clone $monthStart;
    while ($current_date <= $monthEnd) {
        $day_class = 'day';
        $day_number = $current_date->format('j');
        
        // Period kontrolü
        $is_period = false;
        $is_predicted = false;
        
        if ($period_start_date) {
            foreach ($periods as $period) {
                $diff = $current_date->diff($period)->days;
                if ($diff <= 1) {
                    $is_period = true;
                    break;
                }
            }
            
            // Tahmini period (gelecek periodun 3 gün öncesi)
            $next_period = null;
            foreach ($periods as $period) {
                if ($period >= $today) {
                    $next_period = $period;
                    break;
                }
            }
            
            if ($next_period) {
                $diff_to_next = $current_date->diff($next_period)->days;
                if ($diff_to_next >= 0 && $diff_to_next < 3) {
                    $is_predicted = true;
                }
            }
        }
        
        // Özel gün kontrolü
        $is_special = false;
        foreach ($special_days as $special_day) {
            $special_date = new DateTime($special_day['date']);
            if ($special_date->format('Y-m-d') === $current_date->format('Y-m-d')) {
                $is_special = true;
                break;
            }
        }
        
        // CSS class'larını belirle
        if ($is_period) $day_class .= ' period';
        if ($is_predicted) $day_class .= ' predicted';
        if ($is_special) $day_class .= ' special';
        
        $calendar_html .= '<div class="' . $day_class . '" data-date="' . $current_date->format('Y-m-d') . '">' . $day_number . '</div>';
        
        $current_date->modify('+1 day');
    }
    
    $calendar_html .= '</div>';
    
    return $calendar_html;
}

// Takvimi oluştur
$calendar_html = buildCalendar(
    $profile['period_start'], 
    $profile['cycle_length'] ?: 28, 
    $special_days
);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profil - KANTANZA Forum</title>
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
    input, textarea{width:100%;background:#0f131a;border:1px solid var(--line);border-radius:10px;color:var(--ink);padding:10px}
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

    .auth-actions {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    /* Admin butonu için stil */
    #adminButton {
      display: none;
      margin-right: 10px;
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
    }

    /* Profil */
    .profile-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
    .profile-top{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
    }

    /* Yeni yatay profil düzeni: solda pfp, ortada isim, sağda ayarlar */
    .profile-left{display:flex;flex-direction:column;align-items:center;gap:12px}
    .pfp{width:120px;height:120px;border-radius:50%;border:2px solid var(--line);object-fit:cover;background:#0c0f14;display:block}
    .profile-username{font-size:18pt;font-weight:bold;margin:0;text-align:center}
    .profile-center{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center}
    .profile-actions{display:flex;gap:8px}
    .loc-box .addr{font-family:monospace;color:var(--muted)}
    
    /* Takvim - BÜYÜTÜLMÜŞ ve BEYAZ */
    .calendar{display:grid;grid-template-columns:repeat(7,1fr);gap:8px;margin-top:12px}
    .calendar .day{
      padding:15px;
      text-align:center;
      border:1px solid var(--line);
      border-radius:8px;
      cursor:pointer;
      color:white;
      font-weight:bold;
      font-size:14px;
      min-height:50px;
      display:flex;
      align-items:center;
      justify-content:center;
      background: white;
      color: #0f1115;
    }
    .day.period{background:#3a1f2f; color: white;}
    .day.predicted{background:#1f2f3a; color: white;}
    .day.special{background:#2f3a1f; color: white;}
    .link{color:var(--accent)}
    
    /* YouTube Oynatıcı Güncellemesi */
    .youtube-player-controls{margin-top:10px}
    .youtube-player-controls label{font-size:14px; color:var(--muted); margin-bottom:4px}
    .youtube-player-controls button{padding:6px 12px; font-size:14px; border-radius:8px;}
    .youtube-player-controls input{font-size:14px; padding:8px;}
    .youtube-player-controls .row{flex-wrap:wrap; gap:8px;}
    
    /* Küçük ve görünmez iframe boyutu */
    #youtubePlayer iframe {
        width: 100px;
        height: 1px;
        border: none;
        opacity: 0.01;
        pointer-events: none;
        position: absolute;
    }
    .parking-display{font-size:24pt;font-weight:bold;text-align:center;margin:10px 0;color:var(--accent)}
    .habit-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:4px;margin-top:8px}
    .habit-day{width:30px;height:30px;border-radius:50%;border:1px solid var(--line);display:flex;align-items:center;justify-content:center;cursor:pointer}
    .habit-day.checked{background:var(--ok)}
    .shopping-item{display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid var(--line)}
    .shopping-item:last-child{border-bottom:none}
    .special-day-item{display:flex;flex-direction:column;padding:8px 0;border-bottom:1px solid var(--line)}
    .special-day-item:last-child{border-bottom:none}
    .delete-btn{color:var(--danger);cursor:pointer}
    .small-button{padding:6px 10px;font-size:12px}
    .special-day-form{display:flex;flex-direction:column;gap:8px}
    .profile-info{display:flex;flex-direction:column;align-items:center;justify-content:center}
    
    @media (max-width:900px){
      .container{grid-template-columns:1fr; padding:12px}
      .profile-top{flex-direction:column; align-items:center}
      .profile-left{flex-direction:column}
      .profile-center{width:100%}
      .profile-grid{grid-template-columns:1fr}
    }

    /* Modallar */
    .modal{position:fixed;inset:0;background:rgba(0,0,0,.55);display:none;align-items:center;justify-content:center;padding:16px;z-index:40}
    .modal.show{display:flex}
    .modal .sheet{width:min(560px, 96vw);background:var(--card);border:1px solid var(--line);border-radius:16px;box-shadow:0 20px 50px rgba(0,0,0,.45)}
    .modal .sheet .head{padding:16px 16px 0}
    .modal .sheet .body{padding:16px}
    .modal .sheet .foot{padding:0 16px 16px;display:flex;gap:8px;justify-content:flex-end}
    .label-12-calibri{font-family:Calibri, Arial, Helvetica, sans-serif;font-weight:700;font-size:12pt}
    .label-12-calibri-normal{font-family:Calibri, Arial, Helvetica, sans-serif;font-size:12pt}
    
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
  </style>
</head>
<body>
 <!-- ÜST BÖLÜM: Marka ve Auth -->
<header class="card pad">
  <div class="header-content">
    <div class="brand-container">
      <div class="brand"><a href="index.php" style="text-decoration: none; color: inherit;">KANTANZA</a></div>
      <div class="brand-underline"></div>
    </div>
    <div class="auth-actions">
      <!-- Ana Sayfa Butonu -->
      <div>
        <a href="index.php" class="ghost button">Ana Sayfa</a>
      </div>
      
      <!-- Admin Panel Butonu -->
      <?php if ($is_admin): ?>
        <div id="adminButton">
          <a href="admin.php" class="ghost button">Admin Paneli</a>
        </div>
      <?php endif; ?>
      
      <!-- Çıkış butonu -->
      <div id="userButton">
        <form method="post" style="display: inline;">
          <input type="hidden" name="action" value="logout">
          <button type="submit" class="danger">Çıkış yap</button>
        </form>
      </div>
    </div>
  </div>
  <div class="line" style="margin-top:12px"></div>
</header>

  <!-- Profil Sayfası -->
  <section class="container">
    <!-- Mesaj Bildirimleri -->
    <?php if (isset($message)): ?>
      <div class="message success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
      <div class="message error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <div class="card pad" style="grid-column:1 / -1">
      <div class="profile-top">
        <div class="profile-left">
          <img id="pfp" class="pfp" src="<?php echo $profile['pfp'] ? htmlspecialchars($profile['pfp']) : ''; ?>" alt="Profil resmi" />
          <div style="margin-top:8px">
            <form method="post" enctype="multipart/form-data" id="pfpForm" style="display: none;">
              <input type="hidden" name="action" value="update_pfp">
              <input type="file" id="pfpInput" name="pfp" accept="image/*" onchange="document.getElementById('pfpForm').submit()" />
            </form>
            <button id="btnAddPfp" class="small-button" onclick="document.getElementById('pfpInput').click()">Profil resmi ekle</button>
          </div>
        </div>
        <div class="profile-center">
          <div class="profile-username" id="profileUsername"><?php echo htmlspecialchars($username); ?></div>
          <div class="profile-userid" id="profileUserId" style="color:var(--muted);margin-top:4px;">#<?php echo $user_id; ?></div>
        </div>
        <div class="profile-actions">
          <button id="btnProfileSettings" class="ghost small-button">Profil ayarları</button>
        </div>
      </div>
    </div>
    <div class="profile-grid" style="grid-column:1 / -1">
      <!-- Sol -->
      <div>
        <div class="card pad">
          <h3>Konum</h3>
          <div class="loc-box">
            <div class="addr" id="currAddr">Adres/Koordinat okunuyor…</div>
            <div class="row" style="margin:10px 0;gap:8px">
              <button id="btnSaveLoc">Konumumu kaydet</button>
              <button id="btnGoLoc" class="ghost">Konuma git</button>
            </div>
          </div>
        </div>

        <!-- Müzik Bölümü: YouTube arka planda -->
        <div class="card pad">
          <h3>Müzik (arka planda)</h3>
          <form method="post" id="musicForm">
            <input type="hidden" name="action" value="save_music">
            <div class="youtube-player-controls">
              <label for="musicLinkInput">YouTube Video Linki (URL)</label>
              <input type="text" id="musicLinkInput" name="music_link" placeholder="YouTube video linkini yapıştırın" value="<?php echo htmlspecialchars($profile['music_link'] ?? ''); ?>" />
              <div class="row" style="margin-top:10px;justify-content:space-between">
                <button type="button" id="btnPlayMusic">Oynat</button>
                <button type="button" id="btnStopMusic" class="ghost">Durdur</button>
                <button type="submit" class="ghost">Kaydet</button>
              </div>
              <div style="margin-top:8px;color:var(--muted);font-size:12px">
                Durum: <span id="musicStatus">Hazır</span>
              </div>
            </div>
          </form>
          <div id="ytContainer" style="margin-top:10px; position:relative; height:10px;">
            <div id="youtubePlayer"></div>
          </div>
        </div>
        <div class="card pad">
          <h3>Otopark Numarası</h3>
          <div class="parking-display" id="parkingDisplay"><?php echo htmlspecialchars($profile['parking_no'] ?? '-'); ?></div>
          <form method="post" id="parkingForm">
            <input type="hidden" name="action" value="save_parking">
            <input type="text" id="inpParkingNo" name="parking_no" placeholder="Otopark numarası" maxlength="10" value="<?php echo htmlspecialchars($profile['parking_no'] ?? ''); ?>" />
            <div class="row" style="margin-top:8px;gap:8px">
              <button type="submit">Kaydet</button>
              <button type="button" id="btnClearParking" class="ghost">Temizle</button>
            </div>
          </form>
        </div>
      </div>
      <!-- Sağ -->
      <div>
        <div class="card pad">
          <h3>Regli Takvimi</h3>
          <form method="post" id="periodForm">
            <input type="hidden" name="action" value="save_period">
            <div class="row" style="gap:8px;flex-wrap:wrap">
              <label>
                Son başlangıç tarihi
                <input type="date" id="periodStart" name="period_start" value="<?php echo htmlspecialchars($profile['period_start'] ?? ''); ?>" />
              </label>
              <label>
                Döngü (gün)
                <input type="number" id="cycleLen" name="cycle_length" min="20" max="40" value="<?php echo htmlspecialchars($profile['cycle_length'] ?? 28); ?>" />
              </label>
              <button type="submit">Hesapla</button>
            </div>
          </form>
          <?php echo $calendar_html; ?>
        </div>
        <div class="card pad">
          <h3>Alışveriş Listesi</h3>
          <div class="row">
            <input type="text" id="inpShoppingItem" placeholder="Yeni ürün ekle" style="flex:1" />
            <button id="btnAddShopping">+</button>
          </div>
          <div id="shoppingList" style="margin-top:12px">
            <?php foreach ($shopping_list as $index => $item): ?>
              <div class="shopping-item">
                <span><?php echo htmlspecialchars($item); ?></span>
                <span class="delete-btn" data-index="<?php echo $index; ?>">✕</span>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
    <div class="profile-grid" style="grid-column:1 / -1; margin-top:16px">
      <div class="card pad">
        <h3>Alışkanlıklarım (21 Gün Kuralı)</h3>
        <div class="row">
          <input type="text" id="inpHabit" placeholder="Yeni alışkanlık" style="flex:1" />
          <button id="btnAddHabit">+</button>
        </div>
        <div id="habitsList" style="margin-top:12px">
          <?php foreach ($habits as $habit_index => $habit): ?>
            <div style="margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid var(--line)">
              <div style="font-weight:bold;margin-bottom:8px"><?php echo htmlspecialchars($habit['name']); ?></div>
              <div class="habit-grid">
                <?php for ($i = 0; $i < 21; $i++): ?>
                  <div class="habit-day <?php echo isset($habit['days'][$i]) && $habit['days'][$i] ? 'checked' : ''; ?>" 
                       data-habit="<?php echo $habit_index; ?>" data-day="<?php echo $i; ?>">
                    <?php echo $i + 1; ?>
                  </div>
                <?php endfor; ?>
              </div>
              <span class="delete-btn" data-habit="<?php echo $habit_index; ?>" style="margin-left:10px">✕</span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="card pad">
        <h3>Özel Günler</h3>
        <div class="special-day-form">
          <input type="text" id="inpSpecialDayName" placeholder="Etkinlik adı" />
          <input type="date" id="inpSpecialDayDate" />
          <button id="btnAddSpecialDay">+</button>
        </div>
        <div id="specialDaysList" style="margin-top:12px">
          <?php foreach ($special_days as $index => $day): ?>
            <div class="special-day-item">
              <div>
                <strong><?php echo htmlspecialchars($day['name']); ?></strong>
                <div><?php echo date('d.m.Y', strtotime($day['date'])); ?></div>
              </div>
              <span class="delete-btn" data-index="<?php echo $index; ?>">✕</span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- Modals (profil ayar) -->
  <div class="modal" id="modalProfileSettings">
    <div class="sheet">
      <form method="post">
        <input type="hidden" name="action" value="update_profile_settings">
        <div class="head"><div class="label-12-calibri">Profil Ayarları</div></div>
        <div class="body">
          <label class="label-12-calibri-normal">Kullanıcı Adı</label>
          <input id="settingsUsername" name="username" placeholder="Yeni kullanıcı adı" />
          <div class="line" style="margin:8px 0"></div>
          <label class="label-12-calibri-normal">Yeni Şifre</label>
          <input id="settingsPassword" name="password" type="password" placeholder="Yeni şifre (min 5 karakter, 1 büyük harf, 1 sayı)" />
          <div class="line" style="margin:8px 0"></div>
          <label class="label-12-calibri-normal">Şifre Onay</label>
          <input id="settingsPasswordConfirm" type="password" placeholder="Şifre tekrar" />
        </div>
        <div class="foot">
          <button type="button" class="ghost" onclick="closeModal('#modalProfileSettings')">İptal</button>
          <button type="submit">Kaydet</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // --- Yardımcı seçiciler ---
    const el = s => document.querySelector(s);
    const els = s => Array.from(document.querySelectorAll(s));
    
    // --- YouTube Oynatıcı Durumu ---
    let player;
    let isPlayerReady = false;
    let currentPlaylist = [];
    let currentPlaylistIndex = 0;
    const defaultVideoId = 't_H0qL1z2Jc';

    // --- PROFİL FONKSİYONLARI ---
    async function readCurrentLocation() {
      const label = el('#currAddr');
      label.textContent = 'Konum okunuyor…';
      if(!('geolocation' in navigator)) { 
        label.textContent = 'Tarayıcınız konumu desteklemiyor.'; 
        return null; 
      }
      try {
        const pos = await new Promise((res, rej) => navigator.geolocation.getCurrentPosition(res, rej));
        const {latitude: lat, longitude: lng} = pos.coords;
        let addr = '';
        try {
          const r = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`);
          if(r.ok) { 
            const j = await r.json(); 
            addr = j.display_name || ''; 
          }
        } catch(e) {}
        const text = `Adres: ${addr || 'Bulunamadı'} | Koordinat: ${lat.toFixed(5)}, ${lng.toFixed(5)}`;
        label.textContent = text;
        return {lat, lng, addr};
      } catch(e) { 
        label.textContent = 'Konum alınamadı.'; 
        return null; 
      }
    }

    // Konum kaydetme
    async function saveLocation() {
      const location = await readCurrentLocation();
      if (!location) return;
      
      const formData = new FormData();
      formData.append('action', 'save_location');
      formData.append('lat', location.lat);
      formData.append('lng', location.lng);
      formData.append('addr', location.addr);
      
      try {
        const response = await fetch('profile.php', {
          method: 'POST',
          body: formData
        });
        
        if (response.ok) {
          alert('Konum kaydedildi.');
        } else {
          alert('Konum kaydedilirken hata oluştu.');
        }
      } catch (error) {
        alert('Konum kaydedilirken hata oluştu.');
      }
    }

    // Alışveriş listesi işlemleri
    async function updateShoppingList(shoppingList) {
      const formData = new FormData();
      formData.append('action', 'update_shopping_list');
      formData.append('shopping_list', JSON.stringify(shoppingList));
      
      try {
        await fetch('profile.php', {
          method: 'POST',
          body: formData
        });
      } catch (error) {
        console.error('Alışveriş listesi güncellenirken hata oluştu:', error);
      }
    }

    // Alışkanlıklar işlemleri
    async function updateHabits(habits) {
      const formData = new FormData();
      formData.append('action', 'update_habits');
      formData.append('habits', JSON.stringify(habits));
      
      try {
        await fetch('profile.php', {
          method: 'POST',
          body: formData
        });
      } catch (error) {
        console.error('Alışkanlıklar güncellenirken hata oluştu:', error);
      }
    }

    // Özel günler işlemleri
    async function updateSpecialDays(specialDays) {
      const formData = new FormData();
      formData.append('action', 'update_special_days');
      formData.append('special_days', JSON.stringify(specialDays));
      
      try {
        await fetch('profile.php', {
          method: 'POST',
          body: formData
        });
      } catch (error) {
        console.error('Özel günler güncellenirken hata oluştu:', error);
      }
    }

    // --- YOUTUBE PLAYER FONKSİYONLARI ---
    function loadYouTubeAPI() {
      const tag = document.createElement('script');
      tag.src = "https://www.youtube.com/iframe_api";
      const firstScriptTag = document.getElementsByTagName('script')[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    }

    window.onYouTubeIframeAPIReady = function() {
      isPlayerReady = true;
      el('#musicStatus').textContent = 'Oynatıcı Hazır.';
      
      player = new YT.Player('youtubePlayer', {
        height: '100%',
        width: '100%',
        videoId: defaultVideoId,
        playerVars: {
          'autoplay': 0,
          'controls': 0,
          'loop': 1,
          'playlist': defaultVideoId,
          'disablekb': 1,
          'iv_load_policy': 3
        },
        events: {
          'onReady': onPlayerReady,
          'onStateChange': onPlayerStateChange
        }
      });
    }

    function onPlayerReady(event) {
      el('#musicStatus').textContent = 'Oynatıcı Başlatılmaya Hazır.';
      currentPlaylist = [defaultVideoId];
      el('#musicStatus').textContent = 'Hazır (Seçili: Dinlendirici Müzik)';
    }

    function onPlayerStateChange(event) {
      if (event.data == YT.PlayerState.PLAYING) {
        el('#musicStatus').textContent = 'Oynatılıyor.';
      } else if (event.data == YT.PlayerState.PAUSED) {
        el('#musicStatus').textContent = 'Durduruldu.';
      } else if (event.data == YT.PlayerState.ENDED) {
        if (currentPlaylist.length > 1) {
          currentPlaylistIndex = (currentPlaylistIndex + 1) % currentPlaylist.length;
          player.loadVideoById(currentPlaylist[currentPlaylistIndex]);
        } else {
          player.playVideo();
        }
      }
    }

    function extractVideoId(url) {
      if (!url) return null;
      let videoId = null;
      const urlObj = new URL(url);
      if (urlObj.hostname.includes('youtube.com') || urlObj.hostname.includes('youtu.be')) {
        if (urlObj.searchParams.get('v')) {
          videoId = urlObj.searchParams.get('v');
        } else if (urlObj.pathname.length > 1 && urlObj.hostname.includes('youtu.be')) {
          videoId = urlObj.pathname.substring(1);
        }
      }
      if (videoId && videoId.length === 11) return videoId;
      return null;
    }

    async function handlePlayMusic() {
      if (!isPlayerReady) {
        alert('YouTube oynatıcı henüz yüklenmedi. Lütfen bekleyin.');
        return;
      }

      const link = el('#musicLinkInput').value.trim();

      currentPlaylist = [];
      currentPlaylistIndex = 0;

      if (link) {
        const id = extractVideoId(link);
        if (id) {
          currentPlaylist.push(id);
          el('#musicStatus').textContent = 'Link ile yükleniyor...';
        } else {
          alert('Geçersiz YouTube Video Linki.');
          el('#musicStatus').textContent = 'HATA: Geçersiz link.';
          return;
        }
      } else {
        currentPlaylist.push(defaultVideoId);
        el('#musicStatus').textContent = 'Varsayılan müzik yükleniyor.';
      }

      if (currentPlaylist.length > 0) {
        player.loadPlaylist(currentPlaylist);
      }
    }

    function handleStopMusic() {
      if (isPlayerReady) {
        player.pauseVideo();
        el('#musicStatus').textContent = 'Durduruldu.';
      }
    }

    // --- EVENT HANDLERS ---
    document.addEventListener('DOMContentLoaded', () => {
      // Modal dışına tıklama ile kapatma
      els('.modal').forEach(m => {
        m.addEventListener('click', (e) => { 
          if(e.target === m) m.style.display = 'none'; 
        });
      });

      // Profil butonları
      el('#btnProfileSettings').onclick = () => {
        openModal('#modalProfileSettings');
      };

      // Konum butonları
      el('#btnSaveLoc').onclick = saveLocation;
      el('#btnGoLoc').onclick = () => {
        <?php if ($saved_loc): ?>
          const url = `https://www.google.com/maps/dir/?api=1&destination=<?php echo $saved_loc['lat']; ?>,<?php echo $saved_loc['lng']; ?>`;
          window.open(url, '_blank');
        <?php else: ?>
          alert('Önce konum kaydedin.'); 
        <?php endif; ?>
      };

      // Müzik butonları
      el('#btnPlayMusic').addEventListener('click', handlePlayMusic);
      el('#btnStopMusic').addEventListener('click', handleStopMusic);

      // Otopark temizleme butonu
      el('#btnClearParking').onclick = () => {
        el('#inpParkingNo').value = '';
        el('#parkingDisplay').textContent = '-';
        // Formu submit et
        setTimeout(() => {
          el('#parkingForm').submit();
        }, 100);
      };

      // Alışveriş listesi butonu
      el('#btnAddShopping').onclick = () => {
        const item = el('#inpShoppingItem').value.trim();
        if(!item) return;
        
        // Mevcut listeyi al
        const shoppingList = <?php echo json_encode($shopping_list); ?>;
        shoppingList.push(item);
        
        // Listeyi güncelle
        updateShoppingList(shoppingList);
        
        // UI'ı güncelle
        const list = el('#shoppingList');
        const div = document.createElement('div');
        div.className = 'shopping-item';
        div.innerHTML = `<span>${item}</span><span class="delete-btn" data-index="${shoppingList.length - 1}">✕</span>`;
        list.appendChild(div);
        
        // Silme butonuna event ekle
        div.querySelector('.delete-btn').onclick = async function() {
          const index = parseInt(this.getAttribute('data-index'));
          shoppingList.splice(index, 1);
          await updateShoppingList(shoppingList);
          this.parentElement.remove();
        };
        
        el('#inpShoppingItem').value = '';
      };

      // Alışkanlık butonu
      el('#btnAddHabit').onclick = () => {
        const habitName = el('#inpHabit').value.trim();
        if(!habitName) return;
        
        // Mevcut alışkanlıkları al
        const habits = <?php echo json_encode($habits); ?>;
        habits.push({ name: habitName, days: [] });
        
        // Alışkanlıkları güncelle
        updateHabits(habits);
        
        // UI'ı güncelle
        const list = el('#habitsList');
        const habitDiv = document.createElement('div');
        habitDiv.style.marginBottom = '16px';
        habitDiv.style.paddingBottom = '12px';
        habitDiv.style.borderBottom = '1px solid var(--line)';
        
        const habitTitle = document.createElement('div');
        habitTitle.textContent = habitName;
        habitTitle.style.fontWeight = 'bold';
        habitTitle.style.marginBottom = '8px';
        
        const grid = document.createElement('div');
        grid.className = 'habit-grid';
        for (let i = 0; i < 21; i++) {
          const day = document.createElement('div');
          day.className = 'habit-day';
          day.textContent = i + 1;
          day.setAttribute('data-habit', habits.length - 1);
          day.setAttribute('data-day', i);
          grid.appendChild(day);
        }
        
        const deleteBtn = document.createElement('span');
        deleteBtn.className = 'delete-btn';
        deleteBtn.textContent = '✕';
        deleteBtn.style.marginLeft = '10px';
        deleteBtn.setAttribute('data-habit', habits.length - 1);
        
        habitDiv.appendChild(habitTitle);
        habitDiv.appendChild(grid);
        habitDiv.appendChild(deleteBtn);
        list.appendChild(habitDiv);
        
        // Event listener'ları ekle
        grid.querySelectorAll('.habit-day').forEach(day => {
          day.onclick = async function() {
            const habitIndex = parseInt(this.getAttribute('data-habit'));
            const dayIndex = parseInt(this.getAttribute('data-day'));
            
            habits[habitIndex].days[dayIndex] = !habits[habitIndex].days[dayIndex];
            await updateHabits(habits);
            this.classList.toggle('checked');
          };
        });
        
        deleteBtn.onclick = async function() {
          const habitIndex = parseInt(this.getAttribute('data-habit'));
          habits.splice(habitIndex, 1);
          await updateHabits(habits);
          this.parentElement.remove();
        };
        
        el('#inpHabit').value = '';
      };

      // Özel gün butonu
      el('#btnAddSpecialDay').onclick = () => {
        const name = el('#inpSpecialDayName').value.trim();
        const date = el('#inpSpecialDayDate').value;
        if(!name || !date) { 
          alert('Etkinlik adı ve tarihi gerekli.'); 
          return; 
        }
        
        // Mevcut özel günleri al
        const specialDays = <?php echo json_encode($special_days); ?>;
        specialDays.push({ name, date });
        
        // Özel günleri güncelle
        updateSpecialDays(specialDays);
        
        // UI'ı güncelle
        const list = el('#specialDaysList');
        const div = document.createElement('div');
        div.className = 'special-day-item';
        div.innerHTML = `
          <div>
            <strong>${name}</strong>
            <div>${new Date(date).toLocaleDateString('tr-TR')}</div>
          </div>
          <span class="delete-btn" data-index="${specialDays.length - 1}">✕</span>
        `;
        
        list.appendChild(div);
        
        // Silme butonuna event ekle
        div.querySelector('.delete-btn').onclick = async function() {
          const index = parseInt(this.getAttribute('data-index'));
          specialDays.splice(index, 1);
          await updateSpecialDays(specialDays);
          this.parentElement.remove();
        };
        
        el('#inpSpecialDayName').value = '';
        el('#inpSpecialDayDate').value = '';
      };

      // Mevcut silme butonlarına event listener ekle
      // Alışveriş listesi silme butonları
      els('#shoppingList .delete-btn').forEach(btn => {
        btn.onclick = async function() {
          const index = parseInt(this.getAttribute('data-index'));
          const shoppingList = <?php echo json_encode($shopping_list); ?>;
          shoppingList.splice(index, 1);
          await updateShoppingList(shoppingList);
          this.parentElement.remove();
        };
      });

      // Alışkanlık silme butonları
      els('#habitsList .delete-btn').forEach(btn => {
        btn.onclick = async function() {
          const habitIndex = parseInt(this.getAttribute('data-habit'));
          const habits = <?php echo json_encode($habits); ?>;
          habits.splice(habitIndex, 1);
          await updateHabits(habits);
          this.parentElement.remove();
        };
      });

      // Alışkanlık günlerine event listener ekle
      els('#habitsList .habit-day').forEach(day => {
        day.onclick = async function() {
          const habitIndex = parseInt(this.getAttribute('data-habit'));
          const dayIndex = parseInt(this.getAttribute('data-day'));
          const habits = <?php echo json_encode($habits); ?>;
          
          if (!habits[habitIndex].days) habits[habitIndex].days = [];
          habits[habitIndex].days[dayIndex] = !habits[habitIndex].days[dayIndex];
          
          await updateHabits(habits);
          this.classList.toggle('checked');
        };
      });

      // Özel günler silme butonları
      els('#specialDaysList .delete-btn').forEach(btn => {
        btn.onclick = async function() {
          const index = parseInt(this.getAttribute('data-index'));
          const specialDays = <?php echo json_encode($special_days); ?>;
          specialDays.splice(index, 1);
          await updateSpecialDays(specialDays);
          this.parentElement.remove();
        };
      });

      // Takvim günlerine event listener ekle
      els('#calendar .day').forEach(day => {
        day.onclick = function() {
          const date = this.getAttribute('data-date');
          el('#periodStart').value = date;
          // Formu submit et
          setTimeout(() => {
            el('#periodForm').submit();
          }, 100);
        };
      });
      
      // İlk yükleme
      loadYouTubeAPI();
      readCurrentLocation();
    });

    function openModal(id) { 
      document.querySelector(id).style.display = 'flex'; 
    }
    
    function closeModal(id) { 
      document.querySelector(id).style.display = 'none'; 
    }
  </script>
</body>
</html>
