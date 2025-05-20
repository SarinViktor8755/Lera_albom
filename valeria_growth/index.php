<?php
$data = json_decode(file_get_contents('admin/data.json'), true);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Валерия: История роста</title>
    <link rel="icon" type="image/x-icon" href="icons8.png">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css " rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css ">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css ">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Анимация частиц */
        .particles-container {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1; overflow: hidden;
        }
        .particle { position: absolute; width: 2px; height: 2px; background: rgba(255, 255, 255, 0.5); border-radius: 50%; animation: particleFly linear infinite; opacity: 0; }
        @keyframes particleFly {
            0% { transform: translate3d(0, 0, 0) scale(0.5); opacity: 0; }
            10% { opacity: 0.8; }
            50% { transform: translate3d(var(--tx), var(--ty), 0) scale(1.2); opacity: 1; }
            90% { opacity: 0.8; }
            100% { transform: translate3d(calc(var(--tx)*2), calc(var(--ty)*2), 0) scale(0.5); opacity: 0; }
        }
        body { font-family: 'Arial', sans-serif; background-color: #0a0a1a; color: #fff; overflow-x: hidden; }
        .navbar { background: rgba(10, 10, 26, 0.8); backdrop-filter: blur(10px); }
        .container { padding-top: 80px; position: relative; z-index: 1; }
    </style>
</head>
<body>

<!-- Навигация -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Валерия</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="#age"><i class="fas fa-baby mr-1"></i> Возраст</a></li>
                <li class="nav-item"><a class="nav-link" href="#perv"><i class="fas fa-history mr-1"></i> Предыстория</a></li>
                <li class="nav-item"><a class="nav-link" href="#gl1"><i class="fas fa-book-open mr-1"></i> Глава 1</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container unselectable">
    <!-- Галерея из JSON -->
    <div class="anniversary-row">
        <?php foreach ($data as $item): ?>
            <div class="anniversary-frame" onclick='openModal("img/<?php echo htmlspecialchars($item['src']); ?>", "<?php echo addslashes(htmlspecialchars($item['alt'])); ?>")'>
                <img data-src="img/<?php echo htmlspecialchars($item['src']); ?>" alt="<?php echo htmlspecialchars($item['alt']); ?>" class="anniversary-photo loaded" loading="lazy">
                <div class="placeholder"></div>
                <div class="anniversary-text"><?php echo htmlspecialchars($item['text']); ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Модальное окно -->
<div class="modal-overlay" id="modalOverlay" onclick="closeModal()">
    <div class="modal-content" onclick="event.stopPropagation()">
        <img id="modalImage" src="" alt="">
        <div class="modal-text" id="modalText"></div>
    </div>
</div>

<!-- Подвал -->
<footer><div class="container"><p>С любовью создано для Валерии © 2025</p></div></footer>
<div class="back-to-top" id="backToTop"><i class="fas fa-arrow-up"></i></div>

<!-- JS -->
<script src="js_index.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js "></script>
<script src="https://cdn.jsdelivr.net/npm/ @popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js "></script>

<!-- Частицы -->
<script>
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
        container.appendChild(particle);
    }
}
window.addEventListener('load', () => {
    createParticles();
    window.addEventListener('resize', () => {
        const container = document.getElementById('particlesContainer');
        container.innerHTML = '';
        createParticles();
    });
});
</script>

</body>
</html>