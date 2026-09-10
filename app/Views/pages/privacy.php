<?php /** @var array $seo */ ?>
<section class="section legal legal-page">
    <div class="container narrow">
        <article class="legal-card">
            <p class="legal-brand"><bdi dir="ltr"><?= e(brand_name()) ?></bdi> · <?= e(setting('site_role')) ?></p>
            <h1><?= e($seo['h1'] ?? 'Политика конфиденциальности') ?></h1>
            <?php if (setting('privacy_text') !== ''): ?>
                <div class="legal-custom"><?= nl2br(e(setting('privacy_text'))) ?></div>
            <?php else: ?>
            <p>Настоящая политика определяет порядок обработки персональных данных, оставляемых пользователями на сайте <a href="<?= e(app_url()) ?>"><?= e(rtrim(app_url(), '/')) ?></a>.</p>
            <h2>1. Какие данные собираются</h2>
            <p>Имя, телефон, email, текст сообщения, выбранная продукция/комплект, технические данные (IP, user-agent, UTM-метки) при отправке формы заявки.</p>
            <h2>2. Цели обработки</h2>
            <p>Связь по заявке, подготовка коммерческого предложения, улучшение качества сервиса, защита от спама и злоупотреблений.</p>
            <h2>3. Правовые основания</h2>
            <p>Согласие субъекта персональных данных, выраженное при отправке формы.</p>
            <h2>4. Хранение и передача</h2>
            <p>Данные хранятся в базе сайта и могут направляться в Telegram и на email исполнителя для оперативной обработки заявки. Передача третьим лицам вне указанных целей не осуществляется.</p>
            <?php endif; ?>
            <h2>Контакты</h2>
            <div class="legal-contacts">
                <p><bdi dir="ltr"><?= e(brand_name()) ?></bdi><?php if (setting('city')): ?>, <?= e(setting('city')) ?><?php endif; ?></p>
                <?php if (setting('email')): ?><p>Email: <a href="mailto:<?= e(setting('email')) ?>"><?= e(setting('email')) ?></a></p><?php endif; ?>
                <?php if (setting('phone')): ?><p>Телефон: <a href="tel:<?= e(preg_replace('/[^\d+]/', '', setting('phone'))) ?>"><?= e(setting('phone')) ?></a></p><?php endif; ?>
                <?php if (setting('telegram')): ?><p>TG канал: <a href="<?= e(setting('telegram')) ?>" target="_blank" rel="noopener"><?= e(setting('telegram')) ?></a></p><?php endif; ?>
                <?php if (setting('vk')): ?><p>VK: <a href="<?= e(setting('vk')) ?>" target="_blank" rel="noopener"><?= e(setting('vk')) ?></a></p><?php endif; ?>
                <?php if (setting('youtube')): ?><p>YouTube: <a href="<?= e(setting('youtube')) ?>" target="_blank" rel="noopener"><?= e(setting('youtube')) ?></a></p><?php endif; ?>
            </div>
        </article>
    </div>
</section>
