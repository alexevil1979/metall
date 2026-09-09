# План размещения в поисковиках — metall.1tlt.ru

Канонический сайт: **https://metall.1tlt.ru**

## Быстрая проверка

```bash
curl -sI https://metall.1tlt.ru/ | head -20
curl -s https://metall.1tlt.ru/sitemap.xml | head -30
curl -s https://metall.1tlt.ru/robots.txt
```

## Яндекс / Google

1. Добавить сайт `https://metall.1tlt.ru`
2. Подтвердить права (HTML-файл / meta / DNS)
3. Отправить `https://metall.1tlt.ru/sitemap.xml`
4. Указать главное зеркало без www (если www не используется)

## Контент для индекса

- Арочные каркасы 3–8 м
- Каркасы хозблоков на краб-системе
- Доставка металлоконструкций по РФ
- МеталлКомплект31, Белгородская обл.

На VPS:

```bash
cd /ssd/www/metall
git pull origin main
# .env: APP_URL=https://metall.1tlt.ru
```
