<?php


class searchEngine{

public $url_name;
private $certificate;# = "C:\wamp64\www\WebCrawler\CAExtract\cacert.pem";
public $crawled_links = array();
public $engine;
public $linksArray = array();
private $ranking =0;
public $keyword1;
public $keyword2;
private $searchEnded = 0;

public function __construct(){
	  $this->setCertificateLocation(__DIR__ ."\cacert.pem");
  }

  public function addResults($urlName,$description, $title,$promoted){
					
	if(sizeof($this->linksArray) <= 50){
		$this->linksArray[] = array('link'=>$urlName,
										'description'=>$description,
										'title'=>$title,
										'promoted'=>$promoted,
										'rank'=> sizeof($this->linksArray),
										'keyword1'=> $this->keyword1,
										'keyword2'=> $this->keyword2
										);		
	}else
	{
		$this->searchEnded = 1;
	}
	}
	
	public function getAdsResults($dom){
		$xpath = new DOMXpath($dom);
		$adsUrlDivs = $xpath->query('//div[@data-text-ad="1"]');
		
		for($i = 0;$i<sizeof($adsUrlDivs);$i++){
			$url = $xpath->query('//div[@data-text-ad="1"]')[$i];
			
			#get advertisement link
			$link = $url->getElementsByTagName('a')[0];
			$link = $link->getAttribute('href');
			
			#get adverstisement title
			$span = $url->getElementsByTagName('span')[0];
			$title = $span->nodeValue;
			
			#get advertisement description
			$description = $url->firstChild->firstChild->nextSibling;
			$description = $description->getElementsByTagName('span')->item(0)->nodeValue;
			
			$this->addResults($link,$description,$title,1);
		}
		
	}
	
    public function getMainResultURL($dom,$nthElement){
		
		$retVal['link'] = $this->getURL($dom,$nthElement);
		$retVal['title'] = $this->getTitleValue($dom,$nthElement);
		$retVal['description'] = $this->getDescriptionValue($dom,$nthElement);
		$retVal['promoted'] = 0;

		return $retVal;
	}
	public function getTitleValue($dom,$nthElement){
		$xpath = new DOMXpath($dom);
		$span = $xpath->query('//div[@data-sokoban-container]/div[@data-header-feature="0"]')[$nthElement];
		$title = $span->getElementsByTagName('span');
		return $title->item(0)->nodeValue;
	}
	
	public function getDescriptionValue($dom,$nthElement){
		$xpath = new DOMXpath($dom);
		$span = $xpath->query('//div[@data-sokoban-container]/div[@data-content-feature="1"]')[$nthElement];
		return $span->nodeValue;
	}
	
	
	public function getURL($dom,$nthElement){
		$xpath = new DOMXpath($dom);
		$a = $xpath->query('//div[@data-sokoban-container]/div[@data-header-feature="0"]/div/a')[$nthElement];
		return $a->getAttribute('href');
	}
	
	public function getLinksArray(){
		return $this->linksArray;
	}

  
  public function setCertificateLocation($certLoc){
	  $this->certificate = $certLoc;
  }
  
  
  public function setEngine($engine){
	  $this->engine = $engine;
  }
  
  public function setURL($host,$keyword1,$keyword2,$pages){
	  $this->url_name = "https://".$host."/search?q=".$keyword1."+".$keyword2."&start==".$pages;
  }
  

  public function search($keyword1,$keyword2){
	   if(empty($this->engine)){
		 $this->engine = 'google.com';
		}
		
		$this->keyword1 = $keyword1;
	  $this->keyword2 = $keyword2;
	
		$this->setURL($this->engine,$keyword1,$keyword2,100);
		
	  
	  $websiteList = $this->scrapeSERP($this->url_name,5);
	  
	  return $websiteList;
  }
  
  public function scrapeSERP($engineURL,$numberOfPage){
	 
	 $htmlContents =  $this->getContents($engineURL,$this->certificate);
	 $dom= new DomDocument();
     @$dom->loadHTML($htmlContents);
	  
	#get main search list
	$xpath = new DOMXpath($dom);
	$a = $xpath->query('//div[@data-sokoban-container]');
	
	$this->getAdsResults($dom); //get ads website on the top
	
	
	//gets main results
	for($i =0; $i < sizeof($a); $i++){
		$mainResults = $this->getMainResultURL($dom,$i);
		$this->addResults($mainResults['link'],$mainResults['description'],$mainResults['title'],$mainResults['promoted']);
	}
	
	$numberOfPage--;
	if($this->searchEnded === 1){
		return $this->getLinksArray();
	}
	if($numberOfPage >0)
	{
		//get next page link
		$pnnext = $dom->getElementById('pnnext');
		$pnnextURL = $pnnext->getAttribute('href');
		$nextURL = "https://".$this->engine.$pnnextURL;

		//recursive function start
		$this->scrapeSERP($nextURL,$numberOfPage);
		
	}

	
	return $this->getLinksArray();
  }
  
  public function getContents($websiteURL,$certificate){
	$options = array(
			CURLOPT_URL			   => $websiteURL,
			CURLOPT_USERAGENT	   => $_SERVER['HTTP_USER_AGENT'],
			CURLOPT_RETURNTRANSFER => true,   
			CURLOPT_HEADER		   => false,
			CURLOPT_FOLLOWLOCATION => true,    
			CURLOPT_CAPATH	       => $certificate,
			CURLOPT_CAINFO 		   => $certificate
		); 
	
	$ch = curl_init();
	curl_setopt_array($ch, $options);
	$result = curl_exec($ch);
	
  if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch) . "\n";
	}
	
curl_close($ch);
	return $result;

  }
  
}

  ?>
