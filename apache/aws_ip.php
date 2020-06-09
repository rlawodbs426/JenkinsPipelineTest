<?php
function print_aa($p_ary)
{ // array print
        echo '<font color="orangered">';
        if (is_array($p_ary)) {
                echo '<xmp>',print_r($p_ary).'</xmp>';
        } else {
                echo $p_ary;
        }
        echo '</font>';
}

function &value_add($_value,$i,$j) {
        foreach ($_value as $key => $value){
                if($j==0) $result[$i][$j]=$value;
                if($j==1) $result[$i][$j]=$value;
                if($j==2) $result[$i][$j]=$value;
        $j++;
        }
        return $result;
}

function &filter_add($chois_region, $chois_service, $result_region, $result_service, $result_ip_prefix) {
        $filter_cnt=0;
        if ( $chois_region=="ALL" && $chois_service!="ALL") {
                for ($f=0;$f<sizeof($result_ip_prefix);$f++) {
                        if ($result_service[$f] == $chois_service) {
                                $filter[$filter_cnt][1]=$result_region[$f];
                                $filter[$filter_cnt][2]=$result_service[$f];
                                $filter[$filter_cnt][0]=$result_ip_prefix[$f];
                                $filter_cnt++;
                        }
                }
        } elseif ( $chois_region!="ALL" && $chois_service=="ALL" ) {
                for ($f=0;$f<sizeof($result_ip_prefix);$f++) {
                        if ($result_region[$f] == $chois_region) {
                                $filter[$filter_cnt][1]=$result_region[$f];
                                $filter[$filter_cnt][2]=$result_service[$f];
                                $filter[$filter_cnt][0]=$result_ip_prefix[$f];
                                $filter_cnt++;
                        }
                }
        } else {
                for ($f=0;$f<sizeof($result_ip_prefix);$f++) {
                        if ( $chois_region==$result_region[$f] && $chois_service==$result_service[$f] ) {
                                $filter[$filter_cnt][1]=$result_region[$f];
                                $filter[$filter_cnt][2]=$result_service[$f];
                                $filter[$filter_cnt][0]=$result_ip_prefix[$f];
                                $filter_cnt++;
                        }
                }
        }
        return $filter;
}

#print_aa($_REQUEST);
if (isset($_REQUEST['submit'])) {
        $chois_region=$_REQUEST['region'];
        $chois_service=$_REQUEST['service'];
}else{
        $chois_region="ALL";
        $chois_service="ALL";
}
$json_string = file_get_contents ("https://ip-ranges.amazonaws.com/ip-ranges.json");

// parse to php array
$data_array = json_decode($json_string, true);

