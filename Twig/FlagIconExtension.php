<?php

declare(strict_types=1);

namespace Chang\Twig;

class FlagIconExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        $options = array('is_safe' => array('html'));

        return [
            new \Twig_Filter('flag_icon', [$this, 'getFlagIcon'], $options),
            new \Twig_Filter('flag_icon_squared', [$this, 'getFlagIconSquared'], $options),
        ];
    }

    /**
     * @param string $locale
     *
     * @return string
     */
    private function getLocaleCode($locale)
    {
        $locales = explode('_', strtolower($locale));
        $locale = array_pop($locales);

        if ('en' === $locale) {
            $locale = 'gb';
        }

        return $locale;
    }

    /**
     * @param string $locale
     * @param string $tpl
     *
     * @return string
     */
    public function getFlagIcon($locale, $tpl = '<span class="%s"></span>')
    {
        return sprintf($tpl, 'flag-icon flag-icon-' . $this->getLocaleCode($locale));
    }

    /**
     * @param string $locale
     * @param string $tpl
     *
     * @return string
     */
    public function getFlagIconSquared($locale, $tpl = '<span class="%s"></span>')
    {
        return sprintf($tpl, 'flag-icon flag-icon-' . $this->getLocaleCode($locale) . ' flag-icon-squared');
    }
}
