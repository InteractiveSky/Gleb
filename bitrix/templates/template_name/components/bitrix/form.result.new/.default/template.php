<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<div class="b-form">
	<?if ($arResult["isFormErrors"] == "Y"):?><?=$arResult["FORM_ERRORS_TEXT"];?><?endif;?>

	<?=$arResult["FORM_NOTE"]?>
	<?=$arResult["FORM_HEADER"];?>
	<?foreach($arResult['arQuestions'] as $key=>$arQuestions){?>
		<?switch($arResult['arAnswers'][$key][0]['FIELD_TYPE']){
			case 'hidden':
				$label = '';
				break;
			case 'dropdown':
				$label = '<label class="b-auth--label">' . $arQuestions['TITLE'] . (($arQuestions['REQUIRED'] == 'Y')?'<span class="b-auth--required">*</span>':'') .'</label>';
				$label .= '<select class="b-auth--input" name="form_dropdown_' . $key . '" id="form_dropdown_' . $key . '">';
				break;
			case 'multiselect':
				$label = '<label class="b-auth--label">' . $arQuestions['TITLE'] . (($arQuestions['REQUIRED'] == 'Y')?'<span class="b-auth--required">*</span>':'') . '</label>';
				$label .= '<select class="b-auth--input" multiple="" name="form_multiselect_' . $key . '[]" id="form_multiselect_' . $key . '[]" size="0">';
				break;
			default:
				$label = '<label class="b-auth--label">' . $arQuestions['TITLE'] . (($arQuestions['REQUIRED'] == 'Y')?'<span class="b-auth--required">*</span>':'') .'</label>';
				break;
		}
		if($label) {
			echo $label;
		}
		foreach($arResult['arAnswers'][$key] as $arAnswers){
			switch ($arAnswers['FIELD_TYPE']) {
				case 'text':
					$template = '<input type="text" class="b-auth--input" name="form_text_' . $arAnswers['ID'] . '" />';
					break;
				case 'textarea':
					$template = '<textarea class="b-auth--textarea" name="form_textarea_' . $arAnswers['ID'] . '"></textarea>';
					break;
				case 'radio':
					$template = '<label class="b-auth--radiolabel" for="form_radio_' . $arAnswers['ID'] . '"><input type="radio" class="b-auth--radio" id="form_radio_' . $arAnswers['ID'] . '" value="' . $arAnswers['ID'] . '" name="form_radio_' . $key . '" />' . $arAnswers['MESSAGE'] . '</label>';
					break;
				case 'checkbox':
					$template = '<label class="b-auth--radiolabel" for="form_checkbox_' . $arAnswers['ID'] . '"><input type="checkbox" id="form_checkbox_' . $arAnswers['ID'] . '" name="form_checkbox_' . $key . '[]" value="' . $arAnswers['ID'] . '" class="b-auth--radio">' . $arAnswers['MESSAGE'] . '</label>';
					break;
				case 'image':
					$template = '<input type="file" class="b-auth--input" name="form_image_' . $arAnswers['ID'] . '" />';
					break;
				case 'file':
					$template = '<input type="file" class="b-auth--input" name="form_file_' . $arAnswers['ID'] . '" />';
					break;
				case 'email':
					$template = '<input type="email" class="b-auth--input" name="form_email_' . $arAnswers['ID'] . '" />';
					break;
				case 'url':
					$template = '<input type="url" class="b-auth--input" name="form_url_' . $arAnswers['ID'] . '" />';
					break;
				case 'password':
					$template = '<input type="password" class="b-auth--input" name="form_password_' . $arAnswers['ID'] . '" />';
					break;
				case 'hidden':
					$template = '<input type="hidden" class="b-auth--input" name="form_password_' . $arAnswers['ID'] . '" />';
					break;
				case 'dropdown':
					$template = '<option value="' . $arAnswers['ID'] . '">' . $arAnswers['MESSAGE'] . '</option>';
					break;
				case 'multiselect':
					$template = '<option value="' . $arAnswers['ID'] . '">' . $arAnswers['MESSAGE'] . '</option>';
					break;
			}
			echo $template;
		}
		switch($arResult['arAnswers'][$key][0]['FIELD_TYPE']){
			case 'dropdown':
			case 'multiselect':
				$postfix = '</select>';
				break;
			default:
				$postfix = '';
				break;
		}
		if($postfix) {
			echo $postfix;
		}
	}
	?>
	<input type="hidden" name="web_form_apply" value="Y" />
	<input type="submit" name="web_form_apply" value="<?=GetMessage("FORM_APPLY")?>" class="b-auth--submit" />
	<?=$arResult["FORM_FOOTER"]?>
	<div class="clear"></div>
</div>