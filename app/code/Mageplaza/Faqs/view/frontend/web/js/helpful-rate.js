/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_Faqs
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

define([
        'jquery'
    ], function ($) {
        return function (config) {
            var messengerBox      = config.messengerBox,
                articleId         = config.articleId,
                currentArticleIds = {};

            if (JSON.parse(getCookie('mpfaqs_article_data'))) {
                currentArticleIds = JSON.parse(getCookie('mpfaqs_article_data'));
            }
            if (typeof currentArticleIds[articleId] !== "undefined") {
                $('#rating-actions a[data-type="' + currentArticleIds[articleId].type + '"]').removeClass('in-active').addClass('active');
            }
            $.ajax({
                url: window.location.href,
                data: {
                    helpfulrate: true
                },
                success: function (response) {
                    if (response.status) {
                        $('#rating-actions').find('.like-count').text(response.like_count);
                        $('#rating-actions').find('.dislike-count').text(response.dislike_count);
                        $('.mpfaqs-container__content__header__information').find('.like-count').html('<i class="far fa-thumbs-up"></i> ' + response.like_count);
                    }
                }
            });
            $('#rating-actions a').each(function () {
                var el = this;

                $(el).on('click', function () {
                    var type, currentButton, headLikeCount;

                    currentArticleIds = {};
                    if (JSON.parse(getCookie('mpfaqs_article_data'))) {
                        currentArticleIds = JSON.parse(getCookie('mpfaqs_article_data'));
                    }

                    if (typeof currentArticleIds[articleId] !== "undefined") {
                        $('#rating-label').find('.messages').remove();
                        $('#rating-label').append(messengerBox.voteAlert);

                    }
                    else {
                        if ($(this).hasClass('in-active')) {
                            $('#rating-actions a').removeClass('active').addClass('in-active').addClass('disabled');
                            $(this).removeClass('in-active');
                            type          = $(this).attr('data-type');
                            currentButton = this;
                            if (type === 'positive') {
                                headLikeCount = parseInt($('.mpfaqs-container__content__header__information .like-count').text()) + 1;
                                $('.mpfaqs-container__content__header__information .like-count').html('<i class="far fa-thumbs-up"></i> ' + headLikeCount);
                            }
                        }
                        $.ajax({
                            url: window.location.href,
                            data: {
                                action: type,
                            },
                            success: function (response) {
                                var storedArticleIds = receiveCookieArticleIds(articleId, type),
                                    jsonStringIds    = JSON.stringify(storedArticleIds);

                                if (response.status) {
                                    $('#rating-actions a').removeClass('disabled');
                                    $(currentButton).addClass('active');
                                    $(currentButton).find('span').text(parseInt($(currentButton).find('span').text()) + 1);
                                    $('#rating-label').append(messengerBox.voteSuccess);
                                }

                                document.cookie = 'mpfaqs_article_data = ' + jsonStringIds;

                            }
                        });
                    }
                });
            });

            /** get cookie by name */
            function getCookie(name) {
                var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');

                return v ? v[2] : null;
            }

            /** get stored cookie article ids */
            function receiveCookieArticleIds(articleId, actionType) {
                var articleData     = {
                    id: articleId,
                    type: actionType
                },
                    receivedJsonStr = getCookie('mpfaqs_article_data'),
                    articleIds      = JSON.parse(receivedJsonStr);

                if (articleIds == null) {
                    articleIds = {};
                }
                if (typeof articleIds[articleId] !== "undefined") {

                    return articleIds;
                }
                articleIds[articleId] = articleData;

                return articleIds;
            }
        };
    }
);
