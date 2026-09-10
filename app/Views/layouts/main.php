<?php
/** @var array $seo */
/** @var string $content */
use App\Core\Lang;

$settings = $settings ?? [];
$pageTitle = $seo['title'] ?? (brand_name() . ' — ' . setting('site_role'));
$pageDesc = $seo['description'] ?? setting('site_tagline');
$ogTitle = $seo['og_title'] ?? $pageTitle;
$ogDesc = $seo['og_description'] ?? $pageDesc;
$logoPath = setting('avatar_path', '/assets/img/channel-avatar.jpg');
$ogImage = media_url($seo['og_image'] ?? setting('og_image') ?: $logoPath);
if ($ogImage === '' || !str_contains($ogImage, 'http')) {
    $ogImage = app_url(ltrim($ogImage ?: 'assets/img/channel-avatar.jpg', '/'));
}
$logoSrc = (str_starts_with($logoPath, 'http') || str_starts_with($logoPath, '/'))
    ? $logoPath
    : media_url($logoPath);
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$canonical = $seo['canonical'] ?? Lang::absoluteUrl($path === '/' ? '/' : $path);
$robots = $seo['robots'] ?? 'index,follow';
?><!DOCTYPE html>
<html lang="ru" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#c4161c">
    <meta name="color-scheme" content="light dark">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="<?= e($pageDesc) ?>">
    <meta name="robots" content="<?= e($robots) ?>">
    <link rel="canonical" href="<?= e($canonical) ?>">
    <meta property="og:locale" content="ru_RU">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= e($ogTitle) ?>">
    <meta property="og:description" content="<?= e($ogDesc) ?>">
    <meta property="og:url" content="<?= e($canonical) ?>">
    <?php if ($ogImage): ?>
    <meta property="og:image" content="<?= e($ogImage) ?>">
    <meta name="twitter:image" content="<?= e($ogImage) ?>">
    <?php endif; ?>
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($ogTitle) ?>">
    <meta name="twitter:description" content="<?= e($ogDesc) ?>">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon.png">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <link rel="stylesheet" href="/assets/css/main.css">
    <?php if (!empty($settings['yandex_metrika'])): ?>
    <?= $settings['yandex_metrika'] ?>
    <?php endif; ?>
    <?php if (!empty($settings['google_analytics'])): ?>
    <?= $settings['google_analytics'] ?>
    <?php endif; ?>
</head>
<body>
<a class="skip-link" href="#main"><?= e(__('skip_to_content')) ?></a>
<header class="site-header" id="top">
    <div class="container header-inner">
        <a class="brand" href="<?= e(lang_url('/')) ?>">
            <span class="brand-mark" aria-hidden="true">
                <img src="<?= e($logoSrc) ?>" width="42" height="42" alt="">
            </span>
            <span class="brand-text">
                <strong><bdi dir="ltr"><?= e(brand_name()) ?></bdi></strong>
                <small class="brand-role"><?= e(setting('site_role')) ?></small>
            </span>
        </a>

        <nav class="nav" id="siteNav" aria-label="<?= e(__('nav_aria')) ?>">
            <a href="<?= e(lang_url('/#services')) ?>"><?= e(site_copy('nav_services')) ?></a>
            <a href="<?= e(lang_url('/#packages')) ?>"><?= e(site_copy('nav_packages')) ?></a>
            <a href="<?= e(lang_url('/#gallery')) ?>"><?= e(site_copy('nav_gallery')) ?></a>
            <a href="<?= e(lang_url('/#process')) ?>"><?= e(site_copy('nav_process')) ?></a>
            <a href="<?= e(lang_url('/#faq')) ?>"><?= e(site_copy('nav_faq')) ?></a>
            <a href="<?= e(lang_url('/#lead')) ?>"><?= e(site_copy('nav_contacts')) ?></a>
            <div class="nav-mobile-extra">
                <a class="btn btn-primary btn-sm nav-cta-mobile" href="<?= e(lang_url('/#lead')) ?>"><?= e(site_copy('cta_lead')) ?></a>
            </div>
        </nav>

        <div class="header-actions">
            <button type="button" class="theme-toggle" id="themeToggle" aria-label="<?= e(__('theme_toggle')) ?>">◐</button>
            <a class="btn btn-primary btn-sm header-cta-desktop" href="<?= e(lang_url('/#lead')) ?>"><?= e(site_copy('cta_lead')) ?></a>
            <button type="button" class="nav-toggle" id="navToggle" aria-label="<?= e(__('menu')) ?>" aria-expanded="false" aria-controls="siteNav">☰</button>
        </div>
    </div>
