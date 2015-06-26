<?php
namespace Drupal\library;
use Drupal\user\Entity\User;


/**
 * Class X
 * @package Drupal\library taken from Drupal\mall
 * @short Helper library class for mall module.
 * @short Difference from Mall.php is that Mall.php is a library that is only used for mall module. x.php holds more generic functions.
 */

class Library {



  const ERROR_PLEASE_LOGIN_FIRST = 'ERROR_PLEASE_LOGIN_FIRST';
  const ERROR_USER_EXISTS = 'ERROR_USER_EXISTS';
  
  const ERROR_NOT_YOUR_ID = 'ERROR_NOT_YOUR_ID';
  const ERROR_NOT_YOUR_POST = 'ERROR_NOT_YOUR_POST';
  
  const ERROR_MUST_BE_AN_INTEGER = 'ERROR_MUST_BE_AN_INTEGER';


    static $error = [];
  static $input = [];

    static $months = [
        '1'=>'January',
        '2'=>'February',
        '3'=>'March',
        '4'=>'April',
        '5'=>'May',
        '6'=>'June',
        '7'=>'July',
        '8'=>'August',
        '9'=>'September',
        '10'=>'October',
        '11'=>'November',
        '12'=>'December'
    ];
  
  public static function getThemeName() {
    $uri = \Drupal::request()->getRequestUri();

      // di($uri);
    //$uri = substr($uri, 1);
    $ex = explode('?', $uri, 2);
      $uri = $ex[0];
	if ( $uri == '/library' or $uri == '/library/' ) return 'library.first-page'; // this is the entry key of routing.yml
	
    $uri = trim($uri, '/ ');
    $uri = str_replace('/', '.', $uri);
    $uri = strtolower($uri);
    return $uri;
  }

  public static function getThemeFileName() {
    return self::getThemeName() . '.html.twig';
  }

  public static function isFromSubmit() {
    return \Drupal::request()->get('mode') == 'submit';
  }



  /**
   * @param $username
   * @return int
   *
   *
   * @code if ( ! x::getUserID(x::in('owner')) ) return x::errorInfoArray(x::error_wrong_owner, $data);
   */
  public static function getUserID($username) {
    if ( $username ) {
      $user = user_load_by_name($username);
      if ( $user ) {
        return $user->id();
      }
    }
    return 0;
  }


  /**
   *
   * It simply returns Username
   *
   * @param $id
   * @return array|mixed|null|string - username
   * @code $task->worker = x::getUsernameByID( $task->get('worker_id')->value );
   */
  public static function getUsernameByID($id) {
    if ( $id ) {
      $user = User::load($id);
      if ( $user ) {
        return $user->getUsername();
      }
    }
    return '';
  }


  public static function myUid() {
    return \Drupal::currentUser()->getAccount()->id();
  }

  public static function login() {
    return self::myUid();
  }
  public static function admin()
  {
    return self::isAdmin();
  }

    /*
    *checks user role if the user is an admin
    *requires $uid
    */
    public static function isAdmin(){
        $user = User::load( self::myUid() );
        if( $user->roles->target_id == 'administrator' ) return 1;
        else return 0;
    }


  public static function input() {
    return self::getInput();
  }

  /**
   * This is a wrapper of "\Drupal::request()->get($name, $default);" except that the default value is  zero(0) instead of null.
   * @param $name
   * @param int $default
   * @return mixed
   * @code
   *    $parent_id  = x::in('parent_id');
   *    $parent_id  = x::in('parent_id', null);
   *    $parent_id  = x::in('parent_id', '');
   * @code
   */
  public static function in($name, $default=0) {
    return \Drupal::request()->get($name, $default);
  }


  /**
   *
   * 입력 값을 임의로 지정한다.
   *
   * x::getInput() 과 x::in() 함수는 입력 값을 리턴한다.
   *
   * 하지만 이 함수를 통해서 입력 값을 임의로 지정하여 해당 함수들이 임의로 지정한 값을 사용 하게 할 수 있다.
   *
   * 예를 들면, 쿠키에 마지막 검색(폼 전송) 값을 저장해 놓고 다음에 접속 할 때 마지막에 지정한 검색 옵션을 그대로 적용하는 것이다.
   *
   *
   * @param $array
   */
  public static function setInput($array) {
    self::$input = $array;
  }

