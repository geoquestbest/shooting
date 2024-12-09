<?php
echo'<!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:v="urn:schemas-microsoft-com:vml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="format-detection" content="telephone=no,address=no,email=no,date=no,url=no">
    <!-- Enable Dark Mode Support -->
    <meta name="color-scheme" content="light dark">
    <meta name="supported-color-schemes" content="light dark only">
    <title>Theme here</title>
    <!--[if gte mso 9]>
    <xml>
        <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml>
    <![endif]-->
    <!--[if mso]>
    <style type=”text/css”>td {
        font-family: Arial, sans-serif
    }

    a {
        text-decoration: underline !important
    }</style>
    <![endif]-->

    <!-- CSS Reset -->
    <style type="text/css">
        /* Tells the email client that only light styles are provided but the client can transform them to dark. A duplicate of meta color-scheme meta tag above. */

        :root {
            color-scheme: light;
            supported-color-schemes: light;
        }

        /* Remove spaces around the email design added by some email clients. */
        /* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */

        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
        }

        /* Stops email clients resizing small text. */

        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        /* Centers email on Android 4.4 */

        div[style*="margin: 16px 0"] {
            margin: 0 !important;
        }

        /* forces Samsung Android mail clients to use the entire viewport */

        #MessageViewBody,
        #MessageWebViewDiv {
            width: 100% !important;
        }

        /* Stops Outlook from adding extra spacing to tables. */

        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }

        /* Replaces default bold style. */

        th {
            font-weight: normal;
        }

        /* Fixes webkit padding issue. */

        table {
            border-spacing: 0;
            border-collapse: collapse;
            table-layout: fixed;
        }

        /* Prevents Windows 10 Mail from underlining links despite inline CSS. Styles for underlined links should be inline. */

        a {
            text-decoration: none;
            cursor: pointer;
        }

        /* Uses a better rendering method when resizing images in IE. */

        img {
            -ms-interpolation-mode: bicubic;
        }

        /* A work-around for email clients meddling in triggered links. */

        a[href^="x-apple-data-detectors:"],
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: underline !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        .unstyle-auto-detected-links a,
        .aBn {
            border-bottom: 0 !important;
            cursor: default !important;
            color: inherit !important;
            text-decoration: underline !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        u + #body a {
            color: inherit;
            text-decoration: underline;
            font-size: inherit;
            font-family: inherit;
            font-weight: inherit;
            line-height: inherit;
        }

        #MessageViewBody a {
            color: inherit;
            text-decoration: underline;
            font-size: inherit;
            font-family: inherit;
            font-weight: inherit;
            line-height: inherit;
        }

        /* Prevents Gmail from changing the text color in conversation threads. */

        .im {
            color: inherit !important;
        }

        /* Prevents Gmail from displaying a download button on large, non-linked images. */

        .a6S {
            display: none !important;
            opacity: 0.01 !important;
        }

        /* If the above doesn\'t work, add a .g-img class to any image in question. */

        img.g-img + div {
            display: none !important;
        }

        p {
            margin: 0 !important;
        }

        .ExternalClass {
            width: 100%;
        }

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
    </style>
    <!-- CSS Reset end -->


    <!-- Usual css -->
    <!--Dark Mode media queries to target some Android and Outlook.com -->
    <style>
        /* targets color attribute */
        [data-ogsc] .dmmq {
        }

        /* targets background attribute */
        [data-ogsb] .dmmq {
        }
    </style>

    <style type="text/css">
        @media (prefers-color-scheme: light) {
        }


        body {
            background-color: #eaeaea;
            font-family: \'Arial\', sans-serif;
            -webkit-text-size-adjust: none;
            -ms-text-size-adjust: none;
        }


        td {
            padding: 0;
        }

        img {
            border: 0;
        }

        a[href^="tel:"] {
            color: #000000 !important;
            text-decoration: none !important;
        }

        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #eaeaea;
            padding-bottom: 60px;
        }

        .main {
            width: 100%;
            max-width: 600px;
            background-color: #ffffff;
            margin: 0 auto;
            border-spacing: 0;
            font-family: sans-serif;
        }

        @media screen and (max-width: 480px) {
            .mtp p {
                font-size: 16px !important;
            }

            .mtp span {
                font-size: 16px !important;
            }

            .mt14 p {
                font-size: 14px !important;
            }

            .mt a {
                font-size: 24px !important;
            }

            ul li span {
                font-size: 14px !important;
            }
        }
    </style>
    <!--[if mso]>
    <style type="text/css">
        ul {
            margin: 0 !important;
        }

        li {
            margin-left: 40px !important;
        }

        li.firstListItem {
            margin-top: 10px !important;
        }

        li.lastListItem {
            margin-bottom: 10px !important;
        }
    </style>
    <![endif]-->

