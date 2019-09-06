<?php

namespace Telsa\BlizzardConnection\Regions;

use Symfony\Component\Translation\Exception\NotFoundResourceException;

final class US implements RegionInterface
{
    /**
     * @var string[]
     */
    private static $locales = [
        RegionInterface::EN_US,
        RegionInterface::ES_MX,
        RegionInterface::PT_BR,
    ];

    /**
     * @var string
     */
    private $locale;

    /**
     * @param string $locale
     */
    public function __construct($locale = RegionInterface::EN_US)
    {
        if (false === \in_array($locale, self::$locales, true)) {
			throw new NotFoundResourceException(sprintf("Locale %s is not part of the %s Region", $locale, $this->getName()));
        }

        $this->locale = $locale;
    }

    /**
     * {@inheritdoc}
     */
    public function getApiBaseUrl()
    {
        return 'https://us.api.blizzard.com/';
    }

    /**
     * {@inheritdoc}
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'US';
    }

    /**
     * {@inheritdoc}
     */
    public function getOAuthBaseUrl()
    {
        return 'https://us.battle.net';
    }
}
