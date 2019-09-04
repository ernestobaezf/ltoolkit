<?php


namespace ltoolkit\Test\Environment\Main;


use Illuminate\Contracts\Translation\Translator;

class MockTranslator implements Translator
{
    /**
     * Get the translation for a given key.
     *
     * @param string $key
     * @param array $replace
     * @param string $locale
     * @return mixed
     */
    public function trans($key, array $replace = [], $locale = null)
    {
        return $key;
    }

    /**
     * Get a translation according to an integer value.
     *
     * @param string $key
     * @param int|array|\Countable $number
     * @param array $replace
     * @param string $locale
     * @return string
     */
    public function transChoice($key, $number, array $replace = [], $locale = null)
    {
        return $key;
    }

    /**
     * Get the default locale being used.
     *
     * @return string
     */
    public function getLocale()
    {
        return "en";
    }

    /**
     * Set the default locale.
     *
     * @param string $locale
     * @return void
     */
    public function setLocale($locale)
    {
        // TODO: Implement setLocale() method.
    }
}
