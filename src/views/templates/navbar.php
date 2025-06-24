<?php use App\Core\Config; ?>

<nav class="navbar navbar-expand-md navbar-dark bg-primary py-2 sticky-top">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <!-- LEFT: Menu items -->
        <div class="d-flex flex-grow-1 justify-content-start order-1 order-md-1">
            <button class="navbar-toggler me-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav flex-row gap-3">
                    <li class="nav-item"><a class="nav-link text-white" href="/"><?= _t('nav.home') ?></a></li>
                </ul>
            </div>
        </div>

        <!-- CENTER: App name -->
        <div class="mx-auto order-2 text-center">
            <a href="/" class="navbar-brand m-0 text-white fw-bold fs-4"><?= htmlspecialchars(Config::APP_NAME) ?></a>
        </div>

        <!-- RIGHT: Language selector -->
        <div class="d-flex flex-grow-1 justify-content-end align-items-center order-3">
            <div class="d-none d-md-block">
                <?php include __DIR__ . '/language_selector.php'; ?>
            </div>
        </div>
    </div>
</nav>