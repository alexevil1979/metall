<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;

/**
 * Дефолтный контент лендинга (= то, что видит посетитель через site_copy / fallback).
 * ensureMissing() дописывает в БД только пустые/отсутствующие ключи.
 */
final class ContentDefaults
{
    /** @return array<string, string> */
    public static function scalars(): array
    {
        return [
            'public_url' => 'https://metall.1tlt.ru',
            'site_name' => 'МеталлКомплект31',
            'site_name_latin' => 'MetallKomplekt31',
            'site_role' => 'Сборные металлоконструкции',
            'site_tagline' => 'Быстровозводимые каркасы на болтовом соединении для гаража, ангара, хозблока, склада и навеса',
            'hero_offer' => 'Сборные металлоконструкции с доставкой по РФ',
            'hero_sub' => 'Арочные каркасы и универсальные рамы на краб-системе. Болтовое соединение, быстрый монтаж, установка на сваи, плиту или обвязку.',
            'portrait_alt' => 'МеталлКомплект31 — сборные металлоконструкции',
            'og_image' => '/assets/img/channel-banner.jpg',
            'avatar_path' => '/assets/img/channel-avatar.jpg',
            'phone' => '+7 (951) 141-78-98',
            'email' => 'info@metall.1tlt.ru',
            'telegram' => 'https://t.me/metalkomplekt',
            'whatsapp' => 'https://wa.me/79511417898',
            'youtube' => 'https://www.youtube.com/@МеталлКомплект31',
            'vk' => 'https://vk.ru/club236130713',
            'city' => 'Белгородская обл. · доставка по РФ',

            'nav_services' => 'Продукция',
            'nav_packages' => 'Комплекты',
            'nav_gallery' => 'Видео',
            'nav_process' => 'Как заказать',
            'nav_faq' => 'FAQ',
            'nav_contacts' => 'Контакты',
            'cta_lead' => 'Оставить заявку',
            'cta_services' => 'Смотреть продукцию',
            'discuss_label' => 'Подобрать каркас',
            'featured_label' => 'Хит',
            'optimal_label' => 'Популярный',
            'submit_label' => 'Отправить заявку',

            'experience_years' => '5+',
            'projects_count' => '300+',
            'response_hours' => '2',
            'stat_years_label' => 'лет на рынке',
            'stat_projects_label' => 'отгрузок и комплектов',
            'stat_response_label' => 'типовая реакция',
            'stat_hours_suffix' => 'ч',
            'trust_block_title' => 'Формат работы',
            'work_format' => 'Продажа комплектов каркасов с крепежом. Консультация по выбору арки и фундамента. Доставка по всей России.',
            'response_sla' => 'Ответ по заявке обычно в течение 1–2 часов в рабочие дни',
            'not_doing_title' => 'Что не делаем',
            'not_doing' => 'Не делаем: капитальное строительство «под ключ» без согласования, проектирование сложных зданий, монтаж без договорённости',

            'services_title' => 'Продукция',
            'services_sub' => 'Арочные каркасы и универсальные рамы на краб-системе — цены за единицу комплекта.',
            'packages_title' => 'Готовые комплекты',
            'packages_sub' => 'Ориентиры по наборам для гаража, ангара и хозблока — точный расчёт по длине объекта.',
            'process_title' => 'Как заказать',
            'process_sub' => 'Короткий цикл от заявки до отгрузки.',
            'gallery_title' => 'Видео с производства',
            'gallery_sub' => 'Реальные каркасы, сборка на болтах и монтаж — с нашего YouTube-канала.',
            'gallery_youtube_label' => 'Смотреть канал на YouTube',
            'stack_title' => 'Преимущества',
            'stack_sub' => 'То, что важно при выборе быстровозводимого каркаса.',
            'cases_title' => 'Объекты',
            'cases_sub' => 'Примеры применения комплектов.',
            'case_link' => 'Подробнее',
            'faq_title' => 'FAQ',
            'faq_sub' => 'Частые вопросы до заказа.',
            'lead_title' => 'Оставить заявку',
            'lead_sub' => 'Опишите объект — рассчитаем комплект, доставку и сроки.',
            'lead_message_ph' => 'Ширина, длина, назначение объекта',

            'privacy_text' => self::privacyText(),
            'offer_text' => self::offerText(),
        ];
    }

