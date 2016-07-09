$(document).ready(function () {

    $(window).load(function () {
        
        $('.nav-bar').addClass('show_navbar').show();
    })
    $(window).resize(function () {
        if ($('body').width() < 926) {
            $('.nav-item').width($('.nav-bar').width() - 108);
        } else {
            $('.nav-item').width($('.nav-bar').width() - 192);
        }

    });

    $(document).resize(function () {
        if ($('body').width() < 926) {
            $('.nav-item').width($('.nav-bar').width() - 108);
        } else {
            $('.nav-item').width($('.nav-bar').width() - 192);
        }

    });

    $('.dropdownMenubtn').on('mouseenter', function () {
        var id = $(this).attr('id');
        $('.' + id).addClass('open');
    });
    $('.nav-item').width($('.nav-bar').width() - (2 * $('.login-item').width()) - 9);
    $('body').ajaxStart(function () {
        $(this).css({'cursor': 'wait'});
    }).ajaxStop(function () {
        $(this).css({'cursor': 'default'});
    });
    $('.login-item').click(function (e) {
        closeMenu();
        $('.search-item').css('background', 'rgba(90, 156, 144, 0.7)');
        if (!$('.loginpanel').hasClass('showpanel')) {
            $('.searchpanel, .dropdown').removeClass('showpanel');
            $('.loginpanel').addClass('showpanel');
            $(this).css('background', 'rgba(255,255,255,0.4)');
        } else if (!$(e.target).is('.loginpanel , .loginpanel *')) {
            $('.loginpanel').removeClass('showpanel');
            $(this).css('background', 'rgba(90, 156, 144, 0.7)');
        }

    });
    $('.search-item').click(function (e) {
        closeMenu();
        $('.login-item').css('background', 'rgba(90, 156, 144, 0.7)');
        if (!$('.searchpanel').hasClass('showpanel')) {
            $('.loginpanel , .dropdown').removeClass('showpanel');
            $('.searchpanel').addClass('showpanel');
            $(this).css('background', 'rgba(255,255,255,0.4)');
        } else if (!$(e.target).is('.searchpanel , .searchpanel *')) {
            $('.searchpanel').removeClass('showpanel');
            $(this).css('background', 'rgba(90, 156, 144, 0.7)');
        }
    });
    function closeMenu() {
        $('.rd-mobilepanel_toggle').children().first().removeClass('toggle_hover1')
                .next().removeClass('toggle_hover2')
                .next().removeClass('toggle_hover3');
        $('.rd-mobilepanel_toggle').removeClass('rd-mobilepanel_toggle_hover');
        $('.nav-list').removeClass('nav-list_show');
    }
    $(document).click(function (e) {
        if (!$(e.target).is('.search-item , .search-item *  , .login-item , .login-item * ,.rd-mobilepanel_toggle , .rd-mobilepanel_toggle * , .nav-list ,.nav-list * , .dropdown')||$(e.target).is('#reg2')) {
            $('.loginpanel , .searchpanel , .dropdown').removeClass('showpanel');
            $('.search-item , .login-item').css('background', 'rgba(90, 156, 144, 0.7)');
            closeMenu();
        }
    });
    $('.rd-mobilepanel_toggle').click(function () {
        $('.dropdown , .loginpanel ,.searchpanel').removeClass('showpanel');
        if (!$(this).hasClass('rd-mobilepanel_toggle_hover')) {
            $('.rd-mobilepanel_toggle').children().first().addClass('toggle_hover1')
                    .next().addClass('toggle_hover2')
                    .next().addClass('toggle_hover3');
            $(this).addClass('rd-mobilepanel_toggle_hover');
            $('.nav-list').addClass('nav-list_show');
        } else {
            closeMenu();
        }
    });
    $('.dropdown-btn').click(function (e) {
        e.preventDefault();
        var href = $(this).attr('href');
        $('.search-item , .login-item').css('background', 'rgba(90, 156, 144, 0.7)');
        if (!$(href).hasClass('showpanel')) {
            $('.dropdown , .loginpanel ,.searchpanel').removeClass('showpanel');
            $(href).addClass('showpanel');
            $('.nav-list').removeClass('nav-list_show');
        } else {
            $(href).removeClass('showpanel');
        }
    });
});

