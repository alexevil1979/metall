SET NAMES utf8mb4;
INSERT INTO settings (k, v) VALUES
('avatar_path', '/assets/img/channel-avatar.jpg'),
('og_image', '/assets/img/channel-banner.jpg'),
('youtube', 'https://www.youtube.com/@МеталлКомплект31')
ON DUPLICATE KEY UPDATE v = VALUES(v);
