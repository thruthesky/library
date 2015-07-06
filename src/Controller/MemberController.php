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
        return self::registerPage();
    }

    private static function registerPage()
    {
        return [
            '#theme' => 'member.register',
            '#data' => [],
        ];
    }

    public static function register_submit() {
        $r = \Drupal::request();
        $username = $r->get('username');
        $re = Library::registerDrupalUser($username, $r->get('password'), $r->get('mail'));
        if ( $re == Library::ERROR_USER_EXISTS ) {
            Library::error('User ID exists.', Language::string('library', 'user_name_already_taken', ['user_name'=>$username]));
            return self::registerPage();
        }
        Library::loginUser($username);
        Member::updateMemberFormSubmit($username);
        return new RedirectResponse('/');
    }

    public static function login() {
        if ( Library::isFromSubmit() ) {
            $r = \Drupal::request();
            if ( Library::checkPassword($r->get('username'), $r->get('password')) ) {
                Library::loginUser($r->get('username'));
                return new RedirectResponse('/');
            }
            else {
                Library::error(-4119, "Login failed. Please check your ID and Password.");
            }
        }
        return [
            '#theme' => Library::getThemeName(),
            '#data' => [],
        ];
    }
    public static function update() {
        Member::updateMemberFormSubmit(Library::myUsername());
        return [
            '#theme' => Library::getThemeName(),
            '#data' => [
                'member' => Member::load(Library::myUid())
            ],
        ];
    }

}