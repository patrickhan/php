<?php
$xmlDoc=new DOMDocument();
$xmlDoc->load("links.xml");

$x=$xmlDoc->getElementsByTagName('link');

//get the q parameter from URL
$q=$_GET["q"];

//lookup all links from the xml file if length of q>0
if (strlen($q)>0)
{
$hint="";
for($i=0; $i<($x->length); $i++)
  {
  $y=$x->item($i)->getElementsByTagName('title');
  $z=$x->item($i)->getElementsByTagName('url');
  if ($y->item(0)->nodeType==1)
    {
    //find a link matching the search text
     if (strtolower(substr(preg_replace("/[^A-Za-z0-9]/", "", $y->item(0)->childNodes->item(0)->nodeValue),0,strlen(preg_replace("/[^A-Za-z0-9]/", "", $q)))) === strtolower(preg_replace("/[^A-Za-z0-9]/", "", $q)))
      {
      if ($hint=="")
        {
        $hint="<a href='" .
        $z->item(0)->childNodes->item(0)->nodeValue .
        "'>" .
        $y->item(0)->childNodes->item(0)->nodeValue . "</a>";
        }
      else
        {
        $hint=$hint . "<br /><br /><a href='" .
        $z->item(0)->childNodes->item(0)->nodeValue .
        "'>" .
        $y->item(0)->childNodes->item(0)->nodeValue . "</a>";
        }
      }
    }
  }
}

// Set output to "no results" if no hint were found
// or to the correct values
if ($hint=="")
  {
  $response="no results";
  }
else
  {
  $response=$hint;
  }

//output the response
echo $response;
?> 