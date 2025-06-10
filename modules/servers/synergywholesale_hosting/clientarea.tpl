{if $product eq 'Custom Hosting'}
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
{elseif $product eq 'Email Hosting'}
<div align="left">
    <table width="100%" cellspacing="1" cellpadding="0" class="">
    <thead>
        <tr role="row"><th rowspan="1" colspan="2" style="width: 0px;">Email Hosting Details:</th></tr>
    </thead>
        <tr><td>
                <table width="100%" border="0" cellpadding="2" cellspacing="2" class="table table-striped">
                    <tr><td class="fieldarea">Status:</td><td>{$status} {if $status eq 'Active'}<i style="color:green" class="fa fa-check"></i>{/if}</td></tr>
                    <tr><td class="fieldarea">Plan:</td><td>{$plan}</td></tr>
                </table>
            </td></tr>
    </table>
     <table width="100%" cellspacing="1" cellpadding="0" class="">
    <thead>
        <tr role="row"><th rowspan="1" colspan="2" style="width: 0px;">Server Details:</th></tr>
    </thead>
        <tr><td>
                <table width="100%" border="0" cellpadding="2" cellspacing="2" class="table table-striped">
                    <tr><td class="fieldarea">Webmail:</td><td class="sw-values">ax.email</td></tr>
                    <tr><td class="fieldarea">Incoming Mail Server:</td><td class="sw-values">ax.email</td></tr>
                    <tr><td class="fieldarea">Outgoing Mail Server:</td><td class="sw-values">ax.email</td></tr>
                </table>
            </td></tr>
    </table>
     <table width="100%" cellspacing="1" cellpadding="0" class="">
    <thead>
        <tr><th style="width: 45%;">Ports:</th><th style="text-align: right;">Secure</th><th style="text-align: right;">Unsecure</th></tr>
    </thead>
        <tr>
            <table width="100%" border="0" cellpadding="3" cellspacing="2" class="table table-striped">
                <tr><td class="fieldarea">Incoming POP3 Port:</td><td class="sw-values">995</td><td class="sw-values">110</td></tr>
                <tr><td class="fieldarea">Incoming IMAP Mail Port:</td><td class="sw-values">993</td><td class="sw-values">143</td></tr>
                <tr><td class="fieldarea">Outgoing SMTP Port:</td><td class="sw-values">465</td><td class="sw-values">587</td></tr>
            </table>
        </tr>
    </table>
    <table width="100%" cellspacing="1" cellpadding="0" class="">
    <thead>
        <tr role="row"><th rowspan="1" colspan="2" style="width: 0px;">MX Records:</th></tr>
    </thead>
        <tr><td>
                <table width="100%" border="0" cellpadding="2" cellspacing="2" class="table table-striped">
                    {foreach from=$mxrecords key=key item=item }
                        <tr><td class="fieldarea">{$key}</td><td class="sw-values">(Priority {$item})</td></tr>   
                    {/foreach}
                </table>
            </td></tr>
    </table>
    <table width="100%" cellspacing="1" cellpadding="0" class="">
    <thead>
        <tr role="row"><th rowspan="1" colspan="2" style="width: 0px;">SPF Record:</th></tr>
    </thead>
        <tr><td>
                <table width="100%" border="0" cellpadding="2" cellspacing="2" class="table table-striped">
                    <tr><td class="fieldarea">Record:</td><td class="sw-values">v=spf1 +a +mx +include:spf.ax.email ~all</td></tr>
                </table>
            </td></tr>
    </table>
    <table width="100%" cellspacing="1" cellpadding="0" class="">
    <thead>
        <tr role="row"><th rowspan="1" colspan="2" style="width: 0px;">DKIM Record:</th></tr>
    </thead>
        <tr><td>
                <table width="100%" border="0" cellpadding="2" cellspacing="2" class="table table-striped">
                    <tr><td class="fieldarea">Hostname:</td><td class="sw-values">axigen._domainkey.{$domain}</td></tr>
                    <tr><td class="fieldarea">Content (TXT):</td><td class="sw-values">v=DKIM1; k=rsa; p={$dkim};</td></tr>
                </table>
            </td></tr>
    </table>
</div>
<style>
table {
    margin: 20px 0px 20px 0px;
}
.fieldarea {
    font-weight: bold;
    width: 35%;
}
.sw-values {
    max-width: 40%;
    word-break: break-all;
    text-align: right;
}
</style>
{/if}