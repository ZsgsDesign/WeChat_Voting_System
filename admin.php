<?php
    require_once("conn.php");
    if(isset($_SESSION["admin"])){
        redirect("stat.php");
    }
?>
<script src="https://static.1cf.co/js/jquery-3.2.1.min.js"></script>
<script type="text/javascript">
window.addEventListener("load",function() {
    var pass=prompt("管理员密码","");
    if (pass!=null && pass!=""){
        $.post("ajax.php",{
            action: "admin",
            pass: pass
        },function(result){
            if(result.ret==200){
                location.href="stat.php";
            }else{
                alert(result.desc);
                location.href="index.php";
            }
        });
    }
}, false);
</script>