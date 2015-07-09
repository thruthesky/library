<?php
namespace Drupal\library\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\library\Library;



class LibraryController extends ControllerBase {
    public static function index() {
        $data = ['page'=>'index'];
        Library::checkAdminLogin($data);

        return [
            '#theme' => Library::theme(),
            '#data' => $data,
        ];
    }

    public static function theme() {
        if ( Library::isFromSubmit() ) {
            Library::saveFormSubmit('theme');
        }
        $config = Library::getGroupConfig('theme');
        $data = ['page'=>'theme'];
        $data['theme_config'] = $config;
        Library::checkAdminLogin($data);
        return [
            '#theme' => Library::theme(),
            '#data' => $data,
        ];
    }
}