<div class="six columns offset-by-two" style="padding-top:20px;">
	<form method="post" action="<?=$this->current_address;?>">
		<label for="email">Email Address</label>
		<input name="email" type="email" />
		
		<label for="pass">Passphrase</label>
		<input name="pass" type="password" />
	
		<input class="button" name="login" value="Sign in" type="submit" />
	</form>
</div>