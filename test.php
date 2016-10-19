<?php

$readHandle=fopen("test.log","a+");
$writeHandle=fopen("test2.log","w");
$count=0;
while(!feof($readHandle))
{
	
	$textBuffer=fgets($readHandle);
	if($textBuffer=="")
		echo "empty line";
	else
	{
		$count++;
		echo "not $count <br>";
	//fwrite($writeHandle,$textBuffer);
	}
}

//fwrite($readHandle,"EOF");
fclose($readHandle);
fclose($writeHandle);

	
?>