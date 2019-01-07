<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use \Bitrix\Main\Data\Cache;

include_once 'LikeUsers.php';

$likeUsers = new LikeUsers($USER->GetID(),$arParams['ELEMENT_ID']);
$arResult['LIKES']['USERS'] = $likeUsers->getCacheUserList();

//$arResult['LIKES']['USERS'] = $likeUsers->setLoginLikeUsers($arResult['PROPERTIES']['LIKES']['VALUE']);

if($USER->IsAuthorized()):
	$arResult['LIKES']['LIKE']=$likeUsers->isLiked();;
endif;

