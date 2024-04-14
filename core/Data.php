<?php

namespace LennisDev\webDocs;

class Data
{
    private static function resolvePath($path)
    {
        $docsDir = __DIR__ . "/../docs/";
        $realUrl = realpath($docsDir . $path);
        if (str_starts_with($realUrl, realpath($docsDir))) {
            try {
                return $realUrl;
            } catch (\Exception $e) {
                throw new \Exception("File not found");
            }
        } else {
            throw new \Exception("Invalid URL");
        }
    }

    public static function getDataFromUrl($url): string
    {
        try {
            return file_get_contents(self::resolvePath($url));
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public static function getMimeType($url): string
    {
        try {
            return mime_content_type(self::resolvePath($url));
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public static function readfile($url): void
    {
        try {
            readfile(self::resolvePath($url));
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public static function getToc($doc, $version): array
    {
        $toc = [];
        $lines = explode("\n", $doc);
        foreach ($lines as $line) {
            if (str_starts_with($line, "#")) {
                $level = substr_count($line, "#");
                $title = trim(str_replace("#", "", $line));
                $toc[] = [
                    "level" => $level,
                    "title" => $title,
                    "url" => "/$version/" . strtolower(str_replace(" ", "-", $title))
                ];
            }
        }
        return $toc;
    }

    public static function isDir($url): bool
    {
        try {
            return is_dir(self::resolvePath($url));
        } catch (\Exception $e) {
            return false;
        }
    }
}