    public static function privacyText(): string
    {
        return <<<'TXT'
Настоящая политика определяет порядок обработки персональных данных, оставляемых пользователями на сайте https://metall.1tlt.ru.

1. Какие данные собираются
Имя, телефон, email, текст сообщения, выбранная продукция/комплект, технические данные (IP, user-agent, UTM-метки) при отправке формы заявки.

2. Цели обработки
Связь по заявке, подготовка коммерческого предложения, улучшение качества сервиса, защита от спама и злоупотреблений.

3. Правовые основания
Согласие субъекта персональных данных, выраженное при отправке формы.

4. Хранение и передача
Данные хранятся в базе сайта и могут направляться в Telegram и на email исполнителя для оперативной обработки заявки. Передача третьим лицам вне указанных целей не осуществляется.
TXT;
    }

    public static function offerText(): string
    {
        return <<<'TXT'
Настоящий документ является предложением МеталлКомплект31 заключить договор на поставку сборных металлоконструкций на условиях ниже. Сайт: https://metall.1tlt.ru.

1. Предмет
Поставщик поставляет арочные каркасы, универсальные рамы на краб-системе и комплектующие по заявке Покупателя. Цены на сайте — ориентировочные; итоговая стоимость подтверждается до оплаты.

2. Порядок заключения
Заявка на сайте не является автоматическим договором. Договор считается согласованным после подтверждения состава комплекта, сроков и стоимости сторонами (в переписке/счёте/договоре).

3. Оплата и доставка
Оплата производится на реквизиты, указанные в счёте. Доставка по РФ рассчитывается отдельно по адресу и объёму заказа.

4. Ответственность
Поставщик отвечает за комплектность поставки в согласованном объёме. Покупатель обеспечивает корректные данные для доставки и условия приёмки.
TXT;
    }

