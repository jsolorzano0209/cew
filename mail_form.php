<script type="text/javascript">
function validate()
{
       if(document.getElementById('names').value=="Name")
        {
        alert("Enter Name");
        document.getElementById('names').focus();
        return false;
        }
       if(document.getElementById('email').value=="E-mail ID")
        {
        alert("Enter E-mail ID");
        document.getElementById('email').focus();
        return false;
        }
       if(document.getElementById('phone').value=="Enter your Phone")
        {
        alert("Enter Phone");
        document.getElementById('phone').focus();
        return false;
        }
        
        
 if(document.getElementById('email').value!="")
        {
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(document.getElementById('email').value)){
        return (true)
        }
        alert("Invalid E-mail ID! Please re-enter.")
        document.getElementById('email').focus();
        return (false)
        }
       return true;
}
</script>

<div id="frightbox">
<h4>Sign Up To Receive News &amp; Specials!</h4>
<div id="search">
<form method="post">
Name: <input type="text" value="Name" name="names" id="names" class="blk" onfocus="this.value=(this.value=='Name') ? '' : this.value;" onblur="this.value=(this.value=='') ? 'Name' : this.value;"  /><br /> 
Email: <input type="text" value="E-mail ID" name="email" id="email" class="blk" onfocus="this.value=(this.value=='E-mail ID') ? '' : this.value;" onblur="this.value=(this.value=='') ? 'E-mail ID' : this.value;"  /><br />
Phone: <input type="text" value="Enter your Phone" name="phone" id="phone" class="blk" onfocus="this.value=(this.value=='Enter your Phone') ? '' : this.value;" onblur="this.value=(this.value=='') ? 'Enter your Phone' : this.value;"  /><br />
<input type="submit" class="but" value="send" onclick="return validate();"/>
</form>
</div>
</div>

<?php 
if(isset($_POST['email']) or ($_POST['but']))
{
    $email=$_POST['txtemail'];
   $to  = 'sham.b@vividinfotech.com';
// subject
$subject = 'Monthly specials for some great savings';
// message
$message = "
<html>
<head>
  <title>Royal Ink</title>
</head>
<body>
  <p>Royal Ink</p>
  <table>
    <tr>
      <td>Email:</td><td>".$email."</td>
    </tr>
  </table>
</body>
</html>
";
$from="From:".$email;
// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
// Additional headers
$headers .=$from . "\r\n";
mail($to, $subject, $message, $headers);
?>
<script type="text/javascript">
alert("Submitted Successfully");
</script>
<?php } ?>