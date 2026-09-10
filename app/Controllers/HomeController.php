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

        View::render('home/index', [
            'seo' => $seo,
            'services' => Service::active(),
            'packages' => Package::active(),
            'portfolio' => Portfolio::active(),
            'gallery' => gallery_items(),
            'faq' => content_faq_items(),
            'trustBullets' => content_trust_bullets(),
            'processSteps' => content_process_steps(),
            'stackItems' => content_stack_items(),
            'settings' => Setting::all(),
        ], 'layouts/main');
    }
}
