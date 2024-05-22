<?php

defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Object\CMSObject;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

class plgButtonWidgetkit extends CMSPlugin
{
    /**
     * Display the button.
     */
    public function onDisplay($editor)
    {
        HTMLHelper::_('jquery.framework');

        $style = Uri::root(true) . '/media/com_widgetkit/css/joomla.css';
        $script = Uri::root(true) . '/media/com_widgetkit/js/joomla.picker.js';
        $iframe = Route::_('index.php?option=com_widgetkit&tmpl=component&p=/picker', false);

        $document = Factory::getApplication()->getDocument();
        $document->addScript($script, ['version' => 'auto'], ['defer' => true]);
        $document->addScriptOptions('widgetkit', compact('iframe'));
        $document->addStylesheet($style);

        $button = new CMSObject();
        $button->modal = false;
        $button->link = '#';
        $button->onclick = "insertWidgetkitWidget('{$editor}');return false;";
        $button->class = 'btn btn-widgetkit';
        $button->text = 'Widgetkit';
        $button->name = 'widgetkit';
        $button->icon = 'widgetkit';
        $button->iconSVG = '<svg width="30" height="30" viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg">
    <path fill="none" stroke="#444" stroke-width="1.7" d="M16.26,6.08A2.52,2.52,0,0,0,12.82,7a2.49,2.49,0,0,0,.92,3.41,2.52,2.52,0,0,0,3.44-.92A2.49,2.49,0,0,0,16.26,6.08Z"/>
    <path fill="none" stroke="#444" stroke-width="1.7" d="M24.12,19.62a2.53,2.53,0,0,0-3.44.91,2.49,2.49,0,0,0,.92,3.41A2.53,2.53,0,0,0,25,23,2.48,2.48,0,0,0,24.12,19.62Z"/>
    <path fill="none" stroke="#444" stroke-width="1.7" d="M8.32,19.62a2.52,2.52,0,0,0-3.43.91,2.48,2.48,0,0,0,.92,3.41A2.53,2.53,0,0,0,9.25,23,2.49,2.49,0,0,0,8.32,19.62Z"/>
    <path fill="none" stroke="#444" stroke-width="1.7" d="M20.87,9.33a10.58,10.58,0,0,0-.13,2.13,5.77,5.77,0,0,0,.67,2.09A5.56,5.56,0,0,0,24.69,16a7.58,7.58,0,0,1,1.67.87,6,6,0,0,1,1.92,7.83A6,6,0,0,1,26,27a6.44,6.44,0,0,1-2.47.79,7.78,7.78,0,0,1-1.7-.08,6.09,6.09,0,0,1-4.07-2.91,3.51,3.51,0,0,1-.19-.35,5.94,5.94,0,0,1-.52-3.93,5.56,5.56,0,0,0-.54-4.23,4.8,4.8,0,0,0-1.62-1.69"/>
    <path fill="none" stroke="#444" stroke-width="1.7" d="M5.05,16.17a12.49,12.49,0,0,0,2-1.07,5.91,5.91,0,0,0,1.48-1.62,5.53,5.53,0,0,0,.53-4.07A7.39,7.39,0,0,1,9,7.53a6.18,6.18,0,0,1,2.15-4.15A6.3,6.3,0,0,1,14.89,2,5.93,5.93,0,0,1,18,2.81a6.06,6.06,0,0,1,1.9,1.76,6.34,6.34,0,0,1,.77,1.51,5.87,5.87,0,0,1-.52,5c-.06.11-.2.32-.21.34a6,6,0,0,1-3.16,2.39,5.45,5.45,0,0,0-3.42,2.55,4.43,4.43,0,0,0-.67,2.19"/>
    <path fill="none" stroke="#444" stroke-width="1.7" d="M19,26.28a10.46,10.46,0,0,0-1.83-1.18A5.86,5.86,0,0,0,15,24.64a5.53,5.53,0,0,0-3.77,1.59,7.14,7.14,0,0,1-1.59,1A6.15,6.15,0,0,1,5,27.49,6.28,6.28,0,0,1,1.88,25,6,6,0,0,1,1,21.86a6.32,6.32,0,0,1,.56-2.53,6.78,6.78,0,0,1,.92-1.43A6,6,0,0,1,7,15.84c.12,0,.37,0,.4,0a5.93,5.93,0,0,1,3.65,1.52A5.53,5.53,0,0,0,15,19a4.52,4.52,0,0,0,2.24-.53"/>
</svg>';

        return $button;
    }
}