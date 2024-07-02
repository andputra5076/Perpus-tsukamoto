<?php
    include_once'DBConnection.php';      
    class DBQuery{      
	public function IUD($query){
	 	 mysqli_query(DBConnection::getdbconnection(), $query); 		
	}      
	public  function SetDropdown($query,$selectedColumn){
		$command = mysqli_query(DBConnection::getdbconnection(),$query);
		echo "<select>";
		while($rows = $command-> fetch_assoc())
		{
			echo("<option>".$rows[$selectedColumn]."</option>");
		}
		echo "</select>";
	}       
        public  function SetDiv($query,$PHPFiles){   
		$command = mysqli_query(DBConnection::getdbconnection(),$query);
                if($command -> num_rows > 0)// Counting Row kapag may laman
                {
                    while($row = $command-> fetch_assoc())
                    {
                       include $PHPFiles;
                    }                  
                }          
        }
 }