    /** @return array<string, string> JSON/multiline ключи */
    public static function structured(): array
    {
        $trust = json_encode([
            'Болтовое соединение — монтаж без сварки на объекте',
            'Сборно-разборный каркас, быстрое возведение',
            'Выгодная доставка по всей России',
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $steps = json_encode([
            ['t' => 'Заявка', 'd' => 'Укажите ширину/длину объекта и назначение: гараж, ангар, склад, навес.'],
            ['t' => 'Подбор', 'd' => 'Подберём серию арок или каркас, фундамент и состав комплекта.'],
            ['t' => 'Отгрузка', 'd' => 'Комплектуем крепёж и отправляем доставку по вашему адресу.'],
            ['t' => 'Монтаж', 'd' => 'Собираете каркас на болтах — быстро и без сварки на площадке.'],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $faq = json_encode([
            ['q' => 'Что входит в комплект арки?', 'a' => 'Каркас арки и крепёж: болты, шайбы, гайки. Обшивку и фундамент подбираете отдельно или обсуждаем комплектацию.'],
            ['q' => 'Какой фундамент нужен?', 'a' => 'Можно ставить на обвязку, спецблоки, плиту, заливные или винтовые сваи. Капитальный фундамент для стандартных арок не обязателен.'],
            ['q' => 'С какой нагрузкой рассчитаны арки?', 'a' => 'В зависимости от серии — ориентировочно 180–200 кг/м². Точные параметры уточняем по выбранной ширине.'],
            ['q' => 'Какой шаг установки арок?', 'a' => 'Типовой шаг — 3 метра. Длину объекта набираете нужным количеством секций.'],
            ['q' => 'Доставляете по России?', 'a' => 'Да, выгодная доставка по РФ. Стоимость и сроки считаем по адресу и объёму заказа.'],
            ['q' => 'Сложно ли собрать каркас?', 'a' => 'Конструкция сборно-разборная на болтовом соединении — быстрое и простое возведение без сварки на объекте.'],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $gallery = gallery_manifest();
        if ($gallery === []) {
            $gallery = [
                ['file' => '1-jPCfu0XTxSg.jpg', 'id' => 'jPCfu0XTxSg', 'title' => 'Сборка на болтах', 'url' => 'https://www.youtube.com/watch?v=jPCfu0XTxSg'],
                ['file' => '4-u0DIhG8T8EQ.jpg', 'id' => 'u0DIhG8T8EQ', 'title' => 'Каркас для хозблока', 'url' => 'https://www.youtube.com/watch?v=u0DIhG8T8EQ'],
                ['file' => '5-uUamvTsWjmw.jpg', 'id' => 'uUamvTsWjmw', 'title' => 'Каркас на краб-системе', 'url' => 'https://www.youtube.com/watch?v=uUamvTsWjmw'],
                ['file' => '6-g5L1chJzm24.jpg', 'id' => 'g5L1chJzm24', 'title' => 'Каркас для гаража', 'url' => 'https://www.youtube.com/watch?v=g5L1chJzm24'],
                ['file' => '7-gSe4Osrkdp4.jpg', 'id' => 'gSe4Osrkdp4', 'title' => 'Хозблок на дачу', 'url' => 'https://www.youtube.com/watch?v=gSe4Osrkdp4'],
                ['file' => '9-1ZuFkFxbrm8.jpg', 'id' => '1ZuFkFxbrm8', 'title' => 'Каркасы от производителя', 'url' => 'https://www.youtube.com/watch?v=1ZuFkFxbrm8'],
                ['file' => '2-X1KFI5Z30Vk.jpg', 'id' => 'X1KFI5Z30Vk', 'title' => 'Производство металлоконструкций', 'url' => 'https://www.youtube.com/watch?v=X1KFI5Z30Vk'],
                ['file' => '8-Q9bMGq7wsv8.jpg', 'id' => 'Q9bMGq7wsv8', 'title' => 'Каталог продукции', 'url' => 'https://www.youtube.com/watch?v=Q9bMGq7wsv8'],
            ];
        }
        $galleryJson = json_encode($gallery, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $stack = "Болтовое соединение\nСборно-разборный каркас\nБыстрый монтаж\nЛюбой фундамент\nНагрузка до 200 кг/м²\nШаг арок 3 м\nКраб-система\nДоставка по РФ";

        return [
            'trust_bullets_json' => (string)$trust,
            'process_steps_json' => (string)$steps,
            'faq_json' => (string)$faq,
            'gallery_json' => (string)$galleryJson,
            'stack_items' => $stack,
        ];
    }

    /** @return array<string, string> */
    public static function all(): array
    {
        return array_merge(self::scalars(), self::structured());
    }

    public static function get(string $key, string $fallback = ''): string
    {
        $all = self::all();
        if (isset($all[$key]) && $all[$key] !== '') {
            return $all[$key];
        }
        return $fallback;
    }

    /**
     * Дописать в settings отсутствующие и пустые ключи. Уже заполненные не трогает.
     * @return int число записанных ключей
     */
    public static function ensureMissing(): int
    {
        $current = Setting::all();
        $defaults = self::all();
        $toWrite = [];
        foreach ($defaults as $key => $value) {
            $existing = $current[$key] ?? null;
            if ($existing === null || $existing === '' || $existing === '[]') {
                $toWrite[$key] = $value;
            }
        }
        if ($toWrite === []) {
            return 0;
        }
        Setting::setMany($toWrite);
        setting_cache_flush();
        return count($toWrite);
    }

    /**
     * Слияние БД + дефолты для отображения формы (без обязательной записи).
     * @param array<string, string> $settings
     * @return array<string, string>
     */
    public static function mergeForForm(array $settings): array
    {
        $out = self::all();
        foreach ($settings as $k => $v) {
            $v = (string)$v;
            if ($v !== '' && $v !== '[]') {
                $out[$k] = $v;
            }
        }
        return $out;
    }
}
