@extends('dispatch.layout')


@section('content')


    <style>
        .btn-file {
            position: relative;
            overflow: hidden;
        }

        .btn-file input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            font-size: 100px;
            text-align: right;
            filter: alpha(opacity=0);
            opacity: 0;
            outline: none;
            background: white;
            cursor: inherit;
            display: block;
        }

        .btn-default {
            width: 100%;
        }

        .datepicker th.next, .datepicker th.prev {
            cursor: pointer;
        }

        .doc_file {
            top: 4px !important;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            font-size: 16px !important;
            text-align: right !important;
            filter: alpha(opacity=0);
            opacity: 2 !important;
            transform: unset !important;
        }
    </style>


    <div class="container-fluid">
        <div class="col-md-6 col-md-offset-3">


            <form action="<?php echo asset_url(); ?>/dispatch/editaction" method="post" role="form"
                  style="padding: 4px;border: 1px solid #C3C0C0;" enctype="multipart/form-data">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon" style="background:#23374a;"><span style="color:white;"
                                                                                          class="glyphicon glyphicon-user"></span></span>
                        <input type="hidden" value="<?php echo $user->id; ?>" name="id" required>
                        <input type="text" class="form-control" id="InputEmailFirst" name="uname" placeholder="Name"
                               value="<?php echo $user->name; ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon" style="background:#23374a;"><span style="color:white;"
                                                                                          class="glyphicon glyphicon-calendar"></span></span>


                        <input type="text" class="form-control" id="date_pck" name="ubdate"
                               value="<?php echo $user->dateofbirth; ?>" placeholder="Date Of Birth" required>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon" style="background:#23374a;"><span style="color:white;"
                                                                                          class="glyphicon glyphicon-calendar"></span></span>

                        <input type="text" class="form-control" id="join_date" name="ujdate"
                               value="<?php echo $user->dateofjoin; ?>" placeholder="Date Of Joining">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon" style="background:#23374a;"><span style="color:white;"
                                                                                          class="glyphicon glyphicon-comment"></span></span>

                        <input type="text" class="form-control" id="age" name="uage" placeholder="age"
                               value="<?php echo $user->age; ?>" readonly required>

                    </div>
                </div>

                <div class="form-group">
                    <!--   <div class="input-group">
                          <span class="input-group-addon"><span class="glyphicon glyphicon-picture"></span></span>
                        <span class="btn btn-default btn-file">


       Photo <input type="file" name="uimg" id="img" accept="image/*" required>
   </span>
                       </div>-->
                    <div class="fileupload fileupload-new" data-provides="fileupload">
                        <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                            <img src="<?php echo asset_url() . '/uploads/' . $user->img; ?>" alt=""/>
                        </div>
                        <div class="fileupload-preview fileupload-exists thumbnail"
                             style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                        <div> <span class="btn btn-white btn-file"> <span class="fileupload-new"><i
                                            class="fa fa-paper-clip"></i> Select image</span> <span
                                        class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
                        <input type="file" class="default" accept="image/*" name="uimg" id="c_sImg"/>
                        </span> <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i
                                        class="fa fa-trash"></i> Remove</a></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon" style="background:#23374a; color:#fff;"><!--<span class="glyphicon glyphicon-bookmark"></span>--> Documents </span>
                        <span class="btn btn-default btn-file" style="height:30px;">
    	 <input type="file" name="udoc" class="doc_file" accept="application/*" style="right:212px;">
</span>
                    </div>
                    <?php if($user->doc != '') {?><a target="_blank" class="btn btn-primary"
                                                     href="http://view.officeapps.live.com/op/view.aspx?src=<?php echo asset_url() . '/uploads/' . $user->doc; ?>">View
                        Doc</a><?php //echo $user->doc;
                    } ?>
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon" style="background:#23374a;"><span style="color:white;"
                                                                                          class="glyphicon glyphicon-user"></span></span>

                        <input type="email" class="form-control" id="InputEmailFirst"
                               value="<?php echo $user->email; ?>" name="uemail" placeholder="Email" required>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon" style="background:#23374a;"><span style="color:white;"
                                                                                          class="glyphicon glyphicon-user"></span></span>

                        <input type="text" class="form-control" id="uuname" onBlur="press()" name="uuname"
                               value="<?php echo $user->username; ?>" placeholder="Username" minlength="6" required>

                    </div>
                    <p id="error" style="color:red;"></p>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon" style="background:#23374a;"><span style="color:white;"
                                                                                          class="glyphicon glyphicon-user"></span></span>

                        <input type="password" class="form-control" id="InputEmailFirst" name="upass"
                               placeholder="Password" value="<?php echo "vcnbvnzcmmnb"; ?>" minlength="6">
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon" style="background:#23374a;"><span style="color:white;"
                                                                                          class="glyphicon glyphicon-user"></span></span>

                        <select class="form-control" id="InputEmailFirst" name="utype" required>
                            <option value="staff" <?php if ($user->type == 'staff') {
                                echo "selected";
                            } ?>>Staff
                            </option>
                            <option value="admin" <?php if ($user->type == 'admin') {
                                echo "selected";
                            } ?>>Admin
                            </option>
                        </select>
                    </div>
                </div>
                <button style="background:#23374a;" type="submit" id="btn" class="btn btn-primary">Save</button>


            </form>
        </div>
    </div>




@stop




@section('javascript')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/js/bootstrap-datepicker.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo asset_url(); ?>/dispatcher/js/bootstrap-timepicker.min.js"></script>
    <script src="<?php echo asset_url(); ?>/dispatcher/js/bootstrap-fileupload.js"></script>
    <script src="<?php echo asset_url(); ?>/dispatcher/js/jquery.dataTables.js"></script>
    <script src="<?php echo asset_url(); ?>/dispatcher/js/DT_bootstrap.js"></script>
    <script src="<?php echo asset_url(); ?>/dispatcher/js/dynamic_table_init.js"></script>

    <script type="text/javascript">

        $('#date_pck').focus(function () {
            var dob = $(this).val();
            dob = new Date(dob);
            var today = new Date();
            var age = Math.floor((today - dob) / (365.25 * 24 * 60 * 60 * 1000));
            $('#age').val(age);
        });

        $('#date_pck').datepicker({
            format: 'yyyy-mm-dd'
        });
        $('#join_date').datepicker({
            format: 'yyyy-mm-dd'
        });
    </script>

    <script>
        function press() {
            var username = $('#uuname').val();
            if (username.length > 5) {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo asset_url(); ?>/dispatch/chckuser",
                    data: "username=" + username,
                    success: function (data) {
                        data = JSON.parse(data);
                        if (!data.success) {
                            if (username != "<?php echo $user->username; ?>") {
                                $('#uuname').css('border-color', 'red');
                                $('#btn').prop('disabled', true);
                                $('#error').text('Username Already Exists');
                            }
                            else {
                                $('#uuname').css('border-color', '#008313');
                                $('#btn').prop('disabled', false);
                                $('#error').empty();
                            }
                        }
                        else {
                            $('#uuname').css('border-color', '#008313');
                            $('#btn').prop('disabled', false);
                            $('#error').empty();
                        }
                    },
                    error: function (a, b, c) {
                        console.log(a);
                        console.log(b);
                        console.log(c);
                    }
                });
            }
        }
    </script>



    <script>
        $(document).ready(function () {
            $('#example').DataTable();
        });
    </script>






@stop