$(document).ready(function () {

    $('#myCarousel .item').each(function () {
        var next = $(this).next();
        if (!next.length) {
            next = $(this).siblings(':first');
        }
        next.children(':first-child').clone().appendTo($(this));

        for (var i = 0; i < 2; i++) {
            next = next.next();
            if (!next.length) {
                next = $(this).siblings(':first');
            }

            next.children(':first-child').clone().appendTo($(this));
        }
    });
    $('#myCarousel2 .item').each(function () {
        var next = $(this).next();
        if (!next.length) {
            next = $(this).siblings(':first');
        }
        next.children(':first-child').clone().appendTo($(this));

        for (var i = 0; i < 2; i++) {
            next = next.next();
            if (!next.length) {
                next = $(this).siblings(':first');
            }

            next.children(':first-child').clone().appendTo($(this));
        }
    });
    $('[data-toggle="tooltip"]').tooltip()
    $('.scroll').click(function (e) {
        e.preventDefault();
        $("html, body").animate({scrollTop: $($(this).attr('href')).offset().top - 30}, 600);
    });
});

$(document).ready(function () {
    $('#submit').click(function (e) {
        e.preventDefault();
        $('#contact-form .invalid').removeClass('invalid');
        if ($('#name').val() == null || $('#name').val() == '' || $('#name').val().length < 3) {
            $('#name').addClass('invalid');
            return;
        }
        if (!validateEmail($('#email').val())) {
            $('#email').addClass('invalid');
            return;
        }
        if (!isValidPhone($('#phone').val())) {
            $('#phone').addClass('invalid');
            return;
        }
        if ($('#msg').val() == null || $('#msg').val() == '' || $('#msg').val().length < 10) {
            $('#msg').addClass('invalid');
            return;
        }
        $('#submit').html('در حال ارسال ...');
        $.ajax({
            url: $('#contact-form').attr('action'),
            data: $('#contact-form').serialize(),
            type: 'post'
        }).success(function (data) {
            if (data != 'ERROR') {
                $('#contact-form')[0].reset();
                $('#submit').html('ارسال موفق').addClass('success');
                setTimeout(function () {
                    $('#submit').removeClass('success').html('ارسال');
                }, 5000);
            } else {
                $('#contact-form')[0].reset();
                $('#submit').html('ارسال ناموفق').addClass('invalid');
                setTimeout(function () {
                    $('#submit').removeClass('invalid').html('ارسال');
                }, 5000);
            }
        });
    });
    function validateEmail(email) {
        var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }
    function isValidPhone(phone) {
        var p = /^[0]?\d{10}$/i;
        return p.test(phone);
    }
    ;
    $(document).on('click', '.menuTop', function () {
        $('.menuTop').removeClass('active');
        $(this).addClass('active');
    });
});


$(document).ready(function () {
    $(document).on('submit', '#login', function (e) {
        e.preventDefault();
        $('#message').html('');
        form = $(this);
        $('#username , #password').removeClass('invalid');
        if (!$('#username').val() || !$('#username').val().length || $('#username').val().length < 3) {
            $('#username').addClass('invalid');
            $('#message').html('اطلاعات وارد شده نامعتبر است.');
            return;
        }
        if (!$('#password').val() || !$('#password').val().length || $('#password').val().length < 3) {
            $('#password').addClass('invalid');
            $('#message').html('اطلاعات وارد شده نامعتبر است.');
            return;
        }
        $(this).find('.input').prop('disabled', true);
        var data = [];
        data = {
            "_csrf_token": $('#_csrf_token').val(),
            "_username": $('#username').val(),
            "_password": $('#password').val(),
            "_remember_me": $('remember_me').val()
        };
        $.ajax(
                {
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: data,
                    dataType: 'json'
                }).success(function (response) {
            console.log(response)
            if (response.success) {
                $('#message').html('ورود موفق: لطفا منتظر بمانید ...');
                if (response.role == 'ROLE_USER') {
                    window.location = BaseUrl + '/cli_panel/';
                } else if (response.role == 'ROLE_DOCTOR') {
                    window.location = BaseUrl + '/doc_panel/';
                } else {
                    window.location = BaseUrl + '/admin/';
                }
            } else {
                $('#message').html(response.message);
                $(form).find('.input').prop('disabled', false);
            }
        });
    });
});
