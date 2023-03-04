<?


//include("../../include/mysqliphp");

$connect = new PDO("mysql:host=localhost;dbname=saais_pw","root","root");
$connect->exec("set names utf8");


#รับค่าที่ส่งมา
$request_data=json_decode(file_get_contents("php://input"));
$data=array();


if($request_data->action=="insert"){
    $data=array(
                ":goal_details" =>$request_data->goal_details , 
                ":goal_evaluate"=>$request_data->goal_evaluate,
                ":goal_checker" =>$request_data->teacher_id
            );

    $query= "
                INSERT INTO project_goals (project_id,goal_details,goal_evaluate,goal_checker) 
                VALUES
                    ('" . $request_data->project_id . "' ,:goal_details,:goal_evaluate,:goal_checker)";
    $statement=$connect->prepare($query);
    $statement->execute($data);
    $output=array("message"=>"Insert Complete");
    echo json_encode($output);
}



if($request_data->action=="get_all"){
    $query= "
                SELECT 
                        p.project_goal_id,
                        p.goal_details,
                        p.goal_evaluate,
                        p.goal_checker,
                        concat(t.firstname, ' ', t.lastname) as checker_name
                FROM 
                    project_goals p left join teachers t
                    on (p.goal_checker = t.teacher_id)
                WHERE project_id = '" . $request_data->project_id . "'" ;
    $statement=$connect->prepare($query);
    $statement->execute();

    $count = $statement->rowCount();

    if($count>0){
        while($row=$statement->fetch(PDO::FETCH_ASSOC)){
            $data[]=$row;
        }
    }else{
        $data[] =  array('NO');
    }
    echo json_encode($data);
}

if($request_data->action=="get_project_goal"){
    $query= "
            SELECT 
                    p.project_goal_id,
                    p.goal_details,
                    p.goal_evaluate,
                    p.goal_checker,
                    concat(t.firstname, ' ', t.lastname) as checker_name
            FROM 
                project_goals p left join teachers t
                on (p.goal_checker = t.teacher_id)
            WHERE 
                    p.project_goal_id = $request_data->project_goal_id";
    $statement=$connect->prepare($query);
    $statement->execute();

    while($row=$statement->fetch(PDO::FETCH_ASSOC)){
        $data['project_goal_id'] =  $row['project_goal_id'];
        $data['goal_details']    =  $row['goal_details'];
        $data['goal_evaluate']   =  $row['goal_evaluate'];  
        $data['goal_checker']    =  $row['goal_checker'];
        $data['checker_name']    =  $row['checker_name'];
    }

    echo json_encode($data);
}


if($request_data->action=="update"){

    $data=array(
                    ":goal_details"   =>$request_data->goal_details , 
                    ":goal_evaluate"  =>$request_data->goal_evaluate, 
                    ":goal_checker"   =>$request_data->teacher_id,
                    ":project_goal_id" =>$request_data->project_goal_id
                );

    $query= "
                UPDATE project_goals 
                SET 
                        goal_details  = '" . $request_data->goal_details . "'  , 
                        goal_evaluate = '" . $request_data->goal_evaluate . "'  ,
                        goal_checker  = '" . $request_data->teacher_id . "' 
                WHERE 
                        project_goal_id = " . $request_data->project_goal_id ;


    $statement=$connect->prepare($query);
    $statement->execute($data);
    $output=array("message"=>"Update Complete");
    echo json_encode($output);
}


if($request_data->action=="delete"){
    $query= "DELETE FROM project_goals WHERE project_goal_id = $request_data->project_goal_id";
    $statement=$connect->prepare($query);
    $statement->execute();
    $output=array("message"=>"Delete Complete");
    echo json_encode($output);
}


if($request_data->action == "get_all_teacher"){
    $_quey = "
        SELECT 
            teacher_id,firstname,lastname 
        FROM
            teachers
        ORDER BY
            convert(firstname using tis620) 
                ";
    $_statment = $connect->prepare($_quey);
    $_statment->execute();

    while($row=$_statment->fetch(PDO::FETCH_ASSOC)){
        $data[]=$row;
    }
    echo json_encode($data);

}

?>