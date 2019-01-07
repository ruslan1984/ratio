<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use \Bitrix\Main\Data\Cache;

class LikeUsers{
	

	private $userId;		// идентификатор пользователя
	private $elementId;		// идентификатор статьи
	private $userLikes;		// список идентификторов пользователей кому статья понравилась
	private $propertyCode;	// код свойства инфоблока в котором хранятся лайкнувшие пользователи
	private $cacheTime;		// время хранения кэша
	private $cacheCode; 	// идентификатор хранилища кэша для текущей статьи
	private $cachePuth; 	// путь для хранилища кэша

	function __construct($userId, $elementId) {
		if (!\Bitrix\Main\Loader::includeModule('iblock'))die();
    	$this->elementId=$elementId;
    	$this->userId	=$userId;
    	$this->userLikes =$this->getUserLikes();		
		$this->propertyCode = "LIKES";
		$this->cacheTime="3600";
		$this->cacheCode = "LIKES";
		$this->cachePuth = "/LikeUsersNews/Post{$elementId}/";
   	}

   	// получить список лайкнувших пользователей	
	private function setLoginLikeUsers(){
		foreach($this->userLikes as $us){
			$result[]=[
				'USER_ID'	=>	$us,
				'LOGIN'		=>	self::getUserLogin($us)
				];
		}		
		return $result;
	}
	// если лайкнули
	public function isLiked(){
		return in_array($this->userId,$this->userLikes);
	}

	// получить LOGIN пользователя по ID
	private function getUserLogin($userId){
		$rsUser = CUser::GetByID($userId);
		$arUser = $rsUser->Fetch();	
		return $arUser['LOGIN']; 	
	}

	//получить список лайкнувших пользователей
	private function getUserLikes(){
	 	$arSelect = Array("PROPERTY_LIKES");
		$arFilter = Array(false, "ID"=>$this->elementId,"ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
		$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
		while($ob = $res->Fetch())
		{  	
		 	if(!empty($ob['PROPERTY_LIKES_VALUE'])){
	 			$result[]=$ob['PROPERTY_LIKES_VALUE']; 	
	 		}
		}
		return $result; 
	}

	//	Добавляем лайк
	private function addLikeUser(){
		if(!in_array($this->userId, $this->userLikes)){
			array_unshift($this->userLikes,$this->userId);	 			
		}
		return $this->userLikes;
	}

	// Удалить лайк
	private function removeLikeUser(){
		if(in_array($this->userId, $this->userLikes)){
			foreach($this->userLikes as $key=>$user){		
				if($user==$this->userId){			
					unset($this->userLikes[$key]);
				}	
			}
		}
		return $this->userLikes;
	}	

	// подготовка массива для обновления методом CIBlockElement::SetPropertyValueCode
	private function readyArrayToUpdate(){
		foreach($this->userLikes as $us){
	 			$result[]=['VALUE'=>$us];
	 	}
	 	return $result;
	}
	// Обработка лайка
	private function Like(){		
		self::addLikeUser();
	 	$userArray=self::readyArrayToUpdate();
	 	CIBlockElement::SetPropertyValueCode($this->elementId, $this->propertyCode, $userArray);
		self::updateCache();
	}

	// Обработка отмены лайка
	private function Dislike(){				
		self::removeLikeUser();
	 	$userArray=self::readyArrayToUpdate();
	 	CIBlockElement::SetPropertyValueCode($this->elementId, $this->propertyCode, $userArray);
		self::updateCache();
	}

	// кликнули на лайк
	public function clickLike(){
		(self::isLiked())?self::Dislike() : self::Like();
	}
	// очистить кэш
	private function clearCache(){	
		$cache = Cache::createInstance();
		$cache->cleanDir($this->cachePuth);
	}

	//Обновить кэш элемента
	private function updateCache(){
		self::clearCache();
		$cache = Cache::createInstance(); 		
		$cache->initCache($this->cacheTime, $this->cacheCode, $this->cachePuth);
		if($cache->startDataCache()) 
		{	
			$result	= self::setLoginLikeUsers();			
		    $cache->endDataCache($result);
		}
	}
	// Получить список лайкнувших пользователей из кэша
	public function getCacheUserList(){
		$cache = Cache::createInstance(); 
		if ($cache->initCache($this->cacheTime, $this->cacheCode, $this->cachePuth))
		{    
		    return $cache->getVars();    
		}
		elseif($cache->startDataCache())
		{	
			$result=self::setLoginLikeUsers();
		    $cache->endDataCache($result); 
		    return $result;
		}
	}
}