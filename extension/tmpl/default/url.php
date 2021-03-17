<?php

/**
 * @package     Phproberto.Module
 * @subpackage  BS5.Navbar
 *
 * @copyright   Copyright (C) 2021 Roberto Segura López - All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

extract($displayData);

$attributes = [];

if ($item->anchor_title) {
    $attributes['title'] = $item->anchor_title;
}

$attributes['class'] = $item->anchor_css ? $item->anchor_css : '';

if ((int) $item->level === 1) {
    $attributes['class'] .= ' nav-link';
} else {
    $attributes['class'] .= ' dropdown-item';
}

if (in_array($item->id, $path)) {
    $attributes['class'] .= ' active';
}

if ($item->anchor_rel) {
    $attributes['rel'] = $item->anchor_rel;
}

$linktype = $item->title;

if ($item->menu_image) {
    $linktype = JHtml::_('image', $item->menu_image, $item->title);

    if ($item->params->get('menu_text', 1)) {
        $linktype .= '<span class="image-title">' . $item->title . '</span>';
    }
}

if ($item->browserNav == 1) {
    $attributes['target'] = '_blank';
} elseif ($item->browserNav == 2) {
    $options = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,' . $params->get('window_open');

    $attributes['onclick'] = "window.open(this.href, 'targetWindow', '" . $options . "'); return false;";
}

echo $moduleInstance->render(
    'default.link',
    [
        'url' => JFilterOutput::ampReplace(htmlspecialchars($item->flink, ENT_COMPAT, 'UTF-8')),
        'text' => $linktype,
        'attribs' => $attributes
    ]
);
