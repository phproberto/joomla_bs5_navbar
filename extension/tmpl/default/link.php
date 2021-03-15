<?php

/**
 * @package     Phproberto.Module
 * @subpackage  BS5.Navbar
 *
 * @copyright   Copyright (C) 2021 Roberto Segura López - All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

extract($displayData);

if (is_array($attribs)) {
    $attribs = ArrayHelper::toString($attribs);
}

?>
<a href="<?= $url ?>" <?= $attribs ?>><?= $text ?></a>