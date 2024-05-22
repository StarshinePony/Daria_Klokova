<?php

namespace YOOtheme\Framework\Joomla;

use Joomla\CMS\Editor\Editor;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Uri\Uri;
use YOOtheme\Framework\Application;

class EditorHelper
{
    public static function load(Application $app, $element)
    {
        $root = Uri::root();
        $editor = Editor::getInstance();
        $document = Factory::getApplication()->getDocument();
        $language = Factory::getApplication()->getLanguage();
        $language->load("plg_editors_{$element}");

        // skip visual editor
        if (in_array($element, ['none', 'codemirror'])) {
            return;
        }

        // prevent xtd editor buttons from adding assets to the current document
        Factory::$document = clone $document;

        // current editor plugin
        $plugin = Table::getInstance('Extension');
        $plugin->load([
            'folder' => 'editors',
            'element' => $element,
        ]);

        // create editor config
        $config = [
            'id' => 'editor-xtd',
            'title' => isset($plugin->name) ? $language->_($plugin->name) : 'Editor',
            'iframe' => $app['url']->route('editor', ['format' => 'html', 'tmpl' => 'component']),
            'buttons' => array_filter(
                $editor->getButtons('editor-xtd', ['pagebreak', 'readmore', 'widgetkit']),
                function ($button) {
                    return !empty($button->modal);
                }
            ),
            'settings' => static::loadSettings() + [
                'branding' => false,
                'content_css' => "{$root}templates/system/css/editor.css",
                'directionality' => $language->get('rtl') ? 'rtl' : 'ltr',
                'document_base_url' => $root,
                'entity_encoding' => 'raw',
                'insert_button_items' => '', // e.g. 'hr charmap',
                'plugins' => 'link autolink hr lists charmap paste',
                'toolbar1' =>
                    'formatselect bold italic bullist numlist blockquote alignleft aligncenter alignright link strikethrough hr pastetext removeformat charmap outdent indent insert',
            ],
        ];

        // recover document
        Factory::$document = $document;

        return $config;
    }

    public static function loadSettings()
    {
        $tinymce = PluginHelper::getPlugin('editors', 'tinymce');
        $params = $tinymce ? json_decode($tinymce->params, true) : [];

        if (!empty($params['newlines'])) {
            $settings = [
                'forced_root_block' => '',
                'force_p_newlines' => false,
                'force_br_newlines' => true,
            ];
        } else {
            $settings = [
                'forced_root_block' => 'p',
                'force_p_newlines' => true,
                'force_br_newlines' => false,
            ];
        }

        return $settings;
    }

    public static function renderEditor()
    {
        $type = Factory::getConfig()->get('editor');
        $editor = Editor::getInstance($type);
        $exclude = ['pagebreak', 'readmore', 'widgetkit'];

        // core.js needs to initialize Joomla.editors early
        HTMLHelper::_('behavior.core');

        ob_start();

        echo "<form>{$editor->display('content', '', '100%', '100%', '', '30', $exclude)}</form>";

        return ob_get_clean();
    }
}
