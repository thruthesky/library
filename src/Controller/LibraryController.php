<?php
namespace Drupal\library\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\library\Library;



class LibraryController extends ControllerBase {	
	public static function firstPage() {
	  $data = [];
		
      return [
        '#theme' => Library::getThemeName(),
        '#data' => $data,
      ];
	}
}