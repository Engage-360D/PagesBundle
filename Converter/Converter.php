<?php

/**
 * This file is part of the Engage360d package bundles.
 *
 */

namespace Engage360d\Bundle\PagesBundle\Converter;

use Engage360d\Bundle\PagesBundle\Entity\Page\PageBlock;
use Michelf\Markdown;

class Converter
{
    /**
     * The options we use for html to markdown
     *
     * @var array
     */
    private $options = array(
        'header_style' => 'atx',
        'bold_style' => '__',
        'italic_style' => '_',
    );

    /**
     * Converts the outputted json from Sir Trevor to html
     *
     * @param  string $json
     * @return string
     */
    public function toHtml($blocks)
    {
        $html = array();
        foreach ($blocks as $block) {
            $html[] = $this->blockToHtml($block);
        }

        return $html;
    }

    public function blockToHtml(PageBlock $block)
    {
        $converter = $block->getType() . 'ToHtml';
        if (is_callable(array($this, $converter))) {
            return call_user_func_array(
                array($this, $converter),
                $block->getData()
            );
        } else if (array_key_exists('text', $block->getData())) {
            $data = $block->getData();
            return $this->defaultToHtml($data['text']);
        } else {
            throw new Exception('Can\t convert type ' . $block['type'] . '.');
        }
    }

    /**
     * Converts default elements to html
     *
     * @param  string $text
     * @return string
     */
    public function defaultToHtml($text)
    {
        return Markdown::defaultTransform($text);
    }

    /**
     * Converts video block to html
     *
     * @param  string $source
     * @param  string $remoteId
     * @return string
     */
    public function videoToHtml($source, $remoteId)
    {
        // youtube video's
        if ($source == 'youtube') {
            $html = '<iframe src="//www.youtube.com/embed/' . $remoteId . '?rel=0" ';
            $html .= 'frameborder="0" allowfullscreen></iframe>' . "\n";

            return $html;
        }

        // vimeo videos
        if ($source == 'vimeo') {
            $html = '<iframe src="//player.vimeo.com/video/' . $remoteId;
            $html .= '?title=0&amp;byline=0" frameborder="0"></iframe>' . "\n";

            return $html;
        }

        // fallback
        return '';
    }

    /**
     * Converts headers to html
     *
     * @param  string $text
     * @return string
     */
    public function headingToHtml($text)
    {
        return Markdown::defaultTransform('## ' . $text);
    }

    /**
     * Converts quotes to html
     *
     * @param  string           $text
     * @param  string[optional] $cite
     * @return string
     */
    public function quoteToHtml($text, $cite = null)
    {
        $html = '<blockquote>';
        $html .= Markdown::defaultTransform($text);

        // Add the cite if necessary
        if (!empty($cite)) {
            // remove the indent thats added by Sir Trevor
            $cite = ltrim($cite, '>');
            $html .= '<cite>' . Markdown::defaultTransform($cite) . '</cite>';
        }

        $html .= '</blockquote>';

        return $html;
    }

    /**
     * Converts the image to html
     *
     * @param  array  $file
     * @return string
     */
    public function imageToHtml($file)
    {
        return '<img src="' . $file['url'] . '" />' . "\n";
    }
}
