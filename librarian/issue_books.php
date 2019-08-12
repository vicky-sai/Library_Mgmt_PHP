<?php
session_start();
if(!isset($_SESSION["librarian"]))
{
    ?>
    <script type="text/javascript">
        window.location="login.php";
    </script>
    <?php
}
include "connection.php";
include "header.php";
?>
    <!-- page content area main -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3></h3>
                </div>

                <div class="title_right">
                    <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search for...">
                            <span class="input-group-btn">
                      <button class="btn btn-default" type="button">Go!</button>
                    </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="row" style="min-height:500px">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Issue books</h2>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">

                            <form name="form1" action="" method="post">
                                <table>
                                    <tr>
                                        <td>
                                            <select name="enr" class="form-control selectpicker">
                                                <option selected hidden> Choose enrollment</option>;

                                                <?php

                                                $res=mysqli_query($link,"select enrollment from student_registration");
                                                while ($row=mysqli_fetch_array($res))
                                                {
                                                    echo "<option>";
                                                    echo $row["enrollment"];
                                                    echo "</option>";
                                                }
                                                ?>

                                            </select>
                                        </td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>
                                            <input type="submit" value="Search" name="submit1" class="form-control btn btn-default"
                                                   style="margin-top : 5px; background-color: #00264d; color: white">
                                        </td>
                                    </tr>
                                </table>

                                <?php
                                  if(isset($_POST["submit1"]))
                                  {
                                      $res5=mysqli_query($link,"select * from student_registration where enrollment='$_POST[enr]'");
                                      while ($row5=mysqli_fetch_array($res5))
                                      {
                                          $firstname=$row5["firstname"];
                                          $lastname=$row5["lastname"];
                                          $username=$row5["username"];
                                          $email=$row5["email"];
                                          $contact=$row5["contact"];
                                          $sem=$row5["sem"];
                                          $enrollment=$row5["enrollment"];
                                          $_SESSION["enrollment"]=$enrollment;
                                          $_SESSION["susername"]=$username;
                                      }
                                  ?>
                                <table class="table table-bordered">
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" placeholder="Enrollment no"
                                                   value="<?php echo $enrollment; ?>" name="enrollment" disabled/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" placeholder="Student name"
                                                   value="<?php echo $firstname.' '.$lastname; ?>" name="studentname" required/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" placeholder="Student Sem"
                                                   value="<?php echo $sem; ?>"name="studentsem" required/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" placeholder="Student contact"
                                                   value="<?php echo $contact; ?>"name="studentcontact" required/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" placeholder="Student E-mail"
                                                   value="<?php echo $email; ?>"name="studentemail" required/>
                                        </td>
                                    </tr>
                                    <tr>
                                    <td>
                                        <select name="booksname" class="form-control">

                                            <?php
                                            $res=mysqli_query($link,"select books_name from add_books");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                echo "<option>";
                                                echo $row["books_name"];
                                                echo "</option>";
                                            }
                                            ?>

                                        </select>
                                    </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" placeholder="Books issue Date"
                                                   value="<?php echo date("d-M-Y"); ?>" name="booksissuedate" required/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" placeholder="Student username"
                                                   name="studentusername" value="<?php echo $username; ?>"disabled/>
                                        </td>
                                    </tr>
                                    <td>
                                        <input type="submit" value="Issue books" name="submit2" class="form-control btn btn-default"
                                               style="background-color: #00264d; color: white">
                                    </td>
                                </table>
                                      <?php
                                  }
                                ?>

                            </form>
                            <?php
                            if(isset($_POST["submit2"]))

                            {
                                $qty=0;
                                $res=mysqli_query($link,"select * from add_books where books_name='$_POST[booksname]'");
                                while($row=mysqli_fetch_array($res))
                                {
                                    $qty=$row["available_qty"];
                                }

                                if($qty==0)
                                {
                                    ?>
                                    <div class="alert alert-danger col-lg-6 col-lg-push-3">
                                        <strong style="color:white">This books is not available in the stock</strong>
                                    </div>

                                <?php

                                }
                                else {
                                    mysqli_query($link, "insert into issue_books values('','$_SESSION[enrollment]','$_POST[studentname]','$_POST[studentsem]','$_POST[studentcontact]','$_POST[studentemail]','$_POST[booksname]','$_POST[booksissuedate]','','$_SESSION[susername]')");
                                    mysqli_query($link, "update add_books set available_qty=available_qty-1 where books_name='$_POST[booksname] '");
                                    ?>
                                    <script type="text/javascript">
                                        alert("Book issued successfully.");
                                        window.location.href = window.location.href;
                                    </script>
                                    <?php
                                }

                            }

                            ?>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /page content -->

<?php
include "footer.php";
?>