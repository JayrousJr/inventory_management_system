<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">


        <!-- Fonts -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="assets/css/bootstrap.css">

        <style media="all">
        @page {
            size: A4;
            margin: 10px 15px;
        }

        footer {
            position: fixed;
            bottom: 50px;
            left: 0px;
            right: 0px;
            display: inline;
            border-top: 2px solid black;
        }

        .footer1 {
            float: left;
            font-size: 12px;
            text-align: left;
            font-weight: 500;
        }

        .footer2 {
            float: right;
            display: block;
            font-size: 12px;
            text-align: right;
            margin-left: 250px;
        }



        @media print {

            html,
            body {
                font-family: 'dejavusans';
                width: 80mm;
                height: 57mm
            }
        }

        body {
            font-family: 'dejavusans';
        }

        .header-section {
            text-align: center;
        }

        .header-text {
            font-size: 22px;
            color: black;
        }


        .sub-header {
            font-size: 18px;
            color: black;
        }

        .lower-part {
            display: inline;
            /* margin-bottom: 15px; */
        }

        .company-section {
            border-bottom: 7px solid #389ad2;
            margin-top: 130px;
        }

        .address {
            float: left;
            font-size: 14px;
        }

        .image-part {
            float: right;
            margin-left: 80%;
            width: 120px;
        }

        .table-container {
            margin-top: 15px;
        }

        .tables-row {
            display: inline;
        }

        .left {
            float: left;
        }

        .right {
            /* float: right; */
            /* margin-left: 37%; */
        }

        .heads {
            /* float: right; */
        }


        table {
            color: #fff;
            font-family: 'dejavusans';
            font-size: 12px;
            background-color: #fff;
            margin-bottom: 5px;
        }

        th {
            border: 1px solid #1381ce;
            background-color: #389ad2;
            text-align: center;
        }

        td {
            color: #000;
            border: 1px solid #138ace;
            background-color: #fff;
        }
        </style>
    </head>

    <body>

        <div class="container-fluid ">
            <div class="row heads">
                <div class="col-md-12 header-section">
                    <h2 class="header-text">ABNOW.YOU</h2>
                    <h3 class="sub-header">SALE RECEIPT</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-12 lower-part">
                    <p class="address">
                        ABNOW.YOU<br>
                        123 MBEZI BEACH,<br>
                        DAR ES SALAAM, TANZANIA.<br>
                        WEB: www.abnow.com<br>
                        Email: admin@abnow.com<br>
                        Tel: +255 (755) 123 4567</p>
                    <div class="image-part">
                        <!-- <img src="{{public_path(url('/storage/images/logo/logo.png'))}}" width="120px"> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="company-section"></div>

        <div class="container-fluid table-container">
            <div class="row tables-row">

                <div class="right">
                    <table cellpadding="2" cellspacing="0">

                        <tr>
                            <th width="220px">
                                <strong>Product</strong>
                            </th>

                            <th width="80px">
                                <strong></strong>
                            </th>

                            <th width="80px">
                                <strong>Quantity</strong>
                            </th>

                            <th width="80px">
                                <strong>Unit Price</strong>
                            </th>

                            <th width="120px">
                                <strong></strong>
                            </th>


                            <th width="100px">
                                <strong>Total</strong>
                            </th>

                        <tr>

                            <td style="text-align:center" width="220px">&nbsp; {{$record->pro_name}}
                            </td>

                            <td style="text-align:center" width="80px" style="background-color: #b8d9ed">
                            </td>

                            <td style="text-align:center" width="80px">&nbsp; {{$record->pro_quantity}}
                            </td>

                            <td style="text-align:center" width="80px">&nbsp; {{$record->pro_selling_price}}
                            </td>

                            <td style="text-align:center" width="120px" style="background-color: #b8d9ed">
                            </td>

                            <td style="text-align:center" width="100px">&nbsp; {{$record->pro_cost}}
                            </td>
                        </tr>

                        <tr>

                            <td style="text-align:center" width="220px">&nbsp;
                            </td>

                            <td style="text-align:center" width="80px" style="background-color: #b8d9ed">
                            </td>

                            <td style="text-align:center" width="80px">
                            </td>

                            <td style="text-align:center" width="80px">
                            </td>

                            <td style="text-align:center" width="120px" style="background-color: #b8d9ed">
                            </td>

                            <td style="text-align:center" width="100px">
                            </td>
                        </tr>

                        <tr>

                            <td style="text-align:center" width="220px">&nbsp;
                            </td>

                            <td style="text-align:center" width="80px" style="background-color: #b8d9ed">
                            </td>

                            <td style="text-align:center" width="80px">
                            </td>

                            <td style="text-align:center" width="80px">
                            </td>

                            <td style="text-align:center" width="120px" style="background-color: #b8d9ed">
                            </td>

                            <td style="text-align:center" width="100px">
                            </td>
                        </tr>
                        <tr>

                            <td style="text-align:center" width="220px">&nbsp;
                            </td>

                            <td style="text-align:center" width="80px" style="background-color: #b8d9ed">
                            </td>

                            <td style="text-align:center" width="80px">
                            </td>

                            <td style="text-align:center" width="80px">
                            </td>

                            <td style="text-align:center" width="120px" style="background-color: #b8d9ed">
                            </td>

                            <td style="text-align:center" width="100px">
                            </td>
                        </tr>


                    </table>

                </div>
            </div>
        </div>
        <div class="container-fluid">
            <footer>
                <div class="footer1">
                    <p>ABNOW.YOU</p>
                </div>
                <div class="footer2">
                    <span style="margin-right: 100px;">Page 1 of 1</span><span style="margin-left: 80px;">ONILINE
                        INVOICING SYSTEM</span>

                </div>
            </footer>
        </div>
    </body>

</html>