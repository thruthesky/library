<?php
/**
 *
 * https://docs.google.com/document/d/1koxonGQl20ER7HZqUfHd6L53YXT5fPlJxCEwrhRqsN4/edit#
 *
 */
namespace Drupal\library;

/**
 *
 * @package Drupal\library taken from Drupal\mall
 * @short Helper library class for mall module.
 * @short Difference from Mall.php is that Mall.php is a library that is only used for mall module. x.php holds more generic functions.
 */

class Config {

    static $table_name = 'library_config';

    public static function table($table_name=null)
    {
        if ( $table_name ) self::$table_name = $table_name;
        return self::$table_name;
    }

    public static function updateMemberFormSubmit($uid) {
        $input = Library::input();
        foreach( $input as $k => $v ) {
            if ( $k == 'password' ) continue;
            self::update($uid, $k, $v);
        }
    }


    /**
     * Returns a value of a code of a group.
     * @param $group_id
     * @param $code
     * @return mixed
     */
    public static function get($group_id, $code) {
        $db = db_select(self::table());
        $db->fields(null, ['value']);
        $db->condition('group_id', $group_id);
        $db->condition('code', $code);
        $result = $db->execute();
        $re = $result->fetchAssoc(\PDO::FETCH_ASSOC);
        if ( $re ) return $re['value'];
        else return null;
    }

    /**
     * Sets a value
     * @param $group_id - is the group id. It can be a string.
     * @param $code - code of the group.
     * @param $value - value of the code.
     */
    public static function set($group_id, $code, $value) {
        if ( self::exist($group_id, $code) ) self::update($group_id, $code, $value);
        else self::insert($group_id, $code, $value);
    }

    /**
     * Updates a code of a group.
     * @param $group_id
     * @param $code
     * @param $value
     */
    public static function update($group_id, $code, $value) {
        db_update(self::table())
            ->fields(['value'=>$value])
            ->condition('group_id', $group_id)
            ->condition('code', $code)
            ->execute();
    }

    public static function getGroup($group_id) {
        $db = db_select(self::table());
        $db->fields(null, ['code','value']);
        $db->condition('group_id', $group_id);
        $result = $db->execute();
        $rows = [];
        while ( $row = $result->fetchAssoc(\PDO::FETCH_ASSOC) ) {
            $rows[$row['code']] = $row['value'];
        }
        return $rows;
    }


    public static function exist($group_id, $code) {
        $db = db_select(self::table());
        $db->fields(null, ['group_id']);
        $db->condition('group_id', $group_id);
        $db->condition('code', $code);
        $result = $db->execute();
        $re = $result->fetchAssoc(\PDO::FETCH_ASSOC);
        if ( $re ) return true;
        else return false;
    }

    public static function insert($group_id, $code, $value) {
        db_insert(self::table())
            ->fields(['group_id'=>$group_id, 'code'=>$code, 'value'=>$value])
            ->execute();
    }
    public static function delete($group_id, $code) {
        db_delete(self::table())
            ->condition('group_id', $group_id)
            ->condition('code', $code)
            ->execute();
    }
}
