<?php
/**
 * Onestep checkout block
 *
 * @category   Oscheckout
 * @package    Oscheckout
 * @author      Ken Chen <tomyoyo@gmail.com>
 */
class Uplai_Oscheckout_Block_Html_Radio extends Mage_Core_Block_Abstract
{

    protected $_options = array();

    public function getOptions()
    {
        return $this->_options;
    }

    public function setOptions($options)
    {
        $this->_options = $options;
        return $this;
    }

    public function addOption($value, $label, $params=array())
    {
        $this->_options[] = array('value'=>$value, 'label'=>$label);
        return $this;
    }

    public function setId($id)
    {
        $this->setData('id', $id);
        return $this;
    }

    public function setClass($class)
    {
        $this->setData('class', $class);
        return $this;
    }

    public function setTitle($title)
    {
        $this->setData('title', $title);
        return $this;
    }

    public function getId()
    {
        return $this->getData('id');
    }

    public function getClass()
    {
        return $this->getData('class');
    }

    public function getTitle()
    {
        return $this->getData('title');
    }

    protected function _toHtml()
    {
        if (!$this->_beforeToHtml()) {
            return '';
        }
       
        
        $values = $this->getValue();

        if (!is_array($values)){
            if (!is_null($values)) {
                $values = array($values);
            } else {
                $values = array();
            }
        }
		$html = '';
        $isArrayOption = true;
        foreach ($this->getOptions() as $key => $option) {
        	$html .= '<div class="input-box">';
        	$html .= '<input type="radio" name="'.$this->getName().'" id="'.$this->getId().'" class="'
            .$this->getClass().'" title="'.$this->getTitle().'" '.$this->getExtraParams().' value="'.$option['value'].'"';
            if( in_array($value, $values) )
            	$html .= ' checked="checked"';
            $html .= '/>';
            $html .= ' '.$option['label'];
            $html .= '</div>';
        }
       
        return $html;
    }   

    public function getHtml()
    {
        return $this->toHtml();
    }

    public function calcOptionHash($optionValue)
    {
        return sprintf('%u', crc32($this->getName() . $this->getId() . $optionValue));
    }

}
