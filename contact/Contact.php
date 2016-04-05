<?php
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
// initialize my variables
//
$firstName="";
$email="";
$misc="";
$Outdoor= false;
$Headshoot = false;
$Event = false;
$reason = "Personal";

$yourURL = "http://www.uvm.edu/~jahernan/projects/andywebsite/contact/Contact.php";

//initialize flags for errors, one for each item
$firstNameERROR = false;
$emailERROR = false;
$miscERROR = false;

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
// if form has been submitted, validate the information
if (isset($_POST["butSubmit"])){

    //************************************************************
    // is the refeering web page the one we want or is someone trying 
    // to hack in. this is not 100% reliable */
    $fromPage = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; 


    if($fromPage != $yourURL){
        die("<p>Sorry you cannot access this page. Security breach detected and reported</p>");
    } 
    
    /*
        this function just converts all input to html entites to remove any potentially
        malicious coding
    */
    function clean($elem)
    {
        if(!is_array($elem))
            $elem = htmlentities($elem,ENT_QUOTES,"UTF-8");
        else
            foreach ($elem as $key => $value)
                $elem[$key] = clean($value);
        return $elem;
     }

     // be sure to clean out any code that was submitted
     if(isset($_POST)) $_CLEAN['POST'] = clean($_POST); 

     /* now we refer to the $_CLEAN arrays instead of the get or post
      * ex: $to = $_CLEAN['GET']['txtEmail'];
      * or: $to = $_CLEAN['POST']['txtEmail'];
      */
     
     
     //check for errors
     include ("validation_functions.php"); // you need to create this file (see link in lecture notes)
     $errorMsg=array();
    
     //%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
     // begin testing each form element 
     
     // Test first name for empty and valid characters
     $firstName=$_CLEAN['POST']['personsName'];
     if(empty($firstName)){
        $errorMsg[]="Please enter your First Name";
        $firstNameERROR = true;
     } else {
        $valid = verifyAlphaNum ($firstName); /* test for non-valid  data */
        if (!$valid){ 
            $errorMsg[]="Name must be letters and numbers, spaces, dashes and single quotes only.";
            $firstNameERROR = true;
        }
     }
    
    // test email for empty and valid format
    // 
    $email=$_CLEAN['POST']['Email'];
if(empty($email)){
        $errorMsg[]="Please enter email";
        $emailERROR = true;
     } else {
        $valid = verifyEmail ($email); /* test for non-valid  data */
        if (!$valid){ 
            $errorMsg[]="email is wrong and/or unexpected character.";
            $emailERROR = true;
        }
     }
    
    // 
    // test number for empty, value and range
    // 
    $misc=$_CLEAN['POST']['Budget'];
    if(empty($misc)){
        $errorMsg[]="Please enter a value";
        $miscERROR = true;
     } else {
        $valid = verifyphone ($misc); /* test for non-valid  data */
        if (!$valid){ 
            $errorMsg[]="inappropriate value for budget";
            $miscERROR = true;
        }
     }
     
    // set values for other object types.
    // This makes these values on the form 'sticky' but reseting the value
    // to what the person entered
    
    // example for check boxes
    if(isset($_CLEAN['POST']["Outdoors"])){
            $Outdoor = true;
    }

    if(isset($_CLEAN['POST']["Headshoot"])){
            $Headshoot = true;
    }
      if(isset($_CLEAN['POST']["Event"])){
            $Event = true;
    }
    
    // example radio button
    if(isset($_CLEAN['POST']["reason"])){
            $reason = $_POST["reason"];
    }

    //%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
    // our form data is valid so we can mail it
    if(!$errorMsg){    
        //now i can mail it
        $to = $email;
        //if ($debug) print "<p>Form has been sent ".$to."</p>";
        // just sets these variable to the current date and time
        $todaysDate=strftime("%x");
        $currentTime=strftime("%X");

        /* subject line for the email message */
        $subject = "Web Order: " . $todaysDate ;

        // be sure to change Your Site and yoursite to something meaningful
        $mailFrom = "customer@hfilmsproduction.com";

        $bcc = "drmejor14@aol.com"; // if you need to Blind Carbon Copy (person who fills out form will NOT see this) ex:
                   // $bcc = "youremail@yoursite.com";


        //build your message here.
        $message  = '<p>This is your confirmation on your order placed on ' . $todaysDate;
        $message .= '. please print and keep a copy for your records.</p>';
        $message .=  
        /* message */
        $messageTop  = '<html><head><title>' . $subject . '</title></head><body>';

        // $$$$$$$$$$$$ build message Here
        /* here you can customize the message if you need to */

        /* ########################################################################### */
        // This block simply adds the items filled in on the form to the email message
        
        if(isset($_CLEAN['POST'])) {
            foreach ($_CLEAN['POST'] as $key => $value){
                    $message .= "<p>" . $key . " = " . $value . "</p>";
            }
        }
        
        /* ########################################################################### */

        /* To send HTML mail, you can set the Content-type header. */
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";

        /* additional headers */
        $headers .= "From: " . $mailFrom . "\r\n";

        if ($bcc!="") $headers .= "Bcc: " . $bcc . "\r\n";

        $mailMessage = $messageTop . $message;

        /* this line actually sends the email */ 
             $blnMail=mail($to, $subject, $mailMessage, $headers);
    } // no errors our form is valid
    // rest of code goes ABOVE this line
} // ends isset($_POST["butSubmit"]
?>


