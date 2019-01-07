<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div class="news-detail">
	<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arResult["DETAIL_PICTURE"])):?>
		<img
			class="detail_picture"
			border="0"
			src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>"
			width="<?=$arResult["DETAIL_PICTURE"]["WIDTH"]?>"
			height="<?=$arResult["DETAIL_PICTURE"]["HEIGHT"]?>"
			alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>"
			title="<?=$arResult["DETAIL_PICTURE"]["TITLE"]?>"
			/>
	<?endif?>
	<?if($arParams["DISPLAY_DATE"]!="N" && $arResult["DISPLAY_ACTIVE_FROM"]):?>
		<span class="news-date-time"><?=$arResult["DISPLAY_ACTIVE_FROM"]?></span>
	<?endif;?>
	<?if($arParams["DISPLAY_NAME"]!="N" && $arResult["NAME"]):?>
		<h3><?=$arResult["NAME"]?></h3>
	<?endif;?>
	<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arResult["FIELDS"]["PREVIEW_TEXT"]):?>
		<p><?=$arResult["FIELDS"]["PREVIEW_TEXT"];unset($arResult["FIELDS"]["PREVIEW_TEXT"]);?></p>
	<?endif;?>
	<?if($arResult["NAV_RESULT"]):?>
		<?if($arParams["DISPLAY_TOP_PAGER"]):?><?=$arResult["NAV_STRING"]?><br /><?endif;?>
		<?echo $arResult["NAV_TEXT"];?>
		<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?><br /><?=$arResult["NAV_STRING"]?><?endif;?>
	<?elseif(strlen($arResult["DETAIL_TEXT"])>0):?>
		<?echo $arResult["DETAIL_TEXT"];?>
	<?else:?>
		<?echo $arResult["PREVIEW_TEXT"];?>
	<?endif?>
	<div style="clear:both"></div>
	<br />
	<?foreach($arResult["FIELDS"] as $code=>$value):
		if ('PREVIEW_PICTURE' == $code || 'DETAIL_PICTURE' == $code)
		{
			?><?=GetMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?
			if (!empty($value) && is_array($value))
			{
				?><img border="0" src="<?=$value["SRC"]?>" width="<?=$value["WIDTH"]?>" height="<?=$value["HEIGHT"]?>"><?
			}
		}
		else
		{
			?><?=GetMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?=$value;?><?
		}
		?><br />
	<?endforeach;
	foreach($arResult["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>

		<?=$arProperty["NAME"]?>:&nbsp;
		<?if(is_array($arProperty["DISPLAY_VALUE"])):?>
			<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
		<?else:?>
			<?=$arProperty["DISPLAY_VALUE"];?>
		<?endif?>
		<br />
	<?endforeach;?>	
	
	<div class="likeBlock">		
		<?if($USER->IsAuthorized()):?>	
			<form 
				id 		=	"formLikesBind" 
				class='likeBlock__form-likes' 
				method='post' 
				liked	=	"<?=($arResult['LIKES']['LIKE'])?>" 
				userID	=	"<?=$USER->GetID()?>"
				userLogin =	"<?=$USER->GetLogin()?>"
				elementID = "<?=$arResult['ID']?>"
				action='<?=$templateFolder?>/send_like.php'>
				<?if(($arResult['LIKES']['LIKE'])==0):?>
					<button class="form-likes__button likeBlock__likeFon">Мне нравиться</button>
				<?else:?>		
					<button class="form-likes__button">Больше не нравится</button>			
				<?endif;?>

			</form>
		<?endif?>			
		<div class="likeBlock__usersContainer">	
			<?if(!empty($arResult['LIKES']['USERS'])):?>
				<div class="likeBlock__userHeader"><?=\Bitrix\Main\Localization\Loc::getMessage('LIKE_ARTICLE_HEADER')?></div>
			<?endif?>	
			<div class="likeBlock__usersList">
				<?foreach($arResult['LIKES']['USERS'] as $us):?>
					<div class="likeBlock__user" userID="<?=$us['USER_ID']?>" ><?=$us['LOGIN']?></div>				
				<?endforeach;?>
			</div>			
		</div>		
	</div>	
	
</div>


