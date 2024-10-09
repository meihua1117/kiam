<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php"
?>
    <div class="wrapper">

      <!-- Top 메뉴 -->
      <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header_menu.inc.php";?>      
      
      <!-- Left 메뉴 -->
      <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_left_menu.inc.php";?>      

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            회원관리
            <small>회원을 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">회원관리</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12" style="padding-bottom:20px">
              <button class="btn btn-primary pull-right" style="margin-right: 5px;"><i class="fa fa-download"></i> 엑셀다운받기</button>
              <div class="box-tools">
                <div class="input-group" style="width: 250px;">
                  <input type="text" name="table_search" class="form-control input-sm pull-right" placeholder="아이디/이름/휴대폰">
                  <div class="input-group-btn">
                    <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
                
              </div>            
              </div>            
          </div>
          
          
          <div class="row">

              

            
              <div class="box">
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                     <colgroup>
                      <col width="60px">
                      <col width="100px">
                      <col width="100px">
                      <col width="100px">
                      <col width="100px">
                      <col width="60px">
                      <col width="100px">
                      <col width="100px">
                      <col width="100px">
                      <col width="100px">
                      <col width="120px">
                     </colgroup>
                    <thead>
                      <tr>
                        <th>번호</th>
                        <th>회원정보</th>
                        <th>아이디</th>
                        <th>이름</th>
                        <th>전화번호</th>
                        <th>구분</th>
                        <th>기부폰</th>
                        <th>유료건수</th>
                        <th>부가기능</th>
                        <th>총결제</th>
                        <th>접속일</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>1</td>
                        <td>수정 / 탈퇴</td>
                        <td>obmms01</td>
                        <td>원북관리자</td>
                        <td>01027331882</td>
                        <td>소유폰</td>
                        <td>20</td>
                        <td>180,000</td>
                        <td>Y</td>
                        <td>10,000,00</td>
                        <td>2015.11.20</td>
                      </tr>
                      <tr>
                        <td>1</td>
                        <td>수정 / 탈퇴</td>
                        <td>obmms01</td>
                        <td>원북관리자</td>
                        <td>01027331882</td>
                        <td>소유폰</td>
                        <td>20</td>
                        <td>180,000</td>
                        <td>Y</td>
                        <td>10,000,00</td>
                        <td>2015.11.20</td>
                      </tr>
                      <tr>
                        <td>1</td>
                        <td>수정 / 탈퇴</td>
                        <td>obmms01</td>
                        <td>원북관리자</td>
                        <td>01027331882</td>
                        <td>소유폰</td>
                        <td>20</td>
                        <td>180,000</td>
                        <td>Y</td>
                        <td>10,000,00</td>
                        <td>2015.11.20</td>
                      </tr>
                      <tr>
                        <td>1</td>
                        <td>수정 / 탈퇴</td>
                        <td>obmms01</td>
                        <td>원북관리자</td>
                        <td>01027331882</td>
                        <td>소유폰</td>
                        <td>20</td>
                        <td>180,000</td>
                        <td>Y</td>
                        <td>10,000,00</td>
                        <td>2015.11.20</td>
                      </tr>
                      <tr>
                        <td>1</td>
                        <td>수정 / 탈퇴</td>
                        <td>obmms01</td>
                        <td>원북관리자</td>
                        <td>01027331882</td>
                        <td>소유폰</td>
                        <td>20</td>
                        <td>180,000</td>
                        <td>Y</td>
                        <td>10,000,00</td>
                        <td>2015.11.20</td>
                      </tr>
                      <tr>
                        <td>1</td>
                        <td>수정 / 탈퇴</td>
                        <td>obmms01</td>
                        <td>원북관리자</td>
                        <td>01027331882</td>
                        <td>소유폰</td>
                        <td>20</td>
                        <td>180,000</td>
                        <td>Y</td>
                        <td>10,000,00</td>
                        <td>2015.11.20</td>
                      </tr>                                            
                      
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
            
            <div class="row">
                <div class="col-sm-5">
                    <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Showing 1 to 10 of 57 entries</div>
                </div>
                <div class="col-sm-7">
                    <div class="dataTables_paginate paging_simple_numbers" id="example1_paginate">
                        <ul class="pagination">
                            <li class="paginate_button previous disabled" id="example1_previous"><a href="#" aria-controls="example1" data-dt-idx="0" tabindex="0">Previous</a></li>
                            <li class="paginate_button active"><a href="#" aria-controls="example1" data-dt-idx="1" tabindex="0">1</a></li>
                            <li class="paginate_button "><a href="#" aria-controls="example1" data-dt-idx="2" tabindex="0">2</a></li>
                            <li class="paginate_button "><a href="#" aria-controls="example1" data-dt-idx="3" tabindex="0">3</a></li>
                            <li class="paginate_button "><a href="#" aria-controls="example1" data-dt-idx="4" tabindex="0">4</a></li>
                            <li class="paginate_button "><a href="#" aria-controls="example1" data-dt-idx="5" tabindex="0">5</a></li>
                            <li class="paginate_button "><a href="#" aria-controls="example1" data-dt-idx="6" tabindex="0">6</a></li>
                            <li class="paginate_button next" id="example1_next"><a href="#" aria-controls="example1" data-dt-idx="7" tabindex="0">Next</a></li>
                        </ul>
                   </div>
                </div>
            </div>
          </div><!-- /.row -->
          
          

        
          
          
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

      <!-- Footer -->
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      