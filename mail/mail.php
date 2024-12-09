<?php
$subject = "Votre création de contour photo est à présent finalisée ".date("d-m-Y H:i", time());
          $mailheaders = "MIME-Version: 1.0\r\n";
          $mailheaders.= "Content-Type: text/html; charset=utf-8\r\n";
          $mailheaders.= "From: =?utf-8?B?".base64_encode("ShootnBox")."?= <hello@shootnbox.fr>\r\n";
          $mailheaders.= "X-Mailer: PHP/".phpversion()."\r\n";
          $msg = '<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office"
    xmlns:v="urn:schemas-microsoft-com:vml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no,address=no,email=no,date=no,url=no">
    <!-- Enable Dark Mode Support -->
    <meta name="color-scheme" content="light dark">
    <meta name="supported-color-schemes" content="light dark only">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;600;700&display=swap" rel="stylesheet">
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
    <style type=”text/css”>td{font-family:Raleway,sans-serif}a{text-decoration:underline!important}</style>
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

        u+#body a {
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

        img.g-img+div {
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
        [data-ogsc] .dmmq {}

        /* targets background attribute */
        [data-ogsb] .dmmq {}
    </style>

    <style type="text/css">
        @media (prefers-color-scheme: light) {}


        body {
            background-color: #eaeaea;
            font-family: \'Raleway\', sans-serif;
            -webkit-text-size-adjust: none;
            -ms-text-size-adjust: none;
        }


        td {
            padding: 0;
        }

        [style*="Raleway"] {
            font-family: \'Raleway\', Raleway, sans-serif !important;
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
            font-family: \'Raleway\', sans-serif;
        }
        .photos {
            display: flex;
            flex-direction: row;
            justify-content: space-around;
            align-items: center;
        }

        @media screen and (max-width: 594px) {
            .col-1 {
                padding-bottom: 20px !important;
            }

            .mtp p {
                font-size: 16px !important;
            }

        }
    </style>


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
                        <td align="center" valign="top" style="line-height: 0; font-size: 0;">
                            <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation"
                                style="margin:0; overflow: hidden; padding:0;mso-table-lspace: 0; mso-table-rspace: 0;">
                                <tr>
                                    <td align="center" valign="top" style="background: url(\'https://shootnbox.fr/mail/main-bg.png\') 100% no-repeat;">
                                        <img src="https://shootnbox.fr/mail/logo.png" width="auto" height="auto"
                                            style="width: 100%;max-width: 234px;height:auto;margin:0;padding:0;outline:0;border:0;display:block; margin-top: 35px"
                                            alt="img" />
                                            <br> <br>
                                            <p
                                            style="font-family: \'Raleway\'; font-style: normal; font-weight: 700; font-size: 25px; line-height: 34px; text-align: center;color: #FFFFFF; margin-top: 40px !important; padding-right: 10px !important;">
                                            On a la patate
                                        </p>
                                        <br> <br>
                                        <p
                                            style="font-family: \'Raleway\'; font-style: normal; font-weight: 700; font-size: 25px; line-height: 34px; text-align: center;color: #FFFFFF; margin-bottom: 40px !important; padding-right: 10px !important;">
                                            pour vous donner la frite !
                                        </p>
                                    </td>
                                    </tr>

                                    <tr>
                                        <td align="left" style="">
                                            <img src="https://shootnbox.fr/mail/cart1.png" width="auto" height="auto"
                                                style="width: 100%;max-width: 85px;height:auto;margin:0;padding:0;outline:0;border:0;display:block; margin-top: -100px; margin-left: 15px; transform: rotate(-10deg);"
                                                    alt="img" />
                                        </td>
                                    </tr>
                                        <td align="right">
                                            <img src="https://shootnbox.fr/mail/cart2.png" width="auto" height="auto"
                                            style="width: 100%;max-width: 133px;height:auto;margin:0;padding:0;outline:0;border:0;display:block; margin-top: -150px; margin-right: 20px; transform: rotate(10deg);"
                                            alt="img" />
                                        </td>
                                    </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top"
                            style="padding-left: 20px;padding-right: 20px;line-height: 0; font-size: 0;">
                            <!--[if mso]>
                            <table align="center" border="0" cellspacing="0" cellpadding="0" width="453" style="width: 453px;">
                            <tr>
                            <td align="center" valign="top" width="453" style="width: 453px;">
                            <![endif]-->
                            <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation"
                                style="margin:0; overflow: hidden; padding:0;mso-table-lspace: 0; mso-table-rspace: 0;max-width: 453px;">
                                <tr>
                                    <td align="center" valign="top" style="padding-top: 0px;padding-bottom: 20px;">
                                        <p
                                            style="font-family: \'Raleway\'; font-style: normal; font-weight: 800; font-size: 17px; line-height: 34px; text-align: center;color: #ca4ca8;">
                                            Hello Vincent !
                                        </p>
                                        <img src="https://shootnbox.fr/mail/name-line.png" width="auto" height="auto"
                                        style="width: 100%;max-width: 133px;height:auto;margin:0;padding:0;outline:0;border:0;display:block; margin-top: 10px"
                                        alt="img" />

                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top" style="padding-bottom: 10px;">
                                        <p
                                            style="font-family: \'Raleway\', sans-serif;font-size: 16px; line-height: 22px; font-weight: 600;mso-line-height-rule: exactly;mso-color-alt:auto;color: #101F36;">
                                            Nous sommes heureux de vous voir rejoindre la grande famille Shootnbox !
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top" style="padding-bottom: 10px;">
                                        <p
                                            style="font-family: \'Raleway\', sans-serif;font-size: 16px; line-height: 22px; font-weight: 600;mso-line-height-rule: exactly;mso-color-alt:auto;color: #101F36;">
                                            A la recherche de la borne photo idéale ? Vous avez frappé à notre porte et ce n’est pas un hasard : vous retrouvez chez nous le plus large choix de photobooths, les options les plus complètes et le logiciel le plus intuitif.
Sans doute les meilleures bornes Made in France du marché !
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top" style="padding-bottom: 38px;">
                                        <p
                                            style="font-family: \'Raleway\', sans-serif;font-size: 16px; line-height: 22px; font-weight: 600;mso-line-height-rule: exactly;mso-color-alt:auto;color: #101F36;">
                                            Votre demande de location a bien été traitée, <span style="color: rgb(222, 48, 143);">et notre super Team vous recontacte d’ici quelques instants</span>.
Nous sommes là pour vous conseiller et vous accompagner, alors n’hésitez pas à nous
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
                                    <td align="center" valign="top">
                                        <table border="0" cellpadding="0" cellspacing="0" role="presentation"
                                            width="100%"
                                            style="margin:0; overflow: hidden; padding:0;mso-table-lspace: 0; mso-table-rspace: 0;background: rgba(241, 121, 129, 0.09);border-radius: 10px;">

                                            <tr>
                                                <td
                                                    style="padding-top:25px;padding-bottom:25px;padding-right:20px;padding-left:20px;">
                                                    <!--[if (gte mso 9)|(IE)]>
                                              <table width="100%">
                                                <tr>
                                                  <td width="60%" valign="top" >
                                                    <![endif]-->
                                                    <div class="col-1"
                                                        style="width:100%;max-width:500px;display:inline-block;vertical-align:top;">

                                                        <table width="100%" cellpadding="0" cellspacing="0"
                                                        style="border-spacing:0;mso-table-lspace: 0; mso-table-rspace: 0;">
                                                            <tr>
                                                                <td align="left" valign="top">
                                                                    <img src="https://shootnbox.fr/mail/heart.png" width="35" height="auto"
                                                                        style="width: 100%;max-width: 35px;height:auto;margin:0;padding:0;outline:0;border:0;display:block;"
                                                                        alt="title" />
                                                                </td>
                                                                <td align="center" valign="top" style="background: url(\'https://shootnbox.fr/mail/comment.png\') 100% no-repeat; width: 180px; height: 75px;  margin-top: -100px;">
                                                                    <!-- <img src="https://shootnbox.fr/mail/comment.png" width="380" height="auto"
                                                                        style="width: 100%;max-width: 175px;height:auto;margin:0;padding:0;outline:0;border:0;display:block;"
                                                                        alt="title" /> -->
                                                                        <p
                                                                        style="font-family: \'Raleway\', sans-serif;font-size: 10px; max-width: 160px; transform: rotate(5deg); padding-top: 15px; line-height: 9px; font-weight: 400;mso-line-height-rule: exactly;mso-color-alt:auto;color: rgb(222, 48, 143); width: 150px;">
                                                                        Comme vous, beaucoup de personnes
                                                                        font appel à nos animations.
                                                                        Ne tardez pas et réservez votre
                                                                        photoboth au plus vite !
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <table width="100%" cellpadding="0" cellspacing="0"
                                                        style="border-spacing:0;mso-table-lspace: 0; mso-table-rspace: 0;">
                                                            <tr>
                                                                <td align="left" style="padding-bottom: 13px;">
                                                                    <p
                                                                        style="font-family: \'Raleway\'; font-style: normal; font-weight: 700; text-align: center; font-size: 15px; line-height: 15px; color: #ca4ca8;">
                                                                        Mon offre personnalisée
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <div style="display: flex; justify-content: space-between;">
                                                            <table width="100%" cellpadding="0" cellspacing="0"
                                                                style="border-spacing:0;mso-table-lspace: 0; mso-table-rspace: 0; max-width: 230px;">

                                                                <br> <br>

                                                                <tr>
                                                                    <td align="left" valign="top">
                                                                        <p
                                                                            style="font-family: \'Raleway\'; font-style: normal; font-weight: 500; font-size: 15px; line-height: 20px; color: #2B3044;">
                                                                            <span style="color:rgb(222, 48, 143)">Date :</span>  01.01.2020
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="left" valign="top">
                                                                        <p
                                                                            style="font-family: \'Raleway\'; font-style: normal; font-weight: 500; font-size: 15px; line-height: 20px; color: #2B3044;">
                                                                            <span style="color:rgb(222, 48, 143)">Lieu d&rsquo;&eacute;v&eacute;nement :</span>
                                                                            Paris
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="left" valign="top">
                                                                        <p
                                                                            style="font-family: \'Raleway\'; font-style: normal; font-weight: 500; font-size: 15px; line-height: 20px; color: #2B3044;">
                                                                            <span style="color:rgb(222, 48, 143)">Logistique :</span> (si livraison, adresse
                                                                            précisée, <br> ou si retrait, point de collecte
                                                                            précisé)
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <table width="100%" cellpadding="0" cellspacing="0"
                                                                style="border-spacing:0;mso-table-lspace: 0; mso-table-rspace: 0;">

                                                                <tr>
                                                                    <td align="left" valign="top">
                                                                        <p
                                                                            style="font-family: \'Raleway\'; font-style: normal; font-weight: 500; font-size: 15px; line-height: 20px; color: #2B3044;">
                                                                            <span style="color:rgb(222, 48, 143)">Options souscrites :</span>
                                                                        </p>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td align="left" valign="middle"
                                                                        style="font-family: \'Raleway\'; font-style: normal; font-weight: 500; font-size: 15px; line-height: 20px; color: #2B3044;vertical-align: middle;">
                                                                        <img src="https://shootnbox.fr/mail/gaw.jpg"
                                                                            width="10" height="10"
                                                                            style="margin:0;padding:0;outline:0;border:0;display:inline-block;"
                                                                            alt="icon" />&nbsp;Multiple impression 50€
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="left" valign="middle"
                                                                        style="font-family: \'Raleway\'; font-style: normal; font-weight: 500; font-size: 15px; line-height: 20px; color: #2B3044;vertical-align: middle;">
                                                                        <img src="https://shootnbox.fr/mail/gaw.jpg"
                                                                            width="10" height="10"
                                                                            style="margin:0;padding:0;outline:0;border:0;display:inline-block;"
                                                                            alt="icon" />&nbsp;Marque blanche 50€
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <!--[if (gte mso 9)|(IE)]>
                                                    </td><td width="40%" valign="top" >
                                                    <![endif]-->
                                                    <!-- <div class="column"
                                                        style="width:100%;max-width:230px;display:inline-block;vertical-align:top; margin-top: 30px;">
                                                        <table width="100%" cellpadding="0" cellspacing="0"
                                                            style="border-spacing:0;mso-table-lspace: 0; mso-table-rspace: 0;">

                                                            <tr>
                                                                <td align="left" valign="top">
                                                                    <p
                                                                        style="font-family: \'Raleway\'; font-style: normal; font-weight: 500; font-size: 15px; line-height: 20px; color: #2B3044;">
                                                                        <span style="color:rgb(222, 48, 143)">Options souscrites :</span>
                                                                    </p>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td align="left" valign="middle"
                                                                    style="font-family: \'Raleway\'; font-style: normal; font-weight: 500; font-size: 15px; line-height: 20px; color: #2B3044;vertical-align: middle;">
                                                                    <img src="https://shootnbox.fr/mail/gaw.jpg"
                                                                        width="10" height="10"
                                                                        style="margin:0;padding:0;outline:0;border:0;display:inline-block;"
                                                                        alt="icon" />&nbsp;Multiple impression 50€
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="left" valign="middle"
                                                                    style="font-family: \'Raleway\'; font-style: normal; font-weight: 500; font-size: 15px; line-height: 20px; color: #2B3044;vertical-align: middle;">
                                                                    <img src="https://shootnbox.fr/mail/gaw.jpg"
                                                                        width="10" height="10"
                                                                        style="margin:0;padding:0;outline:0;border:0;display:inline-block;"
                                                                        alt="icon" />&nbsp;Marque blanche 50€
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div> -->
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
                    <!-- btn -->
                    <tr>
                        <td align="center" valign="top"
                            style="padding-left: 20px;padding-right: 20px;line-height: 0; font-size: 0;padding-top: 0px; margin-top: -3px;">
                            <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation"
                                style="margin:0; overflow: hidden; padding:0;mso-table-lspace: 0; mso-table-rspace: 0;">
                                <tr>
                                    <td align="center" valign="top">
                                        <!--[if mso]>
                                        <table align="center" border="0" cellspacing="0" cellpadding="0" width="180" style="width: 180px;">
                                        <tr>
                                        <td align="center" valign="top" width="180" style="width: 180px;">
                                        <![endif]-->
                                        <table border="0" cellpadding="0" cellspacing="0" role="presentation"
                                            width="100%"
                                            style="margin:0; overflow: hidden; padding:0;mso-table-lspace: 0; mso-table-rspace: 0;">
                                            <tr>
                                                <td align="center" valign="top"
                                                    style="width: 180px;border-radius: 20px; padding-top: 0px; margin-top: 0px;">
                                                    <div
                                                        style="background: linear-gradient(70deg, rgb(203, 40, 168), rgb(245, 199, 113)); border-radius:20px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;font-weight:bold;line-height:39px;text-align:center;text-decoration:none;width:180px;-webkit-text-size-adjust:none;">
                                                        Mon prix : 379€
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
                    <!-- line -->
                    <tr>
                        <td align="center" valign="top"
                            style="padding-left: 20px;padding-right: 20px;line-height: 0; font-size: 0;">
                            <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation"
                                style="margin:0; overflow: hidden; padding:0;mso-table-lspace: 0; mso-table-rspace: 0;">
                                <tr>
                                    <td align="center" valign="top" style="padding-top: 53px;padding-bottom: 0px;">
                                        <p
                                            style="font-family: \'Raleway\'; font-style: normal; font-weight: 800; font-size: 17px; line-height: 34px; text-align: center;color: #ca4ca8;">
                                            Nos points forts
                                        </p>

                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" valign="top">
                                        <table border="0" cellpadding="0" cellspacing="0" role="presentation"
                                            width="100%"
                                            style="margin:0; overflow: hidden; padding:0;mso-table-lspace: 0; mso-table-rspace: 0;background: white;border-radius: 10px;">

                                            <tr>
                                                <td
                                                    style="padding-top:25px;padding-bottom:25px;padding-right:20px;padding-left:20px;">
                                                    <!--[if (gte mso 9)|(IE)]>
                                              <table width="100%">
                                                <tr>
                                                  <td width="60%" valign="top" >
                                                    <![endif]-->

                                                    <div class="col-1"
                                                        style="width:100%;max-width:500px;display:inline-block;vertical-align:top;">
                                                        <hr>
                                                        <table width="100%" cellpadding="0" cellspacing="0"
                                                            style="border-spacing:0;mso-table-lspace: 0; mso-table-rspace: 0;">

                                                            <div style="display: grid; grid-template: 1fr 1fr;">

                                                                <tr>
                                                                    <td align="left" valign="top">

                                                                        <p
                                                                            style="font-family: \'Raleway\'; font-style: normal; font-weight: 500; font-size: 15px; line-height: 20px; color: #2B3044;">
                                                                            <img src="https://shootnbox.fr/mail/objective.png"
                                                                        width="20" height="20"
                                                                        style="margin:0;padding:0;outline:0;border:0;display:inline-block; margin-right: 5px"
                                                                        alt="icon" />
                                                                            <span style="font-weight: 700;">Personnalisation</span> incluse
                                                                        </p>
                                                                    </td>
                                                                    <td align="left" valign="top">
                                                                        <p
                                                                            style="font-family: \'Raleway\'; font-style: normal; font-weight: 500; font-size: 15px; line-height: 20px; color: #2B3044;">
                                                                            <img src="https://shootnbox.fr/mail/mail.png"
                                                                        width="20" height="20"
                                                                        style="margin:0;padding:0;outline:0;border:0;display:inline-block; margin-right: 5px">

                                                                        <span style="font-weight: 700;">Partage par Email & SMS illimité</span> (Ring)
                                                                        </p>
                                                                    </td>
                                                                </tr>

                                                            </div>
                                                        </table>

                                                        <table width="100%" cellpadding="0" cellspacing="0"
                                                            style="border-spacing:0;mso-table-lspace: 0; mso-table-rspace: 0;">

                                                            <div style="display: grid; grid-template: 1fr 1fr;">


                                                                <tr>
                                                                    <td align="left" valign="top">
                                                                        <p
                                                                            style="font-family: \'Raleway\'; font-style: normal; font-weight: 500; font-size: 15px; line-height: 20px; color: #2B3044;">
                                                                            <img src="https://shootnbox.fr/mail/time.png"
                                                                        width="20" height="20"
                                                                        style="margin:0;padding:0;outline:0;border:0;display:inline-block; margin-right: 5px"
                                                                        alt="icon" />
                                                                            <span style="font-weight: 700;">Installation en 3 minutes</span> sans outil
                                                                        </p>
                                                                    </td>
                                                                    <td align="left" valign="top">
                                                                        <p
                                                                            style="font-family: \'Raleway\'; font-style: normal; font-weight: 500; font-size: 15px; line-height: 20px; color: #2B3044;">
                                                                            <img src="https://shootnbox.fr/mail/print.png"
                                                                        width="20" height="20"
                                                                        style="margin:0;padding:0;outline:0;border:0;display:inline-block; margin-right: 5px"
                                                                        alt="icon" />
                                                                            <span style="font-weight: 700;">Impressions haute qualité</span> en moins de 10 secondes (Vegas et Miroir)
                                                                        </p>
                                                                    </td>
                                                                    <hr>
                                                                </tr>


                                                            </div>
                                                        </table>
                                                        <hr>
                                                        <table width="100%" cellpadding="0" cellspacing="0"
                                                            style="border-spacing:0;mso-table-lspace: 0; mso-table-rspace: 0;">

                                                            <div style="display: grid; grid-template: 1fr 1fr;">


                                                                <tr>
                                                                    <td align="left" valign="top">
                                                                        <p
                                                                            style="font-family: \'Raleway\'; font-style: normal; font-weight: 500; font-size: 15px; line-height: 20px; color: #2B3044;">
                                                                            <img src="https://shootnbox.fr/mail/phone.png"
                                                                        width="20" height="20"
                                                                        style="margin:0;padding:0;outline:0;border:0;display:inline-block; margin-right: 5px"
                                                                        alt="icon" />
                                                                            <span style="font-weight: 700;">Assistance téléphonique 7j/7</span> jusqu’à minuit
                                                                        </p>
                                                                    </td>
                                                                    <td align="left" valign="top">
                                                                        <p
                                                                            style="font-family: \'Raleway\'; font-style: normal; font-weight: 500; font-size: 15px; line-height: 20px; color: #2B3044;">
                                                                            <img src="https://shootnbox.fr/mail/teritory.png"
                                                                        width="20" height="20"
                                                                        style="margin:0;padding:0;outline:0;border:0;display:inline-block; margin-right: 5px"
                                                                        alt="icon" />
                                                                            <span style="font-weight: 700;">100% Made in France </span>
                                                                        </p>
                                                                    </td>
                                                                </tr>

                                                            </div>
                                                        </table>
                                                    </div>
                                                    <!--[if (gte mso 9)|(IE)]>
                                                    </td><td width="40%" valign="top" >
                                                    <![endif]-->

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
                            style="width: 260px;border-radius: 20px; padding-top: 0px; margin-top: 0px; padding-bottom: 35px;">
                            <img src="https://shootnbox.fr/mail/rates.png" alt="" style="max-width: 260px; width: 100%; box-shadow: 0 0 10px rgba(0,0,0,0.5); border-radius: 10px;">
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top"
                            style="padding-left: 20px;padding-right: 20px;line-height: 0; font-size: 0; margin-bottom: 30px;">
                            <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation"
                                style="margin:0; overflow: hidden; padding:0;mso-table-lspace: 0; mso-table-rspace: 0; margin-bottom: 30px;">
                                <tr>
                                    <td align="center" valign="top">
                                        <table border="0" cellpadding="0" cellspacing="0" role="presentation"
                                            width="100%"
                                            style="margin:0; overflow: hidden; padding:0;mso-table-lspace: 0; mso-table-rspace: 0;background: rgba(241, 121, 129, 0.09);border-radius: 10px;">

                                            <tr>
                                                <td
                                                    style="padding-top:25px;padding-bottom:25px;padding-right:20px;padding-left:20px;">
                                                    <!--[if (gte mso 9)|(IE)]>
                                              <table width="100%">
                                                <tr>
                                                  <td width="60%" valign="top" >
                                                    <![endif]-->
                                                    <div class="col-1"
                                                        style="width:100%;max-width:285px;display:inline-block;vertical-align:top;">
                                                        <table width="100%" cellpadding="0" cellspacing="0"
                                                            style="border-spacing:0;mso-table-lspace: 0; mso-table-rspace: 0;">
                                                            <tr>
                                                                <td align="left" style="padding-bottom: 13px;">
                                                                    <p
                                                                        style="font-family: \'Raleway\'; font-style: normal; font-weight: 700; font-size: 17px; line-height: 15px;color: #ca4ca8;">
                                                                        C’est une borne question !
                                                                    </p>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td align="left" valign="top" style="padding-bottom: 30px">
                                                                    <p
                                                                        style="font-family: \'Raleway\'; font-style: normal; font-weight: 500; font-size: 12px; line-height: 20px; color: #2B3044;">
                                                                        <span style="font-weight: 600;">Besoin d’info ?</span> <br>
                                                                        Rendez-vous sur notre <span style="color: white; background:linear-gradient(70deg, rgb(203, 40, 168), rgb(245, 199, 113)); border-radius: 5px;">Foire aux Questions !</span> <br> Tous les sujets y sont abordés : logistique, modali-tés de paiement, options, graphisme…
                                                                    </p>

                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="left" valign="top">
                                                                    <p
                                                                        style="font-family: \'Raleway\'; font-style: normal; font-weight: 500; font-size: 12px; line-height: 20px; color: #2B3044;">
                                                                        Vous ne trouvez pas la réponse à votre question ? Contactez notre Team par téléphone, nous nous ferons un plaisir de vous aider !
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <!--[if (gte mso 9)|(IE)]>
                                                    </td><td width="40%" valign="top" >
                                                    <![endif]-->
                                                    <div class="smartphone"
                                                        style="width:100%;max-width:230px;display:inline-block;vertical-align:top;">
                                                        <table width="100%" cellpadding="0" cellspacing="0"
                                                            style="border-spacing:0;mso-table-lspace: 0; mso-table-rspace: 0;">
                                                            <tr>
                                                                <td align="right" style="padding-bottom: 13px;">
                                                                    <img src="https://shootnbox.fr/mail/smartphone.png" alt="smartphone" style="max-width: 145px; width: 100%;">
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
                            style="padding-left: 20px;padding-right: 20px;line-height: 0; font-size: 0; padding-bottom: 15px;">
                            <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation"
                                style="margin:0; overflow: hidden; padding:0;mso-table-lspace: 0; mso-table-rspace: 0;">
                                <tr>
                                    <td align="center" valign="top" style="padding-top: 15px;padding-bottom: 0px;">
                                        <p
                                            style="font-family: \'Raleway\'; font-style: normal; font-weight: 800; font-size: 17px; line-height: 34px; text-align: center;color: #ca4ca8;">
                                            Votre photobooth en détails
                                        </p>

                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" valign="top">
                                        <table border="0" cellpadding="0" cellspacing="0" role="presentation"
                                            width="100%"
                                            style="margin:0; overflow: hidden; padding:0;mso-table-lspace: 0; mso-table-rspace: 0;background: white;border-radius: 10px;">

                                            <tr>
                                                <td
                                                    style="padding-top:15px;padding-right:20px;padding-left:20px; padding-bottom: 20px; text-align: center;">
                                                    <p
                                                                        style="font-family: \'Raleway\'; font-style: normal; font-weight: 500; font-size: 12px; line-height: 20px; color: #2B3044; padding-bottom: 15px;">
                                                                        Ci-dessus, nos dossiers techniques complet pour vous aider à anticiper au mieux votre événement.
                                                                        <span style="font-weight: 600;">Cliquez sur les modules pour télécharger les dossiers.</span>
                                                                    </p>
                                                    <!--[if (gte mso 9)|(IE)]>
                                              <table width="100%">
                                                <tr>
                                                  <td width="60%" valign="top" >
                                                    <![endif]-->
                                                    <div class="photos">
                                                        <div class="photo-container" style="background: rgba(241, 121, 129, 0.09);border-radius: 10px;">
                                                            <p
                                            style="font-family: \'Raleway\'; font-style: normal; font-weight: 800; font-size: 12px; line-height: 15px; text-align: center;color: #ca4ca8; max-width: 140px; width: 100%;">
                                            Conditions générales <br> de location
                                        </p>
                                                            <img src="https://shootnbox.fr/mail/option1.png" alt="location" style="max-width: 140px; width: 100%">
                                                        </div>
                                                        <div class="photo-container" style="background: rgba(241, 121, 129, 0.09);border-radius: 10px;">
                                                            <p
                                            style="font-family: \'Raleway\'; font-style: normal; font-weight: 800; font-size: 12px; line-height: 30px; text-align: center;color: #ca4ca8; max-width: 140px; width: 100%;">
                                            Plaquette commerciale
                                        </p>
                                                            <img src="https://shootnbox.fr/mail/option2.png" alt="location" style="max-width: 140px; width: 100%">
                                                        </div>
                                                        <div class="photo-container" style="background: rgba(241, 121, 129, 0.09);border-radius: 10px;">
                                                            <p
                                            style="font-family: \'Raleway\'; font-style: normal; font-weight: 800; font-size: 12px; line-height: 15px; text-align: center;color: #ca4ca8; max-width: 140px; width: 100%;">
                                            Exemples de <br> contours photos
                                        </p>
                                                            <img src="https://shootnbox.fr/mail/option3.png" alt="location" style="max-width: 140px; width: 100%">
                                                        </div>
                                                    </div>
                                                    <!--[if (gte mso 9)|(IE)]>
                                                    </td><td width="40%" valign="top" >
                                                    <![endif]-->

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
                        <td align="center" valign="top" bgcolor="pink"
                            style="padding-left: 20px;padding-right: 20px;line-height: 0; font-size: 0;">
                            <table border="0" cellpadding="0" cellspacing="0" role="presentation"
                                            width="100%"
                                            style="margin:0; overflow: hidden; padding:0;mso-table-lspace: 0; mso-table-rspace: 0;">

                                        </table>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" bgcolor="#eab9dd"
                            style="padding-left: 20px;padding-right: 20px;line-height: 0; font-size: 0;">
                            <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation"
                                style="margin:0; overflow: hidden; padding:0;mso-table-lspace: 0; mso-table-rspace: 0;">
                                <tr>
                                    <td align="center" valign="top">
                                        <table border="0" cellpadding="0" cellspacing="0" role="presentation"
                                            width="100%"
                                            style="margin:0; overflow: hidden; padding:0;mso-table-lspace: 0; mso-table-rspace: 0;">
                                            <tr>
                                                <td
                                                    style="padding-top:0px;padding-bottom:0px;padding-right:0;padding-left:0;">
                                                    <!--[if (gte mso 9)|(IE)]>
                                              <table width="100%">
                                                <tr>
                                                  <td width="65%" valign="top" >
                                                    <![endif]-->

                                                    <!--[if (gte mso 9)|(IE)]>
                                                    </td><td width="25%" valign="top" >
                                                    <![endif]-->
                                                        <div class="contact-bar">
                                                            <a href="mailto:contact@shootnbox.fr">contact@shootnbox.fr</a>
                                                            <a href="tel:0145016666">01 45 01 66 66</a>
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
                                <tr>
                                    <td align="center" valign="top"
                                        style="padding-left: 20px;padding-right: 20px;line-height: 10; font-size: 0;padding-top: 10px; padding-bottom: 5px; background-color: #e63cb9!important; border-radius: 10px;">
                                        <table width="100%" border="0" cellpadding="0" cellspacing="0"
                                            role="presentation"
                                            style="margin:0; overflow: hidden; padding:0;mso-table-lspace: 0; mso-table-rspace: 0;">

                                            <tr>
                                                <td align="left" valign="top" style="padding-bottom: 5px; ">
                                                    <p
                                                        style="font-family: \'Raleway\'; font-style: normal; font-weight: 400; font-size: 12px; line-height: 14px; color: #ffffff; display: flex; justify-content: space-between;">

                                                            <a href="mailto:contact@shootnbox.fr" style="color: #ffffff">contact@shootnbox.fr</a>
                                                            <a href="tel:0145016666" style="color: #ffffff !important">Agence Paris : 01 45 01 66 66</a>
                                                            <a href="tel:0532969696" style="color: #ffffff !important">Agence Bordeaux : 05 32 96 96 96</a>
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" valign="top"
                                        style="padding-left: 20px;padding-right: 20px;line-height: 10; font-size: 0;padding-top: 10px; padding-bottom: 5px; border-radius: 10px;">
                                        <table width="100%" border="0" cellpadding="0" cellspacing="0"
                                            role="presentation"
                                            style="margin:0; overflow: hidden; padding:0;mso-table-lspace: 0; mso-table-rspace: 0;">

                                            <tr>
                                                <td align="left" valign="top" style="padding-bottom: 5px; ">
                                                    <div class="photos">
                                                        <img src="https://shootnbox.fr/mail/photo1.png" alt="photo1" style="max-width: 225px; width: 100%; height: 50px;">
                                                        <img src="https://shootnbox.fr/mail/label.png" alt="label" style="max-width: 120px; width: 100%; height: 70px;">
                                                        <img src="https://shootnbox.fr/mail/photo2.png" alt="photo1" style="max-width: 225px; width: 100%; height: 50px;">
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
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
 echo mail("zazurka@gmail.com", "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);

?>