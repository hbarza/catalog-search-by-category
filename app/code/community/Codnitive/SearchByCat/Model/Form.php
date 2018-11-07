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

class Codnitive_SearchByCat_Model_Form extends Mage_Core_Model_Abstract
{

	protected $_config;
	protected $_parentType;
	protected $_isAdvancedSearch;

	public function __construct($data)
	{
		$this->_parentType = $this->_getConfig()->getCategoryParent();
		$this->_isAdvancedSearch = isset($data['is_advanced_search']) ? (bool) $data['is_advanced_search'] : false;
	}

	protected function _getConfig()
	{
		if (null === $this->_config) {
			$this->_config = Mage::getModel('searchbycat/config');
		}
		return $this->_config;
	}

	protected function _getHelper()
	{
		return Mage::helper('searchbycat');
	}

	public function getCategories($category = null)
	{
		$idFilter = ($category === null) ? $this->_getParentCategoryId() : $category->getChildren();

		$categories = Mage::getModel('catalog/category')->getCollection()
				->addAttributeToSelect('name')
				->addAttributeToSelect('all_children')
				->addAttributeToFilter('is_active', 1)
				->addAttributeToFilter('include_in_menu', 1)
				->addIdFilter($idFilter)
				->setOrder('position', 'ASC')
				->load();

		return $categories;
	}

	public function getOptionsHtml()
	{
		$html = '';

		if ($this->_isAdvancedSearch) {
			$this->_config->setMaxDepth(0)->setCategoryParent('root');
			$this->_parentType = $this->_getConfig()->getCategoryParent();
		}

		foreach ($this->getCategories() as $category) {
			$selected = ($this->getCurrentCategoryId() == $category->getId()) ? 'selected="selected"' : '';
			$html .= '<option value="' . $category->getId() . '"' . $selected . '>' . $category->getName() . '</option>';

			$children = $category->getChildren();
			if ($children !== '') {
				$html = $this->_getChildrenOptionsHtml($children, $html);
			}
		}

		return $html;
	}

	protected function _getChildrenOptionsHtml($children, $html)
	{
		$catgoriesIds = explode(',', $children);
		$parentCat    = Mage::getModel('catalog/category')->load($this->_getParentCategoryId());
		$maxDepth     = $this->_getConfig()->getMaxDepth();
		$maxLevel     = ($parentCat->getLevel() - 1) + $maxDepth;

		foreach ($catgoriesIds as $id) {
			$category = Mage::getModel('catalog/category')->load($id);
			if (!empty($maxDepth) && $category->getLevel() > $maxLevel) {
				break;
			}
			$selected = ($this->getCurrentCategoryId() == $category->getId()) ? 'selected="selected"' : '';

			$indentation = '';
			for ($i = 1; $i <= $category->getLevel() - $parentCat->getLevel(); $i++) {
				$indentation .= $this->_getConfig()->getIndentation();
			}

			$html .= '<option value="' . $category->getId() . '"' . $selected . '>' . $indentation . $category->getName() . '</option>';

			$children = $category->getChildren();
			if ($children !== '') {
				$html = $this->_getChildrenOptionsHtml($children, $html);
			}
		}

		return $html;
	}

	protected function _getParentCategoryId()
	{
		$type     = $this->_parentType;
		$category = Mage::registry('current_category');
		$root     = Mage::getModel('catalog/category')->load(Mage::app()->getStore()->getRootCategoryId());

		if (!$category) {
			$selectedCatId = Mage::app()->getFrontController()->getAction()->getRequest()->getParam($this->_getHelper()->getCategoryParamName());

			if (!$selectedCatId) {
				return $root->getChildren();
			}

			$category = Mage::getModel('catalog/category')->load($selectedCatId);
			if ($this->_parentType == 'children') {
				$category = Mage::getModel('catalog/category')->load($category->getParentId());
			}
		}

		switch ($type) {
			case 'current':
				$idFilter = $category->getId();
				break;

			case 'siblings':
				$idFilter = $category->getParentId();
				if ($idFilter == Mage::app()->getStore()->getRootCategoryId()) {
					$idFilter = $root->getChildren();
				}
				break;

			case 'children':
				$idFilter = $category->getChildren();
				if (empty($idFilter)) {
					$idFilter = $category->getId();
				}
				break;

			case 'root':
				$idFilter = $root->getChildren();
				break;
		}

		return $idFilter;
	}

	public function getCurrentCategoryId()
	{
		if (!$this->_getConfig()->autoSelectCurrentCategory() && !$this->_isAdvancedSearch) {
			return null;
		}

		$helper = $this->_getHelper();
		$config = $this->_getConfig();

		if ($helper->isCategoryPage()) {
			$currentCat = Mage::registry('current_category');

			if ($this->_parentType == 'children') {
				$chilrenStr = $currentCat->getChildren();
				if (empty($chilrenStr)) {
					return $currentCat->getId();
				}
				$chilren = explode(',', $chilrenStr);
				return ($config->getChildrenAuto() == 'first') ? $chilren[0] : end($chilren);
			}

			$maxDepth = $this->_getConfig()->getMaxDepth();
			if (empty($maxDepth) || $currentCat->getLevel() <= $this->_getMaxLevel($currentCat)) {
				return $currentCat->getId();
			}
			return $this->_getCatIdInCatalogPages($currentCat->getParentId());
		}

		$searchedCatId = Mage::app()->getFrontController()->getAction()->getRequest()->getParam($helper->getCategoryParamName());
		if ($helper->isSearchResultsPage()) {
			return $searchedCatId;
		}

		if ($helper->isAdvancedSearchPage()) {
			return $searchedCatId[0];
		}

		return Mage::app()->getStore()->getRootCategoryId();
	}

	protected function _getCatIdInCatalogPages($catId)
	{
		$category = Mage::getModel('catalog/category')->load($catId);
		if ($category->getLevel() <= $this->_getMaxLevel($category)) {
			return $catId;
		}
		return $this->_getCatIdInCatalogPages($category->getParentId());
	}

	protected function _getMaxLevel($category)
	{
		$max = $this->_getConfig()->getMaxDepth() + 1;
		if ($this->_parentType == 'root' || !$category) {
			return $max;
		}

		return $category->getLevel() + $max;
	}

}
