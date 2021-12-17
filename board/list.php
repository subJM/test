<?php
	session_start();
  include $_SERVER['DOCUMENT_ROOT'] . "/html/db/db_connector.php";
  include $_SERVER['DOCUMENT_ROOT'] . "/html/db/create_table.php";
  create_table($con, "board");

  $list= array();
  $sql = "select * from 'board'";
  $list = mysqli_query($con, $sql);
  
  //1.페이지 설정되어 있지 않으면 기본 디폴트값으로 1로 설정한다. 
  $page = isset($_GET["page"]) ? $_GET["page"] : 1;

  //2.전체 레코드수
  $sql = "select * from board order by num desc";
  $select_result = mysqli_query($con, $sql);
  $total_record = mysqli_num_rows($select_result); 

  //3.페이지당 글수 
  $scale = 5;

  //4.전체 페이지 수($total_page) 계산
  $total_page = ($total_record !== 0)? ceil($total_record / $scale) : 0;

  //5.표시할 페이지($page)에 따라 $start 계산 예)6페이지 (50번~59번)
  $start = ($page - 1) * $scale;
  
  //6.현재페이지 레코드 결과값을 저장하기 위해서 배열선언
  $list = array(); 

  //7.해당되는 페이지 레코드를 가져와서 배열에 집어넣고 번호순서도 포함시킨다. 
  $sql = "select * from board order by num desc LIMIT {$start}, {$scale}";
  $select_result = mysqli_query($con, $sql);
  for ($i = 0; $row = mysqli_fetch_assoc($select_result); $i++) {
      $list[$i] = $row;
      //번호순서
      $list_num = $total_record - ($page - 1) * $scale; 
      $list[$i]['no'] = $list_num -$i;
  }

?>
<!DOCTYPE HTML>
<html lang="ko">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="format-detection" content="telephone=no">
  <title>2016 굿모닝 경기 소통 크리에이터 공모전</title>
  <link rel="stylesheet" type="text/css" href="../css/general.css">
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script type="text/javascript" src="../js/jquery.scrollTo-min.js"></script>
  <script src="../js/layout.js"></script>
</head>

<body>
  <div id="wrap">
    <!-- @ CONTAINER -->
    <section id="container" class="sub  new">
      <!-- @ CONTENTS -->
      <div id="contents">
        <!-- @ SUB TITLE AREA -->
        <div class="sub-title-area">
          <h2 class="tit">News & Info </h2>
        </div>
        <!-- 에러메세지 출력 -->
        <?php if(isset($_GET['error'])){?>
        <div id="check" style="color:red">
          <?= $_GET['error']; ?>
        </div>
        <?php } ?>

        <!-- 성공메세지 출력 -->
        <?php if(isset($_GET['success'])){?>
        <div id="check" style="color:blue">
          <?= $_GET['success']; ?>
        </div>
        <?php } ?>
        <div class="btn_area">
          <a href="write.php" class="btn_blue_line">글쓰기</a>
        </div>
        <!-- 페이징 처리는 5개씩 해주세요-->
        <table class="news_list">
          <caption>News 리스트</caption>
          <colgroup>
            <col style="width: 10%">
            <col style="width:*">
            <col style="width: 10%">
            <col style="width: 10%">
            <col style="width: 5%">
            <col style="width: 8%">
          </colgroup>
          <thead>
            <tr>
              <th scope="col">번호</th>
              <th scope="col">제목</th>
              <th scope="col">작성자</th>
              <th scope="col">등록일</th>
              <th scope="col">조회</th>
              <th scope="col">첨부</th>
            </tr>
          </thead>
          <tbody>
            <ul>

            </ul>

          <?php 
					  for($i=0; $i<count($list); $i++){
		  		?>
            <tr>
              <td><?= $list[$i]['num'] ?>9</td>
              <td class="board_txt">
                <p>
                  <a href="href=" question_view.php?num=<?= $list[$i]['num'] ?>"><?= $list[$i]['subject'] ?></a>
                </p>
              </td>
              <td class="board_man"><?= $list[$i]['name'] ?></td>
              <td class="board_date"><?= $list[$i]['regist_day'] ?></td>
              <td class="board_read"><?= $list[$i]['hit'] ?></td>
              <?php
                $file_image = (isset($list[$i]['file_name']))? "<img src='./img/file.gif'>" :"";
              ?>
              <td class="board_file"><span class="file_icon"><?= $file_image ?></span>
            </tr>
            <?php } ?>


        </table>
        <div class="pagination">
        <?php
				//===========================================================
				//7. 현재 페이지 처리 함수
				$url = "./question_list.php?page=";
				$write_page = get_paging($scale, $page, $total_page, $url);
				//===========================================================
				//데이터베이스 접속 종료
			?>
        </div>
        <div class="find_wrap">
                <select name="" id="">
                    <option value="">제목</option>
                    <option value="">내용</option>
                </select>
                <input type="text" name="" id=""  title="검색어 입력" placeholder="검색어 입력">
                <a href="#" class="btn_gray">검색</a>
            </div>
        </div>
        <!-- CONTENTS @ -->
    </section>
    <!-- CONTAINER @ -->
</div>
</body>
</html>