?>
<!DOCTYPE html>
<html lang=en>
<head>
        <META charset="UTF-8" />
        <META http-equiv=X-UA-Compatible content="IE=Edge" />
        <META http-equiv="Expires" content="-1">
        <META http-equiv="Pragma" content="no-cache">
        <META http-equiv="Cache-Control" content="No-Cache">
        <META name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
        <style type="text/css">

        ::selection { background-color: #E13300; color: white; }
        ::-moz-selection { background-color: #E13300; color: white; }

tr
        {mso-height-source:auto;
        mso-ruby-visibility:none;}
col
        {mso-width-source:auto;
        mso-ruby-visibility:none;}
br
        {mso-data-placement:same-cell;}
ruby
        {ruby-align:left;}
.style0
        {mso-number-format:General;
        text-align:general;
        vertical-align:middle;
        white-space:nowrap;
        mso-rotate:0;
        mso-background-source:auto;
        mso-pattern:auto;
        color:black;
        font-size:11.0pt;
        font-weight:400;
        font-style:normal;
        text-decoration:none;
        font-family:Arial, monospace;
        mso-font-charset:129;
        border:none;
        mso-protection:locked visible;
        mso-style-name:Table Normal;
        mso-style-id:0;}
td
        {mso-style-parent:style0;
        padding-top:3px;
        padding-right:10px;
        padding-left:10px;
        padding-bottom:3px;
        mso-ignore:padding;
        color:black;
        font-size:11.0pt;
        font-weight:400;
        font-style:normal;
        text-decoration:none;
        font-family:Arial, monospace;
        mso-font-charset:129;
        mso-number-format:General;
        text-align:general;
        vertical-align:middle;
        border:none;
        mso-background-source:auto;
        mso-pattern:auto;
        mso-protection:locked visible;
        white-space:nowrap;
        mso-rotate:0;}
.xl1
        {mso-style-parent:style0;
        color:white;
        font-weight:700;
        font-family:Arial, sans-serif;
        mso-font-charset:0;
        text-align:center;
        border:.5pt solid white;
        background:#282828;
        mso-pattern:black none;
        vertical-align:middle;
        white-space:normal;}
.xl2
        {mso-style-parent:style0;
        color:white;
        font-weight:200;
        font-family:Arial, sans-serif;
        mso-font-charset:0;
        text-align:center;
        border:.5pt solid white;
        background:#595959;
        mso-pattern:black none;
        vertical-align:middle;
        white-space:normal;}
.xl3
        {mso-style-parent:style0;
        font-family:Arial, sans-serif;
        color:black;
        mso-font-charset:0;
        text-align:center;
        border:.5pt solid windowtext;
        background:white;
        mso-pattern:black none;
        white-space:normal;}

        </style>
</head>
<title>Amazon IP Ranges Parser</title>
<body>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<table style='border-style:solid;border-collapse:collapse'>
<tr>
<th class=xl1>Region</th>
<th class=xl1>Service</th>
<th class=xl1>IP-Prefix</th>
</tr>
<?php

$i=0;
$j=0;
$result=array();
$filter=array();
foreach ($data_array['prefixes'] as $ip4_key => $ip4_value) {
#       print_aa($ip4_value);
        $result=value_add($ip4_value,$i,$j);
        $result_region[$i] = $result[$i][1];
        $result_service[$i] = $result[$i][2];
        $result_ip_prefix[$i] = $result[$i][0];
        $i++;
}
$cnt=sizeof($result_ip_prefix);

$unique_region=array_unique($result_region);
$unique_service=array_unique($result_service);
sort($unique_region);
sort($unique_service);
echo "<tr>";
echo "<td class=xl2>";
echo "<select name='region'>";
echo "<option value='ALL'>ALL</option>";
for($i=0;$i<sizeof($unique_region);$i++) {
        echo "<option value=".$unique_region[$i].">".$unique_region[$i]."</option>";
}
echo "</select>";
echo  "</td>";
echo "<td class=xl2>";
echo "<select name='service'>";
echo "<option value='ALL'>ALL</option>";
for($i=0;$i<sizeof($unique_service);$i++) {
        echo "<option value=".$unique_service[$i].">".$unique_service[$i]."</option>";
}
echo "</select>";
echo  "</td>";
echo "<td class=xl2><input type='submit'  name='submit' value='submit'></td>";
echo "</tr>";
if ($chois_region=="ALL" && $chois_service=="ALL") {
        for ($l=0; $l<$cnt;$l++) {
                echo "<tr>";
                echo "<td class=xl3>".$result_region[$l]."</td>";
                echo "<td class=xl3>".$result_service[$l]."</td>";
                echo "<td class=xl3>".$result_ip_prefix[$l]."</td>";
                echo "</tr>";
        }
}else{
        $filter=filter_add($chois_region, $chois_service, $result_region, $result_service, $result_ip_prefix);
        $filter_cnt=sizeof($filter);
        for ($l=0; $l<$filter_cnt;$l++) {
                echo "<tr>";
                echo "<td class=xl3>".$filter[$l][1]."</td>";
                echo "<td class=xl3>".$filter[$l][2]."</td>";
                echo "<td class=xl3>".$filter[$l][0]."</td>";
                echo "</tr>";
        }
/*
        $filter_cnt=sizeof($filter);
        for ($l=0; $l<$filter_cnt;$l++) {
                echo "<tr>";
                if ($l==0) {
                                echo "<td class=xl3>".$chois_region."</td>";
                                echo "<td class=xl3>".$chois_service."</td>";
                                echo "<td class=xl3>".$filter[$l][0]."</td>";
                } else {
                        if ( $chois_region=="ALL" && $chois_service!="ALL") {
                                echo "<td class=xl3>".$filter[$l][1]."</td>";
                                echo "<td class=xl3>-</td>";
                                echo "<td class=xl3>".$filter[$l][0]."</td>";
                        } elseif ( $chois_region!="ALL" && $chois_service=="ALL" ) {
                                echo "<td class=xl3>-</td>";
                                echo "<td class=xl3>".$filter[$l][2]."</td>";
                                echo "<td class=xl3>".$filter[$l][0]."</td>";
                        } else {
                                echo "<td class=xl3>-</td>";
                                echo "<td class=xl3>-</td>";
                                echo "<td class=xl3>".$filter[$l][0]."</td>";
                        }
                }
                echo "</tr>";
        }
*/
}
?>
</table>
</form>
</body>
</html>
