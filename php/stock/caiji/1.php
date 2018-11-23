<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$ttt = 'R003(201000)';
$pos = strrpos($ttt, '(');
if ( !empty($pos) ) {
    $strTmp = substr($ttt, $pos+1,6);
    $strTmpEd = substr($ttt, 0,$pos);
    //echo $strTmpEd;
    //echo '<br>';
    echo $strTmp;
    echo '<br>';
    $posCode = substr($strTmp, 0, 1);
    
    echo $posCode;
}
