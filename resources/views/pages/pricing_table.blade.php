<?php
$loggedIn = Auth::check();
$envProd = \App::environment('production');
?>
<div class="row text-center">
    <div class="col-sm-4 pricing-option">
        <h2>Starter</h2>
        <ul>
            <li><strong><a href="{{ URL::route('faq') }}#what_is_visitors_tested">10,000 visitors tested</a></strong>
            <span class="glyphicon glyphicon-question-sign" aria-hidden="true" title="A/B Testing Lab does NOT provide any new visitors to your website. This is the number of your visitors that can be included in your content experiments. Click the link for a full explanation if you have any questions."></span>
            </li>
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
            <li><strong><a href="{{ URL::route('faq') }}#what_is_visitors_tested">50,000 visitors tested</a></strong>
            <span class="glyphicon glyphicon-question-sign" aria-hidden="true" title="A/B Testing Lab does NOT provide any new visitors to your website. This is the number of your visitors that can be included in your content experiments. Click the link for a full explanation if you have any questions."></span>
            </li>
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
                <input type="hidden" name="hosted_button_id" value="K8HUJHWB38QMS">
                <input type="submit" value="Buy now" name="submit" title="PayPal - The safer, easier way to pay online!" class="btn btn-primary btn-lg">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                </form>
            @else
                <!-- testing -->
                <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top" class="buy">
                @if($loggedIn)
                    <input type="hidden" name="custom" value="{{ Auth::user()->email }}">
                @endif
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="W89XDHAGD8WCU">
                <input type="submit" value="Buy now" name="submit" title="PayPal - The safer, easier way to pay online!" class="btn btn-primary">
                <img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
                </form>
            @endif
        @else
            <a href="{{ URL::route('register.buy') }}" class="btn btn-primary btn-lg buy">Buy now</a>
        @endif
    </div>

    <div class="col-sm-4 pricing-option">
        <h2>Business</h2>
        <ul>
            <li><strong><a href="{{ URL::route('faq') }}#what_is_visitors_tested">100,000 visitors tested</a></strong>
            <span class="glyphicon glyphicon-question-sign" aria-hidden="true" title="A/B Testing Lab does NOT provide any new visitors to your website. This is the number of your visitors that can be included in your content experiments. Click the link for a full explanation if you have any questions."></span>
            </li>
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
                <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top" class="buy">
                @if($loggedIn)
                    <input type="hidden" name="custom" value="{{ Auth::user()->email }}">
                @endif
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="ZFVALHZ89HYYA">
                <input type="submit" value="Buy now" name="submit" title="PayPal - The safer, easier way to pay online!" class="btn btn-primary">
                <img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
                </form>
            @endif
        @else
            <a href="{{ URL::route('register.buy') }}" class="btn btn-primary buy">Buy now</a>
        @endif
    </div>
</div>
<p class="text-center margin-top">
    <strong>Important!</strong>
    A/B Testing Lab does NOT provide any new visitors to your website. This service provides the tools to run content experiments with the traffic that your website is already getting by itself. When your plan ends or you stop the tests, your visitors will see the default version of the website without any changes. Simple as that.<br>
    <a href="{{ URL::route('faq') }}">Click here</a> for more information.
</p>
<div class="text-center margin-top">
<img src="assets/img/payment_options.png">
</div>