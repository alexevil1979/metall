<?php

declare(strict_types=1);

namespace App\Core;

/** Только русский язык. */
final class Lang
{
    public const DEFAULT = 'ru';

    /** @var array<string, array{name:string,native:string,locale:string,dir:string,og:string}> */
    public const LOCALES = [
        'ru' => ['name' => 'Russian', 'native' => 'Русский', 'locale' => 'ru_RU', 'dir' => 'ltr', 'og' => 'ru_RU'],
    ];

    private static string $code = self::DEFAULT;
    /** @var array<string, string> */
    private static array $messages = [];
    /** @var array<string, mixed> */
    private static array $content = [];

    public static function boot(string $root): void
    {
        $uri = Request::uri();
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $path = rawurldecode($path);

        // Старые языковые префиксы → каноникал без префикса
        if (preg_match('#^/(en|ru|fa|zh|tr|ar)(/.*)?$#u', $path, $m)) {
            $rest = $m[2] ?? '/';
            if ($rest === '') {
                $rest = '/';
            }
            $query = parse_url($uri, PHP_URL_QUERY);
            $target = $rest === '/' ? '/' : $rest;
            if ($query) {
                $target .= '?' . $query;
            }
            redirect($target);
        }

        self::$code = self::DEFAULT;
        self::loadFiles($root);
    }

    public static function code(): string
    {
        return self::$code;
    }

    public static function isRtl(): bool
    {
        return false;
    }

    public static function htmlLang(): string
    {
        return 'ru';
    }

    public static function ogLocale(): string
    {
        return 'ru_RU';
    }

    public static function dir(): string
    {
        return 'ltr';
    }

    public static function get(string $key, ?string $default = null): string
    {
        if (array_key_exists($key, self::$messages)) {
            return (string)self::$messages[$key];
        }
        return $default ?? $key;
    }

    public static function content(string $key, ?string $fallback = null): string
    {
        $node = self::contentNode($key);
        if ($node === null) {
            return $fallback ?? '';
        }
        return is_scalar($node) ? (string)$node : ($fallback ?? '');
    }

    /** @return mixed */
    public static function contentNode(string $key): mixed
    {
        $parts = explode('.', $key);
        $node = self::$content;
        foreach ($parts as $p) {
            if (!is_array($node) || !array_key_exists($p, $node)) {
                return null;
            }
            $node = $node[$p];
        }
        return $node;
    }

    /** @return array<int|string, mixed>|null */
    public static function contentArray(string $key): ?array
    {
        $node = self::contentNode($key);
        return is_array($node) ? $node : null;
    }

    /** @return array<int, array{q:string,a:string}> */
    public static function faq(array $fallbackRu): array
    {
        return $fallbackRu;
    }

    public static function prefix(): string
    {
        return '';
    }

    public static function url(string $path = '/'): string
    {
        $path = '/' . ltrim($path, '/');
        if ($path === '/') {
            return '/';
        }
        return $path;
    }

    public static function absoluteUrl(string $path = '/'): string
    {
        return app_url(ltrim(self::url($path), '/'));
    }

    /** @return list<string> */
    public static function codes(): array
    {
        return ['ru'];
    }

    private static function loadFiles(string $root): void
    {
        $uiRu = $root . '/lang/ru.php';
        $messages = is_file($uiRu) ? (require $uiRu) : [];
        self::$messages = is_array($messages) ? $messages : [];
        self::$content = [];
    }
}
