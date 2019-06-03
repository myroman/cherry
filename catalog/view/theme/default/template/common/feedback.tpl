<div class="ctl-feedback">
    <div class="feedback-header">
        <h2 class="subheadline"><?php echo $heading_title; ?></h2>
    </div>

    <div class="feedback-form">
        <form>
            <div>
                <input type="text" name="fullname" placeholder="Имя*" />
            </div>

            <div>
                <input type="email" name="email" placeholder="E-mail*" />
            </div>

            <div>
                <input type="tel" name="phone" placeholder="Телефон" />
            </div>

            <div>
                <textarea name="message" maxlength="1000" placeholder="Сообщение*" rows="5"></textarea>
            </div>

            <div class="submit-button-wrapper">
                <button type="button" id="btnSendFeedback" class="btn btn-default btn-med btn-green">Отправить</button>
            </div>
        </form>
    </div>

    <div class="feedback-sent" style="display: none; ">
        <p>
            Спасибо за ваше сообщение. Мы свяжемся с вами в ближайшее время.
        </p>
    </div>
</div>

<script type="text/javascript">
    $(document).delegate('#btnSendFeedback', 'click', function() {
        $.ajax({
            url: 'index.php?route=common/feedback/send',
            method: 'post',
            data: $('.feedback-form :input'),
            dataType: 'json',
            contentType: false,
            beforeSend: function() {
                $('#btnSendFeedback').button('loading');
            },
            complete: function() {
                $('#btnSendFeedback').button('reset');
            },
            success: function(json) {
                console.log('success feedback');
                $('.alert, .text-danger').remove();
                $('.form-group').removeClass('has-error');

                $('.feedback-form').hide();
                $('.feedback-sent').show();                               
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log('error feedback');
            }
        });
    });
</script>