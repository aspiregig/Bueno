<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>welcome to bueno</title>
    <style type="text/css">
        <!--
        body {
            margin-left: 0px;
            margin-top: 0px;
            margin-right: 0px;
            margin-bottom: 0px;
        }
        -->
    </style></head>

<body>


<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td style="background-color:#231f20;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td valign="middle" style="padding:20px;"> <a href="{{ route('pages.index') }}"><img src="{{ asset('images/email_logo.jpg')}}"/></a></td>
                    <td align="right" style="font-family:Arial, Helvetica, sans-serif; font-size:20px; color:#FFFFFF; float:right; vertical-align:middle; padding:40px 20px 0px 0px;"> <a href="{{ route('pages.index') }}" target="_blank" style="color:#FFFFFF; text-decoration:none;">bueno </a></td>
                </tr>
            </table></td>
    </tr>
    <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="width:460px; padding-left:20px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="font-family: roboto, Arial, Helvetica, sans-serif; font-size:40px; color:#231f20; font-weight:bold; text-transform:uppercase; border-bottom:1px  dashed #000; padding:10px 0px;">HELLO!</td>
                            </tr>
                            <tr>
                                <td  style="font-family: roboto, Arial, Helvetica, sans-serif; font-size:16px; color:#231f20; padding:20px 0px 20px 0px;">Thank you for signing up with us and welcome to Bueno.
                                    Here’s a little bit about us and what we have to offer.
                                    <br />

                                    <br />
<span style="color:#f26327;">
    {!! config('bueno.email.text_1') !!}
    {!! config('bueno.email.text_2') !!}
    {!! config('bueno.email.text_3') !!}
</span></td>
                            </tr>

                            <tr>
                                <td ><img src="{{ asset('images/explore_logo.jpg') }}" /></td>
                            </tr>



                            <tr>
                                <td><a style="font-family: roboto, Arial, Helvetica, sans-serif; font-size:16px; color:#f26327; padding:5px 0px 5px 0px; text-decoration: none" href="http://bueno.kitchen/" target="_blank">VISIT OUR WEBSITE</a></td>
                            </tr>



                            <tr>
                                <td style="font-family: roboto, Arial, Helvetica, sans-serif; font-size:16px; color:#f26327; padding:30px 0px 0px 0px; " >WE WOULD LOVE TO HEAR FROM YOU!</td>
                            </tr>



                            <tr>
                                <td style="font-family: roboto, Arial, Helvetica, sans-serif; font-size:16px; color:#231f20; padding:10px 0px 0px 0px; " >For enquiries, queries and feedback<br />
                                    Call us at  <a href="tel:{{ config('bueno.email.phone') }}" style="color:#231f20; text-decoration:none;">{{ config('bueno.email.phone') }} </a></td>
                            </tr>

                            <tr>
                                <td style="padding:10px 0px 0px 0px" ><table width="120" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td> <a href="{{ config('bueno.social.facebook') }}" target="_blank"><img src="{{ asset('images/facebook_logo.jpg') }}"/></a></td>
                                            <td><a href="{{ config('bueno.social.twitter') }}" target="_blank"><img src="{{ asset('images/twitter_logo.jpg') }}" /></a></td>
                                            <td><a href="{{ config('bueno.social.linkedIn') }}" target="_blank"><img src="{{ asset('images/linkedin_logo.jpg') }}" /></a></td>
                                            <td><a href="{{ config('bueno.social.instagram') }}" target="_blank"><img src="{{ asset('images/instagram_logo.jpg') }}" /></a></td>
                                            {{--<td><a href="{{ config('bueno.social.zomato') }}" target="_blank"><img src="{{ asset('images/zomato_logo.jpg') }}"/></a></td>--}}
                                        </tr>
                                    </table></td>
                            </tr>

                        </table></td>
                    <td align="left" valign="top" style="padding-left:20px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">

                            <tr>
                                <td style="font-family: roboto, Arial, Helvetica, sans-serif; font-size:16px; "><img src="{{ asset('images/good_food.jpg') }}" width="287" height="252" /></td>
                            </tr>
                            <tr>
                                <td> <a href="{{ config('bueno.social.playStore') }}"><img src="{{ asset('images/google_play_logo.jpg') }}"/> </a></td>
                            </tr>
                            <tr>
                                <td><a href="{{ config('bueno.social.iTunes') }}"><img src="{{ asset('images/app_store_logo.jpg') }}"/> </a></td>
                            </tr>

                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                        </table></td>
                </tr>
            </table></td>
    </tr>

    <tr>
        <td style="font-family: roboto, Arial, Helvetica, sans-serif; font-style:none;  font-size:16px; color:#808285; padding:10px 0px 20px 20px;" >
            <a href="{{ route('pages.faq.get') }}" style="color:#808285; text-decoration:none;" target="_blank">FAQs </a>  |
            <a href="{{ route('pages.privacy_policy.get') }}" style="color:#808285; text-decoration:none;" target="_blank">privacy policy </a>  |
            <a href="{{ route('pages.terms_conditions.get') }}" style="color:#808285; text-decoration:none;" target="_blank">terms &amp; conditions  </a> |
            <a href="{{ route('pages.refund_cancellation.get') }}" style="color:#808285; text-decoration:none;" target="_blank">refunds &amp; cancellation  </a> |
            <a href="{{ route('pages.contact.get') }}" style="color:#808285; text-decoration:none;" target="_blank">contact us </a></td>
    </tr>

    <tr>
        <td style="background-color:#d1d3d4;" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td valign="top" style="width:398px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="font-family: roboto, Arial, Helvetica, sans-serif; font-size:14px; color:#fff; background-color:#d1d3d4; padding:20px 20px; text-decoration:none;" >
                                    This email was sent from a notification-only address that cannot accept incoming email. Please do not reply to this message.</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</body>
</html>
