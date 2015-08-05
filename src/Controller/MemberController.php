<?php
namespace Drupal\library\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\library\Language;
use Drupal\library\Library;
use Drupal\library\Member;
use Symfony\Component\HttpFoundation\RedirectResponse;

/*added by benjamin for mall register*/
use Drupal\mall\x;


class MemberController extends ControllerBase {
    public static function register() {
        if ( Library::isFromSubmit() ) return self::register_submit();
        return self::registerTheme();
    }

    private static function registerTheme($data=null) {
        return [
            '#theme' => 'member.register',
            '#data' => $data,
        ];
    }

    public static function register_submit() {
		$input = x::input();
		
        if ( $error = self::checkRegisterSubmit() ) {
			$data['error'] = $error;
			$data['input'] = $input;
			return [
				'#theme' => 'member.register',
				'#data' => $data,				
			];
            //return self::registerTheme( [ 'error' => $error ] );
        }

        $r = \Drupal::request();

        $username = $r->get('username');
        $re = Library::registerDrupalUser($username, $r->get('password'), $r->get('mail'));	
		
        if ( $re == Library::ERROR_USER_EXISTS ) {			
            //Library::error('User ID exists.', Language::string('library', 'user_name_already_taken', ['user_name'=>$username]));
			$data['error'] = "Username is already taken.";
			$data['input'] = $input;			
			 return [
				'#theme' => 'member.register',
				'#data' => $data,
			];						
            //return self::registerTheme(['error'=>"User name exists."]);
        }
		
        $uid = Library::loginUser($username);
        Member::updateMemberFormSubmit($username, $uid);

        return new RedirectResponse('/');
    }

    public static function login() {
        $data = [];
        if ( Library::isFromSubmit() ) {
            $r = \Drupal::request();
            if ( Library::checkPassword($r->get('username'), $r->get('password')) ) {
                Library::loginUser($r->get('username'));
                return new RedirectResponse('/');
            }
            else {
                $data['error'] = "Login failed. Please check your ID and Password.";
            }
        }
        return [
            '#theme' => Library::getThemeName(),
            '#data' => $data,
        ];
    }
    public static function update() {
		$request = \Drupal::request();
	
        Member::updateMemberFormSubmit(Library::myUsername(), Library::myUid());
		$fid = $request->get("fid");
		
		if( !empty( $fid ) ){						
			Library::updateUploadedFiles( Library::myUid(), "profile_photo" );			
		}
		
        return [
            '#theme' => Library::getThemeName(),
            '#data' => [
                'member' => Member::load(Library::myUid())
            ],
        ];
    }

    private static function checkRegisterSubmit() {
        $request = \Drupal::request();
        $error = null;
        if ( ! $request->get('password') ) $error = "Input Password.";
        else if ( $request->get('password') != $request->get('confirm_password') ) $error = "Password and Password-confirm does not match. Please re-type your password.";
        else if ( ! $request->get('mail') ) $error = "Input Email.";
        else if ( ! $request->get('mobile') ) $error = "Input Mobile Number.";
        return $error;
    }

	/*
	*code by benjamin
	*will this be okay?
	*/
	public static function view( $user_name ) {
		//any other way for this one?
		$user = user_load_by_name( $user_name );				
		//di( $user->user_picture->target_id );
		if( !empty( $user ) ){
			$user = Member::load( $user->id() );
			$data['user'] = $user;			
			$data['months'] = Library::$months;
		}
		else{
			$data['error'] = "User does no exist";
		}
		
		return [
            '#theme' => 'member.view',
            '#data' => $data,
        ];
	}
}