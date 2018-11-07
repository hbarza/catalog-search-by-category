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

class Codnitive_SearchByCat_Block_Search_Form extends Mage_Core_Block_Template
{

	protected function _construct()
	{
		parent::_construct();

		$template = 'catalogsearch/form.mini.phtml';
		if ($this->_getConfig()->isActive()) {
			$template = 'codnitive/searchbycat/search/form.phtml';
		}
		$this->setTemplate($template);
	}

	protected function _getConfig()
	{
		return Mage::getModel('searchbycat/config');
	}

	public function getOptionsHtml()
	{
		return Mage::getModel('searchbycat/form', array('is_advanced_search' => false))->getOptionsHtml();
	}

}
