<?php

/**
 * @package     Phproberto.Module
 * @subpackage  BS5.Navbar
 *
 * @copyright   Copyright (C) 2021 Roberto Segura López - All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;

extract($displayData);

$title      = $item->anchor_title ? ' title="' . $item->anchor_title . '"' : '';
$anchor_css = $item->anchor_css ? $item->anchor_css : '';


if (in_array($item->id, $path)) {
    $anchor_css .= ' active';
}

$linktype   = $item->title;

if ($item->menu_image) {
    $linktype = HTMLHelper::_('image', $item->menu_image, $item->title);

    if ($item->params->get('menu_text', 1)) {
        $linktype .= '<span class="image-title">' . $item->title . '</span>';
    }
}
?>
<a href="#" class="nav-link dropdown-toggle <?php echo $anchor_css; ?>" <?php echo $title; ?> data-bs-toggle="dropdown" role="button" aria-expanded="false">
    <?php echo $linktype; ?>
</a>