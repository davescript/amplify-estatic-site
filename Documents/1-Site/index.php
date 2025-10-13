<?php
require_once __DIR__ . '/includes/helpers.php';

$pageSlug = 'index';

$formData = [
    'name' => '',
    'email' => '',
    'phone' => '',
    'subject' => '',
    'message' => '',
];
$feedback = null;
$instagramAccessToken = getenv('INSTAGRAM_ACCESS_TOKEN') ?: 'COLOQUE_SEU_ACCESS_TOKEN_DO_INSTAGRAM';
$instagramLimit = 9;
$instagramCacheFile = __DIR__ . '/cache/instagram-feed.json';
$instagramCacheTTL = 60 * 10; // 10 minutos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData['name'] = trim($_POST['name'] ?? '');
    $formData['email'] = trim($_POST['email'] ?? '');
    $formData['phone'] = trim($_POST['phone'] ?? '');
    $formData['subject'] = trim($_POST['subject'] ?? '');
    $formData['message'] = trim($_POST['message'] ?? '');

    $errors = [];

    if ($formData['name'] === '') {
        $errors[] = 'Por favor, informe o seu nome completo.';
    }

    if (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Informe um e-mail válido.';
    }

    if ($formData['phone'] === '') {
        $errors[] = 'Informe um telefone para contacto.';
    }

    if ($formData['subject'] === '') {
        $errors[] = 'Inclua um assunto para a mensagem.';
    }

    if (strlen($formData['message']) < 10) {
        $errors[] = 'A mensagem precisa ter pelo menos 10 caracteres.';
    }

    if (empty($errors)) {
        $emailAdmin = 'email@palomalopesadvogada.pt';
        $safeSubject = str_replace(["\r", "\n"], '', $formData['subject']);
        $safeName = str_replace(["\r", "\n"], ' ', $formData['name']);

        $bodyLines = [
            "Nome: {$safeName}",
            "Email: {$formData['email']}",
            "Telefone: {$formData['phone']}",
            "Assunto: {$safeSubject}",
            "Mensagem:",
            $formData['message'],
        ];

        $body = implode("\n\n", $bodyLines);
        $headers = [
            'From' => "{$safeName} <{$formData['email']}>",
            'Reply-To' => $formData['email'],
            'X-Mailer' => 'PHP/' . phpversion(),
        ];

        $formattedHeaders = '';
        foreach ($headers as $key => $value) {
            $formattedHeaders .= $key . ': ' . $value . "\r\n";
        }

        if (@mail($emailAdmin, $safeSubject, $body, $formattedHeaders)) {
            $feedback = [
                'type' => 'success',
                'message' => 'Mensagem enviada com sucesso! Entrarei em contacto em breve.',
            ];
            $formData = ['name' => '', 'email' => '', 'phone' => '', 'subject' => '', 'message' => ''];
        } else {
            $feedback = [
                'type' => 'error',
                'message' => 'Não foi possível enviar a mensagem. Tente novamente mais tarde.',
            ];
        }
    } else {
        $feedback = [
            'type' => 'error',
            'message' => implode(' ', $errors),
        ];
    }
}

include 'includes/header.php';
?>

<section id="inicio" class="hero py-12 sm:py-16" data-slides='["assets/images/hero-paloma.jpg"]'>
    <div class="hero__content reveal max-w-4xl mx-auto px-4 md:px-6 lg:px-12">
        <span class="hero__eyebrow">Advocacia de Imigração</span>
        <h1 class="hero__title">Paloma Lopes: A Sua Advogada de Imigração no Algarve, Portugal</h1>
        <p class="hero__subtitle leading-relaxed text-white/90">
            Descomplique a sua jornada em Portugal. Especialista em Nacionalidade, Vistos e Residência Legal,
            ofereço acompanhamento jurídico estratégico para transformar planos em conquistas.
        </p>
        <div class="hero__actions">
            <a class="button button--primary" href="#contacto">Agendar Consulta</a>
            <a class="button button--outline button--whatsapp" href="https://wa.me/351963731313" target="_blank" rel="noopener noreferrer">
                <span aria-hidden="true">💬</span> Falar no WhatsApp
            </a>
        </div>
        <div class="pillars hero-pillars">
            <div class="section__heading hero-pillars__heading">Nossos pilares</div>
            <article class="pillar-card">
                <h3 class="pillar-card__title">Experiência</h3>
                <p class="pillar-card__text">Conhecimento aprofundado das leis e procedimentos de imigração portugueses.</p>
            </article>
            <article class="pillar-card">
                <h3 class="pillar-card__title">Personalização</h3>
                <p class="pillar-card__text">Soluções jurídicas adaptadas ao seu perfil, objetivos e prazos.</p>
            </article>
            <article class="pillar-card">
                <h3 class="pillar-card__title">Transparência</h3>
                <p class="pillar-card__text">Comunicação clara em cada etapa, com relatórios e orientações objetivas.</p>
            </article>
            <article class="pillar-card">
                <h3 class="pillar-card__title">Apoio Contínuo</h3>
                <p class="pillar-card__text">Suporte desde o primeiro contacto até a conclusão do processo e além.</p>
            </article>
        </div>
    </div>
