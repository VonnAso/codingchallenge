<?php


#example usage
include('searchEngine.php');




$client = new searchEngine();
// $client->setCertificateLocation(__DIR__ ."\cacert.pem"); #required for google security
$client->setEngine("google.ae");
$results = $client->search("cafm","software");

echo 'Crawled Sites:<br>';
$SERP =  $results; #FINAL RESULTS

#Iterate and Print
for($y = 0; $y< sizeof($SERP); $y++){
	echo 'keyword 1:' . $SERP[$y]['keyword1'].'<br>';
	echo 'keyword 2:' . $SERP[$y]['keyword2'].'<br>';
	echo 'Ranking:' . $SERP[$y]['rank'].'<br>';
	echo 'link:' . $SERP[$y]['link'].'<br>';
	echo 'title:' . $SERP[$y]['title'].'<br>';
	echo 'description:' . $SERP[$y]['description'].'<br>';
	echo 'Promoted:' . $SERP[$y]['promoted'].'<br>';
	echo '<br>';
}


?>