<?php
namespace Drupal\library\Entity;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\library\CategoryInterface;
use Drupal\library\Language;
use Drupal\library\Library;
use Drupal\user\UserInterface;

/**
 * Defines the CategoryLog entity.
 *
 *
 * @ContentEntityType(
 *   id = "library_category",
 *   label = @Translation("Library Category entity"),
 *   base_table = "library_category",
 *   fieldable = TRUE,
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid"
 *   }
 * )
 */
class Category extends ContentEntityBase implements CategoryInterface {

    const ERROR_CATEGORY_EXIST = 'ERROR_CATEGORY_EXIST';
    const ERROR_BLANK_CATEGORY_NAME = 'ERROR_BLANK_CATEGORY_NAME';


    /**
     *
     * Returns all the children by a Category ID
     *
     * @note it does not return grand children. It is only an alias of loadChildren()
     *
     * @param $no
     * @return array
     * @see loadChildren
     */
    public static function getChildren( $no ){
        return Category::loadChildren( $no );
    }

    /**
     * Alias of loadAllChildren
     * @param $no
     * @return array
     */
    public static function getAllChildren( $no ){
        return Category::loadAllChildren( $no );
    }

    /**
     * Alias of groupRoot
     * @param $no
     * @return mixed
     */
    public static function getRoot( $no ){
        return Category::groupRoot( $no );
    }

    /**
     * Alias of loadParents
     * @param $no
     * @return mixed
     */
    public static function getParents( $no ){
        return Category::loadParents( $no );
    }

    /**
     * Alias of getCategoryById
     * @param $id
     * @return null|static
     */
    public static function getEntity( $id ){
        return Category::getCategoryById( $id );
    }



    /**
     * @param $parent_id
     * @param $name
     * @return int|mixed|null|string
     */
    public static function add($parent_id, $name) {
        if( empty( $name ) ) return self::ERROR_BLANK_CATEGORY_NAME;

        $brothers = \Drupal::entityManager()->getStorage('library_category')->loadByProperties(['parent_id'=>$parent_id, 'name'=>$name]);
        if ( $brothers ) {
            //$brother = reset($brothers);
            $parent = self::load($parent_id);
            if ( $parent ) $parent_name = $parent->label();
            else $parent_name = '';

            return Library::error('Category Exist', Language::string('library', 'category_exist', ['name'=>$name]));
        }
        else {
            $category = Category::create();
            $category->set('user_id', \Drupal::currentUser()->getAccount()->id());
            $category->set('name', $name);
            $category->set('parent_id', $parent_id);
            $category->save();
            return $category->id();
        }
    }

    public static function update($id, $name) {
        $category = Category::load($id);
        if ( $category ) {
            $category->set('name', $name)->save();
            return 0;
        }
        else {
            return -1;
        }
    }

    public static function getCategoryById( $id ){
        return self::load( $id );
    }


    public static function deleteAll($id) {
        $category = Category::load($id);
        if ( $category ) {
            self::deleteChildren( $id, 0, true );
            $category->delete();
        }
    }


    /**
     *
     * Returns all the children and descendants of a node.
     *
     * @param $no - category id whose children and descendants will be returned.
     * @param int $depth
     * @return array
     */
    public static function loadAllChildren($no, $depth = 0) {//$delete temporary
        $categories = \Drupal::entityManager()->getStorage('library_category')->loadByProperties(['parent_id'=>$no]);
        $rows = [];
        foreach( $categories as $c ){
            $id = $c->id();
            $rows[ $id ]['entity'] = $c;
            $rows[ $id ]['depth'] = $depth;
            $returns = self::loadAllChildren( $id, $depth + 1 );
            if( $returns ) $rows = $rows + $returns;
            $rows[ $id ]['child_no'] = count( $returns );
        }
        return $rows;
    }

    /**
     *
     * Load children. Children Only. Not grand children nor descendants.
     *
     * @param $no - category id
     * @return array
     */
    public static function loadChildren($no) { // $delete temporary
        $categories = \Drupal::entityManager()->getStorage('library_category')->loadByProperties(['parent_id'=>$no]);
        $rows = [];
        foreach( $categories as $c ){
            $rows[] = $c;
        }

        return $rows;
    }

