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

        $faq = json_decode(Setting::get('faq_json', '[]'), true);
        if (!is_array($faq)) {
            $faq = [];
        }

        View::render('home/index', [
            'seo' => $seo,
            'services' => Service::active(),
            'packages' => Package::active(),
            'portfolio' => Portfolio::active(),
            'gallery' => self::galleryItems(),
            'faq' => $faq,
            'settings' => Setting::all(),
            'usdRate' => \App\Core\Currency::usdRate(),
        ], 'layouts/main');
    }

    /** @return list<array{file:string,id:string,title:string,url:string}> */
    private static function galleryItems(): array
    {
        $path = dirname(__DIR__, 2) . '/public/assets/img/gallery/manifest.json';
        if (!is_file($path)) {
            return [];
        }
        $data = json_decode((string)file_get_contents($path), true);
        return is_array($data) ? $data : [];
    }
}
