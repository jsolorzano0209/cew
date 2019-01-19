<?php

function some_random_code(){

?>


<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/masterslider.css">
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/ms-staff-style.css">
    
    <!-- google font Lato -->
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400' rel='stylesheet' type='text/css'>


    <!-- template -->
    <div class="ms-staff-carousel ms-round">
      <!-- masterslider -->
      <div class="master-slider" id="masterslider">
<?php 

$args = array( 'post_type' => 'post', 'cat' => 214 );
    $loop = new WP_Query( $args );
    
    while ( $loop->have_posts() ) : $loop->the_post(); ?>
    <div class="ms-slide">
              <img src="<?php echo wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()));  ?>" alt="lorem ipsum dolor sit"/>  
              <div class="ms-info">
                <h3><?php echo the_title(); ?></h3>

                <p><?php echo the_content(); ?></p>
                
              </div>     
          </div>

<?php endwhile;?>
         
        
       
      </div>
      <!-- end of masterslider -->
      <div class="ms-staff-info" id="staff-info"> </div>
    </div>
     
    
    
    <!-- Master Slider -->
    <script src="<?php echo get_template_directory_uri(); ?>/js/masterslider.min.js"></script>
    
    <!-- Template js -->
    <script src="<?php echo get_template_directory_uri(); ?>/js/masterslider.staff.carousel.js"></script>

  <!-- end of syntaxHylight wrapper -->
 
  
  <script type="text/javascript"> 

    var slider = new MasterSlider();
    slider.setup('masterslider' , {
      loop:true,
      width:240,
      height:240,
      speed:20,
      view:'stf',
      preload:0,
      space:0,
      space:35,
      autoplay:false,
      viewOptions:{centerSpace:1.6}
    });
    slider.control('arrows');
    slider.control('slideinfo',{insertTo:'#staff-info'});

    $('#myTab a').click(function (e) {
      e.preventDefault()
      $(this).tab('show')
    });

    SyntaxHighlighter.all();
    
  </script>


<?php
}// End some_random_code()

?>