<?php

/**
 * @package     Phproberto.Module
 * @subpackage  BS5.Navbar
 *
 * @copyright   Copyright (C) 2021 Roberto Segura López - All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;

extract($displayData);

$modules = ModuleHelper::getModules($position);

if (!$modules) {
    return;
}
?>
<?php foreach ($modules as $module) : ?>
    <?php
    // Always avoid rendering module title
    $module->title = ''
    ?>
    <div class="<?= $position ?>">
        <?= ModuleHelper::renderModule($module, ['style' => 'html5']); ?>
    </div>
<?php endforeach;
