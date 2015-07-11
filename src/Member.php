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
 * Class X
 * @package Drupal\library taken from Drupal\mall
 * @short Helper library class for mall module.
 * @short Difference from Mall.php is that Mall.php is a library that is only used for mall module. x.php holds more generic functions.
 */

class Member {
    public static function load($uid) {
        $user = User::load($uid);
        $user->extra = self::extra($user->get('name')->value);
        return $user;
    }
    public static function updateMemberFormSubmit($uid) {
        $input = Library::input();
		
		//just for confirm password
		if( !empty( $input['confirm_password'] ) ) unset( $input['confirm_password'] );
		
        foreach( $input as $k => $v ) {
            if ( $k == 'password' ) continue;
            self::set($uid, $k, $v);
        }
    }
    public static function set($user_id, $code, $value) {
        Config::set("user.$user_id", $code,$value);
    }

    private static function extra($username) {
        return Config::getGroup("user.$username");
    }
}
