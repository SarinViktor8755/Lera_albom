<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Получаем фото для разных разделов
$pervPhotos = getPhotosByCategory('perv');
$gl1Photos = getPhotosByCategory('gl1');

// Дата рождения Валерии
$birthDate = new DateTime('2025-03-14');
$currentDate = new DateTime();
$ageInterval = $currentDate->diff($birthDate);

// Рассчитываем прогресс
function calculateProgress($current, $total) {
    return min(100, max(0, ($current / $total) * 100));
}

$ageYears = $ageInterval->y;
$ageMonths = $ageInterval->m;
$ageDays = $ageInterval->d;
$totalDays = $ageInterval->days;

// До следующих событий
$nextBirthday = new DateTime($currentDate->format('Y') . '-03-14');
if ($nextBirthday < $currentDate) {
    $nextBirthday->modify('+1 year');
}
$daysToBirthday = $currentDate->diff($nextBirthday)->days;

// Проценты для прогресс-баров
$yearProgress = calculateProgress($totalDays, 365);
$kindergartenDate = new DateTime('2028-09-01');
$kindergartenDays = $currentDate->diff($kindergartenDate)->days;
$kindergartenProgress = calculateProgress($totalDays, $totalDays + $kindergartenDays);

$fiveYearsDate = new DateTime('2030-03-14');
$fiveYearsDays = $currentDate->diff($fiveYearsDate)->days;
$fiveYearsProgress = calculateProgress($totalDays, $totalDays + $fiveYearsDays);

$schoolDate = new DateTime('2031-09-01');
$schoolDays = $currentDate->diff($schoolDate)->days;
$schoolProgress = calculateProgress($totalDays, $totalDays + $schoolDays);

$tenYearsDate = new DateTime('2035-03-14');
$tenYearsDays = $currentDate->diff($tenYearsDate)->days;
$tenYearsProgress = calculateProgress($totalDays, $totalDays + $tenYearsDays);

$eighteenYearsDate = new DateTime('2043-03-14');
$eighteenYearsDays = $currentDate->diff($eighteenYearsDate)->days;
$eighteenYearsProgress = calculateProgress($totalDays, $totalDays + $eighteenYearsDays);

