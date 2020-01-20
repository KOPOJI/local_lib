<?php
/**
 * example of use:
 * need to run `composer install` before
 */
require_once 'vendor/autoload.php';

$seoTemplate = new \Local\Bitrix\Seo\Text\SeoText(
    \Local\Bitrix\Seo\Text\SeoTextFactory::create()
);

var_dump($seoTemplate->getSeoData());

/**
 * example of json:
 */
/*
{
    "title": "{{element_name}} — купить в Москве по цене {{price}}, заказать с доставкой {{element_name}} в интернет-магазине SiteRu",
  "h1": "{{category_name}} {{element_name}}",
  "description": "Купить {{element_name}} по выгодной цене {{price}} в Москве и Московской области. Интернет-магазин SiteRu: доставка {{category_name}} {{element_name}}: инструкция по применению, показания и противопоказания, отзывы.",
  "topText": "",
  "bottomText": ""
}*/