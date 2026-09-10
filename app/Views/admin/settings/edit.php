<?php
use App\Core\Csrf;
$s = $settings;
$pretty = static function (string $key, $fallback) use ($s): string {
    $raw = (string)($s[$key] ?? '');
    if ($raw === '' && $fallback !== null && $fallback !== '') {
        if (is_array($fallback)) {
            return (string)json_encode($fallback, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        }
        return (string)$fallback;
    }
    if ($raw !== '' && str_ends_with($key, '_json')) {
        $decoded = json_decode($raw, true);
        if (is_array($decoded)) {
            return (string)json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        }
    }
    return $raw;
};

$defaultTrust = [
    'Болтовое соединение — монтаж без сварки на объекте',
    'Сборно-разборный каркас, быстрое возведение',
    'Выгодная доставка по всей России',
];
$defaultSteps = [
    ['t' => 'Заявка', 'd' => 'Укажите ширину/длину объекта и назначение: гараж, ангар, склад, навес.'],
    ['t' => 'Подбор', 'd' => 'Подберём серию арок или каркас, фундамент и состав комплекта.'],
    ['t' => 'Отгрузка', 'd' => 'Комплектуем крепёж и отправляем доставку по вашему адресу.'],
    ['t' => 'Монтаж', 'd' => 'Собираете каркас на болтах — быстро и без сварки на площадке.'],
];
$defaultStack = "Болтовое соединение\nСборно-разборный каркас\nБыстрый монтаж\nЛюбой фундамент\nНагрузка до 200 кг/м²\nШаг арок 3 м\nКраб-система\nДоставка по РФ";
$defaultGalleryPath = dirname(__DIR__, 4) . '/public/assets/img/gallery/manifest.json';
$defaultGallery = is_file($defaultGalleryPath)
    ? (json_decode((string)file_get_contents($defaultGalleryPath), true) ?: [])
    : [];
?>
<form method="post" action="/admin/settings" class="form-grid form-wide" enctype="multipart/form-data">
    <?= Csrf::field() ?>

    <fieldset>
        <legend>Бренд и hero</legend>
        <label>Публичный URL (каноникал)<input name="public_url" value="<?= e($s['public_url'] ?? 'https://metall.1tlt.ru') ?>"></label>
        <label>Название бренда<input name="site_name" value="<?= e($s['site_name'] ?? '') ?>"></label>
        <label>Название латиницей<input name="site_name_latin" value="<?= e($s['site_name_latin'] ?? '') ?>"></label>
        <label>Роль / подпись<input name="site_role" value="<?= e($s['site_role'] ?? '') ?>"></label>
        <label>Слоган<textarea name="site_tagline" rows="2"><?= e($s['site_tagline'] ?? '') ?></textarea></label>
        <label>H1 / оффер<textarea name="hero_offer" rows="2"><?= e($s['hero_offer'] ?? '') ?></textarea></label>
        <label>Подзаголовок hero<textarea name="hero_sub" rows="3"><?= e($s['hero_sub'] ?? '') ?></textarea></label>
        <label>Логотип / фото (jpg/png/webp ≤3MB)<input type="file" name="avatar" accept="image/jpeg,image/png,image/webp"></label>
        <?php if (!empty($s['avatar_path'])): ?><p class="muted">Сейчас: <?= e($s['avatar_path']) ?></p><?php endif; ?>
        <label>OG image URL<input name="og_image" value="<?= e($s['og_image'] ?? '') ?>" placeholder="/assets/img/... или /uploads/..."></label>
        <label>Alt логотипа<input name="portrait_alt" value="<?= e($pretty('portrait_alt', 'МеталлКомплект31 — сборные металлоконструкции')) ?>"></label>
    </fieldset>

    <fieldset>
        <legend>Контакты и соцсети</legend>
        <label>Телефон<input name="phone" value="<?= e($s['phone'] ?? '') ?>"></label>
        <label>Email<input name="email" value="<?= e($s['email'] ?? '') ?>"></label>
        <label>Telegram-канал<input name="telegram" value="<?= e($s['telegram'] ?? '') ?>"></label>
        <label>WhatsApp URL<input name="whatsapp" value="<?= e($s['whatsapp'] ?? '') ?>"></label>
        <label>YouTube<input name="youtube" value="<?= e($s['youtube'] ?? '') ?>"></label>
        <label>VK<input name="vk" value="<?= e($s['vk'] ?? '') ?>"></label>
        <label>Город / локация<input name="city" value="<?= e($s['city'] ?? '') ?>"></label>
    </fieldset>

    <fieldset>
        <legend>Навигация и кнопки</legend>
        <div class="form-two">
            <label>Пункт: продукция<input name="nav_services" value="<?= e($pretty('nav_services', 'Продукция')) ?>"></label>
            <label>Пункт: комплекты<input name="nav_packages" value="<?= e($pretty('nav_packages', 'Комплекты')) ?>"></label>
            <label>Пункт: видео<input name="nav_gallery" value="<?= e($pretty('nav_gallery', 'Видео')) ?>"></label>
            <label>Пункт: как заказать<input name="nav_process" value="<?= e($pretty('nav_process', 'Как заказать')) ?>"></label>
            <label>Пункт: FAQ<input name="nav_faq" value="<?= e($pretty('nav_faq', 'FAQ')) ?>"></label>
            <label>Пункт: контакты<input name="nav_contacts" value="<?= e($pretty('nav_contacts', 'Контакты')) ?>"></label>
            <label>CTA заявка<input name="cta_lead" value="<?= e($pretty('cta_lead', 'Оставить заявку')) ?>"></label>
            <label>CTA продукция<input name="cta_services" value="<?= e($pretty('cta_services', 'Смотреть продукцию')) ?>"></label>
            <label>Кнопка «подобрать»<input name="discuss_label" value="<?= e($pretty('discuss_label', 'Подобрать каркас')) ?>"></label>
            <label>Бейдж «хит»<input name="featured_label" value="<?= e($pretty('featured_label', 'Хит')) ?>"></label>
            <label>Бейдж «популярный»<input name="optimal_label" value="<?= e($pretty('optimal_label', 'Популярный')) ?>"></label>
            <label>Кнопка отправки формы<input name="submit_label" value="<?= e($pretty('submit_label', 'Отправить заявку')) ?>"></label>
        </div>
    </fieldset>

    <fieldset>
        <legend>Статистика и доверие</legend>
        <div class="form-two">
            <label>Число «лет»<input name="experience_years" value="<?= e($s['experience_years'] ?? '') ?>"></label>
            <label>Подпись лет<input name="stat_years_label" value="<?= e($pretty('stat_years_label', 'лет на рынке')) ?>"></label>
            <label>Число проектов<input name="projects_count" value="<?= e($s['projects_count'] ?? '') ?>"></label>
            <label>Подпись проектов<input name="stat_projects_label" value="<?= e($pretty('stat_projects_label', 'отгрузок и комплектов')) ?>"></label>
            <label>Часы реакции<input name="response_hours" value="<?= e($s['response_hours'] ?? '') ?>"></label>
            <label>Подпись реакции<input name="stat_response_label" value="<?= e($pretty('stat_response_label', 'типовая реакция')) ?>"></label>
            <label>Суффикс часов<input name="stat_hours_suffix" value="<?= e($pretty('stat_hours_suffix', 'ч')) ?>"></label>
        </div>
        <label>Заголовок блока формата<input name="trust_block_title" value="<?= e($pretty('trust_block_title', 'Формат работы')) ?>"></label>
        <label>Формат работы<textarea name="work_format" rows="2"><?= e($s['work_format'] ?? '') ?></textarea></label>
        <label>SLA ответа<textarea name="response_sla" rows="2"><?= e($s['response_sla'] ?? '') ?></textarea></label>
        <label>Заголовок «что не делаем»<input name="not_doing_title" value="<?= e($pretty('not_doing_title', 'Что не делаем')) ?>"></label>
        <label>Что не делаем<textarea name="not_doing" rows="3"><?= e($s['not_doing'] ?? '') ?></textarea></label>
        <label>Буллеты hero (JSON-массив строк)<textarea name="trust_bullets_json" rows="6"><?= e($pretty('trust_bullets_json', $defaultTrust)) ?></textarea></label>
        <p class="muted">Пример: ["Пункт 1","Пункт 2","Пункт 3"]</p>
    </fieldset>

    <fieldset>
        <legend>Заголовки секций</legend>
        <div class="form-two">
            <label>Продукция — заголовок<input name="services_title" value="<?= e($pretty('services_title', 'Продукция')) ?>"></label>
            <label>Продукция — подзаголовок<textarea name="services_sub" rows="2"><?= e($pretty('services_sub', 'Арочные каркасы и универсальные рамы на краб-системе — цены за единицу комплекта.')) ?></textarea></label>
            <label>Комплекты — заголовок<input name="packages_title" value="<?= e($pretty('packages_title', 'Готовые комплекты')) ?>"></label>
            <label>Комплекты — подзаголовок<textarea name="packages_sub" rows="2"><?= e($pretty('packages_sub', 'Ориентиры по наборам для гаража, ангара и хозблока — точный расчёт по длине объекта.')) ?></textarea></label>
            <label>Как заказать — заголовок<input name="process_title" value="<?= e($pretty('process_title', 'Как заказать')) ?>"></label>
            <label>Как заказать — подзаголовок<textarea name="process_sub" rows="2"><?= e($pretty('process_sub', 'Короткий цикл от заявки до отгрузки.')) ?></textarea></label>
            <label>Видео — заголовок<input name="gallery_title" value="<?= e($pretty('gallery_title', 'Видео с производства')) ?>"></label>
            <label>Видео — подзаголовок<textarea name="gallery_sub" rows="2"><?= e($pretty('gallery_sub', 'Реальные каркасы, сборка на болтах и монтаж — с нашего YouTube-канала.')) ?></textarea></label>
            <label>Кнопка YouTube<input name="gallery_youtube_label" value="<?= e($pretty('gallery_youtube_label', 'Смотреть канал на YouTube')) ?>"></label>
            <label>Преимущества — заголовок<input name="stack_title" value="<?= e($pretty('stack_title', 'Преимущества')) ?>"></label>
            <label>Преимущества — подзаголовок<textarea name="stack_sub" rows="2"><?= e($pretty('stack_sub', 'То, что важно при выборе быстровозводимого каркаса.')) ?></textarea></label>
            <label>Объекты — заголовок<input name="cases_title" value="<?= e($pretty('cases_title', 'Объекты')) ?>"></label>
            <label>Объекты — подзаголовок<textarea name="cases_sub" rows="2"><?= e($pretty('cases_sub', 'Примеры применения комплектов.')) ?></textarea></label>
            <label>Ссылка кейса<input name="case_link" value="<?= e($pretty('case_link', 'Подробнее')) ?>"></label>
            <label>FAQ — заголовок<input name="faq_title" value="<?= e($pretty('faq_title', 'FAQ')) ?>"></label>
            <label>FAQ — подзаголовок<textarea name="faq_sub" rows="2"><?= e($pretty('faq_sub', 'Частые вопросы до заказа.')) ?></textarea></label>
            <label>Заявка — заголовок<input name="lead_title" value="<?= e($pretty('lead_title', 'Оставить заявку')) ?>"></label>
            <label>Заявка — подзаголовок<textarea name="lead_sub" rows="2"><?= e($pretty('lead_sub', 'Опишите объект — рассчитаем комплект, доставку и сроки.')) ?></textarea></label>
            <label>Placeholder сообщения<input name="lead_message_ph" value="<?= e($pretty('lead_message_ph', 'Ширина, длина, назначение объекта')) ?>"></label>
        </div>
    </fieldset>

    <fieldset>
        <legend>Шаги «Как заказать» (JSON)</legend>
        <label>Массив объектов {t, d}<textarea name="process_steps_json" rows="12"><?= e($pretty('process_steps_json', $defaultSteps)) ?></textarea></label>
        <p class="muted">[{ "t": "Заявка", "d": "Описание" }, ...]</p>
    </fieldset>

    <fieldset>
        <legend>Преимущества (по одному на строку)</legend>
        <label>Список<textarea name="stack_items" rows="10"><?= e($pretty('stack_items', $defaultStack)) ?></textarea></label>
    </fieldset>

    <fieldset>
        <legend>Видеогалерея (JSON)</legend>
        <label>Элементы [{file|image, title, url}]<textarea name="gallery_json" rows="16"><?= e($pretty('gallery_json', $defaultGallery)) ?></textarea></label>
        <p class="muted">file — имя файла в /assets/img/gallery/ (например 1-xxx.jpg). Можно указать image как полный путь /uploads/... или https://...</p>
    </fieldset>

    <fieldset>
        <legend>FAQ (JSON)</legend>
        <label>Массив [{q, a}]<textarea name="faq_json" rows="14"><?= e($pretty('faq_json', '[]')) ?></textarea></label>
    </fieldset>

    <fieldset>
        <legend>Юридические страницы</legend>
        <label>Политика конфиденциальности (текст)<textarea name="privacy_text" rows="10"><?= e($s['privacy_text'] ?? '') ?></textarea></label>
        <label>Публичная оферта (текст)<textarea name="offer_text" rows="10"><?= e($s['offer_text'] ?? '') ?></textarea></label>
        <p class="muted">Если пусто — показывается стандартный шаблон. Переносы строк сохраняются.</p>
    </fieldset>

    <fieldset>
        <legend>Аналитика</legend>
        <label>Яндекс.Метрика (код)<textarea name="yandex_metrika" rows="4"><?= e($s['yandex_metrika'] ?? '') ?></textarea></label>
        <label>Google Analytics (код)<textarea name="google_analytics" rows="4"><?= e($s['google_analytics'] ?? '') ?></textarea></label>
    </fieldset>

    <p class="muted">Продукция, комплекты и объекты редактируются в отдельных разделах меню.</p>
    <button class="btn" type="submit">Сохранить контент</button>
</form>
