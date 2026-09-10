<?php
/** @var array $seo */
/** @var array $services */
/** @var array $packages */
/** @var array $portfolio */
/** @var array $gallery */
/** @var array $faq */
/** @var array $trustBullets */
/** @var array $processSteps */
/** @var array $stackItems */
/** @var array $settings */
use App\Core\Csrf;

$h1 = $seo['h1'] ?? setting('hero_offer');
$name = brand_name();
$role = setting('site_role');
$homeUrl = app_url(ltrim(lang_url('/'), '/'));
$avatar = trim((string)setting('avatar_path'));
if ($avatar === '') {
    $avatar = '/assets/img/channel-avatar.jpg';
}
$gallery = $gallery ?? [];
$trustBullets = $trustBullets ?? [__('trust_1'), __('trust_2'), __('trust_3')];
$processSteps = $processSteps ?? [];
$stackItems = $stackItems ?? [];
$exp = setting('experience_years', '10+');
if ($exp !== '' && !str_contains($exp, '+') && preg_match('/^\d+$/', $exp)) {
    $exp .= '+';
}

$logoUrl = app_url(ltrim(str_starts_with($avatar, '/') ? ltrim($avatar, '/') : $avatar, '/'));
if (str_starts_with($avatar, 'http')) {
    $logoUrl = $avatar;
} elseif (str_starts_with($avatar, '/')) {
    $logoUrl = app_url(ltrim($avatar, '/'));
}

