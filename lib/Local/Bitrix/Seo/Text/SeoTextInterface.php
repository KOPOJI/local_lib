<?php

namespace Local\Bitrix\Seo\Text;


interface SeoTextInterface
{
    /**
     * @return SeoTextContentInterface
     */
    public function getContent();
    
    /**
     * @return string|null
     */
    public function getUri();

    /**
     * @param string $uri
     * @return mixed
     */
    public function setUri($uri);
}