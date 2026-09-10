<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\View;
use App\Models\Setting;

final class SettingsController
{
    /** @var array<string, array{title:string,keys:list<string>}> */
    public const SECTIONS = [
        'brand' => [
            'title' => 'Бренд и контакты',
            'keys' => [
                'public_url', 'site_name', 'site_name_latin', 'site_role', 'site_tagline',
                'hero_offer', 'hero_sub', 'portrait_alt', 'og_image',
                'phone', 'email', 'telegram', 'whatsapp', 'youtube', 'vk', 'city',
            ],
        ],
        'menu' => [
            'title' => 'Меню и кнопки',
            'keys' => [
                'nav_services', 'nav_packages', 'nav_gallery', 'nav_process', 'nav_faq', 'nav_contacts',
                'cta_lead', 'cta_services', 'discuss_label', 'featured_label', 'optimal_label', 'submit_label',
            ],
        ],
        'stats' => [
            'title' => 'Цифры и доверие',
            'keys' => [
                'experience_years', 'projects_count', 'response_hours',
                'stat_years_label', 'stat_projects_label', 'stat_response_label', 'stat_hours_suffix',
                'trust_block_title', 'work_format', 'response_sla', 'not_doing_title', 'not_doing',
                'trust_bullets_json',
            ],
        ],
        'sections' => [
            'title' => 'Заголовки секций',
            'keys' => [
                'services_title', 'services_sub', 'packages_title', 'packages_sub',
                'process_title', 'process_sub', 'gallery_title', 'gallery_sub', 'gallery_youtube_label',
                'stack_title', 'stack_sub', 'cases_title', 'cases_sub', 'case_link',
                'faq_title', 'faq_sub', 'lead_title', 'lead_sub', 'lead_message_ph',
            ],
        ],
        'process' => [
            'title' => 'Шаги заказа',
            'keys' => ['process_steps_json'],
        ],
        'stack' => [
            'title' => 'Преимущества',
            'keys' => ['stack_items'],
        ],
        'gallery' => [
            'title' => 'Видео',
            'keys' => ['gallery_json'],
        ],
        'faq' => [
            'title' => 'FAQ',
            'keys' => ['faq_json'],
        ],
        'legal' => [
            'title' => 'Юридическое',
            'keys' => ['privacy_text', 'offer_text'],
        ],
        'analytics' => [
            'title' => 'Аналитика',
            'keys' => ['yandex_metrika', 'google_analytics'],
        ],
    ];

    public function edit(): void
    {
        Auth::requireLogin();
        $section = $this->resolveSection((string)Request::input('section', 'brand'));
        $settings = Setting::all();

        View::render('admin/settings/edit', [
            'title' => 'Контент · ' . self::SECTIONS[$section]['title'],
            'section' => $section,
            'sections' => self::SECTIONS,
            'settings' => $settings,
            'data' => $this->viewData($settings),
            'flash_ok' => flash('ok'),
            'flash_error' => flash('error'),
            'nav_active' => 'settings',
        ], 'admin/layouts/main');
    }

