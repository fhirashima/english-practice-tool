<?php

namespace Artesys\PracticeToolBundle\Form\Model;


/**
 * Exam
 *
 */
class Exam
{
    /**
     * @var integer
     *
     */
    private $id;

    /**
     * @var string
     *
     */
    private $inputText;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set inputText
     *
     * @param string $inputText
     * @return Exam
     */
    public function setInputText($inputText)
    {
        $this->inputText = $inputText;
    
        return $this;
    }

    /**
     * Get inputText
     *
     * @return string 
     */
    public function getInputText()
    {
        return $this->inputText;
    }
}
