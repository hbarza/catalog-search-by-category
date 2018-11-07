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

class Codnitive_SearchByCat_Model_System_Config_Source_Parent extends Mage_Core_Model_Config_Data
{

	const ROOT_VALUE     = 'root';
	const CURRENT_VALUE  = 'current';
	const SIBLINGS_VALUE = 'siblings';
	const CHILDREN_VALUE = 'children';

	protected $_options;

	public function toOptionArray()
	{
		if (!isset($this->_options)) {
			$options = array(
				array(
					'value' => self::ROOT_VALUE,
					'label' => Mage::helper('searchbycat')->__('Store Base'),
				),
				array(
					'value' => self::CURRENT_VALUE,
					'label' => Mage::helper('searchbycat')->__('Current Category and Children'),
				),
				array(
					'value' => self::SIBLINGS_VALUE,
					'label' => Mage::helper('searchbycat')->__('Siblings of Current Category'),
				),
				array(
					'value' => self::CHILDREN_VALUE,
					'label' => Mage::helper('searchbycat')->__('Children of Current Category'),
				),
			);
			$this->_options = $options;
		}
		return $this->_options;
	}

}
