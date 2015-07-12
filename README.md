# Withcenter Library 모듈

본 모듈은 Withcenter IT Team 에서 개발하는 모듈의 라이브러리입니다.

본 문서에 대한 설명은 아래 링크를 참고하십시오.

https://docs.google.com/document/d/1koxonGQl20ER7HZqUfHd6L53YXT5fPlJxCEwrhRqsN4/pub

# 해야 할 것

* 명칭의 변경. 명칭 Library 는 너무 일반적이어서 좋지 않다.
	* Withcenter 를 줄여서 We 로 통칭한다.
	* WeLibrary - we::versioin(), we::config_get();
	* WeTheme - wt 
	* 위와 같이 접두어를 We 을 사용해서 명칭 변경을 한다.
	* 기존의 Library 클래스를 alias 처리하여 그대로 사용 할 수 있도록 한다.
* Sub Theme 기능은 분리를 할 것. 아무것에도 의존하지 않도록 할 것.
	* 관리자 페이지를 드루팔 관리자 페이지로 집어 넣을 것. 그러면 권한 관련 신경 쓸 것도 없음.  
* exportGroupConfig('gropu_name','file_path');
* importGroupConfig('group_name','file_paht');
* 회원 정보를 library_config 에 모두 같이 넣는데, 당장 분리 할 필요가 없다.
	* 분리를 해야하는 경우,
		* 본 모듈을 이용하는 사용자가 많아진 경우,

* /wi/multi
* 멀티 테마
* /library/theme/admin - config 기능을 통해서 도메인별로 스킨지정. 

* library_config 테이블 이름을 we_config 로 변경.
* we_config 와 똑 같은 we_member_meta 테이블을 만들어서 회원 정보만 따로 보관 할 것.
	* we_member 테이블은 id, name, mail, mobile, landline, birth year/month/day, address, location 등의 기본 정보를 저장하고,
	* we_member_meta 테이블은 그외의 모든 정보를 다 저장하도록 한다.
		* 즉, 기본 정보는 관리 및 검색이 용이하게 하고, 그 외의 정보는 그냥 we_config 형태로 모두 저장해 버린다.     
* we_config 의 code 만 따로 인덱싱을 할 것.


* /member
* /member/admin

# 이용안내

## 접속 경로

/library 로 접속을 하면, 모든 기능을 이용 할 수 있다.



## 회원 정보


* /member/register - 회원 가입
* /member/login - 로그인
* /user/logout - 로그아웃
* /member/update - 회원 정보 수정


