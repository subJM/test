<?php
//+++++++++++++++++++++++++++++++++++++++++++++++
   //1.세션스타트 및 데이터 베이스 연동, 테이블 생성( 테이블이 존재하면 생략)
  session_start();
  include_once $_SERVER['DOCUMENT_ROOT']."/html/db/db_connect.php";

  print_r($_POST);
  $username = $_POST["username"]; 

    //2.클라이언트부터 전송해온 값이 존재하는지 점검
    if(isset($_POST["subject"]) && isset($_POST["content"])){
        
        //3. mysql injection 함수 사용
        $subject = mysqli_real_escape_string($con, $_POST["subject"]);
        $content = mysqli_real_escape_string($con, $_POST["content"]);
       
        //4. 공백이 있는지 점검
        if(empty($subject)){
            header("location: write.php?error=제목이 비어있어요");
            exit(); 
        }else if(empty($content)){
            header("location: write.php?error=내용이 비어있어요");
            exit(); 
        }else{

      
          $regist_day = date("Y-m-d (H:i)");  // 현재의 '년-월-일-시-분'을 저장
          
          //업로드 디렉토리(중복파일이 안되게끔 서로다르게 사용할수 있도록 설정)
          //홍길동 : a.hwp 20211207111923077_a.hwp, 저길동 : a.hwp => 20211207111923089_a.hwp 
          $upload_dir = "./data/";

          if(!isset($_FILES["upfile"]["name"]) && isset($_FILES["upfile"]["error"])){
            $upfile_name = "";
            $upfile_type = "";
            $copied_file_name = "";
          }else{
            //1. 파일배열에서 5개 항목을 받는다. 
            $upfile_name = $_FILES["upfile"]["name"];
            $upfile_tmp_name = $_FILES["upfile"]["tmp_name"];
            $upfile_type = $_FILES["upfile"]["type"];
            // 안되면 php ini에서 최대 크기 수정!
            $upfile_size = $_FILES["upfile"]["size"];  
            $upfile_error = $_FILES["upfile"]["error"];

            //2. 파일을 파일명과 확장자를 분리시킨다.(memo.sql) => ['memo','sql']
            $file = explode(".", $upfile_name); 
            $file_name = $file[0]; 
            $file_ext = $file[1];

            //3. 서버에 저장할 파일명을 중복되지 않기 하기위해서 날짜명_시간_파일명.확장자 만든다.
            $new_file_name = date("Y_m_d_H_i_s");
            $new_file_name = $new_file_name . "_" . $file_name;
            // 2021_12_07_11_35_20_memo.sql
            $copied_file_name = $new_file_name . "." . $file_ext;
            // ./data/2020_09_23_11_10_20_memo.sql 다 합친것
            $uploaded_file = $upload_dir . $copied_file_name; 

            //1메가 이상이면 받지 않겠다. 
            if ($upfile_size > 10000000) {
              header("location: write.php?error=첨부파일 10MB 이상 안됩니다.");
              exit(); 
            }

            //
            if (!move_uploaded_file($upfile_tmp_name, $uploaded_file)) {
              header("location: write.php?error=업로드 실패했습니다.");
              exit(); 
            }
          }

          //실제 테이블에 파일업로드 정보를 아이디, 성명, 주제, 내용, 날짜, hit, 파일
          $sql = "insert into board (name, subject, content, regist_day, hit,  file_name, file_type, file_copied) ";
          $sql .= "values('$username', '$subject', '$content', '$regist_day', 0, ";
          $sql .= "'$upfile_name', '$upfile_type', '$copied_file_name')";
          $insert_result = mysqli_query($con, $sql);  // $sql 에 저장된 명령 실행

          if(!$insert_result){
            header("location: write.php?error=테이블에 파일저장이 실패했습니다.");
            exit(); 
          }
          mysqli_close($con);
          
          header("location: list.php?succese=테이블에 파일저장이 실패했습니다.");
  
        }
    }else{
        mysqli_close($con);
        header("location: write.php?error=알수없는 오류발생했습니다.");
        exit(); 
    }
?>