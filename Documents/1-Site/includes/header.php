<?php
$pageTitle = $pageTitle ?? 'Paloma Lopes | Advogada de Imigração em Portugal';
$pageDescription = $pageDescription ?? 'Assessoria jurídica especializada em vistos, nacionalidade portuguesa e residência legal no Algarve, Portugal.';
$pageSlug = $pageSlug ?? basename($_SERVER['PHP_SELF'], '.php');

$navItems = [
    ['label' => 'Início', 'url' => '#inicio'],
    ['label' => 'Sobre Mim', 'url' => '#sobre'],
    ['label' => 'Áreas de Atuação', 'url' => '#areas'],
    ['label' => 'Blog', 'url' => '#blog'],
    ['label' => 'Instagram', 'url' => '#instagram'],
    ['label' => 'Contacto', 'url' => '#contacto'],
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8'); ?>">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="page-<?= htmlspecialchars($pageSlug, ENT_QUOTES, 'UTF-8'); ?>">
    <header class="site-header" data-visible="false">
        <div class="container header-inner max-w-6xl mx-auto px-4 md:px-6 lg:px-12">
            <a class="brand" href="#inicio">
                <img class="brand__logo" src="assets/images/logo-paloma.png" alt="Logótipo Paloma Lopes Advocacia">
            </a>
            <nav class="primary-nav hidden lg:block" aria-label="Principal">
                <ul class="nav__list flex items-center gap-8 text-sm tracking-[0.32em] uppercase text-slate-700">
                    <?php foreach ($navItems as $item): ?>
                        <li class="nav__item">
                            <a class="nav__link" href="<?= $item['url']; ?>">
                                <?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    <li class="nav__item">
                        <a class="nav__link nav__link--whatsapp" href="https://wa.me/351963731313" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
                            <svg viewBox="0 0 32 32" aria-hidden="true" focusable="false" class="nav__icon">
                                <defs>
                                    <linearGradient id="whatsappGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" stop-color="#cdb179"/>
                                        <stop offset="100%" stop-color="#b28a4d"/>
                                    </linearGradient>
                                </defs>
                                <path fill="url(#whatsappGradient)" d="M16 2.667c-7.364 0-13.333 5.97-13.333 13.333c0 2.29.595 4.451 1.633 6.34L2.667 29.333l7.19-1.875A13.23 13.23 0 0 0 16 29.333c7.363 0 13.333-5.97 13.333-13.333S23.363 2.667 16 2.667zm0 24c-2.1 0-4.067-.55-5.792-1.508l-.415-.238-4.275 1.115l1.14-4.16-.27-.43a10.51 10.51 0 0 1-1.605-5.383c0-5.83 4.583-10.56 10.217-10.56c5.63 0 10.217 4.73 10.217 10.56S21.63 26.667 16 26.667zm5.435-7.365c-.3-.15-1.767-.87-2.04-.967-.273-.1-.472-.15-.67.15-.197.3-.77.968-.94 1.166-.173.197-.347.222-.645.074-.3-.148-1.269-.468-2.417-1.492-.895-.8-1.497-1.78-1.67-2.08-.173-.297-.018-.458.13-.606.134-.134.3-.347.45-.52.15-.174.2-.297.3-.495.1-.197.05-.37-.025-.52-.075-.148-.67-1.626-.918-2.226-.242-.583-.49-.504-.67-.512-.173-.007-.37-.009-.568-.009c-.197 0-.52.074-.792.37-.272.297-1.043 1.02-1.043 2.486c0 1.466 1.068 2.883 1.216 3.081.15.197 2.103 3.215 5.1 4.505.713.307 1.27.49 1.704.626.715.227 1.366.195 1.883.118.575-.086 1.767-.723 2.018-1.422.25-.7.25-1.3.174-1.42-.075-.12-.273-.197-.572-.346z"/>
                            </svg>
                        </a>
                    </li>
                </ul>
            </nav>
            <button class="nav-toggle lg:hidden" aria-label="Abrir menu" aria-expanded="false">
                <span class="nav-toggle__line"></span>
                <span class="nav-toggle__line"></span>
                <span class="nav-toggle__line"></span>
            </button>
        </div>
    </header>
    <div class="mobile-nav" aria-hidden="true">
        <nav class="mobile-nav__inner" aria-label="Menu mobile">
            <ul class="mobile-nav__list">
                <?php foreach ($navItems as $item): ?>
                    <li class="mobile-nav__item">
                        <a class="mobile-nav__link" href="<?= $item['url']; ?>">
                            <?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </div>
    <main>
