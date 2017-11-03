$(function () {
    $('[data-toggle="tooltip"]').tooltip();

    //global variables
    var a1 = new ajax(); //ajax object
    var pen_active=false; //pen tool status
    var history=[]; //history array
    var map1 = new map(-34.397, 150.644); //map object
    var compeleted = false; //shape status
    var all_ploygon = []; //ploygon array
    var shape_len; //shape length
    var lis = new listen(); //lister object


    /* listen to the tilesloaded event
     if that is triggered, google maps is loaded successfully for sure */
    google.maps.event.addListener(map1.newmap, 'tilesloaded', function() {

        $('#auto_input,#drop').show();
        var input = document.getElementById('auto_input');
        var tool = document.getElementById('drop');
        //content inside the map
        map1.newmap.controls[google.maps.ControlPosition.TOP_RIGHT].push(tool);
        map1.newmap.controls[google.maps.ControlPosition.TOP_CENTER].push(input);

        //hide loader
        $('#loader').animate({'display':'none','z-index':"0"},800);
        $('#auto_input').css("margin-top","1%");

        //clear the listener, we only need it once
        google.maps.event.clearListeners(map1.newmap, 'tilesloaded');
    });

    //submit off for enter key
    $('#main-form').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });

    //ctrl+z undo
    $('#map').keydown(function (e) {

        if(e.keyCode == 90 && e.ctrlKey)
        {
            var t = new tools();
            compeleted?t.undo():"";
        }
    });


    //right click menu
    $('#map').mousedown(function (e) {
        if(e.button == 2 && pen_active)
        {
            $('#side-menu').css({"top":e.pageY-105,"left":e.pageX-262}).show();
        }
        if(e.button == 1)
        {
            $('#side-menu').css({"display":"none"});
        }
    });


    //animation for input
    $('#auto_input').focusin(function(e)
    {

        $(this).animate({top:"20px"},"slow");
    });

    //animation for input
    $('#auto_input').focusout(function(e)
    {

        $(this).animate({top:"0px"},"slow");
    });


    //click event for tools
    $('.tool1').click(function (e) {
       // $('.tool1').css('background-color','white');
        $(this).css('background-color','#e63b3b');
        $(this).find("i").css('color','white');
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
            case "reset":
                t.reset();
        }
    });




    //edit form
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
        //send ajax for edit
        a1.send(url, 'post', data, a1.success, a1.error);
        return false;

    });


        //add zone
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

        //check zone is present or not
        if ((all_ploygon == '' || typeof all_ploygon != 'object') && compeleted) {
            alert('Draw your zone in map using pen tool');
            return false;
        }

        var data = $(this).serialize() + '&zone_json=' + JSON.stringify(all_ploygon);
        //send zone data to add
        a1.send(url, 'post', data, a1.success, a1.error);
        return false;
    });



    //custom validate functions ---start
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
    //custom validate function ----end


    //tools functions -----start
    function tools() {

    }

    tools.prototype.drawcondition = function (tool) {
        if (!compeleted) {
            if(pen_active)
            {
                this.reset();
            }
            else {
                switch (tool) {
                    case 'pen':
                        this.setpen();
                        break;
                    default:
                        this.setpen();
                        break;
                }
            }

        }
        else {
            alert('Can\'t Draw more than one Zone Area');
            lis.toolcolorchange();
        }
    };

    //setpen
    tools.prototype.setpen = function () {
        var polyOptions = {
            strokeWeight: 0,
            fillOpacity: 0.45,
        };
        map1.drawingManager = new google.maps.drawing.DrawingManager({
            drawingControl: false,
            polygonOptions: polyOptions
        });
        map1.drawingManager.setOptions({
            drawingMode: google.maps.drawing.OverlayType.POLYGON
        });
        map1.drawingManager.setMap(map1.newmap);
        google.maps.event.addListener(map1.drawingManager, 'overlaycomplete', test);
        pen_active=true;
    };

    //reset
    tools.prototype.reset = function () {
        map1.drawingManager.setMap(null);
       lis.toolcolorchange();
        $('#side-menu').hide();
        pen_active=false;
    };


   /* tools.prototype.setrect = function () {

        map1.drawingManager.setOptions({
            drawingMode: google.maps.drawing.OverlayType.RECTANGLE
        });
        map1.drawingManager.setMap(map1.newmap);
    };
*/


   //delete
    tools.prototype.shapedelete = function () {
        if (confirm("Sure you want to delete this zone")) {
            map1.mapPoly.setMap(null);
            this.showtool();
            all_ploygon = [];
            compeleted = false;
            shape_len = 0;
            history = [];
           lis.toolcolorchange();
        }

    };

    //showtool
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



    //undo
    tools.prototype.undo = function () {
        if (history.length > 0) {
           var len = map1.mapPoly.getPath().getLength();
            for(i = 0; i < len; i++)
            {
                map1.mapPoly.getPath().pop();
            }
            len=history.length;
            var undo = history[len-1];
            var maplat = [];
            for (i = 0; i < undo.length; i++) {
                maplat.push(new google.maps.LatLng(undo[i].lat, undo[i].lng));
            }
            map1.draw(maplat);
            history.pop();
            lis.setarray(map1.mapPoly,true);
            google.maps.event.addListener(map1.mapPoly.getPath(), "insert_at", lis.setarray);
            //google.maps.event.addListener(map1.mapPoly.getPath(), "remove_at", lis.setarray);
            google.maps.event.addListener(map1.mapPoly.getPath(), "set_at", lis.setarray);
            lis.toolcolorchange();
        }

    };
    //tool function ----end





    //callback for completed drawing shape
    function test(e) {
       lis.toolcolorchange();
        pen_active=false;
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
        //google.maps.event.addListener(map1.mapPoly.getPath(), "remove_at", lis.setarray);
        google.maps.event.addListener(map1.mapPoly.getPath(), "set_at", lis.setarray);
        compeleted = true;
    }



    //map function ------start
    function map(lat, long) {

        //new map
        this.newmap = new google.maps.Map(document.getElementById('map'), {
            center: {lat: lat, lng: long},
            zoom: 8
        });

        var input = document.getElementById('auto_input');


        //auto complete
        this.autocomplete= new google.maps.places.Autocomplete(input);
        this.autocomplete.addListener('place_changed', getaddress);


        this.mapPoly = null;
    }



    //callback for auto complete
    function getaddress()
    {
        var place = map1.autocomplete.getPlace();
            map1.newmap.setCenter(place.geometry.location);
            map1.newmap.setZoom(15);
    }

    //polygon draw
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

    //view draw
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

    //map function ----end


    //listen function ----start
    function listen() {
    }

    //check status of history menu
    listen.prototype.checkhistory = function () {
        if(history.length > 0)
        {
            $('#undo').show();

        }
        else {
            $('#undo').hide();
        }
    };

    //setarray
    listen.prototype.setarray = function (shape,status) {
        if (typeof shape !== 'object') {
            shape = map1.mapPoly;
        }
        var len = shape.getPath().getLength();
        var htmlStr = "";
        if((!status || typeof status === 'object') && all_ploygon.length > 0)
        {
            var history_len=history.length;
                history[history_len] = all_ploygon;
        }
        lis.checkhistory();
        all_ploygon = [];
        for (var i = 0; i < len; i++) {
            htmlStr = shape.getPath().getAt(i).toJSON();
            all_ploygon.push(htmlStr);
            //history[history_len].push(htmlStr);
        }
    };

    listen.prototype.toolcolorchange =  function()
    {
        $('.tool1').animate({'background-color':'white'},600);
        $('.tool1').find("i").animate({'color':'black'},600);
    };



    //listen function ------end


    //ajax function -------start
    function ajax() {

    }

    //ajax sender
    ajax.prototype.send = function (url, type, data, success, error) {
        $.ajax({
            url: url,
            data: data,
            type: type,
            success: success,
            error: error
        });
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

    ajax.prototype.usuccess = function (response) {
        //alert(response);
        console.log(response.zone);
        var res = JSON.parse(response.zone);
        var maplat = [];
        for (i = 0; i < res.length; i++) {
            maplat.push(new google.maps.LatLng(res[i].lat, res[i].lng));
        }
        map1.view_draw(maplat);
        map1.newmap.setCenter(new google.maps.LatLng(res[0].lat, res[0].lng));
        map1.newmap.setZoom(13);

    };


    function show_map() {
        var a5 = new ajax();
        var formData = {
            'zoneId': $("#zone-view #zone_id").attr("value") //for get zone_id
        };
        a5.send($("#zone-view").attr("action"), "post", formData, a5.usuccess, a5.error);
    }

    show_map();

});






