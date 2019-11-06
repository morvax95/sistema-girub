<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 01/06/2017
 * Time: 12:04 PM
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <title>FACTURA FACIL</title>
    <style type="text/css">

        ::selection { background-color: #E13300; color: white; }
        ::-moz-selection { background-color: #E13300; color: white; }

        body {
            background-color: #fff;
            margin: 40px;
            font: 13px/20px normal Helvetica, Arial, sans-serif;
            color: #4F5155;
        }

        a {
            color: #003399;
            background-color: transparent;
            font-weight: normal;
        }

        h1 {
            color: #444;
            background-color: transparent;
            border-bottom: 1px solid #D0D0D0;
            font-size: 18pt;
            font-weight: normal;
            margin: 0 0 14px 0;
            padding: 14px 15px 10px 15px;
        }

        code {
            font-family: Consolas, Monaco, Courier New, Courier, monospace;
            font-size: 12px;
            background-color: #f9f9f9;
            border: 1px solid #D0D0D0;
            color: #002166;
            display: block;
            margin: 14px 0 14px 0;
            padding: 12px 10px 12px 10px;
        }

        #container {
            margin: 10px;
            border: 1px solid #D0D0D0;
            box-shadow: 0 0 8px #D0D0D0;
            font-size: 12pt;
        }

        p {
            margin: 12px 15px 12px 15px;
        }
    </style>
</head>
<body>
<div id="container">
    <h1>CREACION DE BASE DE DATOS CORRECTA</h1>
    <p style="font-size:12pt">
        Su base de datos ha sido creada satisfactoriamente.
        <br><br>
        <a class="btn btn-primary" href="<?= base_url('facturaFacil/login/acceso') ?>"> CLICK AQUI PARA EMPEZAR </a>
    </p>
</div>
</body>
</html>