</header>

<main id="main">
    <?= $content ?>
</main>

<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <strong><bdi dir="ltr"><?= e(brand_name()) ?></bdi></strong>
            <p><?= e(setting('site_tagline')) ?></p>
        </div>
        <div>
            <p><a href="tel:<?= e(preg_replace('/[^\d+]/', '', setting('phone'))) ?>"><?= e(setting('phone')) ?></a></p>
            <p><a href="mailto:<?= e(setting('email')) ?>"><?= e(setting('email')) ?></a></p>
            <p><?= e(setting('city')) ?></p>
            <div class="footer-social">
                <?php if (setting('telegram')): ?><a href="<?= e(setting('telegram')) ?>" target="_blank" rel="noopener">TG канал</a><?php endif; ?>
                <?php if (setting('vk')): ?><a href="<?= e(setting('vk')) ?>" target="_blank" rel="noopener">VK</a><?php endif; ?>
                <?php if (setting('youtube')): ?><a href="<?= e(setting('youtube')) ?>" target="_blank" rel="noopener">YouTube</a><?php endif; ?>
                <?php if (setting('whatsapp')): ?><a href="<?= e(setting('whatsapp')) ?>" target="_blank" rel="noopener">WhatsApp</a><?php endif; ?>
            </div>
        </div>
        <div class="footer-links">
            <a href="<?= e(lang_url('/privacy')) ?>"><?= e(__('footer_privacy')) ?></a>
            <a href="<?= e(lang_url('/offer')) ?>"><?= e(__('footer_offer')) ?></a>
        </div>
    </div>
    <div class="container footer-copy">© <?= date('Y') ?> <bdi dir="ltr"><?= e(brand_name()) ?></bdi></div>
</footer>

