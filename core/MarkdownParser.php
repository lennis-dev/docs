<?php

namespace LennisDev\webDocs;

require_once __DIR__ . "/../lib/parsedown/Parsedown.php";

class MarkdownParser extends \Parsedown
{
    protected array $headings = array();

    static function render(string $text): array
    {
        $parser = new MarkdownParser();
        $text = $parser->text($text);
        return array("headings" => $parser->getHeadings(), "html" => $text);
    }

    public function text($text): string
    {
        $html = parent::text($text);

        /**
         * adding support for emojis
         */
        $emojis = json_decode(file_get_contents(__DIR__ . "/../lib/emoji-list/emojis.json"), true)["emojis"];
        foreach ($emojis as $emoji) {
            $html = str_replace($emoji["shortname"], "<span class='emoji'>" . $emoji["html"] . "</span>", $html);
        }

        /**
         * adding support for checkboxes
         */
        $html = preg_replace_callback('/<li>\[([ xX])\] (.*)<\/li>/U', function ($matches) {
            $checked = ($matches[1] == 'x' || $matches[1] == 'X') ? 'checked' : '';
            return "<li class='checkbox'><input type='checkbox' disabled $checked> " . $matches[2] . "</li>";
        }, $html);

        /**
         * adding support for [toc] shortcode
         */

        $html = preg_replace('/\[toc\]/', $this->getToc(), $html);

        return $html;
    }

    protected function blockHeader($Line): array
    {
        $block = parent::blockHeader($Line);
        /*
        * adding support for counting headings
        */
        $heading = $block['element']["handler"]["argument"];
        $heading = str_replace(" ", "-", $heading);
        $heading = strtolower($heading);
        $block['element']['attributes']['id'] = $heading;
        $this->headings[] = [
            'id'    => $heading,
            'level' => trim($block['element']['name'], 'h'),
            'text'  => $block['element']["handler"]["argument"],
        ];

        return $block;
    }

    protected function blockQuote($Line): array
    {
        $block = parent::blockQuote($Line);
        /*
        * adding support for custom blockquote classes
        * [!TIP] -> tip
        * [!WARNING] -> warning
        * [!DANGER] -> danger
        * [!INFO] -> info
        * [!NOTE] -> note
        */

        if ($block['element']['name'] == 'blockquote' && isset($block['element']['handler']['argument'][0])) {
            $tmpArgument = $block['element']['handler']['argument'];
            $block['element']['handler']['argument'] = [];
            if (str_starts_with($tmpArgument[0], '[!TIP]'))
                $block['element']['attributes']['class'] = 'tip';
            else if (str_starts_with($tmpArgument[0], '[!WARNING]'))
                $block['element']['attributes']['class'] = 'warning';
            else if (str_starts_with($tmpArgument[0], '[!CAUTION]'))
                $block['element']['attributes']['class'] = 'caution';
            else if (str_starts_with($tmpArgument[0], '[!IMPORTANT]'))
                $block['element']['attributes']['class'] = 'important';
            else if (str_starts_with($tmpArgument[0], '[!NOTE]'))
                $block['element']['attributes']['class'] = 'note';
            else
                $block['element']['handler']['argument'] = $tmpArgument;
        }

        return $block;
    }

    public function getHeadings(): array
    {
        return $this->headings;
    }

    public function getToc(): string
    {
        $toc = "<ol>";
        $prevLevel = 1;
        foreach ($this->headings as $heading) {
            $level = $heading["level"];
            $text = $heading["text"];
            $id = $heading["id"];

            if ($level > $prevLevel) {
                $toc .= "<ol>";
            } else if ($level < $prevLevel) {
                $toc .= "</ol>";
            }

            $toc .= "<li><a href='#{$id}'>{$text}</a></li>";

            for ($i = $prevLevel; $i > $level; $i--) {
                $toc .= "</ol>";
            }

            $prevLevel = $level;
        }
        for ($i = 0; $i < $prevLevel; $i++) {
            $toc .= "</ol>";
        }
        return $toc;
    }
}
