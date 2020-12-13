<style>
    .table-striped>tbody>tr:nth-child(odd)>td, 
    .table-striped>tbody>tr:nth-child(odd)>th {
       background-color: #EBEBEB;
     }
    .tblTr {
        opacity: 1;
        padding: 0px;
        border-width: 0px;
        border-radius: 0px;
        border-color: rgb(102, 102, 102);
        border-style: solid;
        width: 584px;
        height: 47px;
    }
    .btnBar {
        margin: -7px;
        float: right;
    }
    .fieldarea {
    font-weight: bold;
    width: 40%;
    }
</style>

<div><h3>Check Firewall</h3></div>

 <div align="left">
    <form method="post">
        {if $blocked eq true}
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <strong>The IP Address {$ipAddress} is blocked!</strong>
                <input type="hidden" name="id" value="{$serviceid}" />
                <input type="hidden" name="modop" value="custom" />
                <input type="hidden" name="a" value="firewall" />
                <input type="hidden" name="module_function" value="unblock_ip" />
                <input type="hidden" name="customip" value="{$ipAddress}" />
                <input type="hidden" name="ipopt" value="custom" />
                <td><input type="submit" class="btn btn-primary btnBar" name="btnUnblock" value="Unblock IP Address"/></td>     
            </div>
        {/if}
    </form>
        {if $message neq ''}
            <div class="alert alert-success alert-dismissable">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <strong>{$message}</strong>
            </div>
        {/if}          
    <form method="post">
        <table width="65%" cellspacing="1" cellpadding="0">
             <tr><td>         
                <table width="100%" style="margin-bottom: 7px;">
                <tr>
                    <td colspan="1"><strong>Check IP Address</strong></td>
                    <td><strong>IP Address</strong></td>
                </tr>
                </table>
                <table width="100%" border="0" cellpadding="2" cellspacing="2" class="table-striped">

                    <tr class="tblTr">
                        <td class="fieldarea" style="padding-left: 10px;">
                            <input type="radio" name="ipopt" id="ipopt2" name="myIp" value="myIp" checked/>                        
                            <label for="ipopt2">My IP Address</label>                    
                            <input type="hidden" name="myIpAddress" value="{$smarty.server.REMOTE_ADDR}" />
                        </td>
                        <td><center><strong>{$smarty.server.REMOTE_ADDR}</strong></center></td>
                    </tr>

                    <tr>
                        <td class="fieldarea" style="padding-left: 10px;">
                            <input type="radio" name="ipopt" id="ipopt1" value="custom"/>
                            <label for="ipopt1">Check Custom IP Address</label>
                        </td>
                        <td style="padding: 10px;">
                            <input class="form-control" type="text" name="customip" placeholder="Enter IP Address. eg: 155.55.55.55" />
                        </td>
                    </tr>
                    </table>
                        <input type="hidden" name="id" value="{$serviceid}" />
                        <input type="hidden" name="modop" value="custom" />
                        <input type="hidden" name="a" value="firewall" />
                        <input type="hidden" name="module_function" value="check" />
                        <input type="submit" class="btn btn-primary" name="btnCheck" value="Check IP Address"/> 
                </td>
            </tr>
        </table>
    </form>
</div>