<?php

namespace Telsa\BlizzardConnection\Regions;

use Symfony\Component\Translation\Exception\NotFoundResourceException;

final class EU implements RegionInterface
{
    /**
     * @var string[]
     */
    private static $locales = [
        RegionInterface::DE_DE,
        RegionInterface::EN_GB,
        RegionInterface::ES_ES,
        RegionInterface::FR_FR,
        RegionInterface::IT_IT,
        RegionInterface::PL_PL,
        RegionInterface::PT_PT,
        RegionInterface::RU_RU,
    ];

    /**
     * @var string
     */
    private $locale;

    /**
     * @param string $locale
     */
    public function __construct($locale = RegionInterface::EN_GB)
    {
        if (false === in_array($locale, self::$locales, true)) {
            throw new NotFoundResourceException(sprintf("Locale %s is not part of the %s Region", $locale, $this->getName()));
        }

        $this->locale = $locale;
    }

    /**
     * {@inheritdoc}
     */
    public function getApiBaseUrl()
    {
        return 'https://eu.api.blizzard.com/';
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
        return 'EU';
    }

    /**
     * {@inheritdoc}
     */
    public function getOAuthBaseUrl()
    {
        return 'https://eu.battle.net';
    }

}
