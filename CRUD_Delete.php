<div class="modal-backdrop modal animate-fading" style="background-color: rgba(0,0,0,.7)" id="background"></div>
<div class="modal " id="Delete_bank_table">
        <div class="modal-dialog carousel-fade animate-top modal-dialog-scrollable ">
            <div class=" modal-content">
                <div class="modal-header"> <a class="navbar-brand"><b>Delete account</b></a></div>
                <div class="modal-body">
                   <div class=" nav-link">Are you sure?</div>                  
                </div>
                <div class ="modal-footer">
                    <input id="delete_IDNum" name="delete_IDNum"  style="display:none" >
                    <button onclick="hideModal('Delete_bank_table')" class="btn btn-danger w-25">Cancel</button>
                    <button name="deletebtn" onclick="Delete_bank_table()" class="btn btn-success w-25">Yes</button>                                  
                </div>
            </div>
        </div>
    </div>
<script>
    function Delete_bank_table(){
        var delete_IDNum = document.getElementById("delete_IDNum").value;
        window.location.href='?IDNum='+delete_IDNum;
    }
</script>
<?php
if(isset($_GET["IDNum"])){
    include_once 'MyFrameworks/DBQuery.php';
    DBQuery::IUD("Delete from bank_table where IDNum = '".$_GET["IDNum"]."'");
    echo '<script>window.location.href="CRUD.php";</script>';   
}
?>