<?php

namespace SME\CommonsBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Validator\Validator;

class FormHelper
{
	public function getFormErrors($form) {
		$errors = '';
		$erros = array();
		$errors_str = str_replace("\n", '|', $form->getErrorsAsString());
		$errors_str = explode('|', $errors_str);
		array_pop($errors_str);
		
		foreach ($errors_str as $x => $error_str) {
			if (substr_count($error_str, 'ERROR:') > 0) {
				$erros[$x] = trim(str_replace('ERROR: ', '', $errors_str[$x]));
			}
		}
		
		if ($erros) {
			
			$errors = '<div class="panel panel-danger" style="padding: 7px; width: 95%; margin-left: 2.5%; margin-bottom: 1%; margin-top: 2%;">';
			$errors .= '<div class="panel-heading">Por favor, verifique os itens abaixo:</div>';
			$errors .= '<div class="panel-body">';
			$errors .= '<ul style="font-size: 12px;">';
			
			foreach ($erros as $key => $error) {
				$errors .= '<li>' . $error . '</li>';
			}
			
			$errors .= '</ul></div></div>';
			
			return $errors;
		}
		
		return '<div class="alert alert-info" style="padding: 7px; font-size: 12px; width: 95%; margin-left: 2.5%; margin-bottom: 1%; margin-top: 2%;">Todos os campos com * são obrigatórios</div>';
	}    
        
        public function showCustomErrors ($errorArray) {
            $errors = '<div class="panel panel-danger" style="padding: 7px; width: 95%; margin-left: 2.5%; margin-bottom: 1%; margin-top: 2%;">';
            $errors .= '<div class="panel-heading">Por favor, verifique os itens abaixo:</div>';
            $errors .= '<div class="panel-body">';
            $errors .= '<ul style="font-size: 12px;">';

            foreach ($errorArray as $error) {
                $errors .= '<li>' . $error . '</li>';
            }
			
            $errors .= '</ul></div></div>';
            return $errors;
        }
}
