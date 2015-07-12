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

        self::$page($action, $data);

        $data['page'] = $page;
        $data['action'] = $action;
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

    /**
     * @param $data
     *
     * @todo next version 에 따라서 변경이 필요하다.
     */
    private static function admin_member(&$data) {
        Member::countByDomain();
        if ( Library::isAdmin() ) {
            $data['domains'] = Member::countByDomain();
        }
        else if ( Library::isSiteAdmin() ) {
            $data['member_list'] = Member::loadByDomain(Library::domainNameWithoutWWW());
        }
    }
}