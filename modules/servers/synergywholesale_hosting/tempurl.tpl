<style>
    .table-striped>tbody>tr:nth-child(odd)>td, 
    .table-striped>tbody>tr:nth-child(odd)>th {
       background-color: #EBEBEB;
     }
    .tblTr {
        width: 584px;
        height: 47px;
    }
    .fieldarea {
    font-weight: bold;
    width: 40%;
    }
    .btnPadding{
    padding-left: 30px;
    padding-right: 30px;
    }
</style>


{if $tempUrl eq true}
<div class="alert alert-info">
  <strong>IMPORTANT INFORMATION</strong><hr> 
    The temporary URL will automatically be disabled after 28 days. You will need to re-enable it again once 28 days have elapsed.
</div> 
{/if}

        {if $message neq ''}
            <div class="alert alert-success alert-dismissable">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <strong>{$message}</strong>
            </div>
        {/if}  

<div><h3>Manage Temporary URL</h3></div>
 <div align="left">
    <table width="80%" cellspacing="1" cellpadding="0">
        <tr><td>
            <form method="post">
                {if $tempUrl eq false}
                    <table width="100%" border="0" cellpadding="2" cellspacing="2">
                        <h5>Enabling Temporary URL will generate a URL that will remain active for 28 days.</h5>
                        <tr>
                            <input type="hidden" name="id" value="{$serviceid}" />
                            <input type="hidden" name="modop" value="custom" />
                            <input type="hidden" name="a" value="tempurl" />
                            <input type="hidden" name="module_function" value="toggle" />
                            <td style="padding-top: 20px;"><input type="submit" class="btn btn-primary" name="btnEnable" value="Enable Temporary URL"/></td>
                        </tr>
                   </table>
                {/if}
               </form>
                <form method="post">
                {if $tempUrl eq true}
                <h5>The URL below will give you temporary access to your web hosting service.</h5>
                    <table width="100%" border="0" cellpadding="2" cellspacing="2" class="table table-striped">
                       
                        <tr class="tblTr">
                            <td class="fieldarea">Domain Name:</td>
                            <td>{$domain}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="fieldarea">Temporary URL Address:</td>
                            <td><a target="_blank" href="{$tempUrlAddress}">{$tempUrlAddress}</a></td>
                            <td></td>
                        </tr>
                        <tr class="tblTr">
                            <td class="fieldarea">Days Remaning</td>
                            <td>{$daysRemain}</td>
                            <input type="hidden" name="id" value="{$serviceid}" />
                            <input type="hidden" name="modop" value="custom" />
                            <input type="hidden" name="a" value="tempurl" />
                            <input type="hidden" name="module_function" value="reset" />                            
                            <td><input type="submit" class="btn btn-primary btnPadding" value="Reset" /></td>
                        </tr>
                    </table>
                </form>
                    <form method="post">
                    <input type="hidden" name="id" value="{$serviceid}" />
                    <input type="hidden" name="modop" value="custom" />
                    <input type="hidden" name="a" value="tempurl" />
                    <input type="hidden" name="module_function" value="toggle" />
                    <input type="submit" class="btn btn-primary" name="btnDisable" value="Disable Temporary URL"/></td>
                {/if}
            </form> 
            </td></tr>
    </table>
</div>