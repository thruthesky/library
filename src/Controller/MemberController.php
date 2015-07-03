<?php
namespace Drupal\library\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\library\Language;
use Drupal\library\Library;
use Drupal\library\Member;
use Symfony\Component\HttpFoundation\RedirectResponse;


class MemberController extends ControllerBase {
    public static function register() {
        if ( Library::isFromSubmit() ) return self::register_submit();
        return [
            '#theme' => Library::getThemeName(),
            '#data' => [],
        ];
    }
    public static function register_submit() {
        $r = \Drupal::request();
        $username = $r->get('username');
        $re = Library::registerDrupalUser($username, $r->get('password'), $r->get('mail'));
        if ( $re == Library::ERROR_USER_EXISTS ) return Library::error('User ID exists.', Language::string('library', 'user_name_already_taken', ['user_name'=>$username]));
        Library::loginUser($username);
        Member::updateMemberFormSubmit($username);
        return new RedirectResponse('/');
    }

    public static function login() {
        return new RedirectResponse('/');
    }
    public static function logout() {
        return new RedirectResponse('/');
    }
    public static function update() {

        return [
            '#theme' => Library::getThemeName(),
            '#data' => [
                'member' => Member::load(Library::myUid())
            ],
        ];
    }

}