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

class Codnitive_SearchByCat_Model_System_Config_Source_Childrenauto extends Mage_Core_Model_Config_Data
{

	const FIRST_CHILD = 'first';
	const LAST_CHILD  = 'last';

	protected $_options;

	public function toOptionArray()
	{
		if (!isset($this->_options)) {
			$options = array(
				array(
					'value' => self::FIRST_CHILD,
					'label' => Mage::helper('searchbycat')->__('First Child'),
				),
				array(
					'value' => self::LAST_CHILD,
					'label' => Mage::helper('searchbycat')->__('Last Child'),
				),
			);
			$this->_options = $options;
		}
		return $this->_options;
	}

}
