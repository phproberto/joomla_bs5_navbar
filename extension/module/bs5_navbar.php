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
use Joomla\CMS\Layout\FileLayout;
use Joomla\Registry\Registry;

/**
 * Module class
 *
 * @since  __DEPLOY_VERSION__
 */
final class PhprobertoModuleBs5_Navbar
{
    /**
     * Base overridable renderer
     *
     * @var  FileLayout
     */
    protected $defaultBaseRenderer;

    /**
     * Name of the module folder. Ex: mod_phproberto_social
     *
     * @var    string
     * @since  1.0
     */
    protected $folderName;

    /**
     * @var    JRegistry
     * @since  1.0
     */
    protected $params;

    /**
     * DB row data that represents a specific instance.
     *
     * @var \stdClass
     */
    protected $module;

    /**
     * @var  RendererInterface
     */
    protected $renderer;

    /**
     * Constructor
     *
     * @param   mixed  $params  array or Registry with the module parameters
     * @param   mixed  $module  Data if the module represents a specific DB item
     */
    public function __construct($params = array(), $module = null)
    {
        $this->setParams($params);
        $this->module = $module;
    }

    /**
     * Get active menu item.
     *
     * @return  object
     */
    protected function getActiveMenuItem()
    {
        $menu = Factory::getApplication()->getMenu();

        return $menu->getActive() ?: $this->getDefaultMenuItem();
    }

    /**
     * Get base menu item.
     *
     * @return  object
     */
    protected function getBaseMenuItem()
    {
        $params = $this->getParams();

        // Get base menu item from parameters
        if ($params->get('base')) {
            $base = Factory::getApplication()->getMenu()->getItem($params->get('base'));
        } else {
            $base = false;
        }

        // Use active menu item if no base found
        if (!$base) {
            $base = $this->getActiveMenuItem();
        }

        return $base;
    }

    /**
     * Get default menu item (home page) for current language.
     *
     * @return  object
     */
    public static function getDefaultMenuItem()
    {
        $menu = JFactory::getApplication()->getMenu();
        $lang = JFactory::getLanguage();

        // Look for the home menu
        if (JLanguageMultilang::isEnabled()) {
            return $menu->getDefault($lang->getTag());
        }

        return $menu->getDefault();
    }

    /**
     * Get the ID of the module.
     *
     * @return string
     */
    public function getId()
    {
        $params = $this->getParams();

        $idParam = $params->get('tag_id');

        if ($idParam) {
            return $idParam;
        }

        $prefix = $this->getFolderName() . '-';

        if ($this->module && property_exists($this->module, 'id')) {
            return $prefix . $this->module->id;
        }

        return uniqid($prefix);
    }

    /**
     * Get the layout paths for this view
     *
     * @return  array
     */
    public function getModuleLayoutPaths()
    {
        $template  = Factory::getApplication()->getTemplate();

        return array(
            JPATH_THEMES . '/' . $template . '/html/' . $this->getFolderName(),
            JPATH_SITE . '/modules/' . $this->getFolderName() . '/tmpl'
        );
    }

    public function getDefaultLayoutData()
    {
        $active = $this->getActiveMenuItem();
        $base = $this->getBaseMenuItem();
        $default = $this->getDefaultMenuItem();
        $params = $this->getParams();

        return [
            'active_id' => $active->id,
            'base' => $base,
            'class_sfx' => htmlspecialchars($params->get('class_sfx'), ENT_COMPAT, 'UTF-8'),
            'default_id' => $default->id,
            'id' => $this->getId(),
            'list' => $this->getMenuItems(),
            'module' => $this->module,
            'moduleClass' => htmlspecialchars($params->get('moduleclass_sfx', ''), ENT_COMPAT, 'UTF-8'),
            'moduleInstance' => $this,
            'params' => $params,
            'path' => $base->tree,
            'showAll' => $params->get('showAllChildren', 1),
        ];
    }

