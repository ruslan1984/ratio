<?require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
if (!\Bitrix\Main\Loader::includeModule('iblock'))die();

include_once 'LikeUsers.php';
	
$userID		=	$_POST['userID'];
$elementID	=	$_POST['elementID'];
	
if((empty($userID))||(empty($elementID))) return;
	
$likeUsers = new LikeUsers($userID,$elementID);	
$likeUsers->clickLike();
 	 	

