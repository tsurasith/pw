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
    $_img_parent_folder  = "/parentphoto";
    $_img_father_folder  = "/fatherphoto";
    $_img_mother_folder  = "/motherphoto";
    $_img_student_home   = "/studhome";
    $_img_student_certificate = "/student_certificate";

    $_target = $_SERVER["DOCUMENT_ROOT"];

    $_student_img_path  = $_target . $_img_root_folder . $_img_student_folder . "/";
    $_teacher_img_path  = $_target . $_img_root_folder . $_img_teacher_folder . "/";
    $_parent_img_path   = $_target . $_img_root_folder . $_img_parent_folder . "/";
    $_father_img_path   = $_target . $_img_root_folder . $_img_father_folder . "/";
    $_mother_img_path   = $_target . $_img_root_folder . $_img_mother_folder . "/";
    $_student_img_home_path  = $_target . $_img_root_folder . $_img_student_home  . "/";

    $_student_certificate_path = $_target . $_img_root_folder . $_img_student_certificate . "/";
    

    /* import fingerprint raw data */
    $_hr_raw_folder      = "/pwnew/pw/hr_files";

    /* absents attachments path */
    $_hr_absent_folder   = "/pwnew/pw/hr_absent_files";
    $_hr_img_folder      = "/hr_absent_files";
    

    /* student LEC path files */
    $_student_lec_folder = "/pwnew/pw/student_lec_files";

    /* student grade files */
    $_student_grade_folder = "/pwnew/pw/student_grade_files";

     /* Local School grade files */
     $_local_school_folder = "/pwnew/pw/local_school_export_files";

?>
