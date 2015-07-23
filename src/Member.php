<?php
/**
 *
 * https://docs.google.com/document/d/1koxonGQl20ER7HZqUfHd6L53YXT5fPlJxCEwrhRqsN4/edit#
 *
 */
namespace Drupal\library;
use Drupal\user\Entity\User;
use Drupal\library\Config;

use Drupal\file\Entity\File;//used for profile photo url


/**
 * Class Member
 *
 *
 */

class Member {
    /**
     * @param $uid
     * @return null|static
     *
     *
     * @code
     *  $member = Member::load(Library::myUid());
     *  $number = $member->extra['mobile'];
     * @endcode
     *
     */
    public static function load($uid) {
        $user = User::load($uid);
        $user->extra = self::extra($user->get('name')->value);
		
		if( !empty( $user->user_picture->target_id ) ){
			$user->photo = file::load( $user->user_picture->target_id );
			$user->photo->thumbnails = Library::getFileUrl( $user->photo );
		}
        return $user;
    }

    public static function load_by_name($name) {
        $uid = user_load_by_name($name);
        return self::load($uid);
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
        $rows = Config::getGroup("domain.$domain");
        $members = [];
        foreach( $rows as $uid => $name ) {
            $members[] = Member::load($uid);
        }
        return $members;
    }

    public static function countByDomain() {
        return Config::countByGroup('domain.');
    }

    /**
     * @param $username
     * @return bool
     *  - true if the user's last page access is within the past of 10 minutes.
     */
    public static function isOnline($username)
    {
        if ( is_string($username)) {
            $user = user_load_by_name($username);
            $uid = $user->id();
        }
        else $uid = $username;
        $stamp = Member::get($uid, 'stamp_last_access');
        if ( $stamp + 10 * 60 > time() ) return true;
        else return false;
    }
}
