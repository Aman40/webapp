<?php
$count=0;
while($count<20) {
$unique = uniqid("N");
echo "('".$unique."', '', '', '', , , , ),\n";
$count++;
usleep(100000);
}

?>
