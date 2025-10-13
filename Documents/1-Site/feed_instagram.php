<?php
require_once __DIR__ . '/includes/helpers.php';

// =====================================
// CONFIGURAÇÕES GERAIS
// =====================================
$accessToken = getenv('INSTAGRAM_ACCESS_TOKEN') ?: 'SEU_ACCESS_TOKEN_AQUI';
$limit = 9;

// =====================================
// FUNÇÃO PARA CONECTAR À API DO INSTAGRAM
// =====================================
function getInstagramFeed(string $accessToken, int $limit = 9): array
{
    if ($accessToken === '' || $accessToken === 'SEU_ACCESS_TOKEN_AQUI') {
        return [];
    }

    $endpoint = 'https://graph.instagram.com/me/media';
    $fields = 'id,caption,media_type,media_url,permalink,thumbnail_url,timestamp';
    $url = "{$endpoint}?fields={$fields}&access_token={$accessToken}&limit={$limit}";

    $request = http_fetch($url, 10);
    $response = $request['content'] ?? null;
    $error = $request['error'] ?? null;

    if ($error !== null) {
        error_log('Erro ao conectar à API do Instagram: ' . $error);
        return [];
    }

    $data = json_decode((string) $response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log('Erro ao decodificar resposta do Instagram: ' . json_last_error_msg());
        return [];
    }

    if (isset($data['error'])) {
        error_log('Erro da API Instagram: ' . $data['error']['message']);
        return [];
    }

    return $data['data'] ?? [];
}

$posts = getInstagramFeed($accessToken, $limit);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@palomalopes.adv | Instagram Feed</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

<section class="text-center py-10 px-4">
    <h2 class="text-3xl font-semibold mb-3">Acompanhe o dia a dia no Instagram</h2>
    <p class="text-gray-600 max-w-2xl mx-auto leading-relaxed">
        Explore as publicações mais recentes: dicas jurídicas, bastidores da advocacia e orientações sobre imigração e cidadania.
    </p>
    <a href="https://www.instagram.com/palomalopes.adv/" target="_blank"
       class="inline-block mt-4 text-white bg-blue-600 hover:bg-blue-700 px-5 py-2 rounded-lg font-medium transition">
        Seguir @palomalopes.adv
    </a>
</section>

<section class="max-w-6xl mx-auto px-4 pb-16">
    <?php if (empty($posts)): ?>
        <p class="text-center text-red-500 text-lg">
            ⚠️ Não foi possível carregar o feed do Instagram. Verifique o access token e tente novamente.
        </p>
    <?php else: ?>
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                <?php foreach ($posts as $post): ?>
                    <?php
                        $caption = isset($post['caption']) ? $post['caption'] : '';
                        $mediaUrl = $post['media_url'] ?? '';
                        if (($post['media_type'] ?? '') === 'VIDEO' && isset($post['thumbnail_url'])) {
                            $mediaUrl = $post['thumbnail_url'];
                        }
                    ?>
                    <div class="swiper-slide">
                        <a href="<?= htmlspecialchars($post['permalink'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>"
                           target="_blank"
                           class="block overflow-hidden rounded-xl group shadow-md bg-white">
                            <img
                                src="<?= htmlspecialchars($mediaUrl, ENT_QUOTES, 'UTF-8'); ?>"
                                alt="<?= htmlspecialchars(truncate_text($caption, 80), ENT_QUOTES, 'UTF-8'); ?>"
                                class="w-full h-80 object-cover transition-transform duration-300 group-hover:scale-105"
                            >
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-button-next text-gray-700"></div>
            <div class="swiper-button-prev text-gray-700"></div>
            <div class="swiper-pagination"></div>
        </div>
    <?php endif; ?>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        new Swiper('.mySwiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            centeredSlides: true,
            autoplay: {
                delay: 3500,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                640: { slidesPerView: 2 },
                1024: { slidesPerView: 3 },
            },
        });
    });
</script>

</body>
</html>
