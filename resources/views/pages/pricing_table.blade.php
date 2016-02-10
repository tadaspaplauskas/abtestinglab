<?php
$loggedIn = Auth::check();
$envProd = \App::environment('production');
?>
<div class="row text-center">
    <div class="col-sm-4 pricing-option">
        <h2>Starter</h2>
        <ul>
            <li><strong>10,000 unique visitors</strong></li>
            <li>Full control over your resources</li>
            <li>Intuitive visual editor</li>
            <li>Unlimited websites</li>
            <li>Tests archive</li>
            <li>Full stats</li>
            <li class="price">$19</li>
        </ul>

        @if ($loggedIn)
            @if ($envProd)
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top" class="buy">
                @if($loggedIn)
                    <input type="hidden" name="custom" value="{{ Auth::user()->email }}">
                @endif
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="6KTN9KX22WT5Y">
                <input type="submit" value="Buy now" name="submit" title="PayPal - The safer, easier way to pay online!" class="btn btn-primary">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                </form>
            @else
                <!-- testing -->
                <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top" class="buy">
                @if($loggedIn)
                    <input type="hidden" name="custom" value="{{ Auth::user()->email }}">
                @endif
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="XBK8SCQNAN7ZL">
                <input type="submit" value="Buy now" name="submit" title="PayPal - The safer, easier way to pay online!" class="btn btn-primary">
                <img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
                </form>
            @endif
        @else
            <a href="{{ URL::route('register.buy') }}" class="btn btn-primary buy">Buy now</a>
        @endif
    </div>

    <div class="col-sm-4 most-popular pricing-option">
        <h2>Most Popular</h2>
        <ul>
            <li><strong>50,000 unique visitors</strong></li>
            <li>Full control over your resources</li>
            <li>Intuitive visual editor</li>
            <li>Unlimited websites</li>
            <li>Tests archive</li>
            <li>Full stats</li>
            <li class="price">$29</li>
        </ul>

        @if ($loggedIn)
            @if ($envProd)
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top" class="buy">
                @if($loggedIn)
                    <input type="hidden" name="custom" value="{{ Auth::user()->email }}">
                @endif
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="BKVV2FCRND5G4">
                <input type="submit" value="Buy now" name="submit" title="PayPal - The safer, easier way to pay online!" class="btn btn-primary btn-lg">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                </form>
            @else
                <!-- testing -->
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top" class="buy">
                @if($loggedIn)
                    <input type="hidden" name="custom" value="{{ Auth::user()->email }}">
                @endif
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="BKVV2FCRND5G4">
                <input type="submit" value="Buy now" name="submit" title="PayPal - The safer, easier way to pay online!" class="btn btn-primary btn-lg">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                </form>
            @endif
        @else
            <a href="{{ URL::route('register.buy') }}" class="btn btn-primary btn-lg buy">Buy now</a>
        @endif
    </div>

    <div class="col-sm-4 pricing-option">
        <h2>Business</h2>
        <ul>
            <li><strong>100,000 unique visitors</strong></li>
            <li>Full control over your resources</li>
            <li>Intuitive visual editor</li>
            <li>Unlimited websites</li>
            <li>Tests archive</li>
            <li>Full stats</li>
            <li class="price">$49</li>
        </ul>

        @if ($loggedIn)
            @if ($envProd)
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top" class="buy">
                @if($loggedIn)
                    <input type="hidden" name="custom" value="{{ Auth::user()->email }}">
                @endif
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="8FH9S76EVR6T2">
                <input type="submit" value="Buy now" name="submit" title="PayPal - The safer, easier way to pay online!" class="btn btn-primary">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                </form>
            @else
                <!-- testing -->
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top" class="buy">
                @if($loggedIn)
                    <input type="hidden" name="custom" value="{{ Auth::user()->email }}">
                @endif
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="8FH9S76EVR6T2">
                <input type="submit" value="Buy now" name="submit" title="PayPal - The safer, easier way to pay online!" class="btn btn-primary">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                </form>
            @endif
        @else
            <a href="{{ URL::route('register.buy') }}" class="btn btn-primary buy">Buy now</a>
        @endif
    </div>
</div>

<div class="text-center payment-options">
<img src="assets/img/payment_options.png">
</div>