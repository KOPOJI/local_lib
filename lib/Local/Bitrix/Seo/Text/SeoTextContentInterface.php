<?php


namespace Local\Bitrix\Seo\Text;


use ArrayAccess;

interface SeoTextContentInterface extends ArrayAccess
{

    /**
     * @return string
     */
    public function getTopText();

    /**
     * @param  string  $topText
     */
    public function setTopText($topText);

    /**
     * @return string
     */
    public function getBottomText();

    /**
     * @param  string  $bottomText
     */
    public function setBottomText($bottomText);

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param  string  $title
     */
    public function setTitle($title);

    /**
     * @return string
     */
    public function getH1();

    /**
     * @param  string  $h1
     */
    public function setH1($h1);

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @param  string  $description
     */
    public function setDescription($description);
}