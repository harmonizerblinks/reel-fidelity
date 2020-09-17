<html>
    <body>
        <style>
            * {
                font-family: Arial, sans-serif;
                size: 0.9em;
            }
            td{
                padding: 1em;
                vertical-align: middle;
            }
        </style>
        <table border="0" width="80%" style="margin: 0 auto;">
            <tr>
                <td style="padding: 1.5em">
                    <table border="0" width="100%" style="border: #00acee 1px solid">
                        <!--
                        <tr>
                            <td style="text-align: center">
                                <img src="<?php echo AppConfig::ROOT_URL?>/assets/img/logo.png" class="logo" alt="" />
                            </td>
                        </tr>
                        -->
                        <tr>
                            <td>
                                <h3 style="padding: 1em 0; border-bottom: #ccc 1px solid; text-align: center">Enquiry</h3>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="90%">
                                    <tr>
                                        <td width="40%">Customer Name:</td>
                                        <td>{{name}}</td>
                                    </tr>                                                                        
                                    <tr>
                                        <td>Email Address:</td>
                                        <td>{{email}}</td>
                                    </tr> 
                                    <tr>
                                        <td>Telephone:</td>
                                        <td>{{phone}}</td>
                                    </tr>                                    
                                    <tr>
                                        <td>Subject:</td>
                                        <td>{{subject}}</td>
                                    </tr>                                                                        
                                    <tr>
                                        <td>Message:</td>
                                        <td>{{message}}</td>
                                    </tr>                                    
                                </table>
                            </td>
                        </tr>
                    </table>
                    <p><em>Email sent from Smart Screen.</em></p>
                </td>
            </tr>
        </table>
        <style type="text/css">
            *{
                font-family: Arial, sans-serif;
            }
        </style>
    </body>
</html>
