<?php 
/* 

 *
 * @author      Trường An Phạm Nguyễn
 * @copyright   2011, The authors
 * @license     GNU AFFERO GENERAL PUBLIC LICENSE
 *        http://www.gnu.org/licenses/agpl-3.0.html
 * @link    https://github.com/cntn02/Semantic-document-base
 *
 * Jul 27, 2013

Original author:
*       Disclaimer Notice(s)                                                          
*       ex: This code is freely given to you and given "AS IS", SO if it damages      
*       your computer, formats your HDs, or burns your house I am not the one to 
*       blame.                                                                     
*       Moreover, don't forget to include my copyright notices and name.               
*   +------------------------------------------------------------------------------+ 
*       Author(s): Crooty.co.uk (Adam C)                                    
*   +------------------------------------------------------------------------------+ 

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/  

$data .= " 
<style> 
td,body 
{ 
    font-family: sans-serif; 
    font-size: 8pt; 
    color: #444444; 
} 
</style> 
<br> 
    <center> 
     <div style=\"border-bottom:1px #999999 solid;width:480;\"><b> 
       <font size='1' color='#3896CC'>Service Status</font></b> 
     </div>  
   </center> 
<br>"; 

//configure script 
$timeout = "1"; 

//set service checks 
$port[1] = "80";       $service[1] = "Apache";                  $ip[1] =""; 
$port[2] = "21";       $service[2] = "FTP";                     $ip[2] =""; 
$port[3] = "3306";     $service[3] = "MYSQL";                   $ip[3] =""; 
$port[4] = "22";       $service[4] = "Open SSH";             $ip[4] =""; 
$port[5] = "143";      $service[5] = "Email(IMAP)";             $ip[5] =""; 
$port[6] = "2095";     $service[6] = "Webmail";                 $ip[6] =""; 
$port[7] = "2082";     $service[7] = "Cpanel";                  $ip[7] =""; 
$port[8] = "80";       $service[8] = "Internet Connection";     $ip[8] ="google.com"; 
  //$port[9] = "2086";     $service[9] = "WHM";                     $ip[9] =""; 

// 
// NO NEED TO EDIT BEYOND HERE  
// UNLESS YOU WISH TO CHANGE STYLE OF RESULTS 
// 

//count arrays 
$ports = count($port); 
$ports = $ports + 1; 
$count = 1; 

//beggin table for status 
$data .= "<table width='480' border='1' cellspacing='0' cellpadding='3' style='border-collapse:collapse' bordercolor='#333333' align='center'>"; 

while($count < $ports){ 

     if($ip[$count]==""){ 
       $ip[$count] = "localhost"; 
     } 

        $fp = @fsockopen("$ip[$count]", $port[$count], $errno, $errstr, $timeout); 
        if (!$fp) { 
            $data .= "<tr><td>$service[$count]</td><td bgcolor='#FFC6C6'>Offline </td></tr>"; 
  fclose($fp); 
        } else { 
            $data .= "<tr><td>$service[$count]</td><td bgcolor='#D9FFB3'>Online</td></tr>"; 
            fclose($fp); 
        } 
    $count++; 


}  

//close table 
$data .= "</table>"; 

echo $data; 
 // 
// SERVER INFORMATION 
// 

$data1 .= " 
<br> 
    <center> 
     <div style=\"border-bottom:1px #999999 solid;width:480;\"><b> 
       <font size='1' color='#3896CC'>Server Information</font></b> 
     </div>  
   </center><BR>"; 

$data1 .= "<table width='480' border='1' cellspacing='0' cellpadding='3' style='border-collapse:collapse'  

bordercolor='#333333' align='center'>"; 

//GET SERVER LOADS 
$loadresult = @exec('uptime');  
preg_match("/averages?: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)/",$loadresult,$avgs); 


//GET SERVER UPTIME 
  $uptime = explode(' up ', $loadresult); 
  $uptime = explode(',', $uptime[1]); 
  $uptime = $uptime[0].', '.$uptime[1]; 

  //Get the disk space
  function getSymbolByQuantity($bytes) {
     $symbol = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB');
     $exp = floor(log($bytes)/log(1024));
    
     return sprintf('%.2f '.$symbol[$exp], ($bytes/pow(1024, floor($exp))));
 }
  $disk_space = getSymbolByQuantity(disk_total_space("/"));
  $disk_free = getSymbolByQuantity(disk_free_space("/"));
  $disk_free_precent = round(disk_free_space("/")*1.0/disk_total_space("/")*100,2);
  
  //Get ram usage
  $total_mem = preg_split('/ +/', @exec('free -m | grep em:'));
  $total_mem = $total_mem[1];
  $free_mem = preg_split('/ +/', @exec('free -m | grep cache:'));
  $free_mem = $free_mem[3];
  $free_mem_percent = round($free_mem*1.0/$total_mem*100,2);
  
  //Get top mem usage
  $tom_mem_arr = array();
  exec('ps -e -ocomm,rss | sort -b -k2,2n 2>&1', $tom_mem_arr, $status);
  
  $top_mem = implode(' KiB <br/>', array_slice( $tom_mem_arr, - 5, 5) );
  $top_mem = "<pre><b>COMMAND\t\tResident memory</b><br/>" . $top_mem . " KiB</pre>";
  
  
$data1 .= "<tr><td>Server Load Averages </td><td>$avgs[1], $avgs[2], $avgs[3]</td>\n"; 
$data1 .= "<tr><td>Server Uptime        </td><td>$uptime                     </td></tr>"; 
  $data1 .= "<tr><td>Disk free        </td><td>$disk_free/$disk_space = $disk_free_precent%         </td></tr>"; 
  $data1 .= "<tr><td>RAM free        </td><td>$free_mem MiB/$total_mem MiB = $free_mem_percent%         </td></tr>"; 
  $data1 .= "<tr><td>Top RAM user    </td><td>$top_mem         </td></tr>"; 
$data1 .= "</table>"; 
echo $data1;  

?>