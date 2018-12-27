<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function nameToSafe($string, $maxlen = 255) {
    
    if (mb_check_encoding($string, 'UTF-8') === true) {
      $string = mb_strtolower(trim(substr($string, 0, $maxlen)));
      $string = str_replace(array('ü', 'ä', 'ö', 'Ã¼', 'Ã¶', 'Ã¤', ' ', 'ï¿½', 'ÃŸ', 'ß'),
                               array('ue','ae','oe', 'ue','oe','ae', '_', '_', 'ss', 'ss'),
                               $string);
      $string = mb_convert_encoding($string, 'ISO-8859-1', 'UTF-8');
      $string = str_replace(array('u?','o?', 'a?', 'e?'),
                            array('ue','oe','ae', 'e'),
                            $string);
    }
    else {
       $string = strtolower(trim(substr($string, 0, $maxlen)));
       $string = str_replace(array('ü', 'ä', 'ö', ' ', 'ß'),
                               array('ue','ae','oe', '_', 'ss'),
                               $string);
    }
    $noalpha = utf8_decode('ÁÉÍÓÚÝáéíóúýÂÊÎÔÛâêîôûÀÈÌÒÙàèìòùÄËÏÖÜäëïöüÿÃãÕõÅåÑñÇç@°ºª');
    $alpha   = 'AEIOUYaeiouyAEIOUaeiouAEIOUaeiouAEIOUaeiouyAaOoAaNnCcaooa';
    $string = strtr($string, $noalpha, $alpha);
    return preg_replace('/[^a-zA-Z0-9,_\+\(\)\-]/', '_', $string);
	}