<?php
//$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
//
// This function mails the text passed in to the people specified 
// it requires the person sending it to and a message 
// CONSTRAINTS:
//      $to must not be empty
//      $to must be an email format
//      $cc must be an email format if its not empty
//      $bcc must be an email format if its not empty
//      
//      $message must not be empty
//      $message must have a minium number of characters
//      
//      $from should be cleand of invalid html before being sent here but needs 
//            to allow < and >
//      $message should be cleand of invalid html before being sent here
//
// function returns a boolean value
function sendMail($to, $cc, $bcc, $from, $subject, $message){ 
    $MIN_MESSAGE_LENGTH=40;
    
    // just checking to make sure the values passed in are reasonable
    if(empty($to)) return false;
    if(!(preg_match("/^([[:alnum:]]|_|\.|-)+@([[:alnum:]]|\.|-)+(\.)([a-z]{2,4})$/",$to))) return false;
    
    if($cc!=""){
        if(!(preg_match("/^([[:alnum:]]|_|\.|-)+@([[:alnum:]]|\.|-)+(\.)([a-z]{2,4})$/",$cc))) return false;
    }
    
    if($bcc!=""){
        if(!(preg_match("/^([[:alnum:]]|_|\.|-)+@([[:alnum:]]|\.|-)+(\.)([a-z]{2,4})$/",$bcc))) return false;
    }
    if(empty($message)) return false;
    if (strlen($message)<$MIN_MESSAGE_LENGTH) return false;
    
    $to = htmlentities($to,ENT_QUOTES,"UTF-8");
    $cc = htmlentities($cc,ENT_QUOTES,"UTF-8");
    $bcc = htmlentities($bcc,ENT_QUOTES,"UTF-8");
    
    $subject = htmlentities($subject,ENT_QUOTES,"UTF-8");
    
    // we cannot push message into html entites or we lose the format
    // of our email so be sure to do that before sending it to this function

    /* message */
    $messageTop  = '<html><head><title>' . $subject . '</title></head><body>';
    $mailMessage = $messageTop . $message;

    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";

    $headers .= "From: " . $from . "\r\n";

    if ($cc!="") $headers .= "CC: " . $cc . "\r\n";
    if ($bcc!="") $headers .= "Bcc: " . $bcc . "\r\n";

    /* this line actually sends the email */
    $blnMail=mail($to, $subject, $mailMessage, $headers);
    
    return $blnMail;
}
?>