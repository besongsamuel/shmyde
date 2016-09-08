<!DOCTYPE html>
<html lang="en">
    
    
 <link href="<?php echo ASSETS_PATH; ?>css/design_button_style.css" rel="stylesheet">
 <link href="<?php echo ASSETS_PATH; ?>frameworks/ninja-slider/ninja-slider.css" rel="stylesheet">
 <script src="<?php echo ASSETS_PATH; ?>frameworks/ninja-slider/ninja-slider.js"></script>
 <script src="<?php echo ASSETS_PATH; ?>js/button_script.js"></script>

  <body>

	 <!--start slider-->
<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
    <li data-target="#carousel-example-generic" data-slide-to="3"></li>
    <li data-target="#carousel-example-generic" data-slide-to="4"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
    <div class="item active">
      <img src="<?php echo ASSETS_PATH; ?>frameworks/ninja-slider/img/1.png" alt="...">
      <div class="carousel-caption">
        <a href='#'><h3>Design Cool Gouns</h3></a>
	<h2>In minutes, not hours</h2>
      </div>
    </div>
    <div class="item">
      <img src="<?php echo ASSETS_PATH; ?>frameworks/ninja-slider/img/1.jpg" alt="...">
      <div class="carousel-caption">
        ...
      </div>
    </div>
    <div class="item">
      <img src="<?php echo ASSETS_PATH; ?>frameworks/ninja-slider/img/3.jpg" alt="...">
      <div class="carousel-caption">
        ...
      </div>
    </div>
     <div class="item">
      <img src="<?php echo ASSETS_PATH; ?>frameworks/ninja-slider/img/5.jpg" alt="...">
      <div class="carousel-caption">
        ...
      </div>
    </div>
     <div class="item">
      <img src="<?php echo ASSETS_PATH; ?>frameworks/ninja-slider/img/6.jpg" alt="...">
      <div class="carousel-caption">
        ...
      </div>
    </div>
 
    ...
  </div>

  <!-- Controls -->
  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>

    <!--end slider-->	
	
	<div class='start-customizing'>
  		
		<a href="#">
  <div class="button-fill grey">
    <div class="button-text"><?php echo $this->lang->line('shmyde_men_button_text'); ?></div>
    <div class="button-inside">
      <div class="inside-text"><?php echo $this->lang->line('shmyde_men_button_text'); ?></div>
    </div>
  </div></a>
  
  <a href="#">
  <div class="button-fill orange">
    <div class="button-text"><?php echo $this->lang->line('shmyde_women_button_text'); ?></div>
    <div class="button-inside">
      <div class="inside-text"><?php echo $this->lang->line('shmyde_women_button_text'); ?></div>
    </div>
  </div></a>

	</div>

	<!-- first band -->
	<div class='middle-band'>
		<!-- <p><?php echo $this->lang->line('shmyde_247_customer_service'); ?></P>
		<p><b>info @ shmyde.com</b></P> -->
	</div>	
	<!-- end first band -->	

	
	<!-- Statistics Section -->
	<div class='stat-section'>


		<div class='container statistics'>
				<div class='row'>
				
					<div class='col-md-2 col-sm-3'>
						<p><?php echo $this->lang->line('shmyde_tailors'); ?></p>
						<img src='<?php echo ASSETS_PATH; ?>/images/tailors-stat.png'/>
						<p><b>43</b><p>
					</div>
					
					<div class='col-md-2 col-sm-3'>
						<p><?php echo $this->lang->line('shmyde_testimonials'); ?></p>
						<img src='<?php echo ASSETS_PATH; ?>/images/testimonials.png'/>
						<p><b>175</b><p>
					</div>
					
					
					<div class='col-md-2 col-sm-3'>
						<p><?php echo $this->lang->line('shmyde_deliveries'); ?></p>
						<img src='<?php echo ASSETS_PATH; ?>/images/deliveries.png'/>
						<p><b>358</b><p>
					</div>
					
					
					<div class='col-md-6 col-sm-3'></div>
					
				</div>
				
				<div class='row'>
				
					<div class='col-md-2 col-sm-3'>
						<p><?php echo $this->lang->line('shmyde_tweeter'); ?></p>
						<img src='<?php echo ASSETS_PATH; ?>/images/tweeter.png'/>
						<p><b>43</b><p>
					</div>
					
					<div class='col-md-2 col-sm-3'>
						<p><?php echo $this->lang->line('shmyde_facebook'); ?></p>
						<img src='<?php echo ASSETS_PATH; ?>/images/facebook.png'/>
						<p><b>175</b><p>
					</div>
					
					
					<div class='col-md-2 col-sm-3'>
						
					</div>
					
					
					<div class='col-md-6 col-sm-3'></div>
					
				</div>
				
			</div>
	</div>
	<!-- end Statistics Section -->


	<!-- second band -->
	<div class='middle-band'>
		<div class='container'>
			<div class='row'>
				<div class='col-md-12 col-sm-12 col-xs-12'>
					<P><?php echo $this->lang->line('shmyde_teaser_line_01'); ?></P>
					<center><p><b><?php echo $this->lang->line('shmyde_teaser_line_02'); ?></b></p></center>
				</div>
			</div>
		</div>
	</div>	
	<!-- end second band -->	

	<!-- models section -->
	<div class='models'>
		<div class='bg-video'>
				<video muted loop autoplay>
				  <source src="<?php echo ASSETS_PATH; ?>/videos/Model Tailor WorkShop.mp4" type="video/mp4">
				  <source src="<?php echo ASSETS_PATH; ?>/videos/Model Tailor WorkShop.ogg" type="video/ogg">
				  <?php echo $this->lang->line('shmyde_video_tag_not_supported'); ?>
				</video>	
		</div>

		<div class='offers'>

				<div class='grid'> 
					<figure class="effect-sadie">
						<img src="<?php echo ASSETS_PATH; ?>/images/models/men-suits.png" alt="img02"/>
						<figcaption>
							<h2><?php echo $this->lang->line('shmyde_men_button_test'); ?><span><?php echo $this->lang->line('shmyde_suits'); ?></span></h2>
							<a href="#"><?php echo $this->lang->line('shmyde_view_more'); ?></a>
						</figcaption>			
					</figure> 
					<figure class="effect-sadie">
						<img src="<?php echo ASSETS_PATH; ?>/images/models/women-suits.png" alt="img02"/>
						<figcaption>
							<h2><?php echo $this->lang->line('shmyde_women_button_test'); ?> <span><?php echo $this->lang->line('shmyde_suits'); ?></span></h2>
							<a href="#"><?php echo $this->lang->line('shmyde_view_more'); ?></a>
						</figcaption>			
					</figure> 
					<figure class="effect-sadie">
						<img src="<?php echo ASSETS_PATH; ?>/images/models/jeans.png" alt="img02"/>
						<figcaption>
							<h2><span><?php echo $this->lang->line('shmyde_jeans'); ?></span></h2>
							<a href="#"><?php echo $this->lang->line('shmyde_view_more'); ?></a>
						</figcaption>			
					</figure> 
					<figure class="effect-sadie">
						<img src="<?php echo ASSETS_PATH; ?>/images/models/t-shirts.png" alt="img02"/>
						<figcaption>
							<h2>T-<span><?php echo $this->lang->line('shmyde_shirts'); ?></span></h2>
							<a href="#"><?php echo $this->lang->line('shmyde_view_more'); ?></a>
						</figcaption>			
					</figure>
				</div>

				
				<div class='grid' style="z-index:30">
					<figure class="effect-honey">
						<img src="<?php echo ASSETS_PATH; ?>/images/models/popular.png" alt="img05"/>
						<figcaption>
							<h2>POPULAR<span>MODELS</span></h2>
							<a href="#"><?php echo $this->lang->line('shmyde_view_more'); ?></a>
						</figcaption>			
					</figure>
				</div>

		</div>

	</div>
	<!-- end models section --->	

<script>
	 $(".button-fill").hover(function () {
    $(this).children(".button-inside").addClass('full');
}, function() {
  $(this).children(".button-inside").removeClass('full');
});
</script>	
