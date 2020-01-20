<?

namespace Local\Bitrix\Seo\Text;


use Bitrix\Main\Data\Cache;

/**
 * Class SeoText
 * @package Local\Bitrix\Seo\Text
 */
class SeoText
{
    /**
     * Путь для кеширования
     */
    const CACHE_PATH = 'Local/Bitrix/Seo/Text/SeoText/';
    /**
     * Время кеширования
     */
    const TTL = 3600;

    /**
     * @var SeoTextInterface
     */
    private $seoTextHandler;

    /**
     * @var Cache
     */
    private $cache;

    /**
     * SeoText constructor.
     * @param  SeoTextInterface  $seoTextHandler
     */
    public function __construct(SeoTextInterface $seoTextHandler)
    {
        $this->setSeoTextHandler($seoTextHandler);
    }

    /**
     * @param  bool  $refreshCache
     * @return SeoTextContentInterface
     */
    public function getSeoData($refreshCache = false)
    {
        $cache = $this->getCache();
        $cacheId = md5(serialize([__FUNCTION__, get_class($this->seoTextHandler), $this->seoTextHandler->getUri()]));

        if (!$refreshCache && $cache->initCache(static::TTL, $cacheId, static::CACHE_PATH)) {
            $return = $cache->getVars();
        } elseif ($cache->startDataCache()) {

            $return = $this->getSeoTextHandler()->getContent();

            $cache->endDataCache($return);
        } else {
            $return = new SeoTextContent();
        }

        return $return;
    }

    /**
     * @return Cache
     */
    public function getCache()
    {
        if (empty($this->cache)) {
            $this->cache = Cache::createInstance();
        }
        return $this->cache;
    }

    /**
     * @param  Cache  $cache
     */
    public function setCache(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @return SeoTextInterface
     */
    public function getSeoTextHandler()
    {
        return $this->seoTextHandler;
    }

    /**
     * @param  SeoTextInterface  $seoTextHandler
     */
    public function setSeoTextHandler(SeoTextInterface $seoTextHandler)
    {
        $this->seoTextHandler = $seoTextHandler;
    }
}