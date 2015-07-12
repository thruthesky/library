<?php
/**
 *
 * https://docs.google.com/document/d/1koxonGQl20ER7HZqUfHd6L53YXT5fPlJxCEwrhRqsN4/edit#
 *
 */
namespace Drupal\library;
use Drupal\user\Entity\User;
use Drupal\library\Config;


/**
 * Class Member
 *
 *
 */

class Member {
    public static function load($uid) {
        $user = User::load($uid);
        $user->extra = self::extra($user->get('name')->value);
        return $user;
    }

    public static function updateMemberFormSubmit($username, $uid) {
        $input = Library::input();
		
		//just for confirm password
        if( ! empty( $input['password'] ) ) unset( $input['password'] );
		if( ! empty( $input['confirm_password'] ) ) unset( $input['confirm_password'] );


        $domain_wo_www = Library::domainNameWithoutWWW();
        $domain = self::get($username, 'domain');
        if ( empty($domain) ) {
            $input['domain'] = $input['domain'] = $domain_wo_www;
        }
        // https://docs.google.com/document/d/1koxonGQl20ER7HZqUfHd6L53YXT5fPlJxCEwrhRqsN4/edit#heading=h.c71if2nmetqu
        $domain_uid = Config::get('domain.'.$domain, $uid);
        if ( empty($domain_uid) ) {
            Config::set('domain.'.$domain_wo_www, $uid, $username);
        }

        foreach( $input as $k => $v ) {
            self::set($username, $k, $v);
        }

    }
    public static function set($user_id, $code, $value) {
        Config::set("user.$user_id", $code,$value);
    }
    public static function get($user_id, $code) {
        return Config::get("user.$user_id", $code);
    }

    private static function extra($username) {
        return Config::getGroup("user.$username");
    }

    public static function loadByDomain($domain) {
        return Config::getGroup("domain.$domain");
    }

    public static function countByDomain() {
        return Config::countByGroup('domain.');
    }
}
