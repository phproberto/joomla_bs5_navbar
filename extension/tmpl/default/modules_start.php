<?php

/**
 * @package     Phproberto.Module
 * @subpackage  BS5.Navbar
 *
 * @copyright   Copyright (C) 2021 Roberto Segura López - All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

echo $moduleInstance->render(
    'default.modules',
    array_merge(
        $displayData,
        [
            'position' => $displayData['module']->position . '-navbar-start'
        ]
    )
);
