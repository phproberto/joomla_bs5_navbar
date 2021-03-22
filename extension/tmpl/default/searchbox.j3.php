<?php

/**
 * @package     Phproberto.Module
 * @subpackage  BS5.Navbar
 *
 * @copyright   Copyright (C) 2021 Roberto Segura López - All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Router\Route;
use Joomla\CMS\Factory;

extract($displayData);

$app = Factory::getApplication();
$currentSearchword = urldecode($app->input->getString('searchword'));


$customBackgroundColor = $params->get('searchbox_button_bg_color_custom', '#10223E');
$useCustomBackgroundColor = $params->get('searchbox_button_bg_color', 'bg-secondary') === 'custom';
$customTextColor = $params->get('searchbox_button_text_color_custom', '#10223E');
$useCustomTextColor = $params->get('searchbox_button_text_color', 'bg-light') === 'custom';
$placeHolder = $params->get('searchbox_placeholder', JText::_('MOD_PHPROBERTO_BS5_NAVBAR_PARAM_SEARCHBOX_PLACEHOLDER_DEFAULT'));
?>
<form class="d-flex" action="<?php echo Route::_('index.php'); ?>" method="post">
    <input type="hidden" name="task" value="search" />
    <input type="hidden" name="option" value="com_search" />
    <div class="input-group">
        <input class="form-control" name="searchword" type="search" placeholder="<?= $placeHolder ?>" aria-label="<?= $placeHolder ?>" value="<?= $this->escape($currentSearchword) ?>">
        <?php if ($params->get('show_search_button', '1') === '1') : ?>
            <button class="btn <?= $params->get('searchbox_button_bg_color', 'bg-secondary') ?> <?= $params->get('searchbox_button_text_color', 'bg-light') ?>" <?php if ($useCustomBackgroundColor) : ?> style="background-color: <?= $customBackgroundColor ?>" <?php endif; ?> type="submit">Search</button>
        <?php endif ?>
    </div>
</form>