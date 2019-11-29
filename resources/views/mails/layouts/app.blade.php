<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>WANTEDLY</title>

    <!-- Facebook sharing information tags -->
    <meta property="og:title" content="" />

    <style type="text/css">
        /* Forces Hotmail to display emails at full width. */
        
        .ExternalClass {
            width: 100%;
        }
        /* Forces Hotmail to display normal line spacing. */
        
        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Prevents Webkit and Windows Mobile platforms from changing default font sizes. */
        
        body {
            -webkit-text-size-adjust: none;
            -ms-text-size-adjust: none;
        }
        /* Resets all body margins and padding to "0" for good measure. */
        
        body {
            margin: 0;
            padding: 0;
        }
        /* Resolves webkit padding issue. */
        
        table {
            border-spacing: 0;
        }
        /* Resolves the Outlook 2007, 2010, and Gmail td padding issue. */
        
        table td {
            border-collapse: collapse;
        }
        p {
            margin: 0;
            padding: 10px 0;
            margin-bottom: 0;
        }
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            color: #333333;
            line-height: 100%;
        }
        body,
        #body_style {
            width: 100% !important;
            color: #333333;
            background: #f0f0f0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
            line-height: 1.4;
        }
        a {
            color: #114eb1;
            text-decoration: none;
        }
        a:link {
            color: #114eb1;
            text-decoration: none;
        }
        a:visited {
            color: #183082;
            text-decoration: none;
        }
        a:focus {
            color: #0066ff !important;
        }
        a:hover {
            color: #0066ff !important;
        }
        a.email:hover {
            color: #fff;
        }
        a.email {
            text-decoration: none;
            color: #fff;
        }
        a[href^="tel"],
        a[href^="sms"] {
            text-decoration: none;
            color: #333333;
            pointer-events: none;
            cursor: default;
        }
        .mobile_link a[href^="tel"],
        .mobile_link a[href^="sms"] {
            text-decoration: default;
            color: #6e5c4f !important;
            pointer-events: auto;
            cursor: default;
        }
        @media only screen and (max-width: 639px) {
            /* Hide elements at smaller screen sizes (!important needed to override inline CSS). */
            
            body[yahoo] .hide {
                display: none !important;
            }
            /* Adjust table widths at smaller screen sizes. */
            
            body[yahoo] .table {
                width: 320px !important;
            }
            body[yahoo] .innertable {
                width: 280px !important;
            }
            /* Resize hero image at smaller screen sizes. */
            
            body[yahoo] .heroimage {
                width: 280px !important;
                height: 100px !important;
            }
            /* Resize page shadow at smaller screen sizes. */
            
            body[yahoo] .shadow {
                width: 280px !important;
                height: 4px !important;
            }
            /* Collapse cells at smaller screen sizes. */
            
            body[yahoo] .collapse-cell {
                width: 320px !important;
            }
            /* Range social icons left at smaller screen sizes. */
            
            body[yahoo] .social-media img {
                float: left !important;
                margin: 0 1em 0 0 !important;
            }
        }
        @media only screen and (min-width: 640px) and (max-width: 1024px) {} img {
            display:block;
            border:none;
            outline:none;
            text-decoration:none;
        }
        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
    </style>

</head>

<body style="width:100% !important; color:#333333; background:#f0f0f0; font-family:Arial,Helvetica,sans-serif; font-size:13px; line-height:1.4;" alink="#114eb1" link="#114eb1" bgcolor="#e0dbcf" text="#333333" yahoo="fix">

    <div id="body_style">
        
        <table style="font-family:arial;font-size:13px;color:#555;line-height:20px;background:#e4e4e4;padding:20px" border="0" cellspacing="0" cellpadding="0" width="100%" align="center">
        <tbody>
            <tr>
                <td>
                    <table style="background-color:#ec1c24;font-size:12px;line-height:20px;" align="center" bgcolor="#f4f4f4" border="0" cellpadding="0" cellspacing="0" width="620px">
                        <tbody>
                            <tr>
                                <td height="50px">
                                            <h3 style="color:#fff;padding-left:20px;">
                                                @yield('title')
                                            </h3>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table style="font-size:14px;line-height:20px;padding:15px 20px" align="center" bgcolor="#fff" border="0" cellpadding="0" cellspacing="0" width="620px">
                        <tbody>
                            <tr>
                                <td style="padding: 20px;">
                                    @yield('content')
                                    <p>Nếu bạn không phải là người gửi yêu cầu, vui lòng bỏ qua email này.</p>
                                    <p>WANTEDLY SYSTEM - <a href=" https://icd-vn.com">icd-vn.com</a></p>
                                    <p>
                                        <i>
                                            (Email này được gửi tự động từ hệ thống, bạn vui lòng không reply. Mọi thắc mắc vui lòng liên hệ thông tin bên dưới!)
                                        </i>
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table style="font-size:13px;color:#555;line-height:20px;padding:0px 20px 20px 20px" align="center" bgcolor="#fff" border="0" cellpadding="0" cellspacing="0" width="620px">
                        <tbody>
                            <tr>
                                <td>
                                    <hr style="border:none;border-top:1px dashed #e3e3e3;margin:0px">
                                </td>
                            </tr>

                            <tr>
                                <td style="padding:8px 0px;vertical-align:top;text-align:center;white-space:nowrap">
                                    <strong>ICD VIETNAM</strong>
                                    <p style="margin:0px;font-size:12px">
                                        <strong>Địa chỉ:</strong> 277 Lý Tự Trọng<br>
                                        <strong>Điện thoại:</strong> 0123456789  <br>
                                            <strong>Email: <a href="mailto: admin@icd-vn.com" target="_blank"> admin@icd-vn.com</a> | Website: <a target="_blank" href="http://icd-vn.com/">icd-vn.com</a></strong>
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            
        </tbody>
    </table>
        
        
        <!-- End of wrapper table -->

    </div>
    <!-- /PAGE WRAPPER -->

</body>

</html>