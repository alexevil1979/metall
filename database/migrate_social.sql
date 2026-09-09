SET NAMES utf8mb4;
INSERT INTO settings (k, v) VALUES
('telegram', 'https://t.me/metalkomplekt'),
('youtube', 'https://www.youtube.com/@МеталлКомплект31'),
('vk', 'https://vk.ru/club236130713')
ON DUPLICATE KEY UPDATE v = VALUES(v);
