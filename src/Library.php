<?php
/**
 *
 * https://docs.google.com/document/d/1koxonGQl20ER7HZqUfHd6L53YXT5fPlJxCEwrhRqsN4/edit#
 *
 */
namespace Drupal\library;
use Drupal\file\Entity\File;
use Drupal\user\Entity\User;
use Drupal\user\UserAuth;
use Symfony\Component\Yaml\Yaml;


/**
 * Class
 * @package Drupal\library taken from Drupal\mall
 * @short Helper library class for mall module.
 * @short Difference from Mall.php is that Mall.php is a library that is only used for mall module. x.php holds more generic functions.
 */

class Library {

    const ERROR_PLEASE_LOGIN_FIRST = 'ERROR_PLEASE_LOGIN_FIRST';
    const ERROR_USER_EXISTS = -1401;

    const ERROR_NOT_YOUR_ID = 'ERROR_NOT_YOUR_ID';
    const ERROR_NOT_YOUR_POST = 'ERROR_NOT_YOUR_POST';

    const ERROR_MUST_BE_AN_INTEGER = 'ERROR_MUST_BE_AN_INTEGER';


    static $error = [];
    static $notice = [];
    static $input = [];

    static $browser_id=null;
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
    private static $count_log = 0;
    private static $subsite = [];
    private static $subsite_info = [];

    public static function getThemeName() {
        $uri = \Drupal::request()->getRequestUri();
        $ex = explode('?', $uri, 2);
        $uri = $ex[0];
        $uri = trim($uri, '/ ');
        $uri = str_replace('/', '.', $uri);
        $uri = strtolower($uri);
        return $uri;
    }
    public static function theme() {
        return 'library.layout';
    }

    public static function getThemeFileName() {
        return self::getThemeName() . '.html.twig';
    }

