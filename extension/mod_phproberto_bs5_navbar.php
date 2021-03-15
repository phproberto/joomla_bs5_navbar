<?php

/**
 * @package     Phproberto.Module
 * @subpackage  BS5.Navbar
 *
 * @copyright   Copyright (C) 2021 Roberto Segura López - All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Register prefix for autoloading
JLoader::registerPrefix('Phproberto', __DIR__);

$moduleInstance = new PhprobertoModuleBs5_Navbar($params);

echo $moduleInstance->render(
	str_replace('_:', '', $params->get('layout', '_:default', 'string')),
	array(
		'module'         => $module,
		'params'         => $params,
		'moduleInstance' => $moduleInstance
	)
);
