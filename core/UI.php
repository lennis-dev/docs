<?php

namespace LennisDev\webDocs;

require_once __DIR__ . "/Data.php";
require_once __DIR__ . "/MarkdownParser.php";

use LennisDev\webDocs\Data;
use LennisDev\webDocs\MarkdownParser;

class UI
{
    public string $url;
    public string $documentation;
    public string $version;
    public string $filePath;
    public string $html;
    public array $headings;

    public static $e404 = "<h1>404 Not Found</h1>";

    public function __construct($url)
    {
        $this->url = $url;
        $exploded = explode("/", $url);
        $this->documentation = $exploded[1];
        if (Data::isDir("/{$this->documentation}") === false) {
            header("Location: /");
            throw new \Exception("Invalid documentation");
        }
        $this->version = $exploded[2] ?? "";

        if ($this->version === "") {
            $this->version = "stable";
            header("Location: /{$this->documentation}/{$this->version}/");
        }
        $this->filePath = implode("/", array_slice($exploded, 3));

        if (str_ends_with($this->filePath, "/") || empty($this->filePath)) {
            $this->filePath .= "index.md";
        } else if (!str_ends_with($this->filePath, ".md")) {
            $this->filePath .= ".md";
        }

        try {
            $data = Data::getDataFromUrl("/{$this->documentation}/{$this->version}/{$this->filePath}");
            $parsed = MarkdownParser::render($data);
            $this->html = $parsed["html"];
            $this->headings = $parsed["headings"];
        } catch (\Exception $e) {
            $this->html = self::$e404;
            $this->headings = [];
        }
    }

    public function getBody(): string
    {
        $heading = "Markdown Docs";
        if ($this->html === self::$e404) {
            return $this->html;
        } else {
            $footer = "<footer>Edit this page on <a href='https://github.com/LennisDev/webDocs'>GitHub</a></footer>";
            return $this->html . $footer;
        }
    }

    public function getNav(): string
    {
        try {
            $data = json_decode(data::getDataFromUrl("/{$this->documentation}/{$this->version}/data.json"), true);
        } catch (\Exception $e) {
            $data = [];
        }
        $nav = "";
        foreach ($data as $item) {
            if ($item["type"] === "category") {
                if (str_starts_with($this->filePath, $item["link"]) && $item["link"] !== "/") {
                    $nav .= "<details open><summary><a href='/{$this->documentation}/{$this->version}/{$item["link"]}/'>{$item["label"]}</a></summary>";
                } else {
                    $nav .= "<details><summary><a href='/{$this->documentation}/{$this->version}/{$item["link"]}/'>{$item["label"]}</a></summary>";
                }
                foreach ($item["items"] as $subItem) {
                    $nav .= "<a href='/{$this->documentation}/{$this->version}/{$item["link"]}/{$subItem["link"]}'>{$subItem["label"]}</a>";
                }
                $nav .= "</details>";
            } else {
                $nav .= "<a href='/{$this->documentation}/{$this->version}/{$item["link"]}'>{$item["label"]}</a>";
            }
        }
        return $nav;
    }

    protected function getData()
    {
    }

    public function getVersionList(): string
    {
        $versions = scandir(__DIR__ . "/../docs/{$this->documentation}");
        $versions = array_filter($versions, function ($version) {
            return !in_array($version, [".", "..", $this->version]) && Data::isDir("/{$this->documentation}/{$version}");
        });
        $versionList = "";
        foreach ($versions as $version) {
            $versionList .= "<a href='/{$this->documentation}/{$version}/'>{$version}</a>";
        }
        return $versionList;
    }

    public static function renderDoc(string $requestURL): void
    {
        $ui = new UI($requestURL);

        echo '<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Docs</title>
    <link rel="stylesheet" href="/static/style/style.css">
</head>

<body>
    <header>
        <img src="https://lennis.dev/banner" alt="LennisDev" class="logo">
        <div class="dropdown version">
            <button class="dropbtn">' . $ui->version . '</button>
            <div class="dropdown-content">' . $ui->getVersionList() . '</div>
        </div>
        <div class="theme-switcher">
            <input type="checkbox" id="theme-switcher">
            <label for="theme-switcher"></label>
        </div>
    </header>
    <nav>' . $ui->getNav() . '</nav>
    <main>' . $ui->getBody() . '</main>
    <script src="/static/script/script.js"></script>
</body>

</html>';
    }
}