<?
include '../head.php';
?>
<body id="contact">
<? 
include '../top.php';
?>  
    <div id="body2">
<div id="errors">
    <?
if($errorMsg){
    echo "<ol>\n";
    foreach($errorMsg as $err){
        echo "<li>" . $err . "</li>\n";
    }
    echo "</ol>\n";
} ?>
</div>

<form name="form" action="<? print $_SERVER['PHP_SELF']; ?>" 
            method="post"
            id="frmRegister"
            enctype="multipart/form-data"
            onsubmit="return confirm('Are you sure you want to submit this form?');">
            
<fieldset class="intro">
<legend>Complete the following form</legend>

<fieldset class="contact">
<legend>Contact Information</legend>
   <div class="contactBox">
    <label for="txtFname" class="required">Name:</label>
      <input type="text" id="personsName" name="personsName" value="<?php echo $firstName; ?>" 
            tabindex="100" maxlength="30" required placeholder="enter your name" 
                <?php if($firstNameERROR) echo 'class="mistake"' ?>
                autofocus onfocus="this.select()">
  </div>
<div class="contactBox">
    <label for="Email" class="required">Email:</label>
      <input type="Email" id="Email" name="Email" value="<?php echo $email; ?>"
            tabindex="110" maxlength="45" required placeholder="enter a valid email address" onfocus="this.select()" >
 </div>       
      <div class="contactBox">
        <label for="Budget" class="required">Budget:</label>
      <input type="text" id="Budget" name="Budget" value="<?php echo $misc; ?>"
            tabindex="110" maxlength="10" required placeholder="enter a budget" onfocus="this.select()" >
    </div>
</fieldset>                    

<fieldset class="checkbox">
    <legend>Do you want (check all that apply):</legend>
      <label><input type="checkbox" id="chkSleep" name="Types of shots" value="Outdoors" tabindex="221" 
            <?php if($Outdoor) echo ' checked="checked" ';?>/> Outdoors</label>
            
    <label><input type="checkbox" id="chkhotshower" name="Types of shots" value="Headshot" tabindex="223" 
            <?php if($Headshoot) echo ' checked="checked" ';?>/> Headshot</label>
    
    <label><input type="checkbox" id="chkhomework" name="Types of shots" value="Event" tabindex="223" 
            <?php if($Event) echo ' checked="checked" ';?>/> Event</label>
</fieldset>

<fieldset class="radio">
    <legend>What type of photo shoot?</legend>
    <label><input type="radio" id="reasonPersonal" name="reason" value="Personal" tabindex="231" 
            <?php if($reason=="Personal") echo ' checked="checked" ';?>/>Personal</label>
            
    <label><input type="radio" id="reasonCommercial" name="reason" value="Commercial" tabindex="233" 
            <?php if($reason=="Commercial") echo ' checked="checked" ';?>/>Commercial</label>
</fieldset>   

<fieldset class="feedback">
    <legend>Any additional information</legend>
    <label for="txtComments" class="required"></label>
    <textarea id="txtComments" name="AdditionalInformation" 
              tabindex="602" onfocus="this.select()"></textarea>
</fieldset>

<fieldset class="buttons">
    <legend></legend>                
    <input type="submit" id="butSubmit" name="butSubmit" value="Register" tabindex="991" class="button" />

    <input type="reset" id="butReset" name="butReset" value="Reset Form" tabindex="993" class="button" onclick="reSetForm()" />
</fieldset>                    

</fieldset>
</form>
        </div>
</body>
</html>