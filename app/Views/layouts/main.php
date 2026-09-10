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
    <link rel="apple-touch-icon" href="<?= e($logoSrc) ?>">
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
    <?php if (setting('whatsapp')): ?><a class="float-btn wa" href="<?= e(setting('whatsapp')) ?>" target="_blank" rel="noopener" aria-label="WhatsApp">WA</a><?php endif; ?>
    <?php if (setting('telegram')): ?><a class="float-btn tg" href="<?= e(setting('telegram')) ?>" target="_blank" rel="noopener" aria-label="Telegram-канал">TG</a><?php endif; ?>
    <?php if (setting('vk')): ?><a class="float-btn vk" href="<?= e(setting('vk')) ?>" target="_blank" rel="noopener" aria-label="VK">VK</a><?php endif; ?>
    <?php if (setting('youtube')): ?><a class="float-btn yt" href="<?= e(setting('youtube')) ?>" target="_blank" rel="noopener" aria-label="YouTube">YT</a><?php endif; ?>
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
