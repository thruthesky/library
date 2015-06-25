<?php
namespace Drupal\library\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\library\x;



class LibraryController extends ControllerBase {	
	public static function firstPage() {
      return [
        '#theme' => x::getThemeName(),
        '#data' => $data,
      ];
	}
}