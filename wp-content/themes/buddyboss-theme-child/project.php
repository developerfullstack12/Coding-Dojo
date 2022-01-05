
<?php
    /*
    template name:project_view
    
    */
    
    get_header(); 
?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

    <div class="container rounded bg-white mt-5 mb-5">
        <div class="row">
             <div class="col-md-8">
                <div class="d-flex justify-content-between align-items-center">
                 <h4 class="text-heading">Snake Game(April 2021)</h4>
            </div>
            </div>
            <div class="col-md-4">
                <div class="btn_pn"><a href="#" class="btn_preview">< Previous</a><a href="#" class="btn_preview">Next ></a></div>
         </div>
    </div>
    <div class="step-box">
        <ul class="step-list">
            <li class="active"><div class="step_height"><span><i class="fas fa-check-circle"></i></span></div><p class="step_p">Part 1</p></li>
            <li class="active"><div class="step_height"><span><i class="fas fa-check-circle"></i></span></div><p class="step_p">Part 2</p></li>
            <li><div class="step_height"><span></span></div><p class="step_p">Part 3</p></li>
            <li><div class="step_height"><span></span></div><p class="step_p">Part 4</p></li>
            <li><div class="step_height"><span></span></div><p class="step_p">Part 5</p></li>
            <li><div class="step_height"><span></span></div><p class="step_p">Part 6</p></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12 c_input">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="sub-heading">Part 5 : Slowing down the game</h6>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <p class="sub-heading1">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <video controls width="100%">
                          <source src="https://lmstechs.in/Coding-Dojo/wp-content/uploads/2021/11/mov_bbb-1.mp4" type="video/mp4">
                        </video>
                    </div>
                </div>
            </div>
            <div class="col-md-3"><label class="labels_title">Submit Project Link:</label></div>
            <div class="col-md-6"><input type="text" name="display_name" class="form-control" placeholder="" value="" required></div>
            <div class="col-md-3"><button type="button" class="btn_submit">Submit</button></div>
        </div>
    </div>


<?php get_footer(); ?>