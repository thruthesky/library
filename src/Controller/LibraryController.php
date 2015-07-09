<?php
namespace Drupal\library\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\library\Library;



class LibraryController extends ControllerBase {
    public static function index() {
        $data = [];
        Library::checkAdminLogin($data);
        return [
            '#theme' => Library::getThemeName(),
            '#data' => $data,
        ];
    }

    public static function theme() {
        if ( Library::isFromSubmit() ) {
            Library::saveFormSubmit('theme');
        }
        $config = Library::getGroupConfig('theme');
        $data = [];
        $data['theme_config'] = $config;
        Library::checkAdminLogin($data);
        return [
            '#theme' => Library::getThemeName(),
            '#data' => $data,
        ];
    }
}