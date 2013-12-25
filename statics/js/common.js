(function($) {
    //弹窗显隐
    var dialogTime = 300;
    function displayDialog(clickElem, dialogClassName, closeELem) {
        $(document).on('click', clickElem, function(e) {
            e.preventDefault();
            $('<div class="mask1000"></div>').appendTo('body').width($(window).width()).height($(document).height()).fadeIn(dialogTime, function() {
            });
            $('.' + dialogClassName).fadeIn(dialogTime);
        });
        $(document).on('click', closeELem, function(e) {
            e.preventDefault();
            $('.' + dialogClassName).fadeOut(dialogTime, function() {
                $('.mask1000').fadeOut(dialogTime).remove();
            });
        });
    }
    //申请内测弹窗
    displayDialog('.apply', 'j-apply', '.j-apply-close');
    //登录弹窗
    displayDialog('.login a', 'j-login', '.j-login-close');

    //删除弹窗
    displayDialog('.delete, .article-opearte-delete', 'j-delete-confirm', '.j-delete-close, .j-delete-cancel, .j-delete-ok');

    //消息中心显隐
    var messageId,
            menuId;

    $(document).on('mouseenter', '.message-num', renderMessagesBoard).on('mouseleave', '.message-num', function(e) {
        e.preventDefault();
        clearTimeout(messageId);
        messageId = window.setTimeout(function() {
            $('.j-message').stop().slideUp();
        }, 200);
    });

    $(document).on('mouseenter', '.j-message', function() {
        clearTimeout(messageId);
        messageId = window.setTimeout(function() {
            $('.j-message').stop().slideDown();
        }, 200);
    });
    $(document).on('mouseleave', '.j-message', function() {
        clearTimeout(messageId);
        messageId = window.setTimeout(function() {
            $('.j-message').stop().slideUp();
        }, 200);
    });

    function renderMessagesBoard(e) {
        var url = "/msg/getNewMessage";

        $.get(url, renderMessages, 'json');

        e.preventDefault();
        clearTimeout(messageId);
        messageId = window.setTimeout(function() {
            $('.j-message').stop().slideDown();
        }, 200);
    }

    function renderMessages(data) {
        if (data.code !== 0) {
            return;
        }

        var messageList = $('#message-list');
        messageList.empty();

        var messages = data.data.message;
        var messageAmount = data.data.messageCount;

        $.each(messages, function(index, message) {
            if (message.count > 1) {
                message.senderName = message.senderName + '等' + message.count + '人';
            }

            if (message.messageType == 1) {
                var li = $('<li class="message-item">' + message.senderName + '喜欢了你的日记<a href="" title="' + message.diaryTitle + '">' + message.diaryTitle + '</a></li>');
            } else if (message.messageType == 2) {
                var li = $('<li class="message-item"><a href="" title="' + message.diaryTitle + '">' + message.diaryTitle + '</a>有了' + message.count + '条评论</li>');
            } else if (message.messageType == 3) {
                var li = $('<li class="message-item">' + message.senderName + '给你<a href="#" title="留言">留言</a>了</li>');
            } else if (message.messageType == 4) {
                var li = $('<li class="message-item">' + message.senderName + '<a href="#" title="fond">关注</a>了你</li>');
            }

            li.attr({'message-id': message.messageId, 'diary-id': message.diaryId, 'message-type': message.messageType});
            li.bind('click', {messageId: messageId, diaryId: message.diaryId, messageType: message.messageType}, readMessage);
            messageList.append(li);
        });

        if (messageAmount > 0) {
            $('.message-num').text(messageAmount).attr('title', messageAmount + '条新消息').css('background', '#ff9710');
        }
    }

    function readMessage(event) {
        var url = "/msg/readMessage";

        $.post(url, event.data);
    }

    $(document).ready(checkNewMessage);

    function checkNewMessage() {
        var url = "/msg/checkNewMessage";

        $.get(url, renderMesssageAmount, 'json');
    }

    function renderMesssageAmount(data) {
        if (data.code !== 0) {
            return;
        }

        var msgAmount = data.data;
        if (msgAmount <= 0) {
            $('.message-num').text(0).attr('title', 0 + '条新消息').css('background', '#fff');
            return;
        }

        $('.message-num').text(msgAmount).attr('title', msgAmount + '条新消息').css('background', '#ff9710');
    }

    //申请内测表单显隐
    $('.choose input').on('click', function() {
        this.value === 'yes' ? $('.choose-item').show() : $('.choose-item').hide();
    });

    //个人信息菜单显隐
    $(document).on('mouseenter', '.info-username', function(e) {
        e.preventDefault();
        clearTimeout(menuId);
        menuId = window.setTimeout(function() {
            $('.user-menu').stop().slideDown();
        }, 200);
    }).on('mouseleave', '.info-username', function(e) {
        e.preventDefault();
        clearTimeout(menuId);
        menuId = window.setTimeout(function() {
            $('.user-menu').stop().slideUp();
        }, 200);
    });

    //主页年月按钮
    $('.home-date-btn').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('.home-date-wrap').toggle();
    });

    //首页轮播
    $.easing.easeOutBack = function(e, f, a, i, h, g) {
        if (g == undefined) {
            g = 1.70158;
        }
        return i * ((f = f / h - 1) * f * ((g + 1) * f + g) + 1) + a;
    };

    var indexCrouselNum = 0,
            indexCrouselLimit = $('.index-img-item').length;

    $('.main-img .previous').on('click', function(e) {
        e.preventDefault();
        if (indexCrouselNum - 1 >= 0) {
            indexCrousel(--indexCrouselNum, -800, 400, 300, 1000, 800);
        } else if (indexCrouselNum - 1 === -1) {
            var temp = $('.index-img-item').last().clone(true);
            $('.index-img-list').css('paddingLeft', 0).prepend(temp);
            indexCrousel(--indexCrouselNum, -800, 400, 300, 1000, 800, function() {
                $('.index-img-list').css({paddingLeft: 560, left: -560 * indexCrouselLimit});
                temp.remove();
                indexCrouselNum = indexCrouselLimit - 1;
            });
        }
    });

    $('.main-img .next').on('click', function(e) {
        e.preventDefault();
        if (indexCrouselNum + 1 < indexCrouselLimit) {
            indexCrousel(++indexCrouselNum, 400, 350, 450, 800, 1000);
        } else if (indexCrouselNum + 1 === indexCrouselLimit) {
            var temp = $('.index-img-item').eq(0).clone(true);
            $('.index-img-list').append(temp);
            indexCrousel(++indexCrouselNum, 400, 350, 450, 800, 1000, function() {
                $('.index-img-list').css('left', -560);
                temp.remove();
                indexCrouselNum = 0;
            });
        }
    });

    function indexCrousel(num, left, textTime, infoTime, textSpan, infoSpan, callback) {
        var text = $('.index-img-item').eq(num === -1 ? 0 : num).find('.main-wz'),
                info = $('.index-img-item').eq(num === -1 ? 0 : num).find('.main-img-info');
        text.css("left", left);
        info.css("left", left);
        $('.index-img-list').animate({left: -(num + 1) * 560}, 500);
        window.setTimeout(function() {
            text.animate({left: 0}, textSpan, 'easeOutBack');
        }, textTime)
        window.setTimeout(function() {
            info.animate({left: 0}, infoSpan, 'easeOutBack', function() {
                callback && callback();
            });
        }, infoTime)
    }


    //图片本地预览
    function previewImage(obj) {
        if (!document.attachEvent) {
            var fileList = obj.files;
            filetype = /^image\/(?:png|gif|jpeg)$/i;
            for (var i = 0, l = fileList.length; i < l; i++) {
                var file = fileList[i];
                if (!file.type.match(filetype)) {
                    continue;
                }
                var reader = new FileReader();
                reader.onload = function(e) {
                    var fileName = e.target.result,
                            img = $('.note-img');
                    $(obj).prev().attr('src', fileName);
                    img.width() > img.height() ? img.width(288) : img.height(288);
                    img.show();
                };
                reader.readAsDataURL(file);
            }
        } else {
            obj.select();
            obj.blur();
            var fileName = document.selection.createRange().text;
            $(obj).parent().attr('style', 'filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale);');
            $(obj).parent().get(0).filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = fileName;
            $(obj).prev().hide();
        }
    }

    $('.note-img-upload').on('change', function() {
        $('.note-img-tips').hide();
        previewImage(this);
    });



    //主页年份切换
    var maxYear = new Date().getFullYear(),
            maxMonth = new Date().getMonth() + 1,
            maxDate = new Date().getDate(),
            selectedDate = {
                year: maxYear,
                month: maxMonth
            };
    $('.home-note-month').html(selectedDate.year + '年<em>' + selectedDate.month + '月</em>');
    $('.home-year-num').text(selectedDate.year + '年');

    $('.home-year-next').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var year = window.parseInt($('.home-year-num').text()) + 1;
        if (year <= maxYear) {
            $('.home-year-num').text(year + '年');
            createMonthList();
        }
    });

    $('.home-year-prev').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var year = window.parseInt($('.home-year-num').text()) - 1;
        $('.home-year-num').text(year + '年');
        createMonthList();
    });

    $('.home-date-wrap').on('click', '.home-month-selectable', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('.home-month-selected').addClass('home-month-selectable').removeClass('home-month-selected')
        $(this).addClass('home-month-selected').removeClass('home-month-selectable');
        updateSelectedDate();
        $('.home-note-month').html(selectedDate.year + '年<em>' + selectedDate.month + '月</em>');
        if (selectedDate.year === maxYear && selectedDate.month === maxMonth) {
            $('.home-list-next:visible').hide();
        } else {
            $('.home-list-next:hidden').show();
        }
        var monthAsParam = selectedDate.month > 9 ? selectedDate.month : '0' + selectedDate.month;

        location.href = '/home/index/month/' + selectedDate.year + monthAsParam;

    });

    $('.home-list-prev').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        if (!$('.home-month-selected').length) {
            createMonthList(selectedDate.year);
        }
        var num = $('.home-month-selected').parent().index();
        if (num === 0) {
            $('.home-year-prev').trigger('click');
            $('.home-month-item').last().find('a').trigger('click');
        } else {
            $('.home-month-selected').parent().prev().find('a').trigger('click');
        }

        updateSelectedDate();
    });

    $('.home-list-next').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        if (!$('.home-month-selected').length) {
            createMonthList(selectedDate.year);
        }
        var num = $('.home-month-selected').parent().index();

        if (num === 11) {
            $('.home-year-next').trigger('click');
            $('.home-month-item').first().find('a').trigger('click');
        } else {
            $('.home-month-selected').parent().next().find('a').trigger('click');
        }

        updateSelectedDate();
        if (selectedDate.year === maxYear && selectedDate.month === maxMonth) {
            $(this).hide();
        }
    });

    //更新当前选中年月
    function updateSelectedDate() {
        selectedDate.year = window.parseInt($('.home-year-num').html());
        selectedDate.month = window.parseInt($('.home-month-selected').html());
    }

    //创建月份html列表
    function createMonthList(y) {
        var list = '',
                year = y || window.parseInt($('.home-year-num').text());


        if (year !== maxYear) {
            for (var i = 1; i <= 12; i++) {
                list += '<li class="home-month-item"><a href="#" class="home-month-num home-month-selectable" title="' + i + '月">' + i + '月</a></li>';
            }
        } else {
            for (var i = 1; i <= maxMonth; i++) {
                list += '<li class="home-month-item"><a href="#" class="home-month-num home-month-selectable" title="' + i + '月">' + i + '月</a></li>';
            }
            for (; i <= 12; i++) {
                list += '<li class="home-month-item"><a href="#" class="home-month-num" title="' + i + '月">' + i + '月</a></li>';
            }
        }

        if (y) {
            $('.home-year-num').text(selectedDate.year + '年');
        }

        $('.home-month-wrap').html(list);

        if (year === selectedDate.year) {
            $('.home-month-item').eq(selectedDate.month - 1).find('a').removeClass('home-month-selectable').addClass('home-month-selected');
        }

    }

    createMonthList();


    //copy
    $(".copy-btn").click(function() {
        if (window.clipboardData) {
            var text = $(this).parent().find(".copy-info").val();
            window.clipboardData.setData('text', text);
            alert("已成功到剪贴板");
        }
        else {
            alert("浏览器不支持此功能,请您手动复制");
        }

    });

    //留言板+评论的回复按钮
    $('.article-detail,.leave-message-wrap').on('click', '.reply', function(e) {
        e.preventDefault();
        debugger;
        $('.comment-text').val('回复 ' + $(this).closest('.comment-item').find('.article-author').html());
        $('#follow_id').val($(this).attr('alt'));
    });
    //留言板的删除按钮
    $('.article-detail').on('click', '.delete', function(e) {
        e.preventDefault();
        debugger;
        $('.comment-text').val('回复 ' + $(this).closest('.comment-item').find('.article-author').html());
        $('#follow_id').val($(this).attr('alt'));
    });
    //评论的删除按钮
    $('.article-detail,.leave-message-wrap').on('click', '.reply', function(e) {
        e.preventDefault();
        debugger;
        $('.comment-text').val('回复 ' + $(this).closest('.comment-item').find('.article-author').html());
        $('#follow_id').val($(this).attr('alt'));
    });


    //发布页标签处理(自动扩展文本框)
    //文本输入检测事件
    $(document).on('keydown', '.otheritem input', function(e) {
        var _this = $(this);
        if ((e.keyCode === 186 || e.keyCode === 188 || e.keyCode === 32 || e.keyCode === 13) && _this.val() !== "") {
            addExtendItem(_this);
        }
    });
    //文本框失去焦点事件
    $(document).on('blur', '.otheritem input', function(e) {
        var _this = $(this);
        if (_this.val() !== "") {
            addExtendItem(_this);
        }
    });
    //获得焦点
    $('.otheritem').on('click', function() {
        $('.otheritem input').focus();
    });
    //移出扩展项
    $(document).on('click', '.extendclose', function(e) {
        e.preventDefault();
        $(this).parent().remove();
        $('.otheritem input').show();
    });
    //文本框内容转换扩展项
    function addExtendItem(_this) {
        _this.parent().before('<li class="extenditem"><label>' + _this.val() + '</label><a class="extendclose" href="#" title="移除此项"></a><input name="' + _this.attr('name') + '" value="' + _this.val() + '" style="display:none;"></li>')
        setTimeout(function() {
            _this.val('')
        }, 0);
        if ($('.extenditem').length >= 5) {
            _this.hide();
        }
    }
    //$('.note-text-label')


    //同步按钮
    $('.note-sync-wrap .login-sina').on('click', function(e) {
        e.preventDefault();
        if ($(this).hasClass('login-sina-close')) {
            $(this).removeClass('login-sina-close').attr('title', '已开启同步到新浪,点击关闭')
        } else {
            $(this).addClass('login-sina-close').attr('title', '已关闭同步到新浪,点击开启')
        }
    });

    $('.note-sync-wrap .login-dou').on('click', function(e) {
        e.preventDefault();
        if ($(this).hasClass('login-dou-close')) {
            $(this).removeClass('login-dou-close').attr('title', '已开启同步到豆瓣,点击关闭')
        } else {
            $(this).addClass('login-dou-close').attr('title', '已关闭同步到豆瓣,点击开启')
        }
    });

    //点击空白关闭日期浮层
    $(document).on('click', '.home-note-wrap', function(e) {
        $('.home-date-wrap').hide();
    });

    //
    $(document).on('click', '.modify-description', function(e) {
        e.preventDefault();
        $('.user-description-wrap').find('p').hide();
        $('.user-description-wrap').prepend('<textarea maxlength="70" class="modify-textarea"></textarea>');
        $('.modify-textarea').focus();
    });
    $(document).on('blur', '.modify-textarea', function() {
        $('.user-description-wrap').find('p').text(this.value);
        $(this).remove();
        $('.user-description-wrap').find('p').show();
    });

    //日记分享按钮
    $('.article-icon-other').on('click', function(e) {
        e.preventDefault();
        $('.article-share').fadeToggle();
    });

    //日记分享按钮
    $('.article-icon-zan').on('click', function(e) {
        e.preventDefault();
        var diaryId = $(this).attr('alt');
        $.ajax({
            url: encodeURI('/userdiary/replaceRelation/relation/1/diary_id/' + diaryId),
            type: 'GET',
            dataType: 'json',
            async: false,
            success: function(json) {

            }
        });
    });








