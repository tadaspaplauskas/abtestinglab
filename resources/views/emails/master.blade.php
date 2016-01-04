<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>@yield('title')</title>
    </head>
    <body bgcolor="#f6f6f6" style="font-family: Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; -webkit-font-smoothing: antialiased; height: 100%; -webkit-text-size-adjust: none; width: 100% !important; ">
        <img src="{{ url('/assets/img/abtl_logo_email.png') }}" style="max-height: 80px; max-width: 600px; margin: 2em auto 0 auto; display: block;" alt="A/B Testing Lab" title="A/B Testing Lab">
        <!-- body -->
        <table class="body-wrap" bgcolor="#f6f6f6" style="width: 100%; margin: 0; padding: 20px;"><tr>
            <td></td>
            <td class="container" bgcolor="#FFFFFF" style="clear: both !important; display: block !important; max-width: 600px !important; margin: 0 auto; padding: 20px; border: 1px solid #f0f0f0;">
            <!-- content -->
            <div class="content" style="display: block; max-width: 600px; margin: 0 auto; padding: 0;">
                <table style="width: 100%;">
                    <tr>
                    <td style="font-size: 14px; line-height: 1.6em;">
                        <h2 style="font-size: 28px; line-height: 1.2em; color: #111111; font-weight: 200; margin: 0 0 10px; padding: 0;">
                            @yield('title')
                        </h2>
                        @yield('content')

                        @if (isset($mainLink['link']) && isset($mainLink['title']))
                            <!-- button -->
                            <table class="btn-primary" cellpadding="0" cellspacing="0" border="0" style="width: auto !important; Margin: 0 0 10px; padding: 0;"><tr>
                                <td style="font-size: 14px; line-height: 1.6em; border-radius: 25px; text-align: center; vertical-align: top; background: #348eda; " align="center" bgcolor="#348eda" valign="top">
                                    <a href="{{ $mainLink['link'] }}" style="font-family: Helvetica, Arial, sans-serif; font-size: 100%; line-height: 2; color: #ffffff; border-radius: 25px; display: inline-block; cursor: pointer; border-color: #348eda; font-weight: bold; text-decoration: none; background: #348eda; border-style: solid; border-width: 10px 20px;">
                                        {{ $mainLink['title'] }}
                                    </a>
                                </td>
                            </tr>
                            </table>
                        @endif

                        @yield('post_scriptum')
                    </td>
                </tr>
                </table>
            </div>
            <!-- /content -->
            </td>
            <td></td>
            </tr>
        </table>
        <!-- /body -->
        <!-- footer -->
        <table class="footer-wrap" style="clear: both !important; width: 100%; "><tr>
            <td></td>
            <td class="container" style="clear: both !important; display: block !important; max-width: 600px !important; margin: 0 auto; padding: 0;">

            <!-- content -->
            <div class="content" style="display: block; max-width: 600px; margin: 0 auto; padding: 0;">
                <table style="width: 100%; "><tr>
                    <td align="center">
                        <p style="font-size: 11px; color: #666666; font-weight: normal; margin: 0 0 10px; padding: 0;">
                            Don't want to receive these emails in the future? You can unsubscribe by changing email settings
                            <a href="{{ URL::route('account') }}" target="blank" style="color: #999999;"><unsubscribe>here</unsubscribe></a>.
                        </p>
                    </td>
                    </tr>
                </table>
            </div>
            <!-- /content -->
            </td>
            <td></td>
            </tr>
        </table>
        <!-- /footer -->
    </body>
</html>