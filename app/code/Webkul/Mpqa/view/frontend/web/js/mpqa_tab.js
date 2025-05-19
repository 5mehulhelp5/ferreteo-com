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
    "mage/template",
    "Magento_Ui/js/modal/modal",
    'Webkul_Mpqa/js/model/authentication-popup',
    'Magento_Ui/js/modal/alert',
    "mage/translate"
], function ($,mage,template,modal,authenticationPopup,alert,$t) {
    'use strict';
    $.widget('mage.mpqa_tab', {

        _create: function () {
            var self = this;
    /** ------question search */
            $(document).on('keypress','#searchqa', function (e) {
                if (e.which == 13) {
                    search();
                }
            });

            $(document).on('click','.search_button',function (e) {
                search();
            });

            function search()
            {
                var query = $('#searchqa').val();
                if ($.trim(query)!='') {
                    $("body").append(jQuery("<div/>").addClass("filterurl_loader").append(jQuery("<div/>")));
                    $('.wk-qa-action').css('display','none');
                    $.ajax({
                        url     :   self.options.search_url,
                        type    :   "POST",
                        data    :   {pid:self.options.product_id,
                                    query:$('#searchqa').val()},
                        dataType:   "json",
                        success:function (data1) {
                            $('#ajaxquestion-answertemplate').html('');
                            var ask_ques=template('#ask-question-template');
                            $.each(data1, function () {
                                var templateData = template('#question-answertemplate');
                                var questions = templateData({
                                                data: {
                                                    question_id: this['question_id'],
                                                    subject: this['subject'],
                                                    content: this['content'],
                                                    qa_nickname:this['qa_nickname'],
                                                    qa_date:this['qa_date'],
                                                    answer:this['answer'],
                                                    answer_id:this['answer_id'],
                                                    nickname:this['nickname'],
                                                    likes:this['likes'],
                                                    dislikes:this['dislikes'],
                                                    like_class:this['like_class'],
                                                    dislike_class:this['dislike_class'],
                                                    createdat:this['createdat'],
                                                    answer_count:this['count']
                                                }
                                            });
                                $('#ajaxquestion-answertemplate').append(questions);
                            });

                            $('#ajaxquestion-answertemplate').append(ask_ques);
                            if (data1.length==0) {
                                $(".no-result").show();
                            }
                            $('.wk-qa-action-label-search').html($("<span/>").html(query).text());
                            $('#wk-qa-action-search').css('display','inline-block');
                            $("body").find('.filterurl_loader').remove();
                            $(".pager").remove();
                        }
                    });
                }
            }
    /** ---- recent url */
            $(document).on('click','#recent', function () {
                $('.wk-qa-action').css('display','none');
                $("body").append(jQuery("<div/>").addClass("filterurl_loader").append(jQuery("<div/>")));
                $.ajax({
                    url     :   self.options.recent_url,
                    type    :   "POST",
                    data    :   {pid:self.options.product_id},
                    dataType:   "json",
                    success:function (data1) {
                        $('#ajaxquestion-answertemplate').html('');
                        var ask_ques=template('#ask-question-template');
                        $.each(data1, function () {
                            var employeeTemplate = template('#question-answertemplate');
                            var employee = employeeTemplate({
                                                data: {
                                                    question_id: this['question_id'],
                                                    subject: this['subject'],
                                                    content: this['content'],
                                                    qa_nickname:this['qa_nickname'],
                                                    qa_date:this['qa_date'],
                                                    answer:this['answer'],
                                                    answer_id:this['answer_id'],
                                                    nickname:this['nickname'],
                                                    likes:this['likes'],
                                                    dislikes:this['dislikes'],
                                                    like_class:this['like_class'],
                                                    dislike_class:this['dislike_class'],
                                                    createdat:this['createdat'],
                                                    answer_count:this['count']
                                                }
                                            });

                            $('#ajaxquestion-answertemplate').append(employee);
                        });
                         $('#ajaxquestion-answertemplate').append(ask_ques);
                         $("body").find('.filterurl_loader').remove();
                         $(".pager").remove();
                         $('#wk-qa-action-recent').css('display','inline-block');
                    }
                });

            });
    /** ---- most helpful */
            $(document).on('click','#helpful', function () {
                $('.wk-qa-action').css('display','none');
                $("body").append(jQuery("<div/>").addClass("filterurl_loader").append(jQuery("<div/>")));
                $.ajax({
                    url     :   self.options.helpful_url,
                    type    :   "POST",
                    data    :   {pid:self.options.product_id},
                    dataType:   "json",
                    success:function (data1) {
                        $('#ajaxquestion-answertemplate').html('');
                        var ask_ques=template('#ask-question-template');
                        $.each(data1, function () {
                            var employeeTemplate = template('#question-answertemplate');
                            var employee = employeeTemplate({
                                                data: {
                                                    question_id: this['question_id'],
                                                    subject: this['subject'],
                                                    content: this['content'],
                                                    qa_nickname:this['qa_nickname'],
                                                    qa_date:this['qa_date'],
                                                    answer:this['answer'],
                                                    answer_id:this['answer_id'],
                                                    nickname:this['nickname'],
                                                    likes:this['likes'],
                                                    dislikes:this['dislikes'],
                                                    like_class:this['like_class'],
                                                    dislike_class:this['dislike_class'],
                                                    createdat:this['createdat'],
                                                    answer_count:this['count']
                                                }
                                            });

                            $('#ajaxquestion-answertemplate').append(employee);
                        });
                        $('#ajaxquestion-answertemplate').append(ask_ques);
                        $("body").find('.filterurl_loader').remove();
                        $(".pager").remove();
                        $('#wk-qa-action-helpful').css('display','inline-block');
                    }
                });
            });

            $(document).on('click','.wk-qa-action-button',function () {
                location.reload();
            });
    /** ------ more answer */
            $(document).on('click','.qa-ansmore', function () {
                var questionid =    $(this).attr('dataid');
                var this_this=$(this);
                var logid = self.options.buyer_id;

                $("body").append(jQuery("<div/>").addClass("filterurl_loader").append(jQuery("<div/>")));
                $.ajax({
                    url: self.options.viewall_ans_url,
                    data:{quesid:questionid,custid:logid},
                    dataType:'json',
                    success:function (content) {
                        $(this_this).parent().hide();
                        $(this_this).parent().after(content['answer']);
                        $("body").find('.filterurl_loader').remove();
                    }
                });
            });
    /** ------ like answer */
            $(document).on('click','.like', function () {

                var this_this=$(this);
                var login=self.options.islogin;
                if (login == 0) {
                    authenticationPopup.showModal();
                    return false;
                }
                $("body").append($("<div/>").addClass("filterurl_loader").append($("<div/>")));
                var ansid = $(this).attr('dataid');
                var logid = self.options.buyer_id;
                $.ajax({
                    url:self.options.reviewanswer_url,
                    data:{ansid:ansid,custid:logid,action:'like'},
                    success:function (content) {
                        if (content['action_result']==1) {
                            var count=$(this_this).next('span').html();
                            count++;
                            $(this_this).next('span').text(count);
                            $(this_this).addClass("liked").removeClass('like');
                            if (content['action']==1) {
                                $(this_this).siblings('.disliked').addClass("dislike").removeClass('disliked');
                                var dCount = $(this_this).siblings('.dislike').next('span').html();
                                dCount--;
                                $(this_this).siblings('.dislike').next('span').text(dCount);
                            }
                        }
                        $("body").find('.filterurl_loader').remove();
                    }
                });
            });

    /** ------ dislike answer */
            $(document).on('click','.dislike', function () {
                var ansid = $(this).attr('dataid');
                var logid = self.options.buyer_id;
                var this_this=$(this);
                var login=self.options.islogin;
                if (login==0) {
                    authenticationPopup.showModal();
                    return false;
                }
                $("body").append(jQuery("<div/>").addClass("filterurl_loader").append(jQuery("<div/>")));
                $.ajax({
                    url:self.options.reviewanswer_url,
                    data:{ansid:ansid,custid:logid,action:'dislike'},
                    success:function (content) {
                        if (content['action_result']==1) {
                            var count=$(this_this).next('span').html();
                            count++;
                            $(this_this).next('span').text(count);
                            $(this_this).addClass("disliked").removeClass('dislike');
                            if (content['action']==1) {
                                $(this_this).siblings('.liked').addClass("like").removeClass('liked');
                                var lCount = $(this_this).siblings('.like').next('span').html();
                                lCount--;
                                $(this_this).siblings('.like').next('span').text(lCount);
                            }
                        }
                        $("body").find('.filterurl_loader').remove();
                    }
                });

            });

            var qaAnsForm = $('#qa-ans-form');
                qaAnsForm.mage('validation', {});
            var qaQuesForm= $('#qa-ques-form');
                qaQuesForm.mage('validation', {});

            /** question modal */
            var options_ques = {
                type: 'popup',responsive: true,innerScroll: true,title: $t('Have Any Query?'),
                buttons: [{
                        text: $t('Reset'),
                        class:'',
                        click: function () {
                            $('#qa-ques-form input,#qa-ques-form textarea').removeClass('mage-error');
                            $('#qa-ques-form')[0].reset();
                        } /** handler on button click */
                    },{
                        text: $t('Submit Query'),
                        class: 'wk-question-submit',
                        click: function () {
                            /** -----save question */
                            var su = $('#sub').val();
                            var cn = $('#content').val();
                            var nickname=$('#qa_nickname').val();
                            var seller_id=self.options.seller_id ;
                            var adurl = $('#adminurl').val();
                            var nm = "";
                            if (qaQuesForm.valid()!=false) {
                                var thisthis = $(this);
                                $.ajax({
                                    url     :   self.options.question_url,
                                    type    :   "POST",
                                    data    :   {
                                                    pid:self.options.product_id,
                                                    subj:$('#sub').val(),
                                                    con:cn,
                                                    aurl:adurl,
                                                    nickname:nickname,
                                                    seller_id:seller_id,
                                                    form_key: $.cookie('form_key')
                                                },
                                    dataType:   "json",
                                    showLoader: true,
                                    success :   function (data) {
                                        $('#qa-ques-form')[0].reset();
                                        if (data.status) {
                                            alert({
                                                title: $t('Success!'),
                                                content: $t('Your query has been submitted.'),
                                                actions: {
                                                    always: function () {
                                                        location.reload();
                                                    }
                                                }
                                            });
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
                                    }
                                });
                                this.closeModal();
                            }
                        } /** handler on button click */
                    }
                ]
            };
            var popup = modal(options_ques, $('#wk-qa-ask-qa'));

            $(document).on('click','.qa-question', function () {
                var login=self.options.islogin;
                if (login==0) {
                    authenticationPopup.showModal();
                    return false;
                }

                $('#wk-qa-ask-qa').modal('openModal');
            });

            /** answer modal */
            var options_ans = {
                type: 'popup',responsive: true,innerScroll: true,title: $t('Submit Answer'),
                buttons: [{
                        text: $t('Reset'),
                        class:'',
                        click: function () {
                            $('#qa-ans-form input,#qa-ans-form textarea').removeClass('mage-error');
                            $('#qa-ans-form')[0].reset();
                        }
                    },{
                        text: $t('Submit Answer'),
                        class: 'wk-answer-submit',
                        click: function () {
                            /** -----save answer */
                            if (qaAnsForm.valid()!=false) {
                                var thisthis = $(this);
                                $.ajax({
                                    url:self.options.submitanswer_url,
                                    data:$('#qa-ans-form').serialize(),
                                    type:'post',
                                    showLoader: true,
                                    dataType:'json',
                                    success:function (d) {
                                        if (d.status) {
                                            alert({
                                                title: $t('Success!'),
                                                content: $t('Your answer has been submitted.'),
                                                actions: {
                                                    always: function () {
                                                        $('#qa-ans-form')[0].reset();
                                                        location.reload();
                                                    }
                                                }
                                            });
                                        } else {
                                            alert({
                                                title: 'Error!',
                                                content: 'You are not authorised to answer this question.',
                                                actions: {
                                                    always: function () {
                                                        $('#qa-ans-form')[0].reset();
                                                        location.reload();
                                                    }
                                                }
                                            });
                                        }
                                    }
                                });
                                this.closeModal();
                            }
                        }
                    }
                ]
            };
            var popup1 = modal(options_ans, $('#wk-qa-ask-data'));
            $(document).on('click','.qa-ans', function () {
                var login=self.options.islogin;
                if (login==0) {
                    authenticationPopup.showModal();
                    return false;
                }

                var q_id=$(this).parent().siblings('.alogo').attr('id');
                q_id=q_id.substring(2);

                $("#question-id").val(q_id);
                $('#wk-qa-ask-data').modal('openModal');
            });

            $(document).on('click','.action-close', function () {
                $('#qa-ques-form')[0].reset();
                $('#qa-ans-form')[0].reset();
            });
     /** pager link */
            var listItems = $("ul.items.pages-items li.item a");
            listItems.each(function (idx, a) {

                var link=$(a).attr("href");
                link=link+'#mpqa.tab';
                $(a).attr("href",link);
            });

            $('.search-form').submit(function () {
                var query = $('#wk-searchqa').val();
                if ($.trim(query) =='') {
                    event.preventDefault();
                }
            });

            /** Ajax Request */
            
            $(document).ready(function(){
                $.ajax({
                    url: self.options.ajaxurl,
                    cache: true,
                    type: "POST",
                    dataType: 'json'
                }).done(function (response) {
                    var QuestionArray = response['Ques'];
                    var QuesCount = QuestionArray.length;
                    var data = "";
                    $.each(QuestionArray, function (index, ItemData) {
                        var QuesArray = ItemData;
                        var AnsArray = ItemData['Ans'][0];
                        var anscount = ItemData['TotalAns'] - 1;
                        var QuesDate = $.datepicker.formatDate('M dd yy', new Date(QuesArray.created_at));
                        var AnsDate = $.datepicker.formatDate('M dd yy', new Date(AnsArray.created_at));
                        data += "<div  id='q-"+QuesArray.question_id+"'><div class='question'><div class='qlogo'><span>Q</span></div><div class='ques'><div class='subj'><strong><span>"+QuesArray.subject+"</span></strong></div><div class='cont'><span class='wk_prewrap'>"+QuesArray.content+"</span></div><div class='ques-user-info'><span>by </span><span class='wk_bold'>"+QuesArray.qa_nickname+"</span><span> on </span><span>"+QuesDate+"</span><br/></div></div></div><div class='answer'><div class='alogo' id='q-"+AnsArray.question_id+"'><span>A</span></div><div class='answ'><span class='wk_prewrap'>"+AnsArray.content+"</span><div class='user-info'><span>by </span><span class='wk_bold'>"+AnsArray.respond_nickname+"</span><span> on </span><span>"+AnsDate+"</span><br><div class='reviews'><span class='like' title='like' dataid='"+AnsArray.answer_id+"'></span><span>"+AnsArray.like_dislike+"</span><span class='dislike' title='dislike' dataid='"+AnsArray.answer_id+"'></span><span>0</span></div></div></div>"+ (anscount > 0 ? "<div class='answ more'><a class='qa-ansmore' dataid='"+AnsArray.question_id+"'><span>View More("+anscount+")</span></a></div>": " ") +"</div></div>";
                    });
                    var quescountdata = "";
                    quescountdata += "<span>"+QuesCount+"</span>";
                    $('#ajaxquestion-answertemplate').html(data);
                    $('#wk-header-quescount').html(quescountdata);    
                });
            });
        },

    });
    return $.mage.mpqa_tab;
});