</section>

<section id="sobre" class="section section--light py-12 sm:py-16">
    <div class="container max-w-6xl mx-auto px-4 md:px-6 lg:px-12">
        <div class="bg-white/90 rounded-xl shadow-sm p-6 sm:p-8 lg:p-10">
            <div class="section__heading">Sobre mim</div>
            <h2 class="section__title">Paloma Lopes – Advogada</h2>
            <div class="profile-grid">
            <div class="profile-intro reveal">
                <p class="section__description leading-relaxed text-gray-700" style="margin-bottom:1.6rem;">
                    Advogada habilitada no Brasil e em Portugal, atuo com excelência e proximidade na área de Direito da
                    Nacionalidade e Imigração. Acompanho brasileiros e estrangeiros que desejam viver, trabalhar ou obter
                    nacionalidade portuguesa, com foco total em segurança jurídica, clareza de informações e atendimento
                    personalizado.
                </p>
                <div class="profile-highlight">
                    <h3 class="profile-subtitle">Porquê contar comigo?</h3>
                    <ul class="profile-list">
                        <li>Atuação nos dois países (Brasil e Portugal).</li>
                        <li>Conhecimento atualizado da legislação e dos trâmites administrativos.</li>
                        <li>Soluções práticas e orientadas para resultados.</li>
                        <li>Ética, transparência, empatia e comunicação acessível.</li>
                    </ul>
                </div>
                <div class="profile-highlight">
                    <h3 class="profile-subtitle">A minha missão</h3>
                    <p class="profile-text leading-relaxed text-gray-700">
                        Proporcionar um serviço jurídico de excelência, desmistificando o processo migratório e tornando-o
                        acessível para quem deseja viver, investir ou construir família em Portugal. Cada dossier recebe
                        acompanhamento próximo, relatórios claros e estratégia feita à medida.
                    </p>
                </div>
                <a class="button button--primary" href="#contacto" style="margin-top:1rem;">Agendar uma consulta</a>
            </div>
            <div class="profile-side reveal">
                <article class="info-card">
                    <h3 class="profile-subtitle">Facilidades no atendimento</h3>
                    <ul class="profile-list">
                        <li>Hora marcada</li>
                        <li>Wifi disponível</li>
                        <li>Salas privadas</li>
                    </ul>
                </article>
                <article class="info-card">
                    <h3 class="profile-subtitle">Horário de funcionamento</h3>
                    <ul class="profile-list">
                        <li>Segunda a Sexta: 09h00 – 18h00</li>
                        <li>Sábado e Domingo: Encerrado</li>
                    </ul>
                </article>
                <article class="info-card">
                    <h3 class="profile-subtitle">Contactos diretos</h3>
                    <ul class="profile-list">
                        <li>Email: <a href="mailto:adv.palomalopes@gmail.com">adv.palomalopes@gmail.com</a></li>
                        <li>Telefone/WhatsApp: <a href="tel:+351963731313">+351 963 731 313</a></li>
                        <li>Website: <a href="#inicio">Paloma Lopes Advocacia</a></li>
                    </ul>
                </article>
                <article class="info-card">
                    <h3 class="profile-subtitle">Conte com uma advogada preparada</h3>
                    <p class="profile-text leading-relaxed text-gray-700">
                        Um advogado de imigração garante conformidade com a lei, reduz riscos e oferece clareza sobre cada
                        etapa rumo à residência ou à nacionalidade portuguesa. Vamos construir o seu projeto com confiança?
                    </p>
                </article>
            </div>
        </div>
        </div>
    </div>
</section>

