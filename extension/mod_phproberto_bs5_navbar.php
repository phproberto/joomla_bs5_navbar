<?php

/**
 * @package     Phproberto.Module
 * @subpackage  BS5.Navbar
 *
 * @copyright   Copyright (C) 2021 Roberto Segura López - All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once __DIR__ . '/vendor/autoload.php';

use Phproberto\Joomla\Module\Bootstrap5\Navbar\Module;

$moduleInstance = new Module($params, $module);

$layout = str_replace('_:', '', $params->get('layout', '_:default', 'string'));

echo $moduleInstance->render($layout);
