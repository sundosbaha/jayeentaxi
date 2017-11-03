@extends('dispatch.layout')


@section('content')


    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                      
                        <span class="tools pull-right">
                            <a href="javascript:;" class="fa fa-chevron-down"></a>
                            <a href="javascript:;" class="fa fa-cog"></a>
                            <a href="javascript:;" class="fa fa-times"></a>
                         </span>
                    </header>
                    <div class="panel-body">
                        <table class="display table table-bordered table-striped dataTable" id="example">
                            <thead>
                            <tr>
                                <th><?php echo Lang::get('dispatcher.request_id'); ?></th>
                                <th><?php echo Lang::get('dispatcher.customer_name'); ?></th>
                                <th><?php echo Lang::get('dispatcher.driver_id'); ?></th>
                                <th><?php echo Lang::get('dispatcher.driver_name'); ?></th>
                                <th><?php echo Lang::get('dispatcher.status'); ?></th>
                                <th><?php echo Lang::get('dispatcher.amount'); ?></th>
                                <th><?php echo Lang::get('dispatcher.payment_status'); ?></th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach($request as $req)
                            {
                            ?>
                            <tr>
                                <td><?php echo $req->request_id ?></td>
                                <td><?php echo $req->ufname . ' ' . $req->ulname; ?></td>
                                <td><?php echo $req->driver_id; ?></td>
                                <td><?php echo $req->dfname . ' ' . $req->dlname; ?></td>
                                <td>
                                    <?php
                                    if ($req->is_completed == 1) {
                                        echo "<span class='badge bg-red'>Completed</span>";
                                    } else if ($req->is_cancelled == 1) {
                                        echo "<span class='badge bg-red'>Cancelled</span>";
                                    } else if ($req->confirmed_walker != 0) {
                                        echo "<span class='badge bg-red'>On Trip</span>";
                                    } else {
                                        echo "<span class='badge bg-red'>-</span>";
                                    }
                                    ?>
                                </td>
                                <td><?php echo $req->total;  ?></td>
                                <td>
                                    <?php
                                    if ($req->is_paid == 1) {
                                        echo "<span class='badge bg-yellow'>Payment Completed</span>";
                                    } else {
                                        echo "<span class='badge bg-yellow'>Trip Not Completed</span>";
                                    }
                                    ?>
                                </td>
                                <!--<td data-toggle="modal" data-target="#assign"><span class="label label-danger label-mini"></span></td>-->

                            <?php
                            }
                            ?>


                            </tbody>
                        </table>
                </section>

            </div>
        </div>
    </div>




@stop



@section('javascript')

    <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>


    <script>
        $(document).ready(function () {
            $('#example').DataTable({
                "ordering": false
            });

        });
    </script>






@stop