  /**
   * self::$input 의 값을 리턴한다.
   *
   * @note 주의 할 점은 이 값은 꼭 HTTP 입력 값이 아닐 수 있다.
   *
   *      기본 적으로 HTTP 입력 값을 리턴하지만,
   *
   *      프로그램 적으로 임의로 이 값을 다르게 지정 할 수도 있다.
   *
   *      이 함수는 x::in() 에 영향을 미친다.
   *
   * @return array
   */
  public static function getInput() {

    if ( empty(self::$input) ) {
      $request = \Drupal::request();
      $get = $request->query->all();
      $post = $request->request->all();
      self::$input = array_merge( $get, $post ); 
    }
    return self::$input;
  }


  /**
   *
   * Returns an Entity Item.
   *
   * @Note returns an entity ID by User ID.
   *
   * Entity can be any type as long as it has user_id field.
   *
   * @param $type
   * @param $uid
   * @return mixed|null
   */
  public static function loadEntityByUserID($type,$uid) {
    $entities = \Drupal::entityManager()->getStorage($type)->loadByProperties(['user_id'=>$uid]);
    if ( $entities ) $entity = reset($entities);
    else $entity = NULL;
    return $entity;
  }

  /**
   * @param $k
   * @param $v
   * @refer the definition of user_cookie_save() and you will know.
   */
  public static function set_cookie($k, $v) {
    user_cookie_save([$k=>$v]);
  }
  /**
   * @param $k - is the key of the cookie.
   * @return mixed
   */
  public static function get_cookie($k) {
    return \Drupal::request()->cookies->get("Drupal_visitor_$k");
  }
  /**
   * @param $k
   */
  public static function delete_cookie($k) {
    user_cookie_delete($k);
  }


    /**
     * @param $code
     * @param array $info
     * @return array
     * @code
     * return x::error(-1234, ['message'=>'This is error', 'name'=>'JaeHo Song']);
     * @endcode
     */
  public static function error($code, $info = null) {
      self::$error[$code] = $info;
      return $code;
  }

    public static function getError() {
        return self::$error;
    }




  /**
   * Returns true if the input object indicates Error.
   *
   * @note if $re is minus or $re[0] is minus, then it considers as error.
   *
   * @param $re
   * @return bool
   */
  public static function isError($re) {
      return $re;
  }
  
  
  /*------------*/


    /**
     * @param $username
     * @param $password
     * @return int|mixed|null|string
     *      - returns minus value if there is any error.
     *      - or returns User ID.
     */
  public static function registerDrupalUser($username, $password, $email) {

    $user = user_load_by_name($username);
    if ( $user ) return x::ERROR_USER_EXISTS;
    $id = $username;
    $lang = "en";
    $timezone = "Asia/Manila";
    $user = User::create([
      'name'=>$id, // username
      'mail'=>$email,
      'init'=>$email,
      'status'=>1, // whether the user is active or not. Only anonymous is 0. 이 값은 일반적으로 1 이어야 한다.
      'signature'=>$id.'.sig',
      'signature_format'=>'restricted_html',
      'timezone' => $timezone,
      'default_langcode'=>1, // 참고: 이 값을 0 으로 해도, 자동으로 1로 저장 됨.
      'langcode'=>$lang,
      'preferred_langcode'=>$lang,
      'preferred_admin_langcode'=>$lang,
    ]);
    $user->setPassword($password);
    $user->enforceIsNew();
    $user->save();
	
	//added by benjamin for test.. When and where is the UID field saved inside the mall_member aside from this...?
	//Member::set( $user->id(), 'uid', $user->id() );

      return $user->id();
  }


    /**
     *
     * Log into the user account.
     *
     * @param $username
     * @return mixed
     */
  public static function loginUser($username) {
        $user = user_load_by_name($username);
        user_login_finalize( $user );
        return $user->id();
  }


	public static function LinkFileToEntity( $entity_id, $fid, $type ){
		$file = \Drupal::entityManager()->getStorage('file')->load($fid);
		\Drupal::service('file.usage')->add( $file, 'mall', $type, $entity_id );
	}

    public static function isLibraryPage() {
        $request = \Drupal::request();
        $uri = $request->getRequestUri();
        if ( strpos( $uri, '/library') !== FALSE ) {
            return TRUE;
        }
        else return FALSE;
    }
    public static function isLibraryCategoryPage() {
        $request = \Drupal::request();
        $uri = $request->getRequestUri();
        if ( strpos( $uri, '/library/category') !== FALSE ) {
            return TRUE;
        }
        else return FALSE;
    }

    public static function getLanguage() {
        $lns = \Drupal::request()->getLanguages();
        if ( in_array('ko',$lns) ) {
            $ln = 'ko';
        }
        else {
            $ln = 'en';
        }
        return $ln;
    }


}

