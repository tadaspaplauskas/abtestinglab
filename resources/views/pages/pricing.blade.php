@extends('master')

@section('title', 'Pricing')

@section('content')

<div class="row text-center">
	<div class="col-sm-4">
		<h2>Starter</h2>
		10 000 unique visitors
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="6KTN9KX22WT5Y">
		<input type="submit" value="Buy now" name="submit" title="PayPal - The safer, easier way to pay online!" class="btn btn-primary btn-sm">
		<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>

		TESTING
<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="XBK8SCQNAN7ZL">
<input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>




	</div>

	<div class="col-sm-4">
		<h1>Most Popular</h1>
		50 000 unique visitors
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="BKVV2FCRND5G4">
		<input type="submit" value="Buy now" name="submit" title="PayPal - The safer, easier way to pay online!" class="btn btn-primary btn-lg">
		<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>


	</div>

	<div class="col-sm-4">
		<h2>Starter</h2>
		100 000 unique visitors
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="8FH9S76EVR6T2">
		<input type="submit" value="Buy now" name="submit" title="PayPal - The safer, easier way to pay online!" class="btn btn-primary btn-sm">
		<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>

	</div>
</div>

<div class="text-center">
<img src="assets/img/payment_options.png">
</div>

@endsection