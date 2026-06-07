<?php
class Database {
    private static $pdo = null;

    public static function getConnection() {
        if (self::$pdo === null) {
            $host = getenv('DATABASE_HOST') ?: (getenv('DB_HOST') ?: 'db');
            $dbname = getenv('DB_NAME') ?: 'app_db';
            $user = getenv('DB_USER') ?: 'appuser';
            $pass = getenv('DB_PASS') ?: 'apppassword';

            try {
                self::$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);

                self::initializeDatabase(self::$pdo);
            } catch (PDOException $e) {
                throw new Exception("Koneksi database gagal: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }

    private static function initializeDatabase($pdo) {
        // Auto-create users table if not exists
        $pdo->exec("CREATE TABLE IF NOT EXISTS users (
          id         INT AUTO_INCREMENT PRIMARY KEY,
          username   VARCHAR(64) NOT NULL UNIQUE,
          password   VARCHAR(255) NOT NULL,
          role       ENUM('admin', 'user') DEFAULT 'user',
          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        // Seed users if empty
        $stmt = $pdo->query("SELECT COUNT(*) FROM users");
        if ($stmt->fetchColumn() == 0) {
            $pdo->exec("INSERT INTO users (username, password, role) VALUES
              ('admin', MD5('admin123'), 'admin'),
              ('user1', MD5('user123'), 'user'),
              ('user2', MD5('user123'), 'user')");
        }

        // Auto-create articles table if not exists
        try {
            $pdo->query("SELECT 1 FROM articles LIMIT 1");
        } catch (PDOException $e) {
            $pdo->exec("CREATE TABLE IF NOT EXISTS articles (
              id         INT AUTO_INCREMENT PRIMARY KEY,
              title      VARCHAR(255) NOT NULL,
              content    TEXT NOT NULL,
              excerpt    VARCHAR(500) NOT NULL,
              mountain   VARCHAR(100) NOT NULL,
              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )");

            // Seed articles
            $stmt = $pdo->prepare("INSERT INTO articles (id, title, content, excerpt, mountain) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                1, 
                'Misteri Keindahan Kawah Ijen: Api Biru yang Mendunia',
                'Kawah Ijen merupakan salah satu destinasi wisata gunung berapi yang paling populer di Indonesia. Terletak di perbatasan Kabupaten Banyuwangi dan Kabupaten Bondowoso, Jawa Timur, gunung ini terkenal karena fenomena langka "Blue Fire" atau api biru yang hanya bisa dilihat pada dini hari sebelum matahari terbit. Api biru ini terjadi akibat pembakaran gas belerang yang keluar dari celah bebatuan belerang dengan suhu mencapai 600 derajat Celsius. Selain api biru, Kawah Ijen juga menyuguhkan pemandangan danau asam berwarna hijau toska yang menakjubkan dan menyaksikan secara langsung aktivitas para penambang belerang tradisional yang tangguh.',
                'Temukan keajaiban fenomena langka Blue Fire dan keindahan danau asam hijau toska di puncak Gunung Ijen.',
                'Gunung Ijen'
            ]);
            $stmt->execute([
                2, 
                'Tips Mendaki Gunung Semeru Bagi Pemula di Tahun 2026',
                'Gunung Semeru, dengan puncaknya Mahameru yang menjulang setinggi 3.676 meter di atas permukaan laut, merupakan gunung tertinggi di Pulau Jawa. Bagi pendaki pemula, mendaki Semeru memerlukan persiapan fisik dan mental yang matang. Beberapa tips penting meliputi: melakukan latihan fisik minimal sebulan sebelum pendakian, melengkapi peralatan standar pendakian gunung, menyiapkan logistik bergizi yang cukup, dan selalu mematuhi instruksi pemandu wisata lokal. Selain itu, penting juga untuk menjaga kebersihan dengan membawa kembali sampah Anda ke bawah dan berkemah hanya di tempat yang ditentukan seperti Ranu Kumbolo.',
                'Panduan lengkap persiapan fisik, mental, peralatan, dan logistik bagi pendaki pemula yang ingin menaklukkan puncak Mahameru.',
                'Gunung Semeru'
            ]);
            $stmt->execute([
                3, 
                'Gunung Merapi Kembali Mengeluarkan Guguran Lava Pijar',
                'Balai Penyelidikan dan Pengembangan Teknologi Kebencanaan Geologi (BPPTKG) melaporkan bahwa Gunung Merapi di perbatasan Jawa Tengah dan Daerah Istimewa Yogyakarta kembali menunjukkan peningkatan aktivitas vulkanik. Merapi terpantau meluncurkan guguran lava pijar sejauh 1,5 kilometer ke arah barat daya (Kali Bebeng). Status aktivitas Merapi saat ini masih berada di Level III (Siaga). Masyarakat dihimbau untuk tetap tenang, tidak melakukan aktivitas apa pun di daerah potensi bahaya dalam radius 5 kilometer dari puncak, serta selalu memantau perkembangan informasi resmi dari BPPTKG.',
                'Informasi terkini mengenai aktivitas vulkanik Gunung Merapi dan himbauan keselamatan bagi warga di sekitar lereng.',
                'Gunung Merapi'
            ]);
            $stmt->execute([
                4, 
                'Pesona Sabana Alun-Alun Surya Kencana di Gunung Gede',
                'Alun-Alun Surya Kencana merupakan sebuah sabana seluas 50 hektar yang berada di ketinggian 2.750 mdpl di Gunung Gede, Jawa Barat. Sabana ini dipenuhi dengan tanaman bunga abadi Edelweiss Jawa (Anaphalis javanica) yang tumbuh subur secara alami. Keindahan hamparan bunga edelweiss berpadu dengan udara dingin pegunungan yang berkabut menjadikan sabana ini sebagai surga tersembunyi bagi para pendaki gunung. Gunung Gede Pangrango adalah kawasan Taman Nasional yang dilindungi, oleh karena itu pendaki dilarang keras memetik edelweiss demi kelestarian ekosistem taman nasional.',
                'Menikmati keindahan bunga abadi Edelweiss di sabana terluas Jawa Barat yang menakjubkan di Gunung Gede.',
                'Gunung Gede'
            ]);
        }
    }
}
