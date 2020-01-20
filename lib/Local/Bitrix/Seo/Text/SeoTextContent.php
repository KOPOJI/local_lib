<?php


namespace Local\Bitrix\Seo\Text;


class SeoTextContent implements SeoTextContentInterface
{
    private $topText;
    private $bottomText;
    private $title;
    private $h1;
    private $description;

    /**
     * SeoTextContent constructor.
     * @param  array  $data
     * @param  array  $replace
     */
    public function __construct(array $data = [], array $replace = [])
    {
        $this->setValues($data, $replace);
    }

    /**
     * @param  array  $data
     * @param  array  $replace
     * @param  string  $startTag
     * @param  string  $endTag
     */
    public function setValues(array $data, array $replace = [], $startTag = '{{', $endTag = '}}')
    {
        $pattern = '~'.preg_quote($startTag).'(.*?)'.preg_quote($endTag).'~iu';
        foreach ($data as $k => $v) {
            $methodName = 'set'.ucfirst($k);
            if (method_exists($this, $methodName)) {
                $this->{$methodName}(
                    preg_replace_callback($pattern, function ($m) use ($replace) {
                        return isset($replace[$m[1]]) ? $replace[$m[1]] : null;
                    },
                        $v
                    )
                );
            }
        }
    }

    /**
     * @return mixed
     */
    public function getTopText()
    {
        return $this->topText;
    }

    /**
     * @param  mixed  $topText
     */
    public function setTopText($topText)
    {
        $this->topText = $topText;
    }

    /**
     * @return mixed
     */
    public function getBottomText()
    {
        return $this->bottomText;
    }

    /**
     * @param  mixed  $bottomText
     */
    public function setBottomText($bottomText)
    {
        $this->bottomText = $bottomText;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param  mixed  $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getH1()
    {
        return $this->h1;
    }

    /**
     * @param  mixed  $h1
     */
    public function setH1($h1)
    {
        $this->h1 = $h1;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param  mixed  $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param  mixed  $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->{'get'.ucfirst($offset)}() : null;
    }

    /**
     * @param  mixed  $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return method_exists($this, 'get'.ucfirst($offset));
    }

    /**
     * @param  mixed  $offset
     */
    public function offsetUnset($offset)
    {
        $this->offsetSet($offset, null);
    }

    /**
     * @param  mixed  $offset
     * @param  mixed  $value
     * @return void|null
     */
    public function offsetSet($offset, $value)
    {
        $methodName = 'set'.ucfirst($offset);
        return method_exists($this, $methodName) ? $this->{$methodName}($value) : null;
    }
}