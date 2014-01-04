<?php
header("content-type: application/xml");
$url = 'http://du21.dns77.com/~dev04/dell-arte-site'.'/catalog/view/theme/dellarte/img';

echo <<<EOF
<?xml version="1.0" encoding="utf-8" ?>
<cu3er>
	<settings>
		<preloader>
		</preloader>
		<auto_play>
			<defaults symbol="circular" time="8" />
			<tweenIn x="900" y="430" width="35" height="35" tint="0xFFFFFF" />
		</auto_play>
		<description>
			<defaults
				round_corners="0, 0, 0, 0"
				
				heading_font="Century Gothic"
				heading_text_size="22"
				heading_text_color="0xFFFFFF"          
				heading_text_margin="10, 0, 0,10"  
				
				paragraph_font="Century Gothic"
				paragraph_text_size="13"
				paragraph_text_color="0xcbcbcb"
				paragraph_text_margin="10, 0, 0, 10"       
			/>
		</description>
	</settings>    

	<slides>
        <slide>
            <url>$url/slide_suave.jpg</url>
			<link target="_self">index.php</link>        
			<description>
				<link target="_self">index.php</link>    
				<heading>Suave</heading>
				<paragraph>Description goes here!</paragraph>
			</description> 
        </slide>
        <transition num="3" slicing="vertical" direction="down"/>
        <slide>
       		<url>$url/slide_flou.jpg</url>
			<link target="_self">index.php</link>        
			<description>
				<link target="_self">index.php</link>    
				<heading>Flou</heading>
				<paragraph>Description goes here!</paragraph>
			</description> 
        </slide>
        <transition num="4" direction="right" shader="flat" />
		<slide>
            <url>$url/slide_spring.jpg</url>
			<link target="_self">index.php</link>        
			<description>
				<link target="_self">index.php</link>    
				<heading>Spring</heading>
				<paragraph>Description goes here!</paragraph>
			</description> 
        </slide>
		<slide>
       		<url>$url/slide_aqua.jpg</url>
			<link target="_self">index.php</link>        
			<description>
				<link target="_self">index.php</link>    
				<heading>Aqua</heading>
				<paragraph>Description goes here!</paragraph>
			</description> 
        </slide>
		<transition num="6" slicing="vertical" direction="up" shader="flat" delay="0.05" z_multiplier="4" />
        <slide>
       		<url>$url/slide_martini.jpg</url>
			<link target="_self">index.php</link>        
			<description>
				<link target="_self">index.php</link>    
				<heading>Martini</heading>
				<paragraph>Description goes here!</paragraph>
			</description> 
        </slide>
	</slides>
</cu3er>

EOF;
?>
