<?php

require_once __DIR__ . '/ModelValidation.php';
require_once 'iModel.php';

class DocumentField implements iModel
{
    const SQL_GET_ALL = "SELECT * FROM document_field ORDER BY sequence;";
    const SQL_GET_BY_ID = "SELECT * FROM document_field WHERE id = :id;";
    const SQL_INSERT = "INSERT INTO document_field VALUES (null, :name, :description, :sequence, :mandatory,
      :document_type_id);";
    const SQL_UPDATE = "UPDATE document_field SET name = :name, description = :description, sequence = :sequence,
      mandatory = :mandatory, document_type_id = :document_type_id WHERE id = :id;";
    const SQL_DELETE = "DELETE FROM document_field WHERE id = :id";

    const REQUIRED_POST_FIELDS = ['name', 'sequence', 'mandatory', 'documentTypeId'];
    const REQUIRED_PUT_FIELDS = ['name', 'sequence', 'mandatory', 'documentTypeId'];

    private $id, $name, $description, $sequence, $mandatory, $document_type_id;

    /**
     * DocumentField constructor.
     * @param $id
     * @param $name
     * @param $description
     * @param $sequence
     * @param $mandatory
     * @param $document_type_id
     */
    public function __construct($id, $name, $description, $sequence, $mandatory, $document_type_id)
    {
        $this->id = $id;
        $this->setName($name);
        $this->setDescription($description);
        $this->sequence = $sequence;
        $this->mandatory = $mandatory;
        $this->document_type_id = $document_type_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = ModelValidation::getValidName($name);
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = ModelValidation::getValidDescription($description);
    }

    public function getSequence()
    {
        return $this->sequence;
    }

    public function setSequence($sequence)
    {
        $this->sequence = $sequence;
    }

    public function getMandatory()
    {
        return $this->mandatory;
    }

    public function setMandatory($mandatory)
    {
        $this->mandatory = $mandatory;
    }

    public function getDocumentTypeId()
    {
        return $this->document_type_id;
    }

    public function setDocumentTypeId($document_type_id)
    {
        $this->document_type_id = $document_type_id;
    }

    /**
     * Returns associated array representation of model
     * @return array
     */
    public function toArray()
    {
        $assoc = array(
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'sequence' => $this->sequence,
            'mandatory' => $this->mandatory,
            'documentTypeId' => $this->document_type_id);
        return $assoc;
    }

    /**
     * Returns JSON representation of model
     * @return string
     */
    public function toJSON()
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Returns model from JSON
     * @param $json
     * @return DocumentField
     */
    public static function fromJSON($json)
    {
        return new DocumentField(
            getValueFromArray($json, 'id'),
            getValueFromArray($json, 'name'),
            getValueFromArray($json, 'description'),
            getValueFromArray($json, 'sequence'),
            getValueFromArray($json, 'mandatory'),
            getValueFromArray($json, 'documentTypeId')
        );
    }

    /**
     * Returns model from db array
     * @param $db_array
     * @return DocumentField
     */
    public static function fromDBArray($db_array)
    {
        return new DocumentField(
            $db_array['id'],
            $db_array['name'],
            $db_array['description'],
            $db_array['sequence'],
            $db_array['mandatory'],
            $db_array['document_type_id']
        );
    }

    /**
     * Returns associative array for sql querying
     * @return array
     */
    public function toDBArray()
    {
        $db_array = array(
            ':name' => $this->name,
            ':description' => $this->description,
            ':sequence' => $this->sequence,
            ':mandatory' => $this->mandatory,
            ':document_type_id' => $this->document_type_id
        );
        if ($this->id) {
            $db_array[':id'] = $this->id;
        }
        return $db_array;
    }
}