$jsonLdOrg = [
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    '@id' => $homeUrl . '#organization',
    'name' => $name,
    'url' => $homeUrl,
    'email' => setting('email'),
    'telephone' => setting('phone'),
    'description' => setting('site_tagline'),
    'logo' => $logoUrl,
    'image' => $logoUrl,
    'sameAs' => array_values(array_filter([
        setting('youtube'),
        setting('vk'),
        setting('telegram'),
    ])),
    'address' => ['@type' => 'PostalAddress', 'addressLocality' => setting('city'), 'addressCountry' => 'RU'],
];
$offersClean = [];
foreach ($services as $s) {
    $o = [
        '@type' => 'Offer',
        'name' => service_field($s, 'title'),
        'description' => service_field($s, 'short_text'),
        'priceCurrency' => 'RUB',
        'url' => app_url(ltrim(lang_url('/#services'), '/')),
    ];
    if ($s['price_from'] !== null) {
        $o['price'] = (string)$s['price_from'];
    }
    $offersClean[] = $o;
}
$jsonLdService = [
    '@context' => 'https://schema.org',
    '@type' => 'LocalBusiness',
    '@id' => $homeUrl . '#business',
    'name' => $name . ' — ' . $role,
    'description' => setting('site_tagline'),
    'url' => $homeUrl,
    'areaServed' => 'RU',
    'priceRange' => '₽',
    'telephone' => setting('phone'),
];
$jsonLdCatalog = [
    '@context' => 'https://schema.org',
    '@type' => 'OfferCatalog',
    '@id' => $homeUrl . '#offers',
    'name' => __('services_title'),
    'itemListElement' => $offersClean,
];
$faqLd = ['@context' => 'https://schema.org', '@type' => 'FAQPage', '@id' => $homeUrl . '#faq', 'mainEntity' => []];
foreach ($faq as $item) {
    $faqLd['mainEntity'][] = [
        '@type' => 'Question',
        'name' => $item['q'] ?? '',
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['a'] ?? ''],
    ];
}
?>
<script type="application/ld+json"><?= json_encode($jsonLdOrg, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?></script>
<script type="application/ld+json"><?= json_encode($jsonLdService, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?></script>
<script type="application/ld+json"><?= json_encode($jsonLdCatalog, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?></script>
<?php if ($faq): ?><script type="application/ld+json"><?= json_encode($faqLd, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?></script><?php endif; ?>

<section class="hero" aria-labelledby="hero-title">
    <div class="hero-bg" aria-hidden="true"></div>
    <div class="hero-scrim" aria-hidden="true"></div>
    <div class="container hero-grid">
        <div class="hero-copy reveal">
            <p class="eyebrow"><bdi dir="ltr"><?= e($name) ?></bdi> · <?= e($role) ?></p>
            <h1 id="hero-title"><?= e($h1) ?></h1>
            <p class="lead"><?= e(setting('hero_sub')) ?></p>
            <ul class="trust-bullets">
                <?php foreach ($trustBullets as $bullet): ?>
                <li><?= e(is_array($bullet) ? (string)($bullet['t'] ?? $bullet[0] ?? '') : (string)$bullet) ?></li>
                <?php endforeach; ?>
            </ul>
            <div class="hero-cta">
                <a class="btn btn-primary" href="#lead"><?= e(site_copy('cta_lead')) ?></a>
                <a class="btn btn-ghost on-dark" href="#services"><?= e(site_copy('cta_services')) ?></a>
            </div>
        </div>
        <div class="hero-visual reveal">
            <div class="portrait-card">
                <img class="portrait portrait-photo" src="<?= e(str_starts_with($avatar, 'http') || str_starts_with($avatar, '/') ? $avatar : media_url($avatar)) ?>" width="320" height="320" alt="<?= e(site_copy('portrait_alt')) ?>" fetchpriority="high" decoding="async">
                <div class="portrait-meta">
                    <strong><bdi dir="ltr"><?= e($name) ?></bdi></strong>
                    <span><?= e(setting('city')) ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="stats" id="trust" aria-label="<?= e(site_copy('trust_block_title')) ?>">
    <div class="container stats-grid">
        <div class="stat reveal"><strong><?= e($exp) ?></strong><span><?= e(site_copy('stat_years_label', 'stat_years')) ?></span></div>
        <div class="stat reveal"><strong><?= e(setting('projects_count', '100+')) ?></strong><span><?= e(site_copy('stat_projects_label', 'stat_projects')) ?></span></div>
        <div class="stat reveal"><strong><?= e(setting('response_hours', '2')) ?> <?= e(site_copy('stat_hours_suffix')) ?></strong><span><?= e(site_copy('stat_response_label', 'stat_response')) ?></span></div>
    </div>
</section>

<?php if (setting('work_format') || setting('response_sla') || setting('not_doing')): ?>
<section class="section trust-block" id="work-format" aria-labelledby="work-format-title">
    <div class="container">
        <header class="section-head reveal">
            <h2 id="work-format-title"><?= e(site_copy('trust_block_title')) ?></h2>
        </header>
        <div class="trust-grid">
            <?php if (setting('work_format')): ?>
            <div class="trust-card reveal">
                <h3><?= e(site_copy('trust_block_title')) ?></h3>
                <p><?= e(setting('work_format')) ?></p>
            </div>
            <?php endif; ?>
            <?php if (setting('response_sla')): ?>
            <div class="trust-card reveal">
                <h3><?= e(site_copy('stat_response_label', 'stat_response')) ?></h3>
                <p><?= e(setting('response_sla')) ?></p>
            </div>
            <?php endif; ?>
            <?php if (setting('not_doing')): ?>
            <div class="trust-card reveal">
                <h3><?= e(site_copy('not_doing_title')) ?></h3>
                <p><?= e(setting('not_doing')) ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section" id="services" aria-labelledby="services-title">
    <div class="container">
        <header class="section-head reveal">
            <h2 id="services-title"><?= e(site_copy('services_title')) ?></h2>
            <p><?= e(site_copy('services_sub')) ?></p>
        </header>
        <div class="services-grid">
            <?php foreach ($services as $service):
                $price = money($service['price_from'] !== null ? (float)$service['price_from'] : null);
            ?>
            <article class="service-card<?= !empty($service['is_featured']) ? ' is-featured' : '' ?> reveal">
                <div class="service-top">
                    <span class="service-icon" aria-hidden="true"><?= e(mb_substr((string)$service['icon'], 0, 1)) ?></span>
                    <?php if (!empty($service['is_featured'])): ?><span class="badge"><?= e(site_copy('featured_label', 'featured')) ?></span><?php endif; ?>
                </div>
                <h3><?= e(service_field($service, 'title')) ?></h3>
                <p><?= e(service_field($service, 'short_text')) ?></p>
                <div class="service-price">
                    <?php if ($price !== ''): ?>
                        <strong><?= e(__('price_from')) ?> <?= e($price) ?></strong>
                    <?php else: ?>
                        <strong><?= e(__('price_on_request')) ?></strong>
                    <?php endif; ?>
                    <span class="price-note"><?= e($service['price_note'] ?: period_label($service['period'])) ?></span>
                </div>
                <button type="button" class="btn btn-secondary btn-block js-order"
                        data-service="<?= (int)$service['id'] ?>" data-package="">
                    <?= e($service['cta_label'] ?: site_copy('discuss_label', 'discuss')) ?>
                </button>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section-alt" id="packages" aria-labelledby="packages-title">
    <div class="container">
        <header class="section-head reveal">
            <h2 id="packages-title"><?= e(site_copy('packages_title')) ?></h2>
            <p><?= e(site_copy('packages_sub')) ?></p>
        </header>
        <div class="packages-grid">
            <?php foreach ($packages as $pkg):
                $features = package_features($pkg);
                $price = money($pkg['price'] !== null ? (float)$pkg['price'] : null);
            ?>
            <article class="package-card<?= !empty($pkg['is_featured']) ? ' is-featured' : '' ?> reveal">
                <?php if (!empty($pkg['is_featured'])): ?><div class="package-ribbon"><?= e(site_copy('optimal_label', 'optimal')) ?></div><?php endif; ?>
                <h3><?= e(package_field($pkg, 'title')) ?></h3>
                <p><?= e(package_field($pkg, 'description')) ?></p>
                <div class="package-price">
                    <?php if ($price !== ''): ?>
                        <strong><?= e($price) ?></strong>
                    <?php endif; ?>
                    <span class="price-note"><?= e($pkg['price_note'] ?? '') ?></span>
                </div>
                <ul>
                    <?php foreach ($features as $f): ?><li><?= e((string)$f) ?></li><?php endforeach; ?>
                </ul>
                <button type="button" class="btn <?= !empty($pkg['is_featured']) ? 'btn-primary' : 'btn-secondary' ?> btn-block js-order"
                        data-service="" data-package="<?= (int)$pkg['id'] ?>">
                    <?= e($pkg['cta_label'] ?: site_copy('cta_lead')) ?>
                </button>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section" id="process" aria-labelledby="process-title">
    <div class="container">
        <header class="section-head reveal">
            <h2 id="process-title"><?= e(site_copy('process_title')) ?></h2>
            <p><?= e(site_copy('process_sub')) ?></p>
        </header>
        <ol class="steps">
            <?php foreach ($processSteps as $i => $step):
                $num = str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT);
                $st = is_array($step) ? (string)($step['t'] ?? '') : '';
                $sd = is_array($step) ? (string)($step['d'] ?? '') : '';
            ?>
            <li class="reveal"><span><?= e($num) ?></span><h3><?= e($st) ?></h3><p><?= e($sd) ?></p></li>
            <?php endforeach; ?>
        </ol>
    </div>
</section>

<?php if ($portfolio): ?>
<section class="section section-alt" id="cases" aria-labelledby="cases-title">
    <div class="container">
        <header class="section-head reveal">
            <h2 id="cases-title"><?= e(site_copy('cases_title')) ?></h2>
            <p><?= e(site_copy('cases_sub')) ?></p>
        </header>
        <div class="cases-grid">
            <?php foreach ($portfolio as $case): ?>
            <article class="case-card reveal">
                <?php if (!empty($case['image'])): ?>
                <img class="case-image" src="<?= e(media_url($case['image'])) ?>" width="640" height="360" alt="<?= e($case['title']) ?>" loading="lazy" decoding="async">
                <?php endif; ?>
                <h3><?= e($case['title']) ?></h3>
                <p><?= e($case['description'] ?? '') ?></p>
                <?php if (!empty($case['stack'])): ?><p class="muted"><?= e($case['stack']) ?></p><?php endif; ?>
                <?php if (!empty($case['result_text'])): ?><p class="case-result"><?= e($case['result_text']) ?></p><?php endif; ?>
                <?php if (!empty($case['url'])): ?><p><a href="<?= e($case['url']) ?>" target="_blank" rel="noopener"><?= e(site_copy('case_link')) ?></a></p><?php endif; ?>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section<?= $portfolio ? '' : ' section-alt' ?>" id="gallery" aria-labelledby="gallery-title">
    <div class="container">
        <header class="section-head reveal">
            <h2 id="gallery-title"><?= e(site_copy('gallery_title')) ?></h2>
            <p><?= e(site_copy('gallery_sub')) ?></p>
        </header>
        <?php if ($gallery): ?>
        <div class="gallery-grid">
            <?php foreach ($gallery as $item):
                $img = gallery_thumb_url($item);
            ?>
            <a class="gallery-card reveal" href="<?= e($item['url'] ?? '#') ?>" target="_blank" rel="noopener">
                <img src="<?= e($img) ?>" width="360" height="560" alt="<?= e($item['title'] ?? '') ?>" loading="lazy" decoding="async">
                <span class="play" aria-hidden="true"></span>
                <span class="gallery-cap"><?= e($item['title'] ?? '') ?></span>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <?php if (setting('youtube')): ?>
        <p class="rate-note reveal" style="margin-top:1.5rem">
            <a class="btn btn-secondary" href="<?= e(setting('youtube')) ?>" target="_blank" rel="noopener"><?= e(site_copy('gallery_youtube_label', 'gallery_youtube')) ?></a>
        </p>
        <?php endif; ?>
    </div>
</section>

<section class="section" id="stack" aria-labelledby="stack-title">
    <div class="container">
        <header class="section-head reveal">
            <h2 id="stack-title"><?= e(site_copy('stack_title', 'stack_title_only')) ?></h2>
            <p><?= e(site_copy('stack_sub')) ?></p>
        </header>
        <ul class="stack-list reveal">
            <?php foreach ($stackItems as $item): ?>
            <li><?= e((string)$item) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>

<section class="section" id="faq" aria-labelledby="faq-title">
    <div class="container narrow">
        <header class="section-head reveal">
            <h2 id="faq-title"><?= e(site_copy('faq_title')) ?></h2>
            <p><?= e(site_copy('faq_sub')) ?></p>
        </header>
        <div class="faq-list" data-faq>
            <?php foreach ($faq as $i => $item): ?>
            <div class="faq-item reveal">
                <h3 class="faq-q">
                    <button type="button" class="faq-btn" id="faq-btn-<?= (int)$i ?>"
                            aria-expanded="<?= $i === 0 ? 'true' : 'false' ?>"
                            aria-controls="faq-panel-<?= (int)$i ?>">
                        <?= e($item['q'] ?? '') ?>
                    </button>
                </h3>
                <div class="faq-a" id="faq-panel-<?= (int)$i ?>" role="region"
                     aria-labelledby="faq-btn-<?= (int)$i ?>"<?= $i === 0 ? '' : ' hidden' ?>>
                    <p><?= e($item['a'] ?? '') ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section-cta" id="lead" aria-labelledby="lead-title">
    <div class="container lead-grid">
        <div class="reveal">
            <header class="section-head left">
                <h2 id="lead-title"><?= e(site_copy('lead_title')) ?></h2>
                <p><?= e(site_copy('lead_sub')) ?></p>
            </header>
            <div class="contact-chips">
                <?php if (setting('phone')): ?><a href="tel:<?= e(preg_replace('/[^\d+]/', '', setting('phone'))) ?>"><?= e(setting('phone')) ?></a><?php endif; ?>
                <?php if (setting('telegram')): ?><a href="<?= e(setting('telegram')) ?>" target="_blank" rel="noopener">TG канал</a><?php endif; ?>
                <?php if (setting('vk')): ?><a href="<?= e(setting('vk')) ?>" target="_blank" rel="noopener">VK</a><?php endif; ?>
                <?php if (setting('youtube')): ?><a href="<?= e(setting('youtube')) ?>" target="_blank" rel="noopener">YouTube</a><?php endif; ?>
                <?php if (setting('whatsapp')): ?><a href="<?= e(setting('whatsapp')) ?>" target="_blank" rel="noopener">WhatsApp</a><?php endif; ?>
            </div>
        </div>
        <form class="lead-form reveal" id="leadForm" method="post" action="<?= e(lang_url('/lead')) ?>" novalidate>
            <?= Csrf::field() ?>
            <input type="text" name="website" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">
            <input type="hidden" name="page_url" value="<?= e(\App\Core\Lang::absoluteUrl('/')) ?>">
            <input type="hidden" name="utm_source" id="utm_source">
            <input type="hidden" name="utm_medium" id="utm_medium">
            <input type="hidden" name="utm_campaign" id="utm_campaign">
            <input type="hidden" name="utm_content" id="utm_content">
            <input type="hidden" name="utm_term" id="utm_term">

            <div class="form-row">
                <label for="name"><?= e(__('field_name')) ?> *</label>
                <input id="name" name="name" type="text" required maxlength="120" autocomplete="name" aria-describedby="err-name">
                <p class="field-error" id="err-name" hidden></p>
            </div>
            <div class="form-row">
                <label for="phone"><?= e(__('field_phone')) ?> *</label>
                <input id="phone" name="phone" type="tel" required maxlength="40" autocomplete="tel" aria-describedby="err-phone">
                <p class="field-error" id="err-phone" hidden></p>
            </div>
            <div class="form-row">
                <label for="email"><?= e(__('field_email')) ?></label>
                <input id="email" name="email" type="email" maxlength="160" autocomplete="email" aria-describedby="err-email">
                <p class="field-error" id="err-email" hidden></p>
            </div>
            <div class="form-row two">
                <div>
                    <label for="service_id"><?= e(__('field_service')) ?></label>
                    <select id="service_id" name="service_id">
                        <option value=""><?= e(__('not_selected')) ?></option>
                        <?php foreach ($services as $service): ?>
                        <option value="<?= (int)$service['id'] ?>"><?= e(service_field($service, 'title')) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="package_id"><?= e(__('field_package')) ?></label>
                    <select id="package_id" name="package_id">
                        <option value=""><?= e(__('not_selected')) ?></option>
                        <?php foreach ($packages as $pkg): ?>
                        <option value="<?= (int)$pkg['id'] ?>"><?= e(package_field($pkg, 'title')) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <label for="messenger"><?= e(__('field_messenger')) ?></label>
                <select id="messenger" name="messenger">
                    <option value=""><?= e(__('messenger_any')) ?></option>
                    <option value="telegram"><?= e(__('messenger_telegram')) ?></option>
                    <option value="whatsapp"><?= e(__('messenger_whatsapp')) ?></option>
                    <option value="phone"><?= e(__('messenger_phone')) ?></option>
                </select>
            </div>
            <div class="form-row">
                <label for="message"><?= e(__('field_message')) ?></label>
                <textarea id="message" name="message" rows="4" maxlength="3000" placeholder="<?= e(site_copy('lead_message_ph', 'field_message_ph')) ?>"></textarea>
            </div>
            <label class="check">
                <input type="checkbox" name="consent" value="1" required aria-describedby="err-consent">
                <span><?= e(__('consent')) ?>: <a href="<?= e(lang_url('/privacy')) ?>" target="_blank" rel="noopener"><?= e(__('consent_link')) ?></a> *</span>
            </label>
            <p class="field-error" id="err-consent" hidden></p>
            <button class="btn btn-primary btn-block" type="submit" id="leadSubmit" data-label="<?= e(site_copy('submit_label', 'submit')) ?>">
                <?= e(site_copy('submit_label', 'submit')) ?>
            </button>
        </form>
    </div>
</section>
