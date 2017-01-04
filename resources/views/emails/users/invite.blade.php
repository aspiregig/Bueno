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
                    <td align="left" valign="top" style="width:460px; padding-left:20px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="font-family: roboto, Arial, Helvetica, sans-serif; font-size:40px; color:#231f20; text-transform:uppercase; border-bottom:1px  dashed #000; padding:20px 0px 0px 0px;">Welcome to Bueno</td>
                            </tr>
                            <tr>
                                <td  style="font-family: roboto, Arial, Helvetica, sans-serif; font-size:16px; color:#231f20; padding:20px 0px 20px 0px;">Hello {{ $inputs['friend_full_name'] }}! <br />
                                    <br />
                                    {{ $inputs['message'] }}
                                    <br/>
                                    <h5>Referral Code : {{ auth()->user()->referral_code }}</h5>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-family: roboto, Arial, Helvetica, sans-serif; font-size:16px; color:#231f20; padding:20px 0px 0px 0px; border-top:1px  dashed #231f20;">For feedback or enquiries: Call us on: <a href="tel: {{ config('bueno.site.phone') }}" style="color:#231f20; text-decoration:none;"><strong> {{ config('bueno.site.phone') }} </strong></a> | Email us on: <a href="mailto:{{ config('bueno.site.email') }}" style="color:#231f20; text-decoration:none;"><strong>{{ config('bueno.site.email') }}</strong></a></td>
                            </tr>


                            <tr>
                                <td >&nbsp;</td>
                            </tr>
                        </table></td>
                    <td align="left" valign="top" style="padding-left:20px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">

                            <tr>
                                <td style="font-family: roboto, Arial, Helvetica, sans-serif; font-size:16px; ">&nbsp; </td>
                            </tr>
                            <tr>
                                <td >&nbsp;</td>
                            </tr>
                            <tr>
                                <td >&nbsp;</td>
                            </tr>

                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                        </table></td>
                </tr>
            </table></td>
    </tr>
    <tr>
        <td style="background-color:#d1d3d4;" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td valign="top" style="width:398px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">

                            <tr>
                                <td style="font-family: roboto, Arial, Helvetica, sans-serif; font-size:14px; color:#fff; background-color:#d1d3d4; padding:20px 20px; text-decoration:none;" >
                                    This email was sent from a notification-only address that cannot accept incoming email. Please do not reply to this message.</td>
                            </tr>
                        </table></td>
                    <td style="padding-right:20px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td><a href="{{ config('bueno.social.iTunes') }}"><img src="{{ asset('images/google_play_logo.jpg') }}"/> </a></td>
                                <td><a href="{{ config('bueno.social.playStore') }}"><img src="{{ asset('images/app_store_logo.jpg') }}"/> </a></td>
                            </tr>
                        </table></td>
                </tr>
            </table></td>
    </tr>
</table>

</body>
</html>
