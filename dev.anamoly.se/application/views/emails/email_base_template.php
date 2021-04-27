<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?php echo $subject; ?></title>
    <style>
.table-bordered {
border: 1px solid #f4f4f4;
}
tbody {
display: table-row-group;
vertical-align: middle;
border-top-color: inherit;
border-right-color: inherit;
border-bottom-color: inherit;
border-left-color: inherit;
}
tr {
display: table-row;
vertical-align: inherit;
border-top-color: inherit;
border-right-color: inherit;
border-bottom-color: inherit;
border-left-color: inherit;
}
table {
border-collapse: collapse;
border-spacing: 0;
}
.table {
width: 100%;
max-width: 100%;
margin-bottom: 20px;
}
.table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
border: 1px solid #f4f4f4;
padding-left : 16px;
padding-right: 16px;
}
</style>  
</head>
<body>
<?php echo $message; ?>
</body>
</html>
