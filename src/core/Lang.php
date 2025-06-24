<?php
namespace App\Core;

class Lang
{
    protected static array $translations = [];
    protected static array $fallback = [];

    public static function load(string $locale = 'en'): void
    {
        $mainPath = __DIR__ . '../../lang/' . $locale . '.json';
        $fallbackPath = __DIR__ . '../../lang/en.json';

        if (file_exists($mainPath)) {
            $json = file_get_contents($mainPath);
            self::$translations = json_decode($json, true) ?? [];
        } else {
            self::$translations = [];
        }

        if (file_exists($fallbackPath)) {
            $json = file_get_contents($fallbackPath);
            self::$fallback = json_decode($json, true) ?? [];
        } else {
            self::$fallback = [];
        }
    }

    public static function get(string $key, array $replacements = []): string
    {
        $value = self::resolveKey(self::$translations, $key)
            ?? self::resolveKey(self::$fallback, $key)
            ?? $key;

        foreach ($replacements as $placeholder => $val) {
            $value = str_replace('{' . $placeholder . '}', $val, $value);
        }

        return $value;
    }

    protected static function resolveKey(array $source, string $key): ?string
    {
        $keys = explode('.', $key);
        $value = $source;

        foreach ($keys as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return null;
            }
            $value = $value[$segment];
        }

        return is_string($value) ? $value : null;
    }

    public static function detectLocale(): string
    {
        $supported = self::getAvailableLocales();

        if (isset($_GET['lang'])) {
            $lang = strtolower($_GET['lang']);
            if (in_array($lang, $supported)) {
                $_SESSION['lang'] = $lang;
                return $lang;
            }
        }

        if (isset($_SESSION['lang'])) {
            $lang = strtolower($_SESSION['lang']);
            if (in_array($lang, $supported)) {
                return $lang;
            }
        }

        $acceptLang = strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'en');
        $primaryLang = substr(explode(',', $acceptLang)[0], 0, 2);
        $finalLang = in_array($primaryLang, $supported) ? $primaryLang : 'en';

        $_SESSION['lang'] = $finalLang;
        return $finalLang;
    }

    public static function getAvailableLocales(): array
    {
        $langPath = __DIR__ . '../../lang';
        $files = glob($langPath . '/*.json');

        $locales = array_map(function ($file) {
            return strtolower(basename($file, '.json'));
        }, $files);

        return array_unique($locales);
    }

    public static function getMeta(string $locale): array
{
    $path = __DIR__ . '../../lang/' . $locale . '.json';
    if (!file_exists($path)) {
        return ['name' => strtoupper($locale), 'emoji' => '🏳️'];
    }

    $json = json_decode(file_get_contents($path), true);
    return [
        'name' => $json['language']['language_name'] ?? strtoupper($locale),
        'emoji' => $json['language']['language_emoji'] ?? '🏳️',
    ];
}

}
