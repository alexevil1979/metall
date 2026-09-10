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
    /** @var list<string> */
    private const KEYS = [
        'public_url', 'site_name', 'site_name_latin', 'site_role', 'site_tagline', 'hero_offer', 'hero_sub',
        'phone', 'email', 'telegram', 'whatsapp', 'youtube', 'vk', 'city',
        'experience_years', 'projects_count', 'response_hours',
        'stat_years_label', 'stat_projects_label', 'stat_response_label', 'stat_hours_suffix',
        'work_format', 'response_sla', 'not_doing', 'trust_block_title', 'not_doing_title',
        'trust_bullets_json',
        'cta_lead', 'cta_services', 'discuss_label', 'featured_label', 'optimal_label', 'portrait_alt',
        'nav_services', 'nav_packages', 'nav_gallery', 'nav_process', 'nav_faq', 'nav_contacts',
        'services_title', 'services_sub', 'packages_title', 'packages_sub',
        'process_title', 'process_sub', 'process_steps_json',
        'gallery_title', 'gallery_sub', 'gallery_youtube_label', 'gallery_json',
        'stack_title', 'stack_sub', 'stack_items',
        'cases_title', 'cases_sub', 'case_link',
        'faq_title', 'faq_sub', 'faq_json',
        'lead_title', 'lead_sub', 'lead_message_ph', 'submit_label',
        'yandex_metrika', 'google_analytics', 'og_image',
        'privacy_text', 'offer_text',
    ];

    /** @var list<string> */
    private const JSON_KEYS = [
        'trust_bullets_json', 'process_steps_json', 'gallery_json', 'faq_json',
    ];

    public function edit(): void
    {
        Auth::requireLogin();
        View::render('admin/settings/edit', [
            'title' => 'Контент сайта',
            'settings' => Setting::all(),
            'flash_ok' => flash('ok'),
            'flash_error' => flash('error'),
        ], 'admin/layouts/main');
    }

    public function update(): void
    {
        Auth::requireLogin();
        Csrf::requireValid();
        $pairs = [];
        foreach (self::KEYS as $key) {
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
            if (in_array($key, self::JSON_KEYS, true) && $val !== '') {
                $decoded = json_decode($val, true);
                if (!is_array($decoded)) {
                    flash('error', 'Некорректный JSON в поле «' . $key . '»');
                    redirect('/admin/settings');
                }
                $val = json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
            $pairs[$key] = $val;
        }

        $uploaded = $this->storeUpload('avatar');
        if ($uploaded) {
            $pairs['avatar_path'] = $uploaded;
            if (($pairs['og_image'] ?? '') === '') {
                $pairs['og_image'] = $uploaded;
            }
        }

        Setting::setMany($pairs);
        flash('ok', 'Настройки сохранены');
        redirect('/admin/settings');
    }

    private function storeUpload(string $field): ?string
    {
        if (empty($_FILES[$field]['tmp_name']) || !is_uploaded_file($_FILES[$field]['tmp_name'])) {
            return null;
        }
        $code = (int)($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE);
        if ($code !== UPLOAD_ERR_OK) {
            flash('error', 'Ошибка загрузки файла (код ' . $code . '). Проверьте upload_max_filesize в PHP.');
            redirect('/admin/settings');
        }
        if (($_FILES[$field]['size'] ?? 0) > 3 * 1024 * 1024) {
            flash('error', 'Файл больше 3 МБ');
            redirect('/admin/settings');
        }
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = (string)$finfo->file($_FILES[$field]['tmp_name']);
        $map = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];
        if (!isset($map[$mime])) {
            flash('error', 'Допустимы только JPG/PNG/WebP (сейчас: ' . $mime . ')');
            redirect('/admin/settings');
        }
        $name = bin2hex(random_bytes(12)) . '.' . $map[$mime];
        $dir = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads';
        if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
            flash('error', 'Нет каталога uploads и не удалось создать: ' . $dir);
            redirect('/admin/settings');
        }
        if (!is_writable($dir)) {
            flash('error', 'Каталог uploads недоступен для записи. Выполните: chown -R www-data:www-data public/uploads && chmod 775 public/uploads');
            redirect('/admin/settings');
        }
        $dest = $dir . DIRECTORY_SEPARATOR . $name;
        if (!@move_uploaded_file($_FILES[$field]['tmp_name'], $dest)) {
            flash('error', 'Не удалось сохранить файл в ' . $dir . ' (права PHP-FPM)');
            redirect('/admin/settings');
        }
        @chmod($dest, 0644);
        return '/uploads/' . $name;
    }
}
