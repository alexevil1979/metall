<?php
/** @var string $content */
/** @var string $title */
$navActive = $nav_active ?? '';
$path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
if ($navActive === '') {
    if (str_starts_with($path, '/admin/leads')) $navActive = 'leads';
    elseif (str_starts_with($path, '/admin/services')) $navActive = 'services';
    elseif (str_starts_with($path, '/admin/packages')) $navActive = 'packages';
    elseif (str_starts_with($path, '/admin/portfolio')) $navActive = 'portfolio';
    elseif (str_starts_with($path, '/admin/settings')) $navActive = 'settings';
    elseif (str_starts_with($path, '/admin/seo')) $navActive = 'seo';
    elseif (str_starts_with($path, '/admin/notifications')) $navActive = 'notifications';
    elseif (str_starts_with($path, '/admin/password')) $navActive = 'password';
    elseif ($path === '/admin' || $path === '/admin/') $navActive = 'dashboard';
}
$link = static function (string $href, string $id, string $label) use ($navActive): string {
    $cls = $navActive === $id ? ' is-active' : '';
    return '<a class="' . trim($cls) . '" href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</a>';
};
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title><?= e($title ?? 'Админка') ?> — МеталлКомплект31</title>
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
<aside class="admin-nav">
    <div class="admin-brand">
        <img class="admin-brand-mark" src="/assets/img/channel-avatar.jpg" width="32" height="32" alt="">
        <span>МеталлКомплект31</span>
    </div>
    <div class="nav-group">Обзор</div>
    <?= $link('/admin', 'dashboard', 'Дашборд') ?>
    <?= $link('/admin/leads', 'leads', 'Заявки') ?>
    <div class="nav-group">Каталог</div>
    <?= $link('/admin/services', 'services', 'Продукция') ?>
    <?= $link('/admin/packages', 'packages', 'Комплекты') ?>
    <?= $link('/admin/portfolio', 'portfolio', 'Объекты') ?>
    <div class="nav-group">Сайт</div>
    <?= $link('/admin/settings', 'settings', 'Контент') ?>
    <?= $link('/admin/seo', 'seo', 'SEO') ?>
    <?= $link('/admin/notifications', 'notifications', 'Уведомления') ?>
    <?= $link('/admin/password', 'password', 'Пароль') ?>
    <a href="/" target="_blank" rel="noopener">Открыть сайт ↗</a>
    <form method="post" action="/admin/logout" class="logout-form">
        <?= \App\Core\Csrf::field() ?>
        <button type="submit">Выйти</button>
    </form>
</aside>
<div class="admin-main">
    <header class="admin-top"><h1><?= e($title ?? '') ?></h1></header>
    <?php if (!empty($flash_ok)): ?><div class="alert ok"><?= e($flash_ok) ?></div><?php endif; ?>
    <?php if (!empty($flash_error)): ?><div class="alert err"><?= e($flash_error) ?></div><?php endif; ?>
    <?= $content ?>
</div>
</body>
</html>
