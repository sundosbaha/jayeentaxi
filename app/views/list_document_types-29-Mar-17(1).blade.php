@extends('layout')

@section('content')

    <!-- will be used to show any messages -->
    @if (Session::has('message'))
        <div class="alert alert-success">{{ Session::get('message') }}</div>
    @endif

    <div class="col-md-12 col-sm-12">
        <a id="adddoc" href="{{ URL::Route('AdminDocumentTypesEdit', 0) }}">
            <button type="submit" class="btn btn-flat btn-block btn-success" id="newdoc" value="Add New Document Type">
                Add New Document Type
            </button>
        </a>
    </div>
    <br/><br/>


    <div class="box box-danger">

        <form method="get" action="{{ URL::Route('/admin/searchdoc') }}">
            <div class="box-header">
                <h3 class="box-title">Filter</h3>
            </div>
            <div class="box-body row">

                <div class="col-md-6 col-sm-12">

                    <select id="searchdrop" class="form-control" name="type">
                        <option value="docid" id="docid">ID</option>
                        <option value="docname" id="docname">Name</option>
                    </select>


                    <br>
                </div>
                <div class="col-md-6 col-sm-12">


                    <input class="form-control" type="text" name="valu" id="insearch" placeholder="keyword"/>
                    <br>
                </div>

            </div>

            <div class="box-footer">


                <button type="submit" id="btnsearch" class="btn btn-flat btn-block btn-success serach_blad">Search
                </button>


            </div>
        </form>

    </div>



    <div class="box box-info tbl-box">
        <div align="left"
             id="paglink"><?php echo $types->appends(array('type' => Session::get('type'), 'valu' => Session::get('valu')))->links(); ?></div>
        <table class="table table-bordered">
            <tbody>
            <tr>
                <th>ID</th>
                <th>Document Name</th>
                <th>Driver Name</th>
                <th>Actions</th>

            </tr>
            <?php if(count($types) > 0) :
            foreach ($types as $key => $type) { ?>
            <tr>
                <td><?= $type->id ?></td>
                <td><?= $type->name ?>
                <td><?php
                    $walkers = DB::table('walker')
                            ->select('id', DB::raw('CONCAT(last_name, " ", first_name) AS walkerName'))
                            ->where('id', '=', $type->walker_id)
                            ->get();
                    echo(($walkers[0]->walkerName) ? $walkers[0]->walkerName : '');
                    ?>
                    <?php if($type->is_default){ ?>
                    <font style="color:green">(Default)</font>
                    <?php } ?>
                </td>
                <td>
                    <a id="<?php echo $type->id;?>" class="DocumentImageHide" href="#"><input type="button"
                                                                                              class="btn btn-success"
                                                                                              value="Document View"></a>
                    <a id="edit" href="{{ URL::Route('AdminDocumentTypesEdit', $type->id) }}"><input type="button"
                                                                                                     class="btn btn-success"
                                                                                                     value="Edit"></a>
                    <?php if(!$type->is_default){ ?><a id="delete"
                                                       href="{{ URL::Route('AdminDocumentTypesDelete', $type->id) }}"><input
                                type="button" class="btn btn-danger" value="Delete"></a><?php } ?></td>
            </tr>
            <?php
            $DocumentImages = DB::table('document_images')
                    ->select('id', 'image')
                    ->where('document_id', '=', $type->id)
                    ->get();
            foreach($DocumentImages as $key => $DocumentImage) {
            ?>
            <tr class="DocumentImage_<?php echo $type->id;?>" style="display:none">
                <td>
                    <div class="DocumentImage_<?php echo $type->id;?>" style="display:none">
                        <ul>
                            <li><img src="<?php echo $DocumentImage->image; ?>" with="100" height="100"/></li>
                        </ul>
                    </div>
                </td>
            </tr>
            <?php  } ?>

            <?php } ?>
            <?php else : ?>
            <tr>
                <td colspan="4">No Records Found</td>
            </tr>
            <?php endif; ?>
            </tbody>
        </table>

        <div align="left"
             id="paglink"><?php echo $types->appends(array('type' => Session::get('type'), 'valu' => Session::get('valu')))->links(); ?></div>

    </div>


    <script type="text/javascript">
        jQuery(document).ready(function () {

            jQuery(".DocumentImageHide").click(function (e) {
                //alert(jQuery(this).attr('id'));
                e.preventDefault();
                var id = jQuery(this).attr('id');
                jQuery(".DocumentImage_" + id).slideToggle();
            });

            /*jQuery(".btn-danger").click(function(e) {

             ///alert('fdsafdsaf'); return false;

             var result = confirm('Are you sure you want to delete?');

             if (result) {
             return true;
             } else {
             return false;
             }

             });*/

        });
    </script>

    <style>
        ul {
            list-style-type: none;
        }
    </style>
@stop
