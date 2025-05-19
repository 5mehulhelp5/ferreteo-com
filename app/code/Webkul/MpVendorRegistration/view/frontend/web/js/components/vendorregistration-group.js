/**
 * Webkul Software
 *
 * @category  Webkul
 * @package   Webkul_MpVendorRegistration
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
/*jshint browser:true jquery:true*/
/*global alert*/
define([
    'jquery',
    'uiComponent',
    'mage/validation',
    'Magento_Ui/js/modal/alert',
    'ko',
    'mage/translate',
    'mage/url',
    'mage/calendar',
    'Magento_Captcha/js/captcha',
    'Magento_Captcha/js/model/captcha'
], function ($j, Component, validation, alertBox, ko, $t, urlBuilder) {

    console.log('here');
    'use strict';
    var groups = window.vendorRegistrationConfig.groups;
    var attributes = window.vendorRegistrationConfig.attributes;
    var allGroups = [];
    var formData = [];
    return Component.extend({
        defaults: {
            template: 'Webkul_MpVendorRegistration/view/vendor-attribute-group',
            allFields: [],
            groupName: '',
            groupCode: '',
            groupLowerName: '',
            progressStatus: '',
            setSuccess:false,
            successText: '',
            countryData: JSON.parse(window.countryhtmldata),
            displaySelect: 'none',
            displayInput: 'block',
            regionData: [],
            addressFields : window.addressFields,
            streetData: JSON.parse(window.addressFields.street),
            passwordValidation: window.passwordValidation,
            backBtnClass: 'wk-back-btn disabled',
            addCaptcha : "",
            proceedBtnClass: 'wk-proceed-btn action login primary',
            proceedBtnText: $t('Proceed')
        },
        initialize: function () {
            var self = this;
            this._super();
            self.progressStatus(false);
            if (window.location.hash.substr(1) == 'register-as-seller') {
                window.location.hash = 'personal_info';
            }
            if (window.location.hash.substr(1)) {
                $j.each(groups, function (i,v) {
                    if (window.location.hash.substr(1) == v.lower_group_name) {
                        self.groupName(v.group_name);
                        self.groupCode(v.lower_group_name);
                        self.groupLowerName(v.lower_group_name);
                        self.allFields(self._createAttributes(v.group_id));
                        self._refreshElements();
                        window.location.hash = groups[i].lower_group_name;
                        if (i == 0) {
                            self.backBtnClass('wk-back-btn disabled');
                            self.proceedBtnClass('wk-proceed-btn action login primary');
                        } else if (i == groups.length-1) {
                            self.addCaptcha(true);
                            setTimeout(function(){
                            $j("#captch_webkul_vendor").appendTo("#vendor_captcha-data");
                            $j("#captch_webkul_vendor").css("display","block");
                            },500);
                            self.backBtnClass('wk-back-btn');
                            self.proceedBtnClass('wk-proceed-btn action login primary wk-register-seller');
                            self.proceedBtnText('Done');
                        } else {
                            self.backBtnClass('wk-back-btn');
                            self.proceedBtnClass('wk-proceed-btn action login primary');
                        }
                    }
                });
            } else {
                self.groupName(groups[0].group_name);
                self.groupCode(groups[0].group_code);
                self.groupLowerName(groups[0].lower_group_name);
                self.allFields(self._createAttributes(groups[0].group_id));
                self._refreshElements();
                window.location.hash = groups[0].lower_group_name;
            }
            $j('body').on('click', '.white-space-pre-line', function (e) {
                e.preventDefault();
            })

            /**
             * file type validation
             */
            $j('body').on('change', '.custom_file', function () {
                var self = $j(this);
                var ext_arr = self.attr("data-allowed").split(",");
                var new_ext_arr = [];
                for (var i = 0; i < ext_arr.length; i++) {
                    new_ext_arr.push(ext_arr[i]);
                    new_ext_arr.push(ext_arr[i].toUpperCase());
                }
                if (new_ext_arr.indexOf(self.val().split("\\").pop().split(".").pop()) < 0) {
                    self.val('');
                    $j('<div />').html('Invalid Extension. Allowed extensions are '+self.attr("data-allowed"))
                    .modal({
                        title: 'Attention!',
                        autoOpen: true,
                        buttons: [{
                            text: 'Ok',
                            attr: {
                                'data-action': 'cancel'
                            },
                            'class': 'action',
                            click: function () {
                                    self.val('');
                                    this.closeModal();
                                }
                        }]
                    });
                } else {
                    var formData = new FormData();
                    formData.append('image', self[0].files[0]);
                    formData.append('allowedTypes', new_ext_arr);
                    $j('body').trigger('processStart');
                    $j.ajax({
                        type: "POST",
                        url: urlBuilder.build("vendorregistration/seller/upload"),
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            $j('body').trigger('processStop');
                            if (response.error) {
                                self.val('');
                                $j('<div />').html(response.msg)
                                .modal({
                                    title: 'Attention!',
                                    autoOpen: true,
                                    buttons: [{
                                        text: 'Ok',
                                        attr: {
                                            'data-action': 'cancel'
                                        },
                                        'class': 'action',
                                        click: function () {
                                                this.closeModal();
                                            }
                                    }]
                                });
                            } else {
                                self.attr('src', response.data.name);
                                $j('<div />').html(response.msg);
                            }
                        },
                        error: function (response) {
                            alertBox({
                                content: $t('There was error during verifying seller shop data')
                            });
                        }
                    });
                }
            });

            /**
             * shop url validation
             */
            $j('body').on('keyup', '#profileurl', function () {
                var shopUrlVal = $j(this).val();
                $j('#profileurl').val(shopUrlVal.replace(/[^a-z^A-Z^0-9\.\-]/g,''));
            });

            /**
             * shop url availability chek
             */
            $j('body').on('change', '#profileurl', function () {
                var self = this;
                var shopUrlVal = $j('#profileurl').val();
                $j('.available').remove();
                $j('.unavailable').remove();
                if (shopUrlVal) {
                    $j('#wk-load').removeClass('no-display');
                    $j('#proceed').prop("disabled", true);
                    $j.ajax({
                        type: "POST",
                        url: urlBuilder.build("marketplace/seller/usernameverify"),
                        data: {
                            profileurl: shopUrlVal
                        },
                        success: function (response) {
                            $j('#wk-load').addClass('no-display');
                            if (response===0) {
                                $j('#proceed').prop("disabled", false);
                                $j('#profileurl').after(
                                    $j('<div/>').addClass('available message success')
                                    .text($t("Congratulations! Shop name is available."))
                                );
                            } else {
                                $j('#profileurl').val('');
                                $j('#profileurl').after(
                                    $j('<div/>').addClass('available message error')
                                    .text($t("Sorry! But this shop name is not available, please set another shop name."))
                                );
                            }
                        },
                        error: function (response) {
                            alertBox({
                                content: $t('There was error during verifying seller shop data')
                            });
                        }
                    });
                }
            });
            /**
             * email enable
             */
            $j(document).on({
                'mousedown': function () {
                    $j("#email").prop("disabled",false);
                }
            });
            
            /**
             * email availability check
             */
            $j('body').on('change', '#email', function () {
                var self = this;
                var emailVal = $j('#email').val();
                $j('.available').remove();
                $j('.unavailable').remove();
                if (emailVal) {
                    $j('#wk-load').removeClass('no-display');
                    $j('#proceed').prop("disabled", true);
                    $j.ajax({
                        type: "POST",
                        url: urlBuilder.build("vendorregistration/seller/validate"),
                        data: {
                            email: emailVal
                        },
                        success: function (response) {
                            $j('#wk-load').addClass('no-display');
                            if (response.error === false) {
                                $j('#proceed').prop("disabled", false);
                                $j('#email').after(
                                    $j('<div/>').addClass('available message success')
                                    .text($t("Available."))
                                );
                            } else {
                                $j('#email').val('');
                                $j('#email').after(
                                    $j('<div/>').addClass('available message error')
                                    .text($t(response.msg))
                                );
                            }
                        },
                        error: function (response) {
                            console.log(response);
                            alertBox({
                                content: $t('There was error during verifying email')
                            });
                        }
                    });
                }
            });

            $j('body').on("click","#back",function(){
                if($j("#proceed").is(":disabled"))
                  $j("#proceed").prop("disabled", false);
            });

        },
        initObservable: function () {
            this._super().observe(
                'allFields groupName groupLowerName groupCode progressStatus setSuccess successText displaySelect displayInput regionData backBtnClass proceedBtnClass proceedBtnText addCaptcha'
            );
            return this;
        },
        /**
         * get all groups
         */
        getGroups: function () {
            var self = this;
            var index;
            $j.each(groups, function (i, v) {
                if (window.location.hash.substr(1) == v.lower_group_name) {
                    index = i;
                    self.groupName(v.group_name);
                    self.groupCode(v.group_code);
                    self.groupLowerName(v.lower_group_name);
                }
            });
            $j.each(groups, function (i,v) {
                if (i < index) {
                    v.className = 'tab-list active done';
                } else if (i == index) {
                    v.className = 'tab-list active current';
                } else {
                    v.className = 'tab-list';
                }
            });
            return groups;
        },

        /**
         * create attributes on load
         */
        _createAttributes: function (group_id) {
            var self = this;
            var fields = []
            $j.each(attributes, function (i, v) {
                if (v.mvra_attribute_code == 'email') {
                    v.frontend_input = 'email';
                }
                if (v.group_id == group_id) {
                    fields.push(v);
                }
            });
            return fields;
        },
        getAttributes: function () {
            var self = this;
            return self.allFields();
        },
        selectAttributes: function (group_id) {
            var self = this;
            var fields = []
            $j.each(attributes, function (i, v) {
                if (v.group_id == group_id) {
                    fields.push(v);
                }
            });
            $j.each(groups, function (i, v) {
                if (group_id == v.group_id) {
                    self.groupName(v.group_name);
                    self.groupCode(v.group_code);
                    self.groupLowerName(v.lower_group_name);
                }
            });
            self.allFields(fields);
        },
        _refreshElements: function () {
            var self = this;
            var cal = setInterval(function () {
                if ($j('body').find('.dob_type').length != 0) {
                    $j.each($j('.dob_type'), function (i, v) {
                        if(this.name == "dob"){
                            $j(this).calendar({
                                dateFormat: 'mm/dd/yyyy',
                                changeYear: true,
                                changeMonth: true,
                                yearRange: "-100:+100",
                                showOn: "both",
                                buttonText: "",
                            });
                        }else{
                            $j(this).calendar({
                                dateFormat: 'dd/mm/yyyy',
                                changeYear: true,
                                changeMonth: true,
                                yearRange: "-100:+100",
                                showOn: "both",
                                buttonText: "",
                            });
                        }                        
                    });
                    clearInterval(cal);
                }
            }, 500);
        },
        /**
         * proceed button functionality
         */     
        loadJsCustomAfterKoRender : function(){           
        },

        formProceed: function () {
            var self = this;
            var group_id;
            var dataForm = $j('#wk_vendor_create_form');
            dataForm.mage('validation', {});
            if (dataForm.validation('isValid')) {
                dataForm.find('input').each(function (i,v) {
                    if ($j(this).hasClass('custom_file')) {
                        var keyName = v.name;
                        var value = v.src;
                        formData.push({key : keyName, value: value});
                    } else if (v.name == 'street[]') {
                        var keyName = v.name;
                        var taskArray = new Array();
                        dataForm.find("input[name='street[]']").each(function () {
                           taskArray.push($j(this).val());
                        });
                        formData.push({key : keyName, value: taskArray});
                    } else {
                        var keyName = v.name;
                        var value = v.value;
                        formData.push({key : keyName, value: value});
                    }
                });
                dataForm.find('textarea').each(function (i,v) {
                    var keyName = v.name;
                    var value = v.value;
                    formData.push({key : keyName, value: value});
                });
                dataForm.find('select').each(function (i,v) {
                    if (v.value != null) {
                        var keyName = v.name;
                        var value = $j(this).val();
                        formData.push({key : keyName, value: value});
                    }
                });
                localStorage.setItem('seller-register', JSON.stringify(formData));
                var errorProceedStop = 0;
                if ($j('.wk-proceed-btn').hasClass('wk-register-seller')) {
                    $j('body').trigger('processStart');
                    var data = {};
                    var vendorData = JSON.parse(localStorage.getItem('seller-register'));
                    $j.each(vendorData, function (i,v) {
                        $j.each(vendorData, function (ii,vv) {
                            if (v['key'] ==  vv['key']) {
                                data[v['key']] = v['value'];
                            }
                        });
                    });
                    $j.ajax({
                        url: urlBuilder.build("vendorregistration/seller/create"),
                        type: 'POST',
                        data: data,
                        success: function (data) {
                            $j('body').trigger('processStop');
                            if (data.error) {
                                errorProceedStop = 1;
                                alertBox({
                                    title: 'Warning',
                                    content: "<div class='wk-mprma-warning-content'>"+data.msg+"</div>",
                                    actions: {
                                        always: function (){}
                                    }
                                }); 
                                self.buttonProceedFormProceed(errorProceedStop, group_id);                           
                            } else {
                                $j("#vendorregistration-component .actions-toolbar").addClass('wk-display-none');
                                self.successText($t('You have successfully registered as a Seller with us <a href="'+urlBuilder.build("marketplace/account/dashboard")+'">Click Here</a> to access your account'));
                                if (data.authRequired) {
                                    self.successText(data.msg);
                                }
                                self.setSuccess(true);
                                self.progressStatus(true);
                                $j('#back').removeClass('disabled');
                                self.buttonProceedFormProceed(errorProceedStop, group_id);
                            }
                        }
                    });
                } else {
                    self.buttonProceedFormProceed(errorProceedStop, group_id);
                }
               
            }
        },
        /**
         * For button procedd
         */
        buttonProceedFormProceed : function (errorProceedStop, group_id){
            var self = this;
            $j('#back').removeClass('disabled');
            $j('.wk-proceed-btn').parents().find('.wkvr-nav').find('.tab-list.current').addClass('done').removeClass('current').next().addClass('active').addClass('current');
            if ($j('.nav-bar .current').index() == ($j('.nav-bar li').size()-1)) {
                $j('.wk-proceed-btn').addClass('wk-register-seller');
                $j('.wk-back-btn').parents().find('.wk-proceed-btn.wk-register-seller span').text($t('done'));
            }
            $j.each(groups, function (i,v) {
                if (v.lower_group_name == self.groupLowerName()) {
                    var x = i + 1;     
                    if(errorProceedStop == 1){
                        x = 0;
                        $j( ".nav-bar li" ).each(function( index ) {
                            if(index == $j('.nav-bar li').size()-1){
                                $j( this ).removeClass('current active done');
                            }
                            else if(index == 0){
                                $j( this ).addClass('current active');
                                $j( this ).removeClass('done');
                            }
                            else{
                                $j( this ).removeClass('done active');
                            }
                          });
                        $j('.wk-proceed-btn').removeClass('wk-register-seller');
                        $j('.wk-back-btn').parents().find('.wk-proceed-btn span').text($t('Proceed'));
                    }                 
                    if (groups[x]) {
                        group_id = groups[x].group_id;
                        window.location.hash = groups[x].lower_group_name;
                        if(i == groups.length - 2 ){
                        self.addCaptcha(true);
                        setTimeout(function(){
                        $j("#captch_webkul_vendor").appendTo("#vendor_captcha-data");
                        $j("#captch_webkul_vendor").css("display","block");
                        $j(".action.reload.captcha-reload").trigger("click");
                        }, 500);
                        }
                        if(i == groups.length - 1 ){
                        self.addCaptcha(false);
                        $j("#captch_webkul_vendor").css("display","none");
                        $j("#captch_webkul_vendor").appendTo("#display_captcha_hidden");
                        }                            
                    } else {
                        if(i == groups.length - 2 ){
                        $j("#captch_webkul_vendor").appendTo("#vendor_captcha-data");
                        $j("#captch_webkul_vendor").css("display","block");
                        self.addCaptcha(true);
                        }
                        if(i == groups.length - 1 ){
                            self.addCaptcha(false);
                            $j("#captch_webkul_vendor").css("display","none");
                            $j("#captch_webkul_vendor").appendTo("#display_captcha_hidden");
                        }                            
                    }
                    errorProceedStop = 0;
                }                   
            })
            self.selectAttributes(group_id);
            self.fillFieldsData();
            self._refreshElements();
        },
        /**
         * back button functionality
         */
        formBack: function () {
            console.log('hello webkul');
            var self = this;
            var group_id;
            $j('.wk-back-btn').parents().find('.wkvr-nav').find('.tab-list.current').removeClass('active').removeClass('current').prev().addClass('active').addClass('current').removeClass('done');
            if ($j('.nav-bar .current').index() == 0) {
                $j('.wk-back-btn').addClass('disabled');
            }
            $j('.wk-proceed-btn').removeClass('wk-register-seller');
            $j('.wk-back-btn').parents().find('.wk-proceed-btn span').text($t('Proceed'));
            var lower_group_name = window.location.hash.substr(1);
            $j.each(groups, function (i,v) {
                if (v.lower_group_name == lower_group_name) {
                    var x = i-1;
                    if (groups[x]) {
                        group_id = groups[x].group_id;
                        window.location.hash = groups[x].lower_group_name;
                        self.addCaptcha(false);
                        $j("#captch_webkul_vendor").css("display","none");
                        $j("#captch_webkul_vendor").appendTo("#display_captcha_hidden");
                    }
                }
            })
            self.selectAttributes(group_id);
            self.fillFieldsData();
            self._refreshElements();
        },

        /**
         * get regions for address
         */
        getRegions: function () {
            var self = this;
            var param = 'country='+$j('#country').val();

            $j('body').trigger('processStart');
            $j.ajax({
                url: urlBuilder.build("vendorregistration/seller/region"),
                data: param,
                type: "GET",
                dataType: 'json'
            }).done(function (data) {
                $j('body').trigger('processStop');
                if (data.value.length > 0) {
                    self.displaySelect('block');
                    self.displayInput('none');
                    self.regionData(data.value);
                } else {
                    self.displaySelect('none');
                    self.displayInput('block')
                    self.regionData([]);
                }
            });
        },

        /**
         * to fill data in all html type fields
         */
        fillFieldsData: function () {
            var self = this;
            var fields = self.allFields();
            var sellerData = JSON.parse(localStorage.getItem('seller-register'))
            $j.each(fields, function (i,v) {
                $j.each(sellerData,function (si,sv) {
                    if (fields[i].frontend_input === 'multiselect') {
                        if (fields[i].mvra_attribute_code+'[]' == sellerData[si].key) {
                            $j.each(sellerData[si].value, function (i,e) {
                                $j('[name="'+sellerData[si].key+'"] option[value="'+e+'"]').prop("selected", true);
                            });
                        }
                    }
                    if (fields[i].mvra_attribute_code == sellerData[si].key) {
                        if (fields[i].frontend_input == 'boolean') {
                            if (sellerData[si].value == 1) {
                                $j('[name="'+fields[i].mvra_attribute_code+'"]').prop('checked', true);
                            }
                        }
                        if (fields[i].frontend_input != 'file' && fields[i].frontend_input != 'image') {
                            $j('[name="'+fields[i].mvra_attribute_code+'"]').val(sellerData[si].value);
                        }
                    }
                });
            });
            if (window.location.hash.substr(1) == 'address_info') {
                var i = 0;
                $j.each(sellerData,function (si,sv) {
                    if (sellerData[si].key == 'country_id') {
                        $j('body').find('#country').val(sellerData[si].value);
                    } else if (sellerData[si].key == 'region') {
                        $j('body').find('#region').val(sellerData[si].value);
                    } else if (sellerData[si].key == 'region_id') {
                        $j('body').find('#region_id').val(sellerData[si].value);
                    } else if (sellerData[si].key == 'street[]') {
                        if (i == 0) {
                            $j('body').find('#seller-address').val(sellerData[si].value[i]);
                            i++;
                        }
                        $j('body').find('#seller-address_'+i).val(sellerData[si].value[i]);
                        i++;
                    } else {
                        if (sellerData[si].key.indexOf('[]') == -1) {
                            $j('body').find('#seller-'+sellerData[si].key).val(sellerData[si].value);
                        }
                    }
                });
            }
        }
    });
});
