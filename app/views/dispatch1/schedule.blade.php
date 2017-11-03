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
                        <table class="table  table-hover general-table" id="example">
                            <thead>
                            <tr>
                                <th>S.no</th>
                                <th><?php echo Lang::get('dispatcher.customer_name'); ?></th>

                                <th><?php echo Lang::get('dispatcher.date'); ?></th>
                                <th><?php echo Lang::get('dispatcher.time'); ?></th>
                                <th><?php echo Lang::get('dispatcher.Pickup'); ?></th>


                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $cntrr = 1;
                            foreach($request as $req)
                            {
                            ?>
                            <tr>
                                <td><?php  echo $cntrr; ?></td>
                                <td><?php echo $req->first_name . ' ' . $req->last_name ?></td>


                                <td><?php echo date('d-m-Y', strtotime($req->schedule_datetime)); ?></td>
                                <td>
                                    <?php echo date('h:m A', strtotime($req->schedule_datetime)); ?>
                                </td>
                                <td><?php echo $req->pickupAddress; ?></td>
                            </tr>
                            <?php
                            $cntrr++;
                            }
                            ?>

                            </tbody>
                        </table>
                </section>

            </div>
        </div>
    </div>
    </div>



@stop



@section('javascript')

    <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>


    <script>
        $(document).ready(function () {
            $('#example').DataTable();
        });
    </script>






@stop