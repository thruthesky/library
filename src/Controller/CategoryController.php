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
            //'#theme' => Library::getThemeName(),
            '#theme' => 'library.category.admin',
            '#data' => ['groups'=>Category::getTopNodes()]
        ];
    }
  public static function add() {
      $parent_id  = Library::in('parent_id');
      $re = Category::add($parent_id, Library::in('name', ''));

	if( $parent_id == 0 ) return self::collectionTheme();
	else {
      //$redirect_url = '/library/category/admin/group/list?parent_id=' . Category::getRootID($parent_id);
    }


  }
  
  public static function del() {
	$id = x::in('id');
    if ( x::in('confirmed', '') != 'yes' && $children = Category::loadAllChildren($id) ) {
      $redirect_url = "/library/category/admin/delete/confirm?id=$id";
    }
    else {
      $is_root = Category::isRoot($id);
	  if ( $is_root ) $redirect_url = '/library/category/admin';
      else $redirect_url = '/library/category/admin/group/list?parent_id=' . Category::getRootID($id);
      Category::deleteAll($id );      
    }
    return new RedirectResponse( $redirect_url );
  }
  
  public static function update() {	
	$id =  \Drupal::request()->get('id');
	$name =  \Drupal::request()->get('name');
   
	
	if( $id == 0 ) $redirect_url = '/library/category/admin';
	else{
		$group = Category::groupRoot($id);
		$redirect_url = '/library/category/admin/group/list?parent_id=' . $group->id();
	}
	Category::update( $id, $name );
    return new RedirectResponse( $redirect_url );//
  }

  public static function groupCollection()
  {
    $data = [];

    if ( $id = x::in('parent_id') ) {
      $data['group'] = Category::load(x::in('parent_id'));
      $data['children'] = Category::loadAllChildren( $id );
    }
    return [
      '#theme' => x::getThemeName(),
      '#data' => $data,
    ];
  }

  public static function deleteConfirm() {
	$data = [];
	$data['category'] = Category::load(x::in('id'));
	$children = Category::loadAllChildren( x::in('id') );
	if( $children ) $data['children'] = $children;
	
    return [
      '#theme' => x::getThemeName(),
      '#data' => $data,
    ];
  }
}
