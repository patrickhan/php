<div class="box">
	<div class="top"><img src="catalog/view/theme/default/image/search.png" alt="" /><?php echo $heading_title; ?></div>
	<div class="middle" style="text-align: left;"><input type='text' name='searchp' id='searchp' onkeyup="showResult(this.value)"/>
<script>
function showResult(str)
{
if (str.length==0)
  {
  document.getElementById("livesearch").innerHTML="";
  document.getElementById("livesearch").style.border="0px";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("livesearch").innerHTML=xmlhttp.responseText;
    document.getElementById("livesearch").style.border="1px solid #A5ACB2";
    }
  }
xmlhttp.open("GET","search/livesearch.php?q="+str,true);
xmlhttp.send();
}
</script>
<div id="livesearch" style="z-index:500;position:relative;padding: 10px;"></div>
	</div>
	<div class="bottom">&nbsp;</div>
</div>