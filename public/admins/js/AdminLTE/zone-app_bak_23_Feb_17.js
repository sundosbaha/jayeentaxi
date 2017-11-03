$(function () {
    $('[data-toggle="tooltip"]').tooltip();
    var a1 = new ajax();
    $('#main-form-edit').submit(function (e) {
        var url = $(this).attr("action");
        var test = $(this)[0];
        var len = test.length;
        var c = new custom_validate();
        for (var i = 0; i < len; i++) {
            switch (test[i].name) {
                case "zone_name":
                case "type[]":
                case "typeid[]":
                case "service_visible[]":
                    if (!c.czone_json(test[i], i, test[i].value)) {
                        return false;
                    }
                    break;
                case "service_base_price[]":
                case "service_price_distance[]":
                case "service_price_time[]":
                case "service_max_size[]":
                    if (!c.cservice_base_price(test[i], i, test[i].value)) {
                        return false;
                    }
                    break;
            }
        }

        var data = $(this).serialize();
        a1.send(url, 'post', data, a1.success, a1.error);
        return false;

    });

    $('#main-form').submit(function (e) {
        var url = $(this).attr("action");
        var test = $(this)[0];
        var len = test.length;
        var c = new custom_validate();
        for (var i = 0; i < len; i++) {
            switch (test[i].name) {
                case "zone_name":
                case "type[]":
                case "typeid[]":
                case "service_visible[]":
                    if (!c.czone_json(test[i], i, test[i].value)) {
                        return false;
                    }
                    break;
                case "service_base_price[]":
                case "service_price_distance[]":
                case "service_price_time[]":
                case "service_max_size[]":
                case "service_base_distance[]":
                    if (!c.cservice_base_price(test[i], i, test[i].value)) {
                        return false;
                    }
                    break;
            }
        }
        if (all_ploygon == '' || typeof all_ploygon != 'object') {
            alert('Draw your zone in map using pen tool');
            return false;
        }

        var data = $(this).serialize() + '&zone_json=' + JSON.stringify(all_ploygon);
        a1.send(url, 'post', data, a1.success, a1.error);
        return false;
    });

    function custom_validate() {

    }

    custom_validate.prototype.required = function (value) {
        return value ? true : false;
    };
    custom_validate.prototype.isnum = function (value) {
        if (!isNaN(value)) {
            return true;
        } else {
            return false;
        }
    };

    custom_validate.prototype.error = function (obj, index, error) {
        /*var keyword=this.msgcon(index);
         if(index <= 3)
         {
         alert(keyword.msg);
         }
         else {
         alert(keyword.row_name+" "+keyword.msg+" "+error=='re'?"is required":"is not integer");
         }*/
        $(obj).focus();
        if (obj.name == 'zone_name') {
            alert('zone name is required');
        }
        else {
            var type = $(obj).attr('data-type');
            var name = $(obj).attr('title');
            var msg;
            if (error == 're') {
                msg = 'is required';
            } else {
                msg = 'is not a integer';
            }
            alert(type + ' ' + name + ' ' + msg);
        }
        return false;
    };
    custom_validate.prototype.czone_json = function (obj, index, value) {
        if (!this.required(value)) {
            return this.error(obj, index, 're')
        }
        return true;
    };
    custom_validate.prototype.cservice_base_price = function (obj, index, value) {
        var id = $(obj).attr('data-id');
        if ($('#visible_' + id)[0].checked) {
            if (!this.required(value)) {
                return this.error(obj, index, 're')
            }
            if (!this.isnum(value)) {
                return this.error(obj, index, 'num')
            }
        }

        return true;
    };
    var map1 = new map(11.0174388, 76.9844677);
    var compeleted = false;
    var all_ploygon = [];
    var shape_len;
    var last_path = [];
    var lis = new listen();
    $('.tool1').click(function (e) {
        e.preventDefault();
        var tool = $(this).attr('data-id');
        var t = new tools();
        switch (tool) {
            case "pen":
                t.drawcondition(tool);
                break;
            case "undo":
                t.undo();
                break;
            case "delete":
                t.shapedelete();
                break;
            default:
                t.drawcondition(tool);
                break;
        }
    });

    function tools() {

    }

    tools.prototype.drawcondition = function (tool) {
        if (!compeleted) {
            switch (tool) {
                case 'pen':
                    this.setpen();
                    break;
                default:
                    this.setpen();
                    break;
            }
        }
        else {
            alert('Can\'t Draw more than one Zone Area');
        }
    };

    tools.prototype.setpen = function () {

        map1.drawingManager.setOptions({
            drawingMode: google.maps.drawing.OverlayType.POLYGON
        });
        map1.drawingManager.setMap(map1.newmap);
    };

    tools.prototype.setrect = function () {

        map1.drawingManager.setOptions({
            drawingMode: google.maps.drawing.OverlayType.RECTANGLE
        });
        map1.drawingManager.setMap(map1.newmap);
    };

    tools.prototype.shapedelete = function () {
        if (confirm("Sure you want to delete this zone")) {
            map1.mapPoly.setMap(null);
            this.showtool();
            all_ploygon = [];
            compeleted = false;
            shape_len = 0;
        }

    };

    tools.prototype.showtool = function () {
        $('.tmp-tool').toggle();
    };
    tools.prototype.checkundotool = function () {
        if (map1.mapPoly != null) {
            if (shape_len < map1.mapPoly.getPath().getLength()) {
                if ($('.undo-tool').css('display') == 'none') {
                    $('.undo-tool').css('display', 'block');
                }
            }
            else {
                if ($('.undo-tool').css('display') != 'none') {
                    $('.undo-tool').css('display', 'none');
                }
            }
        }

    };
    tools.prototype.undo = function () {
        if (shape_len < map1.mapPoly.getPath().getLength()) {
            var len = map1.mapPoly.getPath().getLength() - 1;
            var last = map1.mapPoly.getPath().getAt(len);
            last_path.push(last);
            map1.mapPoly.getPath().pop();
            lis.setarray('undo');
        }

    };


    google.maps.event.addListener(map1.drawingManager, 'overlaycomplete', test);

    function test(e) {
        var shape = e.overlay;
        var t = new tools();
        t.showtool();
        lis.setarray(shape);
        if (e.type != 'marker') {
            map1.drawingManager.setDrawingMode(null);
        }
        var maplat = [];
        for (i = 0; i < all_ploygon.length; i++) {
            maplat.push(new google.maps.LatLng(all_ploygon[i].lat, all_ploygon[i].lng));
        }
        shape_len = shape.getPath().getLength();
        map1.draw(maplat);
        shape.setMap(null);

        google.maps.event.addListener(map1.mapPoly.getPath(), "insert_at", lis.setarray);
        google.maps.event.addListener(map1.mapPoly.getPath(), "remove_at", lis.setarray);
        google.maps.event.addListener(map1.mapPoly.getPath(), "set_at", lis.setarray);
        compeleted = true;
    }

    function map(lat, long) {
        this.newmap = new google.maps.Map(document.getElementById('map'), {
            center: {lat: lat, lng: long},
            zoom: 11
        });
        var polyOptions = {
            strokeWeight: 0,
            fillOpacity: 0.45,
        };
        this.drawingManager = new google.maps.drawing.DrawingManager({
            drawingControl: false,
            polygonOptions: polyOptions
        });
        this.mapPoly = null;
    }

    map.prototype.draw = function (lat) {
        map1.mapPoly = new google.maps.Polygon({
            paths: lat,
            editable: true,
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#FF0000',
            fillOpacity: 0.35
        });
        map1.mapPoly.setMap(map1.newmap);
    };


    map.prototype.view_draw = function (lat) {
        map1.mapPoly1 = new google.maps.Polygon({
            paths: lat,
            editable: false,
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#FF0000',
            fillOpacity: 0.35
        });
        map1.mapPoly1.setMap(map1.newmap);
    };


    function listen() {
        this.selected_shape = null;
    }


    listen.prototype.setarray = function (shape) {
        if (typeof shape !== 'object') {
            shape = map1.mapPoly;
        }
        var len = shape.getPath().getLength();
        var htmlStr = "";
        var tmp = all_ploygon;
        all_ploygon = [];
        for (var i = 0; i < len; i++) {
            htmlStr = shape.getPath().getAt(i).toJSON();
            all_ploygon.push(htmlStr);
        }
    };

    listen.prototype.selectshape = function (shape) {
        if (shape.type !== 'marker') {
            lis.clearshape();
            lis.selected_shape = shape;
            shape.setEditable(true);
        }

    };

    listen.prototype.clearshape = function () {
        if (lis.selected_shape) {
            if (selectedShape.type !== 'marker') {
                lis.selectedShape.setEditable(false);
            }
            selectedShape = null;
        }
    };

    function ajax() {

    }

    ajax.prototype.send = function (url, type, data, success, error) {
        $.ajax({
            url: url,
            data: data,
            type: type,
            success: success,
            error: error
        });
    };


    ajax.prototype.usuccess = function (response) {
        //alert(response);
        console.log(response.zone);
        var res = JSON.parse(response.zone);
        var maplat = [];
        for (i = 0; i < res.length; i++) {
            maplat.push(new google.maps.LatLng(res[i].lat, res[i].lng));
        }
        map1.view_draw(maplat);

    };

    ajax.prototype.success = function (response, status) {

        var getUrl = window.location;
        var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/" + getUrl.pathname.split('/')[2] + "/admin/zonedivisions";

        if (status == 'success') {
            if(response.type == 'new')
            {
                alert('Added Successfully');
                window.location.href = baseUrl;
            }
            else {
                alert('Update Successfully');
                window.location.href = baseUrl;
            }
        }
        else {
            alert('Something went wrong');
        }

    };
    ajax.prototype.error = function (a, b, c) {
        console.log(a);
        console.log(b);
        console.log(c);
    };


    function show_map() {
        var a5 = new ajax();
        var formData = {
            'zoneId': $("#zone-view #zone_id").attr("value") //for get zone_id
        };
        a5.send($("#zone-view").attr("action"), "post", formData, a5.usuccess, a5.error);
    }

    show_map();

    /*
     function zone_view() {

     }


     var zoneId = $("#zone-view #zone_id").attr("value");

     var url = $("#zone-view").attr("action");

     var data = "zone_id="+zoneId;

     var formData = {
     'zoneId': $("#zone-view #zone_id").attr("value") //for get zone_id
     };
     //console.log(formData);


     //alert(zoneId+" "+url+ " " + data);

     //alert(url);


     zone_view.send(url, 'post', formData, a1.success, a1.error);

     ajax.prototype.send = function (url, type, data, success, error) {
     $.ajax({
     url: url,
     data: data,
     type: type,
     success: success,
     error: error
     });
     };

     */



});