$retirementDate = new DateTime('2090-03-14');
$retirementDays = $currentDate->diff($retirementDate)->days;
$retirementProgress = calculateProgress($totalDays, $totalDays + $retirementDays);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Валерия: История роста</title>
    <link rel="icon" type="image/x-icon" href="icons8.png">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        /* Ваши стили из оригинального файла */
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
        
        /* Добавьте остальные стили из оригинального файла */
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
                    <li class="nav-item">
                        <a class="nav-link" href="#perv"><i class="fas fa-history mr-1"></i> Предыстория</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#gl1"><i class="fas fa-book-open mr-1"></i> Глава 1</a>
                    </li>
                    <?php if (isAuthenticated()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="admin/"><i class="fas fa-cog mr-1"></i> Админ</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Основной контент -->
    <div class="container unselectable">
        <h1 id="name-title"><span class="glow-text">✨</span><span id="name-letters">Валерия</span><span class="glow-text">✨</span></h1>
        
        <div class="info">
            <div class="slideshow-container">
                <div class="slideshow" id="slideshow">
                    <?php 
                    $allPhotos = array_merge($pervPhotos, $gl1Photos);
                    shuffle($allPhotos);
                    foreach (array_slice($allPhotos, 0, 10) as $photo): ?>
                        <img src="<?php echo UPLOAD_DIR . htmlspecialchars($photo['filename']); ?>" loading="lazy">
                    <?php endforeach; ?>
                </div>
            </div>         
            
            <p id="age"><span class="emoji">👶</span> Возраст ребенка: <span class="highlight"><br><?php echo $ageYears; ?> лет, <?php echo $ageMonths; ?> месяцев, <?php echo $ageDays; ?> дней</span><br></p>
            <p id="nextBirthday"><span class="emoji">🎂</span> До следующего дня рождения: <span class="highlight"><?php echo $daysToBirthday; ?> дней</span></p>
            <div class="progressBarContainer">
                <div id="progressBarYear" class="progressBar" style="width: <?php echo $yearProgress; ?>%;"></div>
            </div>
            
            <p id="totalDays"><span class="emoji">📅</span> Всего дней жизни: <span class="highlight"><?php echo $totalDays; ?></span></p>
            <div class="progressBarContainer">
                <div id="progressBarTotalDays" class="progressBar" style="width: <?php echo calculateProgress($totalDays, 365*100); ?>%;"></div>
            </div>

            <p id="kindergartenTime"><span class="emoji">🏫</span> До детского сада: <span class="highlight"><?php echo $kindergartenDays; ?> дней</span></p>
            <div class="progressBarContainer">
                <div id="progressBarKindergarten" class="progressBar" style="width: <?php echo $kindergartenProgress; ?>%;"></div>
            </div>
            
            <p id="fiveYears"><span class="emoji">🎈</span> До 5-летия: <span class="highlight"><?php echo $fiveYearsDays; ?> дней</span></p>
            <div class="progressBarContainer">
                <div id="progressBar5" class="progressBar" style="width: <?php echo $fiveYearsProgress; ?>%;"></div>
            </div>
            
            <p id="schoolTime"><span class="emoji">📚</span> До школы: <span class="highlight"><?php echo $schoolDays; ?> дней</span></p>
            <div class="progressBarContainer">
                <div id="progressBarSchool" class="progressBar" style="width: <?php echo $schoolProgress; ?>%;"></div>
            </div>
            
            <p id="tenYears"><span class="emoji">🌟</span> До 10-летия: <span class="highlight"><?php echo $tenYearsDays; ?> дней</span></p>
            <div class="progressBarContainer">
                <div id="progressBar10" class="progressBar" style="width: <?php echo $tenYearsProgress; ?>%;"></div>
            </div>
            
            <p id="eighteenYears"><span class="emoji">🎉</span> До 18-летия: <span class="highlight"><?php echo $eighteenYearsDays; ?> дней</span></p>
            <div class="progressBarContainer">
                <div id="progressBar18" class="progressBar" style="width: <?php echo $eighteenYearsProgress; ?>%;"></div>
            </div>
            
            <p id="retirement"><span class="emoji">👵</span> До пенсии: <span class="highlight"><?php echo $retirementDays; ?> дней</span></p>
            <div class="progressBarContainer">
                <div id="progressBarRetirement" class="progressBar" style="width: <?php echo $retirementProgress; ?>%;"></div>
            </div>
        </div>
        
        <!-- Раздел "Первые дни" -->
        <div class="section-divider">
            <div class="section-title" id="perv">Предыстория</div>
        </div>
        
        <div class="anniversary-row">
            <?php foreach ($pervPhotos as $photo): ?>
                <div class="anniversary-frame" onclick="openModal('<?php echo UPLOAD_DIR . htmlspecialchars($photo['filename']); ?>', '<?php echo htmlspecialchars($photo['caption']); ?>')">
                    <img data-src="<?php echo UPLOAD_DIR . htmlspecialchars($photo['filename']); ?>" alt="<?php echo htmlspecialchars($photo['caption']); ?>" class="anniversary-photo" loading="lazy">
                    <div class="placeholder"><?php echo htmlspecialchars($photo['caption']); ?></div>
                    <div class="anniversary-text"><?php echo htmlspecialchars($photo['caption']); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Раздел "Глава - 1" -->
        <div class="section-divider">
            <div class="section-title" id="gl1">Глава - 1</div>
        </div>
        
        <div class="anniversary-row">
            <?php foreach ($gl1Photos as $photo): ?>
                <div class="anniversary-frame" onclick="openModal('<?php echo UPLOAD_DIR . htmlspecialchars($photo['filename']); ?>', '<?php echo htmlspecialchars($photo['caption']); ?>')">
                    <img data-src="<?php echo UPLOAD_DIR . htmlspecialchars($photo['filename']); ?>" alt="<?php echo htmlspecialchars($photo['caption']); ?>" class="anniversary-photo" loading="lazy">
                    <div class="placeholder"><?php echo htmlspecialchars($photo['caption']); ?></div>
                    <div class="anniversary-text"><?php echo htmlspecialchars($photo['caption']); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Подвал -->
    <footer>
        <div class="container">
            <p>С любовью создано для Валерии © <?php echo date('Y'); ?></p>
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

    <!-- Скрипты -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <script>
        // Создание частиц
        function createParticles() {
            const container = document.getElementById('particlesContainer');
            const particleCount = 150;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                const startX = Math.random() * window.innerWidth;
                const startY = Math.random() * window.innerHeight;
                
                const angle = Math.random() * Math.PI * 2;
                const distance = 100 + Math.random() * 300;
                const tx = Math.cos(angle) * distance;
                const ty = Math.sin(angle) * distance;
                
                const size = 1 + Math.random() * 3;
                const delay = Math.random() * 5;
                const duration = 2 + Math.random() * 3;
                
                particle.style.left = `${startX}px`;
                particle.style.top = `${startY}px`;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.setProperty('--tx', `${tx}px`);
                particle.style.setProperty('--ty', `${ty}px`);
                particle.style.animationDuration = `${duration}s`;
                particle.style.animationDelay = `${delay}s`;
                
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
        
        // Модальное окно
        function openModal(src, text) {
            document.getElementById('modalImage').src = src;
            document.getElementById('modalText').textContent = text;
            document.getElementById('modalOverlay').style.display = 'flex';
        }
        
        function closeModal() {
            document.getElementById('modalOverlay').style.display = 'none';
        }
        
        // Слайдшоу
        let currentSlide = 0;
        const slides = document.querySelectorAll('#slideshow img');
        
        function showSlide(n) {
            slides.forEach(slide => slide.classList.remove('active'));
            currentSlide = (n + slides.length) % slides.length;
            slides[currentSlide].classList.add('active');
        }
        
        function nextSlide() {
            showSlide(currentSlide + 1);
        }
        
        // Инициализация
        window.addEventListener('load', () => {
            createParticles();
            
            // Инициализация слайдшоу
            if (slides.length > 0) {
                showSlide(0);
                setInterval(nextSlide, 3000);
            }
            
            // Пересоздаем частицы при изменении размера окна
            window.addEventListener('resize', () => {
                const container = document.getElementById('particlesContainer');
                container.innerHTML = '';
                createParticles();
            });
            
            // Кнопка "Наверх"
            const backToTop = document.getElementById('backToTop');
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 300) {
                    backToTop.style.display = 'block';
                } else {
                    backToTop.style.display = 'none';
                }
            });
            
            backToTop.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });
    </script>
</body>
</html>