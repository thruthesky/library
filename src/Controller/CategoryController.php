<?php
namespace Drupal\library\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\library\Library;
use Drupal\library\x;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

use Drupal\library\Entity\Category;



class CategoryController extends ControllerBase {	
	public static function collection() {
        return self::collectionTheme();
	}
    public static function collectionTheme() {
        return [
            '#theme' => 'library.category.admin',
            '#data' => ['groups'=>Category::getTopNodes()]
        ];
    }
  public static function add() {
	$parent_id  = Library::in('parent_id');
	$re = Category::add($parent_id, Library::in('name', ''));
	
	if( $parent_id == 0 ) return self::collectionTheme();
	else {
		$group_root = Category::groupRoot( $parent_id )->id();
		return self::groupCollection( $group_root );
    }


  }
  
  public static function del() {  
	$id = Library::in('id');	
	
    if ( Library::in('confirmed', '') != 'yes' && $children = Category::loadAllChildren($id) ) {
		return [            
			'#theme' => 'library.category.admin.delete.confirm',
			'#data' => [ 'category'=> Category::load($id), 'children'=> $children ]
		];
    }
    else {
      $group_root = Category::groupRoot($id)->id();
	  
	  Category::deleteAll( $id );
	  
	  if ( $id == $group_root ) return self::collectionTheme();
      else{		
		return self::groupCollection( $group_root );
	  }
    }
  }
  
  public static function update() {	
	$id =  \Drupal::request()->get('id');
	$name =  \Drupal::request()->get('name');
	
	$data = [];
	Category::update( $id, $name );
	$group_root = Category::groupRoot($id)->id();
	if( $id == $group_root ){
		return self::collectionTheme();
	}
	else{
		return self::groupCollection( $group_root );
	}	
  }

  public static function groupCollection( $id = null )
  {
    $data = [];
	if( $id == null ) $id = Library::in('parent_id');
    if ( $id ) {
      $data['group'] = Category::load( $id );
      $data['children'] = Category::loadAllChildren( $id );
    }

    return [
      '#theme' => 'library.category.admin.group.list',
      '#data' => $data,
    ];
  }

  public static function deleteConfirm() {
	$data = [];
	$data['category'] = Category::load(Library::in('id'));
	$children = Category::loadAllChildren( Library::in('id') );
	if( $children ) $data['children'] = $children;
	
    return [
      '#theme' => Library::getThemeName(),
      '#data' => $data,
    ];
  }

}
