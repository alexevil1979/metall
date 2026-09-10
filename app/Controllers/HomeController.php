<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Models\Package;
use App\Models\PageSeo;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Setting;

final class HomeController
{
    public function index(): void
    {
        $seo = PageSeo::get('home') ?? [];

        $faq = setting_json('faq_json', []);
        $trustBullets = setting_json('trust_bullets_json', [
            __('trust_1'),
            __('trust_2'),
            __('trust_3'),
        ]);
        $processSteps = setting_json('process_steps_json', [
            ['t' => __('step1_t'), 'd' => __('step1_d')],
            ['t' => __('step2_t'), 'd' => __('step2_d')],
            ['t' => __('step3_t'), 'd' => __('step3_d')],
            ['t' => __('step4_t'), 'd' => __('step4_d')],
        ]);
        $stackItems = setting_lines('stack_items', [
            'Болтовое соединение',
            'Сборно-разборный каркас',
            'Быстрый монтаж',
            'Любой фундамент',
            'Нагрузка до 200 кг/м²',
            'Шаг арок 3 м',
            'Краб-система',
            'Доставка по РФ',
        ]);

        View::render('home/index', [
            'seo' => $seo,
            'services' => Service::active(),
            'packages' => Package::active(),
            'portfolio' => Portfolio::active(),
            'gallery' => self::galleryItems(),
            'faq' => $faq,
            'trustBullets' => $trustBullets,
            'processSteps' => $processSteps,
            'stackItems' => $stackItems,
            'settings' => Setting::all(),
        ], 'layouts/main');
    }

    /** @return list<array<string, mixed>> */
    private static function galleryItems(): array
    {
        $fromDb = setting_json('gallery_json', []);
        if ($fromDb !== []) {
            return $fromDb;
        }
        $path = dirname(__DIR__, 2) . '/public/assets/img/gallery/manifest.json';
        if (!is_file($path)) {
            return [];
        }
        $data = json_decode((string)file_get_contents($path), true);
        return is_array($data) ? $data : [];
    }
}
