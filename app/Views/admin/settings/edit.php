<?php
/** @var array $settings */
/** @var array $sections */
/** @var string $section */
/** @var list<string> $trust */
/** @var list<array{t?:string,d?:string}> $steps */
/** @var list<array<string,mixed>> $gallery */
/** @var list<array{q?:string,a?:string}> $faq */
/** @var list<string> $stack */
/** @var list<string> $gallery_files */
use App\Core\Csrf;
$s = $settings;
$v = static fn(string $k, string $d = '') => e($s[$k] ?? $d);
$trust = $trust ?? [''];
$steps = $steps ?? [['t' => '', 'd' => '']];
$gallery = $gallery ?? [['file' => '', 'title' => '', 'url' => '']];
$faq = $faq ?? [['q' => '', 'a' => '']];
$stack = $stack ?? [''];
$gallery_files = $gallery_files ?? gallery_available_files();
if ($trust === []) {
    $trust = [''];
}
if ($steps === []) {
    $steps = [['t' => '', 'd' => '']];
}
if ($gallery === []) {
    $gallery = [['file' => '', 'title' => '', 'url' => '']];
}
if ($faq === []) {
    $faq = [['q' => '', 'a' => '']];
}
if ($stack === []) {
    $stack = [''];
}
?>
<div class="content-layout">
    <nav class="content-tabs" aria-label="Разделы контента">
        <?php foreach ($sections as $id => $meta): ?>
        <a class="content-tab<?= $section === $id ? ' is-active' : '' ?>" href="/admin/settings?section=<?= e($id) ?>"><?= e($meta['title']) ?></a>
        <?php endforeach; ?>
    </nav>

    <form method="post" action="/admin/settings" class="content-form" enctype="multipart/form-data" id="contentForm">
        <?= Csrf::field() ?>
        <input type="hidden" name="section" value="<?= e($section) ?>">

        <div class="panel content-panel">
            <?php if ($section === 'brand'): ?>
                <p class="panel-lead">Основная информация о компании и способы связи.</p>
                <div class="form-two">
                    <label>Публичный URL<input name="public_url" value="<?= $v('public_url', 'https://metall.1tlt.ru') ?>"></label>
                    <label>Город / локация<input name="city" value="<?= $v('city') ?>"></label>
                    <label>Название бренда<input name="site_name" value="<?= $v('site_name') ?>"></label>
                    <label>Название латиницей<input name="site_name_latin" value="<?= $v('site_name_latin') ?>"></label>
                    <label>Роль / подпись<input name="site_role" value="<?= $v('site_role') ?>"></label>
                    <label>Alt логотипа<input name="portrait_alt" value="<?= $v('portrait_alt', 'МеталлКомплект31') ?>"></label>
                </div>
                <label>Слоган<textarea name="site_tagline" rows="2"><?= $v('site_tagline') ?></textarea></label>
                <label>Заголовок на главной (H1)<textarea name="hero_offer" rows="2"><?= $v('hero_offer') ?></textarea></label>
                <label>Подзаголовок на главной<textarea name="hero_sub" rows="3"><?= $v('hero_sub') ?></textarea></label>
                <div class="form-two">
                    <label>Логотип (jpg/png/webp ≤3MB)<input type="file" name="avatar" accept="image/jpeg,image/png,image/webp"></label>
                    <label>OG-картинка (URL)<input name="og_image" value="<?= $v('og_image') ?>" placeholder="/assets/img/... или /uploads/..."></label>
                </div>
                <?php if (!empty($s['avatar_path'])): ?>
                <div class="logo-preview">
                    <img src="<?= e($s['avatar_path']) ?>" alt="" width="64" height="64">
                    <span class="muted">Сейчас: <?= e($s['avatar_path']) ?></span>
                </div>
                <?php endif; ?>
                <h3 class="form-subtitle">Контакты</h3>
                <div class="form-two">
                    <label>Телефон<input name="phone" value="<?= $v('phone') ?>"></label>
                    <label>Email<input name="email" value="<?= $v('email') ?>"></label>
                    <label>Telegram-канал<input name="telegram" value="<?= $v('telegram') ?>" placeholder="https://t.me/..."></label>
                    <label>WhatsApp<input name="whatsapp" value="<?= $v('whatsapp') ?>" placeholder="https://wa.me/..."></label>
                    <label>YouTube<input name="youtube" value="<?= $v('youtube') ?>"></label>
                    <label>VK<input name="vk" value="<?= $v('vk') ?>"></label>
                </div>

            <?php elseif ($section === 'menu'): ?>
                <p class="panel-lead">Подписи в шапке сайта и тексты кнопок.</p>
                <h3 class="form-subtitle">Пункты меню</h3>
                <div class="form-two">
                    <label>Продукция<input name="nav_services" value="<?= $v('nav_services', 'Продукция') ?>"></label>
                    <label>Комплекты<input name="nav_packages" value="<?= $v('nav_packages', 'Комплекты') ?>"></label>
                    <label>Видео<input name="nav_gallery" value="<?= $v('nav_gallery', 'Видео') ?>"></label>
                    <label>Как заказать<input name="nav_process" value="<?= $v('nav_process', 'Как заказать') ?>"></label>
                    <label>FAQ<input name="nav_faq" value="<?= $v('nav_faq', 'FAQ') ?>"></label>
                    <label>Контакты<input name="nav_contacts" value="<?= $v('nav_contacts', 'Контакты') ?>"></label>
                </div>
                <h3 class="form-subtitle">Кнопки</h3>
                <div class="form-two">
                    <label>Оставить заявку<input name="cta_lead" value="<?= $v('cta_lead', 'Оставить заявку') ?>"></label>
                    <label>Смотреть продукцию<input name="cta_services" value="<?= $v('cta_services', 'Смотреть продукцию') ?>"></label>
                    <label>Подобрать каркас<input name="discuss_label" value="<?= $v('discuss_label', 'Подобрать каркас') ?>"></label>
                    <label>Отправить форму<input name="submit_label" value="<?= $v('submit_label', 'Отправить заявку') ?>"></label>
                    <label>Бейдж «Хит»<input name="featured_label" value="<?= $v('featured_label', 'Хит') ?>"></label>
                    <label>Бейдж «Популярный»<input name="optimal_label" value="<?= $v('optimal_label', 'Популярный') ?>"></label>
                </div>

            <?php elseif ($section === 'stats'): ?>
                <p class="panel-lead">Блок цифр под баннером и карточки доверия.</p>
                <div class="form-two">
                    <label>Значение «лет»<input name="experience_years" value="<?= $v('experience_years') ?>"></label>
                    <label>Подпись<input name="stat_years_label" value="<?= $v('stat_years_label', 'лет на рынке') ?>"></label>
                    <label>Значение «проекты»<input name="projects_count" value="<?= $v('projects_count') ?>"></label>
                    <label>Подпись<input name="stat_projects_label" value="<?= $v('stat_projects_label', 'отгрузок и комплектов') ?>"></label>
                    <label>Часы ответа<input name="response_hours" value="<?= $v('response_hours') ?>"></label>
                    <label>Подпись<input name="stat_response_label" value="<?= $v('stat_response_label', 'типовая реакция') ?>"></label>
                    <label>Суффикс часов<input name="stat_hours_suffix" value="<?= $v('stat_hours_suffix', 'ч') ?>"></label>
                </div>
                <h3 class="form-subtitle">Буллеты в hero</h3>
                <div class="repeat-list" data-repeat="trust">
                    <?php foreach ($trust as $i => $bullet): ?>
                    <div class="repeat-row">
                        <input name="trust_bullet[]" value="<?= e(is_scalar($bullet) ? (string)$bullet : '') ?>" placeholder="Преимущество <?= (int)$i + 1 ?>">
                        <button type="button" class="btn-icon js-remove-row" title="Удалить" aria-label="Удалить">×</button>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="btn btn-ghost js-add-row" data-target="trust">+ Добавить пункт</button>
                <template id="tpl-trust">
                    <div class="repeat-row">
                        <input name="trust_bullet[]" value="" placeholder="Новый пункт">
                        <button type="button" class="btn-icon js-remove-row" title="Удалить" aria-label="Удалить">×</button>
                    </div>
                </template>
                <h3 class="form-subtitle">Формат работы</h3>
                <label>Заголовок блока<input name="trust_block_title" value="<?= $v('trust_block_title', 'Формат работы') ?>"></label>
                <label>Формат работы<textarea name="work_format" rows="2"><?= $v('work_format') ?></textarea></label>
                <label>SLA ответа<textarea name="response_sla" rows="2"><?= $v('response_sla') ?></textarea></label>
                <label>Заголовок «что не делаем»<input name="not_doing_title" value="<?= $v('not_doing_title', 'Что не делаем') ?>"></label>
                <label>Что не делаем<textarea name="not_doing" rows="3"><?= $v('not_doing') ?></textarea></label>

            <?php elseif ($section === 'sections'): ?>
                <p class="panel-lead">Заголовки и описания блоков на главной странице.</p>
                <?php
                $pairs = [
                    ['services_title', 'Продукция — заголовок', 'services_sub', 'Продукция — описание'],
                    ['packages_title', 'Комплекты — заголовок', 'packages_sub', 'Комплекты — описание'],
                    ['process_title', 'Как заказать — заголовок', 'process_sub', 'Как заказать — описание'],
                    ['gallery_title', 'Видео — заголовок', 'gallery_sub', 'Видео — описание'],
                    ['stack_title', 'Преимущества — заголовок', 'stack_sub', 'Преимущества — описание'],
                    ['cases_title', 'Объекты — заголовок', 'cases_sub', 'Объекты — описание'],
                    ['faq_title', 'FAQ — заголовок', 'faq_sub', 'FAQ — описание'],
                    ['lead_title', 'Заявка — заголовок', 'lead_sub', 'Заявка — описание'],
                ];
                foreach ($pairs as [$t, $tl, $d, $dl]): ?>
                <div class="form-two">
                    <label><?= e($tl) ?><input name="<?= e($t) ?>" value="<?= $v($t) ?>"></label>
                    <label><?= e($dl) ?><textarea name="<?= e($d) ?>" rows="2"><?= $v($d) ?></textarea></label>
                </div>
                <?php endforeach; ?>
                <div class="form-two">
                    <label>Кнопка YouTube<input name="gallery_youtube_label" value="<?= $v('gallery_youtube_label', 'Смотреть канал на YouTube') ?>"></label>
                    <label>Ссылка «подробнее» у объектов<input name="case_link" value="<?= $v('case_link', 'Подробнее') ?>"></label>
                    <label>Placeholder в форме заявки<input name="lead_message_ph" value="<?= $v('lead_message_ph', 'Ширина, длина, назначение объекта') ?>"></label>
                </div>

            <?php elseif ($section === 'process'): ?>
                <p class="panel-lead">Этапы блока «Как заказать». Порядок = порядок на сайте.</p>
                <div class="repeat-list" data-repeat="steps">
                    <?php foreach ($steps as $i => $step): ?>
                    <div class="repeat-card">
                        <div class="repeat-card-head">
                            <strong>Шаг <?= (int)$i + 1 ?></strong>
                            <button type="button" class="btn-icon js-remove-row" title="Удалить" aria-label="Удалить">×</button>
                        </div>
                        <label>Название<input name="step_t[]" value="<?= e((string)($step['t'] ?? '')) ?>"></label>
                        <label>Описание<textarea name="step_d[]" rows="2"><?= e((string)($step['d'] ?? '')) ?></textarea></label>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="btn btn-ghost js-add-row" data-target="steps">+ Добавить шаг</button>
                <template id="tpl-steps">
                    <div class="repeat-card">
                        <div class="repeat-card-head">
                            <strong>Новый шаг</strong>
                            <button type="button" class="btn-icon js-remove-row" title="Удалить" aria-label="Удалить">×</button>
                        </div>
                        <label>Название<input name="step_t[]" value=""></label>
                        <label>Описание<textarea name="step_d[]" rows="2"></textarea></label>
                    </div>
                </template>

            <?php elseif ($section === 'stack'): ?>
                <p class="panel-lead">Список преимуществ (чипы). Каждый пункт — отдельное поле.</p>
                <div class="repeat-list" data-repeat="stack">
                    <?php foreach ($stack as $item): ?>
                    <div class="repeat-row">
                        <input name="stack_item[]" value="<?= e((string)$item) ?>" placeholder="Преимущество">
                        <button type="button" class="btn-icon js-remove-row" title="Удалить" aria-label="Удалить">×</button>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="btn btn-ghost js-add-row" data-target="stack">+ Добавить</button>
                <template id="tpl-stack">
                    <div class="repeat-row">
                        <input name="stack_item[]" value="" placeholder="Преимущество">
                        <button type="button" class="btn-icon js-remove-row" title="Удалить" aria-label="Удалить">×</button>
                    </div>
                </template>

            <?php elseif ($section === 'gallery'): ?>
                <p class="panel-lead">Ролики блока «Видео с производства» на главной. Выберите превью из загруженных файлов или вставьте ссылку YouTube — заголовок можно править вручную.</p>
                <div class="repeat-list" data-repeat="gallery">
                    <?php foreach ($gallery as $i => $item):
                        $file = (string)($item['file'] ?? '');
                        if ($file === '' && !empty($item['image'])) {
                            $file = (string)$item['image'];
                        }
                        $fileName = $file;
                        if (str_contains($fileName, '/')) {
                            $fileName = basename(parse_url($fileName, PHP_URL_PATH) ?: $fileName);
                        }
                        $thumb = gallery_thumb_url(is_array($item) ? $item : []);
                    ?>
                    <div class="repeat-card gallery-card-admin">
                        <div class="repeat-card-head">
                            <strong>Видео <?= (int)$i + 1 ?></strong>
                            <button type="button" class="btn-icon js-remove-row" title="Удалить" aria-label="Удалить">×</button>
                        </div>
                        <div class="gallery-edit-row">
                            <img class="gallery-thumb js-gallery-thumb" src="<?= $thumb !== '' ? e($thumb) : '' ?>" alt="" width="120" height="68" loading="lazy"<?= $thumb === '' ? ' hidden' : '' ?>>
                            <div class="gallery-thumb gallery-thumb-empty js-gallery-thumb-empty"<?= $thumb !== '' ? ' hidden' : '' ?> aria-hidden="true"></div>
                            <div class="gallery-edit-fields">
                                <div class="form-two">
                                    <label>Заголовок<input name="gallery_title[]" value="<?= e((string)($item['title'] ?? '')) ?>"></label>
                                    <label>Ссылка на YouTube<input class="js-gallery-url" name="gallery_url[]" value="<?= e((string)($item['url'] ?? '')) ?>" placeholder="https://www.youtube.com/watch?v=..."></label>
                                </div>
                                <label>Превью
                                    <select class="js-gallery-file" name="gallery_file[]">
                                        <option value="">— выберите файл —</option>
                                        <?php foreach ($gallery_files as $gf): ?>
                                        <option value="<?= e($gf) ?>"<?= $fileName === $gf ? ' selected' : '' ?>><?= e($gf) ?></option>
                                        <?php endforeach; ?>
                                        <?php if ($fileName !== '' && !in_array($fileName, $gallery_files, true)): ?>
                                        <option value="<?= e($fileName) ?>" selected><?= e($fileName) ?> (текущий)</option>
                                        <?php endif; ?>
                                    </select>
                                </label>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="btn btn-ghost js-add-row" data-target="gallery">+ Добавить видео</button>
                <template id="tpl-gallery">
                    <div class="repeat-card gallery-card-admin">
                        <div class="repeat-card-head">
                            <strong>Новое видео</strong>
                            <button type="button" class="btn-icon js-remove-row" title="Удалить" aria-label="Удалить">×</button>
                        </div>
                        <div class="gallery-edit-row">
                            <img class="gallery-thumb js-gallery-thumb" src="" alt="" width="120" height="68" hidden>
                            <div class="gallery-thumb gallery-thumb-empty js-gallery-thumb-empty" aria-hidden="true"></div>
                            <div class="gallery-edit-fields">
                                <div class="form-two">
                                    <label>Заголовок<input name="gallery_title[]" value=""></label>
                                    <label>Ссылка на YouTube<input class="js-gallery-url" name="gallery_url[]" value="" placeholder="https://www.youtube.com/watch?v=..."></label>
                                </div>
                                <label>Превью
                                    <select class="js-gallery-file" name="gallery_file[]">
                                        <option value="">— выберите файл —</option>
                                        <?php foreach ($gallery_files as $gf): ?>
                                        <option value="<?= e($gf) ?>"><?= e($gf) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </label>
                            </div>
                        </div>
                    </div>
                </template>

            <?php elseif ($section === 'faq'): ?>
                <p class="panel-lead">Вопросы и ответы на главной.</p>
                <div class="repeat-list" data-repeat="faq">
                    <?php foreach ($faq as $i => $item): ?>
                    <div class="repeat-card">
                        <div class="repeat-card-head">
                            <strong>Вопрос <?= (int)$i + 1 ?></strong>
                            <button type="button" class="btn-icon js-remove-row" title="Удалить" aria-label="Удалить">×</button>
                        </div>
                        <label>Вопрос<input name="faq_q[]" value="<?= e((string)($item['q'] ?? '')) ?>"></label>
                        <label>Ответ<textarea name="faq_a[]" rows="3"><?= e((string)($item['a'] ?? '')) ?></textarea></label>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="btn btn-ghost js-add-row" data-target="faq">+ Добавить вопрос</button>
                <template id="tpl-faq">
                    <div class="repeat-card">
                        <div class="repeat-card-head">
                            <strong>Новый вопрос</strong>
                            <button type="button" class="btn-icon js-remove-row" title="Удалить" aria-label="Удалить">×</button>
                        </div>
                        <label>Вопрос<input name="faq_q[]" value=""></label>
                        <label>Ответ<textarea name="faq_a[]" rows="3"></textarea></label>
                    </div>
                </template>

            <?php elseif ($section === 'legal'): ?>
                <p class="panel-lead">Если поле пустое — на сайте показывается стандартный шаблон.</p>
                <label>Политика конфиденциальности<textarea name="privacy_text" rows="12" placeholder="Оставьте пустым для шаблона по умолчанию"><?= $v('privacy_text') ?></textarea></label>
                <label>Публичная оферта<textarea name="offer_text" rows="12" placeholder="Оставьте пустым для шаблона по умолчанию"><?= $v('offer_text') ?></textarea></label>

            <?php elseif ($section === 'analytics'): ?>
                <p class="panel-lead">Вставьте готовый код счётчика — он появится в &lt;head&gt; сайта.</p>
                <label>Яндекс.Метрика<textarea name="yandex_metrika" rows="8" placeholder="<script>...</script>"><?= $v('yandex_metrika') ?></textarea></label>
                <label>Google Analytics<textarea name="google_analytics" rows="8" placeholder="<script>...</script>"><?= $v('google_analytics') ?></textarea></label>
            <?php endif; ?>
        </div>

        <div class="form-actions sticky-actions">
            <button class="btn" type="submit">Сохранить раздел</button>
            <a class="btn btn-ghost" href="/" target="_blank" rel="noopener">Открыть сайт</a>
        </div>
    </form>
</div>
<script src="/assets/js/admin.js" defer></script>
