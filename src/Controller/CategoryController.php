<?php
namespace Drupal\library\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\library\x;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

use Drupal\library\Entity\Category;



class CategoryController extends ControllerBase {	
	public static function collection() {	
      $categories = \Drupal::entityManager()->getStorage('library_category')->loadByProperties(['parent_id'=>0]);
	  $groups = [];
	  foreach( $categories as $c ){
		$groups[$c->id()]['entity'] = $c;
		$groups[$c->id()]['child_no'] = count( Category::loadAllChildren( $c->id() ) );
	  }

      $data = [
        'groups' => $groups
      ];
	  
      return [
        '#theme' => x::getThemeName(),
        '#data' => $data,
      ];
	}
  public static function add() {

    $parent_id  = x::in('parent_id');
    $re = Category::add($parent_id, x::in('name', ''));

	if( $parent_id == 0 ) $redirect_url = '/library/admin/category?';
	else {
      $group = Category::groupRoot($parent_id);
      $redirect_url = '/library/category/admin/group/list?parent_id=' . $group->id();
    }

    if ( x::isError($re) ) {
      $redirect_url .= "&error=$re[0]&message=$re[1]";
    }



    return new RedirectResponse( $redirect_url );
  }
  
  public static function del() {
	$id = x::in('id');
    if ( x::in('confirmed', '') != 'yes' && $children = Category::loadAllChildren($id) ) {
      $redirect_url = "/library/category/admin/delete/confirm?id=$id";
    }
    else {
      $is_root = Category::isRoot($id);
	  if ( $is_root ) $redirect_url = '/library/admin/category';
      else $redirect_url = '/library/category/admin/group/list?parent_id=' . Category::getRootID($id);
      Category::deleteAll($id );      
    }
    return new RedirectResponse( $redirect_url );
  }
  
  public static function update() {	
	$id =  \Drupal::request()->get('id');
	$name =  \Drupal::request()->get('name');
   
	
	if( $id == 0 ) $redirect_url = '/library/admin/category';
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
