<?php
// Database configuration
$dbHost     = "localhost";
$dbPort     = "3306";
$dbName     = "valeria_admin";
$dbUser     = "root";
$dbPassword = "aiwa30";

// Connect to database
try {
    $conn = new PDO("mysql:host=$dbHost;port=$dbPort;dbname=$dbName", $dbUser, $dbPassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Get chapters for navigation
$chaptersNav = $conn->query("SELECT id, title FROM chapters ORDER BY weight")->fetchAll();

// Generate navigation items
$navItems = '';
foreach ($chaptersNav as $chapter) {
    $sectionId = 'chapter-' . $chapter['id'];
    $navItems .= <<<HTML
        <li class="nav-item">
            <a class="nav-link" href="#{$sectionId}"><i class="fas fa-book-open mr-1"></i> {$chapter['title']}</a>
        </li>
HTML;
}

// Get chapters with photos for content
$stmt = $conn->prepare("
    SELECT c.id, c.title, c.additional_info, 
           p.id as photo_id, p.title as photo_title, p.description as photo_description, p.file_path
    FROM chapters c
    LEFT JOIN photos p ON p.chapter_id = c.id
    ORDER BY c.weight, c.title, p.weight, p.created_at
");
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Organize data by chapters
$chapters = [];
foreach ($data as $row) {
    $chapterId = $row['id'];
    
    if (!isset($chapters[$chapterId])) {
        $chapters[$chapterId] = [
            'title' => $row['title'],
            'additional_info' => $row['additional_info'],
            'photos' => []
        ];
    }
    
    if ($row['photo_id']) {
        $chapters[$chapterId]['photos'][] = [
            'id' => $row['photo_id'],
            'title' => $row['photo_title'],
            'description' => $row['photo_description'],
            'file_path' => $row['file_path']
        ];
    }
}

// Generate photo sections
$categorySections = [];
foreach ($chapters as $chapterId => $chapter) {
    $sectionId = 'chapter-' . $chapterId;
    
    $photoFrames = array_map(function($photo) {
        $title = htmlspecialchars($photo['title']);
        $filePath = htmlspecialchars($photo['file_path']);
        $description = htmlspecialchars($photo['description'] ?: $photo['title']);
        
        return <<<HTML
            <div class="anniversary-frame" onclick="openModal('$filePath', '$description')">
                <img data-src="$filePath" alt="$title" class="anniversary-photo" loading="lazy">
                <div class="placeholder">$title</div>
                <div class="anniversary-text">$title</div>
            </div>
HTML;
    }, $chapter['photos']);
    
    $photoFramesHtml = implode("\n", $photoFrames);
    
    $additionalInfo = $chapter['additional_info'] ? '<br>' . htmlspecialchars($chapter['additional_info']) : '';
    
    $categorySections[] = <<<HTML
        <div class="section-divider">
            <div class="section-title" id="$sectionId">{$chapter['title']}$additionalInfo</div>
        </div>
        
        <div class="anniversary-row">
            $photoFramesHtml
        </div>
HTML;
}

$categorySectionsHtml = implode("\n", $categorySections);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Валерия: История роста</title>
    <link rel="icon" type="image/x-icon" href="icons8.png">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="styel.css">

    <style>
        /* Анимация частиц в стиле DOOM/Windows */
        .particles-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }
        
        .particle {
            position: absolute;
            width: 2px;
            height: 2px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            animation: particleFly linear infinite;
            opacity: 0;
        }
        
        @keyframes particleFly {
            0% {
                transform: translate3d(0, 0, 0) scale(0.5);
                opacity: 0;
            }
            10% {
                opacity: 0.8;
            }
            50% {
                transform: translate3d(var(--tx), var(--ty), 0) scale(1.2);
                opacity: 1;
            }
            90% {
                opacity: 0.8;
            }
            100% {
                transform: translate3d(calc(var(--tx) * 2), calc(var(--ty) * 2), 0) scale(0.5);
                opacity: 0;
            }
        }
        
        /* Остальные стили остаются без изменений */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #0a0a1a;
            color: #fff;
            overflow-x: hidden;
        }
        
        .navbar {
            background: rgba(10, 10, 26, 0.8);
            backdrop-filter: blur(10px);
        }
        
        .container {
            padding-top: 80px;
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>
    <!-- Плавающие элементы фона -->
    <div class="floating-elements">
        <div class="floating-element" style="width: 100px; height: 100px; top: 20%; left: 10%; animation-delay: 0s;"></div>
        <div class="floating-element" style="width: 150px; height: 150px; top: 60%; left: 80%; animation-delay: 2s;"></div>
        <div class="floating-element" style="width: 80px; height: 80px; top: 30%; left: 50%; animation-delay: 4s;"></div>
        <div class="floating-element" style="width: 120px; height: 120px; top: 70%; left: 20%; animation-delay: 6s;"></div>
    </div>
    <div class="particles-container" id="particlesContainer"></div>

    <!-- Навигация -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Валерия</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#age"><i class="fas fa-baby mr-1"></i> Возраст</a>
                    </li>
                    <?= $navItems ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Основной контент -->
    <div class="container unselectable">
        <h1 id="name-title"><span class="glow-text">✨</span><span id="name-letters"></span><span class="glow-text">✨</span></h1>
        
        <div class="info">
            <div class="slideshow-container">
                <div class="slideshow" id="slideshow">
                    <img src="img/1.jpg" class="active" loading="lazy">
                    <img src="img/5.jpg" loading="lazy">
                    <img src="img/3.jpg" loading="lazy">
                    <img src="img/2.jpg" loading="lazy">
                    <img src="img/4.jpg" loading="lazy">
                    <img src="img/6.jpg" loading="lazy">
                    <img src="img/PXL_20250401_162439869_1.jpg" loading="lazy">
                </div>
            </div>         
            <p id="age"><span class="emoji">👶</span> Возраст ребенка: <span class="highlight"><br>0 лет, 0 месяцев, 0 дней</span><br></p>
            <p id="nextBirthday"><span class="emoji">🎂</span> До следующего дня рождения: <span class="highlight">355 дней</span></p>
            <div class="progressBarContainer">
                <div id="progressBarYear" class="progressBar" style="width: 2.88186%;"></div>
            </div>
            
            <p id="totalDays"><span class="emoji">📅</span> Всего дней жизни: <span class="highlight"></span></p>
            <div class="progressBarContainer">
                <div id="progressBarTotalDays" class="progressBar" style="width: 0%;"></div>
            </div>

            <p id="kindergartenTime"><span class="emoji">🏫</span> До детского сада: <span class="highlight"></span></p>
            <div class="progressBarContainer">
                <div id="progressBarKindergarten" class="progressBar" style="width: 0%;"></div>
            </div>
            
            <p id="fiveYears"><span class="emoji">🎈</span> До 5-летия: <span class="highlight"></span></p>
            <div class="progressBarContainer">
                <div id="progressBar5" class="progressBar" style="width: 0.552668%;"></div>
            </div>
            <p id="schoolTime"><span class="emoji">📚</span> До школы: <span class="highlight"></span></p>
            <div class="progressBarContainer">
                <div id="progressBarSchool" class="progressBar" style="width: 0.369989%;"></div>
            </div>
            <p id="tenYears"><span class="emoji">🌟</span> До 10-летия: <span class="highlight"></span></p>
            <div class="progressBarContainer">
                <div id="progressBar10" class="progressBar" style="width: 0.276334%;"></div>
            </div>
            <p id="eighteenYears"><span class="emoji">🎉</span> До 18-летия: <span class="highlight"></span></p>
            <div class="progressBarContainer">
                <div id="progressBar18" class="progressBar" style="width: 0.15351%;"></div>
            </div>
            <p id="retirement"><span class="emoji">👵</span> До пенсии: <span class="highlight"></span></p>
            <div class="progressBarContainer">
                <div id="progressBarRetirement" class="progressBar" style="width: 0.0460493%;"></div>
            </div>
        </div>
        
        <!-- Разделы с фотографиями, сгенерированные из базы данных -->
        <?= $categorySectionsHtml ?>
    </div>

    <!-- Подвал -->
    <footer>
        <div class="container">
            <p>С любовью создано для Валерии © 2025</p>
        </div>
    </footer>

    <!-- Кнопка "Наверх" -->
    <div class="back-to-top" id="backToTop">
        <i class="fas fa-arrow-up"></i>
    </div>

    <!-- Модальное окно для увеличенного просмотра -->
    <div class="modal-overlay" id="modalOverlay" onclick="closeModal()">
        <div class="modal-content" onclick="event.stopPropagation()">
            <img id="modalImage" src="" alt="">
            <div class="modal-text" id="modalText"></div>
        </div>
    </div>
    
    <!-- Скрипт для создания частиц -->
    <script>
        // Создание частиц
        function createParticles() {
            const container = document.getElementById('particlesContainer');
            const particleCount = 150; // Количество частиц
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                // Начальная позиция
                const startX = Math.random() * window.innerWidth;
                const startY = Math.random() * window.innerHeight;
                
                // Конечная позиция (случайное направление)
                const angle = Math.random() * Math.PI * 2;
                const distance = 100 + Math.random() * 300;
                const tx = Math.cos(angle) * distance;
                const ty = Math.sin(angle) * distance;
                
                // Случайный размер и задержка анимации
                const size = 1 + Math.random() * 3;
                const delay = Math.random() * 5;
                const duration = 2 + Math.random() * 3; // Быстрая анимация как в DOOM
                
                // Применяем стили
                particle.style.left = `${startX}px`;
                particle.style.top = `${startY}px`;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.setProperty('--tx', `${tx}px`);
                particle.style.setProperty('--ty', `${ty}px`);
                particle.style.animationDuration = `${duration}s`;
                particle.style.animationDelay = `${delay}s`;
                
                // Цвет частицы (можно сделать оранжевые как в DOOM)
                const isOrange = Math.random() > 0.7;
                if (isOrange) {
                    particle.style.background = 'rgba(255, 100, 0, 0.8)';
                    particle.style.boxShadow = '0 0 10px 2px rgba(255, 100, 0, 0.5)';
                } else {
                    const brightness = 150 + Math.random() * 105;
                    particle.style.background = `rgba(${brightness}, ${brightness}, ${brightness}, 0.7)`;
                }
                
                container.appendChild(particle);
            }
        }
        
        // Инициализация при загрузке
        window.addEventListener('load', () => {
            createParticles();
            
            // Пересоздаем частицы при изменении размера окна
            window.addEventListener('resize', () => {
                const container = document.getElementById('particlesContainer');
                container.innerHTML = '';
                createParticles();
            });
        });
    </script>
    
    <script src="js_index.js"></script>
    
    <!-- Bootstrap JS и зависимости -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>