//事件相关
    var event = {
        //绑定事件
        on: function(elem, type, callback) {
            if (document.addEventListener) {
                event.on = function(elem, type, callback) {
                    elem.addEventListener(type, callback, false);
                }
            } else {
                event.on = function(elem, type, callback) {
                    elem.attachEvent('on' + type, callback);
                }
            }
            event.on(elem, type, callback);
        },
        //解绑事件
        off: function(elem, type, callback) {
            if (document.removeEventListener) {
                event.off = function(elem, type, callback) {
                    elem.removeEventListener(type, callback);
                }
            } else {
                event.off = function(elem, type, callback) {
                    elem.detachEvent('on' + type, callback);
                }
            }
            event.off(elem, type, callback);
        }
    };

    //dom操作相关
    var dom = {
        //获取css属性值
        getCss: function(elem, cssProperty) {
            if (window.getComputedStyle) {
                dom.getCss = function(elem, cssProperty) {
                    return window.getComputedStyle(elem, null).getPropertyValue(cssProperty);
                };
            } else {
                dom.getCss = function(elem, cssProperty) {
                    return elem.currentStyle.getAttribute(cssProperty);
                };
            }
            return dom.getCss(elem, cssProperty);
        },
        //获取纯数字css属性值
        getCssNum: function(elem, cssProperty) {
            return window.parseInt(dom.getCss(elem, cssProperty), 10)
        }
    };

    if ($('.setting-head-image').length !== 0) {
        commonImageClip({
            formatChage: false,
            imgScale: false,
            imgDrag: false,
            clipWindowScale: false,
            clipWindow: $('.setting-head-clipwindow')[0],
            img: $('.setting-head-image')[0],
            wrap: $('.setting-head-wrap')[0]
        })
    }

    //图片裁剪
    function commonImageClip(o) {

        //剪裁框
        var clipWindow = o && o.clipWindow || $('clip-window'),
                //被剪裁图片
                img = o && o.img || $('img'),
                //外围容器
                wrap = o && o.wrap || $('wrap'),
                //剪裁框缩放按钮
                scale = o && o.scale || $('clip-window-scale'),
                //图片切换横版按钮
                hengBtn = o && o.hengBtn || $('heng'),
                //图片切换竖版按钮
                shuBtn = o && o.shuBtn || $('shu'),
                //图片放大按钮
                imgMaxBtn = o && o.imgMaxBtn || $('img-max'),
                //图片缩小按钮
                imgMinBtn = o && o.imgMinBtn || $('img-min'),
                //拖拽过程中元素透明度
                dragingOpacity = o && o.dragingOpacity || 0.7,
                //图片每次放大/缩小的跨度(px)
                imgScaleSpan = o && o.imgScaleSpan || 50,
                //触发图片拖拽的元素
                imgDragTriggerElem = img,
                //被剪裁图片副本(用于遮罩显示)
                cliped,
                //存储拖拽偏移数据
                offsetDetail = {},
                clipControl = {
                    //是否开启剪裁框版式转换功能
                    formatChage: o && o.formatChage !== undefined ? o.formatChage : true,
                    //是否开启被裁减图片缩放功能
                    imgScale: o && o.imgScale !== undefined ? o.imgScale : true,
                    //是否开启被裁减图片拖拽功能
                    imgDrag: o && o.imgDrag !== undefined ? o.imgDrag : true,
                    //是否开启裁减框拖拽功能
                    clipWindowDrag: o && o.clipWindowDrag !== undefined ? o.clipWindowDrag : true,
                    //是否开启裁减框缩放功能
                    clipWindowScale: o && o.clipWindowScale !== undefined ? o.clipWindowScale : true,
                    //是否开启裁减框遮罩功能
                    clipWindowMask: o && o.clipWindowMask !== undefined ? o.clipWindowMask : true
                },
        opacityControl = {
            //是否开启图片拖拽透明变化
            imgDragOpacity: o && o.imgDragOpacity !== undefined ? o.imgDragOpacity : true,
            //是否开启剪裁窗拖拽透明变化
            clipWindowDragOpacity: o && o.clipWindowDragOpacity !== undefined ? o.clipWindowDragOpacity : true,
            //是否开启剪裁窗缩放按钮透明变化
            clipWindowScaleOpacity: o && o.clipWindowScaleOpacity !== undefined ? o.clipWindowScaleOpacity : true
        },
        //横版数值范围(4:3)
        clipWindowHengLimit = {
            minWidth: o && o.minWidthHeng || 220,
            minHeight: o && o.minHeightHeng || 165,
            maxWidth: o && o.maxWidthHeng || 400,
            maxHeight: o && o.maxHeightHeng || 300,
            heng: true
        },
        //竖版数值范围(3:4)
        clipWindowShuLimit = {
            minWidth: o && o.minWidthShu || 165,
            minHeight: o && o.minHeightShu || 220,
            maxWidth: o && o.maxWidthShu || 225,
            maxHeight: o && o.maxHeightShu || 300,
            heng: false
        },
        wrapWidth = wrap.clientWidth,
                wrapHeight = wrap.clientHeight,
                imgWidth = img.clientWidth,
                imgHeight = img.clientHeight,
                ratio;

        //初始化被剪裁图片
        clipWindowScaleLimit = clipWindowHengLimit;

        if (imgWidth > wrapWidth) {
            ratio = wrapWidth / imgWidth;
            img.width = wrapWidth;
            img.height = imgHeight * ratio;
        } else if (imgHeight > wrapHeight) {
            ratio = wrapHeight / imgHeight;
            img.height = wrapHeight;
            img.width = imgWidth * ratio;
        }

        //根据配置项自动生成遮罩或修复IE下BUG
        if (clipControl.clipWindowMask) {
            var clipMask = document.createElement('div');
            clipMask.id = "clip-mask";
            clipMask.className = "clip-mask";
            wrap.appendChild(clipMask);
            imgDragTriggerElem = clipMask;

            cliped = document.createElement('img');
            cliped.id = 'clipedImg';
            cliped.className = 'clipedImg';
            cliped.src = img.src;
            clipWindow.appendChild(cliped);

            cliped.width = img.width;
            cliped.height = img.height; //为修复IE下此处高度不随宽度自动等比缩放的奇怪BUG
            cliped.style.left = dom.getCssNum(cliped, 'left') - dom.getCssNum(clipWindow, 'left') + 'px';
            cliped.style.top = dom.getCssNum(cliped, 'top') - dom.getCssNum(clipWindow, 'top') + 'px';
        } else {
            //加个透明层 用于修复IE下 鼠标处于裁剪框内 非边框上方时无法拖拽的bug
            var clipContent = document.createElement('div');
            clipContent.className = 'clip-transparent-content';
            clipWindow.appendChild(clipContent);
        }

        if (clipControl.formatChage && hengBtn && shuBtn) {
            console.log(1);
            //切换到横版裁剪框
            event.on(hengBtn, 'click', function() {
                var width = clipWindow.clientWidth,
                        height = clipWindow.clientHeight;
                clipWindowScaleLimit = clipWindowHengLimit;
                if (width < height) {
                    clipWindow.style.width = clipWindowScaleLimit.minWidth + 'px';
                    clipWindow.style.height = clipWindowScaleLimit.minHeight + 'px';
                }
            });
            //切换到竖版裁剪框
            event.on(shuBtn, 'click', function() {
                var width = clipWindow.clientWidth,
                        height = clipWindow.clientHeight;
                clipWindowScaleLimit = clipWindowShuLimit;
                if (width > height) {
                    clipWindow.style.width = clipWindowScaleLimit.minWidth + 'px';
                    clipWindow.style.height = clipWindowScaleLimit.minHeight + 'px';
                }
            });
        }

        if (clipControl.imgScale && imgMaxBtn && imgMinBtn) {
            //被剪裁图片最大缩放控制
            event.on(imgMaxBtn, 'click', function(e) {
                e = e || window.event;
                e.preventDefault ? e.preventDefault() : e.returnValue = false;
                var temp = img.width + imgScaleSpan;
                if (temp <= wrapWidth) {
                    ratio = temp / imgWidth;
                    img.width = temp;
                    img.height = imgHeight * ratio;

                    if (clipControl.clipWindowMask) {
                        cliped.width = temp;
                        cliped.height = imgHeight * ratio;
                    }
                }
            });
            //被剪裁图片最小缩放控制
            event.on(imgMinBtn, 'click', function(e) {
                e = e || window.event;
                e.preventDefault ? e.preventDefault() : e.returnValue = false;
                var temp = img.width - imgScaleSpan;
                if (temp >= clipWindowScaleLimit.minWidth) {
                    ratio = temp / imgWidth;
                    img.width = temp;
                    img.height = imgHeight * ratio;
                    if (clipControl.clipWindowMask) {
                        cliped.width = temp;
                        cliped.height = imgHeight * ratio;
                    }
                }
            });
        }

        //被剪裁图片拖拽处理
        if (clipControl.imgDrag) {
            event.on(imgDragTriggerElem, 'mousedown', imgMousedown);
        }

        function imgMousedown(e) {
            e = e || window.event;
            e.preventDefault ? e.preventDefault() : e.returnValue = false;
            if (opacityControl.imgDragOpacity) {
                img.style.opacity = dragingOpacity;
            }
            offsetDetail.originX = dom.getCssNum(img, 'left');
            offsetDetail.originY = dom.getCssNum(img, 'top');
            offsetDetail.originMouseX = e.clientX;
            offsetDetail.originMouseY = e.clientY;
            if (clipControl.clipWindowMask) {
                offsetDetail.originClipedX = dom.getCssNum(cliped, 'left');
                offsetDetail.originClipedY = dom.getCssNum(cliped, 'top');
            }
            event.on(document, 'mousemove', imgMousemove);
            event.on(document, 'mouseup', imgMouseup);
        }

        function imgMousemove(e) {
            e = e || window.event;
            e.preventDefault ? e.preventDefault() : e.returnValue = false;
            img.style.left = offsetDetail.originX + e.clientX - offsetDetail.originMouseX + 'px';
            img.style.top = offsetDetail.originY + e.clientY - offsetDetail.originMouseY + 'px';
            if (clipControl.clipWindowMask) {
                cliped.style.left = offsetDetail.originClipedX + e.clientX - offsetDetail.originMouseX + 'px';
                cliped.style.top = offsetDetail.originClipedY + e.clientY - offsetDetail.originMouseY + 'px';
            }
        }

        function imgMouseup(e) {
            if (opacityControl.imgDragOpacity) {
                img.style.opacity = 1;
            }
            event.off(document, 'mousemove', imgMousemove);
            event.off(document, 'mouseup', imgMouseup);
            offsetDetail = {};
        }

        //剪裁框拖拽处理
        if (clipControl.clipWindowDrag) {
            event.on(clipWindow, 'mousedown', clipWindowMousedown);
        }

        function clipWindowMousedown(e) {
            e = e || window.event;
            e.preventDefault ? e.preventDefault() : e.returnValue = false;
            e.stopPropagation ? e.stopPropagation() : e.cancelBubble = true;
            if (opacityControl.clipWindowDragOpacity) {
                clipWindow.style.opacity = dragingOpacity;
            }
            offsetDetail.originX = dom.getCssNum(clipWindow, 'left');
            offsetDetail.originY = dom.getCssNum(clipWindow, 'top');
            offsetDetail.originMouseX = e.clientX;
            offsetDetail.originMouseY = e.clientY;
            if (clipControl.clipWindowMask) {
                offsetDetail.originClipedX = dom.getCssNum(cliped, 'left');
                offsetDetail.originClipedY = dom.getCssNum(cliped, 'top');
            }
            offsetDetail.minX = 0;
            offsetDetail.maxX = wrap.clientWidth - clipWindow.offsetWidth;
            offsetDetail.minY = 0;
            offsetDetail.maxY = wrap.clientHeight - clipWindow.offsetHeight;
            event.on(document, 'mousemove', clipWindowMousemove);
            event.on(document, 'mouseup', clipWindowMouseup);
        }

        function clipWindowMousemove(e) {
            e = e || window.event;
            e.preventDefault ? e.preventDefault() : e.returnValue = false;
            var x = offsetDetail.originX + e.clientX - offsetDetail.originMouseX,
                    y = offsetDetail.originY + e.clientY - offsetDetail.originMouseY;

            if (offsetDetail.minX <= x && x <= offsetDetail.maxX) {
                clipWindow.style.left = x + 'px';
            } else if (x < offsetDetail.minX) {
                clipWindow.style.left = offsetDetail.minX + 'px';
            } else {
                clipWindow.style.left = offsetDetail.maxX + 'px';
            }

            if (offsetDetail.minY <= y && y <= offsetDetail.maxY) {
                clipWindow.style.top = y + 'px';
            } else if (y < offsetDetail.minY) {
                clipWindow.style.top = offsetDetail.minY + 'px';
            } else {
                clipWindow.style.top = offsetDetail.maxY + 'px';
            }

            if (clipControl.clipWindowMask) {
                cliped.style.left = offsetDetail.originClipedX - dom.getCssNum(clipWindow, 'left') + offsetDetail.originX + 'px';
                cliped.style.top = offsetDetail.originClipedY - dom.getCssNum(clipWindow, 'top') + offsetDetail.originY + 'px';
            }
        }

        function clipWindowMouseup(e) {
            if (opacityControl.clipWindowDragOpacity) {
                clipWindow.style.opacity = 1;
            }
            event.off(document, 'mousemove', clipWindowMousemove);
            event.off(document, 'mouseup', clipWindowMouseup);
            offsetDetail = {};
        }

        //剪裁框等比缩放拖拽处理
        if (clipControl.clipWindowScale) {
            event.on(scale, 'mousedown', scaleMousedown);
        }

        function scaleMousedown(e) {
            e = e || window.event;
            e.preventDefault ? e.preventDefault() : e.returnValue = false;
            e.stopPropagation ? e.stopPropagation() : e.cancelBubble = true;
            if (opacityControl.clipWindowScaleOpacity) {
                scale.style.opacity = dragingOpacity;
            }
            offsetDetail.originMouseX = e.clientX;
            offsetDetail.originMouseY = e.clientY;
            offsetDetail.originWidth = clipWindow.clientWidth;
            offsetDetail.originHeight = clipWindow.clientHeight;
            event.on(document, 'mousemove', scaleMousemove);
            event.on(document, 'mouseup', scaleMouseup);
        }

        function scaleMousemove(e) {
            e = e || window.event;
            e.preventDefault ? e.preventDefault() : e.returnValue = false;

            var x = offsetDetail.originWidth + e.clientX - offsetDetail.originMouseX;

            if (x < clipWindowScaleLimit.minWidth) {
                x = clipWindowScaleLimit.minWidth;
            } else if (clipWindowScaleLimit.maxWidth < x) {
                x = clipWindowScaleLimit.maxWidth;
            }

            clipWindow.style.width = x + 'px';
            clipWindow.style.height = (clipWindowScaleLimit.heng ? x * 0.75 : x / 0.75) + 'px';
        }

        function scaleMouseup(e) {
            if (opacityControl.clipWindowScaleOpacity) {
                scale.style.opacity = 1;
            }
            event.off(document, 'mousemove', scaleMousemove);
            event.off(document, 'mouseup', scaleMouseup);
            offsetDetail = {};
        }

    }


})(jQuery);