<section id="areas" class="section py-12 sm:py-16">
    <div class="container max-w-6xl mx-auto px-4 md:px-6 lg:px-12 reveal">
        <div class="bg-white/90 rounded-xl shadow-sm p-6 sm:p-8 lg:p-10">
            <div class="section__heading">Áreas de atuação</div>
            <h2 class="section__title">Serviços jurídicos especializados em imigração para Portugal</h2>
            <p class="section__description leading-relaxed text-gray-700">
                Atuação completa e personalizada para garantir tranquilidade na sua jornada migratória: da nacionalidade aos
                vistos, da regularização à documentação complementar.
            </p>
            <div class="areas-grid">
            <article class="area-card area-card--nacionalidade">
                <h3 class="area-card__title">Nacionalidade Portuguesa</h3>
                <ul>
                    <li>Abertura e acompanhamento do processo direto em Portugal.</li>
                    <li>Filhos e netos de portugueses.</li>
                    <li>Tempo de residência legal.</li>
                    <li>Casamento ou união de facto.</li>
                    <li>Filhos nascidos em Portugal.</li>
                    <li>Pais estrangeiros de filhos portugueses.</li>
                </ul>
            </article>
            <article class="area-card area-card--vistos">
                <h3 class="area-card__title">Vistos</h3>
                <ul>
                    <li>Preparação completa para vistos de estada temporária e residência.</li>
                    <li>Visto D7 (rendimentos/reformados) e Visto D2 (empreendedores).</li>
                    <li>Vistos de estudante, trabalho, nómada digital e reagrupamento familiar.</li>
                </ul>
            </article>
            <article class="area-card area-card--legalizacao">
                <h3 class="area-card__title">Legalização e Regularização</h3>
                <ul>
                    <li>Consultoria para entrada legal e regularização de imigrantes.</li>
                    <li>Autorização de residência temporária ou permanente.</li>
                    <li>Manifestação de Interesse (art. 88.º/89.º) e acompanhamento no AIMA.</li>
                    <li>Ações judiciais para agendamentos de concessão ou renovação.</li>
                </ul>
            </article>
            <article class="area-card area-card--reagrupamento">
                <h3 class="area-card__title">Reagrupamento Familiar</h3>
                <ul>
                    <li>Apoio jurídico a residentes que desejam reunir a família em Portugal.</li>
                    <li>Preparação documental e representação em processos junto à AIMA.</li>
                </ul>
            </article>
            <article class="area-card area-card--documentos">
                <h3 class="area-card__title">Outros Documentos Essenciais</h3>
                <ul>
                    <li>NIF, NISS, abertura de atividade e troca de morada fiscal.</li>
                    <li>Apostila de Haia, reconhecimentos e certificações.</li>
                    <li>Passaporte, título eleitoral, alistamento e dispensa militar.</li>
                    <li>Procurações, contratos e autorizações de viagem para menores.</li>
                    <li>Substituição de cartas de condução estrangeiras e traduções certificadas.</li>
                </ul>
            </article>
            <article class="area-card area-card--registo">
                <h3 class="area-card__title">Alteração de Registo Civil</h3>
                <ul>
                    <li>Casamento civil e transcrição de casamento.</li>
                    <li>Divórcio consensual e homologação de sentenças estrangeiras.</li>
                    <li>Atualização e regularização de registos civis em Portugal.</li>
                </ul>
            </article>
            </div>
        </div>
    </div>
</section>

<section id="blog" class="section section--light py-12 sm:py-16">
    <div class="container max-w-6xl mx-auto px-4 md:px-6 lg:px-12 reveal">
        <div class="bg-white/90 rounded-xl shadow-sm p-6 sm:p-8 lg:p-10">
            <div class="section__heading">Blog</div>
            <h2 class="section__title">Conteúdos para orientar a sua decisão</h2>
            <p class="section__description leading-relaxed text-gray-700">
                Em breve, artigos com guias práticos, atualizações legislativas e dicas sobre imigração, nacionalidade e vida
                em Portugal. Fique atento!
            </p>
            <div class="post-grid">
            <article class="post-card reveal bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden">
                <div class="post-card__media">
                    <img src="assets/images/blog-01.svg" alt="Checklist jurídico para renovação de vistos">
                </div>
                <div>
                    <h3 class="post-card__title">Checklist para renovar vistos sem erros</h3>
                    <p class="post-card__excerpt">
                        Documentos essenciais, prazos e boas práticas para planear a renovação da autorização de residência
                        em Portugal.
                    </p>
                </div>
            </article>
            <article class="post-card reveal bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden">
                <div class="post-card__media">
                    <img src="assets/images/blog-02.svg" alt="Compliance migratório para empresas em Portugal">
                </div>
                <div>
                    <h3 class="post-card__title">Compliance migratório para empresas portuguesas</h3>
                    <p class="post-card__excerpt">
                        Como estruturar políticas internas e treinar equipas para contratar profissionais estrangeiros com
                        segurança jurídica.
                    </p>
                </div>
            </article>
            <article class="post-card reveal bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden">
                <div class="post-card__media">
                    <img src="assets/images/blog-03.svg" alt="Mediação e acordos em processos de imigração">
                </div>
                <div>
                    <h3 class="post-card__title">Mediação como aliada durante o processo migratório</h3>
                    <p class="post-card__excerpt">
                        Estratégias para resolver impasses documentais e familiares através de acordos personalizados.
                    </p>
                </div>
            </article>
            </div>
        </div>
    </div>
