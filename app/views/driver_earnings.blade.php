@extends('layout')

@section('content')



    <!--  Summary end -->
    <!-- filter start -->
    <div class="box box-danger">
        <div class="box-header">
            <h3 class="box-title">Filter</h3>
        </div>
        <div class="box-body">
            <div class="row">
                <form role="form" method="get" action="{{ URL::Route('DriverEarnings') }}">
                    <div class="col-md-6 col-sm-6 col-lg-6">
                        <input type="text" class="form-control" style="overflow:hidden;" id="start-date"
                               name="start_date" value="{{ Input::get('start_date') }}" placeholder="Start Date">
                        <br>
                    </div>

                    <div class="col-md-6 col-sm-6 col-lg-6">
                        <input type="text" class="form-control" style="overflow:hidden;" id="end-date" name="end_date"
                               placeholder="End Date" value="{{ Input::get('end_date') }}">
                        <br>
                    </div>
            </div>
        </div><!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" name="submit" class="btn btn-primary" value="Filter_Data">Filter Data</button>
            <!--<button type="submit" name="submit" class="btn btn-primary" value="Download_Report">Download Report</button>-->
        </div>

        </form>

    </div>

    <!-- filter end-->
    <div class="box box-info tbl-box">
        <table id="example" class="display" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>S.no</th>
                <th>Driver Name</th>
                <th>Driver Payment</th>
                <th>Total Trip</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $i = 1;
            foreach($walkers as $key => $walker) { ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $walker[1]; ?></td>
                <td><?php echo $walker[2]; ?></td>
                <td><?php echo $walker[3]; ?></td>
            </tr>
            <?php $i++; } ?>
            </tbody>
        </table>
    </div>
    <!--</form>-->


    <script>
        var $j = jQuery.noConflict();
        $j(function () {
            $j("#start-date").datepicker({
                //defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 1,
                onClose: function (selectedDate) {
                    $j("#end-date").datepicker("option", "minDate", selectedDate);
                }
            });
            $j("#end-date").datepicker({
                //defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 1,
                maxDate: new Date(),
                onClose: function (selectedDate) {
                    $j("#start-date").datepicker("option", "maxDate", selectedDate);
                }
            });
        });
    </script>

    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/r/bs-3.3.5/jq-2.1.4,dt-1.10.8/datatables.min.css"/>

    <script type="text/javascript"
            src="https://cdn.datatables.net/r/bs-3.3.5/jqc-1.11.3,dt-1.10.8/datatables.min.js"></script>
    <script type="text/javascript" charset="utf-8">
        var $k = jQuery.noConflict();
        $k(document).ready(function () {
            $k('#example').DataTable();
        });
    </script>


    <script type="text/javascript">
        // For demo to fit into DataTables site builder...
        $('#example')
                .removeClass('display')
                .addClass('table table-striped table-bordered');
    </script>
    <style>
        html, body {
            font-family: 'Source Sans Pro', sans-serif;
            font-size: 15px !important;
            color: #0C020A !important;
        }
    </style>
@stop