<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\DB;

final class SitemapController
{
    public function index(): void
    {
        header('Content-Type: application/xml; charset=utf-8');
        header('Cache-Control: public, max-age=3600');
        $base = rtrim(app_url(), '/');
        if ($base === '') {
            $base = 'https://metall.1tlt.ru';
        }
        $pages = [
            '/' => '1.0',
            '/privacy' => '0.3',
            '/offer' => '0.3',
        ];
        $lastmod = self::lastmod();

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($pages as $page => $priority) {
            $loc = $base . ($page === '/' ? '/' : $page);
            echo "  <url>\n";
            echo '    <loc>' . htmlspecialchars($loc, ENT_XML1) . "</loc>\n";
            echo '    <lastmod>' . $lastmod . "</lastmod>\n";
            echo "    <changefreq>weekly</changefreq>\n";
            echo '    <priority>' . $priority . "</priority>\n";
            echo "  </url>\n";
        }
        echo '</urlset>';
    }

    private static function lastmod(): string
    {
        $ts = [time()];
        try {
            $pdo = DB::conn();
            foreach (['portfolio', 'leads'] as $table) {
                $v = $pdo->query('SELECT MAX(created_at) FROM ' . $table)->fetchColumn();
                if ($v) {
                    $ts[] = strtotime((string)$v) ?: time();
                }
            }
        } catch (\Throwable $e) {
        }
        return date('Y-m-d', max($ts));
    }
}
