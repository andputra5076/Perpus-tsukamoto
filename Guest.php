<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="assets/css/Animation.css">
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<script src="assets/js/MyJS.js" type="text/javascript"></script>
<style>
</style>
</head>
<body>
    <div id="SelectedRow" class="text-white"></div>
    <div class="container-fluid" style="margin-top:100px">
        <center>
        <div class="w-75">
        <table class="table">
            <thead>
                <td>No.</td>  
                <td>Name</td>  
                <td>Location</td>  
                <td>Feedback</td>  
            </thead>
            <tbody>
                <?php
                 include_once 'MyFrameworks/DBQuery.php';
                 DBQuery::SetDiv("SELECT * FROM bank_table order by IDNum  desc",'Gridview_bank_table_Guest.php'); 
                ?>
            </tbody>
        </table>
        </div>
        </center>
    </div>  
    </div>
</body>
</html>