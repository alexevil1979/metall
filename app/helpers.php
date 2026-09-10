<?php

declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function __(string $key, ?string $default = null): string
{
    return \App\Core\Lang::get($key, $default);
}

function lang_url(string $path = '/'): string
{
    return \App\Core\Lang::url($path);
}

function current_lang(): string
{
    return \App\Core\Lang::code();
}

function money(?float $amount): string
{
    if ($amount === null) {
        return '';
    }
    return number_format($amount, 0, '.', ' ') . ' ₽';
}

function money_offer(?float $amount): array
{
    $rub = money($amount);
    return ['rub' => $rub, 'usd' => ''];
}

function money_dual(?float $amount): string
{
    return money($amount);
}

function period_label(string $period): string
{
    return match ($period) {
        'monthly' => __('period_monthly'),
        'custom' => __('period_custom'),
        default => __('period_one_time'),
    };
}

function brand_name(): string
{
    return setting('site_name', 'МеталлКомплект31');
}

function setting(string $key, string $default = ''): string
{
    static $cache = null;
    if ($cache === null) {
        $cache = \App\Models\Setting::all();
    }

    return isset($cache[$key]) && $cache[$key] !== null && $cache[$key] !== ''
        ? (string)$cache[$key]
        : $default;
}

/** Текст лендинга из settings с fallback на lang/ru.php */
function site_copy(string $key, ?string $langKey = null): string
{
    $v = setting($key, '');
    if ($v !== '') {
        return $v;
    }
    return __($langKey ?? $key);
}

/** @return list<mixed> */
function setting_json(string $key, array $default = []): array
{
    $raw = setting($key, '');
    if ($raw === '') {
        return $default;
    }
    $data = json_decode($raw, true);
    return is_array($data) ? $data : $default;
}

/** @return list<string> */
function setting_lines(string $key, array $default = []): array
{
    $raw = setting($key, '');
    if ($raw === '') {
        return $default;
    }
    $lines = preg_split('/\R/u', $raw) ?: [];
    $out = [];
    foreach ($lines as $line) {
        $line = trim((string)$line);
        if ($line !== '') {
            $out[] = $line;
        }
    }
    return $out !== [] ? $out : $default;
}

/** @return list<array<string, mixed>> */
function gallery_manifest(): array
{
    $path = dirname(__DIR__) . '/public/assets/img/gallery/manifest.json';
    if (!is_file($path)) {
        return [];
    }
    $data = json_decode((string)file_get_contents($path), true);
    return is_array($data) ? array_values($data) : [];
}

/**
 * Видео для главной и админки: settings.gallery_json, иначе manifest.json.
 * @return list<array<string, mixed>>
 */
function gallery_items(): array
{
    $fromDb = setting_json('gallery_json', []);
    if ($fromDb !== []) {
        return array_values($fromDb);
    }
    return gallery_manifest();
}

/** URL превью ролика из file / image */
function gallery_thumb_url(array $item): string
{
    $image = trim((string)($item['image'] ?? ''));
    if ($image !== '') {
        if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://') || str_starts_with($image, '/')) {
            return $image;
        }
        return '/assets/img/gallery/' . ltrim($image, '/');
    }
    $file = trim((string)($item['file'] ?? ''));
    if ($file === '') {
        return '';
    }
    if (str_starts_with($file, 'http://') || str_starts_with($file, 'https://') || str_starts_with($file, '/')) {
        return $file;
    }
    return '/assets/img/gallery/' . ltrim($file, '/');
}

/** @return list<string> */
function content_trust_bullets(): array
{
    $items = setting_json('trust_bullets_json', []);
    if ($items !== []) {
        return array_values(array_map('strval', $items));
    }
    return [
        __('trust_1'),
        __('trust_2'),
        __('trust_3'),
    ];
}

/** @return list<array{t:string,d:string}> */
function content_process_steps(): array
{
    $items = setting_json('process_steps_json', []);
    if ($items !== []) {
        $out = [];
        foreach ($items as $row) {
            if (!is_array($row)) {
                continue;
            }
            $out[] = [
                't' => (string)($row['t'] ?? ''),
                'd' => (string)($row['d'] ?? ''),
            ];
        }
        return $out !== [] ? $out : content_process_steps_defaults();
    }
    return content_process_steps_defaults();
}