    public function update(): void
    {
        Auth::requireLogin();
        Csrf::requireValid();
        $section = $this->resolveSection((string)Request::input('section', 'brand'));
        $keys = self::SECTIONS[$section]['keys'];
        $pairs = [];

        foreach ($keys as $key) {
            if (in_array($key, ['trust_bullets_json', 'process_steps_json', 'gallery_json', 'faq_json', 'stack_items'], true)) {
                continue;
            }
            $val = trim((string)Request::input($key, ''));
            if ($key === 'experience_years') {
                $val = preg_replace('/\++$/', '+', $val) ?? $val;
                if ($val !== '' && !str_ends_with($val, '+') && ctype_digit($val)) {
                    $val .= '+';
                }
            }
            if ($key === 'public_url' && $val !== '') {
                $val = rtrim($val, '/');
            }
            $pairs[$key] = $val;
        }

        if (in_array('trust_bullets_json', $keys, true)) {
            $pairs['trust_bullets_json'] = json_encode($this->stringList('trust_bullet'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        if (in_array('process_steps_json', $keys, true)) {
            $pairs['process_steps_json'] = json_encode($this->processStepsFromRequest(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        if (in_array('gallery_json', $keys, true)) {
            $pairs['gallery_json'] = json_encode($this->galleryFromRequest(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        if (in_array('faq_json', $keys, true)) {
            $pairs['faq_json'] = json_encode($this->faqFromRequest(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        if (in_array('stack_items', $keys, true)) {
            $items = $this->stringList('stack_item');
            $pairs['stack_items'] = implode("\n", $items);
        }

        if ($section === 'brand') {
            $uploaded = $this->storeUpload('avatar');
            if ($uploaded) {
                $pairs['avatar_path'] = $uploaded;
                if (($pairs['og_image'] ?? '') === '') {
                    $pairs['og_image'] = $uploaded;
                }
            }
        }

        Setting::setMany($pairs);
        flash('ok', 'Сохранено: ' . self::SECTIONS[$section]['title']);
        redirect('/admin/settings?section=' . urlencode($section));
    }

    private function resolveSection(string $section): string
    {
        return isset(self::SECTIONS[$section]) ? $section : 'brand';
    }

    /** @param array<string,string> $settings */
    private function viewData(array $settings): array
    {
        unset($settings);
        // Те же источники, что и на публичной главной (включая fallback на manifest/lang).
        return [
            'trust' => content_trust_bullets(),
            'steps' => content_process_steps(),
            'gallery' => gallery_items(),
            'faq' => content_faq_items(),
            'stack' => content_stack_items(),
        ];
    }

    /** @return list<string> */
    private function stringList(string $key): array
    {
        $raw = Request::input($key, []);
        if (!is_array($raw)) {
            return [];
        }
        $out = [];
        foreach ($raw as $v) {
            $v = trim((string)$v);
            if ($v !== '') {
                $out[] = $v;
            }
        }
        return $out;
    }

    /** @return list<array{t:string,d:string}> */
    private function processStepsFromRequest(): array
    {
        $titles = Request::input('step_t', []);
        $descs = Request::input('step_d', []);
        if (!is_array($titles)) {
            $titles = [];
        }
        if (!is_array($descs)) {
            $descs = [];
        }
        $out = [];
        $n = max(count($titles), count($descs));
        for ($i = 0; $i < $n; $i++) {
            $t = trim((string)($titles[$i] ?? ''));
            $d = trim((string)($descs[$i] ?? ''));
            if ($t === '' && $d === '') {
                continue;
            }
            $out[] = ['t' => $t, 'd' => $d];
        }
        return $out;
    }

    /** @return list<array<string, string>> */
    private function galleryFromRequest(): array
    {
        $files = Request::input('gallery_file', []);
        $titles = Request::input('gallery_title', []);
        $urls = Request::input('gallery_url', []);
        if (!is_array($files)) {
            $files = [];
        }
        if (!is_array($titles)) {
            $titles = [];
        }
        if (!is_array($urls)) {
            $urls = [];
        }
        $out = [];
        $n = max(count($files), count($titles), count($urls));
        for ($i = 0; $i < $n; $i++) {
            $file = trim((string)($files[$i] ?? ''));
            $title = trim((string)($titles[$i] ?? ''));
            $url = trim((string)($urls[$i] ?? ''));
            if ($file === '' && $title === '' && $url === '') {
                continue;
            }
            $item = ['title' => $title, 'url' => $url];
            if (str_starts_with($file, 'http://') || str_starts_with($file, 'https://') || str_starts_with($file, '/')) {
                $item['image'] = $file;
                $item['file'] = basename((string)(parse_url($file, PHP_URL_PATH) ?: $file));
            } else {
                $item['file'] = $file;
            }
            $id = $this->youtubeIdFromUrl($url);
            if ($id === '' && preg_match('/-([A-Za-z0-9_-]{6,})\.(jpe?g|png|webp)$/i', $item['file'], $m)) {
                $id = $m[1];
            }
            if ($id !== '') {
                $item['id'] = $id;
            }
            $out[] = $item;
        }
        return $out;
    }

    private function youtubeIdFromUrl(string $url): string
    {
        if ($url === '') {
            return '';
        }
        if (preg_match('~(?:youtube\.com/watch\?v=|youtu\.be/|youtube\.com/shorts/)([A-Za-z0-9_-]{6,})~', $url, $m)) {
            return $m[1];
        }
        return '';
    }

    /** @return list<array{q:string,a:string}> */
    private function faqFromRequest(): array
    {
        $qs = Request::input('faq_q', []);
        $as = Request::input('faq_a', []);
        if (!is_array($qs)) {
            $qs = [];
        }
        if (!is_array($as)) {
            $as = [];
        }
        $out = [];
        $n = max(count($qs), count($as));
        for ($i = 0; $i < $n; $i++) {
            $q = trim((string)($qs[$i] ?? ''));
            $a = trim((string)($as[$i] ?? ''));
            if ($q === '' && $a === '') {
                continue;
            }
            $out[] = ['q' => $q, 'a' => $a];
        }
        return $out;
    }

    private function storeUpload(string $field): ?string
    {
        if (empty($_FILES[$field]['tmp_name']) || !is_uploaded_file($_FILES[$field]['tmp_name'])) {
            return null;
        }
        $code = (int)($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE);
        if ($code !== UPLOAD_ERR_OK) {
            flash('error', 'Ошибка загрузки файла (код ' . $code . ').');
            redirect('/admin/settings?section=brand');
        }
        if (($_FILES[$field]['size'] ?? 0) > 3 * 1024 * 1024) {
            flash('error', 'Файл больше 3 МБ');
            redirect('/admin/settings?section=brand');
        }
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = (string)$finfo->file($_FILES[$field]['tmp_name']);
        $map = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];
        if (!isset($map[$mime])) {
            flash('error', 'Допустимы только JPG/PNG/WebP');
            redirect('/admin/settings?section=brand');
        }
        $name = bin2hex(random_bytes(12)) . '.' . $map[$mime];
        $dir = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads';
        if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
            flash('error', 'Не удалось создать каталог uploads');
            redirect('/admin/settings?section=brand');
        }
        if (!is_writable($dir)) {
            flash('error', 'Каталог uploads недоступен для записи');
            redirect('/admin/settings?section=brand');
        }
        $dest = $dir . DIRECTORY_SEPARATOR . $name;
        if (!@move_uploaded_file($_FILES[$field]['tmp_name'], $dest)) {
            flash('error', 'Не удалось сохранить файл');
            redirect('/admin/settings?section=brand');
        }
        @chmod($dest, 0644);
        return '/uploads/' . $name;
    }
}
