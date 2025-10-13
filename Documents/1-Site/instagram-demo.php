<?php
$accessToken = getenv('INSTAGRAM_ACCESS_TOKEN') ?: 'INSIRA_SEU_ACCESS_TOKEN_AQUI';
$limit = 8;
?><!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@palomalopes.adv – Feed Instagram (Demo)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-800">

<section class="text-center py-10 px-4">
    <h1 class="text-3xl md:text-4xl font-semibold mb-3">Acompanhe o dia a dia no Instagram</h1>
    <p class="text-gray-600 max-w-2xl mx-auto leading-relaxed">
        Explore as publicações mais recentes diretamente aqui na página. Conteúdos sobre imigração, cidadania e bastidores da advocacia.
    </p>
    <a href="https://www.instagram.com/palomalopes.adv/" target="_blank"
       class="inline-block mt-4 text-white bg-blue-600 hover:bg-blue-700 px-5 py-2 rounded-lg font-medium transition">
        Seguir @palomalopes.adv
    </a>
</section>

<section class="max-w-5xl mx-auto px-4 pb-16">
    <div class="swiper mySwiper js-instagram-demo" data-token="<?= htmlspecialchars($accessToken, ENT_QUOTES, 'UTF-8'); ?>" data-limit="<?= (int) $limit; ?>">
        <div class="swiper-wrapper js-instagram-demo-feed"></div>
        <div class="swiper-button-next text-gray-700"></div>
        <div class="swiper-button-prev text-gray-700"></div>
        <div class="swiper-pagination"></div>
    </div>
    <div class="mt-6 text-center text-sm text-gray-500 js-instagram-demo-empty" hidden>
        Não foi possível carregar o feed. Verifique o access token e tente novamente.
    </div>
</section>

<script>
(function () {
    const carousel = document.querySelector('.js-instagram-demo');
    const feedWrapper = document.querySelector('.js-instagram-demo-feed');
    const emptyMessage = document.querySelector('.js-instagram-demo-empty');

    if (!carousel || !feedWrapper) {
        return;
    }

    const accessToken = carousel.dataset.token || '';
    const limit = Number(carousel.dataset.limit || 8);

    if (!accessToken || accessToken === 'INSIRA_SEU_ACCESS_TOKEN_AQUI') {
        if (emptyMessage) emptyMessage.hidden = false;
        return;
    }

    const renderSlides = (posts) => {
        feedWrapper.innerHTML = '';
        posts.forEach((post) => {
            const mediaType = post.media_type || '';
            const captionRaw = post.caption || '';
            const caption = captionRaw ? captionRaw.substring(0, 80) + (captionRaw.length > 80 ? '…' : '') : '';
            const permalink = post.permalink || '#';
            let mediaUrl = post.media_url || '';

            if ((mediaType === 'VIDEO' || mediaType === 'CAROUSEL_ALBUM') && post.thumbnail_url) {
                mediaUrl = post.thumbnail_url;
            }

            const slide = document.createElement('div');
            slide.className = 'swiper-slide';
            slide.innerHTML = `
                <a href="${permalink}" target="_blank" rel="noopener noreferrer"
                   class="block rounded-xl overflow-hidden group shadow-md">
                    <img src="${mediaUrl}"
                         alt="${caption.replace(/"/g, '&quot;') || 'Publicação no Instagram'}"
                         class="w-full h-80 object-cover transition-transform duration-300 group-hover:scale-105">
                </a>`;
            feedWrapper.appendChild(slide);
        });

        // eslint-disable-next-line no-new
        new Swiper('.mySwiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            centeredSlides: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            breakpoints: {
                640: { slidesPerView: 2 },
                1024: { slidesPerView: 3 },
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    };

    fetch(`https://graph.instagram.com/me/media?fields=id,caption,media_type,media_url,permalink,thumbnail_url&access_token=${encodeURIComponent(accessToken)}&limit=${encodeURIComponent(Number.isFinite(limit) ? limit : 8)}`)
        .then((response) => response.json())
        .then((data) => {
            if (!data || !Array.isArray(data.data) || data.data.length === 0) {
                if (emptyMessage) emptyMessage.hidden = false;
                return;
            }
            renderSlides(data.data);
        })
        .catch((error) => {
            console.error('Erro ao carregar o feed do Instagram:', error);
            if (emptyMessage) emptyMessage.hidden = false;
        });
})();
</script>

</body>
</html>
