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

class Codnitive_SearchByCat_Model_Advanced extends Mage_CatalogSearch_Model_Advanced
{

	protected function _getConfig()
	{
		return Mage::getModel('searchbycat/config');
	}

	protected function _getHelper()
	{
		return Mage::helper('searchbycat');
	}

	protected function _getSearchedCategories()
	{
		return Mage::app()->getFrontController()->getAction()->getRequest()
				->getParam($this->_getHelper()->getCategoryParamName());
	}

	private function _getCondition()
	{
		return (!$this->_getConfig()->isActive() || !$this->_getConfig()->activeInAdvancedSearch());
	}

	public function getSearchCriterias()
	{
		if ($this->_getCondition()) {
			return parent::getSearchCriterias();
		}

		$value = array();
		if ($categories = $this->_getSearchedCategories()) {
			foreach ($categories as $catId){
				$category = Mage::getModel('catalog/category')->load($catId);
				if ($category->getId()) {
					$value[]  = $category->getName();
				}
			}
		}
		if (empty($value)) {
			$value[] = $this->_getConfig()->getSelectText();
		}
		$this->_searchCriterias[] = array('name' => $this->_getConfig()->getAdvanceSearchLabel(), 'value' => implode(', ', $value));

		return parent::getSearchCriterias();
	}

	public function getProductCollection()
	{
		parent::getProductCollection();

		if ($this->_getCondition()) {
			return $this->_productCollection;
		}

		if ($categories = $this->_getSearchedCategories()) {
			foreach ($categories as $catId) {
				if (is_numeric($catId)) {
					$this->_productCollection->addCategoryFilter(Mage::getModel('catalog/category')->load($catId), true);
				}
			}
		}

		return $this->_productCollection;
	}

}
