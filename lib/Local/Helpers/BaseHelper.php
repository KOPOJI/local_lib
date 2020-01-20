<?php

namespace Local\Helpers;

use Local\Bitrix\Data\Request;
use Local\Bitrix\Data\Server;

/**
 * Class BaseHelper
 * @package Local\Helpers
 */
abstract class BaseHelper
{
    /**
     * Return word with needed ending for given number
     *
     * @param $n
     * @param array $items
     *
     * @return bool|string
     */
    public static function pluralize($n, array $items)
    {
        if(!isset($items[0], $items[1], $items[2]))
            return false;
        if($n % 10 === 1 && $n % 100 !== 11)
            return $items[0];
        if($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 > 20))
            return $items[1];
        return $items[2];
    }

    /**
     * Return word with needed ending for given number, with number & split symbol
     *
     * @param $n
     * @param array $items
     * @param string $splitSym
     *
     * @return bool|mixed
     */
    public static function pluralizeN($n, array $items, $splitSym = ' ')
    {
        return $n . $splitSym . static::pluralize($n, $items);
    }

    /**
     * Wrapper of htmlspecialchars
     *
     * @param $value
     * @param $flags
     *
     * @return string
     */
    public static function enc($value, $flags = ENT_COMPAT)
    {
        static $htmlFilterExists = null;
        if(!isset($htmlFilterExists))
            $htmlFilterExists = class_exists('\\Bitrix\\Main\\Text\\HtmlFilter');
        return $htmlFilterExists ? \Bitrix\Main\Text\HtmlFilter::encode($value, $flags) : htmlspecialcharsbx($value, $flags);
    }

    /**
     * Return json_encoded data
     *
     * @param $data
     * @param $options
     * @param $depth
     *
     * @return bool|string
     */
    public static function getJson($data, $options = 0, $depth = 512)
    {
        return json_encode($data, $options, $depth);
    }

    /**
     * Return encoded string or false
     *
     * @param $value
     *
     * @return bool|string
     */
    public static function utf8ToCP1251($value)
    {
        return iconv('UTF-8', 'Windows-1251', $value);
    }

    /**
     * Return full url with https? & server name for specified path
     *
     * @param $url
     *
     * @return string
     */
    public static function getFullUrl($url)
    {
        return 'http' . (Request::get()->isHttps() ? 's' : '') . '://' . Server::get()->getServerName() . (0 === strpos($url, '/') ? $url : '/' . $url);
    }

    /**
     * Return image with specified watermark
     *
     * @param array $element
     * @param array $watermarkParams
     * @param int   $resizeType
     *
     * @return array|bool
     */
    public static function getImageWatermark(array $element = array(), array $watermarkParams = array(), $resizeType = BX_RESIZE_IMAGE_PROPORTIONAL)
    {
        if(!isset($element['ID'], $element['WIDTH'], $element['HEIGHT'], $watermarkParams['file']))
            return false;

        $defParams = array(
            'name' => 'watermark',
            'position' => 'center',
            'size' => 'real',
        );
        foreach($watermarkParams as $k => $v)
            $defParams[$k] = $v;

        $arWaterMark = array($defParams);

        return \CFile::ResizeImageGet(
            $element['ID'],
            array('width' => $element['WIDTH'], 'height' => $element['HEIGHT']),
            $resizeType,
            true,
            $arWaterMark
        );
    }

    /**
     * Return image path with specified watermark
     *
     * @param array $element
     * @param array $watermarkParams
     * @param int   $resizeType
     *
     * @return bool|string
     */
    public static function getImageWatermarkSrc(array $element = array(), array $watermarkParams = array(), $resizeType = BX_RESIZE_IMAGE_PROPORTIONAL)
    {
        $data = static::getImageWatermark($element, $watermarkParams, $resizeType);
        return isset($data['src']) ? $data['src'] : false;
    }

    /**
     * Check prop empty
     *
     * @param $code
     * @param array $data
     * @param string $propArrKey
     *
     * @return bool
     */
    public static function propEmpty($code, array $data = array(), $propArrKey = 'PROPERTIES')
    {
        return empty($data[$propArrKey][$code]['VALUE']);
    }

    /**
     * Check prop filled
     *
     * @param $code
     * @param array $data
     * @param string $propArrKey
     *
     * @return bool
     */
    public static function propFilled($code, array $data = array(), $propArrKey = 'PROPERTIES')
    {
        return !static::propEmpty($code, $data, $propArrKey);
    }

    /**
     * Returns array of prop data (or empty array)
     *
     * @param $code
     * @param array $data
     * @param string $propArrKey
     *
     * @return array
     */
    public static function prop($code, array $data = array(), $propArrKey = 'PROPERTIES')
    {
        return isset($data[$propArrKey][$code]) ? $data[$propArrKey][$code] : array();
    }

    /**
     * Returns value of given prop (or null)
     *
     * @param $code
     * @param array $data
     * @param string $propArrKey
     *
     * @return null|string
     */
    public static function propValue($code, array $data = array(), $propArrKey = 'PROPERTIES')
    {
        return isset($data[$propArrKey][$code]['VALUE']) ? $data[$propArrKey][$code]['VALUE'] : null;
    }

    /**
     * Returns encoded value of given prop (or null)
     *
     * @param $code
     * @param array $data
     * @param string $propArrKey
     * @param int $encodeFlags
     *
     * @return null|string
     */
    public static function escPropValue($code, array $data = array(), $propArrKey = 'PROPERTIES', $encodeFlags = ENT_COMPAT)
    {
        $value = static::propValue($code, $data, $propArrKey);
        return empty($value) ? null : static::enc($value, $encodeFlags);
    }

    /**
     * @param $price
     * @param string $addPrice
     *
     * @return string
     */
    public static function formatPrice($price, $addPrice = ' руб')
    {
        return number_format( ceil($price), 0, ' ', ' ') . $addPrice;
    }

    /**
     * @param $text
     * @return string
     */
    public static function translit($text) {
        return strtr(
            $text,
            array(
                'а'=>'a', 'б'=>'b', 'в'=>'v', 'г'=>'g', 'д'=>'d', 'е'=>'e', 'ж'=>'g', 'з'=>'z', 'и'=>'i', 'й'=>'y', 'к'=>'k', 'л'=>'l', 'м'=>'m', 'н'=>'n', 'о'=>'o', 'п'=>'p', 'р'=>'r', 'с'=>'s', 'т'=>'t', 'у'=>'u', 'ф'=>'f', 'ы'=>'i', 'э'=>'e', 'А'=>'A', 'Б'=>'B', 'В'=>'V', 'Г'=>'G', 'Д'=>'D', 'Е'=>'E', 'Ж'=>'G', 'З'=>'Z', 'И'=>'I', 'Й'=>'Y', 'К'=>'K', 'Л'=>'L', 'М'=>'M', 'Н'=>'N', 'О'=>'O', 'П'=>'P', 'Р'=>'R', 'С'=>'S', 'Т'=>'T', 'У'=>'U', 'Ф'=>'F', 'Ы'=>'I', 'Э'=>'E', 'ё'=>'yo', 'х'=>'h', 'ц'=>'ts', 'ч'=>'ch', 'ш'=>'sh', 'щ'=>'shch', 'ъ'=>'', 'ь'=>'', 'ю'=>'yu', 'я'=>'ya', 'Ё'=>'YO', 'Х'=>'H', 'Ц'=>'TS', 'Ч'=>'CH', 'Ш'=>'SH', 'Щ'=>'SHCH', 'Ъ'=>'', 'Ь'=>'', 'Ю'=>'YU', 'Я'=>'YA'
            ));
    }

    /**
     * @param $str
     * @return string
     */
    public static function ucFirst($str)
    {
        return mb_strtoupper(mb_substr($str, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($str, 1, null, 'UTF-8');
    }

    /**
     * @param $src
     * @param $dst
     * @return bool
     */
    public static function imageResizeToSameSize($src, $dst)
    {

        if(!($info = @getimagesize($src)))
            return false;

        $w = $info[0];
        $h = $info[1];
        $type = substr($info['mime'], 6);

        $func = 'imagecreatefrom' . $type;

        if(!function_exists($func))
            return false;

        $img = $func($src);

        $new = imagecreatetruecolor($w, $h);
        // прозрачность
        if($type == 'gif' || $type == 'png')
        {
            imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
            imagealphablending($new, false);
            imagesavealpha($new, true);
        }
        imagecopyresampled($new, $img, 0, 0, 0, 0, $w, $h, $w, $h);

        $save = 'image' . $type;

        return $save($new, $dst);
    }
    /**
     * @param $src
     * @param $dst
     * @param $width
     * @param $height
     * @param int $crop
     * @return bool
     */
    public static function imageResize($src, $dst, $width, $height, $crop = 0)
    {

        if(!($info = @getimagesize($src)))
            return false;

        $w = $info[0];
        $h = $info[1];
        $type = substr($info['mime'], 6);

        $func = 'imagecreatefrom' . $type;

        if(!function_exists($func))
            return false;

        $img = $func($src);

        if($crop) // изменение размера (непропорциональное)
        {
            if($w <= $width && $h <= $height)
                return false; //еще меньше
            $ratio = max($width/$w, $height/$h);
            $h = $height / $ratio;
            $x = ($w - $width / $ratio) / 2;
            $w = $width / $ratio;
        }
        else // пропорциональное
        {
            if($w <= $width && $h <= $height)
                return false; // еще меньше
            $ratio = min($width/$w, $height/$h);
            $width = $w * $ratio;
            $height = $h * $ratio;
            $x = 0;
        }

        $new = imagecreatetruecolor($width, $height);
        // прозрачность
        if($type == 'gif' || $type == 'png')
        {
            imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
            imagealphablending($new, false);
            imagesavealpha($new, true);
        }
        imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);

        $save = 'image' . $type;

        return $save($new, $dst);
    }
}