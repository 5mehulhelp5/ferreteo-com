require([
        "jquery"
    ],
    function($) {
        function load() {
            var states_select = $('div.states-select>div.admin__field-control>select.admin__control-select');
            var cities_select = $('div.cities-select>div.admin__field-control>select.admin__control-select');

            if(states_select.val())
            {
                var selected_state = states_select.val();
                var selected_city = cities_select.val();
                cities_select.empty().append($('<option data-title="load" value="0">Load...</option>'));
                $.ajax({
                    url: $("#getcitiesUrl").val(),
                    type: 'post',
                    data: {'selected_state' : selected_state},
                    dataType: 'json',
                    showLoader : true,
                    success: function (data) {
                        if(data.request == 'OK') {
                            cities_select.empty();
                            cities_select.append( $('<option data-title="-" value="">Please Select...</option>'));
                            $.each(data.result, function() {
                                var select_city = "";
                                if(selected_city==this.cities_name)
                                    select_city = "selected";
                                cities_select.append( $('<option data-title="'+this.cities_name+'" value="'+this.cities_name+'" '+select_city+'>'+this.cities_name+'</option>'));
                            });
                        }else{
                            cities_select.empty();
                            cities_select.append( $('<option data-title="'+data.result+'" value="">'+data.result+'</option>'));
                        }
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            }
            states_select.change(function () {
                var selected_state = $(this).val();
                cities_select.empty().append($('<option data-title="load" value="0">Load...</option>'));
                $.ajax({
                    url: $("#getcitiesUrl").val(),
                    type: 'post',
                    data: {'selected_state' : selected_state},
                    dataType: 'json',
                    success: function (data) {
                        if(data.request == 'OK') {
                            cities_select.empty();
                            cities_select.append( $('<option data-title="-" value="">Please Select...</option>'));
                            $.each(data.result, function() {
                                cities_select.append( $('<option data-title="'+this.cities_name+'" value="'+this.cities_name+'">'+this.cities_name+'</option>'));
                            });

                        }else{
                            cities_select.empty();
                            cities_select.append( $('<option data-title="'+data.result+'" value="">'+data.result+'</option>'));
                        }
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            })

        }
        setTimeout(load, 1000);
    });