</section>

<section id="instagram" class="section py-12 sm:py-16">
    <div class="container max-w-6xl mx-auto px-4 md:px-6 lg:px-12 reveal">
        <div class="bg-white/90 rounded-xl shadow-sm p-6 sm:p-8 lg:p-10">
            <div class="section__heading">Instagram</div>
            <h2 class="section__title">Acompanhe o dia a dia da advocacia de imigração</h2>
            <p class="section__description leading-relaxed text-gray-700">
                Casos de sucesso, bastidores e atualizações em tempo real diretamente do perfil
                <a href="https://www.instagram.com/palomalopes.adv" target="_blank" rel="noopener noreferrer">@palomalopes.adv</a>.
            </p>
        <?php
        /**
         * Carrega o feed do Instagram, com suporte a cache e tratamento de erros simples.
         */
        $instagramPosts = [];
        $cacheAvailable = false;

        // Verifica se existe cache recente
        if (is_file($instagramCacheFile) && (filemtime($instagramCacheFile) + $instagramCacheTTL) > time()) {
            $cached = json_decode(file_get_contents($instagramCacheFile), true);
            if (is_array($cached)) {
                $instagramPosts = $cached;
                $cacheAvailable = true;
            }
        }

        // Se não há cache válido, chama API
        if (!$cacheAvailable) {
            if ($instagramAccessToken && $instagramAccessToken !== 'COLOQUE_SEU_ACCESS_TOKEN_DO_INSTAGRAM') {
                $endpoint = 'https://graph.instagram.com/me/media';
                $fields = 'id,caption,media_type,media_url,permalink,thumbnail_url,timestamp';
                $query = http_build_query([
                    'fields' => $fields,
                    'access_token' => $instagramAccessToken,
                    'limit' => $instagramLimit,
                ]);
                $url = $endpoint . '?' . $query;

                $request = http_fetch($url, 10);
                $response = $request['content'] ?? null;
                $error = $request['error'] ?? null;

                if ($error === null && $response) {
                    $data = json_decode($response, true);
                    if (isset($data['data']) && is_array($data['data'])) {
                        $instagramPosts = $data['data'];

                        // Garante a pasta de cache criada e grava JSON
                        if (!is_dir(dirname($instagramCacheFile))) {
                            mkdir(dirname($instagramCacheFile), 0775, true);
                        }
                        file_put_contents($instagramCacheFile, json_encode($instagramPosts));
                    } elseif (isset($data['error']['message'])) {
                        error_log('Erro na API Instagram: ' . $data['error']['message']);
                    } elseif (json_last_error() !== JSON_ERROR_NONE) {
                        error_log('Erro ao decodificar resposta do Instagram: ' . json_last_error_msg());
                    }
                } elseif ($error !== null) {
                    error_log('Erro ao carregar feed do Instagram: ' . $error);
                }
            }
        }
        ?>
        <?php if (!empty($instagramPosts)): ?>
            <div class="instagram-section">
                <div class="swiper instagram-carousel js-instagram-carousel">
                    <div class="swiper-wrapper">
                        <?php foreach ($instagramPosts as $post): ?>
                            <?php
                                $mediaType = $post['media_type'] ?? '';
                                $captionRaw = $post['caption'] ?? '';
                                $caption = truncate_text($captionRaw, 80);
                                $permalink = $post['permalink'] ?? '#';
                                $mediaUrl = $post['media_url'] ?? '';
                                if (($mediaType === 'VIDEO' || $mediaType === 'CAROUSEL_ALBUM') && !empty($post['thumbnail_url'])) {
                                    $mediaUrl = $post['thumbnail_url'];
                                }
                            ?>
                            <div class="swiper-slide">
                                <a class="instagram-card" href="<?= htmlspecialchars($permalink, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">
                                    <div class="instagram-card__media">
                                        <img src="<?= htmlspecialchars($mediaUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($caption !== '' ? $caption : 'Publicação no Instagram', ENT_QUOTES, 'UTF-8'); ?>">
                                        <?php if ($mediaType === 'VIDEO'): ?>
                                            <span class="instagram-card__badge">Vídeo</span>
                                        <?php elseif ($mediaType === 'CAROUSEL_ALBUM'): ?>
                                            <span class="instagram-card__badge">Álbum</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($caption !== ''): ?>
                                        <div class="instagram-card__meta">
                                            <p class="instagram-card__caption"><?= htmlspecialchars($caption, ENT_QUOTES, 'UTF-8'); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="swiper-button-next js-instagram-next"></div>
                    <div class="swiper-button-prev js-instagram-prev"></div>
                    <div class="swiper-pagination js-instagram-pagination"></div>
                </div>
                <?php if (!$cacheAvailable): ?>
                    <div class="instagram-empty" role="note">
                        <p>Os dados foram carregados em tempo real via API do Instagram. Utilize tokens de longa duração e considere configurar um cron job para renovar o cache.</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="instagram-empty" role="status">
                <p>
                    Não foi possível carregar o feed no momento. Verifique o Access Token e as permissões da API ou aceda ao perfil
                    <a href="https://www.instagram.com/palomalopes.adv" target="_blank" rel="noopener noreferrer">@palomalopes.adv</a>.
                </p>
            </div>
        <?php endif; ?>
        </div>
    </div>