/** @return list<array{t:string,d:string}> */
function content_process_steps_defaults(): array
{
    return [
        ['t' => __('step1_t'), 'd' => __('step1_d')],
        ['t' => __('step2_t'), 'd' => __('step2_d')],
        ['t' => __('step3_t'), 'd' => __('step3_d')],
        ['t' => __('step4_t'), 'd' => __('step4_d')],
    ];
}

/** @return list<string> */
function content_stack_items(): array
{
    return setting_lines('stack_items', [
        'Болтовое соединение',
        'Сборно-разборный каркас',
        'Быстрый монтаж',
        'Любой фундамент',
        'Нагрузка до 200 кг/м²',
        'Шаг арок 3 м',
        'Краб-система',
        'Доставка по РФ',
    ]);
}

/** @return list<array{q:string,a:string}> */
function content_faq_items(): array
{
    $items = setting_json('faq_json', []);
    if ($items !== []) {
        $out = [];
        foreach ($items as $row) {
            if (!is_array($row)) {
                continue;
            }
            $out[] = [
                'q' => (string)($row['q'] ?? ''),
                'a' => (string)($row['a'] ?? ''),
            ];
        }
        return $out !== [] ? $out : content_faq_defaults();
    }
    return content_faq_defaults();
}

/** @return list<array{q:string,a:string}> */
function content_faq_defaults(): array
{
    return [
        ['q' => 'Что входит в комплект арки?', 'a' => 'Каркас арки и крепёж: болты, шайбы, гайки. Обшивку и фундамент подбираете отдельно или обсуждаем комплектацию.'],
        ['q' => 'Какой фундамент нужен?', 'a' => 'Можно ставить на обвязку, спецблоки, плиту, заливные или винтовые сваи. Капитальный фундамент для стандартных арок не обязателен.'],
        ['q' => 'С какой нагрузкой рассчитаны арки?', 'a' => 'В зависимости от серии — ориентировочно 180–200 кг/м². Точные параметры уточняем по выбранной ширине.'],
        ['q' => 'Какой шаг установки арок?', 'a' => 'Типовой шаг — 3 метра. Длину объекта набираете нужным количеством секций.'],
        ['q' => 'Доставляете по России?', 'a' => 'Да, выгодная доставка по РФ. Стоимость и сроки считаем по адресу и объёму заказа.'],
        ['q' => 'Сложно ли собрать каркас?', 'a' => 'Конструкция сборно-разборная на болтовом соединении — быстрое и простое возведение без сварки на объекте.'],
    ];
}

function service_field(array $service, string $field): string
{
    return (string)($service[$field] ?? '');
}

function package_field(array $package, string $field): string
{
    return (string)($package[$field] ?? '');
}

/** @return list<string> */
function package_features(array $package): array
{
    return \App\Models\Package::featuresList($package['features'] ?? null);
}

function app_url(string $path = ''): string
{
    $canonical = 'https://metall.1tlt.ru';
    $base = '';
    try {
        $pub = trim((string)\App\Models\Setting::get('public_url', ''));
        if ($pub !== '') {
            $base = rtrim($pub, '/');
        }
    } catch (\Throwable $e) {
    }
    if ($base === '') {
        $base = rtrim((string)\App\Core\Config::get('url', ''), '/');
    }
    if ($base === '' || preg_match('#example\.com#i', $base)) {
        $base = $canonical;
    }
    $base = rtrim($base, '/');
    if ($path === '' || $path === '/') {
        return $base . '/';
    }
    return $base . '/' . ltrim($path, '/');
}

function media_url(?string $path): string
{
    $path = trim((string)$path);
    if ($path === '') {
        return '';
    }
    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        return $path;
    }
    return app_url(ltrim($path, '/'));
}

function flash(string $key, ?string $value = null): ?string
{
    if ($value !== null) {
        $_SESSION['_flash'][$key] = $value;
        return null;
    }
    $v = $_SESSION['_flash'][$key] ?? null;
    unset($_SESSION['_flash'][$key]);
    return is_string($v) ? $v : null;
}

function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

function slugify(string $text): string
{
    $map = [
        'а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'zh','з'=>'z','и'=>'i','й'=>'y',
        'к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f',
        'х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'sch','ъ'=>'','ы'=>'y','ь'=>'','э'=>'e','ю'=>'yu','я'=>'ya',
    ];
    $text = mb_strtolower(trim($text));
    $text = strtr($text, $map);
    $text = preg_replace('~[^a-z0-9]+~', '-', $text) ?? '';
    return trim($text, '-') ?: 'item';
}
