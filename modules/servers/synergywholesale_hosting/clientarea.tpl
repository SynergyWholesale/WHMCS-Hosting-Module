 <div align="left">
    <table width="100%" cellspacing="1" cellpadding="0" class="">
    <thead>
        <tr role="row"><th rowspan="1" colspan="2" style="width: 0px;">Hosting Details:</th></tr>
    </thead>
        <tr><td>
                <table width="100%" border="0" cellpadding="2" cellspacing="2" class="table table-striped">
                    <tr><td class="fieldarea">Status:</td><td>{$status} {if $status eq 'Active'}<i style="color:green" class="fa fa-check"></i>{/if}</td></tr>
                    <tr><td class="fieldarea">Server:</td><td>{$server}</td></tr>
                    <tr><td class="fieldarea">Plan:</td><td>{$plan}</td></tr>
                    <tr><td class="fieldarea">Dedicated IP:</td><td>{$dedicatedIP}</td></tr>
                    <tr><td class="fieldarea">Server IP:</td><td>{$serverIP}</td></tr>
                </table>
            </td></tr>
    </table>
</div>
<style>
.fieldarea {
    font-weight: bold;
    width: 40%;
}
</style>