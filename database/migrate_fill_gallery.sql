-- Заполнить gallery_json из текущего набора роликов, если ключ пустой или отсутствует.
-- Безопасно: не затирает уже сохранённые непустые данные.
SET NAMES utf8mb4;

INSERT INTO settings (k, v) VALUES
('gallery_json', '[{"file":"1-jPCfu0XTxSg.jpg","id":"jPCfu0XTxSg","title":"Сборка на болтах","url":"https://www.youtube.com/watch?v=jPCfu0XTxSg"},{"file":"4-u0DIhG8T8EQ.jpg","id":"u0DIhG8T8EQ","title":"Каркас для хозблока","url":"https://www.youtube.com/watch?v=u0DIhG8T8EQ"},{"file":"5-uUamvTsWjmw.jpg","id":"uUamvTsWjmw","title":"Каркас на краб-системе","url":"https://www.youtube.com/watch?v=uUamvTsWjmw"},{"file":"6-g5L1chJzm24.jpg","id":"g5L1chJzm24","title":"Каркас для гаража","url":"https://www.youtube.com/watch?v=g5L1chJzm24"},{"file":"7-gSe4Osrkdp4.jpg","id":"gSe4Osrkdp4","title":"Хозблок на дачу","url":"https://www.youtube.com/watch?v=gSe4Osrkdp4"},{"file":"9-1ZuFkFxbrm8.jpg","id":"1ZuFkFxbrm8","title":"Каркасы от производителя","url":"https://www.youtube.com/watch?v=1ZuFkFxbrm8"},{"file":"2-X1KFI5Z30Vk.jpg","id":"X1KFI5Z30Vk","title":"Производство металлоконструкций","url":"https://www.youtube.com/watch?v=X1KFI5Z30Vk"},{"file":"8-Q9bMGq7wsv8.jpg","id":"Q9bMGq7wsv8","title":"Каталог продукции","url":"https://www.youtube.com/watch?v=Q9bMGq7wsv8"}]')
ON DUPLICATE KEY UPDATE v = IF(v IS NULL OR v = '' OR v = '[]', VALUES(v), v);

INSERT INTO settings (k, v) VALUES
('trust_bullets_json', '["Болтовое соединение — монтаж без сварки на объекте","Сборно-разборный каркас, быстрое возведение","Выгодная доставка по всей России"]'),
('process_steps_json', '[{"t":"Заявка","d":"Укажите ширину/длину объекта и назначение: гараж, ангар, склад, навес."},{"t":"Подбор","d":"Подберём серию арок или каркас, фундамент и состав комплекта."},{"t":"Отгрузка","d":"Комплектуем крепёж и отправляем доставку по вашему адресу."},{"t":"Монтаж","d":"Собираете каркас на болтах — быстро и без сварки на площадке."}]'),
('stack_items', 'Болтовое соединение\nСборно-разборный каркас\nБыстрый монтаж\nЛюбой фундамент\nНагрузка до 200 кг/м²\nШаг арок 3 м\nКраб-система\nДоставка по РФ')
ON DUPLICATE KEY UPDATE v = IF(v IS NULL OR v = '' OR v = '[]', VALUES(v), v);
