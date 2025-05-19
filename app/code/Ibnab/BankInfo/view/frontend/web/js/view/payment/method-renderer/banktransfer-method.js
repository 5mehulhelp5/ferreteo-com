/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define(
    [
        'ko',
        'Magento_Checkout/js/view/payment/default',
        'jquery'
    ],
    function (ko, Component,$) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Ibnab_BankInfo/payment/banktransfer',
                allbank: '',
                activebankowner: '',
                allbankVar: '',
                firstTextBank: '',
            },
            /*
            initObservable: function () {
                this._super()
                    .observe([
                        'allbank',
                        'activebankowner'
                    ]);
                return this;
            },*/
            getData: function() {
                return {
                    'method': this.item.method,
                    'additional_data': {
                        'allbank': $('#banktransfer_allbank').val(),
                        'activebankowner': $('#banktransfer_activebankowner').val()
                    }
                };
            },
            /**
             * Get value of instruction field.
             * @returns {String}
             */
            getInstructions: function () {
                return window.checkoutConfig.payment.instructions[this.item.method];
            },
            /**
             * Get value of activebank field.
             * @returns {Int}
             */
            getActivebank: function () {
                var result = false;
                if(window.checkoutConfig.payment.banktransfer['activebank'] == 1){
                    result = true;
                }else{
                    result = false;
                }
                
                return result;
            },
            bankChanged: function () {

              var currentSelectValue= $("[name='payment[allbank]']").val();              
              var linecurrent = this.allbankVar[currentSelectValue];
              
              if (linecurrent !== undefined){
              var htlmBankLine = linecurrent.bank.replace('/b','<b>').replace('/l','</b>') + "<br />" ;
              htlmBankLine +=  linecurrent.description.replace('/b','<b>').replace('/l','</b>') + "<br />" ;
              htlmBankLine += linecurrent.additional1.replace('/b','<b>').replace('/l','</b>') + "<br />" ;
              htlmBankLine +=  linecurrent.additional2.replace('/b','<b>').replace('/l','</b>') + "<br />"+ "<br />" ;
              }else{
                 htlmBankLine = ""; 
              }
              $("#banknameUpdate").html(htlmBankLine);
              
            },
            firstBankChanged: function () {             
              var linecurrent = this.allbankVar[this.firstTextBank];
              if (linecurrent !== undefined){
              var htlmBankLine = linecurrent.bank.replace('/b','<b>').replace('/l','</b>') + "<br />" ;
              htlmBankLine +=  linecurrent.description.replace('/b','<b>').replace('/l','</b>') + "<br />" ;
              htlmBankLine += linecurrent.additional1.replace('/b','<b>').replace('/l','</b>') + "<br />" ;
              htlmBankLine +=  linecurrent.additional2.replace('/b','<b>').replace('/l','</b>') + "<br />"+ "<br />" ;
              }else{
                 htlmBankLine = ""; 
              }
              $("#banknameUpdate").html(htlmBankLine);
              
            },
            /**
             * Get value of allbank field.
             * @returns {String}
             */
            getAllbank: function () {
                this.allbankVar = window.checkoutConfig.payment.banktransfer['allbank'];
                //var arrayAllbank = allBank.split(",");
                 //alert(window.checkoutConfig.payment.banktransfer.toSource());
                var arrayAllbankResult = new Array();
                var i = 0;
                for (var key in this.allbankVar ) {
                   if(i === 0){
                       this.firstTextBank  = key;
                   }
                   arrayAllbankResult.push({ value: key, text: key});
                   i++;
                };
                this.firstBankChanged();
                return arrayAllbankResult;
            },
            /**
             * Get value of activebankowner field.
             * @returns {Int}
             */
            getActivebankowner: function () {
                var result = false;
                if(window.checkoutConfig.payment.banktransfer['activebankowner'] == 1){
                    result = true;
                }else{
                    result = false;
                }
                
                return result;
            }

        });
    }
);
