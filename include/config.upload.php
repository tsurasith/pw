<?php

    /* 
       This is the root image folder 
       Full path - /Applications/MAMP/htdocs/pwnew/pw/images
       Root image folder suppose to be - "/pwnew/pw/images"
    */
    $_img_root_folder   = "/pwnew/pw/images";

    /* 
       These for specify the sub image folders for each other
       It requires to leading with / for example 
       Folder - "student_image" then the value suppose to be "/student_image"
    */
    $_img_student_folder = "/studphoto";
    $_img_teacher_folder = "/teacphoto";
    $_img_parent_folder  = "/aphoto";
    $_img_father_folder  = "/papaphoto";
    $_img_mother_folder  = "/mamaphoto";
    $_img_student_home   = "/studhome";

    $_target = $_SERVER["DOCUMENT_ROOT"];

    $_student_img_path  = $_target . $_img_root_folder . $_img_student_folder . "/";
    $_teacher_img_path  = $_target . $_img_root_folder . $_img_teacher_folder . "/";
    $_parent_img_path   = $_target . $_img_root_folder . $_img_parent_folder . "/";
    $_father_img_path   = $_target . $_img_root_folder . $_img_father_folder . "/";
    $_mother_img_path   = $_target . $_img_root_folder . $_img_mother_folder . "/";
    $_student_img_path  = $_target . $_img_root_folder . $_img_student_home  . "/";

    
    
?>