</section>

<section id="contacto" class="section section--light py-12 sm:py-16">
    <div class="container max-w-6xl mx-auto px-4 md:px-6 lg:px-12">
        <div class="bg-white/90 rounded-xl shadow-sm p-6 sm:p-8 lg:p-10 contact-wrapper--single">
            <div class="reveal contact-info">
                <div>
                    <div class="section__heading" style="color: var(--color-accent);">Contacto</div>
                    <h2 class="section__title" style="margin-bottom: 1rem;">Fale comigo</h2>
                    <p class="section__description leading-relaxed text-gray-700" style="margin-bottom: 1.6rem;">
                        Pronto(a) para avançar com a sua jornada em Portugal? Envie a sua mensagem para agendar uma consulta e
                        descobrir como posso ajudar.
                    </p>
                </div>
                <div class="contact-info__item">
                    <span class="contact-info__label">Nome</span>
                    <span class="contact-info__value">Paloma Lopes | Advogada de Imigração</span>
                </div>
                <div class="contact-info__item">
                    <span class="contact-info__label">Localização</span>
                    <span class="contact-info__value">Algarve, Portugal</span>
                </div>
                <div class="contact-info__item">
                    <span class="contact-info__label">Email</span>
                    <span class="contact-info__value"><a href="mailto:adv.palomalopes@gmail.com">adv.palomalopes@gmail.com</a></span>
                </div>
                <div class="contact-info__item">
                    <span class="contact-info__label">Telefone / WhatsApp</span>
                    <span class="contact-info__value"><a href="tel:+351963731313">+351 963 731 313</a></span>
                </div>
                <div class="contact-hours">
                    <strong>Horário de atendimento:</strong><br>
                    De segunda a sexta-feira, das 9h00 às 18h00 (horário de Portugal).
                </div>
            </div>
            <div class="reveal">
                <?php if ($feedback): ?>
                    <div class="form-alert form-alert--<?= $feedback['type'] === 'success' ? 'success' : 'error'; ?>">
                        <?= htmlspecialchars($feedback['message'], ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                <?php endif; ?>
                <form class="contact-form" action="#contacto" method="post" novalidate>
                    <div class="form-group">
                        <label for="name">Nome completo</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            required
                            value="<?= htmlspecialchars($formData['name'], ENT_QUOTES, 'UTF-8'); ?>"
                        >
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            required
                            value="<?= htmlspecialchars($formData['email'], ENT_QUOTES, 'UTF-8'); ?>"
                        >
                    </div>
                    <div class="form-group">
                        <label for="phone">Telefone</label>
                        <input
                            id="phone"
                            name="phone"
                            type="tel"
                            required
                            value="<?= htmlspecialchars($formData['phone'], ENT_QUOTES, 'UTF-8'); ?>"
                        >
                    </div>
                    <div class="form-group">
                        <label for="subject">Assunto</label>
                        <input
                            id="subject"
                            name="subject"
                            type="text"
                            required
                            value="<?= htmlspecialchars($formData['subject'], ENT_QUOTES, 'UTF-8'); ?>"
                        >
                    </div>
                    <div class="form-group">
                        <label for="message">Mensagem</label>
                        <textarea
                            id="message"
                            name="message"
                            required><?= htmlspecialchars($formData['message'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>
                    <button class="button button--primary" type="submit">Enviar mensagem</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
