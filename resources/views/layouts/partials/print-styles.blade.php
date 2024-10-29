<style>
    @font-face {
        font-family: 'Segoe UI';
        font-style: normal;
        font-weight: normal;
        src: local('Segoe UI'),
            url({{ yurl('assets/fonts/segoeui/segoeui.ttf') }}) format('truetype');
    }

    @font-face {
        font-family: 'Segoe UI';
        font-style: italic;
        font-weight: normal;
        src: local('Segoe UI Italic'),
            url({{ yurl('assets/fonts/segoeui/segoeuii.ttf') }}) format('truetype');
    }

    @font-face {
        font-family: 'Segoe UI';
        font-style: normal;
        font-weight: bold;
        src: local('Segoe UI Bold'),
            url({{ yurl('assets/fonts/segoeui/segoeuib.ttf') }}) format('truetype');
    }

    @font-face {
        font-family: 'Segoe UI';
        font-style: italic;
        font-weight: bold;
        src: local('Segoe UI Bold Italic'),
            url({{ yurl('assets/fonts/segoeui/segoeuibi.ttf') }}) format('truetype');
    }

    /** Define the margins of your page **/
    @page {
        margin: 1cm;
    }

    * {
        box-sizing: border-box;
        font-family: "Segoe UI", sans-serif;
    }

    header {
        font-family: "Segoe UI", sans-serif;
        position: fixed;
        top: 0px;
        left: 0;
        right: 0;
        /*margin-left: 10mm;*/
        /*margin-right: 5mm;*/
        /*line-height: 35px;*/
    }

    footer {
        bottom: -30px;
        font-family: "Segoe UI", sans-serif;
        left: 0px;
        position: fixed;
        right: 0;
        height: 50px;
        /* line-height: 35px; */
    }

    body {
        font-size: 10pt;
        font-family: "Segoe UI", sans-serif;
        margin-top: 2cm;
    }

    .pagenum:before {
        content: counter(page);
        content: counter(page, decimal);
    }

    table {
        width: 100%;
        border: 1pt solid black;
        border-collapse: collapse;
    }

    tr th,
    tr td {
        border-bottom: 1pt solid black;
        border: 1pt solid black;
        border-right: 1pt solid black;
    }

    ul {
        margin: 0;
        padding-left: 20px;
    }

    .table-data {
        height: 44px;
        background-repeat: no-repeat;
        border: 1px solid;
        font-weight: normal;
        vertical-align: middle;
    }

    .table-data tr th,
    .table-data tr td {
        padding: 5px 8px;
    }

    .table-data tr td {
        vertical-align: top;
    }

    .table {
        display: table;
        width: 100%;
        border-collapse: collapse;
    }

    .table-row {
        display: table-row;
        /* border: none; */
        border: 1px solid black;
    }

    .table-cell {
        display: table-cell;
        /* border: none; */
        border: 1px solid black;
        padding: 5px;
    }

    .page-break: {
        page-break-inside: always;
    }

    .nowrap {
        white-space: nowrap;
    }

    .text-center {
        text-align: center;
    }

    .text-left {
        text-align: left;
    }

    .text-right {
        text-align: right;
    }

    .horizontal-line {
        border: 1px solid black;
        margin: 10px 0;
    }

    .wysiwyg-content {
        max-width: 100% !important;
        text-align: justify;
        width: 100% !important;
    }

    .wysiwyg-content img {
        max-height: 90% !important;
        max-width: 100% !important;
    }

    .wysiwyg-content table {
        width: 100px !important;
        overflow: hidden;
    }

    .wysiwyg-content td {
        max-width: 300px !important;
        overflow: hidden;
    }
</style>
