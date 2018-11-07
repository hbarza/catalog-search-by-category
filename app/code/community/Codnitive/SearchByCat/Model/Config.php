<?php
/**
 * CODNITIVE
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE_EULA.html.
 * It is also available through the world-wide-web at this URL:
 * http://www.codnitive.com/en/terms-of-service-softwares/
 * http://www.codnitive.com/fa/terms-of-service-softwares/
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade to newer
 * versions in the future.
 *
 * @category   Codnitive
 * @package    Codnitive_SearchByCat
 * @author     Hassan Barza <support@codnitive.com>
 * @copyright  Copyright (c) 2012 CODNITIVE Co. (http://www.codnitive.com)
 * @license    http://www.codnitive.com/en/terms-of-service-softwares/ End User License Agreement (EULA 1.0)
 */

class Codnitive_SearchByCat_Model_Config
{

	const PATH_NAMESPACE      = 'codnitivecatalog';
	const EXTENSION_NAMESPACE = 'searchbycat';

	const EXTENSION_NAME    = 'Catalog Search by Category';
	const EXTENSION_VERSION = '1.0.00';
	const EXTENSION_EDITION = 'Basic';

	protected $_maxDepth;
	protected $_categoryParent;

	public static function getNamespace()
	{
		return self::PATH_NAMESPACE . '/' . self::EXTENSION_NAMESPACE . '/';
	}

	public function getExtensionName()
	{
		return self::EXTENSION_NAME;
	}

	public function getExtensionVersion()
	{
		return self::EXTENSION_VERSION;
	}

	public function getExtensionEdition()
	{
		return self::EXTENSION_EDITION;
	}

	public function isActive()
	{
		return Mage::getStoreConfigFlag(self::getNamespace() . 'active');
	}

	public function autoSelectCurrentCategory()
	{
		return Mage::getStoreConfigFlag(self::getNamespace() . 'auto_select_current_category');
	}

	public function setMaxDepth($maxDepth = null)
	{
		if (null === $maxDepth) {
			$maxDepth = Mage::getStoreConfig(self::getNamespace() . 'max_depth');
		}

		$this->_maxDepth = $maxDepth;
		return $this;
	}

	public function getMaxDepth()
	{
		if (null === $this->_maxDepth) {
			$this->setMaxDepth();
		}
		return $this->_maxDepth;
	}

	public function getIndentation()
	{
		return Mage::getStoreConfig(self::getNamespace() . 'indentation');
	}

	public function disableAjaxSuggest()
	{
		if (!$this->isActive()) {
			return false;
		}
		return Mage::getStoreConfigFlag(self::getNamespace() . 'ajax_suggest');
	}

	public function setCategoryParent($parent = null)
	{
		if (null === $parent) {
			$parent = Mage::getStoreConfig(self::getNamespace() . 'category_parent');
		}

		$this->_categoryParent = $parent;
		return $this;
	}

	public function getCategoryParent()
	{
		if (null === $this->_categoryParent) {
			$this->setCategoryParent();
		}
		return $this->_categoryParent;
	}

	public function getChildrenAuto()
	{
		return Mage::getStoreConfig(self::getNamespace() . 'children_auto');
	}

	public function getSearchDefaultText()
	{
		return Mage::getStoreConfig(self::getNamespace() . 'search_def_txt');
	}

	public function getSelectText()
	{
		return Mage::getStoreConfig(self::getNamespace() . 'select_txt');
	}

	public function getBtnText()
	{
		return Mage::getStoreConfig(self::getNamespace() . 'btn_txt');
	}

	public function activeInAdvancedSearch()
	{
		if (!$this->isActive()) {
			return false;
		}
		return Mage::getStoreConfigFlag(self::getNamespace() . 'advanced_active');
	}

	public function getAdvanceSearchLabel()
	{
		return Mage::getStoreConfig(self::getNamespace() . 'advnaced_label');
	}

}
