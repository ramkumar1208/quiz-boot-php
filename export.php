<?php
require('conn.php');
$sql="select * from users";
$res=mysqli_query($con,$sql);
$html='<table><tr>
        <th>Name</th>
        <th>mobile </th>
        <th>Email</th>
        <th>dob</th>
        <th>ic number</th>
        <th>batch code </th>
      </tr>';
while($row=mysqli_fetch_assoc($res)){
	$html.='<tr><td>'.$row['user_name'].'</td><td>'.$row['mobile'].'</td><td>'.$row['user_email'].'</td><td>'.$row['user_dob'].'</td><td>'.$row['ic_number'].'</td><td>'.$row['batch_code'].'</td></tr>';
}
$html.='</table>';
header('Content-Type:application/xls');
header('Content-Disposition:attachment;filename=report.xls');
echo $html;
?>