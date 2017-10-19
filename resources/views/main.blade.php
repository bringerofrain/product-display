@extends('layout')
@section('content')
<div>
    <h2>Products</h2>
    <div class="input-group" style="float:right;width:380px;">
      <input style="" type="text" id="inpsearch" class="form-control" placeholder="Search for..." aria-label="Search for...">
      <span class="input-group-btn">
        <button class="btn btn-secondary" type="button" id="search">Go!</button>
      </span>
    </div>
    <div class="cb"></div>
    <div id="layout" class="layout">
    </div>

    <script>
        $(()=>{

            $("#layout").ready(function(){
                $.get('/initialload',{csrf:$('meta[name="csrf-token"]').attr("content")},(retdata)=>{
                    $("#layout").html(retdata);
                });
            });
            $("#search").click(event=>{
                event.preventDefault();
                $.post('/search',{'_token':$('meta[name="csrf-token"]').attr("content"),'search':$("#inpsearch").val()},retdata=>{
                    console.log(retdata);
                    $("#layout").html(retdata);
                });

            });
            $("#inpsearch").keyup((event)=>{
                if (event.keyCode == 13) {
                    $("#search").click();
                }
            });
        });
    </script>
</div>



@stop
