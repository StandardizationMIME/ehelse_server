<?php

/**
 * ApplicationError massage
 */
class ApplicationError
{
    protected $title, $message, $response_code;

    /**
     * ApplicationError constructor.
     * @param $title
     * @param $message
     */
    public function __construct($title, $message)
    {
        $this->title = $title;
        $this->message = $message;
    }

    /**
     * Returns response code
     * @return mixed
     */
    public function getResponseCode()
    {
        return $this->response_code;
    }

    /**
     * toString
     * @return string
     */
    public function __toString()
    {
        return $this->title . "<br>" . $this->message;
    }

    /**
     * Returns JSON representation
     * @return string
     */
    public function toJSON()
    {
        return json_encode(array(
            "title" => $this->title,
            "message" => $this->message
        ), JSON_PRETTY_PRINT);
    }

}