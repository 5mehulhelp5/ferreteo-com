/**
 * @category   Webkul
 * @package    Webkul_MpSellerBadge
 * @author     Webkul Software Private Limited
 * @copyright  Copyright (c)  Webkul Software Private Limited (https://webkul.com)
 * @license    https://store.webkul.com/license.html
 */
/*jshint jquery:true*/
define([
    "jquery",
], function ($, $t, alert) {
    'use strict';
    var animationComplete = true;
    var $i = 0;
    $.widget('mage.sellerBadgePlugin', {

        options: {
            wkNextBadge: '.wknextbadge',
            wkPrevBadge: '.wkprevbadge'
        },
        _create: function () {
            var self = this;
            var count = $('.wkbadges').length;
            var totalwidth = $('.wkbadges:first').width() * count;
            $('.wkslide').width(totalwidth+count*5+4);
            $('.wkslide').height($('.wkbadges:eq(0)').height());
            $('#wkslidecont').width($('.wkbadges:eq(0)').width()+5);
            $('#wkslidecont').height($('.wkbadges:eq(0)').height());
            $('#wkslidecont').removeClass('nodisplay');

            $(self.options.wkNextBadge).on('click', function (e) {
                self.nextBadge(count);
            });
            $(self.options.wkPrevBadge).on('click', function (e) {
                self.prevBadge(count);
            });
        },
        nextBadge : function (count) {
            if (animationComplete) {
                animationComplete = false;
                $i=$i+1;
                
                if ($i == count) {
                    $i=0;
                }
                var $slideamount = $('.wkbadges:eq('+$i+')').width()*($i)+(5*$i);
                $('.wkslide').animate({"left":"-"+$slideamount},1500,function () {
                    animationComplete = true; });
                console.log($i);
            }

        },
        prevBadge : function (count) {
            
            if (animationComplete) {
                animationComplete = false;
                $i--;
                
                if ($i < 0) {
                    $i=0;
                    animationComplete = true;
                } else {
                    var $slideamount = $('.wkbadges:eq('+$i+')').width()*($i)+(2*$i);
                    console.log($slideamount);
                    $('.wkslide').animate({"left":"-"+$slideamount},1500,function () {
                        animationComplete = true; });
                }
                console.log($i);
            }

        }
    });
    return $.mage.sellerBadgePlugin;
});
