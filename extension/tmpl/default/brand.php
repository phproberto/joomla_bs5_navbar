<?php

/**
 * @package     Phproberto.Module
 * @subpackage  BS5.Navbar
 *
 * @copyright   Copyright (C) 2021 Roberto Segura López - All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Uri\Uri;

extract($displayData);

$imageUrl = $params->get('brand_image') ?: rtrim(Uri::root(), '/') . '/media/mod_phproberto_bs5_navbar/img/bootstrap-logo.svg';
$imageHeight = $params->get('brand_image_height', '24');
$imageWidth = $params->get('brand_image_width');
?>
<a class="navbar-brand" href="<?= Uri::root() ?>">
    <?php if ($params->get('show_brand_image', '1') === '1') : ?>
        <img src="<?= $imageUrl ?>" alt="" <?php if ($imageWidth) : ?>width="<?= $imageWidth ?>" <?php endif; ?> <?php if ($imageHeight) : ?>height="<?= $imageHeight ?>" <?php endif; ?> />
    <?php endif; ?>
    <?php if ($params->get('show_brand_text', '1') === '1') : ?>
        <?= $params->get('brand_text', 'Navbar') ?>
    <?php endif; ?>
</a>