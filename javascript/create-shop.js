$(document).ready(function() {
    // 檢查帳號
    $('#account').on('change', function() {
        var account = $('#account').val();
        if(account != '') {
            $.ajax({
                url: '../api/doCheck_account.php',
                type: 'post',
                data: {account: account},
                success: function(response) {
                    if(response == 'exists') {
                        $('#account').addClass('is-invalid').removeClass('is-valid');
                        $('#accountFeedback').text('此帳號已存在').removeClass('valid-feedback').addClass('invalid-feedback').show();
                    } else {
                        $('#account').removeClass('is-invalid').addClass('is-valid');
                        $('#accountFeedback').text('此帳號可以使用').removeClass('invalid-feedback').addClass('valid-feedback').show();
                    }
                }
            });
        } else {
            $('#account').addClass('is-invalid').removeClass('is-valid');
            $('#accountFeedback').text('請輸入帳號').removeClass('valid-feedback').addClass('invalid-feedback').show();
        }
    });

    // 表單提交檢查
    $('form').on('submit', function(e) {
        if($('#account').hasClass('is-invalid')) {
            e.preventDefault();
            alert('請確保帳號可以使用');
        }
    });

    // 文件上傳按鈕點擊事件
    $('#uploadButton').on('click', function() {
        $('#shopImage').click();
    });

    // 文件選擇改變事件
    $('#shopImage').on('change', function(e) {
        var file = e.target.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').attr('src', e.target.result).show();
            }
            reader.readAsDataURL(file);
        }
    });
});