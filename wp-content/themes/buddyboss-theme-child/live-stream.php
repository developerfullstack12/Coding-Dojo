
<?php
    /*
    template name:live-stream
    
    */
    
    get_header(); 
?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

    <div class="container rounded bg-white mt-5 mb-5">
         <div class="d-flex justify-content-between align-items-center">
        <h4 class="text-heading">Livestream</h4>
    </div>
    <div class="row">
        <div class="col-md-12 c_input">
                <div class="row mt-2">
                    <div class="col-md-12">
                        <iframe width="560" height="250" src="https://www.youtube.com/embed/z52vueRUj7Q" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div><br/><br/>
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="sub-heading">Upcomeing Streaming Session: 23 March 2021 8pm - 9pm</h6>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <p class="sub-heading1">Community Guidline</p>
                </div>
                <ol class="c_guidline">
                  <li>Coffee</li>
                  <li>Tea</li>
                  <li>Milk</li>
                </ol>  
            </div>
        </div>
    </div>


<?php get_footer(); ?>