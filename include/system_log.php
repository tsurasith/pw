<?php

    function event_log($_connection,
        $_event_action_type_code,
        $_event_module_code,
        $_event_task_code,
        $_event_key,
        $_event_details,
        $_event_user_id,$acadyear,$acadsemester)
        {

            $_sqlInsert = "
                INSERT INTO `events_log`(
                    `event_action_type_code`,
                    `event_module_code`,
                    `event_task_code`,
                    `event_datetime`,
                    `event_user_account_id`,
                    `acadyear`,
                    `acadsemester`,
                    `event_key`,
                    `event_details`
                )
                VALUES(
                    '" . $_event_action_type_code . "',
                    '" . $_event_module_code . "',
                    '" . $_event_task_code . "',
                    CURRENT_TIMESTAMP,
                    '" . $_event_user_id . "',
                    '" . $acadyear . "',
                    '" . $acadsemester . "',
                    '" . $_event_key . "',
                    '" . $_event_details . "'
                )
                ";
            $_resIns = mysqli_query($_connection,$_sqlInsert);
            if($_resIns){
                return "success";
            }else{
                return "failed";
            }
        }

        /*
        event_log($_connection,
        $_event_action_type_code,
        $_event_module_code,
        $_event_task_code,
        $_event_key,  -- $_event_key = hash("sha256",$_event_details);
        $_event_details,
        $_event_user_id,$acadyear,$acadsemester)
        */

        function checkDuplicateEventLog($_connection,$_key){
            $_sql = "SELECT * FROM events_log where event_key = '" . $_key . "'";
            $_res = mysqli_query($_connection,$_sql);
            $_rows = 0;
            $_rows = mysqli_num_rows($_res);

            if($_rows > 0){
                return false;
            }else{
                return true;
            }
        }

?>