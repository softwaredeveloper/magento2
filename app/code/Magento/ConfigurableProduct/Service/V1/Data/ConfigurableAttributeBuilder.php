<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @copyright   Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Magento\ConfigurableProduct\Service\V1\Data;

class ConfigurableAttributeBuilder extends \Magento\Framework\Service\Data\AbstractObjectBuilder
{
    /**
     * @param int $value 
     * @return self 
     */
    public function setId($value)
    {
        return $this->_set(ConfigurableAttribute::ID, $value);
    }

    /**
     * @param string $value
     * @return self 
     */
    public function setAttributeId($value)
    {
        return $this->_set(ConfigurableAttribute::ATTRIBUTE_ID, $value);
    }

    /**
     * @param string $value 
     * @return self 
     */
    public function setLabel($value)
    {
        return $this->_set(ConfigurableAttribute::LABEL, $value);
    }

    /**
     * @param bool $value 
     * @return self 
     */
    public function useDefault($value)
    {
        return $this->_set(ConfigurableAttribute::USE_DEFAULT, $value);
    }

    /**
     * @param \Magento\ConfigurableProduct\Service\V1\Data\ConfigurableAttribute\Value[] $value 
     * @return self 
     */
    public function setValues($value)
    {
        return $this->_set(ConfigurableAttribute::VALUES, $value);
    }
}
