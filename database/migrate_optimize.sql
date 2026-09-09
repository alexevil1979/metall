SET NAMES utf8mb4;
INSERT INTO settings (k, v) VALUES
('public_url', 'https://metall.1tlt.ru'),
('site_name_latin', 'MetallKomplekt31')
ON DUPLICATE KEY UPDATE v = VALUES(v);
