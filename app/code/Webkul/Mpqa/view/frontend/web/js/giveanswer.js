/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_Mpqa
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

define([
    "jquery",
    'mage/mage',
    'Magento_Ui/js/modal/alert'
], function ($,mage,alert) {
    'use strict';
    $.widget('mage.giveanswer', {

        _create: function () {
            var self = this;
            /* save answer */
            $('button.button.wk-mp-btn.btn').on('click',function () {
                var ans=$("#maincont").val();
                var qid=self.options.question_id;
                if (ans!='') {
                    $("body").append(jQuery("<div/>").addClass("filterurl_loader").append(jQuery("<div/>")));
                    $.ajax({
                        url:self.options.submitanswer_url,
                        data:{
                                form_key: $.cookie('form_key'),
                                ans:ans,
                                qid:qid,
                                cid:self.options.customer_id
                            },
                        type:'post',
                        dataType:'json',
                        success:function (data) {
                            if (data.status) {
                                $('#'+qid).after("<div class='respond newres' ><div class='wk-mp-user'><label class='rlabl'>"+data.respond_type+":</label></div><div class='conten'><span class='wk_prewrap'>"+ans+"</span></div><div class='dte'>"+data.time+"</div></div> ");
                                $('#maincont').val('');
                            } else {
                                alert({
                                    title: 'Error!',
                                    content: 'Please try again.',
                                    actions: {
                                        always: function () {
                                            location.reload();
                                        }
                                    }
                                });
                            }
                            $("body").find('.filterurl_loader').remove();
                        }
                    });
                }
            });
        },

    });
    return $.mage.giveanswer;
});
