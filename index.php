<?php
require_once('./controller/classes.php');
?>
<form action="controller/calculate.php" method="post">
	Investment Amount:<input type="text" name="inv_amount" value="5000"><br/>
	Payment Type:<select	name="payment_type">
		<option value="blended">Blended Interest and Principal</option>
		<option value="fixed">Fixed-Interest</option>
		<option value="compounding">Compounding</option>
	</select><br/> 
	interest:<input type="text" name="interest" value="5.0">%<br/>
	Term:<select name="term">
		<option value="5">5 Year</option>
		<option value="3">3 Year</option>
	</select> <br/>
	<input type="submit" value="submit" />

</form>
