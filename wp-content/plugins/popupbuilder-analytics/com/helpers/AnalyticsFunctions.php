<?php
namespace sgpban;

class AnalyticsFunctions
{
	public static function createSelectBox($data, $selectedValue, $attrs)
	{
		$attrString = '';
		$selected = '';

		if (!empty($attrs) && isset($attrs)) {
			foreach ($attrs as $attrName => $attrValue) {
				$attrString .= ''.$attrName.'="'.$attrValue.'" ';
			}
		}

		$selectBox = '<select '.$attrString.'>';

		foreach ($data as $value => $label) {

			if ($selectedValue == $value && !is_array($selectedValue)) {
				$selected = 'selected';
			}
			else if (is_array($selectedValue) && in_array($value, $selectedValue)) {
				$selected = 'selected';
			}
			$selectBox .= '<option value="'.$value.'" '.$selected.'>'.$label.'</option>';
			$selected = '';
		}

		$selectBox .= '</select>';

		return $selectBox;
	}

	public static function createCheckboxes($elements, $name, $newLine, $selectedInput, $class)
	{
		$str = '';

		foreach ($elements as $key => $element) {
			$breakLine = '';
			$infoIcon = '';
			$title = '';
			$value = '';
			$infoIcon = '';
			$checked = '';

			if (isset($element['title'])) {
				$title = $element['title'];
			}
			if (isset($element['value'])) {
				$value = $element['value'];
			}
			if ($newLine) {
				$breakLine = '<br>';
			}
			if (isset($element['info'])) {
				$infoIcon = $element['info'];
			}
			if ($element['value'] == $selectedInput) {
				$checked = 'checked';
			}
			else if (is_array($selectedInput) && in_array($element['value'], $selectedInput)) {
				$checked = 'checked';
			}
			$attrStr = '';
			if (isset($element['data-attributes'])) {
				foreach ($element['data-attributes'] as $key => $dataValue) {
					$attrStr .= $key.'="'.$dataValue.'" ';
				}
			}

			$str .= '<span class='.$class.'>'.$element['title'].'</span>';
			$str .= '<input type="checkbox" name='.$name.' '.$attrStr.' value='.$value.' $checked>'.$infoIcon.$breakLine;;

		}

		return $str;
	}

	public static function getAssocArrayFormDbSelect($queryResult)
	{
		$resultAssoc = array();
		foreach ($queryResult as $key => $value) {
			$assosArray = array_values($value);
			$resultAssoc[$assosArray['0']] = $assosArray['1'];
		}

		return $resultAssoc;
	}
}