    /**
     * Get a list of the menu items.
     *
     * @return  array
     */
    protected function getMenuItems()
    {
        $params = $this->getParams();
        $app = Factory::getApplication();
        $menu = $app->getMenu();

        // Get active menu item
        $base = $this->getBaseMenuItem();
        $user = Factory::getUser();
        $levels = $user->getAuthorisedViewLevels();
        asort($levels);
        $key = 'menu_items' . $params . implode(',', $levels) . '.' . $base->id;
        $cache = Factory::getCache('mod_menu', '');

        if ($cache->contains($key)) {
            $items = $cache->get($key);
        } else {
            $path           = $base->tree;
            $start          = (int) $params->get('startLevel', 1);
            $end            = (int) $params->get('endLevel', 0);
            $showAll        = $params->get('showAllChildren', 1);
            $items          = $menu->getItems('menutype', $params->get('menutype'));
            $hidden_parents = array();
            $lastitem       = 0;

            if ($items) {
                foreach ($items as $i => $item) {
                    $item->parent = false;

                    if (isset($items[$lastitem]) && $items[$lastitem]->id == $item->parent_id && $item->getParams()->get('menu_show', 1) == 1) {
                        $items[$lastitem]->parent = true;
                    }

                    if (($start && $start > $item->level)
                        || ($end && $item->level > $end)
                        || (!$showAll && $item->level > 1 && !in_array($item->parent_id, $path))
                        || ($start > 1 && !in_array($item->tree[$start - 2], $path))
                    ) {
                        unset($items[$i]);
                        continue;
                    }

                    // Exclude item with menu item option set to exclude from menu modules
                    if (($item->getParams()->get('menu_show', 1) == 0) || in_array($item->parent_id, $hidden_parents)) {
                        $hidden_parents[] = $item->id;
                        unset($items[$i]);
                        continue;
                    }

                    $item->deeper     = false;
                    $item->shallower  = false;
                    $item->level_diff = 0;

                    if (isset($items[$lastitem])) {
                        $items[$lastitem]->deeper     = ($item->level > $items[$lastitem]->level);
                        $items[$lastitem]->shallower  = ($item->level < $items[$lastitem]->level);
                        $items[$lastitem]->level_diff = ($items[$lastitem]->level - $item->level);
                    }

                    $lastitem     = $i;
                    $item->active = false;
                    $item->flink  = $item->link;

                    // Reverted back for CMS version 2.5.6
                    switch ($item->type) {
                        case 'separator':
                            break;

                        case 'heading':
                            // No further action needed.
                            break;

                        case 'url':
                            if ((strpos($item->link, 'index.php?') === 0) && (strpos($item->link, 'Itemid=') === false)) {
                                // If this is an internal Joomla link, ensure the Itemid is set.
                                $item->flink = $item->link . '&Itemid=' . $item->id;
                            }
                            break;

                        case 'alias':
                            $item->flink = 'index.php?Itemid=' . $item->getParams()->get('aliasoptions');

                            // Get the language of the target menu item when site is multilingual
                            if (JLanguageMultilang::isEnabled()) {
                                $newItem = Factory::getApplication()->getMenu()->getItem((int) $item->getParams()->get('aliasoptions'));

                                // Use language code if not set to ALL
                                if ($newItem != null && $newItem->language && $newItem->language !== '*') {
                                    $item->flink .= '&lang=' . $newItem->language;
                                }
                            }
                            break;

                        default:
                            $item->flink = 'index.php?Itemid=' . $item->id;
                            break;
                    }

                    if ((strpos($item->flink, 'index.php?') !== false) && strcasecmp(substr($item->flink, 0, 4), 'http')) {
                        $item->flink = JRoute::_($item->flink, true, $item->getParams()->get('secure'));
                    } else {
                        $item->flink = JRoute::_($item->flink);
                    }

                    // We prevent the double encoding because for some reason the $item is shared for menu modules and we get double encoding
                    // when the cause of that is found the argument should be removed
                    $item->title          = htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8', false);
                    $item->anchor_css     = htmlspecialchars($item->getParams()->get('menu-anchor_css', ''), ENT_COMPAT, 'UTF-8', false);
                    $item->anchor_title   = htmlspecialchars($item->getParams()->get('menu-anchor_title', ''), ENT_COMPAT, 'UTF-8', false);
                    $item->anchor_rel     = htmlspecialchars($item->getParams()->get('menu-anchor_rel', ''), ENT_COMPAT, 'UTF-8', false);
                    $item->menu_image     = $item->getParams()->get('menu_image', '') ?
                        htmlspecialchars($item->getParams()->get('menu_image', ''), ENT_COMPAT, 'UTF-8', false) : '';
                    $item->menu_image_css = htmlspecialchars($item->getParams()->get('menu_image_css', ''), ENT_COMPAT, 'UTF-8', false);
                }

                if (isset($items[$lastitem])) {
                    $items[$lastitem]->deeper     = (($start ?: 1) > $items[$lastitem]->level);
                    $items[$lastitem]->shallower  = (($start ?: 1) < $items[$lastitem]->level);
                    $items[$lastitem]->level_diff = ($items[$lastitem]->level - ($start ?: 1));
                }
            }

            $cache->store($items, $key);
        }

        return $items;
    }

    /**
     * Get the standard renderer and setup paths
     *
     * @return  FileLayout    Renderer instance
     */
    public function getRenderer($layout = null)
    {
        $params = $this->getParams();

        $renderer = new FileLayout($layout);

        $debugMode = (bool) $params->get('debug', false);
        $renderer->setDebug($debugMode);

        $renderer->setIncludePaths($this->getModuleLayoutPaths());

        return $renderer;
    }

    /**
     * Get current instance name
     *
     * Example: in class "PhprobertoModuleSocial" it will return "social"
     *
     * @return  string
     */
    protected function getInstanceName()
    {
        $class = get_class($this);

        $name = strstr($class, 'Module');
        $name = str_replace('Module', '', $name);

        return strtolower($name);
    }

    /**
     * Get the module folder name
     *
     * @return  string
     */
    protected function getFolderName()
    {
        if (null === $this->folderName) {
            $class = get_class($this);

            $prefix = strstr($class, 'Module', true);

            $this->folderName = 'mod_' . strtolower($prefix) . '_' . $this->getInstanceName();
        }

        return $this->folderName;
    }

    /**
     * Get the module parameters
     *
     * @return  JRegistry
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Render the module
     *
     * @param   array  $data  Data for the layout
     *
     * @return  string
     */
    public function render($layout = null, $data = array())
    {
        return $this->getRenderer($layout)->render(array_merge($this->getDefaultLayoutData(), $data));
    }

    /**
     * Render the module
     *
     * @param   array  $data  Data for the layout
     *
     * @return  string
     */
    public function renderDebug($layout = null, $data = array())
    {
        return $this->getRenderer($layout)->debug(array_merge($this->getDefaultLayoutData(), $data));
    }

    /**
     * Get the module parameters
     *
     * @param   mixed  $params  array or Registry witht the module parameters
     *
     * @return  self
     */
    public function setParams($params)
    {
        if (is_array($params) || is_string($params)) {
            $this->params = new Registry($params);
        }

        if ($params instanceof \JRegistry || $params instanceof Registry) {
            $this->params = $params;
        } else {
            $this->params = new Registry;
        }

        return $this;
    }
}
