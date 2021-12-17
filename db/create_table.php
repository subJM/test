<?php
function create_table($con, $table_name)
{
    $flag = false;
    $sql = "show tables from board_test";
    $result = mysqli_query($con, $sql) or die('Error' . mysqli_error($con));

    //반복문을 통해서 레코드셋에서 한 레코드씩 가져와서 첫번째 필드내용을 조사해서 해당된 테이블명이 있는지 확인한다.
    while ($row = mysqli_fetch_row($result)) {
        if ($row[0] === "$table_name") {
            $flag = true;
            break;
        }
    }

    //해당된 테이블이 없으면 해당 테이블명을 찾아서 테이블 쿼리문을 작성한다.
    if ($flag === false) {
        switch ($table_name) {
                //질문자 게시판
            case 'board':
                $sql = "CREATE TABLE IF NOT EXISTS `board` (
                  `num` int(11) NOT NULL AUTO_INCREMENT,
                  `name` char(10) NOT NULL,
                  `subject` char(200) NOT NULL,
                  `content` text NOT NULL,
                  `regist_day` char(20) NOT NULL,
                  `hit` int(11) NOT NULL,
                  `file_name_0` char(40) DEFAULT NULL,
                  `file_type_0` char(40) DEFAULT NULL,
                  `file_copied_0` char(40) DEFAULT NULL,
                  PRIMARY KEY (`num`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                break;
       
            default:
                echo "<script>alert('해당테이블명이 없습니다. 점검요망!');</script>";
                break;
        } //end of switch
        if (mysqli_query($con, $sql)) {
            echo "<script>alert('{$table_name} 테이블이 생성되었습니다.');</script>";
        } else {
            // echo "테이블 생성 실패원인" . mysqli_error($con);
        }
    } //end of if($flag)
}
?>