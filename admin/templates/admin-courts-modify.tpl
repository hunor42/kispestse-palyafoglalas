{include file="header.tpl"}

{include file="admin-header.tpl"}

<div class="container-fluid main-container" >
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h2>Pályák <small>({if $smarty.get.a2id=="new"}Új pálya létrehozása{else}Pálya módosítása{/if})</small></h2>
            <hr>

            <div id="statusbar" class="alert alert-danger" role="alert"></div>

            <form class="clearfix form-horizontal" method="post" id="court_form">
                <div class="form-group">
                    <label for="name" class="col-md-4 control-label">Név</label>
                    <div class="col-md-8">
                        <input type="text" name="name" value="{$court->name}" placeholder="Név" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="subtitle" class="col-md-4 control-label">Leírás</label>
                    <div class="col-md-8">
                        <input type="text" name="subtitle" value="{$court->subtitle}" placeholder="Leírás" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="price" class="col-md-4 control-label">Alapértelmezett ár</label>
                    <div class="col-md-3">
                        <input type="text" name="price" value="{$court->price}" placeholder="Ár" class="form-control text-right" />
                    </div>
                </div>
				<input type="button" id="sbm" value="Mentés" class="btn btn-success pull-right"/>
            </form>
        </div>
    </div>
</div>



<script type="text/javascript">
	{literal}
	$("#sbm").click(function(){
		var data = $("#court_form").serialize();
		$.ajax({
			type: "POST",
			url: "/admin/courts/{/literal}{$smarty.get.a2id}{literal}/?did={/literal}{$smarty.get.did}{literal}",
			data: data,
			success: function(resp){
				if(resp=="ok"){
					location.href='/admin/courts/';
					$("#statusbar").hide();
				}else{
					$("#statusbar").show().html(resp);
				}
			}
		});
	});
	{/literal}
</script>


{include file="footer.tpl"}
