<?php
namespace Drupal\library\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\file\Entity\File;

use Drupal\library\Library;
use Drupal\library\Member;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class API extends ControllerBase
{

    public function DefaultController()
    {
        $call = \Drupal::request()->get('call');
        $re = $this->$call();
        if (is_array($re)) {
        } else {
            $re = ['result' => $re];
        }
        if (!isset($re['code'])) $re['code'] = 0;
        $re = json_encode($re);
        $response = new JsonResponse($re);
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }


    public static function fileUpload() {		
        Library::log("fileUpload() begin");

        $uploads = Library::fileUploadInfo();		
        Library::log($uploads);
        file_prepare_directory($repo = DIR_LIBRARY_DATA, FILE_CREATE_DIRECTORY);
		
        $re = [];
        foreach( $uploads as $upload ) {
            Library::log("name: $upload[name], tmp_name: $upload[tmp_name]");
			//di( $upload );
			if( $upload['form_name'] == "profile_photo" ){
				if( strpos( $upload['type'], "image/" ) !== false ) {}
				else {
					//$error = "Invalid file format!";
					$data = [];
					$data['code'] = "-10001";
					$data['error'] = "Invalid file format!";
					return $data;
				}
				//di( $upload );exit;
			}
			
            if ( empty($upload['error'])  ) {
                $name = urlencode($upload['name']);
                if ( strlen($name) > 150 ) {
                    $pi = pathinfo($name);
                    $name = substr($pi['filename'], 0, 144) . '.' . $pi['extension'];
                }
                Library::log("name:$name");
                $path = $repo . $name;
                Library::log("path to save: $path");
                $file = file_save_data(file_get_contents($upload['tmp_name']), $path);
                if ($file) {
                    $upload['url'] = $file->url();
                    $upload['thumbnails'] = Library::getFileUrl( $file );
                    $upload['fid'] = $file->id();
                    $info['form_name'] = $upload['form_name'];
                    \Drupal::service('file.usage')->add($file, 'library', $upload['form_name'], 0); // refer buildguide
                    $file->set('status', 0)->save(); // refer #buildguide
                }
            }
            else {

            }
            $re[] = $upload;
        }
        return ['files'=>$re];
    }

	public static function getMemberProfile(){
		$request = \Drupal::request();
		$uid = $request->get('uid');
		$target_id = $request->get('target_id');
		
		$data = [];
		$data['uid'] = $uid;
		$data['target_id'] = $target_id;
		
		$data['markup'] = self::renderMemberProfile( $data['uid'], $data['target_id'] );
		
		return $data;
	}
	
	public static function renderMemberProfile( $uid, $target_id ){
		$member = Member::load( $uid );
		
		if( $member->photo ) $photo = $member->photo->thumbnails['url_thumbnail'];
		else $photo = "/modules/library/img/no_primary_photo.png";
		
		$uid = $member->id();
		$user_id = $member->label();
		$name = $member->extra['full_name'];
		
		$date = date( "M Y",$member->created->value );
		
		//if( !empty( $member->extra['location'] ) ) $location = $member->extra['location'];
		//else $location = "Location not specified";
		$location = "Philippines";
		
		$markup =	"
					<div class='member-profile-box' uid='$uid' target_id='$target_id'>
						<div class='triangle'></div>
						<div class='triangle two'></div>
						<div class='row user'>
							<div class='photo'>
								<img src='$photo'/>
							</div>
							<div class='info'>
								<div class='name'>$name <span>($user_id)</span></div>
								<div class='location'><img src='/modules/library/img/member-profile/location.png''/>$location</div>
								<div class='date'><img src='/modules/library/img/member-profile/time.png''/>$date</div>
							</div>
						</div>	
						<div class='row message'><span class='caption'>
							<a href='/message/send?receiver=$user_id'><img src='/modules/library/img/member-profile/message.png'/>Message</a>
						</span></div>
						<div class='row post'><span class='caption'><a href='/post/search?qn=y&q=$user_id'>Search Posts</a></span></div>
						<div class='row post'><span class='caption'><a href='/mall/item/search?user_id=$uid'>Items on Sale</a></span></div>
					</div>
					";
		//<div class='row view-profile'><span class='caption'><a href='/member/view/$user_id'>View Profile</a></span></div>
		return $markup;
	}
}
