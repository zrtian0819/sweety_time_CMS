<!-- 引入jquery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>


<script>
    // 控制side-bar開關
    $("#sideBarController").click(function() {
        $("#sideBar").toggleClass("sideBarToggle");
    })

    $(document).ready(function() {
        function checkWidth() {
            if ($(window).width() > 992) {
                $('#sideBar').removeClass('sideBarToggle');
            } else {
                $('#sideBar').addClass('sideBarToggle');
            }
        }

        // 初次加載時檢查一次寬度
        checkWidth();

        // 當視窗大小改變時，執行檢查
        $(window).resize(checkWidth);
    });

    //控制side-bar內按鈕的收合
    $("#adminBtn").click(function(){
        $("#adminList").toggleClass("adminToggle")
    })

    $("#storeBtn").click(function(){
        $("#storeList").toggleClass("storeToggle")
    })

</script>