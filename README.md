# МеталлКомплект31 — продающий лендинг

Одностраничный лендинг сборных металлоконструкций с админкой, заявками и уведомлениями в Telegram + Gmail SMTP.

Стек: **PHP 8.2**, **MySQL 5.7**, **Apache 2.4**, чистый MVC (на базе архитектуры proflanding).

Домен: **https://metall.1tlt.ru**  
VPS: `/ssd/www/metall`  
Git: https://github.com/alexevil1979/metall

## Возможности

- Публичный лендинг с продукцией и комплектами из БД
- Только русский язык
- Цены в ₽
- Форма заявок (CSRF + honeypot + rate limit)
- Админка: заявки, продукция, комплекты, объекты, **весь контент лендинга**, SEO, уведомления, пароль
- Уведомления: Telegram Bot API + SMTP Gmail
- SEO: Open Graph, JSON-LD, sitemap.xml, robots.txt

Контент лендинга правится в `/admin/settings` по подразделам (бренд, меню, цифры, заголовки, шаги, преимущества, видео, FAQ, юр. тексты, аналитика) — обычные поля, без JSON.
Продукция / комплекты / объекты — отдельные разделы CRUD.

## Установка на VPS

```bash
cd /ssd/www
git clone https://github.com/alexevil1979/metall.git
cd metall
cp .env.example .env
nano .env
```

### База данных

```bash
mysql -u root -p -e "CREATE DATABASE metall CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p metall < database/schema.sql
mysql -u root -p metall < database/seed.sql
```

В `.env`:

```
APP_URL=https://metall.1tlt.ru
DB_NAME=metall
DB_USER=root
DB_PASS=пароль_root
```

Сгенерировать `APP_KEY`:

```bash
php -r "echo bin2hex(random_bytes(32)), PHP_EOL;"
```

### Права

```bash
chown -R www-data:www-data /ssd/www/metall
chmod -R 775 /ssd/www/metall/storage
```

### Apache + PHP-FPM

```apache
DocumentRoot /ssd/www/metall/public
```

Пример: `deploy/apache-vhost.conf.example`

```bash
sudo a2enmod rewrite proxy proxy_fcgi headers expires deflate ssl
sudo cp /ssd/www/metall/deploy/apache-vhost.conf.example /etc/apache2/sites-available/metall.conf
sudo a2ensite metall.conf
sudo apache2ctl configtest && sudo systemctl reload apache2
sudo certbot --apache -d metall.1tlt.ru
sudo systemctl reload php82-fpm
sudo systemctl reload apache2
```

## Первый вход в админку

URL: `https://metall.1tlt.ru/admin/login`

Из seed:

- Логин: `admin`
- Пароль: `ChangeMe123!`

**Сразу смените пароль** в разделе «Пароль».

## Обновление на VPS

```bash
cd /ssd/www/metall
git pull
mysql -u root -p metall < database/migrate_fill_gallery.sql
```

`migrate_fill_gallery.sql` заполняет видео/шаги/буллеты только если в БД они пустые — уже сохранённый контент не затирает.

## Контент из прайса

Seed заполнен по КП «МеталлКомплект31»:

- Арки 3–8 м (стандарт / усиленные / высокие)
- Каркасы хозблоков и душа/санузла на краб-системе
- Телефон: +7 (951) 141-78-98
- Доставка по РФ

## Чеклист запуска

- [ ] `.env` заполнен, не в git
- [ ] schema + seed импортированы
- [ ] `storage/` доступен на запись www-data
- [ ] DocumentRoot = `.../public`
- [ ] HTTPS работает
- [ ] `/` открывается, продукция видна
- [ ] заявка пишется в `leads`
- [ ] тест Telegram / SMTP в админке
- [ ] пароль админа сменён

## Структура

```
public/          # DocumentRoot
app/             # Core, Controllers, Models, Views, Services
config/          # config.php, routes.php
database/        # schema.sql, seed.sql
storage/         # logs, cache, uploads
deploy/          # пример Apache vhost
```

## Git

```bash
cd /ssd/www/metall
git pull origin main
```

Локально:

```bash
git clone https://github.com/alexevil1979/metall.git
cd metall
cp .env.example .env
php -S localhost:8080 -t public
```
