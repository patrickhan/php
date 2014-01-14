<?php echo $header; ?>
<div id="content" class="flex-item">
      <section id="content-part">
        <div id="content-part-col-1">
          <div class="content-col-title">New Arrivals</div>
          <div id="content-col1-thumb"> <a href="#"> <img title="winter fashion"
                alt="winter fashion"
                src="catalog/view/theme/grey/image/Layer83.png">
            </a> <a href="#"> <img title="summer fashion" alt="summer fashion"
                src="catalog/view/theme/grey/image/Layer84.png">
            </a> </div>
          <img title="adv" alt="adv" src="catalog/view/theme/grey/image/Layer86.png">
        </div>
        <div id="content-part-col-2">
	  <div id="products-fashoin">
		<div class="content-col-title">Fashion products <a class="view-all-link"
		    href="#">View
		    all...</a> </div>
		<div class="products-thumb">
		  <div class="product-achor"><a href="#">
			<img src="catalog/view/theme/grey/image/Layer90.png">
		    </a>
		    <div class="price-lable">price:<span class="price-lable-number">$100.99</span></div>
		    <div class="product-name">Product name</div>
		    <div class="add-cart-button">Add To Cart</div>
		  </div>
		  <div class="product-achor">2</div>
		  <div class="product-achor">3</div>
		</div>
	  </div>
	  <div id="products-feature">
		<div class="content-col-title"><?php echo $text_featured; ?><a class="view-all-link"
		    href="#">View
		    all...</a> </div>
		<div class="products-thumb">
		  
		  <?php if ($config_prods){
		      for ($i = 0; $i < sizeof($featured); $i = $i + 1) {?>
			      <?php for ($j = $i; $j < ($i + 1); $j++) { ?>
				      <div class="product-achor">
				      <?php if (isset($featured[$j])) { ?>
					      <a href="<?php echo $featured[$j]['href']; ?>" style="background-image: url('<?php echo $featured[$j]['thumb']; ?>')">
					      </a>
					      <div class="product-name"><?php echo $featured[$j]['name']; ?></div>
					      <div class="price-lable">price:<span class="price-lable-number"><?php echo $featured[$j]['price']; ?></span></div>
					      <div class="add-cart-button">Add To Cart</div>
				      <?php } ?>
				      </div>
			      <?php } ?>
		      <?php }
		      } ?> 
		  <!--div class="product-achor">2</div>
		  <div class="product-achor">3</div>
		  <div class="product-achor">4</div>
		  <div class="product-achor">5</div-->
      
		</div>
	  </div>
          <div id="welcomes"><span style="font-weight: bold;">Welcome to our
              company</span><br>
            <div style="font-size: 12px;" id="welcome-content">Lorem Ipsum is
              simply dummy text of the printing and typesetting industry. Lorem
              Ipsum has been the industry's standard dummy text ever since the
              1500s, when an unknown printer took a galley of type and scrambled
              it to make a type specimen book. It has survived not only five
              centuries, but also the leap into electronic typesetting.</div>
          </div>
        </div>
        <div id="content-part-col-3">
          <div class="content-col-title">Best sellers</div>
          <div id="content-col1-thumb"> <a href="#"> <img title="winter fashion"
                alt="winter fashion"
                src="catalog/view/theme/grey/image/Layer80.png">
            </a> <a href="#"> <img title="summer fashion" alt="summer fashion"
                src="catalog/view/theme/grey/image/Layer81.png">
            </a> </div>
          <div class="content-col-title">What's new</div>
          <a href="#"> <img style="margin-top: 5px; margin-bottom: 10px;" title="summer fashion"
              alt="summer fashion"
              src="catalog/view/theme/grey/image/Layer82.png">
          </a>
          <div class="content-col-title">Testmonial</div>
          <p style="text-align: left;"> "Lorem Ipsum is simply dummy text of the
            printing and typesetting industry. Lorem Ipsum has been the
            industry's standard dummy text ever since the 1500s” by David
            Conrad. 2 days ago </p>
          <hr>
          <p style="text-align: left;"> “Lorem Ipsum is simply dummy text of the
            printing and typesetting industry. Lorem Ipsum has been the
            industry's standard dummy text ever since the 1500s” by David
            Conrad. 2 days ago </p>
        </div>
      </section>
    </div>
<?php echo $footer; ?> 
