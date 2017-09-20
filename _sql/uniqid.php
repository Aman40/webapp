<?php
$count=0;
while($count<100) {
$unique = uniqid("I");
echo "(".$unique.", , , , , ),\n";
$count++;
usleep(100000);
}

?>
