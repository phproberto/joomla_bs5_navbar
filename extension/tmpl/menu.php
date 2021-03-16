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

$id = '';

if (($tagId = $params->get('tag_id', ''))) {
    $id = ' id="' . $tagId . '"';
}
?>

<ul class="navbar-nav me-auto mb-2 mb-lg-0 menu<?php echo $class_sfx; ?>" <?php echo $id; ?>>
    <?php foreach ($list as $i => &$item) {
        $class = 'item-' . $item->id . ' type-' . $item->type;

        if ($item->id == $default_id) {
            $class .= ' default';
        }

        if (($item->id == $active_id) || ($item->type == 'alias' && $item->params->get('aliasoptions') == $active_id)) {
            $class .= ' current';
        }

        if (in_array($item->id, $path)) {
            $class .= ' active';
        } elseif ($item->type == 'alias') {
            $aliasToId = $item->params->get('aliasoptions');

            if (count($path) > 0 && $aliasToId == $path[count($path) - 1]) {
                $class .= ' active';
            } elseif (in_array($aliasToId, $path)) {
                $class .= ' alias-parent-active';
            }
        }

        if ($item->type == 'separator') {
            $class .= ' ';
        }

        if ($item->deeper) {
            $class .= ' deeper';
        }

        if ($item->parent) {
            $class .= ' parent';
        }

        if ($item->deeper) {
            $class .= ' dropdown level-' . $item->level;
        }

        if ((int) $item->level === 1) {
            $class .= ' nav-item';
        }

        echo '<li class="' . $class . '">';
        switch ($item->type):
            case 'component':
                echo $moduleInstance->render(
                    'default.component',
                    array_merge($displayData, ['item' => $item])
                );
                break;
            case 'url':
            default:
                echo $moduleInstance->render(
                    'default.url',
                    array_merge($displayData, ['item' => $item])
                );
                break;
            case 'separator':
            case 'heading':
                echo $moduleInstance->render(
                    'default.separator',
                    array_merge($displayData, ['item' => $item])
                );
                break;
        endswitch;

        // The next item is deeper.
        if ($item->deeper) {
            echo '<ul class="dropdown-menu">';
        }
        // The next item is shallower.
        elseif ($item->shallower) {
            echo '</li>';
            echo str_repeat('</ul></li>', $item->level_diff);
        }
        // The next item is on the same level.
        else {
            echo '</li>';
        }
    }
    ?>
</ul>