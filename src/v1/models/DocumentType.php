<?php

require_once __DIR__ . '/ModelValidation.php';
require_once __DIR__ . '/iModel.php';

class DocumentType implements iModel
{
    private $id, $name;

    /**
     * DocumentType constructor.
     * @param $id
     * @param $name
     */
    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets name if it is valid, return the n first characters if it is too long
     * @param $name
     */
    public function setName($name)
    {
        if (strlen($name) > ModelValidation::NAME_MAX_LENGTH) {
            $this->name = ModelValidation::getValidName($name);
            echo "Name is too long, set to: " . $this->name;
        }
        else {
            $this->name = $name;
        }
    }

    /**
     * Returns associated array representation of model
     * @return array
     */
    public function toArray()
    {
        // TODO: Implement toArray() method.
    }
}