NOTE: the CA certificate is needed to successfully CURL to google.
as a default it is already declared as 
$this->setCertificateLocation(__DIR__ ."\cacert.pem");

incase it needs a different certificate use the function setCertificateLocation
e.g. $client->setCertificateLocation("C:\wamp64\www\WebCrawler\CAExtract\cacert.pem");



#########################################################################################
example usage of searchCrawler.php


<?php
include('searchEngine.php');


$client = new searchEngine();
$client->setCertificateLocation("C:\wamp64\www\WebCrawler\cacert.pem"); #required for google security
$client->setEngine("google.ae");
$results = $client->search("cafm","software");

$SERP =  $results; #FINAL RESULTS

?>

##############################################################################
please check example_usage.php for examples



###############################################################
composer install

{
  "name":  "vonnaso/crawling-search-engine",
  "repositories": [
    {
      "type": "package",
      "url": "https://github.com/VonnAso/575t-dmcc-and-fynda-coding-challenge.git",
      "package": {
        "name": "vonnaso/575t-dmcc-and-fynda-coding-challenge",
        "version": "0.0.1",
        "type": "crawlingSearchEngine",
        "source": {
          "url": "https://github.com/VonnAso/575t-dmcc-and-fynda-coding-challenge.git",
          "type": "git",
          "reference": "master-to-main"
        }
      }
    }
  ],
  "require": {
    "vonnaso/575t-dmcc-and-fynda-coding-challenge": "0.0.1"
  }
  
}