    public static function isFromSubmit() {
        if ( \Drupal::request()->get('mode') == 'submit' ) return true;
        if ( \Drupal::request()->get('submit') ) return true;
        return false;
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

    /**
     * @deprecated use myUsername() instead.
     * @return mixed
     */
    public static function myName() {
        return self::myUsername();
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
    public static function isAdmin() {
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
        if ( !isset(self::$input['page_no']) ) self::$input['page_no'] = 1;

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

    /*
    *Works the same as error, just that it uses the static variable notice
    *this will be used for successful notices e.g.) success on creating an account
    */
    public static function notice($code, $info = null) {
        self::$notice[$code] = $info;
        return $code;
    }

    public static function getNotice() {
        return self::$notice;
    }




    /**
     * Returns true if the input object indicates Error.
     *
     * @note
     *
     * @param $re -
     *    - true if $re is less than 0
     * @return bool
     */
    public static function isError($re) {
        if ( is_numeric($re) && $re < 0 ) return true;
        else if ( is_array($re) ) {
            if ( isset($re[0]) && $re[0] < 0 ) return true;
        }
        return false;
    }

    /**
     * @param $re
     * @return null
     */
    public static function readError($re) {
        if ( is_array($re) && $re[0] < 0 ) {
            return isset($re[1]) ? $re[1] : null;
        }
        return null;
    }


    /**
     *
     * @deprecated
     * @param $code
     * @param array $info
     * @return array
     * @code
     * if( empty( $name ) ) return Library::error(-1, Language::string('library', 'empty_category_name'));
     * @endcode
     */
    public static function error($code=0, $info = null) {
        if ( empty($code) ) return;
        self::$error[$code] = $info;
        return $code;
    }




    /**
     *
     * @deprecated
     * @return array
     */
    public static function getError() {
        return self::$error;
    }



    /*------------*/


    /**
     * @param $username
     * @param $password
     * @return int|mixed|null|string
     *      - returns minus value if there is any error.
     *      - or returns User ID.
     */
    public static function registerDrupalUser($username, $password, $mail) {

        $user = user_load_by_name($username);
        if ( $user ) return self::ERROR_USER_EXISTS;
        $id = $username;
        $lang = "en";
        $timezone = "Asia/Manila";
        $user = User::create([
            'name'=>$id, // username
            'mail'=>$mail,
            'init'=>$mail,
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
     * @note it does not check password.
     *
     * @param $username
     * @return mixed
     *      - User ID if success.
     *      - 0 on failure.
     */
    public static function loginUser($username) {
        $user = user_load_by_name($username);
        if ( $user ) {
            user_login_finalize( $user );
            return $user->id();
        }
        else return 0;
    }


    /**
     *
     * It only checks if the password is right or not.
     * @note it does not login.
     * @param $name
     * @param $password
     * @return mixed
     *
     *      - User ID on success.
     *      - FALSE on failure
     */
    public static function checkPassword($name, $password)
    {
        $userStorage = \Drupal::entityManager();
        $passwordChecker = \Drupal::service('password');
        $auth = new UserAuth($userStorage, $passwordChecker);
        return $auth->authenticate($name, $password);
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



    /**
     * @param $str
     * @TODO @WARNING If the log file permission is not open to public, then it will create error.
     */
    public static function log ( $str )
    {
        /**
         * @TODO @WARNING This was a bug. If $str is unset/empty, there will be a warning error on Drupal.
         */
        if ( empty($str) ) return;

        $path_log = "./debug.log";
        $fp_log = fopen($path_log, 'a+');
        if ( ! is_string($str) ) {
            $str = print_r( $str, true );
        }
        self::$count_log ++;
        fwrite($fp_log, self::$count_log . " : $str\n");

        fclose( $fp_log );
    }


    /**
     *
     * Returns HTML elements for displaying page navigation
     * @param $page_no - current page number
     * @param $total_record - the whole number of item
     * @param $items_per_page - items per page
     * @param $qs - Extra query string.
     *      - If you want to add some http query.
     *      - Query string
     * @param int $paging - is the number of pages in navigation. How many pages you want to show in the navigation bar.
     * @param array $text - Texts on navigation bar.
     *        - This is the text on the navigation bar.
     *      - You can set a text for first page, previous page, next page, last page and so on.
     *      - ex) $text = array("First ", "Previous (n)", "Next (n)", "Last");
     *          -- Where '(n)' is number of actual page.
     * @param $path - is the page of the index.php
     * @return string
     * @code How to display list and navigation bar.
     *      // Get current page no
     *      if ( $in[page_no] ) $page_no = $in[page_no];
     *      else $page_no = 1;
     *      // Get number of item to show in one page.
     *      $items_per_page = 15;
     * // Get total number of records.
     *      $total_record = "전체 레코드(글, 게시물, 쪽지 수)";
     *      // How to get the list from database.
     *      $rows = mysql_query("select * from table_name order by UID desc limit " . ($page_no - 1) * $items_per_page . ", ". $items_per_page, $connect);
     * // display content...
     *      // display navigation bar like below.
     *      echo paging( $page_no, $total_record, $items_per_page );
     * @endcode
     *
     *
     *
     * @note If you omit $qs, $_GET['page_no'] will be deleted and set new page no.
     * @note How to design
     *      - You can edit style with CSS Style Overriding
     *      - Each block has a class='cell'
     *      - ex) .navigator .cell { ... }
     *
     *
     * @note CSS classes
     *
     * - nav.navigation-bar - is the wrapper
     *      - class='first_page' - is the first page.
     *      - class='previous_page' - is the previous page.
     *      - class='page_no' - is each page.
     *      - class='selected' - is the current page.
     *      - class='next_page' - is next page.
     *      - class='last_page' '- is last page.
     *
     * @note Sample CSS Code
    nav.navigation-bar a {
    display:inline-block;
    margin:0 1px;
    padding:4px 6px;
    background-color: #d3e8f4;
    border-radius: 2px;
    }
     */
    public static function paging( $page_no, $total_record, $items_per_page, $qs=NULL, $paging=10, $text=null, $path=null) {
        if ( empty($total_record) ) return NULL;
        if ( empty($text) ) $text = array("&lt;&lt;", "Previous (n)", "Next (n)", "&gt;&gt;");



        /// If you uncomment below, "[ 1 ]" will not appear on a list where there is only 1 page.
        /// if ( $total_record <= $items_per_page ) return NULL;




        if ( empty($qs) ) {
            /// @warning when the input of $qs is empty,
            /// it returns the value of HTTP input excep page_no and idx
            /// and it puts $in[action]='list'
            $qv = Library::input();
            unset($qv["page_no"]);
            unset($qv["id"]);
            $qs = http_build_query($qv);
        }


        // Number pages to show in navigation bar.
        // Default number of pages is 10.
        if ( !$paging ) $paging = 10;

        // Number of pages to display on navigatio bar.
        $text[1] = str_replace("(n)", $paging, $text[1]);
        $text[2] = str_replace("(n)", $paging, $text[2]);


        // Total number of pages.
        if ( $total_record % $items_per_page != 0 ) {
            $totalpage = intval($total_record / $items_per_page) + 1;
        } else {
            $totalpage = intval($total_record / $items_per_page);
        }





        // Page number that begins on the navigation bar.
        if ( $page_no % $paging == 0 ) {
            $startpage = $page_no - ( $paging - 1 );
        } else {
            $startpage = intval( $page_no / $paging ) * $paging + 1;
        }


        // Prvious Page number
        $prevpage = $startpage - 1;

        // Next Page number
        $nextpage = $startpage + $paging;

        // Lst Page number
        if ( $totalpage / $paging > 1 ) {
            $laststartpage = (intval($totalpage / $paging) * $paging ) + 1;
        } else {
            $laststartpage = 1;
        }


        $rt = "<nav class='navigation-bar'>";

        /** @short first page button.
         *
         * @note If the text is empty, it does not show the first page button.
         */
        $first_page = "$text[0]";
        if ( $first_page && $page_no > $paging ) {

            if ( $qs ) {
                $rt .= "<a class='first-page' href='$path?page_no=1&".$qs."'>";
            } else {
                $rt .= "<a class='first-page' href='$path?page_no=1'>";
            }
            $rt .= "$first_page</a>";
        } else {

        }




        // Previous page button
        $previous_page = "$text[1]";
        if ( $totalpage > $paging && $page_no > $paging ) {
            if ( $qs ) {
                $rt .= "<a class='prev-page' href='$path?page_no=".$prevpage."&".$qs."'>";
            } else {
                $rt .= "<a class='prev-page' href='$path?page_no=".$prevpage."'>";
            }
            $rt .= "<span class='no'>$previous_page</span></a>";
        } else {
        }



        // This is list has only one(1) page?
        if ( $totalpage <= 1 ) {
            $rt .= "<div class='one-page'>1</div>";
        }
        else {
            // Page number list.
            for ( $i = $startpage ; $i <= ($startpage + ($paging - 1) ) ; $i++ ) {
                // If this page number is not the current page,
                if ( $page_no != $i ) {
                    if ( $qs ) {
                        $rt .= "<a class='page' href='$path?page_no=".$i."&".$qs."'>".$i."</a>";
                    } else {
                        $rt .= "<a class='page' href='$path?page_no=".$i."'>".$i."</a>";
                    }
                }
                // If this page number is the current page,
                else {
                    $rt .= "<a class='page selected' href='javascript:void(0);'>".$i."</a>";
                }

                //
                if ( $i >= $totalpage ) {
                    break;
                }
            }
        }

        // 'Move to Next block' button
        $next_page = "$text[2]";

        if ( $startpage + $paging - 1 < $totalpage) {
            if ( $qs ) {
                $rt .= "<a class='next-page' href='$path?page_no=".$nextpage."&".$qs."'>";
            } else {
                $rt .= "<a class='next-page' href='$path?page_no=".$nextpage."'>";
            }
            $rt .= "$next_page</a>";
        } else {

        }


        /** @short Move to last page button.
         *
         * @note If the text is empty, it does not show the last page button.
         */
        $last_page = "$text[3]";
        if ( $last_page && $page_no < intval($laststartpage) ) {
            if ( $qs ) {
                $rt .= "<a class='last-page' href='$path?page_no=".$totalpage."&".$qs."'>";
            } else {
                $rt .= "<a class='last-page' href='$path?page_no=".$totalpage."'>";
            }
            $rt .= "$last_page</a>";
        }
        else {
        }

        $rt .= "</nav>";

        return $rt;
    }

    public static function getPageNo() {
        $page_no = \Drupal::request()->get('page_no');
        if ( empty($page_no) ) $page_no = 1;
        return $page_no;
    }

    /**
     *
     *
     * @deprecated
     * @param $variables
     * @param null $error_html_twig
     *
     */
    public static function parseErrorMessage(&$variables, $error_html_twig=null) {
        if ( empty($error_html_twig) ) $error_html_twig = "modules/library/templates/error.html.twig";
        $template = @file_get_contents($error_html_twig);
        if ( empty($template) ) return;
        $error = self::getError();
        $markup = \Drupal::service('twig')->renderInline($template, ['error'=>$error]);
        if ( empty($variables['error_message']) ) $variables['error_message'] =  $markup;
        else $variables['error_message'] .=  $markup;
    }


    /**
     *
     * It saves all the information coming from the FORM submit.
     *
     * @param $form_name
     * @code
     *      Library::saveFormSubmit('post_global_config');
     * @endcode
     */
    public static function saveFormSubmit($form_name) {
        self::setGroupConfig($form_name, Library::input());
    }

    /**
     *
     * 'x.' is attached at the beginning to avoid the conflict of 'keys' of drupal.
     *
     * @param $group_name
     * @param $arr
     */
    public static function setGroupConfig($group_name, $arr) {
        $group_name = "x.$group_name";
        foreach( $arr as $k => $v ) {
            \Drupal::state()->set("$group_name.$k", $v);
        }
    }

    /**
     * @param $group_name
     * @return array
     * @code
     * $variables['global_config'] = Library::getGroupConfig('post_global_config');
     * @endcode
     */
    public static function getGroupConfig($group_name) {
        $group_name = "x.$group_name";
        $configs = getStateGroup($group_name);
        if ( $configs ) {
            foreach( $configs as $k => $v ) {
                $k = str_replace("$group_name.", '', $k);
                $news[$k] = $v;
            }
            return $news;
        }
        else return [];
    }

    public static function getSiteUrl() {
        $protocol = isset($_SERVER["HTTPS"]) ? 'https' : 'http';
        return $protocol . '://' . self::domain_name();
    }




    /**
     *
     * Returns domain name in lower letter.
     *
     * @return null|string
     *
     *  Examples of return value.
     *
     *      - abc.1234.456.com
     *      - www.abc.com
     *      - abc.com
     *
     */
    public static function domain_name()
    {
        if ( isset( $_SERVER['HTTP_HOST'] ) ) {
            $domain = $_SERVER['HTTP_HOST'];
            $domain = strtolower($domain);
            return $domain;
        }
        else return NULL;
    }

    public static function domainNameWithoutWWW() {
        $domain = self::domain_name();
        return str_replace("www.", '', $domain);
    }

    public static function getBrowserID() {
        if ( self::$browser_id  ) return self::$browser_id;
        self::$browser_id = self::get_cookie('bid');
        if ( empty(self::$browser_id) ) {
            self::$browser_id = self::uniqueID();
            self::set_cookie('bid', self::$browser_id);
        }
        return self::$browser_id;
    }

    private static function uniqueID() {
        return md5(uniqid(rand(), true));
    }

    public static function recordBrowserID($browser_id) {
        if ( $uid = self::login() ) {
            if ( self::existBrowserID($uid, $browser_id) ) {

            }
            else {
                self::saveBrowserID($uid, $browser_id);
            }
        }
        return;
    }

    private static function existBrowserID($uid, $browser_id) {
        $result = db_select('library_member_browser_id')
            ->fields(null, ['browser_id'])
            ->condition('user_id', $uid)
            ->condition('browser_id', $browser_id)
            ->execute();
        $row = $result->fetchAssoc(\PDO::FETCH_NUM);
        if ( empty($row['browser_id']) ) return false;
        else return true;
    }

    private static function saveBrowserID($uid, $browser_id) {
        db_insert('library_member_browser_id')
            ->fields(['user_id'=>$uid, 'browser_id'=>$browser_id])
            ->execute();
    }

    public static function clientIP() {
        return \Drupal::request()->server->get('REMOTE_ADDR');
    }

    /**
     * @param $module
     * @param $id
     * @param string $order_field
     * @param string $order_direction
     * @return array
     */
    public static function files_by_module_id($module, $id, $order_field='fid', $order_direction='DESC') {
        $result = db_select('file_usage')
            ->fields(null, ['fid', 'module', 'type'])
            ->condition('module', $module)
            ->condition('id', $id)
            ->orderBy($order_field,$order_direction)
            ->execute();
        $files = [];
        while ( $row = $result->fetchAssoc(\PDO::FETCH_ASSOC) ) {
            $file = File::load($row['fid']);
            $name = $file->filename->value;
            $name = urldecode($name);
            $file->dname = $name;
            $file->type = $row['type'];
            $file->module = $row['module'];
            $files[] = $file;
        }
        return $files;
    }


    /**
     * @param $module
     * @param $type
     * @param $id
     * @param string $order_field
     * @param string $order_direction
     * @return array
     */
    public static function files_by_module_type_id($module, $type, $id, $order_field='fid', $order_direction='DESC') {
        $result = db_select('file_usage')
            ->fields(null, ['fid', 'module', 'type'])
            ->condition('module', $module)
            ->condition('type', $type)
            ->condition('id', $id)
            ->orderBy($order_field,$order_direction)
            ->execute();
        $files = [];
        while ( $row = $result->fetchAssoc(\PDO::FETCH_ASSOC) ) {
            $file = File::load($row['fid']);
            $name = $file->filename->value;
            $name = urldecode($name);
            $file->dname = $name;
            $file->type = $row['type'];
            $file->module = $row['module'];
            $files[] = $file;
        }
        return $files;
    }
    public static function file_usage($fid) {
        $result = db_select('file_usage')
            ->condition('fid', $fid)
            ->execute();
        $result->fetchAssoc(\PDO::FETCH_ASSOC);
    }

    public static function file_delete($fid) {
        if ( empty($fid) || ! is_numeric($fid) ) return;
        $file = File::load($fid);
        if ( $file ) {
            $file->delete();
        }
    }

    public static function myUsername() {
        return \Drupal::currentUser()->getAccount()->getUsername();
    }

    public static function checkAdminLogin(&$data) {
        if ( ! Library::isAdmin() ) {
            $data['error_title'] = "Admin Permission Required";
            $data['error'] = "You are not admin. To access this page, You need to login as admin.";
        }
    }


    /**
     * @param $path
     * @return array
     *
     * @code
     *
     * $path = "$variables[dir_sub_theme]/$variables[sub_theme].yml";
    $yml = Library::loadYml($path);
    di($yml['sitename']);
     *
     * @endcode
     */
    public static function loadYml($path) {
        $yml = Yaml::parse(file_get_contents($path));
        return $yml;
    }

    /**
     * @return array
     *
     * @note "http://drupalkorea.org/post/drupal-freetalk/638?jj" will return below
     * @code
     * Array
    (
    [0] => post
    [1] => drupal-freetalk
    [2] => 638
    )
     * @endcode
     *
     */
    public static function getUriSegment() {
        $uri = \Drupal::request()->getRequestUri();
        $arr = explode('?', $uri);
        $uri = trim($arr[0], '/');
        return explode('/', $uri);
    }

    public static function userAgent() {
        return isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : null;
    }

    public static function getSubsiteInformation() {


        if ( ! empty(self::$subsite_info) ) return self::$subsite_info;

        $sites = self::getAllSubsiteInformation();

        $domain = Library::domain_name();
        for ($i = 1; $i <= MAX_DOMAIN; $i++) {
            if (!empty($sites["domain$i"])) {
                if (strpos($domain, $sites["domain$i"]) !== FALSE) {
                    self::$subsite_info['sub_theme'] = $sites["theme$i"];
                    self::$subsite_info['sub_theme_sitename'] = $sites["sitename$i"];
                    self::$subsite_info['sub_admin'] = $sites["admin$i"];
                    break;
                }
            }
        }

        return self::$subsite_info;
    }

    /**
     * @return array
     */
    private static function getAllSubsiteInformation() {
        if ( ! empty(self::$subsite) ) return self::$subsite;
        self::$subsite = Library::getGroupConfig('theme');
        return self::$subsite;
    }

    /**
     * Returns true if the logged in user is site admin
     * @return bool
     */
    public static function isSiteAdmin() {
        $site = Library::getSubsiteInformation();
        if ( isset($site['sub_admin'] ) ) {
            if ($site['sub_admin'] == Library::myUsername()) {
                return TRUE;
            }
        }
        return false;
    }



    public static function fileUploadInfo()
    {
        $re = [];
        foreach ($_FILES as $k => $v) {
            $f = array();
            $f['form_name'] = $k;
            if (is_array($v['name'])) {
                for ($i = 0; $i < count($v['name']); $i++) {
                    $f['name'] = $v['name'][$i];
                    $f['type'] = $v['type'][$i];
                    $f['tmp_name'] = $v['tmp_name'][$i];
                    $f['error'] = $v['error'][$i];
                    $f['size'] = $v['size'][$i];
                    $re[] = $f;
                }
            } else {
                $f['name'] = $v['name'];
                $f['type'] = $v['type'];
                $f['tmp_name'] = $v['tmp_name'];
                $f['error'] = $v['error'];
                $f['size'] = $v['size'];
                $re[] = $f;
            }
        }
        return $re;
    }


    public static function isLibraryMemberPage() {
        $request = \Drupal::request();
        $uri = $request->getRequestUri();
        if ( strpos( $uri, '/member') !== FALSE ) {
            return TRUE;
        }
        else return FALSE;
    }
}

