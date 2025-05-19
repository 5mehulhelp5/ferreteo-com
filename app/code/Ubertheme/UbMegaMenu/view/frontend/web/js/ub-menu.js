/**
 * Copyright © 2016 Ubertheme. All rights reserved.
 */

define([
    'jquery',
    'mage/translate',
    'matchMedia'
], function ($, $t) {
    'use strict';

    $.widget('mage.ubMenu', {

        options: {
            "rootSelector": ".ub-mega-menu.level0",
            "itemSelector": "li.mega",
            "mobileBreakPoint": "767",
            "mobileType": 'accordion',
            drillOptions: {
                $container: null,
                container: 'drilldown-container',
                root: 'drilldown-root',
                sub: 'drilldown-sub',
                back: 'drilldown-back',
                speed: 200,
                _css: {
                    float: 'left',
                    width: null
                },
                _history: []
            },
            "extraClass": ""
        },

        /**
         * Initialize widget
         */
        _create: function () {
            this.initialize();
            this.bindEvents();
        },

        initialize: function() {
            var self = this;

            //hide default TopMenu
            $('.nav-sections-item-content .navigation').hide();

            //reset active
            $(self.options.rootSelector).find('.active').removeClass('active');

            //set active state for current selected menu item and all associated parent items
            var $activeItem = null;
            //check has clicked menu item id in session storage
            var currentItemId = (sessionStorage) ? sessionStorage.getItem('ubMenuItemId') : false;
            var currentUrl = window.location.protocol + '//' + window.location.host + window.location.pathname;
            var urlPath = window.location.pathname;
            var urlPathWithSearch = window.location.pathname + window.location.search;
            if (currentItemId) {
                $activeItem = $(self.options.rootSelector + ' #' + currentItemId);
                if ( $activeItem.find('a[href="'+ currentUrl +'"]').length
                    || $activeItem.find('a[href="'+ urlPath +'"]').length
                    || $activeItem.find('a[href="'+ urlPathWithSearch +'"]').length
                ) {
                    if ($activeItem.children('a.mega').length) {
                        $activeItem = $activeItem.children('a.mega');
                    }
                    if ($activeItem.children('span.mega').length) {
                        $activeItem = $activeItem.children('span.mega');
                    }
                } else {
                    $activeItem = null;
                }
            } else {
                $activeItem = $(self.options.rootSelector + ' a[href="' + currentUrl + '"]');
                if (!$activeItem.length) {
                    $activeItem = $(self.options.rootSelector + ' a[href="' + urlPath + '"]');
                }
                if (!$activeItem.length) {
                    $activeItem = $(self.options.rootSelector + ' a[href="' + urlPathWithSearch + '"]');
                }
            }
            if ($activeItem && $activeItem.length) {
                if ($activeItem.length > 1) {
                    $activeItem = $activeItem.first();
                }
                $activeItem.addClass('active');
                $activeItem.parentsUntil(self.options.rootSelector).addClass('active');
                //active for related elements
                $(self.options.itemSelector + '.active').children().addClass('active');
                $(self.options.itemSelector + '.has-child.active').children().addClass('active');
            }
        },

        bindEvents: function () {
            var self = this;

            //binding common events
            /**
             * update current clicked menu item id to using on other contexts
             */
            var menuId = null;
            var eventName = "click";
            if ($(this.options.rootSelector).data('device-type') === 'tablet') {
                eventName = "touchstart";
            }
            $(self.options.rootSelector + " " + self.options.itemSelector).on(eventName, function (e) {
                if ($(e.target).closest(self.options.itemSelector).length) {
                    menuId = $(e.target).closest(self.options.itemSelector).attr('id');
                    sessionStorage.setItem('ubMenuItemId', menuId);
                } else {
                    //reset status of all `A` tags
                    $(self.options.rootSelector + ' a.mega').data('status', '');
                }
            });

            //binding specific events
            mediaCheck({
                media: '(max-width: ' + self.options.mobileBreakPoint + 'px)',
                entry:function() {
                    self.mobileEvents();
                },
                exit: function() {
                    self.notMobileEvents();
                }
            });
        },

        mobileEvents: function () {
            var self = this;

            //reset events on tablet/desktop
            $(self.options.rootSelector + ' a.mega')
                .add(self.options.rootSelector + ' span.mega')
                .off('click touchstart mouseenter')

            //off desktop events for mobile menu context
            $(self.options.rootSelector).find(self.options.itemSelector).off('mouseenter').off('mouseleave');

            if (self.options.mobileType === 'accordion') {
                self.applyAccordion();
            } else {
                self.applyDrillDown();
            }
        },

        notMobileEvents: function() {
            var self = this;
            var isTablet = $('html').hasClass('touch') ? 1 : 0;

            //off mobile events
            $(self.options.rootSelector + ' li.has-child').children().off('click');

            //binding common events on tablet and desktop
            var $menuItems = $(self.options.rootSelector + ' a.mega').add(self.options.rootSelector + ' span.mega');
            var eventName = isTablet ? 'click' : 'mouseenter';
            $menuItems.on(eventName, function(e) {
                if ($(e.target).prop('tagName') === 'A') {
                    e.preventDefault();
                    //e.stopPropagation();
                }
                /**
                 * if menu item has extra css class 'style-tabs'
                 * we will apply tabs style in child menu items
                 */
                if ($(this).hasClass('style-tabs') || $(this).hasClass('style-tabs-hz')) {
                    self.applyTabs($(this));
                }
                //if menu item is a tab head
                if ($(this).hasClass('tab-head')) {
                    var $tabHead = $(this).parent('li.tab-head');
                    self.activeTab($tabHead);
                    sessionStorage.setItem('ubLastOpenedTabId', $tabHead.attr('id'));
                }

                //get current status of menu item
                var status = $(this).data('status');
                //reset status of all menu items
                $menuItems.data('status', '');
                if (isTablet) {
                    if ( !$(this).hasClass('has-child')
                        || ( $(this).hasClass('has-child') && status != undefined &&  status === 'touched' )
                    ) {
                        if ($(this).attr('href') !== undefined && $(this).attr('href').length) {
                            window.location.href = $(this).attr('href');
                        }
                        return true;
                    } else {
                        $(this).data('status', 'touched');
                    }
                } else {
                    return true;
                }

                return false;
            });

            if (isTablet) {
                //close sub menu items when touch to outside of menu area
                $(document).on('click touchstart', function (e) {
                    if (!$(e.target).closest(self.options.rootSelector).length) {
                        $('ul.level0 ' + self.options.itemSelector + '.active').children().removeClass('active');
                    }
                });
            } else {
                //add extra class 'mega-hover' when mouse hover on menu item on desktop
                $(self.options.rootSelector).find(self.options.itemSelector).each(function(i, el) {
                    if (!$(el).hasClass('tab-head')) {
                        $(el).mouseenter(function() {
                            $(this).addClass('mega-hover');
                            if ($(this).hasClass('has-child')
                                || $(this).parents(self.options.itemSelector + '.has-child').length) {
                                $('body').addClass('ub-sub-menu-opened');
                            }
                        }).mouseleave(function() {
                            $(this).removeClass('mega-hover');
                            if (!$(this).parents(self.options.itemSelector + '.has-child').length) {
                                $('body').removeClass('ub-sub-menu-opened');
                            }
                        });
                    }
                });
            }
        },

        applyTabs : function($item) {
            var self = this;
            //check and get the needed tab to active
            var lastOpenedId = sessionStorage.getItem('ubLastOpenedTabId');
            var $activeTab = null;
            if (lastOpenedId) {
                $activeTab = $('#' + lastOpenedId);
            } else {
                var $tabHeads = $item.siblings('.child-content').find('ul.level2').children('li.tab-head');
                $activeTab = $tabHeads.filter(function(k) {
                    return ($(this).children('a.mega.active').length || $(this).children('span.mega.active').length);
                });
                if (!$activeTab.length) {
                    $activeTab = $tabHeads.first();
                }
            }
            if ($activeTab) {
                if (!$activeTab.hasClass('active')) {
                    self.activeTab($activeTab);
                } else {
                    self.resizeTab($activeTab);
                }
            }
        },

        activeTab: function($tabHead) {
            $tabHead.addClass('active').siblings('li.tab-head').removeClass('active');
            this.resizeTab($tabHead);
        },

        resizeTab: function($tabHead) {
            //auto set min-height for wrapper of current tabs
            var $tabContent = $tabHead.children('.child-content');
            var minHeight = parseInt($tabContent.outerHeight()) + parseInt($tabContent.css('top'));
            $tabHead.closest('div.child-content').first().css('min-height', minHeight);
        },

        applyAccordion: function() {
            var self = this;
            //binding events on items has sub items
            $(self.options.rootSelector + ' li.has-child a.has-child')
                .add(self.options.rootSelector + ' li.has-child span.has-child')
                .add(self.options.rootSelector + ' li.has-child span.menu-parent-icon').on('click', function (e) {
                    var preventDefault = false;
                    if ($(e.target).prop('tagName') === 'A' || $(e.target).parent("a.mega").length) {
                        preventDefault = true;
                    }
                    //inactive all siblings elements
                    $(this).parent().siblings('.has-child').children().removeClass('active');
                    //toggle active for current element
                    if (!$(this).hasClass('active')) {
                        $(this).addClass('active').siblings().addClass('active');
                    } else {
                        $(this).removeClass('active').siblings().removeClass('active');
                    }
                    if (preventDefault) {
                        e.preventDefault();
                    }
            });
            //bind click event on menu item group links (shop all item)
            var $shopAllItems = $(self.options.rootSelector + ' li.has-child span.menu-group-link');
            if ($shopAllItems.length) {
                $shopAllItems.on('click', function (e) {
                    var url = $(this).siblings('a').attr('href');
                    if (url !== undefined && url.length && url !== '#') {
                        window.location.href = url;
                        sessionStorage.setItem('ubMenuItemId', $(this).parent(self.options.itemSelector).attr('id'));
                    }
                });
            }
        },

        applyDrillDown: function () {
            var self = this;

            //wrapper more tags using for drilldown function
            $(self.options.rootSelector).wrap("<div class='drilldown'><div class='drilldown-container'></div></div>");

            //append drilldown buttons
            var ddButtons = '<div class=\"btn-drilldown\" style="display: none;">';
            ddButtons  += '<a class=\"btn-back\" href=\"javascript:void(0);\">' + $t('Back') + '</a>';
            ddButtons  += '</div>';
            $(self.options.rootSelector).parent().parent().prepend(ddButtons);

            //set drilldown container element
            self.options.drillOptions.$container = $(self.element).parent("." + self.options.drillOptions.container);

            //binding events on items has sub items
            $(self.options.rootSelector + ' li.has-child a.has-child')
                .add(self.options.rootSelector + ' li.mega span.has-child')
                .on('click', function (e) {
                    var preventDefault = false;
                    if ($(e.target).prop('tagName') === 'A' || $(e.target).closest("a.mega").length) {
                        preventDefault = true;
                    }

                    var $next = $(this).nextAll('.' + self.options.drillOptions.sub);
                    if ($next.length) {
                        self.drillDown($next, {});
                    } else {
                        preventDefault = false;
                    }

                    if (preventDefault) {
                        e.preventDefault();
                    }
            });

            self.options.drillOptions.$container.closest(".drilldown").delegate(
                '.btn-drilldown .btn-back',
                'click',
                function () {
                self.drillUp({});
            });

            //fixed styles of the header links if exists
            self.fixHeaderLinks();

        },

        drillDown: function ($next, opts) {
            var self = this;

            if (!$next.length) {
                return;
            }

            //re-calculate width for drilldown container
            self.options.drillOptions._css.width = $(self.element).outerWidth();
            self.options.drillOptions.$container.width(self.options.drillOptions._css.width * 2);

            //mark parent of the opened node
            $next.parent().attr("data-is-parent", true);

            //get the parent item
            var $parentItem = $next.siblings('a.mega');
            if (!$parentItem.length) {
                $parentItem = $next.siblings('span.mega');
            }

            var $parentElement =  null;
            if ($parentItem.attr("href") != undefined) {
                $parentElement =  '<a class="parent-item" href="'+ $parentItem.attr("href")
                    + '"><span>' + $parentItem.text() + '</span></a>';
            } else {
                $parentElement =  '<span class="parent-item">' + $parentItem.text() + '</span>';
            }
            if (!$next.children('.parent-item').length) {
                $next.prepend($parentElement);
            }


            //update needed CSS classes
            $next = $next.removeClass('child-content')
                .removeClass(self.options.drillOptions.sub)
                .addClass(self.options.drillOptions.root)
                .addClass(self.options.extraClass);

            //append to drilldown container
            $next.css('left', (2 * self.options.drillOptions._css.width) + 'px');
            self.options.drillOptions.$container.append($next);

            var speed = (opts && opts.speed !== undefined) ? opts.speed : self.options.drillOptions.speed;
            self.doDrilling(
                { marginLeft: (-1 * self.options.drillOptions._css.width) + "px", speed: speed },
                function () {
                    $next.animate({ left: '0px' }, speed, null);
                    var $current = $next.prev();

                    self.options.drillOptions._history.push($current.detach());
                    self.restoreState($next);

                    self.options.drillOptions.$container.parent().parent().addClass('drilling');
                    self.options.drillOptions.$container.parent().find('.btn-drilldown').show();

                    //fixed styles of the header links if exists
                    self.fixHeaderLinks();
                }
            );
        },

        drillUp: function (opts) {
            var self = this;

            //re-calculate width for drilldown container
            self.options.drillOptions._css.width = $(self.element).outerWidth();
            self.options.drillOptions.$container.width(self.options.drillOptions._css.width * 2);

            //get node element to backward and prepend in to container
            var $back = self.options.drillOptions._history.pop();

            if ($back === undefined) {
                return;
            }

            $back.css('left', (-1 * self.options.drillOptions._css.width) + 'px');
            self.options.drillOptions.$container.prepend($back);

            var speed = (opts && opts.speed !== undefined) ? opts.speed : self.options.drillOptions.speed;
            self.doDrilling(
                { marginLeft: '0px', speed: speed},
                function () {
                    $back.animate({ left: '0px' }, speed, null);

                    var $current = $back.next();
                    $current.addClass(self.options.drillOptions.sub)
                        .removeClass(self.options.drillOptions.root)
                        .removeClass(self.options.extraClass);

                    //restore to the node element at its initial position in the Menu DOM tree
                    self.options.drillOptions.$container.find('[data-is-parent]').last()
                        .removeAttr('data-is-parent')
                        .append($current);
                    $current.find('.menu-group-link').first().hide();
                    $current.find('.drilldown-back').first().hide();
                    self.restoreState($back);

                    if ($back.hasClass('level0')) {
                        self.options.drillOptions.$container.parent().parent().removeClass('drilling');
                        self.options.drillOptions.$container.parent().find('.btn-drilldown').hide();
                    }

                    //fixed styles of the header links if exists
                    self.fixHeaderLinks();
                }
            );
        },

        doDrilling: function(opts, callback) {
            var $menus = this.options.drillOptions.$container.children('.' + this.options.drillOptions.root);
            $menus.css(this.options.drillOptions._css);

            var $menu = $menus.first();
            $menu.animate({ left: opts.marginLeft }, opts.speed, callback);
         },

        restoreState: function ($node) {
            $node.css({
                float: '',
                width: '',
                left: '',
                right:''
            });
            //reset width of drilldown container
            this.options.drillOptions.$container.css('width', '');
        },

        fixHeaderLinks: function () {
            var $el = this.options.drillOptions.$container.parent().siblings('.header.links');
            if ($el.length) {
                var $drilldownRoot = this.options.drillOptions.$container.children('.drilldown-root');
                var $back =  this.options.drillOptions.$container.siblings('.btn-drilldown');
                var top = $drilldownRoot.outerHeight(true) + $back.outerHeight(true);
                $el.css('top', top);
                $el.css('position', 'absolute');
            }
        }
    });

    return $.mage.ubMenu;
});
