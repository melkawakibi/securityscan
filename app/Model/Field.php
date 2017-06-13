<?php

namespace App\Model;

use Symfony\Component\DomCrawler\Field\FormField;

class Field extends FormField
{
	/**
     * @var \DOMElement
     */
    protected $inputfield;

	public function __construct($inputfield){

		$this->inputfield = $inputfield;
	}

	/**
     * Returns the node of the field.
     *
     * @return DOMElement The node of the field
     */
    public function getNode()
    {
        return $this->inputfield->node;
    }

    /**
     * Returns the node of the field.
     *
     * @return DOMElement The node of the field
     */
    public function getType()
    {
        return $this->getNode()->getAttribute('type');
    }

	/**
     * Returns null.
     *
     * @return null
     */
	protected function initialize(){
		return null;
	}

}