<?php if (setting('telegram') || setting('whatsapp') || setting('vk') || setting('youtube')): ?>
<div class="float-messengers" aria-label="<?= e(__('messengers')) ?>">
    <?php if (setting('whatsapp')): ?>
    <a class="float-btn wa" href="<?= e(setting('whatsapp')) ?>" target="_blank" rel="noopener" aria-label="WhatsApp">
        <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true" focusable="false"><path fill="currentColor" d="M17.47 14.38c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.64.07-.3-.15-1.26-.46-2.4-1.48-.89-.79-1.48-1.76-1.65-2.06-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.61-.92-2.21-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.48s1.06 2.88 1.21 3.07c.15.2 2.1 3.2 5.08 4.49.71.3 1.26.49 1.69.63.71.23 1.36.2 1.87.12.57-.08 1.76-.72 2.01-1.41.25-.7.25-1.29.17-1.41-.07-.13-.27-.2-.57-.35m-5.42 7.4h-.01a9.87 9.87 0 0 1-5.03-1.38l-.36-.21-3.74.98 1-3.65-.24-.37a9.86 9.86 0 0 1-1.51-5.26C1.16 5.34 5.6.9 11.05.9a9.82 9.82 0 0 1 6.99 2.9 9.82 9.82 0 0 1 2.89 6.99c0 5.45-4.43 9.88-9.88 9.88m8.41-18.3A11.82 11.82 0 0 0 12.05 0C5.5 0 .16 5.34.16 11.89c0 2.1.55 4.14 1.59 5.95L.06 24l6.3-1.65a11.88 11.88 0 0 0 5.69 1.45h.01c6.55 0 11.89-5.34 11.89-11.89 0-3.17-1.23-6.16-3.48-8.41"/></svg>
    </a>
    <?php endif; ?>
    <?php if (setting('telegram')): ?>
    <a class="float-btn tg" href="<?= e(setting('telegram')) ?>" target="_blank" rel="noopener" aria-label="Telegram-канал">
        <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true" focusable="false"><path fill="currentColor" d="M11.94 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0h-.06zm4.96 7.22c.1 0 .32.02.47.14.16.1.22.25.17.33.02.09.04.3.02.47-.18 1.9-.96 6.5-1.36 8.63-.17.9-.5 1.2-.82 1.23-.7.07-1.23-.46-1.9-.9-1.06-.7-1.65-1.13-2.68-1.8-1.18-.78-.42-1.21.26-1.91.18-.18 3.25-2.98 3.31-3.23 0-.03.01-.15-.06-.21s-.17-.04-.25-.02c-.1.02-1.79 1.14-5.06 3.34-.48.33-.91.49-1.3.48-.43 0-1.25-.24-1.87-.44-.75-.25-1.35-.37-1.3-.79.03-.22.33-.44.89-.66 3.5-1.53 5.83-2.53 7-3.02 3.33-1.38 4.02-1.62 4.47-1.63z"/></svg>
    </a>
    <?php endif; ?>
    <?php if (setting('vk')): ?>
    <a class="float-btn vk" href="<?= e(setting('vk')) ?>" target="_blank" rel="noopener" aria-label="VK">
        <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true" focusable="false"><path fill="currentColor" d="M12.79 2h-1.58C5.6 2 4 3.6 4 9.21v5.58C4 20.4 5.6 22 11.21 22h1.58C18.4 22 20 20.4 20 14.79V9.21C20 3.6 18.4 2 12.79 2zm3.32 12.99h-1.35c-.51 0-.66-.4-1.57-1.31-.79-.75-1.14-.85-1.33-.85-.27 0-.35.08-.35.45v1.2c0 .32-.1.51-.95.51-1.41 0-2.98-.85-4.08-2.44C6.06 11.03 5.5 8.86 5.5 8.54c0-.18.07-.36.45-.36h1.34c.34 0 .47.16.6.52.65 1.87 1.74 3.51 2.19 3.51.17 0 .25-.08.25-.5V9.95c-.05-.9-.53-.98-.53-1.3 0-.16.13-.31.34-.31h2.11c.28 0 .39.16.39.5v2.71c0 .28.13.39.21.39.17 0 .3-.1.6-.4.94-1.05 1.6-2.66 1.6-2.66.09-.19.24-.36.58-.36h1.34c.4 0 .49.2.4.5-.16.75-1.68 2.9-1.68 2.9-.14.22-.19.32 0 .57.14.18.61.59.92.95.57.64 1 1.18 1.12 1.56.12.37-.06.56-.44.56z"/></svg>
    </a>
    <?php endif; ?>
    <?php if (setting('youtube')): ?>
    <a class="float-btn yt" href="<?= e(setting('youtube')) ?>" target="_blank" rel="noopener" aria-label="YouTube">
        <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true" focusable="false"><path fill="currentColor" d="M23.5 6.19A3.02 3.02 0 0 0 21.38 4.05C19.5 3.55 12 3.55 12 3.55s-7.5 0-9.38.5A3.02 3.02 0 0 0 .5 6.19 31.2 31.2 0 0 0 0 12c0 2.01.19 3.98.5 5.81a3.02 3.02 0 0 0 2.12 2.14c1.88.5 9.38.5 9.38.5s7.5 0 9.38-.5a3.02 3.02 0 0 0 2.12-2.14c.32-1.83.5-3.8.5-5.81s-.18-3.98-.5-5.81zM9.75 15.02V8.98L15.5 12l-5.75 3.02z"/></svg>
    </a>
    <?php endif; ?>
</div>
<?php endif; ?>

<div class="toast" id="toast" hidden role="status" aria-live="polite"></div>
<script>
window.PL_I18N = {
  leadOk: <?= json_encode(__('lead_ok'), JSON_UNESCAPED_UNICODE) ?>,
  leadErr: <?= json_encode(__('lead_err'), JSON_UNESCAPED_UNICODE) ?>,
  leadNetwork: <?= json_encode(__('lead_network'), JSON_UNESCAPED_UNICODE) ?>,
  leadUrl: <?= json_encode(lang_url('/lead'), JSON_UNESCAPED_UNICODE) ?>,
  submitting: <?= json_encode(__('submitting', 'Отправка…'), JSON_UNESCAPED_UNICODE) ?>
};
</script>
<script src="/assets/js/main.js" defer></script>
</body>
</html>
