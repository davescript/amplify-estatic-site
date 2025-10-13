<?php
declare(strict_types=1);

/**
 * Faz uma requisição HTTP simples retornando corpo e erro (se houver),
 * com suporte a ambientes sem a extensão cURL.
 *
 * @return array{content: string|null, error: string|null}
 */
function http_fetch(string $url, int $timeout = 10): array
{
    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        $content = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($content === false) {
            return ['content' => null, 'error' => $error !== '' ? $error : 'Erro desconhecido ao executar cURL.'];
        }

        return ['content' => $content, 'error' => null];
    }

    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'timeout' => $timeout,
            'ignore_errors' => true,
            'header' => "User-Agent: PHP\r\n",
        ],
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
        ],
    ]);

    $content = @file_get_contents($url, false, $context);
    if ($content === false) {
        $lastError = error_get_last();
        return ['content' => null, 'error' => $lastError['message'] ?? 'Falha ao carregar recurso remoto.'];
    }

    return ['content' => $content, 'error' => null];
}

/**
 * Limita o texto ao número desejado de caracteres com suporte opcional a mbstring.
 */
function truncate_text(string $text, int $limit, string $suffix = '…', string $encoding = 'UTF-8'): string
{
    $text = trim($text);
    $limit = max(0, $limit);

    if ($limit === 0 || $text === '') {
        return '';
    }

    if (function_exists('mb_strlen') && function_exists('mb_substr')) {
        if (mb_strlen($text, $encoding) <= $limit) {
            return $text;
        }

        return rtrim(mb_substr($text, 0, $limit, $encoding)) . $suffix;
    }

    if (strlen($text) <= $limit) {
        return $text;
    }

    return rtrim(substr($text, 0, $limit)) . $suffix;
}
