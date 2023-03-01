<?php
declare(strict_types=1);

namespace HTTPCrawler\Templates;

class SimpleTemplate
{

    public static array $blocks = [];
    public static string $cache_path = '..\cache';
    public static string $templates_path = '..\templates';

    static function view($file, $data = []): void
    {
        $cached_file = self::cache($file);
        extract($data, EXTR_SKIP);
        require $cached_file;
    }

    static function cache($file): string
    {
        if (!file_exists(self::$cache_path)) {
            mkdir(self::$cache_path, 0744);
        }
        $cached_file = self::$cache_path . DIRECTORY_SEPARATOR . str_replace(['/', '.html'], ['_', ''], $file . '.php');

        $code = self::includeFiles(self::$templates_path . DIRECTORY_SEPARATOR . $file);
        $code = self::compileCode($code);

        file_put_contents($cached_file, '<?php class_exists(\'' . __CLASS__ . '\') or exit; ?>' . PHP_EOL . $code);

        return $cached_file;
    }

    static function compileCode(string $code): string
    {
        $code = self::compileBlock($code);
        $code = self::compileYield($code);
        $code = self::compileEscapedEchos($code);
        $code = self::compileEchos($code);
        return self::compilePHP($code);
    }

    static function includeFiles(string $file): string
    {
        $code = file_get_contents($file);
        preg_match_all('/{% ?(extends|include) ?\'?(.*?)\'? ?%}/i', $code, $matches, PREG_SET_ORDER);
        foreach ($matches as $value) {
            $code = str_replace($value[0], self::includeFiles($value[2]), $code);
        }
        return preg_replace('/{% ?(extends|include) ?\'?(.*?)\'? ?%}/i', '', $code);
    }

    static function compilePHP(string $code): string
    {
        return preg_replace('~\{%\s*(.+?)\s*%}~is', '<?php $1 ?>', $code);
    }

    static function compileEchos(string $code): string
    {
        return preg_replace('~\{{\s*(.+?)\s*}}~is', '<?php echo $1 ?>', $code);
    }

    static function compileEscapedEchos(string $code): string
    {
        return preg_replace('~\{{{\s*(.+?)\s*}}}~is', '<?php echo htmlentities($1, ENT_QUOTES, \'UTF-8\') ?>', $code);
    }

    static function compileBlock(string $code): string
    {
        preg_match_all('/{% ?block ?(.*?) ?%}(.*?){% ?endblock ?%}/is', $code, $matches, PREG_SET_ORDER);
        foreach ($matches as $value) {
            if (!array_key_exists($value[1], self::$blocks)) self::$blocks[$value[1]] = '';
            if (!str_contains($value[2], '@parent')) {
                self::$blocks[$value[1]] = $value[2];
            } else {
                self::$blocks[$value[1]] = str_replace('@parent', self::$blocks[$value[1]], $value[2]);
            }
            $code = str_replace($value[0], '', $code);
        }
        return $code;
    }

    static function compileYield(string $code): string
    {
        foreach (self::$blocks as $block => $value) {
            $code = preg_replace('/{% ?yield ?' . $block . ' ?%}/', $value, $code);
        }
        return preg_replace('/{% ?yield ?(.*?) ?%}/i', '', $code);
    }

}
