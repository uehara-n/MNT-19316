//初期表示時の処理
$(document).ready(function(){
  //ajaxstartに'show'という文字列を渡す
  ajaxstart('show');
});

//いいねボタンがクリックされた場合の処理
$(function(){
   //class名:"iine-bnt"がクリックされたら処理する
  jQuery(".iine_btn").click(function(){
     //ajaxstartに'plus'という文字列を渡す
    ajaxstart('plus');
  });
});

function ajaxstart(act) {
   //Ajax通信処理
    $.ajax({
      type:"POST",
      uel:"https://www.enlarge.tokyo/iine_btn.php?act="+act
    })
    .done(function(date){
        //返り値がある場合のみ処理
        if(date.length > 0){
          //返り値を指定class名:"iine-cnt"の値に差し込む
          $(".line-cnt").text("→"+date);
        }
    });
}
