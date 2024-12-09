<?php
$cfg = array();
$cfg['xmlclient'] = FALSE;
$cfg['doctypeid'] = "<!DOCTYPE html>";
$contenttype = ($cfg['doctypeid']>2 && $cfg['xmlclient']) ? 'application/xhtml+xml' : 'text/html; charset=utf-8';
@header('Content-Type: '.$contenttype);

@require_once("../inc/mainfile.php");

foreach ($_GET as $key => $value){if ($key != "submit") {$$key = mysqli_real_escape_string($conn, $value);}}


		$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$order_id);
		$row_orders = mysqli_fetch_assoc($result_orders);

		$subject = "Votre espace client Shootnbox ".date("d-m-Y H:i", time());
		$mailheaders = "MIME-Version: 1.0\r\n";
		$mailheaders.= "Content-Type: text/html; charset=utf-8\r\n";
		$mailheaders.= "From: =?utf-8?B?".base64_encode("ShootnBox")."?= <hello@shootnbox.fr>\r\n";
		$mailheaders.= "X-Mailer: PHP/".phpversion()."\r\n";
		$msg = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
  <head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="telephone=no" name="format-detection">
    <title></title>
    <!--[if gte mso 9]><style>
        sup { font-size: 100% !important; }
      </style><![endif]-->
    <style type="text/css">
      /* CONFIG STYLES Please do not delete and edit CSS styles below */
      /* IMPORTANT THIS STYLES MUST BE ON FINAL EMAIL */
      a[x-apple-data-detectors] {
          color: inherit !important;
          text-decoration: none !important;
          font-size: inherit !important;
          font-family: inherit !important;
          font-weight: inherit !important;
          line-height: inherit !important;
      }
      /*
      END OF IMPORTANT
      */
      /*
      END CONFIG STYLES
      */
      /* RESPONSIVE STYLES Please do not delete and edit CSS styles below. If you don\'t need responsive layout, please delete this section. */
      @media only screen and (max-width: 600px) {
          p,
          ul li,
          ol li,
          a {
        line-height: 150% !important;
          }
          h1,
          h2,
          h3,
          h1 a,
          h2 a,
          h3 a {
        line-height: 120%;
          }
          h1 {
        font-size: 30px !important;
        text-align: center;
          }
          h2 {
        font-size: 26px !important;
        text-align: center;
          }
          h3 {
        font-size: 20px !important;
        text-align: center;
          }
          .es-header-body h1 a,
          .es-content-body h1 a,
          .es-footer-body h1 a {
        font-size: 30px !important;
          }
          .es-header-body h2 a,
          .es-content-body h2 a,
          .es-footer-body h2 a {
        font-size: 26px !important;
          }
          .es-header-body h3 a,
          .es-content-body h3 a,
          .es-footer-body h3 a {
        font-size: 20px !important;
          }
          .es-menu td a {
        font-size: 16px !important;
          }
          .es-header-body p,
          .es-header-body ul li,
          .es-header-body ol li,
          .es-header-body a {
        font-size: 16px !important;
          }
          .es-content-body p,
          .es-content-body ul li,
          .es-content-body ol li,
          .es-content-body a {
        font-size: 16px !important;
          }
          .es-footer-body p,
          .es-footer-body ul li,
          .es-footer-body ol li,
          .es-footer-body a {
        font-size: 16px !important;
          }
          .es-infoblock p,
          .es-infoblock ul li,
          .es-infoblock ol li,
          .es-infoblock a {
        font-size: 12px !important;
          }
          *[class="gmail-fix"] {
        display: none !important;
          }
          .es-m-txt-c,
          .es-m-txt-c h1,
          .es-m-txt-c h2,
          .es-m-txt-c h3 {
        text-align: center !important;
          }
          .es-m-txt-r,
          .es-m-txt-r h1,
          .es-m-txt-r h2,
          .es-m-txt-r h3 {
        text-align: right !important;
          }
          .es-m-txt-l,
          .es-m-txt-l h1,
          .es-m-txt-l h2,
          .es-m-txt-l h3 {
        text-align: left !important;
          }
          .es-m-txt-r img,
          .es-m-txt-c img,
          .es-m-txt-l img {
        display: inline !important;
          }
          .es-adaptive table,
          .es-left,
          .es-right {
        width: 100% !important;
          }
          .es-content table,
          .es-header table,
          .es-footer table,
          .es-content,
          .es-footer,
          .es-header {
        width: 100% !important;
        max-width: 600px !important;
          }
          .es-adapt-td {
        display: block !important;
        width: 100% !important;
          }
          .adapt-img {
        width: 100% !important;
        height: auto !important;
          }
          .es-m-p0 {
        padding: 0 !important;
          }
          .es-m-p0r {
        padding-right: 0 !important;
          }
          .es-m-p0l {
        padding-left: 0 !important;
          }
          .es-m-p0t {
        padding-top: 0 !important;
          }
          .es-m-p0b {
        padding-bottom: 0 !important;
          }
          .es-m-p20b {
        padding-bottom: 20px !important;
          }
          .es-mobile-hidden,
          .es-hidden {
        display: none !important;
          }
          tr.es-desk-hidden,
          td.es-desk-hidden,
          table.es-desk-hidden {
        width: auto !important;
        overflow: visible !important;
        float: none !important;
        max-height: inherit !important;
        line-height: inherit !important;
          }
          tr.es-desk-hidden {
        display: table-row !important;
          }
          table.es-desk-hidden {
        display: table !important;
          }
          td.es-desk-menu-hidden {
        display: table-cell !important;
          }
          .es-menu td {
        width: 1% !important;
          }
          table.es-table-not-adapt,
          .esd-block-html table {
        width: auto !important;
          }
          table.es-social {
        display: inline-block !important;
          }
          table.es-social td {
        display: inline-block !important;
          }
          .es-desk-hidden {
        display: table-row !important;
        width: auto !important;
        overflow: visible !important;
        max-height: inherit !important;
          }
          .es-m-p5 {
        padding: 5px !important;
          }
          .es-m-p5t {
        padding-top: 5px !important;
          }
          .es-m-p5b {
        padding-bottom: 5px !important;
          }
          .es-m-p5r {
        padding-right: 5px !important;
          }
          .es-m-p5l {
        padding-left: 5px !important;
          }
          .es-m-p10 {
        padding: 10px !important;
          }
          .es-m-p10t {
        padding-top: 10px !important;
          }
          .es-m-p10b {
        padding-bottom: 10px !important;
          }
          .es-m-p10r {
        padding-right: 10px !important;
          }
          .es-m-p10l {
        padding-left: 10px !important;
          }
          .es-m-p15 {
        padding: 15px !important;
          }
          .es-m-p15t {
        padding-top: 15px !important;
          }
          .es-m-p15b {
        padding-bottom: 15px !important;
          }
          .es-m-p15r {
        padding-right: 15px !important;
          }
          .es-m-p15l {
        padding-left: 15px !important;
          }
          .es-m-p20 {
        padding: 20px !important;
          }
          .es-m-p20t {
        padding-top: 20px !important;
          }
          .es-m-p20r {
        padding-right: 20px !important;
          }
          .es-m-p20l {
        padding-left: 20px !important;
          }
          .es-m-p25 {
        padding: 25px !important;
          }
          .es-m-p25t {
        padding-top: 25px !important;
          }
          .es-m-p25b {
        padding-bottom: 25px !important;
          }
          .es-m-p25r {
        padding-right: 25px !important;
          }
          .es-m-p25l {
        padding-left: 25px !important;
          }
          .es-m-p30 {
        padding: 30px !important;
          }
          .es-m-p30t {
        padding-top: 30px !important;
          }
          .es-m-p30b {
        padding-bottom: 30px !important;
          }
          .es-m-p30r {
        padding-right: 30px !important;
          }
          .es-m-p30l {
        padding-left: 30px !important;
          }
          .es-m-p35 {
        padding: 35px !important;
          }
          .es-m-p35t {
        padding-top: 35px !important;
          }
          .es-m-p35b {
        padding-bottom: 35px !important;
          }
          .es-m-p35r {
        padding-right: 35px !important;
          }
          .es-m-p35l {
        padding-left: 35px !important;
          }
          .es-m-p40 {
        padding: 40px !important;
          }
          .es-m-p40t {
        padding-top: 40px !important;
          }
          .es-m-p40b {
        padding-bottom: 40px !important;
          }
          .es-m-p40r {
        padding-right: 40px !important;
          }
          .es-m-p40l {
        padding-left: 40px !important;
          }
          .es-content-body .fz46 {
        font-size: 46px !important;
          }
          .custom_padding {
        padding-right: 0 !important;
        padding-left: 0 !important;
          }
      }
      /* END RESPONSIVE STYLES */
    </style>
  </head>
  <body style="width:100%;font-family:arial, \'helvetica neue\', helvetica, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;">
    <div class="es-wrapper-color" style="background-color:#f6f6f6;">
      <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-image:;background-repeat:repeat;background-position:center top;background-color:#f6f6f6;">
        <tbody>
          <tr>
            <td class="esd-email-paddings" valign="top" style="padding:0;Margin:0;">
              <table class="es-content esd-footer-popover" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;width:100%;table-layout:fixed !important;">
                <tbody>
                  <tr>
                    <td class="esd-stripe" align="center" style="padding:0;Margin:0;">
                      <table class="es-content-body" width="600" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#ffffff;">
                        <tbody>
                          <tr>
                            <td class="esd-structure es-p40t es-p20r es-p20l es-m-p15r es-m-p15l" align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:40px;">
                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                <tbody>
                                  <tr>
                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                        <tbody>
                                          <tr>
                                            <td align="center" class="esd-block-image" style="padding:0;Margin:0;font-size: 0px;"><a target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#2cb543;font-size:14px;"><img src="https://opgygf.stripocdn.email/content/guids/CABINET_36d8f38bcb5973575b8b7d024ca409735a92d35124f3ceec002556594bdaf13a/images/logo.png" alt style="border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;display: block;" width="192"></a></td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                          <tr>
                            <td class="esd-structure es-p20t es-p20r es-p20l es-m-p15r es-m-p15l" align="left" style="padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px;">
                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                <tbody>
                                  <tr>
                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                        <tbody>
                                          <tr>
                                            <td align="center" class="esd-block-text es-p20b" style="padding:0;Margin:0;padding-bottom:20px;">
                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;color: #da3a8d; font-size: 15px;"><strong>Bonjour,</strong></p>
                                            </td>
                                          </tr>
                                          <tr>
                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
                                              <p class="custom_padding" style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;padding-right:100px;padding-left:100px;color: #da3a8d; font-size: 15px;"><strong>Merci pour votre confiance et bienvenue dans la Shootnbox Family&nbsp;!</strong></p>
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                          <tr>
                            <td class="esd-structure es-p40t es-p20r es-p20l es-m-p15r es-m-p15l" align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:40px;">
                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                <tbody>
                                  <tr>
                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                        <tbody>
                                          <tr>
                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size: 15px; color: #707070;">Vous pouvez dès à présent vous connecter à votre <strong>Espace Client !</strong></p>
                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size: 15px; color: #707070;">Celui-ci est votre interface dédiée, regroupant toutes vos informations.</p>
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                          <tr>
                            <td class="esd-structure es-p40t es-p20r es-p20l es-m-p15r es-m-p15l" align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:40px;">
                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                <tbody>
                                  <tr>
                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                        <tbody>
                                          <tr>
                                            <td align="center" class="esd-block-text es-p40r es-p40l es-m-p0r es-m-p0l" style="padding:0;Margin:0;padding-left:40px;padding-right:40px;">
                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size: 15px; color: #707070;">Veuillez complétez les champs requis afin de finaliser les détails indispensables de votre événement.</p>
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                          <tr>
                            <td class="esd-structure es-p30t es-p40r es-p40l es-m-p15r es-m-p15l" align="left" style="padding:0;Margin:0;padding-top:30px;padding-left:40px;padding-right:40px;">
                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                <tbody>
                                  <tr>
                                    <td width="520" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-spacing:0px;border-left:3px solid #ff0000;border-right:3px solid #ff0000;border-top:3px solid #ff0000;border-bottom:3px solid #ff0000;border-radius: 12px; border-collapse: separate;">
                                        <tbody>
                                          <tr>
                                            <td align="center" class="esd-block-text es-p15t es-p10r es-p10l" style="padding:0;Margin:0;padding-left:10px;padding-right:10px;padding-top:15px;">
                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;color: #ff0000; font-size: 15px;"><strong>ATTENTION !</strong></p>
                                            </td>
                                          </tr>
                                          <tr>
                                            <td align="center" class="esd-block-text es-p15b es-p10r es-p10l" style="padding:0;Margin:0;padding-left:10px;padding-right:10px;padding-bottom:15px;">
                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;color: #ff0000; font-size: 15px;"><strong>Merci de remplir ces informations avant le : 05/06/2023</strong></p>
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                          <tr>
                            <td class="esd-structure es-p30t es-p20r es-p20l" align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:30px;">
                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                <tbody>
                                  <tr>
                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                        <tbody>
                                          <tr>
                                            <td align="center" class="esd-block-button" style="padding:0;Margin:0;"><span class="es-button-border" style="border-style:solid solid solid solid;display:inline-block;border-radius:30px;width:auto;border-width: 0px; border-color: #2cb543; background: #da3a8d;"><a href="https://ftp.shootnbox.fr/cabinet/" class="es-button" target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#2cb543;display:inline-block;border-radius:30px;font-family:arial, \'helvetica neue\', helvetica, sans-serif;font-weight:normal;font-style:normal;line-height:120%;color:#ffffff;text-decoration:none;width:auto;text-align:center;padding:10px 20px 10px 20px;mso-padding-alt:0;mso-border-alt:10px solid #31cb4b;mso-style-priority:100 !important;text-decoration:none !important;background: #da3a8d; font-size: 17px;">Cliquez ici pour y accéder</a></span></td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                          <tr>
                            <td class="esd-structure es-p30t es-p40b es-p20r es-p20l es-m-p15r es-m-p15l" align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:30px;padding-bottom:40px;">
                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                <tbody>
                                  <tr>
                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                        <tbody>
                                          <tr>
                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size: 15px; color: #707070;"><strong>Vos identifiants pour vous connecter sont les suivants:</strong></p>
                                            </td>
                                          </tr>
                                          <tr>
                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size: 15px; color: #707070;"><strong></strong><strong><span style="color:#D23680;">Identifiant:</span> '.$row_orders['num_id'].'</strong></p>
                                            </td>
                                          </tr>
                                          <tr>
                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size: 15px; color: #707070;"><strong><span style="color:#D23680;">Mot de passe:</span> '.$row_orders['password'].'</strong></p>
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                          <tr>
                            <td class="esd-structure es-p20" align="left" bgcolor="#707070" style="padding:0;Margin:0;padding:20px;background-color: #707070;">
                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                <tbody>
                                  <tr>
                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                        <tbody>
                                          <tr>
                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size:14px;color: #ffffff;">Shootnbox</p>
                                            </td>
                                          </tr>
                                          <tr>
                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size:14px;color: #ffffff;">contact@shootnbox.fr 01 45</p>
                                            </td>
                                          </tr>
                                          <tr>
                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size:14px;color: #ffffff;">01 45 01 66 66</p>
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </body>
</html>';
              echo $msg;