</head>

<body>
<!-- Preheader text -->
<div style="display: none; max-height: 0; overflow: hidden;">
    Preheder here
</div>
<!-- Do not edit the div below, it hides other text from showing in preheader using &zwnj;&nbsp; -->
<div style="display: none; max-height: 0; overflow: hidden;">
    &nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
</div>
<!-- Preheader text end -->
<table class="wrapper" border="0" cellpadding="0" cellspacing="0"
       style="width: 100%;table-layout: fixed;background-color: #eaeaea;mso-padding-alt:0">
    <!--[if mso | IE]>
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #eaeaea;">
        <tr>
            <td>
    <![endif]-->
    <tr>
        <td align="center" style="padding-bottom: 60px;">
            <!--[if mso | IE]>
            <table align="center" border="0" cellspacing="0" cellpadding="0" width="600" style="width: 600px;">
                <tr>
                    <td align="center" valign="top" width="600" style="width: 600px;">
            <![endif]-->
            <table class="main" border="0" cellpadding="0" cellspacing="0" width="100%"
                   style="width: 100%;max-width: 600px;background-color: #ffffff;margin: 0 auto;border-spacing: 0;font-family: sans-serif;">

                <tr>
                    <td align="center" valign="top" style="line-height: 0; font-size: 0;padding-top: 20px;">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation"
                               style="margin:0; overflow: hidden; padding:0;mso-table-lspace: 0; mso-table-rspace: 0;">
                            <tr>
                                <td align="center" valign="top">
                                    <img src="https://ftp.shootnbox.fr/reservation/email/xwAsset1@2x.png" width="600"
                                         height="auto"
                                         style="width: 100%;max-width: 600px;height:auto;margin:0;padding:0;outline:0;border:0;display:block;"
                                         alt="bn"/>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="top"
                        style="padding-left: 20px;padding-right: 20px;line-height: 0; font-size: 0;">
                        <!--[if mso]>
                        <table align="center" border="0" cellspacing="0" cellpadding="0" width="510" style="width: 510px;">
                            <tr>
                                <td align="center" valign="top" width="510" style="width: 510px;">
                        <![endif]-->
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation"
                               style="margin:0; overflow: hidden; padding:0;mso-table-lspace: 0; mso-table-rspace: 0;max-width: 510px;">
                            <tr>
                                <td align="center" valign="top" style="padding-bottom: 20px;">
                                    <p
                                            style="font-family: \'Arial\';font-style: normal;font-weight: 700;font-size: 18px;line-height: 23px;color: #e4177f;">
                                        Hello '.explode( ' ', $fields[ 'Nom' ] )[ 0 ].'!
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" valign="top" style="padding-bottom: 20px;">
                                    <img src="https://ftp.shootnbox.fr/reservation/email/xwAsset3@2x.png" width="140"
                                         height="auto" style="margin:0;padding:0;outline:0;border:0;display:block;"
                                         alt="img"/>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" valign="top" style="padding-bottom: 15px;">
                                    <p
                                            style="font-family: \'Arial\';font-style: normal;font-weight: 700;font-size: 14px;line-height: 23px;color: #3E3E3E;">
                                        Nous sommes heureux de&nbsp;vous voir rejoindre la&nbsp;grande famille
                                        Shootnbox !
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" valign="top" style="padding-bottom: 15px;">
                                    <p
                                            style="font-family: \'Arial\';font-style: normal;font-weight: 700;font-size: 14px;line-height: 23px;color: #3E3E3E;">
                                        A&nbsp;la&nbsp;recherche de&nbsp;la&nbsp;borne photo id&eacute;ale ? Vous
                                        avez frapp&eacute; &agrave;&nbsp;notre porte et&nbsp;ce&nbsp;n&rsquo;est pas
                                        un&nbsp;hasard : vous retrouvez chez
                                        nous le&nbsp;plus large choix de&nbsp;photobooths, les options les plus
                                        compl&egrave;tes et&nbsp;le&nbsp;logiciel le&nbsp;plus intuitif.<br/>
                                        Sans doute les meilleures bornes Made in&nbsp;France du&nbsp;march&eacute; !
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" valign="top">
                                    <p
                                            style="font-family: \'Arial\';font-style: normal;font-weight: 700;font-size: 14px;line-height: 23px;color: #3E3E3E;">
                                        Votre demande de location a bien été traitée, et <span
                                                style="color: #e4177f;">notre super Team vous recontacte d&rsquo;ici
                                            quelques instants.</span>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" valign="top" style="padding-bottom: 10px;">
                                    <p
                                            style="font-family: \'Arial\';font-style: normal;font-weight: 700;font-size: 14px;line-height: 23px;color: #3E3E3E;">
                                        Nous sommes l&agrave;&nbsp;pour vous conseiller et&nbsp;vous accompagner,
                                        alors n&rsquo;h&eacute;sitez pas &agrave;&nbsp;nous solliciter !
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <!--[if mso]>
                        </td>
                        </tr>
                        </table>
                        <![endif]-->
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="top"
                        style="padding-left: 20px;padding-right: 20px;line-height: 0; font-size: 0;">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation"
                               style="margin:0; overflow: hidden; padding:0;mso-table-lspace: 0; mso-table-rspace: 0;">
                            <tr>
                                <td align="right" valign="top" style="padding-bottom: 10px;">
                                    <img src="https://ftp.shootnbox.fr/reservation/email/xwAsset4@2x.png" width="200"
                                         height="auto" style="margin:0;padding:0;outline:0;border:0;display:block;"
                                         alt="img"/>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="top"
                        style="padding-left: 20px;padding-right: 20px;line-height: 0; font-size: 0;">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation"
                               style="margin:0; overflow: hidden; padding:0;mso-table-lspace: 0; mso-table-rspace: 0;">
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" role="presentation"
                                           width="100%"
                                           style="margin:0; overflow: hidden; padding:0;mso-table-lspace: 0; mso-table-rspace: 0;background: #f8f4f0;border-radius: 10px;">
                                        <tr>
                                            <td align="center" valign="top" style="line-height: 0; font-size: 0;">
                                                <table width="100%" border="0" cellpadding="0" cellspacing="0"
                                                       role="presentation"
                                                       style="margin:0; overflow: hidden; padding:0;mso-table-lspace: 0; mso-table-rspace: 0;">
                                                    <tr>
                                                        <td align="center"
                                                            style="padding-bottom: 13px;padding-top: 20px;">
                                                            <p
                                                                    style="font-family: \'Arial\'; font-style: normal; font-weight: 700; font-size: 15px; line-height: 15px; color: #e4177f;">
                                                                Mon offre personnalisée
                                                            </p>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td
                                                    style="padding-top:25px;padding-bottom:25px;padding-right:20px;padding-left:20px;">
                                                <!--[if (gte mso 9)|(IE)]>
                                                <table width="100%">
                                                    <tr>
                                                        <td width="60%" valign="top">
                                                <![endif]-->
                                                <div class="col-1"
                                                     style="width:100%;max-width:285px;display:inline-block;vertical-align:top;">
                                                    <table width="100%" cellpadding="0" cellspacing="0"
                                                           style="border-spacing:0;mso-table-lspace: 0; mso-table-rspace: 0;">

                                                        <tr>
                                                            <td align="left" valign="top">
                                                                <p style="font-family: \'Arial\'; font-style: normal; font-weight: 500; font-size: 15px; line-height: 20px; color: #e4177f;">
                                                                    <b>Date</b> : <span style="color: #3E3E3E;">'.$fields[ 'Date de l’événement' ].'</span>
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="left" valign="top">
                                                                <p
                                                                        style="font-family: \'Arial\'; font-style: normal; font-weight: 500; font-size: 15px; line-height: 20px; color: #e4177f;">
                                                                    <b>Lieu d&rsquo;&eacute;v&eacute;nement :</b>
                                                                    <span style="color: #3E3E3E;">'.$fields[ 'Lieu de l’événement' ].'</span>
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="left" valign="top">
                                                                <p style="font-family: \'Arial\'; font-style: normal; font-weight: 500; font-size: 15px; line-height: 20px; color: #e4177f;">
                                                                    <b>Logistique :</b>
                                                                    <span style="color: #3E3E3E;">
																		'.explode( ':1:', $fields[ 'Options de livraison' ] )[ 0 ].'<br>
																		<'.( implode( ', ', array_filter( array( $fields[ 'Agency' ], $fields[ 'Adresse de livraison' ], $fields[ 'Ville de livraison' ], $fields[ 'Code postal' ] ), 'boolval' ) ) ).'
                                                                    </span>
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="left" valign="top">
                                                                <p style="font-family: \'Arial\'; font-style: normal; font-weight: 500; font-size: 15px; line-height: 20px; color: #e4177f;">
                                                                    <b>Borne photo :</b>
                                                                    <span style="color: #3E3E3E;">
																		'.$fields[ 'Ma borne' ].'<br>
                                                                    </span>
                                                                </p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <!--[if (gte mso 9)|(IE)]>
                                                </td>
                                                <td width="40%" valign="top">
                                                <![endif]-->
                                                <div class="column"
                                                     style="width:100%;max-width:230px;display:inline-block;vertical-align:top;">
                                                    <table width="100%" cellpadding="0" cellspacing="0"
                                                           style="border-spacing:0;mso-table-lspace: 0; mso-table-rspace: 0;">

                                                        <tr>
                                                            <td align="left" valign="top">
                                                                <p style="font-family: \'Arial\'; font-style: normal; font-weight: 500; font-size: 15px; line-height: 20px; color: #e4177f;">
                                                                    <b>Options souscrites :</b>
                                                                </p>
                                                            </td>
                                                        </tr>';

														if ( trim( $fields[ 'Mes options' ] ) != "" ) {
															$options = explode( ',', $fields[ 'Mes options' ] );
															foreach ( $options as $option ): $option = explode( ':1:', $option );
                                                                echo'<tr>
                                                                    <td align="left" valign="middle" style="font-family: \'Arial\'; font-style: normal; font-weight: 500; font-size: 15px; line-height: 20px; color: #e4177f;vertical-align: middle;">
                                                                        <img src="https://ftp.shootnbox.fr/reservation/email/xwAsset5@2x.png" width="10" height="10" style="margin:0;padding:0;outline:0;border:0;display:inline-block;" alt="icon"/>&nbsp;
                                                                        <span style="color: #3E3E3E;">'.$option[ 0 ].' '.$option[ 1 ].'€</span>
                                                                    </td>
                                                                </tr>';
															endforeach;
														}
                                                    echo'</table>
                                                </div>
                                                <!--[if (gte mso 9)|(IE)]>
                                                </td>
                                                </tr>
                                                </table>
                                                <![endif]-->
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="top" style="padding-left: 20px;padding-right: 20px;line-height: 0; font-size: 0;padding-top: 30px;padding-bottom: 30px;">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="margin:0; overflow: hidden; padding:0;mso-table-lspace: 0; mso-table-rspace: 0;">
                            <tr>
                                <td align="center" valign="top">
                                    <!--[if mso]>
                                    <table align="center" border="0" cellspacing="0" cellpadding="0" width="180" style="width: 180px;">
                                        <tr>
                                            <td align="center" valign="top" width="180" style="width: 180px;">
                                    <![endif]-->
                                    <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="margin:0; overflow: hidden; padding:0;mso-table-lspace: 0; mso-table-rspace: 0;max-width: 180px;">
                                        <tr>
                                            <td align="center" valign="top"
                                                style="width: 250px;border-radius: 20px;">
                                                <div style="background-color:#dc6096;border-radius:20px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;font-weight:bold;line-height:39px;text-align:center;text-decoration:none;width:220px;-webkit-text-size-adjust:none;">
                                                    Mon prix : '.$fields[ 'Mon tarif' ].'
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                    <!--[if mso]>
                                    </td>
                                    </tr>
                                    </table>
                                    <![endif]-->
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="top"
                        style="padding-left: 20px;padding-right: 20px;line-height: 0; font-size: 0;">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="margin:0; overflow: hidden; padding:0;mso-table-lspace: 0; mso-table-rspace: 0;">
                            <tr>
                                <td align="center" valign="top" style="padding-bottom: 20px;">
                                    <p style="font-family: \'Arial\';font-style: normal;font-weight: 400;font-size: 16px;line-height: 23px;color: #e4177f;">
                                        Nos points forts
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- google -->
                <tr>
                    <td align="center" valign="top" style="line-height: 0; font-size: 0;padding-top: 30px;padding-bottom: 30px;">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="margin:0; overflow: hidden; padding:0;mso-table-lspace: 0; mso-table-rspace: 0;">
                            <tr>
                                <td align="center" valign="top">
                                    <a href="https://www.google.com/search?hl=fr-FR&gl=fr&q=Shootnbox/+Photobooth+/+location+photobooth+/+Borne+Photo+/+Photobox+/+Photobooth+360,+3+Sent.+des+Mar%C3%A9cages,+93100+Montreuil&ludocid=1331238237106430303&lsig=AB86z5VrDQvhyEipyQCCzTFtl9Gv&bshm=lbse/1#lrd=0x47e6712e441122c5:0x1279821f9a25615f,1,,,,"><img src="https://ftp.shootnbox.fr/reservation/email/xwAsset12@2x.png" width="310" height="auto" style="width: 100%;max-width: 310px;height:auto;margin:0;padding:0;outline:0;border:0;display:block;" alt="bn"/></a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!-- pink -->
                <tr>
                    <td align="center" valign="top" style="padding-left: 20px;padding-right: 20px;line-height: 0; font-size: 0;">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="margin:0; overflow: hidden; padding:0;mso-table-lspace: 0; mso-table-rspace: 0;">
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="margin:0; overflow: hidden; padding:0;mso-table-lspace: 0; mso-table-rspace: 0;border-radius: 20px;background-color: #f8f4f0;">
                                        <tr>
                                            <td style="padding-top:0px;padding-bottom:0px;padding-right:0;padding-left:0;text-align:center;">
                                                <!--[if (gte mso 9)|(IE)]>
                                                <table width="100%">
                                                    <tr>
                                                        <td width="50%" valign="top">
                                                <![endif]-->
                                                <div class="column" style="width:100%;max-width:308px;display:inline-block;vertical-align:top;">
                                                    <table width="100%" cellpadding="0" cellspacing="0" style="border-spacing:0;mso-table-lspace: 0; mso-table-rspace: 0;">
                                                        <tr>
                                                            <td align="center" valign="top" style="line-height: 0; font-size: 0;padding-top: 20px;padding-left: 20px;">
                                                                <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="margin:0; overflow: hidden; padding:0;mso-table-lspace: 0; mso-table-rspace: 0;">
                                                                    <tr>
                                                                        <td align="left">
                                                                            <p style="font-family: \'Arial\';font-style: normal;font-weight: 400;font-size: 16px;line-height: 23px;color: #e4177f;">
                                                                                C&rsquo;est une borne question !
                                                                            </p>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="left" valign="top" style="padding-bottom: 20px;">
                                                                            <p style="font-family: \'Arial\';font-style: normal;font-weight: 400;font-size: 14px;line-height: 23px;color: #3E3E3E;">
                                                                                Besoin d&rsquo;info ?<br/>
                                                                                Rendez-vous sur notre <a href="https://shootnbox.fr/faq/"
                                                                                                         style="background-color: #dc6096;color: #ffffff;border-radius: 20px;">&nbsp;Foire
                                                                                    aux Questions
                                                                                    !&nbsp;</a><br/>
                                                                                Tous les sujets y sont abordés :
                                                                                logistique,
                                                                                modalités de paiement, options,
                                                                                graphisme…
                                                                            </p>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="left" valign="top" style="padding-bottom: 20px;">
                                                                            <p style="font-family: \'Arial\';font-style: normal;font-weight: 400;font-size: 14px;line-height: 23px;color: #3E3E3E;">
                                                                                Vous ne trouvez pas la réponse à
                                                                                votre question ? Contactez notre
                                                                                Team par téléphone, nous nous ferons
                                                                                un plaisir de
                                                                                vous aider !
                                                                            </p>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <!--[if (gte mso 9)|(IE)]>
                                                </td>
                                                <td width="50%" valign="top">
                                                <![endif]-->
                                                <div class="column"
                                                     style="width:100%;max-width:250px;display:inline-block;vertical-align:top;">
                                                    <table width="100%" cellpadding="0" cellspacing="0"
                                                           style="border-spacing:0;mso-table-lspace: 0; mso-table-rspace: 0;">
                                                        <tr>
                                                            <td align="center" style="padding-bottom: 20px;">
                                                                <img src="https://ftp.shootnbox.fr/reservation/email/xwAsset16@2x.png" width="248" height="auto" style="margin:0;padding:0;outline:0;border:0;display:block;" alt="img"/>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <!--[if (gte mso 9)|(IE)]>
                                                </td>
                                                </tr>
                                                </table>
                                                <![endif]-->
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="top"
                        style="padding-left: 20px;padding-right: 20px;line-height: 0; font-size: 0;padding-top: 30px;">
                        <!--[if mso]>
                        <table align="center" border="0" cellspacing="0" cellpadding="0" width="460" style="width: 460px;">
                            <tr>
                                <td align="center" valign="top" width="460" style="width: 460px;">
                        <![endif]-->
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation"
                               style="margin:0; overflow: hidden; padding:0;mso-table-lspace: 0; mso-table-rspace: 0;max-width: 460px;">
                            <tr>
                                <td align="center" valign="top" style="padding-bottom: 15px;">
                                    <p style="font-family: \'Arial\';font-style: normal;font-weight: 700;font-size: 14px;line-height: 23px;color: #e4177f;">
                                        Votre photobooth en détails
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" valign="top">
                                    <p style="font-family: \'Arial\';font-style: normal;font-weight: 400;font-size: 12px;line-height: 20px;color: #3E3E3E;">
                                        Ci-dessus, nos dossiers techniques complet pour vous aider
                                        &agrave;&nbsp;anticiper au&nbsp;mieux votre &eacute;v&eacute;nement. Cliquez
                                        sur les modules
                                        pour t&eacute;l&eacute;charger les dossiers.
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <!--[if mso]>
                        </td>
                        </tr>
                        </table>
                        <![endif]-->
                    </td>
                </tr>
                <!-- 3 blocks -->
                <tr>
                    <td align="center" height="100%" valign="top" width="100%" style="padding: 25px 0 50px 0;">
                        <!--[if (gte mso 9)|(IE)]>
                        <table align="center" border="0" cellspacing="0" cellpadding="0" width="600">
                            <tr>
                                <td align="center" valign="top" width="600">
                        <![endif]-->
                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%"
                               style="max-width:600px;">
                            <tr>
                                <td align="center" valign="top" style="font-size:0;">
                                    <!--[if (gte mso 9)|(IE)]>
                                    <table align="center" border="0" cellspacing="0" cellpadding="0" width="600">
                                        <tr>
                                            <td align="left" valign="top" width="190">
                                    <![endif]-->
                                    <div
                                            style="display:inline-block; max-width:33.3333%; min-width:190px; vertical-align:top; width:100%;">

                                        <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%"
                                               style="max-width:190px;">
                                            <tr>
                                                <td align="center" valign="top"
                                                    style="padding-top: 5px;padding-bottom: 5px;">
                                                    <a href="'.home_url( '/conditions-de-location/' ).'">
                                                        <img src="https://ftp.shootnbox.fr/reservation/email/xwAsset17@2x.png" width="140" height="auto" style="margin:0;padding:0;outline:0;border:0;display:block;" alt="img"/>
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <!--[if (gte mso 9)|(IE)]>
										</td>
											  <td width="15" style="font-size: 1px;">&nbsp;</td>
										<td align="left" valign="top" width="190">
										<![endif]-->
                                    <div style="display:inline-block; max-width:33.3333%; min-width:190px; vertical-align:top; width:100%;"
                                         class="mobile-wrapper">
                                        <table align="center" border="0" cellpadding="0" cellspacing="0"
                                               width="100%" style="max-width:190px;" class="max-width">
                                            <tr>
                                                <td align="center" valign="top"
                                                    style="padding-top: 5px;padding-bottom: 5px;">';

													$map = array(
														'ring'       => 'https://shootnbox.fr/wp-content/uploads/2024/06/LE-RING-Plaquette-Shootnbox.pdf',
														'vegas'      => 'https://shootnbox.fr/wp-content/uploads/2024/06/LE-VEGAS-Plaquette-Shootnbox.pdf',
														'miroir'     => 'https://shootnbox.fr/wp-content/uploads/2024/06/LE-MIROIR-Plaquette-Shootnbox.pdf',
														'spinner'    => 'https://shootnbox.fr/wp-content/uploads/2024/06/LE-SPINNER-Plaquette-Shootnbox.pdf',
														'aircam-360' => 'https://shootnbox.fr/wp-content/uploads/2024/06/LE-AIRCAM-Plaquette-Shootnbox.pdf',
														'aircam_360' => 'https://shootnbox.fr/wp-content/uploads/2024/06/LE-AIRCAM-Plaquette-Shootnbox.pdf',
													);
													
													
													if ( key_exists( 'service_type', $args ) && key_exists( $args[ 'service_type' ], $map ) ) {
														$link = $map[ $args[ 'service_type' ] ];
														
														echo '<a href="' . $link . '">';
													}


                                                    echo'<img src="https://ftp.shootnbox.fr/reservation/email/xwAsset18@2x.png"
                                                         width="140" height="auto"
                                                         style="margin:0;padding:0;outline:0;border:0;display:block;"
                                                         alt="img"/>';
													
													if ( $link ) {
														echo '</a>';
													}
                                                echo'</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <!--[if (gte mso 9)|(IE)]>
										</td>
											  <td width="15" style="font-size: 1px;">&nbsp;</td>
										<td align="left" valign="top" width="190">
										<![endif]-->
                                    <div style="display:inline-block; max-width:33.3333%; min-width:190px; vertical-align:top; width:100%;" class="mobile-wrapper">
                                        <table align="right" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:190px; float: right;" class="max-width">
                                            <tr>
                                                <td align="center" valign="top" style="padding-top: 5px;padding-bottom: 5px;">';
	                                                $link = '';
	                                                $map = array(
		                                                //'ring'       => 'https://shootnbox.fr/wp-content/uploads/2024/06/LE-RING-Plaquette-Shootnbox.pdf',
		                                                'vegas'      => 'https://shootnbox.fr/wp-content/uploads/2023/05/Vegas-Exemples-contours-photos.pdf',
		                                                'miroir'     => 'https://shootnbox.fr/wp-content/uploads/2023/05/RING-ET-MIROIR-Exemples-contours-photos.pdf',
		                                                'spinner'    => 'https://shootnbox.fr/wp-content/uploads/2023/05/Spinner-Exemples-contours-photos.pdf',
		                                                //'aircam-360' => 'https://shootnbox.fr/wp-content/uploads/2024/06/LE-AIRCAM-Plaquette-Shootnbox.pdf',
		                                                //'aircam_360' => 'https://shootnbox.fr/wp-content/uploads/2024/06/LE-AIRCAM-Plaquette-Shootnbox.pdf',
	                                                );
	
	
	                                                if ( key_exists( 'service_type', $args ) && key_exists( $args[ 'service_type' ], $map ) ) {
		                                                $link = $map[ $args[ 'service_type' ] ];
		
		                                                echo '<a href="' . $link . '">';
	                                                }
                                                    echo'<img src="https://ftp.shootnbox.fr/reservation/email/xwAsset19@2x.png" width="140" height="auto" style="margin:0;padding:0;outline:0;border:0;display:block;" alt="img"/>';
	                                                if ( $link ) {
		                                                echo '</a>';
	                                                }
                                                echo'</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <!--[if (gte mso 9)|(IE)]>
                                    </td>
                                    </tr>
                                    </table>
                                    <![endif]-->
                                </td>
                            </tr>
                        </table>
                        <!--[if (gte mso 9)|(IE)]>
                        </td>
                        </tr>
                        </table>
                        <![endif]-->
                    </td>
                </tr>
                <!-- line -->
                <tr>
                    <td align="center" height="100%" valign="top" width="100%" style="padding-right: 20px;padding-left: 20px;">
                        <!--[if (gte mso 9)|(IE)]>
                        <table align="center" border="0" cellspacing="0" cellpadding="0" width="600">
                            <tr>
                                <td align="center" valign="top" width="600">
                        <![endif]-->
                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
                            <tr>
                                <td align="center" valign="top" style="font-size:0;background-color: #e4177f;border-radius: 10px;">
                                    <!--[if (gte mso 9)|(IE)]>
                                    <table align="center" border="0" cellspacing="0" cellpadding="0" width="600">
                                        <tr>
                                            <td align="left" valign="top" width="145">
                                    <![endif]-->
                                    <div style="display:inline-block; max-width: 27%; min-width:145px; vertical-align:top; width:100%;">

                                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:145px;">
                                            <tr>
                                                <td align="center" valign="top">
                                                    <a href="mailto:contact@shootnbox.fr" style="font-family: \'Arial\';font-style: normal;font-weight: 400;font-size: 12px;line-height: 23px;color: #ffffff;">contact@shootnbox.fr</a>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <!--[if (gte mso 9)|(IE)]>
															</td>
																  <td width="15" style="font-size: 1px;">&nbsp;</td>
															<td align="left" valign="top" width="185">
															<![endif]-->
                                    <div style="display:inline-block; max-width: 33%; min-width:185px; vertical-align:top; width:100%;">
                                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:185px;">
                                            <tr>
                                                <td align="center" valign="top">
                                                    <p style="font-family: \'Arial\';font-style: normal;font-weight: 400;font-size: 12px;line-height: 23px;color: #ffffff;">
                                                        Agence Paris : 01 45 01 66 66
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <!--[if (gte mso 9)|(IE)]>
															</td>
																  <td width="15" style="font-size: 1px;">&nbsp;</td>
															<td align="left" valign="top" width="186">
															<![endif]-->
                                    <div style="display:inline-block; max-width:33%; min-width:186px; vertical-align:top; width:100%;">
                                        <table align="center" border="0" cellpadding="0" cellspacing="0"
                                               width="100%" style="max-width:186px;">
                                            <tr>
                                                <td align="center" valign="top">
                                                    <p style="font-family: \'Arial\';font-style: normal;font-weight: 400;font-size: 12px;line-height: 23px;color: #ffffff;">
                                                        Agence Bordeaux : 05 32 96 96 96
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <!--[if (gte mso 9)|(IE)]>
                                    </td>
                                    </tr>
                                    </table>
                                    <![endif]-->
                                </td>
                            </tr>
                        </table>
                        <!--[if (gte mso 9)|(IE)]>
                        </td>
                        </tr>
                        </table>
                        <![endif]-->
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="top"
                        style="padding-left: 20px;padding-right: 20px;line-height: 0; font-size: 0;padding-top: 20px;">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation"
                               style="margin:0; overflow: hidden; padding:0;mso-table-lspace: 0; mso-table-rspace: 0;">
                            <tr>
                                <td align="center" valign="top" style="padding-bottom: 20px;">
                                    <img src="https://ftp.shootnbox.fr/reservation/email/xwAsset20@2x.png" width="560" height="auto" style="width: 100%;max-width: 560px;height:auto;margin:0;padding:0;outline:0;border:0;display:block;" alt="img"/>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" valign="top" style="padding-bottom: 30px;">
                                    <img src="https://ftp.shootnbox.fr/reservation/email/xwAsset21@2x.jpg" width="120" height="auto" style="margin:0;padding:0;outline:0;border:0;display:block;" alt="img"/>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

            </table>
            <!--[if mso]>
            </td>
            </tr>
            </table>
            <![endif]-->
        </td>
    </tr>
    <!--[if mso | IE]>
    </td>
    </tr>
    </table>
    <![endif]-->
</table>
</body>

</html>';
?>