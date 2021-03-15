<?php

/**
 * @package     Phproberto.Module
 * @subpackage  BS5.Navbar
 *
 * @copyright   Copyright (C) 2021 Roberto Segura López - All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;

extract($displayData);

$doc = Factory::getDocument();

if ($params->get('load_bootstrap_css', 'cdn') === 'cdn') {
    $doc->addStyleSheet('https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css');
} elseif ($params->get('load_bootstrap_css', 'cdn') === 'local') {
    $doc->addStyleSheet(rtrim(Uri::root(), '/') . '/media/mod_phproberto_bs5_navbar/css/bootstrap.min.css');
}

if ($params->get('load_bootstrap_js', 'cdn') === 'cdn') {
    $doc->addScript('https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js');
} elseif ($params->get('load_bootstrap_js', 'cdn') === 'local') {
    $doc->addScript(rtrim(Uri::root(), '/') . '/media/mod_phproberto_bs5_navbar/js/bootstrap.bundle.min.js');
}

$id = '';

if (($tagId = $params->get('tag_id', ''))) {
    $id = ' id="' . $tagId . '"';
}

$moduleClass = htmlspecialchars($params->get('moduleclass_sfx', ''), ENT_COMPAT, 'UTF-8');

$customBackgroundColor = $params->get('bg_color_custom', '#10223E');
$useCustomBackgroundColor = $params->get('bg_color', 'bg-secondary') === 'custom';
?>
<nav class="navbar navbar-expand-lg <?= $params->get('color_scheme', 'navbar-light') ?> <?= $params->get('bg_color', 'bg-secondary') ?> <?php echo $moduleClass; ?> " <?php if ($useCustomBackgroundColor) : ?> style="background-color: <?= $customBackgroundColor ?>" <?php endif; ?>>
    <div class="container">
        <?= $moduleInstance->render('default.modules', array_merge($displayData, ['position' => $module->position . '-navbar-start'])) ?>
        <?php if ($params->get('show_brand', '1') === '1') : ?>
            <?= $this->sublayout('brand', $displayData) ?>
        <?php endif; ?>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div id="navbar" class="navbar-collapse collapse">
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
            <?php if ($params->get('show_search', '1') === '1') : ?>
                <?= $moduleInstance->render('default.searchbox', $displayData) ?>
            <?php endif; ?>
            <?= $moduleInstance->render('default.modules', array_merge($displayData, ['position' => $module->position . '-navbar-end'])) ?>
        </div>
    </div>
</nav>