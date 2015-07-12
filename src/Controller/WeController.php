<?php
namespace Drupal\library\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\library\Library;
use Drupal\library\Member;



class WeController extends ControllerBase {
    public static function page($page=null, $action=null) {
        $data = [];
        if ( empty($page) ) $page = 'index';
        if ( empty($action) ) $action = 'index';


        $data['page'] = $page;
        $data['action'] = $action;


        self::$page($action, $data);

        return [
            '#theme' => 'we.layout',
            '#data' => $data,
        ];
    }

    private static function index($action, &$data) {

    }

    private static function admin($action, &$data) {

        $call = 'admin_'.$action;
        self::$call($data);
    }

    private static function admin_index(&$data) {

    }

    /**
     * @param $data
     *
     * @todo next version 에 따라서 변경이 필요하다.
     * @return string
     */
    private static function admin_member(&$data) {

        $request = \Drupal::request();
        if ( Library::isAdmin() && $request->get('domain') == null ) {
            $data['domains'] = Member::countByDomain();
        }
        else if ( Library::isAdmin() || Library::isSiteAdmin() ) {
            $data['members'] = Member::loadByDomain(Library::domainNameWithoutWWW());
        }
        else {
            $data['error'] = "You are not allowed to view this page";
        }
    }
}