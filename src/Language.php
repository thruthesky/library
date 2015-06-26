<?php
namespace Drupal\library;
use Symfony\Component\Yaml\Yaml;
class Language {
    private static $language = [];
    private static $text = [];

    /**
     * Returns the language.
     * @param $module_name - module name. module_name.language.yml file will be loaded.
     * @return array - language code and text
     * @NOTE It caches on memory. You can call this function as many times as you want.
     * @code
     *      $ol = Language::load();
     * @endcode
     */
    public static function load($module_name) {
        if ( empty(self::$language) ) {
            $path_language = drupal_get_path('module', $module_name) . "/$module_name.language.yml";
            self::$language = Yaml::parse(file_get_contents($path_language));
            $ln = Library::getLanguage();
            foreach( self::$language as $name => $value ) {
                self::$text[$name] = $value[$ln];
            }
        }
        return self::$text;
    }

    /**
     * @param $module_name
     * @param $code
     * @param array $kvs
     * @return mixed
     * @code
     *  return Library::error('Category Exist', Language::string('library', 'category_exist', ['name'=>$name]));
     *  Library::error(-999, \Drupal\library\Language::string('library', 'version'));
     * @endcode
     */
    public static function string($module_name, $code, $kvs=[]) {
        $language = self::load($module_name);
        $message = $language[$code];
        foreach( $kvs as $k => $v ) {
            $message = str_replace('#'.$k, $v, $message);
        }
        return $message;
    }
}