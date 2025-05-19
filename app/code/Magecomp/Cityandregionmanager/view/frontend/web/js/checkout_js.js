require([
    "jquery",
    "mage/url",
    "jquery/ui",
    'mage/translate',
    "domReady!"
],
        function ($, url) {
            function load() {
                updateState();
                var states_select = $('div#shipping-new-address-form>div.state-drop-down>div.control>select.select');
                var cities_select = $('div#shipping-new-address-form>div.city-drop-down>div.control>select.select');
                var zip_select = $('div#shipping-new-address-form>div.postcode-drop-down>div.control>select.select');
                var zip_input = $('div#shipping-new-address-form>div.postcode-text-box>div.control>input.input');
                $('div.state-drop-down>div.control>select.select :nth-child(1)').attr("selected", "selected");
                $('div.city-drop-down>div.control>select.select :nth-child(1)').attr("selected", "selected");
                $('div.postcode-drop-down>div.control>select.select :nth-child(1)').attr("selected", "selected");

                $('#shipping-new-address-form select[name=country_id]').change(function ()
                {
                    updateState();
                });
                $("#state_name_select_zip").change(function () {
                    if ($(this).val() != "") {
                        $("#city_name_select_zip").empty().append($('<option data-title="load" value="">'+$.mage.__('Load')+'...</option>'));
                    }
                    $.ajax({
                        url: url.build('magecomp_cityandregionmanager/ajax/getcities'),
                        type: 'post',
                        data: {'selected_state': $("#state_name_select_zip option:selected").text()},
                        dataType: 'json',
                        success: function (data) {
                            if (data.request == 'OK') {
                                $("#city_name_select_zip").empty().append($('<option data-title="Please Select..." value="">'+$.mage.__('Please Select')+'...</option>'));
                                $.each(data.result, function () {
                                    $("#city_name_select_zip").append($('<option data-title="' + this.cities_name + '" value="' + this.customcity + '">' + this.customcity + '</option>'));
                                });
                            } else {
                                $("#city_name_select_zip").empty().append($('<option data-title="' + data.result + '" value="' + data.result + '">' + data.result + '</option>'));
                            }
                        },
                        error: function (error) {
                            console.log(error);
                        }
                    });
                });
                states_select.change(function () {
                    var selected_state = $('div#shipping-new-address-form>div.state-drop-down>div.control>select.select :selected').text();
                    if ($(this).val() != "") {
                        cities_select.empty().append($('<option data-title="load" value="">'+$.mage.__('Load')+'...</option>'));
                    }
                    $.ajax({
                        url: url.build('magecomp_cityandregionmanager/ajax/getcities'),
                        type: 'post',
                        data: {'selected_state': selected_state},
                        dataType: 'json',
                        success: function (data) {
                            if (data.request == 'OK') {
                                cities_select.empty().append($('<option data-title="Please Select..." value="">'+$.mage.__('Please Select')+'...</option>'));
                                $.each(data.result, function () {
                                    cities_select.append($('<option data-title="' + this.cities_name + '" value="' + this.customcity + '">' + this.customcity + '</option>'));
                                });
                            } else {
                                cities_select.empty().append($('<option data-title="' + data.result + '" value="' + data.result + '">' + data.result + '</option>'));
                            }
                        },
                        error: function (error) {
                            console.log(error);
                        }
                    });
                });
                cities_select.change(function () {
                    var selected_city = $('div#shipping-new-address-form>div.city-drop-down>div.control>select.select :selected').text();
                    zip_select.empty().append($('<option data-title="load" value="">'+$.mage.__('Load')+'...</option>'));
                    $.ajax({
                        url: url.build('magecomp_cityandregionmanager/ajax/getzip'),
                        type: 'post',
                        data: {'selected_city': selected_city},
                        dataType: 'json',
                        success: function (data) {
                            if (data.request == 'OK') {
                                if (window.valuesConfig == 1) {
                                    jQuery('.postcode-text-box').show();
                                }
                                // if (window.valuesConfig == 0){

                                zip_select.empty().append($('<option data-title="Please Select..." value="">'+$.mage.__('Please Select')+'...</option>'));
                                $.each(data.result, function () {
                                    zip_select.append($('<option data-title="' + this.zip_code + '" value="' + this.zip_code + '">' + this.zip_code + '</option>'));
                                });

                            } else {
                                zip_select.empty().append($('<option data-title="' + data.result + '" value="' + data.result + '">' + data.result + '</option>'));
                            }


                        },
                        error: function (error) {
                            console.log(error);
                        }
                    });
                });
                $('#state-submit').click(function () {
                    var state = $('#state_name').val();
                    if (state.length > 0) {
                        $('#error').append(' ');
                        var button = $(this);
                        button.text('Saving...');
                        button.attr('disabled', true);
                        $.ajax({
                            url: url.build('magecomp_cityandregionmanager/ajax/addnewstate'),
                            type: 'post',
                            data: {'state_name': state},
                            dataType: 'json',
                            success: function (data) {
                                if (data.request == 'OK') {
                                    $("#popup-mpdal-state").modal("closeModal");
                                    button.text('Submit').attr('disabled', false);
                                    states_select.empty().append($('<option data-title="load" value="">'+$.mage.__('Load')+'...</option>'));
                                    //reload states after adding new states
                                    $.ajax({
                                        url: url.build('magecomp_cityandregionmanager/ajax/getstates'),
                                        type: 'post',
                                        dataType: 'json',
                                        success: function (data) {
                                            if (data.request == 'OK') {
                                                states_select.empty().append($('<option data-title="Please Select..." value="">'+$.mage.__('Please Select')+'...</option>'));
                                                $.each(data.result, function () {
                                                    states_select.append($('<option data-title="' + this.states_name + '" value="' + this.customstate + '">' + this.customstate + '</option>'));
                                                });
                                            } else {
                                                states_select.empty().append($('<option data-title="' + data.result + '" value="' + data.result + '">' + data.result + '</option>'));
                                            }
                                        },
                                        error: function (error) {
                                            console.log(error);
                                        }
                                    });
                                    //end reload states after adding new states
                                } else {
                                    $("#popup-mpdal-state").modal("closeModal");
                                }
                            },
                            error: function (error) {
                                console.log(error);
                            }
                        })
                    } else {
                        $('#error').append('<span style="color: red">Fill in required fields!</span>');
                    }
                });
                $('#city-submit').click(function () {
                    var state = $('#state_name_select option:selected').text();
                    var city = $('#city_name').val();
                    if (state.length > 0) {
                        $('#error').append(' ');
                        var button = $(this);
                        button.text('Saving...');
                        button.attr('disabled', true);
                        $.ajax({
                            url: url.build('magecomp_cityandregionmanager/ajax/addnewcity'),
                            type: 'post',
                            data: {'state_name': state, 'city_name': city},
                            dataType: 'json',
                            success: function (data) {
                                if (data.request == 'OK') {
                                    $("#popup-mpdal-city").modal("closeModal");
                                    button.text('Submit').attr('disabled', false);
                                    cities_select.empty().append($('<option data-title="load" value="">'+$.mage.__('Load')+'...</option>'));
                                    //reload cities after adding new states
                                    $.ajax({
                                        url: url.build('magecomp_cityandregionmanager/ajax/getcities'),
                                        type: 'post',
                                        data: {'selected_state': $("#state_name_select option:selected").text()},
                                        dataType: 'json',
                                        success: function (data) {
                                            if (data.request == 'OK') {
                                                cities_select.empty().append($('<option data-title="Please Select..." value="">'+$.mage.__('Please Select')+'...</option>'));
                                                $.each(data.result, function () {
                                                    cities_select.append($('<option data-title="' + this.cities_name + '" value="' + this.customcity + '">' + this.customcity + '</option>'));
                                                });
                                            } else {
                                                cities_select.empty().append($('<option data-title="' + data.result + '" value="' + data.result + '">' + data.result + '</option>'));
                                            }
                                        },
                                        error: function (error) {
                                            console.log(error);
                                        }
                                    });
                                    //end reload cities after adding new states
                                } else {
                                    $("#popup-mpdal-city").modal("closeModal");
                                }
                            },
                            error: function (error) {
                                console.log(error);
                            }
                        })
                    } else {
                        $('#error').append('<span style="color: red">Fill in required fields!</span>');
                    }
                });
                $('#zip-submit').click(function () {
                    var state = $('#state_name_select_zip option:selected').text();
                    var city = $('#city_name_select_zip option:selected').text();
                    var zip = $('#zip').val();
                    if (state.length > 0) {
                        $('#error').append(' ');
                        var button = $(this);
                        button.text('Saving...');
                        button.attr('disabled', true);
                        $.ajax({
                            url: url.build('magecomp_cityandregionmanager/ajax/addnewzip'),
                            type: 'post',
                            data: {'state_name': state, 'city_name': city, 'zip': zip},
                            dataType: 'json',
                            success: function (data) {
                                if (data.request == 'OK') {
                                    $("#popup-mpdal-zip").modal("closeModal");
                                    button.text('Submit').attr('disabled', false);
                                    zip_select.empty().append($('<option data-title="load" value="">'+$.mage.__('Load')+'...</option>'));
                                    //reload cities after adding new states
                                    $.ajax({
                                        url: url.build('magecomp_cityandregionmanager/ajax/getzip'),
                                        type: 'post',
                                        data: {'selected_city': city},
                                        dataType: 'json',
                                        success: function (data) {
                                            if (data.request == 'OK') {
                                                zip_select.empty().append($('<option data-title="Please Select..." value="">'+$.mage.__('Please Select')+'...</option>'));
                                                $.each(data.result, function () {
                                                    zip_select.append($('<option data-title="' + this.zip_code + '" value="' + this.zip_code + '">' + this.zip_code + '</option>'));
                                                });
                                            } else {
                                                zip_select.empty().append($('<option data-title="' + data.result + '" value="' + data.result + '">' + data.result + '</option>'));
                                            }
                                        },
                                        error: function (error) {
                                            console.log(error);
                                        }
                                    });
                                    //end reload zip after adding new states
                                } else {
                                    $("#popup-mpdal-zip").modal("closeModal");
                                }
                            },
                            error: function (error) {
                                console.log(error);
                            }
                        })
                    } else {
                        $('#error').append('<span style="color: red">Fill in required fields!</span>');
                    }
                });
                //Cart Page Started
                var cart_country_select = $('fieldset.estimate select[name=country_id]');
                var cart_states_select = $('fieldset.estimate>div.state-drop-down>div.control>select.select');
                var cart_cities_select = $('fieldset.estimate>div.city-drop-down>div.control>select.select');
                var cart_zip_select = $('fieldset.estimate>div.postcode-drop-down>div.control>select.select');
                var updateCartState = function () {
                    var selected_country = $('fieldset.estimate select[name=country_id]').val();

                    if (selected_country != "") {
                        cart_states_select.empty().append($('<option data-title="load" value="">'+$.mage.__('Load')+'...</option>'));
                    }
                    $.ajax({
                        url: url.build('magecomp_cityandregionmanager/ajax/getstates'),
                        type: 'post',
                        data: {'selected_country': selected_country},
                        dataType: 'json',
                        success: function (data) {
                            if (data.request == 'OK') {
                                cart_states_select.empty().append($('<option data-title="Please Select..." value="">'+$.mage.__('Please Select')+'...</option>'));
                                $.each(data.result, function () {
                                    cart_states_select.append($('<option data-title="' + this.states_name + '" value="' + this.customstate + '">' + this.customstate + '</option>'));
                                });
                            } else {
                                cart_states_select.empty().append($('<option data-title="' + data.result + '" value="' + data.result + '">' + data.result + '</option>'));
                            }
                        },
                        error: function (error) {
                            console.log(error);
                        }
                    });
                };

                updateCartState();
                cart_country_select.change(function () {
                    updateCartState();
                });

                cart_states_select.change(function (){
                    var cartselected_state = $('fieldset.estimate>div.state-drop-down>div.control>select.select :selected').text();
                    if ($(this).val() != "") {
                        cart_cities_select.empty().append($('<option data-title="load" value="">'+$.mage.__('Load')+'...</option>'));
                    }
                    $.ajax({
                        url: url.build('magecomp_cityandregionmanager/ajax/getcities'),
                        type: 'post',
                        data: {'selected_state': cartselected_state},
                        dataType: 'json',
                        success: function (data) {
                            if (data.request == 'OK') {
                                cart_cities_select.empty().append($('<option data-title="Please Select..." value="">'+$.mage.__('Please Select')+'...</option>'));
                                $.each(data.result, function () {
                                    cart_cities_select.append($('<option data-title="' + this.cities_name + '" value="' + this.customcity + '">' + this.customcity + '</option>'));
                                });
                            } else {
                                cart_cities_select.empty().append($('<option data-title="' + data.result + '" value="' + data.result + '">' + data.result + '</option>'));
                            }
                        },
                        error: function (error) {
                            console.log(error);
                        }
                    });
                });

                cart_cities_select.change(function () {
                    var cartselected_city = $('fieldset.estimate>div.city-drop-down>div.control>select.select :selected').text();
                    cart_zip_select.empty().append($('<option data-title="load" value="">'+$.mage.__('Load')+'...</option>'));
                    $.ajax({
                        url: url.build('magecomp_cityandregionmanager/ajax/getzip'),
                        type: 'post',
                        data: {'selected_city': cartselected_city},
                        dataType: 'json',
                        success: function (data) {
                            if (data.request == 'OK') {
                                cart_zip_select.empty().append($('<option data-title="Please Select..." value="">'+$.mage.__('Please Select')+'...</option>'));
                                $.each(data.result, function () {
                                    cart_zip_select.append($('<option data-title="' + this.zip_code + '" value="' + this.zip_code + '">' + this.zip_code + '</option>'));
                                });

                            } else {
                                cart_zip_select.empty().append($('<option data-title="' + data.result + '" value="' + data.result + '">' + data.result + '</option>'));
                            }
                        },
                        error: function (error) {
                            console.log(error);
                        }
                    });
                });
                //Cart Page End
            }
            var search = function () {
                var elem = $("div.state-drop-down select.select").text();
                if (elem.length > 1) {
                    load();
                    clearInterval(intervalID);
                }
            };
            var intervalID = setInterval(search, 500);
            var updateState = function () {
                var states_select = $('div#shipping-new-address-form>div.state-drop-down>div.control>select.select');
                var selected_country = $('#shipping-new-address-form select[name=country_id]').val();

                if (selected_country != "") {
                    states_select.empty().append($('<option data-title="load" value="">'+$.mage.__('Load')+'...</option>'));
                }
                $.ajax({
                    url: url.build('magecomp_cityandregionmanager/ajax/getstates'),
                    type: 'post',
                    data: {'selected_country': selected_country},
                    dataType: 'json',
                    success: function (data) {
                        if (data.request == 'OK') {
                            states_select.empty().append($('<option data-title="Please Select..." value="">'+$.mage.__('Please Select')+'...</option>'));
                            $.each(data.result, function () {
                                states_select.append($('<option data-title="' + this.states_name + '" value="' + this.customstate + '">' + this.customstate + '</option>'));
                            });
                        } else {
                            states_select.empty().append($('<option data-title="' + data.result + '" value="' + data.result + '">' + data.result + '</option>'));
                        }
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            };
        });