    /*
    *Loads all categories
    *returns:
    *[ i ][ entity ], and [ i ][ child_no ], [ i ][ child ] for all root categories
    *[ i ][ child ][ i ][ entity ] , [ i ][ child ][ i ][ child_no ], and [ i ][ child ][ i ][ depth ] for all sub categories
    */
    public static function loadAllCategories(){
        $categories = \Drupal::entityManager()->getStorage('library_category')->loadByProperties(['parent_id'=>0]);

        $clist = [];
        foreach( $categories as $category ){
            $sub_category = Category::loadAllChildren( $category->id() );
            $clist[ $category->id() ]['entity'] = $category;
            $clist[ $category->id() ]['child_no'] = count( $sub_category );

            foreach( $sub_category as $sc ){
                $clist[ $category->id() ]['child'][ $sc['entity']->id() ] = $sc;
            }
        }

        return $clist;
    }
    /**
     *
     * It returns the path from a node to root in array.
     * The array is in associative-array keyed by entity id and the value is entity itself.
     *
     * @Attention Use this function to get category path(route) information or to get the root(first) category, or the second category.
     *
     * @param $id
     * @return mixed
     *
     * @code
    use Drupal\mall\Entity\Category;
    $entities = Category::loadParents(73);
    foreach ( $entities as $category ) {
    echo $category->id() . ' : ' .  $category->get('name')->value . "\n";
    }
     * @endcode
     */
    public static function loadParents($id) {

        $entity = self::load($id);
        //echo "no: $no \n";
        if ( $entity ) {
            $id = $entity->id();

            //$rows[ $id ]['id'] = $id;
            //$rows[ $id ]['name'] = $entity->label();
            $rows[$id] = $entity;
            $pid = $entity->get('parent_id')->target_id;
            if ( $pid ) {
                $rets = self::loadParents($pid);
                $rows = $rows + $rets;
            }
            return $rows;
        }
    }

    /*
    *also deletes the children of a category when deleted
    */
    public static function deleteChildren( $id ){
        $categories = \Drupal::entityManager()->getStorage('library_category')->loadByProperties(['parent_id'=>$id]);
        foreach( $categories as $c ){
            self::deleteChildren( $c->id() );
            $c->delete();
        }
    }

    /**
     *
     * It returns the Root Entity of the Category Group which has 0 as its parent_id.
     *
     * @param $id - any category id of a category group.
     * @return mixed
     *
    $category = Category::groupRoot(73);
    echo "Group : " . $category->label();
     */
    public static function groupRoot($id) {
        $categories = self::loadParents($id);
        $reversed = array_reverse($categories);
        return reset($reversed);
    }

    public static function groupParents($no) {
        return self::loadParents($no);
    }


    /**
     *
     * Returns true if the 'node' is a root 'node'.
     *
     * @param $id
     * @return bool|int
     */
    public static function isRoot($id) {
        $category = self::load($id);
        if ( $category ) return ! $category->get('parent_id')->target_id;
        else return 0;
    }

    /**
     *
     * Returns the 'root node id' of the 'node'.
     *
     * @param $id
     * @return int
     */
    public static function getRootID($id) {
        if ( $id ) {
            $root = self::groupRoot($id);
            if ( $root ) return $root->id();
        }
        return 0;
    }


    /**
     * Returns the top nodes whose parent_id is 0.
     *
     * @note Use this method to get all the first level nodes.
     *
     * @code
     *  '#data' => ['groups'=>Category::getTopNodes()]
     * @endcode
     */
    public static function getTopNodes()
    {
        $categories = \Drupal::entityManager()->getStorage('library_category')->loadByProperties(['parent_id'=>0]);
        $groups = [];
        foreach( $categories as $c ){
            $groups[$c->id()]['entity'] = $c;
            $groups[$c->id()]['no_of_children'] = count( Category::loadAllChildren( $c->id() ) );
        }
        return $groups;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedTime() {
        return $this->get('created')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function getChangedTime() {
        return $this->get('changed')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function getOwner() {
        return $this->get('user_id')->entity;
    }

    /**
     * {@inheritdoc}
     */
    public function getOwnerId() {
        return $this->get('user_id')->target_id;
    }

    /**
     * {@inheritdoc}
     */
    public function setOwnerId($uid) {
        $this->set('user_id', $uid);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setOwner(UserInterface $account) {
        $this->set('user_id', $account->id());
        return $this;
    }


    public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
        $fields['id'] = BaseFieldDefinition::create('integer')
            ->setLabel(t('ID'))
            ->setDescription(t('The ID of the Category entity.'))
            ->setReadOnly(TRUE);

        $fields['uuid'] = BaseFieldDefinition::create('uuid')
            ->setLabel(t('UUID'))
            ->setDescription(t('The UUID of the Category entity.'))
            ->setReadOnly(TRUE);



        $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
            ->setLabel(t('Drupal User ID'))
            ->setDescription(t('The Drupal User ID who created the entity.'))
            ->setSetting('target_type', 'user');



        $fields['langcode'] = BaseFieldDefinition::create('language')
            ->setLabel(t('Language code'))
            ->setDescription(t('The language code of entity.'));

        $fields['created'] = BaseFieldDefinition::create('created')
            ->setLabel(t('Created'))
            ->setDescription(t('The time that the entity was created.'));

        $fields['changed'] = BaseFieldDefinition::create('changed')
            ->setLabel(t('Changed'))
            ->setDescription(t('The time that the entity was last edited.'));

        $fields['name'] = BaseFieldDefinition::create('string')
            ->setLabel(t('Name'))
            ->setDescription(t('Name of Category.'))
            ->setSettings(array(
                'default_value' => '',
                'max_length' => 64,
            ));


        $fields['parent_id'] = BaseFieldDefinition::create('entity_reference')
            ->setLabel(t('Parent ID'))
            ->setDescription(t('The parent category entity id of the Entity'))
            ->setSetting('target_type', 'library_category');


        return $fields;
    }



}
